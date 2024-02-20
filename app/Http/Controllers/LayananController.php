<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\Layanan;
use View;
use PDF;
use Str;
use DB;

class LayananController extends Controller
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
     $data = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->groupby('skpd.id_skpd')->get();
     $edit = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->where('id_layanan',base64_decode($r->id))->first();
     $skpd = Skpd::all();
     return view('admin.layanan.layanan',compact('data','skpd','edit'));
     }else{
      $data = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->get();
     $skpd = Skpd::all();
     return view('admin.layanan.layanan',compact('data','skpd'));
     }
    
     

  }
  function create(Request $r){
    $data=[
        'id_skpd'=>$r->id_opd,
        'nama_layanan'=>$r->layanan,
    ];
    try {
        Layanan::insert($data);
        return redirect('admin/layanan')->with('success','Data berhasil di input');
    } catch (\Throwable $th) {
        print $th->getmessage();
        //return back()->with($th->getmessage());
    }
  }
  function update(Request $r){
    $data=[
        'id_skpd'=>$r->id_opd,
        'nama_layanan'=>$r->layanan,
    ];
    try {
        Layanan::where('id_layanan',$r->id)->update($data);
        return redirect('admin/layanan')->with('success','Data berhasil di update');
    } catch (\Throwable $th) {
        //print $th->getmessage();
        return back()->with($th->getmessage());
    }
  }
   function delete($id){
     try {
        Layanan::where('id_layanan',base64_decode($id))->delete();
        return back()->with('success','Data berhasil hapus');
     } catch (\Throwable $th) {
                return back()->with('danger',$th->getmessage());

     }
   }
  function data_skpd(Request $request,Skpd $skpd){
    $edit = null;
      if($request->act){
      if($request->act=='delete'){
      $cek = $skpd->where('id_skpd',dec64($request->id));
      if(empty($cek->first()))
      return back()->with('danger','Data Tidak Ditemukan');
      $cek->delete();
      return redirect('admin/data-skpd')->with('success','Berhasil dihapus');

      }

      if($request->act=='edit'){
      $edit = $skpd->where('id_skpd',dec64($request->id));
      if(empty($edit->first()))
      return redirect('admin/data-skpd')->with('danger','Data Tidak Ditemukan');

      if($request->simpan){
        $edit->update(['nama_skpd'=>$request->nama_skpd,'alamat'=>$request->alamat]);
        return redirect('admin/data-skpd')->with('success','Berhasil diedit');
       }
      $edit = $edit->first();
     }

      if($request->act=='add'){
        if($request->simpan){
          $skpd->insert(['nama_skpd'=>$request->nama_skpd,'alamat'=>$request->alamat]);
        return redirect('admin/data-skpd')->with('success','Berhasil Ditambah');

        }
      }
    }
    return view('admin.data_skpd',['edit'=>$edit]);
    
      }
}
