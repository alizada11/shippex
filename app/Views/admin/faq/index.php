<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>


<div class="container">
  <!-- Stats Overview -->
  <div class="row">


    <div class="card  p-0 ">
      <div class="card-header d-flex justify-content-between">

        <h3>FAQ Management</h3>
        <a href="<?= base_url('admin/faqs/create') ?>" class="btn text-white bg-shippex-orange">+ Add FAQ</a>
      </div>


      <div class="card-body p-0 table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Question</th>
              <th>Answer</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($faqs as $index => $faq): ?>
              <tr>
                <td><?= $faq['id'] ?></td>
                <td><?= esc($faq['question']) ?></td>
                <td><?= esc($faq['answer']) ?></td>

                <td class="actions-col">
                  <div class="action-buttons-table">
                    <a href="<?= base_url('admin/faqs/edit/' . $faq['id']) ?>" class="btn btn-action view">
                      <i class="fas fa-eye "></i>
                    </a>

                    <form action="<?= base_url('admin/faqs/delete/' . $faq['id']) ?>" class="delete-form" method="post">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-action delete"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="row mt-4">
        <?= $pager->links('default', 'bootstrap_full') ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>