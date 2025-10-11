<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row mb-5 g-4">
 <div class="col-md-4">
  <div class="card text-white bg-primary mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-users"></i> Shipping Requests</h5>
    <p class="card-text fs-4"><?= $shipping_requests ?></p>
   </div>
  </div>
 </div>

 <div class="col-md-4">
  <div class="card text-white bg-success mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Shopper Requests</h5>
    <p class="card-text fs-4"><?= $shopper_requests ?></p>
   </div>
  </div>
 </div>

 <div class="col-md-4">
  <div class="card text-white bg-warning mb-3">
   <div class="card-body">
    <h5 class="card-title"><i class="fas fa-address-book"></i> Addresses</h5>
    <p class="card-text fs-4"><?= $addresses ?></p>
   </div>
  </div>
 </div>
</div>
<div class="card mb-4">
 <div class="card-header bg-primary text-white">
  <i class="fas fa-warehouse me-2"></i> Our Warehouse Address
 </div>
 <div class="card-body">
  <?php if ($address): ?>
   <div class="row">
    <?php foreach ($address as $wh): ?>
     <div class="col-lg-6 col-xl-4 mb-4">
      <div class="card h-100 border-0 shadow-sm">
       <div class="card-header bg-shippex-purple text-white">
        <h5 class="mb-0">
         <i class="fas fa-warehouse me-2"></i>
         <?= esc($wh['city']) ?>, <?= esc($wh['country']) ?>
        </h5>
       </div>
       <div class="card-body">
        <div class="info-item mb-3">
         <div class="d-flex align-items-start">
          <div class="icon-circle bg-shippex-light text-shippex-purple me-3">
           <i class="fas fa-map-marked-alt"></i>
          </div>
          <div>
           <h6 class="text-shippex-purple mb-1">Address</h6>
           <p class="mb-1"><?= esc($wh['address_line']) ?></p>
           <p class="mb-1"><?= esc($wh['city']) ?>, <?= esc($wh['postal_code']) ?></p>
           <p class="mb-0"><?= esc($wh['country']) ?></p>
          </div>
         </div>
        </div>

        <div class="info-item mb-3">
         <div class="d-flex align-items-start">
          <div class="icon-circle bg-shippex-light text-shippex-purple me-3">
           <i class="fas fa-phone-alt"></i>
          </div>
          <div>
           <h6 class="text-shippex-purple mb-1">Contact</h6>
           <p class="mb-0"><?= esc($wh['phone']) ?></p>
          </div>
         </div>
        </div>

        <?php if (!empty($wh['operating_hours'])): ?>
         <div class="info-item">
          <div class="d-flex align-items-start">
           <div class="icon-circle bg-shippex-light text-shippex-purple me-3">
            <i class="fas fa-clock"></i>
           </div>
           <div>
            <h6 class="text-shippex-purple mb-1">Operating Hours</h6>
            <p class="mb-0"><?= esc($wh['operating_hours']) ?></p>
           </div>
          </div>
         </div>
        <?php endif; ?>
       </div>
      </div>
     </div>
    <?php endforeach; ?>
   </div>
  <?php else: ?>
   <div class="text-center py-4">
    <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
    <p class="text-danger mb-0">No warehouse address assigned yet. Please contact support.</p>
   </div>
  <?php endif; ?>
 </div>
</div>

<style>
 .icon-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
 }

 .info-item {
  padding: 0.5rem 0;
 }
</style>

<?= $this->endSection() ?>