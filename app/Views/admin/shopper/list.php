<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>



<div class="container">
 <div class="row">

  <!-- Bookings Table -->
  <div class="card p-0 shadow-sm border-0">

   <div class="card-header bg-shippex-purple text-white d-flex justify-content-between align-items-center">
    <h3 class="mb-0">Shopper Requests</h3>
    <form action="<?= base_url('search') ?>" method="post" class="search-minimal d-flex gap-2">
     <?= csrf_field() ?>

     <input
      type="text"
      name="q"
      class="form-control"
      placeholder="Search..."
      required
      autocomplete="off">

     <input type="hidden" name="model" value="App\Models\ShopperRequestModel">
     <input type="hidden" name="detail_url" value="admin/shopper/requests/view/">
     <input type="hidden" name="back_url" value="<?= current_url() ?>">

     <button class="btn btn-shippex-orange">
      <i class="fas fa-search"></i>
     </button>
    </form>
   </div>
   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0">
      <thead class="table-light">
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
   <?php if (!empty($pager) && method_exists($pager, 'links')): ?>
    <div class="row mt-4">
     <?= $pager->links('default', 'bootstrap_full') ?>
    </div>
   <?php endif; ?>

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