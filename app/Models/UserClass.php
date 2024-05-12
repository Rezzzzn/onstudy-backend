<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    use HasFactory;

    protected $table = 'user_class';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
}
