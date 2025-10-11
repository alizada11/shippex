<?= $this->extend('customers/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
 <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-shippex-purple mb-0"><i class="fas fa-address-book me-2"></i>Your Addresses</h2>
  <a href="/addresses/create" class="btn btn-shippex-orange">
   <i class="fas fa-plus-circle me-2"></i>Add New Address
  </a>
 </div>

 <div class="row g-4">
  <!-- Shipping Addresses Column -->
  <div class="col-12 col-lg-6">
   <div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-shippex-purple text-white">
     <h3 class="mb-0"><i class="fas fa-truck me-2"></i>Shipping Addresses</h3>
    </div>
    <div class="card-body">


     <?php if (empty($shippingAddresses)): ?>
      <div class="text-center py-4">
       <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
       <p class="text-muted">No shipping addresses saved</p>
      </div>
     <?php else: ?>
      <div class="row g-3">
       <?php foreach ($shippingAddresses as $address): ?>
        <div class="col-12">
         <div class="card address-card <?= $address['is_default'] ? 'border-shippex-orange' : '' ?>">
          <div class="card-body">
           <?php if ($address['is_default']): ?>
            <p class="text-muted mb-4 text-center">
             <i class="fas fa-info-circle me-2"></i>We will send your packages to these addresses
            </p>
            <span class="badge bg-shippex-orange mb-2">
             <i class="fas fa-star me-1"></i>Primary Address
            </span>
           <?php endif; ?>

           <h5 class="text-shippex-purple mb-3"><?= esc($address['first_name'] . ' ' . $address['last_name']) ?></h5>
           <address class="mb-0">
            <i class="fas fa-map-marker-alt text-shippex-orange me-2"></i><?= esc($address['street_address1']) ?><br>
            <?= esc($address['city']) ?>, <?= esc($address['state']) ?> <?= esc($address['zip_code']) ?><br>
            <?= esc($address['country']) ?><br>
            <i class="fas fa-phone text-shippex-orange me-2"></i><?= esc($address['phone_primary']) ?>
           </address>
          </div>
          <div class="card-footer bg-transparent border-top-0">
           <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('addresses/edit/' . $address['id']) ?>" class="btn btn-outline-shippex-purple btn-sm">
             <i class="fas fa-edit me-1"></i>Edit
            </a>
            <?php if (!$address['is_default']): ?>
             <button type="button" class="btn btn-outline-shippex-orange btn-sm"
              data-set-primary-address
              data-address-url="<?= site_url('addresses/primary/' . $address['id']) ?>">
              <i class="fas fa-star me-1"></i>Set Primary
             </button>
             <button type="button" class="btn btn-outline-danger btn-sm"
              data-delete-address
              data-address-url="<?= site_url('addresses/delete/' . $address['id']) ?>">
              <i class="fas fa-trash-alt me-1"></i>Delete
             </button>
            <?php endif; ?>
           </div>
          </div>
         </div>
        </div>
       <?php endforeach; ?>
      </div>
     <?php endif; ?>
    </div>
   </div>
  </div>

  <!-- Billing Addresses Column -->
  <div class="col-12 col-lg-6">
   <div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-shippex-purple text-white">
     <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i>Billing Addresses</h3>
    </div>
    <div class="card-body">


     <?php if (empty($billingAddresses)): ?>
      <div class="text-center py-4">
       <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
       <p class="text-muted">No billing addresses saved</p>
      </div>
     <?php else: ?>
      <div class="row g-3">
       <?php foreach ($billingAddresses as $address): ?>
        <div class="col-12">
         <div class="card address-card <?= $address['is_default'] ? 'border-shippex-orange' : '' ?>">
          <div class="card-body">
           <?php if ($address['is_default']): ?>
            <p class="text-muted mb-4 text-center">
             <i class="fas fa-info-circle me-2"></i>We will bill to these addresses
            </p>
            <span class="badge bg-shippex-orange mb-2">
             <i class="fas fa-star me-1"></i>Primary Address
            </span>
           <?php endif; ?>

           <h5 class="text-shippex-purple mb-3"><?= esc($address['first_name'] . ' ' . $address['last_name']) ?></h5>
           <address class="mb-0">
            <i class="fas fa-map-marker-alt text-shippex-orange me-2"></i><?= esc($address['street_address1']) ?><br>
            <?= esc($address['city']) ?>, <?= esc($address['state']) ?> <?= esc($address['zip_code']) ?><br>
            <?= esc($address['country']) ?><br>
            <i class="fas fa-phone text-shippex-orange me-2"></i><?= esc($address['phone_primary']) ?>
           </address>
          </div>
          <div class="card-footer bg-transparent border-top-0">
           <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('addresses/edit/' . $address['id']) ?>" class="btn btn-outline-shippex-purple btn-sm">
             <i class="fas fa-edit me-1"></i>Edit
            </a>
            <?php if (!$address['is_default']): ?>
             <button type="button" class="btn btn-outline-shippex-orange btn-sm"
              data-set-primary-address
              data-address-url="<?= site_url('addresses/primary/' . $address['id']) ?>">
              <i class="fas fa-star me-1"></i>Set Primary
             </button>
             <button type="button" class="btn btn-outline-danger btn-sm"
              data-delete-address
              data-address-url="<?= site_url('addresses/delete/' . $address['id']) ?>">
              <i class="fas fa-trash-alt me-1"></i>Delete
             </button>
            <?php endif; ?>
           </div>
          </div>
         </div>
        </div>
       <?php endforeach; ?>
      </div>
     <?php endif; ?>
    </div>
   </div>
  </div>
 </div>
</div>


<script>
 // Add confirmation dialogs for delete/set primary actions
 document.querySelectorAll('[data-delete-address]').forEach(button => {
  button.addEventListener('click', function() {
   const message = this.getAttribute('data-address-confirm') || 'Are you sure?';
   if (confirm(message)) {
    window.location.href = this.getAttribute('data-address-url');
   }
  });
 });

 document.querySelectorAll('[data-set-primary-address]').forEach(button => {
  button.addEventListener('click', function() {
   const message = this.getAttribute('data-address-confirm') || 'Are you surssssse?';
   if (confirm(message)) {
    window.location.href = this.getAttribute('data-address-url');
   }
  });
 });
</script>

<?= $this->endSection() ?>