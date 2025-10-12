<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>


<?php
$countries = json_decode(file_get_contents(__DIR__ . '/../../partials/countries.json'), true);
?>

<form action="<?= base_url('admin/w_pages/store') ?>" method="post" enctype="multipart/form-data">
 <div class="container py-4">
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('error')): ?>
   <div class="alert-custom alert-error">
    <i class="fas fa-exclamation-circle me-2"></i>
    <?= session()->getFlashdata('error') ?>
   </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
   <div class="alert-custom alert-success">
    <i class="fas fa-check-circle me-2"></i>
    <?= session()->getFlashdata('success') ?>
   </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
   <div>
    <h1 class="page-title">
     <i class="fas fa-plus-circle"></i>
     Create New Warehouse
    </h1>
    <p class="page-subtitle">Set up a new warehouse page for your shipping operations</p>
   </div>
   <div class="d-flex gap-2">
    <a href="<?= site_url('warehouses') ?>" class="btn btn-shippex-secondary">
     <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
    <button type="submit" class="btn btn-shippex-success">
     <i class="fas fa-save me-2"></i>Create Warehouse
    </button>
   </div>
  </div>

  <!-- COUNTRY INFO -->
  <div class="shippex-card">
   <div class="shippex-card-header">
    <h4><i class="fas fa-globe-americas"></i> Country Information</h4>
   </div>
   <div class="card-body p-4">
    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label for="dest_country" class="form-label required-field">Country</label>
       <select class="form-select" id="dest_country" name="country_code" required>
        <option value="">--Select Country--</option>
        <?php foreach ($countries as $ct): ?>
         <option value="<?= esc($ct['code']) ?>"
          <?= (isset($warehouse) && $warehouse['country_code'] == $ct['code']) ? 'selected' : '' ?>>
          <?= esc($ct['name']) ?>
         </option>
        <?php endforeach; ?>
       </select>
       <div class="form-text">Select the country where this warehouse is located</div>
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- HERO SECTION -->
  <div class="shippex-card">
   <div class="shippex-card-header">
    <h4><i class="fas fa-star"></i> Hero Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Hero Title</label>
     <input type="text" name="hero_title" class="form-control" placeholder="Enter compelling hero title that captures attention">
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Description 1</label>
       <textarea name="hero_description_1" class="form-control" rows="3" placeholder="Enter first description highlighting key benefits"></textarea>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Description 2</label>
       <textarea name="hero_description_2" class="form-control" rows="3" placeholder="Enter second description with additional details"></textarea>
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">CTA Text</label>
       <input type="text" name="hero_cta_text" class="form-control" value="START SAVING" placeholder="Call-to-action text">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">CTA Link</label>
       <input type="text" name="hero_cta_link" class="form-control" placeholder="Call-to-action URL (e.g., /register, /quote)">
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Banner Image</label>
       <input type="file" name="banner_image" class="form-control" accept="image/*">
       <div class="image-upload-hint">Recommended: 1200x400px, JPG or PNG format</div>
       <div class="preview-placeholder">
        <i class="fas fa-image"></i>
        <div>Banner image preview will appear here</div>
       </div>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Hero Image</label>
       <input type="file" name="hero_image" class="form-control" accept="image/*">
       <div class="image-upload-hint">Recommended: 600x400px, JPG or PNG format</div>
       <div class="preview-placeholder">
        <i class="fas fa-image"></i>
        <div>Hero image preview will appear here</div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- BRANDS SECTION -->
  <div class="shippex-card">
   <div class="shippex-card-header">
    <h4><i class="fas fa-tags"></i> Brands Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Brands Title</label>
     <input type="text" name="brands_title" class="form-control" placeholder="Enter brands section title">
    </div>

    <div class="form-section">
     <label class="form-label">Brands Text</label>
     <textarea name="brands_text" class="form-control" rows="4" placeholder="Enter brands description or list of supported brands"></textarea>
    </div>

    <div class="form-section">
     <label class="form-label">Brands Image</label>
     <input type="file" name="brands_image" class="form-control" accept="image/*">
     <div class="image-upload-hint">Recommended: 800x600px, JPG or PNG format</div>
     <div class="preview-placeholder">
      <i class="fas fa-image"></i>
      <div>Brands image preview will appear here</div>
     </div>
    </div>
   </div>
  </div>

  <!-- SHIPPING & PAYMENT -->
  <div class="shippex-card">
   <div class="shippex-card-header">
    <h4><i class="fas fa-shipping-fast"></i> Shipping & Payment</h4>
   </div>
   <div class="card-body p-4">
    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Shipping Text</label>
       <input type="text" name="shipping_text" class="form-control" placeholder="Enter shipping information (delivery times, methods, etc.)">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Payment Text</label>
       <input type="text" name="payment_text" class="form-control" placeholder="Enter payment information (methods, security, etc.)">
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- BOTTOM SECTION -->
  <div class="shippex-card">
   <div class="shippex-card-header">
    <h4><i class="fas fa-layer-group"></i> Bottom Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Bottom Title</label>
     <input type="text" name="bottom_title" class="form-control" placeholder="Enter bottom section title">
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Paragraph 1</label>
       <textarea name="bottom_paragraph_1" class="form-control" rows="3" placeholder="Enter first paragraph with additional information"></textarea>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Paragraph 2</label>
       <textarea name="bottom_paragraph_2" class="form-control" rows="3" placeholder="Enter second paragraph with closing remarks"></textarea>
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Bottom CTA Text</label>
       <input type="text" name="bottom_cta_text" class="form-control" placeholder="Final call-to-action text">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Bottom CTA Link</label>
       <input type="text" name="bottom_cta_link" class="form-control" placeholder="Final call-to-action URL">
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="d-flex justify-content-between mt-4">
   <a href="<?= site_url('warehouses') ?>" class="btn btn-shippex-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to List
   </a>
   <button type="submit" class="btn btn-shippex-success">
    <i class="fas fa-save me-2"></i>Create Warehouse
   </button>
  </div>
 </div>
</form>


<style>
 :root {
  --shippex-primary: #4d148c;
  --shippex-secondary: #ff6600;
  --shippex-accent: #fbbc05;
  --shippex-light: #f8f9fa;
  --shippex-dark: #202124;
  --shippex-border: #dadce0;
  --shippex-success: #0d8b68;
 }

 .shippex-card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  overflow: hidden;
  margin-bottom: 1.5rem;
 }

 .shippex-card:hover {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
 }

 .shippex-card-header {
  background: linear-gradient(135deg, var(--shippex-primary), #6f12d3ff);
  color: white;
  padding: 1rem 1.5rem;
  border-bottom: none;
 }

 .shippex-card-header h4 {
  margin: 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
 }

 .shippex-card-header h4 i {
  font-size: 1.2rem;
 }

 .form-label {
  font-weight: 600;
  color: var(--shippex-dark);
  margin-bottom: 0.5rem;
 }

 .form-control,
 .form-select {
  border-radius: 8px;
  border: 1px solid var(--shippex-border);
  padding: 0.75rem 1rem;
  transition: all 0.2s;
 }

 .form-control:focus,
 .form-select:focus {
  border-color: var(--shippex-primary);
  box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.15);
 }

 .btn-shippex-success {
  background: var(--shippex-success);
  border: none;
  border-radius: 8px;
  padding: 0.75rem 2rem;
  font-weight: 600;
  transition: all 0.2s;
  color: white;
 }

 .btn-shippex-success:hover {
  background: #0b775a;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(13, 139, 104, 0.3);
  color: white;
 }

 .btn-shippex-secondary {
  background: #f1f3f4;
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  color: var(--shippex-dark);
  transition: all 0.2s;
 }

 .btn-shippex-secondary:hover {
  background: #e8eaed;
  transform: translateY(-2px);
 }

 .required-field::after {
  content: " *";
  color: #d93025;
 }

 .form-section {
  margin-bottom: 1.5rem;
 }

 .page-title {
  color: var(--shippex-dark);
  font-weight: 700;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
 }

 .page-subtitle {
  color: #5f6368;
  margin-bottom: 2rem;
 }

 .alert-custom {
  border-radius: 8px;
  border: none;
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
 }

 .alert-error {
  background: #fce8e6;
  color: #c5221f;
  border-left: 4px solid #c5221f;
 }

 .alert-success {
  background: #e6f4ea;
  color: #137333;
  border-left: 4px solid #137333;
 }

 .image-upload-hint {
  font-size: 0.875rem;
  color: #5f6368;
  margin-top: 0.5rem;
 }

 .preview-placeholder {
  background: #f8f9fa;
  border: 2px dashed #dadce0;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  color: #5f6368;
  margin-top: 0.5rem;
 }

 .preview-placeholder i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: block;
 }

 @media (max-width: 768px) {
  .shippex-card-header h4 {
   font-size: 1.1rem;
  }

  .page-title {
   font-size: 1.5rem;
  }
 }
</style>
<script>
 // Simple image preview functionality
 document.addEventListener('DOMContentLoaded', function() {
  const fileInputs = document.querySelectorAll('input[type="file"]');

  fileInputs.forEach(input => {
   input.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
     const reader = new FileReader();
     const placeholder = this.nextElementSibling.nextElementSibling;

     reader.onload = function(e) {
      placeholder.innerHTML = `
            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px; display: block;">
            <div style="margin-top: 5px; font-size: 0.8rem; color: #5f6368;">${file.name}</div>
          `;
     }

     reader.readAsDataURL(file);
    }
   });
  });
 });
</script>

<?= $this->endSection(); ?>