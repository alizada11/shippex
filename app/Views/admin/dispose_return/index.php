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

<div class="container-fluid ">
 <div class="card shadow-sm border-0 p-0 ">
  <div class="card-header premium-header p-3 d-flex justify-content-between align-items-center">
   <h3 class="mb-0"><?= esc($title) ?></h3>
  </div>
  <div class="card-body  p-0">
   <?php if (empty($requests)): ?>
    <div class="text-center text-muted">No requests found.</div>
   <?php else: ?>
    <div class="table-responsive">
     <table class="table table-hover align-middle ">
      <thead class="table-light">
       <tr>
        <th>#</th>
        <th>Package ID</th>
        <th>User ID</th>
        <th>Type</th>
        <th>Status</th>
        <th>Reason</th>
        <th>Created</th>
        <th class="text-end">Actions</th>
       </tr>
      </thead>
      <tbody>

       <?php foreach ($requests as $r): ?>
        <tr>
         <td><?= esc($r['id']) ?></td>
         <td><?= esc($r['package_id']) ?></td>
         <td><?= fullname($r['user_id']) ?></td>
         <td>
          <span class="badge bg-<?= $r['request_type'] === 'dispose' ? 'danger' : 'info' ?>">
           <?= ucfirst($r['request_type']) ?>
          </span>
         </td>
         <td>
          <?php
          $statusColor = [
           'pending' => 'warning',
           'approved' => 'success',
           'rejected' => 'secondary'
          ][$r['status']] ?? 'light';
          ?>
          <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($r['status']) ?></span>
         </td>
         <td><?= esc($r['reason'] ?? '-') ?></td>
         <td><?= esc(date('Y-m-d H:i', strtotime($r['created_at']))) ?></td>
         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="<?= site_url('admin/dispose_return/edit/' . $r['id']) ?>" class="btn btn-action edit">
            <i class="fas fa-pencil"></i>
           </a>

           <form class="delete-form" action="<?= site_url('admin/dispose_return/delete/' . $r['id']) ?>" method="post" class="d-inline delete-form">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-action delete "><i class="fas fa-trash"></i></button>
           </form>
          </div>
         </td>
        </tr>
       <?php endforeach; ?>

      </tbody>
     </table>
    </div>
    <div class="row mt-4">
     <?= $pager->links('default', 'bootstrap_full') ?>
    </div>

   <?php endif; ?>
  </div>
 </div>
</div>

<?= $this->endSection() ?>