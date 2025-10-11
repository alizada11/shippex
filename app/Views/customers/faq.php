<?= $this->extend('customers/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
 <div class="row align-items-start">

  <!-- Accordion Section -->
  <div class="col-lg-6 mb-4">
   <h2 class="mb-4">Frequently Asked Questions</h2>
   <div class="accordion" id="faqAccordion">

    <?php if (!empty($faqs)): ?>
     <?php foreach ($faqs as $index => $faq): ?>
      <?php
      $headingId = 'heading' . $index;
      $collapseId = 'collapse' . $index;
      ?>
      <div class="accordion-item">
       <h2 class="accordion-header" id="<?= esc($headingId) ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= esc($collapseId) ?>">
         <?= esc($faq['question']) ?>
        </button>
       </h2>
       <div id="<?= esc($collapseId) ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
         <?= nl2br(esc($faq['answer'])) ?>
        </div>
       </div>
      </div>
     <?php endforeach; ?>
    <?php else: ?>
     <p>No FAQs found.</p>
    <?php endif; ?>

   </div>

   <div class="mt-4">
    <a href="<?= base_url('contact') ?>" class="btn btn-primary">Still have questions? Contact Us</a>
   </div>
  </div>

  <!-- Image Section -->
  <div class="col-lg-6 text-center sticky-top" style="top: 100px;">
   <img src="<?= base_url('images/faq.svg') ?>" alt="FAQ Banner" class="img-fluid">
  </div>

 </div>
</div>
<?= $this->endSection() ?>