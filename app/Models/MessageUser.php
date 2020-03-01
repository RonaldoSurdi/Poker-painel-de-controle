<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageUser extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function msg(){
        return $this->hasOne(Message::class,'id','message_id');
    }
    public function user(){
        return $this->hasOne( UserApp::class,'id','user_app_id');
    }
}
