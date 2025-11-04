<?php if ($errors = session()->getFlashdata('errors')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
   <ul class="mb-0">
    <?php foreach ($errors as $error): ?>
     <li><?= esc($error) ?></li>
    <?php endforeach ?>
   </ul>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
   <?= session()->getFlashdata('error') ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>

<?php if (session()->getFlashdata('validation')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
   <?php
   $validation = session()->getFlashdata('validation');
   if (is_array($validation)) {
    echo '<ul class="mb-0">';
    foreach ($validation as $error) {
     echo "<li>$error</li>";
    }
    echo '</ul>';
   } else {
    echo $validation;
   }
   ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>

<?php if (session()->getFlashdata('dbError')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
   <?php
   $dbError = session()->getFlashdata('dbError');
   if (is_array($dbError) && isset($dbError['message'])) {
    echo $dbError['message'] . '<br>';
   } else {
    echo $dbError . '<br>';
   }
   ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>


<?php if (session()->getFlashdata('message')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
   <?= session()->getFlashdata('message') ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
 <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; min-width: 300px;">
  <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
   <?= session()->getFlashdata('success') ?>
   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
 </div>
<?php endif; ?>