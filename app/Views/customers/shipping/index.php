<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">




  <!-- Bookings Table -->
  <div>
   <div class="table-header">
    <div class="row align-items-center p-3">
     <div class="col-lg-6 text-start">
      <h5 class="mb-0"><i class="fas fa-list me-2"></i>Shipping Requests</h5>
     </div>
     <div class="col-lg-6 text-end">
      <a href="<?= base_url('shipping/rates') ?>" class="btn bg-white text-dark">
       <i class="fas fa-plus"></i> Book new Shipping
      </a>
     </div>
    </div>
   </div>
   <div class="card-body">
    <?php if (!$requests) : ?>
     <div class="table-responsive">
      <div class="py-5 text-center">
       <p>You have not submitted any request yet. <br>To book, please click <a href="<?= base_url('shipping/rates') ?>">here</a></p>
      </div>
     </div>
    <?php else : ?>
     <div class="table-responsive">
      <table class="table table-hover">
       <thead>
        <tr>
         <th>ID</th>
         <th>Item Name</th>
         <th>Weight</th>
         <th>Height</th>
         <th>Length</th>
         <th>Width</th>
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
          <td><?= $req['height'] ?> <small>cm</small></td>
          <td><?= $req['length'] ?> <small>cm</small></td>
          <td><?= $req['width'] ?> <small>cm</small></td>
          <td class="fw-bold"><?= $req['currency'] . ' ' . $req['total_charge'] ?></td>
          <td><?= fullname($req['user_id']) ?></td>
          <td><span class="status-badge bg-success"><?= $req['delivery_time'] ?></span></td>
          <td><?= statusBadge($req['status']) ?></td>

          <td>
           <div class="btn-group">
            <a href="/customer/shipping/details/<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-eye"></i></a>
            <a href="/customer/shipping/delete/<?= $req['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger btn-action"><i class="fas fa-trash"></i></a>
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
 </div>
 <?= $this->endSection() ?>