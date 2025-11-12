<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>


<div class="container py-4">
 <!-- Page Header -->
 <div class="page-header justify-content-between">
  <div>
   <h1 class="page-title">
    <i class="fas fa-warehouse"></i>
    Warehouses
   </h1>
   <p class="text-muted mb-0">Manage your global warehouse locations and pages</p>
  </div>
  <a href="<?= site_url('admin/w_pages/create') ?>" class="btn-shippex-orange">
   <i class="fas fa-plus"></i>
   Add New Warehouse
  </a>
 </div>




 <!-- Warehouses Grid -->
 <?php if (!empty($warehouses)): ?>
  <div class="warehouse-grid" id="warehouseGrid">
   <?php foreach ($warehouses as $w): ?>
    <div class="warehouse-card" data-status="<?= $w['is_active'] ? 'active' : 'inactive' ?>" data-country="<?= strtolower(esc($w['country_name'])) ?>" data-code="<?= strtolower(esc($w['country_code'])) ?>">
     <div class="warehouse-header justify-content-between">
      <h3 class="warehouse-country">
       <i class="fas fa-flag"></i>
       <?= esc($w['country_name']) ?>
      </h3>
      <span class="warehouse-code"><?= esc($w['country_code']) ?></span>
     </div>
     <div class="warehouse-body">
      <div class="warehouse-meta">
       <span class="status-badge <?= $w['is_active'] ? 'status-active' : 'status-inactive' ?>">
        <i class="fas fa-<?= $w['is_active'] ? 'check-circle' : 'pause-circle' ?>"></i>
        <?= $w['is_active'] ? 'Active' : 'Inactive' ?>
       </span>
       <small class="text-muted">
        <i class="fas fa-calendar"></i>
        Created <?= date('M j, Y', strtotime($w['created_at'] ?? 'now')) ?>
       </small>
      </div>
      <div class="warehouse-actions">
       <a href="<?= site_url('admin/w_pages/edit/' . $w['id']) ?>" class="btn-action edit">
        <i class="fas fa-edit"></i>

       </a>
       <a href="<?= base_url('warehouse/' . $w['country_code']) ?>" class="btn-action view">
        <i class="fas fa-eye"></i>
       </a>
       <form class="delete-form" action="<?= site_url('admin/w_pages/delete/' . $w['id']) ?>" method="post" class="d-inline delete-form">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-action delete "><i class="fas fa-trash"></i></button>
       </form>
      </div>
     </div>
    </div>
   <?php endforeach; ?>
  </div>
 <?php else: ?>
  <!-- Empty State -->
  <div class="empty-state">
   <i class="fas fa-warehouse"></i>
   <h3>No Warehouses Found</h3>
   <p class="text-muted">Get started by creating your first warehouse page.</p>
   <a href="<?= site_url('admin/w_pages/create') ?>" class="btn-shippex-primary mt-3">
    <i class="fas fa-plus"></i>
    Create First Warehouse
   </a>
  </div>
 <?php endif; ?>
</div>


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

 .page-header {
  display: flex;
  justify-content: between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--shippex-border);
 }

 .page-title {
  color: var(--shippex-dark);
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
 }

 .page-title i {
  color: var(--shippex-primary);
 }

 .btn-shippex-primary {
  background: var(--shippex-primary);
  border: none;
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  transition: all 0.2s;
  color: white;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
 }

 .btn-shippex-primary:hover {
  background: #0d5bb8;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
  color: white;
 }

 .warehouse-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border: 1px solid var(--shippex-border);
  overflow: hidden;
 }

 .warehouse-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
 }

 .warehouse-header {
  background: linear-gradient(135deg, var(--shippex-primary), #6b12caff);
  color: white;
  padding: 1.25rem;
  display: flex;
  justify-content: between;
  align-items: center;
 }

 .warehouse-country {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
 }

 .warehouse-code {
  background: rgba(255, 255, 255, 0.2);
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
 }

 .warehouse-body {
  padding: 1.25rem;
 }

 .warehouse-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
 }

 .status-badge {
  padding: 0.375rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
 }

 .status-active {
  background: #e6f4ea;
  color: var(--shippex-success);
 }

 .status-inactive {
  background: #fce8e6;
  color: #c5221f;
 }

 .warehouse-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
 }




 .empty-state {
  text-align: center;
  padding: 3rem 2rem;
  color: #5f6368;
 }

 .empty-state i {
  font-size: 3rem;
  color: #dadce0;
  margin-bottom: 1rem;
 }

 .empty-state h3 {
  color: #5f6368;
  margin-bottom: 0.5rem;
 }

 .warehouse-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
 }

 .stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
 }

 .stat-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border-left: 4px solid var(--shippex-primary);
 }

 .stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: var(--shippex-dark);
  margin: 0;
 }

 .stat-label {
  color: #5f6368;
  font-size: 0.875rem;
  margin: 0;
 }


 @media (max-width: 768px) {
  .warehouse-grid {
   grid-template-columns: 1fr;
  }

  .page-header {
   flex-direction: column;
   align-items: flex-start;
   gap: 1rem;
  }

 }
</style>
<?= $this->endSection(); ?>