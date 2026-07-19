<?php

namespace App\Models;

use App\Traits\CustomAttributesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleBase extends Role
{
    use CustomAttributesTrait;

    protected $upper_fields = [
        'name',
        'description'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'video_url'
    ];

    /**
     * @param $query
     * @param $name
     * @return Builder
     */
    public function scopeName(Builder $query, $name)
    {
        if (trim($name)) {
            $query->where('name', "LIKE", "%$name%");
        }

        return $query;
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeAdmin(Builder $query)
    {
        $query->where('name', '!=', 'Administrador');
        return $query;
    }

    public function getIsNotAdminAttribute()
    {
        return $this->name !== Str::upper(User::ADMINISTRADOR);
    }
}
