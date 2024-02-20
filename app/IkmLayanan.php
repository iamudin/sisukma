<?php
namespace App;
use Illuminate\Http\Request;
use View;
use PDF;
use Str;
use DB;
class IkmLayanan
{

    function periode(){
        if(request()->year){
            View::share('periode',request()->year);
           if(request()->month){
            View::share('periode',blnindo(request()->month).' '.request()->year);
            if(request()->date){
            View::share('periode',request()->date.' '.blnindo(request()->month).' '.request()->year);
            }
            }
        }
        elseif(request()->from && request()->to){
            View::share('periode',tglindo(request()->from).' s/d '.tglindo(request()->to));
        }else{
            View::share('periode',date('Y'));
        }
    }
 function get_response_periode($id_layanan){
    if(session('level') == 'skpd'){
        $path = 'adminskpd/respon-layanan/'.session('id_skpd');
    }else{
        $path = 'cetakrekapskpd';
    }
        $q = \App\Models\Respon::where('id_layanan',$id_layanan)->get();
        return getResponLayanan(enc64(json_encode(json_decode($q,true))));
}

}