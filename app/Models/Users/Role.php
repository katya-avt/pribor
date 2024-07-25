<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'roles';
    protected $guarded = false;

    const ADMIN = 1;
    const ENGINEERING_DEPARTMENT_OFFICER = 2;
    const ECONOMIC_DEPARTMENT_OFFICER = 3;
    const PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER = 4;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public static function getRoleIdByRoleName(string $name)
    {
        return self::query()->where('name', '=', $name)->first()->id;
    }
}
