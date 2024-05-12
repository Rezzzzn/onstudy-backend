<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function has_many_materials()
    {
        return $this->hasMany(Material::class);
    }
}
