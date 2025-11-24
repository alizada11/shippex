<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <div class="row">

  <!-- Bookings Table -->
  <div class="card px-0 shadow-sm border-0">

   <div class="card-header premium-header p-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Shipping Requests</h5>
   </div>
   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover">
      <thead class="table-light">
       <tr>
        <th>ID</th>
        <th> Item Name</th>
        <th>Weight</th>
        <th>Dimensions <small>(L×W×H cm)</small></th>
        <th>Total Charge</th>
        <th>User</th>
        <th>Delivery Time</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($requests as $req): ?>
        <tr>
         <td><?= $req['id'] ?></td>
         <td><?= $req['category'] ?></td>
         <td><?= $req['weight'] ?> <small>kg</small></td>
         <td>(<?= $req['height'] . 'x' . $req['length'] . 'x' . $req['width'] ?>)<small>cm</small></td>
         <td class="fw-bold"><?= ($req['total_charge'] >= 1) ? ($req['currency'] . ' ' . $req['total_charge']) : 'N/A' ?></td>
         <td><?= fullname($req['user_id']) ?></td>
         <td><span class="status-badge bg-success"><?= !empty($req['delivery_time']) ? $req['delivery_time'] : 'N/A' ?></span></td>
         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="/shipping/details/<?= $req['id'] ?>" class="btn btn-action view"><i class="fas fa-eye"></i></a>
           <form class="delete-form" action="<?= base_url('shipping/delete/' . $req['id']) ?>" method="post" class="d-inline delete-form">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
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

   </div>
  </div>
 </div>
 <?= $this->endSection() ?>