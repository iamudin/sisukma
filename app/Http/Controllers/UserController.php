<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\User;
use App\Models\Layanan;
use View;
use PDF;
use Str;
use DB;

class UserController extends Controller
{
  //Memeriksa apakah ada session dan ambil level sessions
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
       if(!session()->has('id_user'))
       {
        return redirect('login')->with('danger','Silahkan Login Terlebih Dahulu');
       }
         return $next($request);
     });
     $this->menuside();
  
  }

  function index(Request $r){
     if(isset($_GET['act']) AND $_GET['act']=='edit'){
     $edit = User::join('skpd','skpd.id_skpd','users.id_skpd')->where('id',base64_decode($r->id))->first();
     $skpd = Skpd::all();
     return view('admin.user.user',compact('skpd','edit'));
     }else{
     $data = User::join('skpd','skpd.id_skpd','users.id_skpd')->get();
     $skpd = Skpd::all();
     return view('admin.user.user',compact('data','skpd'));
     }
    
     

  }
  function create(Request $r){
    $data=[
        'nama'=>$r->nama,
        'email'=>'-',
        'username'=>$r->username,
        'password'=>md5($r->password),
        'last_login_ip'=>'-',
        'id_skpd'=>$r->id_opd,
        'level'=>'skpd'
    ];
    try {
        User::insert($data);
        return redirect('admin/userskpd')->with('success','Data berhasil di input');
    } catch (\Throwable $th) {
        print $th->getmessage();
        //return back()->with($th->getmessage());
    }
  }
  function update(Request $r){
     $data=[
        'nama'=>$r->nama,
        'email'=>'-',
        'username'=>$r->username,
        'password'=>md5($r->password),
        'last_login_ip'=>'-',
        'id_skpd'=>$r->id_opd,
        'level'=>'skpd'
    ];
    try {
        User::where('id',$r->id)->update($data);
        return redirect('admin/userskpd')->with('success','Data berhasil di update');
    } catch (\Throwable $th) {
        //print $th->getmessage();
        return back()->with($th->getmessage());
    }
  }
   function delete($id){
     try {
        User::where('id',base64_decode($id))->delete();
        return back()->with('success','Data berhasil hapus');
     } catch (\Throwable $th) {
                return back()->with('danger',$th->getmessage());

     }
   }
  
}
