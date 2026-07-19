<?php

namespace App\Audit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{
    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_BOOL = 'bool';
    const DATA_TYPE_DATE = 'date';
    const DATA_TYPE_TIME = 'time';
    const DATA_TYPE_DATE_TIME = 'datetime';
    const DATA_TYPE_ID = 'id';
    const DATA_TYPE_FLOAT = 'float';
    const DATA_TYPE_STATUS = 'status';

    /**
     * @var IsAuditable
     */
    private $model;

    /**
     * @param IsAuditable $model
     * @return Audit
     */
    public static function modelInstance(IsAuditable $model)
    {
        $i = self::tableInstace($model->getAuditTable());
        $i->parent_id = $model->id;
        $i->model = $model;
        return $i;
    }

    /**
     * @param string $table
     * @return Audit
     */
    public static function tableInstace(string $table)
    {
        $i = new self();
        $i->setTable($table);
        return $i;
    }

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'created_by',
        'field_name',
        'data_type',
        'before_value',
        'after_value'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * @param $value
     */
    public function setDataTypeAttribute($value)
    {
        $this->attributes['data_type'] = $value === self::DATA_TYPE_ID ? self::DATA_TYPE_STRING : $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function getBeforeValueAttribute($value)
    {
        return self::formatValue($value, $this->data_type);
    }

    /**
     * @param $value
     * @return string
     */
    public function getAfterValueAttribute($value)
    {
        return self::formatValue($value, $this->data_type);
    }

    /**
     * @param $field
     * @param $field_key
     * @return array|object
     */
    public static function normalizeField($field, $field_key)
    {
        $normalizedField = [];
        $aliasName = null;
        if (strpos($field, '_alias') !== false) {
            $aliasName = $field;
            $field = str_replace('_alias', '', $field);
        }
        if (strpos($field_key, '_alias') !== false) {
            $aliasName = $field_key;
            $field_key = str_replace('_alias', '', $field_key);
        }
        if (is_int($field_key)) {
            if (is_string($field)) {
                $normalizedField = [
                    'name' => $field,
                    'type' => Audit::DATA_TYPE_STRING
                ];
            } else if (is_array($field)) {
                $normalizedField = $field;
            }
        } else {
            if (strpos($field, ':') !== false) {
                [$field, $extra] = explode(':', $field, 2);
            }

            $normalizedField = [
                'name' => $field_key,
                'type' => $field,
                'extra' => $extra ?? null
            ];
        }
        $normalizedField['alias_name'] = $aliasName;

        return (object)$normalizedField;
    }

    /**
     * @param mixed $value
     * @param object $field
     * @return string
     */
    public static function formatDbValue($value, $field): string
    {
        switch ($field->type) {
            case self::DATA_TYPE_DATE:
                if ($value instanceof Carbon) {
                    return $value->toDateString();
                }
                break;
            case self::DATA_TYPE_DATE_TIME:
                if ($value instanceof Carbon) {
                    return $value->toDateTimeString();
                }
                break;
            case self::DATA_TYPE_TIME:
                if ($value instanceof Carbon) {
                    return $value->toTimeString();
                }
                break;
            case self::DATA_TYPE_ID:
                if ($value) {
                    list($table, $fields) = explode(',', $field->extra ?? '');
                    if (is_subclass_of($table, Model::class)) {
                        $model = (new \ReflectionClass($table))->newInstanceWithoutConstructor();
                        if ($model && $record = $model->find($value)) {
                            $value = $record->{$fields} ?? '';
                        }
                    } else {
                        if ($table && $fields && $record = DB::table($table)->where('id', $value)->select([$fields])->first()) {
                            $value = $record->{$fields} ?? '';
                        }
                    }
                }
                break;
            case self::DATA_TYPE_FLOAT:
                if ($value) {
                    $value = getDecimalFormat($value);
                }
                break;
        }

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        return (string) $value;
    }

    /**
     * @param string $value
     * @param string $type
     * @return string
     */
    public static function formatValue(string $value, string $type): string
    {
        switch ($type) {
            case self::DATA_TYPE_DATE:
                if ($value && $date = parse_date($value, 'Y-m-d')) {
                    return format_date($date);
                }
                break;
            case self::DATA_TYPE_DATE_TIME:
                if ($value && $date = parse_date($value, 'Y-m-d H:i:s')) {
                    return format_date_time($date);
                }
                break;
            case self::DATA_TYPE_TIME:
                if ($value && $date = parse_time($value, 'H:i:s')) {
                    return format_time($date);
                }
                break;
            case self::DATA_TYPE_BOOL:
                if ((bool)$value) {
                    return __('base_lang.yes');
                } else {
                    return __('base_lang.no');
                }
                break;
            case self::DATA_TYPE_STATUS:
                if ((bool)$value) {
                    return __('base_lang.active');
                } else {
                    return __('base_lang.inactive');
                }
                break;
        }

        return $value;
    }

    public static function insertAuditRow(IsAuditable $model, $field, $before_value, $after_value)
    {
        DB::beginTransaction();

        try {
            $audit = self::modelInstance($model);
            $audit->created_by = Auth::user()->id ?? 1;
            $audit->field_name = $field->alias_name ?? $field->name ?? 'unknown';
            $audit->data_type = $field->type ?? 'string';
            $audit->before_value = self::formatDbValue($before_value, $field);
            $audit->after_value = self::formatDbValue($after_value, $field);
            $audit->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
