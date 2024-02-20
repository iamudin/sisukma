<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use View;
class AuthController extends Controller
{

function login(Request $req){
  //memeriksa apakah email dan password terisi
  if($req->username && $req->password){
    //memeriksa username dan password ada di database
    $cek = Login::cekUser(['username'=>$req->username,'password'=>md5($req->password)]);
    //Jika tidak kosongdf
    if(!empty($cek)){
      Session::put('id_user',$cek->id);
      Session::put('level',$cek->level);
      Session::put('nama',$cek->nama);
      Session::put('id_skpd',$cek->id_skpd);
      Login::where('id',$cek->id)->update(['last_login_at'=>now()]);
      if($cek->level=='admin'):
      return redirect('admin/dashboard');
      else:
      return redirect('adminskpd/dashboard');
      endif;
    }
    //jika email dan password tidak ada dalam database
    else {
      return back()->with('danger','Username dan Password Tidak Ditemukan');
    }
  }
  else {
    //Jika sudah login
    if(Session::has('id_user')){
     
      if(Session::get('level')=='admin')
      return redirect('admin/dashboard')->send();
      return redirect('adminskpd/dashboard')->send();
    }
    return view('login');
  }
}
function logout(){
  //cek apakah sudah login
  if(Session::has('id_user')){
  //jika ya, maka hapus sesi;
    Session::flush();
  }
  //kembali kehalaman login
  return redirect('login');
}
function account(Request $req){
  if(session('level')=='admin'):
  $this->menuside();
  else:
    View::share('sidebarmenu',[
      ['ikon'=>'desktop','nama'=>'Layanan','target'=>url('skpd/layanan')],
      ['ikon'=>'camera','nama'=>'Gallery','target'=>url('adminskpd/gallery')],
      ['ikon'=>'gear','nama'=>'Pengaturan','target'=>url('adminskpd/pengaturan')],

      ]);
  endif;
  $edit = Login::where('id',session('id_user'))->first();
  if($req->simpan){
    if($req->pass && $req->pass2){
      if($req->pass!=$req->pass2){
        return back()->with('danger','Password Harus Sama');
      }else{

      }
    }

        if(Login::where('username',$req->username)->where('id','!=',$edit->id)->count()>0){
        return back()->with('danger','Username telah digunakan di akun lain, gunakan karatker yang lain.');

        }
      
    Login::where('id',$edit->id)->update([
      'username'=>$req->username,
      'password'=>$req->pass && $req->pass2 ? md5($req->pass) : $edit->password,
      'nama'=>$req->nama
    ]);
    return back()->with('success','Perubahan akun tersimpan');

  }
  return view('account',compact('edit'));
}
}
