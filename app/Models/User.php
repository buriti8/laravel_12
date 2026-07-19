<?php

namespace App\Models;

use App\Audit\Audit;
use App\Audit\HasAudit;
use App\Audit\IsAuditable;
use App\Traits\CustomAttributesTrait;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements /* HasOneSignalDevicesInterface, */ IsAuditable
{
    use Notifiable, /* HasApiTokens, */ CustomAttributesTrait, CreatedUpdatedByTrait, HasRoles, Impersonate, /* HasOneSignalDevices, */ SoftDeletes, HasAudit;

    const ADMINISTRADOR = 'Administrador';

    protected $table = 'users';
    protected $perPage = 30;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'remember_token',
        'last_login',
        'last_logout',
        'ip_address',
        'photo',
        'status',
        'is_admin',
    ];

    protected $upper_fields = [
        'name',
        'username',
        'email',
    ];

    protected $no_double_spaces = [
        'name',
        'username',
        'email',
    ];

    protected $dates = [
        'last_login',
        'last_logout',
    ];

    protected $audit_fields = [
        'name',
        'email',
        'username',
        'status' => Audit::DATA_TYPE_STATUS,
    ];

    protected $file_fields = [
        'photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string
     */
    protected $guard_name = "web";

    /**
     * @return bool
     */
    public static function isAdmin()
    {
        return Auth::user() && Auth::user()->is_admin;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(?string $password): bool
    {
        return $password && Hash::check($password, $this->password);
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->is_admin;
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        // El superadmin nunca puede ser impersonado
        if ($this->is_super_admin) {
            return false;
        }

        // Si hay un usuario autenticado (el que intenta impersonar)
        if (auth()->check()) {
            $impersonator = auth()->user();

            // Si quien impersona NO es superadmin y el objetivo es admin no puede ser impersonado
            if (!$impersonator->is_super_admin && $this->is_admin) {
                return false;
            }
        }

        // En cualquier otro caso, puede ser impersonado
        return true;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeStatus(Builder $query)
    {
        return $query->where('status', 1);
    }

    /**
     * @param Builder $query
     * @param array $search
     * @return Builder
     */
    public function scopeSearch(Builder $builder, array $search)
    {
        foreach ($search as $column => $value) {
            switch ($column) {
                case 'name_user':
                    if ($value) {
                        $builder->where(function ($query) use ($value) {
                            $query->where("name", "LIKE", "%{$value}%")
                                ->orwhere("username", "LIKE", "%{$value}%");
                        });
                    }
                    break;
                case 'email':
                    if ($value) {
                        $builder->where("email", "LIKE", "%{$value}%");
                    }
                    break;
                case 'role':
                    if ($value) {
                        $builder->whereHas('roles', function (Builder $query) use ($value) {
                            return $query->where('role_id', $value);
                        });
                    }
                    break;
                case 'status':
                    if ($value !== null) {
                        $builder->where('status', $value);
                    }
                    break;
            }
        }

        return $builder->orderBy('is_admin', 'DESC')->orderBy('name', 'ASC');
    }

    /**
     * @return string
     */
    public function getFirstNameAttribute()
    {
        return explode(' ', trim($this->name))[0] ?? '';
    }

    /**
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->name . ' - ' . $this->getRoleNames()->implode(', ');
    }

    /**
     * @return string|null
     */
    public function getLastIpAttribute()
    {
        return $this->sessions()->first()->ip_address ?? null;
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute(?string $value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getFullNameRoleAttribute()
    {
        return $this->getRoleNames()->implode("\n");
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->id === 1;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id');
    }
}
