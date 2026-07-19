<?php

namespace App\Audit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait HasAudit
{
    /**
     * @return string
     */
    public function getAuditTable(): string
    {
        return $this->getTable() . '_audit';
    }

    /**
     * @return string
     */
    public function auditLangFile(): string
    {
        return $this->getTable();
    }

    /**
     * @return array
     */
    public function getAuditFields(): array
    {
        if(isset($this->audit_fields) && is_array($this->audit_fields)) {
            return collect($this->audit_fields)->map(function($fieldName, $fieldKey) {
                $field = Audit::normalizeField($fieldName, $fieldKey);
                $field->label = __($this->auditLangFile().'.'.$field->name);
                return $field;
            })->values()->toArray();
        }
        return [];
    }

    /**
     * @return HasMany
     */
    final public function audits(): ?HasMany
    {
        if ($this instanceof IsAuditable && $this instanceof Model) {
            $instance = Audit::modelInstance($this);
            return (new HasMany(
                $instance->newQuery(),
                $this,
                $instance->getTable() . '.parent_id',
                'id'
            ))->latest('created_at')
                ->whereColumn('before_value', '<>', 'after_value')
                ->when(method_exists($this, 'getNoShowAudit') && !empty($this->getNoShowAudit()), function ($query) {
                    $query->whereNotIn('field_name', $this->getNoShowAudit());
                });
        }

        return null;
    }

    /**
     * @return bool
     */
    final public function auditTableExists(): bool
    {
        return Schema::hasTable($this->getAuditTable());
    }

    /**
     * @return void
     */
    final public function createAuditTable()
    {
        Schema::create($this->getAuditTable(), function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('created_by');
            $table->string('field_name', 100);
            $table->string('data_type', 100)->nullable();
            $table->text('before_value')->nullable();
            $table->text('after_value')->nullable();

            $table->foreign('parent_id')
                ->references($this->getKeyName())->on($this->getTable())
                ->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * @return void
     */
    public function dropAuditTable()
    {
        Schema::dropIfExists($this->getAuditTable());
    }

    public function auditIntermediateTable($relation, array $newData, string $modelClass)
    {
        // Obtener datos anteriores
        $before = $this->{$relation}()->get()->toArray();
        $after = $newData;

        if (empty($before)) {
            return;
        }

        // Modelo intermedia para obtener campos de auditoría
        $model = new $modelClass();
        $auditFields = $model->getAuditFields();

        // Recorrer cada elemento
        foreach ($after as $index => $newItem) {
            $oldItem = $before[$index] ?? [];

            foreach ($auditFields as $field) {
                $oldValue = $oldItem[$field->name] ?? null;
                $newValue = $newItem[$field->name] ?? null;

                // Limpiar valores float
                if ($field->type == 'float') {
                    $newValue = str_replace(config('app.number_grp', ','), '', $newValue);
                    $oldValue = str_replace(config('app.number_grp', ','), '', $oldValue);
                }

                // Ignorar si ambos valores están vacíos
                if (empty($oldValue) && empty($newValue)) {
                    continue;
                }

                if ($oldValue != $newValue) {
                    Audit::insertAuditRow($this, $field, $oldValue, $newValue);
                }
            }
        }

        // Auditoría para elementos eliminados
        if (count($before) > count($after)) {
            for ($i = count($after); $i < count($before); $i++) {
                $this->auditRemovedItem($before[$i], $auditFields);
            }
        }
    }

    /**
     * Auditoría para elementos removidos
     */
    protected function auditRemovedItem($removedItem, $auditFields)
    {
        foreach ($auditFields as $field) {
            $oldValue = $removedItem[$field->name] ?? null;
            Audit::insertAuditRow($this, $field, $oldValue, null);
        }
    }
}
