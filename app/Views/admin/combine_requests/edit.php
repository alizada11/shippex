<?php
$session = session();
$role = $session->get('role');

// Dynamically pick layout based on role
if ($role === 'admin') {
 $this->extend('admin/layouts/main');
} else {
 $this->extend('customers/layouts/main');
}
?>
<?= $this->section('content') ?>
<div class="card ">
 <!-- Page Header -->
 <div class="card-header mb-4">
  <div class="container">
   <div class="d-flex justify-content-between align-items-center">
    <div class="">
     <h3 class="page-title">
      <i class="fas fa-edit me-2"></i>Combine Package Request
     </h3>

    </div>
    <div class="header-actions">
     <a href="<?= base_url('admin/combine-requests') ?>" class="btn btn-shippex-orange">
      <i class="fas fa-arrow-left me-2"></i>Back to Requests
     </a>
    </div>
   </div>
  </div>
 </div>
 <div class="row card-body">
  <!-- Package Details Card -->
  <div class="col-lg-8 mb-4">
   <div class="premium-card shadow-sm border-0">
    <div class="card-header">
     <h5 class="card-title mb-0">Package Details</h5>
    </div>
    <div class="card-body">
     <div class="table-responsive">
      <table class="table table-hover">
       <thead class="table-light">
        <tr>
         <th>User</th>
         <th>Warehouse</th>
         <th>Dimensions (L×W×H)</th>
         <th>Weight</th>
        </tr>
       </thead>
       <tbody>
        <?php foreach ($packages as $pack): ?>
         <tr>
          <td>
           <div class="d-flex align-items-center">
            <?php
            $session = session();
            $role = $session->get('role');
            ?>
            <a class="d-flex align-items-center"
             href="<?= $role === 'customer' ? base_url('profile/') : base_url('users/profile/' . $pack['user_id']) ?>">

             <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
              <i class="fas fa-user text-primary"></i>
             </div>
             <span><?= fullname($pack['user_id']) ?></span>
            </a>
           </div>
          </td>
          <td>
           <div class="d-flex flex-column">
            <span class="fw-medium"><?= warehouse_name($pack['virtual_address_id'])['country'] ?></span>
            <small class="text-muted"><?= warehouse_name($pack['virtual_address_id'])['city'] . ', ' . warehouse_name($pack['virtual_address_id'])['address_line'] ?></small>
           </div>
          </td>
          <td>
           <span class="badge bg-light text-dark"><?= $pack['length'] ?>×<?= $pack['width'] ?>×<?= $pack['height'] ?></span>
          </td>
          <td>
           <span class="fw-medium"><?= $pack['weight'] ?> kg</span>
          </td>
         </tr>
        <?php endforeach; ?>
       </tbody>
      </table>
     </div>
    </div>
   </div>
  </div>
  <?php if ($role == 'admin'): ?>
   <!-- Combined Package Form -->
   <div class="col-lg-4 mb-4">
    <div class="card shadow-sm border-0">
     <div class="card-header">
      <h5 class="card-title mb-0">Combined Package</h5>
     </div>
     <div class="card-body">
      <form method="post" action="<?= base_url('admin/combine-requests/update/' . $request['id']) ?>">
       <?= csrf_field() ?>

       <!-- Dimensions Inputs -->
       <div class="row g-3 mb-4">
        <div class="col-12">
         <label class="form-label fw-medium">Dimensions (cm)</label>
        </div>
        <div class="col-4">
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light">L</span>
          <input type="number" step="0.01" name="length" value="<?= $request['total_length'] ?>" class="form-control form-control-sm" placeholder="Length">
         </div>
        </div>
        <div class="col-4">
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light">W</span>
          <input type="number" step="0.01" name="width" value="<?= $request['total_width'] ?>" class="form-control form-control-sm" placeholder="Width">
         </div>
        </div>
        <div class="col-4">
         <div class="input-group input-group-sm">
          <span class="input-group-text bg-light">H</span>
          <input type="number" step="0.01" name="height" value="<?= $request['total_height'] ?>" class="form-control form-control-sm" placeholder="Height">
         </div>
        </div>
       </div>

       <!-- Weight Input -->
       <div class="mb-4">
        <label for="weight" class="form-label fw-medium">Weight (kg)</label>
        <div class="input-group input-group-sm">
         <input type="number" step="0.01" name="weight" value="<?= $request['total_weight'] ?>" class="form-control" id="weight" placeholder="Weight">
         <span class="input-group-text">kg</span>
        </div>
       </div>

       <!-- Hidden Fields -->
       <input type="hidden" name="warehouse_id" value="<?= $request['warehouse_id'] ?>">
       <input type="hidden" name="user_id" value="<?= $request['user_id'] ?>">

       <!-- Toggle Switch -->
       <div class="form-check form-switch mb-4">
        <input class="form-check-input" type="checkbox" id="create_package" name="create_package" value="1">
        <label class="form-check-label" for="create_package">Create package after update</label>
       </div>
       <!-- Retailer -->

       <div class="mb-4">
        <label for="weight" class="form-label fw-medium">Retailer Name</label>
        <div class="input-group input-group">
         <input type="text" name="retailer" class="form-control" placeholder="DHL">
        </div>
       </div>
       <!-- Submit Button -->
       <?php if ($request['status'] == 'pending'): ?>
        <div class="d-grid">
         <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-2"></i>Save Combined Package
         </button>
        </div>
       <?php endif; ?>
      </form>
     </div>
    </div>
   </div>
  <?php endif; ?>
 </div>
</div>

<style>
 .avatar-sm {
  width: 32px;
  height: 32px;
  font-size: 14px;
 }

 .card {
  border-radius: 0.5rem;
 }

 .card-header {
  border-radius: 0.5rem 0.5rem 0 0 !important;
 }

 .form-control:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
 }

 .form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
 }

 .table th {
  border-top: none;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #6c757d;
 }

 .shadow-sm {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
 }

 .badge {
  font-size: 0.75rem;
  padding: 0.35em 0.65em;
 }
</style>

<?= $this->endSection() ?>