<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
 <div class="alert alert-success">
  Thank you — your personal shopper request was received. We'll be in touch shortly.
 </div>
 <p><a class="btn custom-btn" href="/shopper">Create another request</a></p>
 <p><a class="btn custom-btn" href="/shopper/requests">See your request</a></p>
</div>
<?= $this->endSection() ?>