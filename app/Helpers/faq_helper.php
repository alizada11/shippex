<?php

use App\Models\FaqModel;

if (!function_exists('get_faqs')) {
 /**
  * Fetch FAQs from the database.
  *
  * @param int|null $limit Limit the number of FAQs (optional)
  * @param string $order Sort order ('ASC' or 'DESC')
  * @return array
  */
 function get_faqs(int $limit = null, string $order = 'ASC'): array
 {
  $faqModel = new FaqModel();

  if ($limit) {
   return $faqModel->orderBy('id', $order)->findAll($limit);
  }

  return $faqModel->orderBy('id', $order)->findAll();
 }
}

if (!function_exists('render_faqs')) {
 /**
  * Render FAQs as a Bootstrap accordion.
  *
  * @param array|null $faqs (optional) — pass preloaded FAQs or leave empty to auto-fetch all
  * @param string $accordionId (optional) — ID for the accordion wrapper
  * @return string
  */
 function render_faqs(array $faqs = null, string $accordionId = 'faqAccordion'): string
 {
  if (empty($faqs)) {
   $faqs = get_faqs();
  }

  if (empty($faqs)) {
   return '<p>No FAQs available.</p>';
  }

  $html = '<div class="accordion" id="' . esc($accordionId) . '">';
  foreach ($faqs as $index => $faq) {
   $headingId = 'heading' . $index;
   $collapseId = 'collapse' . $index;

   $html .= '
            <div class="accordion-item">
                <h2 class="accordion-header" id="' . esc($headingId) . '">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . esc($collapseId) . '">
                        ' . esc($faq['question']) . '
                    </button>
                </h2>
                <div id="' . esc($collapseId) . '" class="accordion-collapse collapse" data-bs-parent="#' . esc($accordionId) . '">
                    <div class="accordion-body">' . nl2br(esc($faq['answer'])) . '</div>
                </div>
            </div>';
  }
  $html .= '</div>';

  return $html;
 }
}
