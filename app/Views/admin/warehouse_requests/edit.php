<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
 <div class="card border-0 shadow-sm">
  <div class="card-header bg-shippex-purple text-white">
   <h3 class="mb-0"><i class="fas fa-warehouse me-2"></i>Warehouse Requests</h3>

  </div>

  <div class="card-body">


   <h2>Edit Request #<?= $request['id'] ?></h2>
   <form method="post" action="<?= site_url('warehouse-requests/update/' . $request['id']) ?>">
    <label>Status:</label>
    <select name="status" required>
     <option value="pending" <?= $request['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
     <option value="accepted" <?= $request['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
     <option value="rejected" <?= $request['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>

    <label>Reject Reason (if rejected):</label>
    <textarea name="rejectation_reason"><?= $request['rejectation_reason'] ?></textarea>

    <button type="submit">Update</button>
   </form>



  </div>
 </div>
</div>
<?= $this->endSection() ?>