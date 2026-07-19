<?php

namespace App\Observers;

use App\Audit\IsAuditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Audit\Audit;

class AuditableObserver
{
    /**
     * @param IsAuditable $model
     */
    public function updating(IsAuditable $model)
    {
        if ($model instanceof Model) {
            collect($model->getAuditFields())->each(function ($field) use ($model) {
                $originalValue = $model->getOriginal($field->name);
                $updatedValue = $model->getAttributeValue($field->name);

                if ($model->isDirty($field->name) && $this->validateValueType($originalValue, $updatedValue, $field->type)) {
                    Audit::insertAuditRow(
                        $model,
                        $field,
                        $originalValue,
                        $updatedValue
                    );
                }
            });
        }
    }

    public function updated(IsAuditable $model)
    {
        if (!$model->audited) {
            $model->audited = true;
        }
    }

    public function deleted(IsAuditable $model)
    {
        collect($model->getAuditFields())->each(function ($field) use ($model) {
            if ($field->name === 'deleted_at'
                && $this->validateValueType($model->getOriginal($field->name), $model->getAttributeValue($field->name), $field->type)) {
                Audit::insertAuditRow(
                    $model,
                    $field,
                    '',
                    $model->getAttributeValue($field->name)
                );
            }
        });
    }

    public function validateValueType($original, $value, $type)
    {
        if (filled($value)) {
            switch ($type) {
                case 'date':
                    $value = ($value instanceof Carbon) ? $value->toDateString() : $value;
                    break;
                case 'datetime':
                    $value = ($value instanceof Carbon) ? $value->toDateTimeString() : $value;
                    break;
                case 'float':
                    $value = formatFloatValue($value);
                    break;
                case 'status':
                    $original = (bool) $original;
                    $value = (bool) $value;
                break;
            }
        }

        return $original !== $value;
    }
}
