<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row g-4">
 <div class="col-md-4">
  <div class="card text-white bg-shippex-purple mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-users"></i> Users</h5>
    <p class="card-text fs-4"><?= $usersCount; ?></p>
   </div>
  </div>
 </div>

 <div class="col-md-4">
  <div class="card text-white bg-success mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Orders</h5>
    <p class="card-text fs-4"><?= $orders; ?></p>
   </div>
  </div>
 </div>

 <div class="col-md-4">
  <div class="card text-white bg-shippex-orange mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-warehouse"></i> Warehouses</h5>
    <p class="card-text fs-4"><?= $addresses ?></p>
   </div>
  </div>
 </div>
</div>

<div class="card mt-4 p-0">
 <div class="card-header d-flex justify-content-between">
  <span>Recent Users</span>
  <a class="link text-white" href="<?= base_url('/') ?>">View All</a>
 </div>
 <div class="card-body">
  <table class="table table-hover">
   <thead>
    <tr>
     <th>Name</th>
     <th>Email</th>
     <th>Role</th>
     <th>Joined</th>
    </tr>
   </thead>
   <tbody>
    <?php foreach ($users as $user): ?>
     <tr>
      <td><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
      <td><?= $user['email'] ?></td>
      <td><span class="badge bg-success"><?= $user['role'] ?></span></td>
      <td><?= $user['created_at'] ?></td>
     </tr>
    <?php endforeach; ?>

   </tbody>
  </table>
 </div>
</div>

<?= $this->endSection() ?>