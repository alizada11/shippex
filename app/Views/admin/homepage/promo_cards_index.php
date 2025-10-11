<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <div class="row align-items-center">

     <div class="mb-4 d-flex justify-content-between">
      <h2 class="mb-0">Manage Promotion Section</h2>
      <a href="<?= site_url('/admin/cms/promo-cards/create') ?>" class="btn btn-primary mb-3">Add Promo Card</a>
     </div>
     <table class="table table-bordered">
      <thead>
       <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Button Text</th>
        <th>Button URL</th>
        <th>Image</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       <?php foreach ($promoCards as $card): ?>
        <tr>
         <td><?= esc($card['title']) ?></td>
         <td><?= esc($card['description']) ?></td>
         <td><?= esc($card['button_text']) ?></td>
         <td><?= esc($card['button_url']) ?></td>
         <td>
          <?php if ($card['image']): ?>
           <img src="<?= base_url('uploads/promo_cards/' . $card['image']) ?>" width="80">
          <?php endif; ?>
         </td>
         <td>
          <a href="<?= site_url('/admin/cms/promo-cards/edit/' . $card['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="<?= site_url('/admin/cms/promo-cards/delete/' . $card['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this card?')">Delete</a>
         </td>
        </tr>
       <?php endforeach; ?>
      </tbody>
     </table>



    </div>
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>