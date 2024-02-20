<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
      protected $table = 'users';
     public $timestamps = false;

static function cekUser($data=array()){
  $cek = Login::where($data)->first();
  if(!empty($cek)){
    return $cek;
  }else {
    return null;
  }
}
function get_detail_user($id){
    return Login::where('id',$id)->first();
}
}
