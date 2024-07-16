<?php
function toqr($nik){
  return 'data:image/png;base64,'.base64_encode(QrCode::format('png')->size(600)->color(000,000,000)->backgroundColor(255,255,255,1)->generate($nik));
}
function getYear($id){
  return App\Models\Respon::where('id_layanan',$id)->select(DB::raw("(DATE_FORMAT(created, '%Y')) as year"))->orderBy('created','asc')->groupBy(DB::raw("DATE_FORMAT(created, '%Y')"))->pluck('year');
}
function getMonth($id,$year){
  return App\Models\Respon::where('id_layanan',$id)->whereYear('created',$year)->select(DB::raw("(DATE_FORMAT(created, '%m')) as month"))->orderBy('created','asc')->groupBy(DB::raw("DATE_FORMAT(created, '%m')"))->pluck('month');
}
function getIkmLayanan($respon){
  $unsur = ['u1','u2','u3','u4','u5','u6','u7','u8','u9'];
  foreach($unsur as $r){
    $u[$r] = 0;
  }
  if($respon->count()>0){

  foreach($respon as $r){

    foreach($unsur as $row){
      $u[$row] += $r->$row;
    }
  }
  foreach($unsur as $r){
    $a[] = ($u[$r] / $respon->count()) * (1/9);
  }
}


  return isset($a) ? round(array_sum($a)*25,2) : 0;

}
function getIkmPd($respon){
  $unsur = ['u1','u2','u3','u4','u5','u6','u7','u8','u9'];
  foreach($unsur as $r){
    $u[$r] = 0;
  }
  if($respon->count()>0){

  foreach($respon as $r){

    foreach($unsur as $row){
      $u[$row] += $r->$row;
    }
  }
  foreach($unsur as $r){
    $a[] = ($u[$r] / $respon->count()) * (1/9);
  }
}

  return isset($a) ? round(array_sum($a)*25,2) : 0;

}
function numtomonth($a){
  return strlen($a) > 1 ? $a: '0'.$a;
}

function diffmonth(\DateTime $date1, \DateTime $date2)
{
  $diff = $date1->diff($date2);

  $months = $diff->y * 12 + $diff->m + $diff->d / 30;

  return (int) round($months);
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
             abort(404);
         endif;
         return $triwulan;
 }
 function isDate($date, $format = 'Y-m-d'){
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
function getResponLayanan($respon){
  $respon = collect(json_decode(dec64($respon)));
  return $respon->filter(function ($value)use($respon) {
  if(request()->year){
    if(request()->month){
    return date('Y',strtotime($value->tgl_survei)) == request()->year && date('m',strtotime($value->tgl_survei)) == request()->month;
    }elseif(request()->from && request()->to){
      return date('Y',strtotime($value->tgl_survei)) == request()->year && date('m',strtotime($value->tgl_survei)) >= request()->from && date('m',strtotime($value->tgl_survei)) <= request()->to;
    }else{
    return date('Y',strtotime($value->tgl_survei)) == request()->year;
    }
  }else{
    return $respon;
  }
    });
}
function get_client_ip() {
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if(isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
      $ipaddress = 'UNKNOWN';
  return $ipaddress;
}
function getResponfilter($respon,$year,$month){
  return $respon->filter(function ($value) use($year,$month) {

      return date('Y',strtotime($value->tgl_survei)) == $year && date('m',strtotime($value->tgl_survei)) == $month;

    });
}
function populasi($p){
  $a = array(10=>10,15=>14,20=>19,25=>24,30=>28,35=>32,40=>36,45=>40,50=>44,55=>48,60=>65,65=>56,70=>59,75=>63,80=>66,85=>70,90=>73,95=>76,100=>80,220=>140,230=>144,240=>148,250=>152,260=>155,270=>159,280=>162,290=>165,300=>169,320=>175,340=>181,360=>186,380=>191,400=>196,420=>201,440=>205,460=>210,480=>214,500=>217,1200=>291,1300=>297,1400=>302,1500=>306,1600=>310,1700=>313,1800=>317,1900=>320,2000=>322,2200=>327,2400=>331,2600=>335,2800=>338,3000=>341,3500=>346,4000=>351,4500=>354,5000=>357,6000=>361);
return isset($a[$p]) ? $a[$p] : null;
  }
  function checkwaktu($start){
    $firstime = array('08','09','10','11','12');
    $second = array('13','14','15','16','17');
  $waktu = date('H',strtotime($start));
  if(in_array($waktu,$firstime)){
    return '08:00 - 12:00';
  }elseif(in_array($waktu,$second)){
    return '13:00 - 17:00';
  }else{
    return false;
  }

  }
  function slovin($res){
    return  round($res / ($res * 0.0025 + 1));
  }
  function takesample($resp){
  return populasi($resp) ?? slovin($resp);
  }
function prediket(float $nilai,$huruf=false){
  if($nilai >= 25.00 && $nilai <=64.99):
    $a = "Tidak Baik";
    $b= "D";

    elseif($nilai >= 65 && $nilai <=76.60):
      $a = "Kurang Baik";
    $b= "C";

      elseif($nilai >= 76.61 && $nilai <=88.30):
        $a = "Baik";
    $b= "B";

  elseif($nilai >= 88.31 && $nilai <=100.30):
    $a = "Sangat Baik";
    $b= "A";
  else:
    $a = "-";
    $b= "-";
  endif;
  return $huruf ? $b : $a;
}
function jlh_layanan($id){
  return DB::table('layanan')->where('id_skpd',$id)->count();
}
function nama_skpd($id){
  return DB::table('skpd')->where('id_skpd',$id)->first()->nama_skpd;
}
function jlh_respon($id){
  return DB::table('respon')->join('layanan','layanan.id_layanan','respon.id_layanan')->where('respon.id_layanan',$id)->count();
}
function dec64($val){
  return base64_decode($val);
}
function enc64($val){
  return base64_encode($val);
}
function admin_info($val){
  $d = DB::table('users')->where('id',session('id_user'))->first();
  return $d->$val ?? null;
}
function tanggal($date){
  return date('m/d/Y H:i T',strtotime($date));
}
function get_info($key){
  $cek = DB::table('info')->where('keyword',$key)->first();
  return $cek->value ?? null;
}
function getjam($val){
  return date('H:i', strtotime($val));
}
function gettgl($val){
  return date('d-m-Y', strtotime($val));
}
function title($val){
  View::share('title',$val);
}
function upload_foto($foto){
  $dir = public_path('photo/');
  $filename = rand().'-'.time().'.'.$foto->getClientOriginalExtension();
  $foto->move($dir,$filename);
  return $filename;
}
function blnindo($bln){
  $bulan_array = array(
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
);
return $bulan_array[ltrim($bln,'0')];
}
function tglindo($val)
{

  $waktu = date('Y-m-d', strtotime($val));
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($val));

    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$tanggal $bulan $tahun";
}


