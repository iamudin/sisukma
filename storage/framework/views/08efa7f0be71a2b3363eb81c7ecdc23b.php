
<?php $__env->startSection('content'); ?>

<?php if(request()->act && request()->act=='add' || request()->act=='edit'): ?>
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%"><?php echo e(request()->act=='add'? 'Tambah' : 'Edit'); ?> User Perangkat Daerah   <a href="<?php echo e(url('admin/userskpd')); ?>" style="float:right" class="text-white btn btn-danger btn-sm">Kembali</a></h2>
<br>
<form class="" action="<?php echo e(url((!empty($edit))? 'admin/userskpd/update':'admin/userskpd/create')); ?>" method="post">
  <?php echo csrf_field(); ?>
  <div class="form-group">
    <label for="">SKPD</label>
    <select class="form-control" name="id_opd" required>
        <option>--Pilih SKPD--</option>
        <?php $__currentLoopData = $skpd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($v->id_skpd); ?>" <?php if(!empty($edit)): ?><?php if($edit['id_skpd']==$v->id_skpd): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($v->nama_skpd); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </select>
  </div>
  <div class="form-group">
    <label for="">Nama user</label>
    <input type="hidden" name="id" value="<?php (!empty($edit)) ? print $edit->id:''; ?>">
    <input type="text" class="form-control" name="nama" value="<?php (!empty($edit)) ? print $edit->nama:''; ?>" required>
  </div>
  <div class="form-group">
    <label for="">Username</label>
    <input type="text" name="username" class="form-control" value="<?php (!empty($edit)) ? print $edit->username:''; ?>" required>
  </div>
   <div class="form-group">
    <label for="">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="form-group">
    <br>
    <button type="submit" class="btn float-end btn-primary btn-sm" name="simpan" value="true"> Simpan</button>
  </div>
</form>
<br>

          </div>

<?php else: ?>
<div class="container-fluid px-4">
    <h2 class="mt-4" style="width:100%">Data USER SKPD  <a href="<?php echo e(url('admin/userskpd?act=add')); ?>" style="float:right" class="text-white btn btn-primary btn-sm">Tambah</a></h2>
<br>
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Nama User</th>
                        <th>SKPD</th>
                        <th>username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e($v->nama); ?></td>
                        <td><?php echo e($v->nama_skpd); ?></td>
                        <td><?php echo e($v->username); ?></td>
                        <td>
                            <a href="<?php echo e(url('admin/userskpd?act=edit&id='.base64_encode($v->id))); ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a onclick="return confirm('Anda yakin akan menghapus data?, data yang dihapus tidak dapat dikembalikan kembali')" href="<?php echo e(url('admin/userskpd/delete/'.base64_encode($v->id))); ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        
                        </td>
                    
                        
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  
                  
                 
                </tbody>
            </table>
          </div>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\sisukma\resources\views/admin/user/user.blade.php ENDPATH**/ ?>