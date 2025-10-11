<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h2>All Virtual Addresses</h2>
<a href="/warehouse/create" class="btn btn-primary mb-3">Add New Address</a>

<table class="table table-bordered">
 <thead>
  <tr>
   <th>User ID</th>
   <th>Country</th>
   <th>Address</th>
   <th>Postal Code</th>
   <th>Phone</th>
   <th>Default</th>
   <th>Actions</th>
  </tr>
 </thead>
 <tbody>
  <?php foreach ($addresses as $addr): ?>
   <tr>
    <td><?= esc($addr['user_id']) ?></td>
    <td><?= esc($addr['country']) ?></td>
    <td><?= esc($addr['address_line']) ?></td>
    <td><?= esc($addr['postal_code']) ?></td>
    <td><?= esc($addr['phone']) ?></td>
    <td><?= $addr['is_default'] ? 'Yes' : 'No' ?></td>
    <td>
     <a href="/warehouse/edit/<?= $addr['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
     <a href="/warehouse/delete/<?= $addr['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
    </td>
   </tr>
  <?php endforeach; ?>
 </tbody>
</table>
<?= $this->endSection() ?>