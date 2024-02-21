<?php $__env->startSection('content'); ?>

<?php if(request()->act && request()->act=='add' || request()->act=='edit'): ?>
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%"><?php echo e(request()->act=='add'? 'Tambah' : 'Edit'); ?> Perangkat Daerah   <a href="<?php echo e(url('admin/data-skpd')); ?>" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<div class="row">

<div class="col-lg-4">
<?php if($edit): ?>
<img class="w-100" src="<?php echo e(toqr(url('survei/'.enc64($edit->id_skpd)))); ?>" >
<?php endif; ?>
</div>
<div class="col-lg-8">
<form class="" action="<?php echo e(URL::full()); ?>" method="post">
  <?php echo csrf_field(); ?>
  <div class="form-group">
    <label for="">Nama SKPD</label>
    <input type="text" class="form-control" name="nama_skpd" value="<?php echo e($edit->nama_skpd ?? null); ?>">
  </div>
  <div class="form-group">
    <label for="">Alamat</label>
    <textarea class="form-control" name="alamat"><?php echo e($edit->alamat ?? null); ?></textarea>
  </div>
  <div class="form-group">
    <label for="">Gunakan Sebagai Sample</label><br>
    <?php $__currentLoopData = ['0'=>'Tidak','1'=>'Iya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <input type="radio" name="status_sample" <?php echo e($edit && $edit->status_sample ? ($edit->status_sample==$k ? 'checked' : '') :''); ?> value="<?php echo e($k); ?>" id=""> <?php echo e($r); ?> <br>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <div class="form-group">
    <label for="">Tahun Sample</label><br>
    <?php for($i=2022; $i<=date('Y'); $i++): ?>
    <input type="checkbox" name="tahun_sample[]" <?php if($edit && $edit->periodeAktif()->where('tahun',$i)->exists()): ?> checked="checked" <?php endif; ?> value="<?php echo e($i); ?>" id=""> <?php echo e($i); ?> <br>
    <?php endfor; ?>
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan</button>
  </div>
</form>
</div>
</div>
<br>

          </div>

<?php else: ?>
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Data Perangkat Daerah
    <div style="float:right"> <a href="<?php echo e(url('admin/data-skpd?act=add')); ?>"  class="text-white btn btn-primary btn-sm">Tambah</a></div></h2>
<br>
<p style="font-size:30px;border-left:5px solid orange;padding-left:10px">Periode <b><?php echo e($periode); ?></b> <button onclick="$('.periode').modal('show')" class="btn btn-sm btn-danger float-end"> <i class="fa fa-edit"></i> Ganti Periode</button></p>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>QR</th>
                        <th>Nama SKPD</th>
                        <th>Jumlah Layanan</th>
                        <th>Nilai IKM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                  <?php
                 $ik = new \App\IkmManager;
                ?>
                  <?php $__currentLoopData = DB::table('skpd')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                  $ikm = $ik->nilai_ikm_skpd($row->id_skpd)['ikm'];
                  ?>
                  <tr>
                    <td><?php echo e($k+1); ?></td>
                    <td> <a target="_blank" href="<?php echo e('https://sisukma.bengkaliskab.go.id/survei/'.enc64($row->id_skpd)); ?>"><img src="<?php echo e(toqr('https://sisukma.bengkaliskab.go.id/survei/'.enc64($row->id_skpd))); ?>" height="100" ></a></td>
                    <td><?php echo e($row->nama_skpd); ?></td>
                    <td><?php echo e(jlh_layanan($row->id_skpd)); ?></td>
                    <td><?php echo e($ikm ? round($ikm,2) : 0); ?></td>
                    <td style="width:120px">

                      <a href="<?php echo e(url('adminskpd/respon-layanan/'.$row->id_skpd.'?year='.($per ?($per['year'] ? $per['year'] :null) : date('Y')).'&bulan='.($per && isset($per['month']) ? ($per['month']? $per['month'] :null) :null).'&cetak=true')); ?>" class="btn btn-primary btn-sm"> <i class="fa fa-print" aria-hidden="true"></i> </a>
                      <a href="<?php echo e(url('admin/data-skpd?act=edit&id='.enc64($row->id_skpd))); ?>" class="btn btn-warning btn-sm"> <i class="fas fa-edit    "></i></a>
                      <a href="<?php echo e(url('admin/data-skpd?act=delete&id='.enc64($row->id_skpd))); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ?')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
          </div>
          <?php echo $__env->make('periode', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\sisukma\resources\views/admin/data_skpd.blade.php ENDPATH**/ ?>