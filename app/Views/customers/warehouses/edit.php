<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h2>Edit Warehouse Address</h2>
<form action="/warehouse/update/<?= $address['id'] ?>" method="post">
 <div class="form-group">
  <label>User</label>
  <select name="user_id" class="form-control" required>
   <?php foreach ($users as $user): ?>
    <option value="<?= $user['id'] ?>" <?= $user['id'] == $address['user_id'] ? 'selected' : '' ?>>
     <?= esc($user['username']) ?> (ID: <?= $user['id'] ?>)
    </option>
   <?php endforeach; ?>
  </select>
 </div>
 <div class="form-group">
  <label>Country</label>
  <input type="text" name="country" class="form-control" value="<?= esc($address['country']) ?>" required>
 </div>
 <div class="form-group">
  <label>Address Line</label>
  <textarea name="address_line" class="form-control" required><?= esc($address['address_line']) ?></textarea>
 </div>
 <div class="form-group">
  <label>Postal Code</label>
  <input type="text" name="postal_code" class="form-control" value="<?= esc($address['postal_code']) ?>" required>
 </div>
 <div class="form-group">
  <label>Phone</label>
  <input type="text" name="phone" class="form-control" value="<?= esc($address['phone']) ?>" required>
 </div>
 <div class="form-check">
  <input type="checkbox" name="is_default" value="1" class="form-check-input"
   <?= $address['is_default'] ? 'checked' : '' ?>>
  <label class="form-check-label">Set as Default</label>
 </div>
 <br>
 <button type="submit" class="btn btn-primary">Update</button>
</form>
<?= $this->endSection() ?>