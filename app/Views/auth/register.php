<!DOCTYPE html>
<html>

<head>
 <title>Admin Login</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
 <div class="container mt-5">
  <div class="row justify-content-center">
   <div class="col-md-4">
    <h3 class="mb-4 text-center">Login</h3>

    <?php if (session()->getFlashdata('error')): ?>
     <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/register') ?>">
     <input type="text" name="username" required placeholder="Username" class="form-control mb-2">
     <input type="email" name="email" required placeholder="Email" class="form-control mb-2">
     <input type="password" name="password" required placeholder="Password" class="form-control mb-2">
     <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

   </div>
  </div>
 </div>
</body>

</html>