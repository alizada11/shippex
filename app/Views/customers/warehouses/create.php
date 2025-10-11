<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h2>Add Warehouse Address</h2>
<form action="/warehouse/store" method="post">
 <div class="form-group">
  <label>User</label>
  <select name="user_id" class="form-control" required>
   <?php foreach ($users as $user): ?>
    <option value="<?= $user['id'] ?>"><?= esc($user['username']) ?> (ID: <?= $user['id'] ?>)</option>
   <?php endforeach; ?>
  </select>
 </div>
 <div class="form-group">
  <label>Country</label>
  <input type="text" name="country" class="form-control" required>
 </div>
 <div class="form-group">
  <label>Address Line</label>
  <textarea name="address_line" class="form-control" required></textarea>
 </div>
 <div class="form-group">
  <label>Postal Code</label>
  <input type="text" name="postal_code" class="form-control" required>
 </div>
 <div class="form-group">
  <label>Phone</label>
  <input type="text" name="phone" class="form-control" required>
 </div>
 <div class="form-check">
  <input type="checkbox" name="is_default" value="1" class="form-check-input" checked>
  <label class="form-check-label">Set as Default</label>
 </div>
 <br>
 <button type="submit" class="btn btn-success">Save</button>
</form>
<?= $this->endSection() ?>