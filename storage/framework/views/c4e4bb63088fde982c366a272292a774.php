<?php $__env->startSection('content'); ?>
    <!-- ======= Our Services Section ======= -->
    <section id="services" class="services sections-bg text-center">
      <div class="container " data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12 text-center">

          </div>
        </div>
        <div class="section-header">
          <h2>Statistik</h2>
          <p>Berikut adalah indeks kepuasan masyarakat yang didapatkan dari hasil survei <b> Periode <?php echo e($periode); ?></b> <br><br> <br> <button onclick="$('.periode').modal('show');" class="btn btn-primary btn-sm"> <i class="fa fa-edit" aria-hidden="true"></i> Ganti Periode</button> </p>

        </div>

        <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-12">

            <div class="service-item  position-relative ">

              <h3>Kabupaten</h3>
              <div class="row">
                <div class="col-lg-6">
              <h5>Nilai IKM</h5>

                <p style="font-size:100px" class="mt-5 mb-5"><?php echo e(round($ikm['ikm'],2)); ?></p>
                <p class="text-center"><h5>Mutu Pelayanan</h5>
  <h1><?php echo e(prediket(round($ikm['ikm'],2),true)); ?></h1> (<?php echo e(prediket(round($ikm['ikm'],2))); ?>)
</p>
              </div>
                <div class="col-lg-6">
                <h5>Responden</h5>
              <table class="table" style="text-align:left;" border="0">
              <tr>
                <td>Jumlah</td>
                <td>:</td>
                <td><b><?php echo e($ikm['jumlah']); ?></b> Orang</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>L = <b><?php echo e($ikm['l']); ?></b>  Orang / P = <b><?php echo e($ikm['p']); ?></b> Orang </td>
              </tr>
              <tr>
                <td>Pendidikan</td>
                <td>:</td>
                <td>
                  <?php $__currentLoopData = ['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php echo e($r); ?> <span class="float-end "><b><?php echo e($ikm[Str::lower(str_replace(' ','_',$r))]); ?></b>  <span class="text-muted">Orang</span></span> <br>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
              </tr>
              </table>
                </div>
              </div>


            </div>
          </div><!-- End Service Item -->
<?php $__currentLoopData = \App\Models\Skpd::withWhereHas('periodeAktif', function ($q) {
        $q->where('tahun', request()->year??date('Y'));
    })->whereStatusSample(1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<?php
$u = new \App\IkmManager;
$id = $r->id_skpd;
$ikm = $u->nilai_ikm_skpd($r->id_skpd);
?>
          <div class="col-lg-6">

<div class="service-item  position-relative ">

  <h3><?php echo e($r->nama_skpd); ?></h3>
  <div class="row">
    <div class="col-lg-4">
  <h5>Nilai IKM</h5>
    <p style="font-size:60px" class="mt-5 mb-5"><?php echo e(round($ikm['ikm'],2)); ?></p>
  <p class="text-center"><h5>Mutu Pelayanan </h5>
  <h1><?php echo e(prediket(round($ikm['ikm'],2),true)); ?></h1> (<?php echo e(prediket(round($ikm['ikm'],2))); ?>)
</p>
  </div>
    <div class="col-lg-8">
    <h5>Responden</h5>
  <table class="table" style="text-align:left;" border="0">
  <tr>
    <td>Jumlah</td>
    <td>:</td>
    <td><b><?php echo e($ikm['jumlah']); ?></b> Orang</td>
  </tr>
  <tr>
    <td>Jenis Kelamin</td>
    <td>:</td>
    <td>L = <b><?php echo e($ikm['l']); ?></b>  Orang / P = <b><?php echo e($ikm['p']); ?></b> Orang </td>
  </tr>
  <tr>
    <td>Pendidikan</td>
    <td>:</td>
    <td>
      <?php $__currentLoopData = ['Non Pendidikan','SD','SMP','SMA','DIII','S1','S2','S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php echo e($r); ?> <span class="float-end "><b><?php echo e($ikm['pendidikan'][Str::lower(str_replace(' ','_',$r))] ?? 0); ?></b>  <span class="text-muted">Orang</span></span> <br>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </td>
  </tr>
  </table>
  <div>
<form action="<?php echo e(url()->full()); ?>" method="post"> <?php echo csrf_field(); ?>
  <input type="hidden" name="ids" value="<?php echo e(enc64($id)); ?>">
  <input type="hidden" name="periode" value="<?php echo e($periode); ?>">
<button style="cursor:pointer"  name="print" value="<?php echo e(enc64(json_encode($ikm))); ?>" type="submit" class="btn btn-info btn-sm  float-end" aria-hidden="true"><i class="fa fa-print" aria-hidden="true"></i></button>
</form>
  </div>
    </div>
  </div>


</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

      </div>
    </section><!-- End Our Services Section -->
<?php echo $__env->make('periode', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layoutfront', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\sisukma\resources\views/front/new.blade.php ENDPATH**/ ?>