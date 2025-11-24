<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->



 <!-- Bookings Table -->
 <div class="card">
  <div class="card-header">
   <div class="row align-items-center">
    <div class="col-lg-6 text-start">
     <h5 class="mb-0"><i class="fas fa-list me-2"></i>Shipping Requests</h5>
    </div>
    <div class="col-lg-6 text-end">
     <a href="<?= base_url('shipping/rates') ?>" class="btn btn-shippex-orange">
      <i class="fas fa-plus"></i> Book new Shipping
     </a>
    </div>
   </div>
  </div>
  <div class="card-body p-0">
   <?php if (!$requests) : ?>
    <div class="table-responsive">
     <div class="py-5 text-center">
      <p>You have not submitted any request yet. <br>To book, please click <a href="<?= base_url('shipping/rates') ?>">here</a></p>
     </div>
    </div>
   <?php else : ?>
    <div class="table-responsive">
     <table class="table table-hover">
      <thead class="table-light">
       <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Weight</th>
        <th>Dimensions <small>(L×W×H cm)</small></th>
        <th>Total Charge</th>
        <th>User</th>
        <th>Delivery Time</th>
        <th>Status</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($requests as $req) : ?>
        <tr>
         <td><?= $req['id'] ?></td>
         <td><?= $req['category'] ?></td>
         <td><?= $req['weight'] ?> <small>kg</small></td>
         <td>(<?= $req['height'] . 'x' . $req['length'] . 'x' . $req['width'] ?>)<small>cm</small></td>
         <td class="fw-bold"><?= ($req['total_charge'] >= 1) ? ($req['currency'] . ' ' . $req['total_charge']) : 'N/A' ?></td>
         <td><?= fullname($req['user_id']) ?></td>
         <td><span class="status-badge bg-success"><?= ($req['delivery_time']) ? $req['delivery_time'] : 'N/A' ?></span></td>
         <td><?= statusBadge($req['status']) ?></td>

         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="/customer/shipping/details/<?= $req['id'] ?>" class="btn btn-action view"><i class="fas fa-eye"></i></a>
           <form class="delete-form" action="<?= base_url('customer/shipping/delete/' . $req['id']) ?>" method="post" class="d-inline delete-form">
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
   <?php endif; ?>
   <div class="row mt-4">
    <?= $pager->links('default', 'bootstrap_full') ?>
   </div>
  </div>
 </div>
 <?= $this->endSection() ?>