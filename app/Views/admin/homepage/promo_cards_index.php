<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="row align-items-center">

    <div class="card-header border-0 align-items-center d-flex justify-content-between">
     <h5 class="mb-0">Manage Promotion Section</h5>
     <a href="<?= site_url('/admin/cms/promo-cards/create') ?>" class="btn btn-shippex-orange "><i class="fas fa-plus"></i> Add Promo Card</a>
    </div>
    <div class="card-body p-0 table-responsive">
     <table class="table table-hover">
      <thead class="table-light">
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

         <td class="actions-col">
          <div class="action-buttons-table">
           <a href="<?= site_url('/admin/cms/promo-cards/edit/' . $card['id']) ?>" class="btn btn-action edit">
            <i class="fas fa-edit "></i>
           </a>

           <form action="<?= site_url('/admin/cms/promo-cards/delete/' . $card['id']) ?>" class="delete-form" method="post">
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
   </div>
  </div>
 </div>
</div>

<?= $this->endSection() ?>