<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$countries = json_decode(file_get_contents(__DIR__ . '/../../partials/countries.json'), true);
?>


<form action="<?= base_url('admin/w_pages/update/' . $warehouse['id']) ?>" method="post" enctype="multipart/form-data">
 <div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
   <div>
    <h1 class="page-title">
     <i class="fas fa-warehouse"></i>
     Edit Warehouse: <?= esc($warehouse['country_name']) ?> (<?= esc($warehouse['country_code']) ?>)
    </h1>
    <p class="page-subtitle">Update warehouse details and page content</p>
   </div>
   <div class="d-flex gap-2">
    <a href="<?= site_url('warehouses') ?>" class="btn btn-shippex-secondary">
     <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
    <button type="submit" class="btn btn-shippex-primary">
     <i class="fas fa-save me-2"></i>Update Warehouse
    </button>
   </div>
  </div>

  <!-- COUNTRY INFO -->
  <div class="shippex-card mb-4">
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
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- HERO SECTION -->
  <div class="shippex-card mb-4">
   <div class="shippex-card-header">
    <h4><i class="fas fa-star"></i> Hero Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Hero Title</label>
     <input type="text" name="hero_title" value="<?= esc($warehouse['hero_title']) ?>" class="form-control" placeholder="Enter compelling hero title">
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Description 1</label>
       <textarea name="hero_description_1" class="form-control" rows="3" placeholder="Enter first description"><?= esc($warehouse['hero_description_1']) ?></textarea>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Description 2</label>
       <textarea name="hero_description_2" class="form-control" rows="3" placeholder="Enter second description"><?= esc($warehouse['hero_description_2']) ?></textarea>
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">CTA Text</label>
       <input type="text" name="hero_cta_text" value="<?= esc($warehouse['hero_cta_text']) ?>" class="form-control" placeholder="Call-to-action text">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">CTA Link</label>
       <input type="text" name="hero_cta_link" value="<?= esc($warehouse['hero_cta_link']) ?>" class="form-control" placeholder="Call-to-action URL">
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Banner Image</label>
       <input type="file" name="banner_image" class="form-control">
       <?php if (!empty($warehouse['banner_image'])): ?>
        <div class="image-preview-container">
         <div class="image-preview">
          <img src="<?= base_url('uploads/warehouses/' . $warehouse['banner_image']) ?>" class="img-thumbnail">
          <div class="image-preview-label">Current Banner</div>
         </div>
        </div>
       <?php endif; ?>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Hero Image</label>
       <input type="file" name="hero_image" class="form-control">
       <?php if (!empty($warehouse['hero_image'])): ?>
        <div class="image-preview-container">
         <div class="image-preview">
          <img src="<?= base_url('uploads/warehouses/' . $warehouse['hero_image']) ?>" class="img-thumbnail">
          <div class="image-preview-label">Current Hero</div>
         </div>
        </div>
       <?php endif; ?>
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- BRANDS SECTION -->
  <div class="shippex-card mb-4">
   <div class="shippex-card-header">
    <h4><i class="fas fa-tags"></i> Brands Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Brands Title</label>
     <input type="text" name="brands_title" value="<?= esc($warehouse['brands_title']) ?>" class="form-control" placeholder="Enter brands section title">
    </div>

    <div class="form-section">
     <label class="form-label">Brands Text</label>
     <textarea name="brands_text" class="form-control" rows="4" placeholder="Enter brands description"><?= esc($warehouse['brands_text']) ?></textarea>
    </div>

    <div class="form-section">
     <label class="form-label">Brands Image</label>
     <input type="file" name="brands_image" class="form-control">
     <?php if (!empty($warehouse['brands_image'])): ?>
      <div class="image-preview-container">
       <div class="image-preview">
        <img src="<?= base_url('uploads/warehouses/' . $warehouse['brands_image']) ?>" class="img-thumbnail">
        <div class="image-preview-label">Current Brands Image</div>
       </div>
      </div>
     <?php endif; ?>
    </div>
   </div>
  </div>

  <!-- SHIPPING & PAYMENT -->
  <div class="shippex-card mb-4">
   <div class="shippex-card-header">
    <h4><i class="fas fa-shipping-fast"></i> Shipping & Payment</h4>
   </div>
   <div class="card-body p-4">
    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Shipping Text</label>
       <input type="text" name="shipping_text" value="<?= esc($warehouse['shipping_text']) ?>" class="form-control" placeholder="Enter shipping information">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Payment Text</label>
       <input type="text" name="payment_text" value="<?= esc($warehouse['payment_text']) ?>" class="form-control" placeholder="Enter payment information">
      </div>
     </div>
    </div>
   </div>
  </div>

  <!-- BOTTOM SECTION -->
  <div class="shippex-card mb-4">
   <div class="shippex-card-header">
    <h4><i class="fas fa-layer-group"></i> Bottom Section</h4>
   </div>
   <div class="card-body p-4">
    <div class="form-section">
     <label class="form-label">Bottom Title</label>
     <input type="text" name="bottom_title" value="<?= esc($warehouse['bottom_title']) ?>" class="form-control" placeholder="Enter bottom section title">
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Paragraph 1</label>
       <textarea name="bottom_paragraph_1" class="form-control" rows="3" placeholder="Enter first paragraph"><?= esc($warehouse['bottom_paragraph_1']) ?></textarea>
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Paragraph 2</label>
       <textarea name="bottom_paragraph_2" class="form-control" rows="3" placeholder="Enter second paragraph"><?= esc($warehouse['bottom_paragraph_2']) ?></textarea>
      </div>
     </div>
    </div>

    <div class="row">
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Bottom CTA Text</label>
       <input type="text" name="bottom_cta_text" value="<?= esc($warehouse['bottom_cta_text']) ?>" class="form-control" placeholder="Call-to-action text">
      </div>
     </div>
     <div class="col-md-6">
      <div class="form-section">
       <label class="form-label">Bottom CTA Link</label>
       <input type="text" name="bottom_cta_link" value="<?= esc($warehouse['bottom_cta_link']) ?>" class="form-control" placeholder="Call-to-action URL">
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="d-flex justify-content-between mt-4">
   <a href="<?= site_url('warehouses') ?>" class="btn btn-shippex-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to List
   </a>
   <button type="submit" class="btn btn-shippex-primary">
    <i class="fas fa-save me-2"></i>Update Warehouse
   </button>
  </div>
 </div>
</form>

<!-- Font Awesome for icons -->

<style>
 :root {
  --shippex-primary: #4d148c;
  --shippex-secondary: #ff6600;
  --shippex-accent: #fbbc05;
  --shippex-light: #f8f9fa;
  --shippex-dark: #202124;
  --shippex-border: #dadce0;
 }

 .shippex-card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  overflow: hidden;
 }

 .shippex-card:hover {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
 }

 .shippex-card-header {
  background: linear-gradient(135deg, var(--shippex-primary), #6a14c6ff);
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

 .shippex-section {
  margin-bottom: 2rem;
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

 .btn-shippex-primary {
  background: var(--shippex-primary);
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  color: #FFF;
  transition: all 0.2s;
 }

 .btn-shippex-primary:hover {
  background: var(--shippex-secondary);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
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

 .image-preview-container {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  margin-top: 10px;
 }

 .image-preview {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
 }

 .image-preview img {
  max-height: 150px;
  display: block;
 }

 .image-preview-label {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 5px;
  font-size: 0.8rem;
  text-align: center;
 }

 .section-divider {
  height: 2px;
  background: linear-gradient(to right, transparent, var(--shippex-border), transparent);
  margin: 2rem 0;
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

 .required-field::after {
  content: " *";
  color: #d93025;
 }

 .form-section {
  margin-bottom: 1.5rem;
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
<?= $this->endSection(); ?>