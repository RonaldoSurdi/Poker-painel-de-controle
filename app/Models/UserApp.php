<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApp extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
    protected $table = 'user_app';
}
