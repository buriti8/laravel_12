<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

trait CreatedUpdatedByTrait
{
    public static function bootCreatedUpdatedByTrait(): void
    {
        static::creating(function (Model $model) {
            $model->created_by_id = getImpersonateUser();
            $model->updated_by_id = getImpersonateUser();
        });

        static::updating(function (Model $model) {
            $model->updated_by_id = getImpersonateUser();
        });

        static::deleting(function (Model $model) {
            if (in_array(SoftDeletes::class, class_uses($model))) {
                $model->updated_by_id = getImpersonateUser();
                $model->save();
            }
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
