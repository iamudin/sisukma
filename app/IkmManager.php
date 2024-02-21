<?php
namespace App;
use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\Skpd;
use App\Models\Unsur;
use App\Models\Layanan;
use App\Models\Respon;
use View;
use PDF;
use Str;
use DB;
class IkmManager
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
    function astrw($from,$to){
     if($from == '01' && $to == '03'):
        $triwulan = 'I';
     elseif($from == '04' && $to == '06'):
        $triwulan = 'II';
        elseif($from == '07' && $to == '09'):
        $triwulan = 'III';
            elseif($from == '10' && $to == '12'):
        $triwulan = 'IV';
            else:
                $triwulan = '';
            endif;
            return $triwulan;
    }
   function get_response_periode($id_skpd){
    if(session('level') == 'skpd'){
        $path = 'adminskpd/respon-layanan/'.session('id_skpd');
    }else{
        $path = 'cetakrekapskpd';
    }
    if(request()->year){
        $from = date('Y-m-d',strtotime(request()->year.'-01-01'));
        $to_date = request()->year."-12-23";
        $between = [$from,date("Y-m-t", strtotime($to_date))];
        $respon = Respon::join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->join('skpd', 'skpd.id_skpd', 'layanan.id_skpd')->where('skpd.id_skpd', $id_skpd)->whereBetween('respon.tgl_survei', $between)->get();

        $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));
            $star = intval(substr("01", 0, 1) == '0' ? substr("01", 1, 2) : "01");
            $to = intval(substr("12", 0, 1) == '0' ? substr("12", 1, 2) : "12");
            $data = array();
            for ($a = $star; $a <= $to; $a++){
          $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
          $u = $rsp->take(takesample(count($rsp)));
          foreach ($u as $rd) {
            array_push($data, $rd);
          }
        // $o[] = numtomonth($a);
        // $f[$a] = getIkmPd($u);?
        // return $rsp;
        }
        $q = collect($data);
            // dd($q);
        View::share('periode',request()->year);
        View::share('urlcetak',url($path.'?year='.request()->year));
       if(request()->month){
        View::share('periode',blnindo(request()->month).' '.request()->year);
        View::share('urlcetak',url($path.'?year='.request()->year.'&month='.request()->month));
        $q = Respon::join('layanan','layanan.id_layanan','respon.id_layanan')->join('skpd','skpd.id_skpd','layanan.id_skpd')->where('skpd.id_skpd',$id_skpd)->whereYear('respon.tgl_survei',request()->year)->whereMonth('respon.tgl_survei',request()->month)->get();
        if(request()->date){
        View::share('periode',request()->date.' '.blnindo(request()->month).' '.request()->year);
        $q = Respon::join('layanan','layanan.id_layanan','respon.id_layanan')->join('skpd','skpd.id_skpd','layanan.id_skpd')->where('skpd.id_skpd',$id_skpd)->whereDate('respon.tgl_survei',request()->year.'-'.request()->month.'-'.request()->date)->get();
        }
        }elseif(request()->from && request()->to){
            if(request()->sms){
                View::share('periode',(Str::lower(request('sms')) == "i" ? "Januari s/d Juni" : "Juli s/d Desember " )." ".request('year'));
                View::share('urlcetak',url($path.'?year='.request()->year.'&from='.request()->from.'&to='.request()->to.'&sms='.request()->sms));

                    // dd($q);
                }
            else{
                View::share('periode','Triwulan '.$this->astrw(request()->from,request()->to).' '.request()->year);
                View::share('urlcetak',url($path.'?year='.request()->year.'&from='.request()->from.'&to='.request()->to));

        }

        $from = date('Y-m-d',strtotime(request()->year.'-'.request()->from.'-01'));
        $to_date = request()->year."-".request()->to."-23";
        $between = [$from,date("Y-m-t", strtotime($to_date))];
                $respon = Respon::join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->join('skpd', 'skpd.id_skpd', 'layanan.id_skpd')->where('skpd.id_skpd', $id_skpd)->whereBetween('respon.tgl_survei', $between)->get();

        $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));
            $star = intval(substr(request('from'), 0, 1) == '0' ? substr(request('from'), 1, 2) : request('from'));
            $to = intval(substr(request('to'), 0, 1) == '0' ? substr(request('to'), 1, 2) : request('to'));
        $data = array();
            for ($a = $star; $a <= $to; $a++){
          $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
          $u = $rsp->take(takesample(count($rsp)));
          foreach ($u as $rd) {
            array_push($data, $rd);
          }
          // $o[] = numtomonth($a);

          // $f[$a] = getIkmPd($u);?
              // return $rsp;
        }
                $q = collect($data);
    }else{
        $from = date('Y-m-d',strtotime(request()->year.'-01-01'));
        $to_date = request()->year."-12-23";
        $between = [$from,date("Y-m-t", strtotime($to_date))];
                $respon = Respon::join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->join('skpd', 'skpd.id_skpd', 'layanan.id_skpd')->where('skpd.id_skpd', $id_skpd)->whereBetween('respon.tgl_survei', $between)->get();

        $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));

        $data = array();
            for ($a = 1; $a <=12; $a++){
          $rsp = getResponfilter($respon->sortByDesc('id_respon'), request('year'), numtomonth($a));
          $u = $rsp->take(takesample(count($rsp)));
          foreach ($u as $rd) {
            array_push($data, $rd);
          }
          // $o[] = numtomonth($a);

          // $f[$a] = getIkmPd($u);?
              // return $rsp;
        }
                $q = collect($data);
        }

    }
    else{
        View::share('periode',date('Y'));
        View::share('urlcetak',url($path.'?year='.date('Y')));
        $from = date('Y-m-d',strtotime(date('Y').'-01-01'));
        $to_date = date('Y')."-12-23";
        $between = [$from,date("Y-m-t", strtotime($to_date))];
                $respon = Respon::join('layanan', 'layanan.id_layanan', 'respon.id_layanan')->join('skpd', 'skpd.id_skpd', 'layanan.id_skpd')->where('skpd.id_skpd', $id_skpd)->whereBetween('respon.tgl_survei', $between)->get();

        $totalmonth = diffmonth(new \DateTime($from), new \DateTime($to_date));

        $data = array();
            for ($a = 1; $a <=12; $a++){
          $rsp = getResponfilter($respon->sortByDesc('id_respon'), date('Y'), numtomonth($a));
          $u = $rsp->take(takesample(count($rsp)));
          foreach ($u as $rd) {
            array_push($data, $rd);
          }
          // $o[] = numtomonth($a);

          // $f[$a] = getIkmPd($u);?
              // return $rsp;
        }
                $q = collect($data);
    }
    return $q;
   }

   function pekerjaan_arr(){
    foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
        $arr[Str::lower(str_replace(' ','_',$r))] = 0;
    endforeach;
    return $arr;
}
function usia_arr($data){
    $range = array(
        ['type'=>'1','range'=>'17 - 23','jumlah'=>$data[1]],
        ['type'=>'2','range'=>'24 - 29','jumlah'=>$data[2]],
        ['type'=>'3','range'=>'30 - 40','jumlah'=>$data[3]],
        ['type'=>'4','range'=>'Diatas 40','jumlah'=>$data[4]],
    );
    return $range;
}

   function ikm_kabupaten($tahun=false){
    $skpd = $tahun ?   \App\Models\Skpd::withWhereHas('periodeAktif', function ($q) use ($tahun) {
        $q->where('tahun', $tahun);
    })->whereStatusSample(1)->get() : [];
    $l['jumlah'] = 0;
    $l['l'] = 0;
    $l['p'] = 0;
    $l['sma'] = 0;
    $l['non_pendidikan'] = 0;
    $l['sd'] = 0;
    $l['smp'] = 0;
    $l['diii'] = 0;
    $l['s1'] = 0;
    $l['s2'] = 0;
    $l['s3'] = 0;
    $l['ikm'] = 0;
    $d['type1'] =0;
    $d['type2'] = 0;
    $d['type3'] = 0;
    $d['type4'] = 0;
    $l['pekerjaan'] = $this->pekerjaan_arr();
    foreach($skpd as $r){
        $cek = $this->nilai_ikm_skpd($r->id_skpd);
            $l['jumlah'] += $cek['jumlah'];
            $l['l'] += $cek['l'];
            $l['p']  += $cek['p'];
            foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r):
                $l[Str::lower(str_replace(' ','_',$r))] += $cek['pendidikan'][Str::lower(str_replace(' ','_',$r))] ?? 0;
            endforeach;
            foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
                $l['pekerjaan'][Str::lower(str_replace(' ','_',$r))] += $cek['pekerjaan'][Str::lower(str_replace(' ','_',$r))] ?? 0;
            endforeach;

            foreach($cek['usia'] as $k=>$u){
                $d['type'.($k+1)]  += $u['jumlah'];
              }

            $l['ikm'] += $cek['ikm'];
    }
    $l['usia'] = $this->usia_arr([1=>$d['type1'],$d['type2'],$d['type3'],$d['type4']]);

    $l['ikm'] = $l['ikm'] / count($skpd);
    return $l;

   }
//    function ikm_skpd($id_skpd){
//     $skpd = DB::table('skpd')->join('layanan','layanan.id_skpd','skpd.id_skpd')->groupBy('skpd.id_skpd')->where('skpd.id_skpd',$id_skpd)->get();
//     $l['jumlah'] = 0;
//     $l['l'] = 0;
//     $l['p'] = 0;
//     $l['sma'] = 0;
//     $l['non_pendidikan'] = 0;
//     $l['sd'] = 0;
//     $l['smp'] = 0;
//     $l['diii'] = 0;
//     $l['s1'] = 0;
//     $l['s2'] = 0;
//     $l['s3'] = 0;
//     $l['ikm'] = 0;
//     $jly = 0;
//     foreach($skpd as $r){
//         $k = $this->ikm_layanan_skpd($r->id_skpd);
//         foreach($k as $cek){
//             $l['jumlah'] += $cek['jumlah'];
//             $l['l'] += $cek['l'];
//             $l['p']  += $cek['p'];
//             $l['sma']  += $cek['sma'];
//             $l['non_pendidikan']   += $cek['non_pendidikan'];
//             $l['sd'] += $cek['sd'];
//             $l['smp'] += $cek['smp'];
//             $l['diii'] += $cek['diii'];
//             $l['s1'] += $cek['s1'];
//             $l['s2'] += $cek['s2'];
//             $l['s3'] += $cek['s3'];
//             $l['ikm'] += $cek['ikm'];
//             $jly++;
//         }
//     }
//     $l['ikm'] = $l['ikm'] / $jly;
//     return $l;
//    }

   function nilai_unsur_rekap(){
    $skpd = \App\Models\Skpd::withWhereHas('periodeAktif', function ($q)  {
        $q->where('tahun', request()->year ?? date('Y'));
    })->whereStatusSample(1)->get();
    $data['unsur'] = array();
    $data['data'] = array();
    foreach($skpd as $r){
        $nilai = $this->nilai_ikm_skpd($r->id_skpd);
        $a['nama_skpd']= $r->nama_skpd;
        $a['ikm'] = $nilai['ikm'];

        foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $u){
            $a[$u] = $nilai[$u];
        }

        array_push($data['data'],$a);
    }
    foreach(['u1','u2','u3','u4','u5','u6','u7','u8','u9'] as $u){
        $data['unsur'][$u] = array_sum(array_column($data['data'],$u)) / count($skpd);
    }
    // $data['ikm_unsur'] = $a;
    $data['unsur']['total_ikm'] = array_sum(array_column($data['data'],'ikm')) / count($skpd) ;
    return $data;
}

//     function ikm_layanan_skpd($id_skpd,$u=false){
//         $list_layanan = Layanan::join('skpd','skpd.id_skpd','layanan.id_skpd')->where('layanan.id_skpd',$id_skpd)->get();
//         $data = array();

//         foreach($list_layanan as $row){
//         if($u):
//         $total_ikm = $this->ikm_layanan($this->get_response_periode($row->id_layanan),true);

//         array_push($data,array_merge( $total_ikm,array('nama_skpd'=>$row->nama_skpd)));
//         else:
//         $total_ikm = $this->ikm_layanan($this->get_response_periode($row->id_layanan));

//         array_push($data,array_merge( $total_ikm,array('nama_layanan'=>$row->nama_layanan)));
//     endif;

// }
//         return $data;
//     }


function nilai_ikm_skpd($id_skpd){
    $unsur = array('u1','u2','u3','u4','u5','u6','u7','u8','u9');
    foreach($unsur as $r){
        $u[$r] = 0;
    }
    $respon = $this->get_response_periode($id_skpd)->sortByDesc('id_respon');
    $sample = takesample(count($respon));
    $responden = $respon->take($sample);

    if(count($respon) > 0):

    foreach($responden as $row){

        foreach($unsur as $r){
            $u[$r] += $row->$r;
        }
    }
    $data = $this->responden($responden);
    foreach($unsur as $r){
        $totalunsur[] = ($u[$r] / $sample) * 1 / 9;
        $data[$r] = $u[$r] / $sample;
    }
    $data['ikm'] = array_sum($totalunsur) * 25;
else:
    $data = $this->responden($responden);
    foreach($unsur as $r){
        $data[$r] = 0;
    }
    $data['ikm'] = 0;
endif;
return $data;
}

function usiarange($data){
        $range = array(
            ['type'=>'1','range'=>'17 - 23','jumlah'=>count($data->where('usia','>=',17)->where('usia','<=',23))],
            ['type'=>'2','range'=>'24 - 29','jumlah'=>count($data->where('usia','>=',24)->where('usia','<=',29))],
            ['type'=>'3','range'=>'30 - 40','jumlah'=>count($data->where('usia','>=',30)->where('usia','<=',40))],
            ['type'=>'4','range'=>'Diatas 40','jumlah'=>count($data->where('usia','>',40))],
        );
        return $range;
}
function usiarangenull(){
    $range = array(
        ['type'=>'1','range'=>'17 - 23','jumlah'=>0],
        ['type'=>'2','range'=>'24 - 29','jumlah'=>0],
        ['type'=>'3','range'=>'30 - 40','jumlah'=>0],
        ['type'=>'4','range'=>'Diatas 40','jumlah'=>0],
    );
    return $range;
}
function pekerjaan($data){
    foreach(['TNI','POLRI','PNS','SWASTA','WIRAUSAHA','Lainnya'] as $r):
        $arr[Str::lower(str_replace(' ','_',$r))] = count($data->where('pekerjaan',$r));
    endforeach;
    return $arr;
}
function pendidikan($data){
    foreach(['SMA','Non Pendidikan','SD','SMP','DIII','S1','S2','S3'] as $r):
        $arr[Str::lower(str_replace(' ','_',$r))] = count($data->where('pendidikan',$r));
        endforeach;
        return $arr;
}
function responden($resp){
        if(count($resp)>0){
            $sample = count($resp);
            $data = $resp;

            $arr['jumlah'] = $sample;
            $arr['l'] = count($data->where('jenis_kelamin','L'));
            $arr['p'] = count($data->where('jenis_kelamin','P'));
            $arr['usia'] = $this->usiarange($data);
            $arr['pekerjaan'] = $this->pekerjaan($data);
            $arr['pendidikan'] = $this->pendidikan($data);
            $arr['saran'] = $data->map->only(['id_respon','tgl_survei','nama_layanan','saran','jam_survei']);
        }else{
            $arr['jumlah'] =0;
            $arr['l'] = 0;
            $arr['p'] = 0;
            $arr['usia'] = $this->usiarangenull();
            $arr['pekerjaan'] = array();
            $arr['pendidikan'] = array()    ;
            $arr['saran'] = array()    ;
        }
        return $arr;
    }
}
