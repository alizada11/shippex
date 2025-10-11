<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
 <!-- Stats Overview -->
 <div class="row">
  <div class="card">
   <div class="card-body">
    <h1 class="mb-4">How It Works - Admin</h1>

    <!-- Tabs for all 3 sections -->
    <ul class="nav nav-tabs mb-4" id="howItWorksTabs" role="tablist">
     <li class="nav-item" role="presentation">
      <button class="nav-link active" id="intro-tab" data-bs-toggle="tab" data-bs-target="#intro" type="button" role="tab">How It Works</button>
     </li>
     <li class="nav-item" role="presentation">
      <button class="nav-link" id="steps-tab" data-bs-toggle="tab" data-bs-target="#steps" type="button" role="tab">Steps</button>
     </li>
     <li class="nav-item" role="presentation">
      <button class="nav-link" id="why-choose-tab" data-bs-toggle="tab" data-bs-target="#whyChoose" type="button" role="tab">Why Choose Us</button>
     </li>
    </ul>

    <div class="tab-content" id="howItWorksTabsContent">

     <!-- How It Works Intro -->
     <div class="tab-pane fade show active" id="intro" role="tabpanel">
      <form action="
      <?= isset($section) ? site_url('admin/how-it-works/update/' . $section['id']) : site_url('admin/how-it-works/store') ?>" method="post" enctype="multipart/form-data">
       <div class="mb-3">
        <label>Title</label>
        <input type="text" value="<?= esc($section['title']) ?>" name="title" class="form-control">
       </div>
       <div class="mb-3">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="4"><?= esc($section['description'], 'raw') ?></textarea>
       </div>

       <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        <img src="<?= base_url('uploads/how_it_works/' . $section['image']) ?>" width="50px" height="50px" alt="">
       </div>
       <div class="mb-3">
        <label>Button Text</label>
        <input type="text" value="<?= esc($section['button_text']) ?>" name="button_text" class="form-control">
       </div>
       <div class="mb-3">
        <label>Button Link</label>
        <input type="text" value="<?= esc($section['button_link']) ?>" name="button_link" class="form-control">
       </div>

       <button class="btn btn-primary">Save</button>
      </form>
     </div>

     <!-- Steps Tab -->
     <div class="tab-pane fade" id="steps" role="tabpanel">
      <div class="mb-3">
       <a href="<?= site_url('admin/cms/steps/create') ?>" class="btn btn-primary">Add New Step</a>
      </div>
      <table class="table table-bordered ">
       <thead>
        <tr>
         <th>ID</th>
         <th>Title</th>
         <th>Description</th>
         <th>Image</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php if (!empty($steps)): ?>
         <?php foreach ($steps as $step): ?>
          <tr>
           <td><?= $step['id'] ?></td>
           <td><?= esc($step['title']) ?></td>
           <td><?= esc($step['description']) ?></td>
           <td>
            <?php if (!empty($step['image'])): ?>
             <img src="<?= base_url('uploads/' . $step['image']) ?>" alt="Step Image" width="80">
            <?php endif; ?>
           </td>
           <td class="d-flex">
            <a href="<?= site_url('admin/cms/steps/edit/' . $step['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="<?= site_url('admin/cms/steps/delete/' . $step['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
           </td>
          </tr>
         <?php endforeach; ?>
        <?php else: ?>
         <tr>
          <td colspan="5" class="text-center">No steps found.</td>
         </tr>
        <?php endif; ?>
       </tbody>
      </table>
     </div>

     <!-- Why Choose Us Tab -->
     <div class="tab-pane fade" id="whyChoose" role="tabpanel">
      <div class="mb-3">
       <a href="<?= site_url('admin/cms/why-choose/create') ?>" class="btn btn-primary">Add New Item</a>
      </div>
      <table class="table table-bordered">
       <thead>
        <tr>
         <th>ID</th>
         <th>Title</th>
         <th>Description</th>
         <th>Icon/Image</th>
         <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        <?php if (!empty($why)): ?>
         <?php foreach ($why as $item): ?>
          <tr>
           <td><?= $item['id'] ?></td>
           <td><?= esc($item['title']) ?></td>
           <td><?= esc($item['description']) ?></td>
           <td>
            <?php if (!empty($item['icon'])): ?>
             <img src="<?= base_url('uploads/why_choose/' . $item['icon']) ?>" alt="Icon" width="50">
            <?php endif; ?>
           </td>
           <td class="d-flex gap-1 justify-content-between">
            <a href="<?= site_url('admin/why-choose/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="<?= site_url('admin/why-choose/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
           </td>
          </tr>
         <?php endforeach; ?>
        <?php else: ?>
         <tr>
          <td colspan="5" class="text-center">No items found.</td>
         </tr>
        <?php endif; ?>
       </tbody>
      </table>
     </div>

    </div>
   </div>
  </div>
 </div>
</div>
<?= $this->endSection() ?>