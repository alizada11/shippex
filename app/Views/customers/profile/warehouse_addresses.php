<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
 <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-shippex-purple mb-0"><i class="fas fa-warehouse me-2"></i>Our Warehouse Locations</h2>
 </div>

 <!-- Enhanced Tabs -->
 <div class="mb-4">
  <ul class="nav nav-pills" id="warehouseTabs" role="tablist">
   <?php foreach ($warehouses as $index => $wh): ?>
    <li class="nav-item me-2" role="presentation">
     <button class="nav-link d-flex align-items-center <?= $index === 0 ? 'active' : '' ?>"
      id="tab-<?= $index ?>"
      data-bs-toggle="pill"
      data-bs-target="#content-<?= $index ?>"
      type="button"
      role="tab"
      aria-controls="content-<?= $index ?>"
      aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
      <i class="fas fa-map-marker-alt me-2"></i>
      <?= esc($wh['country']) ?>
     </button>
    </li>
   <?php endforeach; ?>
  </ul>
 </div>

 <!-- Tab Content -->
 <div class="tab-content" id="warehouseTabsContent">
  <?php foreach ($warehouses as $index => $wh): ?>
   <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
    id="content-<?= $index ?>"
    role="tabpanel"
    aria-labelledby="tab-<?= $index ?>">

    <div class="card border-0 shadow-sm">
     <div class="card-header bg-shippex-light">
      <h4 class="mb-0 text-shippex-purple">
       <i class="fas fa-warehouse me-2"></i>
       <?= esc($wh['city']) ?>, <?= esc($wh['country']) ?>
      </h4>
     </div>
     <div class="card-body">
      <div class="row g-3">
       <div class="col-md-6">
        <div class="info-item">
         <div class="icon-circle bg-shippex-light text-shippex-purple">
          <i class="fas fa-map-marked-alt"></i>
         </div>
         <div>
          <h6 class="text-shippex-purple">Address</h6>
          <p class="mb-0"><?= esc($wh['address_line']) ?></p>
          <p class="mb-0"><?= esc($wh['city']) ?>, <?= esc($wh['postal_code']) ?></p>
          <p class="mb-0"><?= esc($wh['country']) ?></p>
         </div>
        </div>
       </div>

       <div class="col-md-6">
        <div class="info-item">
         <div class="icon-circle bg-shippex-light text-shippex-purple">
          <i class="fas fa-phone-alt"></i>
         </div>
         <div>
          <h6 class="text-shippex-purple">Contact</h6>
          <p class="mb-0"><?= esc($wh['phone']) ?></p>
         </div>
        </div>
       </div>
      </div>

      <?php if (!empty($wh['operating_hours'])): ?>
       <div class="row mt-3">
        <div class="col-12">
         <div class="info-item">
          <div class="icon-circle bg-shippex-light text-shippex-purple">
           <i class="fas fa-clock"></i>
          </div>
          <div>
           <h6 class="text-shippex-purple">Operating Hours</h6>
           <p class="mb-0"><?= esc($wh['operating_hours']) ?></p>
          </div>
         </div>
        </div>
       </div>
      <?php endif; ?>
     </div>
    </div>

   </div>
  <?php endforeach; ?>
 </div>
</div>

<style>
 .bg-shippex-purple {
  background-color: #4E148C !important;
 }

 .text-shippex-purple {
  color: #4E148C !important;
 }

 .bg-shippex-light {
  background-color: #F0E6FF !important;
 }

 .btn-shippex-orange {
  background-color: #FF6600;
  color: white;
  border-color: #FF6600;
 }

 .nav-pills .nav-link.active {
  background-color: #4E148C;
  color: white;
 }

 .nav-pills .nav-link {
  color: #4E148C;
  border: 1px solid #dee2e6;
 }

 .icon-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  flex-shrink: 0;
 }

 .info-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 15px;
 }

 .card {
  border-radius: 0.5rem;
  overflow: hidden;
 }
</style>

<?= $this->endSection() ?>