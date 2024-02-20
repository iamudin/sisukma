<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\Unsur;
use View;
use PDF;
use Str;
use DB;

class UnsurController extends Controller
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
     $edit = Unsur::where('id_unsur',base64_decode($r->id))->first();
     return view('admin.unsur.unsur',compact('edit'));
     }else{
     $data = Unsur::all();
     return view('admin.unsur.unsur',compact('data',));
     }
    
     

  }
  function create(Request $r){
    $data=[
        'nama_unsur'=>$r->nama,
        'a'=>$r->a,
        'b'=>$r->b,
        'c'=>$r->c,
        'd'=>$r->d,
        'urutan'=>$r->urutan,
    ];
    try {
        Unsur::insert($data);
        return redirect('admin/unsur')->with('success','Data berhasil di input');
    } catch (\Throwable $th) {
        print $th->getmessage();
        //return back()->with($th->getmessage());
    }
  }
  function update(Request $r){
   $data=[
        'nama_unsur'=>$r->nama,
        'a'=>$r->a,
        'b'=>$r->b,
        'c'=>$r->c,
        'd'=>$r->d,
        'urutan'=>$r->urutan,
    ];
    try {
        Unsur::where('id_unsur',$r->id)->update($data);
        return redirect('admin/unsur')->with('success','Data berhasil di update');
    } catch (\Throwable $th) {
        //print $th->getmessage();
        return back()->with($th->getmessage());
    }
  }
   function delete($id){
     try {
        Unsur::where('id_unsur',base64_decode($id))->delete();
        return back()->with('success','Data berhasil hapus');
     } catch (\Throwable $th) {
                return back()->with('danger',$th->getmessage());

     }
   }
  
}
