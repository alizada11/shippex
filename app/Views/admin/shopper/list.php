<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="container py-4">
   <div class="card shadow-sm">
    <div class="card-header bg-shippex-purple text-white d-flex justify-content-between align-items-center">
     <h3 class="mb-0">Shopper Requests</h3>

    </div>
    <div class="card-body p-0">
     <div class="table-responsive">
      <table class="table table-hover mb-0">
       <thead class="thead-light">
        <tr>
         <th class="text-nowrap">Request ID</th>
         <th>Status</th>
         <th>Description</th>
         <th class="text-nowrap">Submission Date</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php foreach ($requests as $req): ?>
         <tr>
          <td class="font-weight-bold">#<?= $req['id'] ?></td>
          <td>
           <?= statusBadge($req['status']) ?>
          </td>
          </td>
          <td class="text-truncate" style="max-width: 200px;" title="<?= $req['delivery_description'] ?>">
           <?= $req['delivery_description'] ?>
          </td>
          <td class="text-nowrap">
           <?= date('M d, Y H:i', strtotime($req['created_at'])) ?>
          </td>
          <td class="text-nowrap">
           <?php if ($req['is_saved'] == 1): ?>
            <a href="<?= site_url('admin/shopper/requests/edit/' . $req['id']) ?>" class="disabled btn btn-sm btn-outline-primary">
             <i class="fas fa-edit"></i> Edit
            </a>
           <?php else: ?>
            <a href="<?= site_url('admin/shopper/requests/view/' . $req['id']) ?>" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-eye"></i> View
            </a>
           <?php endif; ?>
          </td>
         </tr>
        <?php endforeach; ?>
        <?php if (empty($requests)): ?>
         <tr>
          <td colspan="5" class="text-center text-muted py-4">
           <i class="fas fa-box-open fa-2x mb-2"></i>
           <p class="mb-0">No shipping requests found</p>

          </td>
         </tr>
        <?php endif; ?>
       </tbody>
      </table>
     </div>
    </div>
    <?php if (!empty($requests)): ?>
     <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <small class="text-muted">Showing <?= count($requests) ?> requests</small>
      <nav aria-label="Page navigation">
       <ul class="pagination pagination-sm mb-0">
        <li class="page-item disabled">
         <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
         <a class="page-link" href="#">Next</a>
        </li>
       </ul>
      </nav>
     </div>
    <?php endif; ?>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
 .table-hover tbody tr:hover {
  background-color: rgba(0, 123, 255, 0.05);
 }

 .badge {
  font-size: 0.8rem;
  font-weight: 500;
  padding: 0.35em 0.65em;
 }

 .card {
  border-radius: 0.5rem;
  overflow: hidden;
 }

 .card-header {
  border-radius: 0 !important;
 }
</style>
<?= $this->endSection() ?>