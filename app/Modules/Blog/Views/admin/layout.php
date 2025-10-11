<!doctype html>
<html>

<head>
 <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <script src="https://cdn.tailwindcss.com"></script>
 <title>Blog Admin</title>
</head>

<body class="bg-gray-50 text-gray-900">
 <div class="max-w-5xl mx-auto p-6">
  <h1 class="text-2xl font-bold mb-6">Blog Admin</h1>
  <?= session('message') ? '<div class="p-3 bg-green-100 rounded mb-4">' . esc(session('message')) . '</div>' : '' ?>
  <?= isset($errors) && $errors ? '<div class="p-3 bg-red-100 rounded mb-4">' . esc(json_encode($errors)) . '</div>' : '' ?>
  <?= $this->renderSection('content') ?>
 </div>
</body>

</html>