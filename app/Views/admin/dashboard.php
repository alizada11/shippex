<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Admin Dashboard</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Bootstrap 5 CDN -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

 <style>
  body {
   font-size: 0.9rem;
  }

  .sidebar {
   height: 100vh;
   background-color: #343a40;
  }

  .sidebar a {
   color: #adb5bd;
   text-decoration: none;
  }

  .sidebar a:hover {
   color: #fff;
   background-color: #495057;
  }

  .sidebar .nav-link.active {
   background-color: #495057;
   color: #fff;
  }

  .content {
   padding: 20px;
  }

  .navbar-brand {
   font-weight: bold;
  }
 </style>
</head>

<body>

 <div class="container-fluid">
  <div class="row">
   <!-- Sidebar -->
   <nav class="col-md-2 d-none d-md-block sidebar">
    <div class="position-sticky pt-3">
     <div class="text-white text-center my-3 fs-5">Admin Panel</div>
     <ul class="nav flex-column">
      <li class="nav-item">
       <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="#"><i class="fas fa-users me-2"></i> Users</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="#"><i class="fas fa-box me-2"></i> Products</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="#"><i class="fas fa-chart-line me-2"></i> Analytics</a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="#"><i class="fas fa-cogs me-2"></i> Settings</a>
      </li>
     </ul>
    </div>
   </nav>

   <!-- Main Content -->
   <main class="col-md-9 ms-sm-auto col-lg-10 content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
     <div class="container-fluid">
      <a class="navbar-brand" href="#">Welcome, Admin</a>
      <div class="ms-auto">
       <a class="btn btn-outline-danger btn-sm" href="<?= base_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
     </div>
    </nav>

    <div class="row g-4">
     <div class="col-md-4">
      <div class="card text-white bg-primary mb-3">
       <div class="card-body">
        <h5 class="card-title"><i class="fas fa-users"></i> Users</h5>
        <p class="card-text fs-4">124</p>
       </div>
      </div>
     </div>

     <div class="col-md-4">
      <div class="card text-white bg-success mb-3">
       <div class="card-body">
        <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Orders</h5>
        <p class="card-text fs-4">78</p>
       </div>
      </div>
     </div>

     <div class="col-md-4">
      <div class="card text-white bg-warning mb-3">
       <div class="card-body">
        <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Revenue</h5>
        <p class="card-text fs-4">$5,320</p>
       </div>
      </div>
     </div>
    </div>

    <div class="card mt-4">
     <div class="card-header">
      Recent Users
     </div>
     <div class="card-body">
      <table class="table table-hover">
       <thead>
        <tr>
         <th>Name</th>
         <th>Email</th>
         <th>Status</th>
         <th>Joined</th>
        </tr>
       </thead>
       <tbody>
        <tr>
         <td>John Doe</td>
         <td>john@example.com</td>
         <td><span class="badge bg-success">Active</span></td>
         <td>2025-07-15</td>
        </tr>
        <tr>
         <td>Jane Smith</td>
         <td>jane@example.com</td>
         <td><span class="badge bg-warning text-dark">Pending</span></td>
         <td>2025-07-14</td>
        </tr>
        <tr>
         <td>Bob Johnson</td>
         <td>bob@example.com</td>
         <td><span class="badge bg-danger">Banned</span></td>
         <td>2025-07-13</td>
        </tr>
       </tbody>
      </table>
     </div>
    </div>

   </main>
  </div>
 </div>

 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>