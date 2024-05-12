<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    public $incrementing = false;
    protected $keyType = 'string';

    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function belongs_to_materials_via_submissions()
    {
        return $this->belongsToMany(Material::class, 'submissions', 'user_id', 'material_id');
    }

    public function belongs_to_classes_via_user_class()
    {
        return $this->belongsToMany(Classes::class, 'user_class', 'user_id', 'class_id');
    }
}
