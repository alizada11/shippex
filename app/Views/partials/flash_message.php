<?php
// Collect all possible flashdata
$errors     = session()->getFlashdata('errors');
$error      = session()->getFlashdata('error');
$validation = session()->getFlashdata('validation');
$dbError    = session()->getFlashdata('dbErrorr');
$message    = session()->getFlashdata('message');
$success    = session()->getFlashdata('success');

// Only run script if any exist
if ($errors || $error || $validation || $dbError || $message || $success):
?>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
  document.addEventListener('DOMContentLoaded', function() {
   <?php if ($errors): ?>
    Swal.fire({
     icon: 'error',
     title: 'Validation Errors',
     html: `<ul style="text-align:left;">
        <?php foreach ($errors as $e): ?>
          <li><?= esc($e) ?></li>
        <?php endforeach; ?>
      </ul>`,
     showConfirmButton: false
    });
   <?php endif; ?>

   <?php if ($error): ?>
    Swal.fire({
     icon: 'error',
     title: 'Error',
     text: "<?= esc($error) ?>",
     timer: 6000,
     showConfirmButton: false
    });
   <?php endif; ?>

   <?php if ($validation): ?>
    Swal.fire({
     icon: 'error',
     title: 'Validation Error',
     html: `
        <?php
        if ($validation instanceof \CodeIgniter\Validation\Validation) {
         $validationErrors = $validation->getErrors();
        } elseif (is_array($validation)) {
         $validationErrors = $validation;
        } else {
         $validationErrors = [$validation];
        }

        if (!empty($validationErrors)) {
         echo '<ul style="text-align:left;">';
         foreach ($validationErrors as $vError) {
          echo '<li>' . esc($vError) . '</li>';
         }
         echo '</ul>';
        }
        ?>
      `,
     timer: 6000,
     showConfirmButton: false
    });
   <?php endif; ?>

   <?php if ($dbError): ?>
    Swal.fire({
     icon: 'error',
     title: 'Database Error',
     html: `
      <?php
      if (is_array($dbError)) {
       // Safely display message or full array if message missing
       if (!empty($dbError['message'])) {
        echo nl2br(esc($dbError['message']));
       } elseif (!empty($dbError['code'])) {
        echo 'Error Code: ' . esc($dbError['code']);
       } else {
        echo '<pre>' . esc(json_encode($dbError, JSON_PRETTY_PRINT)) . '</pre>';
       }
      } elseif (is_string($dbError)) {
       echo esc($dbError);
      } else {
       echo 'An unknown database error occurred.';
      }
      ?>
    `,
     timer: 6000,
     showConfirmButton: false
    });
   <?php endif; ?>

   <?php if ($message): ?>
    Swal.fire({
     icon: 'info',
     title: 'Notice',
     text: "<?= esc($message) ?>",
     timer: 6000,
     showConfirmButton: false
    });
   <?php endif; ?>

   <?php if ($success): ?>
    Swal.fire({
     icon: 'success',
     title: 'Success!',
     text: "<?= esc($success) ?>",
     timer: 6000,
     showConfirmButton: false
    });
   <?php endif; ?>
  });
 </script>
<?php endif; ?>


<script>
 document.addEventListener('DOMContentLoaded', function() {
  // Target all delete forms
  document.querySelectorAll('.delete-form').forEach(form => {
   form.addEventListener('submit', function(e) {
    e.preventDefault(); // Stop immediate submission

    Swal.fire({
     title: 'Are you sure?',
     text: "This action cannot be undone.",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#d33',
     cancelButtonColor: '#6c757d',
     confirmButtonText: 'Yes, delete it!',
     cancelButtonText: 'Cancel',
     reverseButtons: true
    }).then((result) => {
     if (result.isConfirmed) {
      form.submit(); // Proceed only if confirmed
     }
    });
   });
  });
 });
</script>