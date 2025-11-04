<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="card mt-4 p-0">
 <div class="card-header d-flex justify-content-between">
  <span>Recent Users</span>
 </div>
 <div class="card-body">
  <table class="table table-hover">
   <thead>
    <tr>
     <th>Name</th>
     <th>Email</th>
     <th>Role</th>
     <th>Joined</th>
     <th>Details</th>
    </tr>
   </thead>
   <tbody>
    <?php foreach ($users as $user): ?>
     <tr>
      <td><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
      <td><?= $user['email'] ?></td>
      <td><span class="badge bg-success"><?= $user['role'] ?></span></td>
      <td><?= $user['created_at'] ?></td>
      <td><a class="btn btn-action view" href="<?= base_url('users/profile/' . $user['id']) ?>"><i class=" fas fa-eye"></i> </a></td>
     </tr>
    <?php endforeach; ?>

   </tbody>
  </table>
 </div>
</div>

<?= $this->endSection() ?>