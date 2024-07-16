<div class="modal saran" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <form action="<?php echo e(URL::full()); ?>" method="post">
      <?php echo csrf_field(); ?>
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-comments" aria-hidden="true"></i> Saran Periode <?php echo e($periode); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <input type="hidden" name="saran" value="<?php echo e(enc64(json_encode($data->saran))); ?>">
      <input type="hidden" name="periode" value="<?php echo e('Periode '.$periode); ?>">
      <div class="modal-body" style="max-height:70vh;overflow:auto">
    
      <div class="row">
        <div class="col-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis Layanan</th>
                    <th>Saran</th>
                </tr>
            </thead>
            <?php $no=0; ?>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = collect($data->saran)->sortBy('tgl_survei'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               
                <tr>
                    <td><?php echo e($no +1); ?></td>
                    <td><?php echo e($row->tgl_survei); ?></td>
                    <td><?php echo e($row->jam_survei ?? '-'); ?></td>
                    <td><?php echo e($row->nama_layanan); ?></td>
                    <td><?php echo e($row->saran??'-'); ?></td>
                </tr>
                <?php $no++; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <?php if(!empty($data->saran)): ?><button type="submit" name="cetak_saran" value="true" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Cetak Saran</button><?php endif; ?>
      </div>
    </form>

    </div>
  </div>
</div><?php /**PATH C:\laragon\www\sisukma\resources\views/skpd/lihatsaran.blade.php ENDPATH**/ ?>