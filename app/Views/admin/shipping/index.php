<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">




  <!-- Bookings Table -->
  <div>
   <div class="table-header">
    <div class="p-3">
     <h5 class="mb-0"><i class="fas fa-list me-2"></i>Shipping Requests</h5>
    </div>
   </div>
   <div class="card-body">
    <div class="table-responsive">
     <table class="table table-hover">
      <thead>
       <tr>
        <th>ID</th>
        <th> Item Name</th>
        <th>Weight</th>
        <th>Height</th>
        <th> Length</th>
        <th> Width</th>
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
         <td><?= $req['height'] ?> <small>cm</small></td>
         <td><?= $req['length'] ?> <small>cm</small></td>
         <td><?= $req['width'] ?> <small>cm</small></td>
         <td class="fw-bold"><?= $req['currency'] . ' ' . $req['total_charge'] ?></td>
         <td><?= fullname($req['user_id']) ?></td>
         <td><span class="status-badge bg-success"><?= $req['delivery_time'] ?></span></td>
         <td>
          <div class="btn-group">
           <a href="/shipping/details/<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a>
           <a href="/shipping/delete/<?= $req['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger btn-action"><i class="fas fa-trash"></i></a>
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