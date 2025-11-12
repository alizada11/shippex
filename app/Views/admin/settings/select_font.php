<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">

    <div class="card  shadow-sm ">
        <div class="card-header">
            <h5>Select Google Font</h5>

        </div>
        <div class="card-body">
            <form method="post" action="<?= site_url('admin/fonts/save') ?>">
                <label for="fontSelect" class="form-label">Choose a font:</label>
                <select class="form-control" id="fontSelect" name="font_name" style="width: 100%;">
                    <?php foreach ($fonts as $font): ?>
                        <option value="<?= esc($font['family']) ?>">
                            <?= esc($font['family']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="my-4">
                    <h5>Live Preview</h5>
                    <div id="preview" class="border rounded p-3" style="font-size: 24px;">
                        shippex shipping co
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Font</button>
            </form>
        </div>

    </div>
    <hr>
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-info">
            <h5 class="mb-0"><i class="fas fa-history mr-2"></i> Font History</h5>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Font Name</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($selected_fonts as $font): ?>
                            <tr>
                                <td class="pl-4 font-weight-bold"><?= $font['font_name'] ?></td>
                                <td>
                                    <span style="font-family: '<?= $font['font_name'] ?>'">Sample Text</span>
                                </td>
                                <td>
                                    <?php if ($font['is_default']): ?>
                                        <span class=" badge-success">Default</span>
                                    <?php else: ?>
                                        <span class=" badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$font['is_default']): ?>
                                        <a href="<?= base_url('admin/fonts/make_default/' . $font['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            Set as Default
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 with custom rendering
    $('#fontSelect').select2({
        templateResult: formatFontOption,
        templateSelection: formatFontOption,
        matcher: matchCustom
    });

    // When font changes
    $('#fontSelect').on('change', function() {
        const font = $(this).val();
        loadGoogleFont(font);
        $('#preview').css('font-family', font);
    });

    // Format each option in Select2 with preview
    function formatFontOption(state) {
        if (!state.id) return state.text;
        const font = state.text;
        const $option = $(`
        <span style="font-family: '${font}', sans-serif important!;">${font} -(select the font to see the live preview)</span>
        `);
        loadGoogleFont(font);
        return $option;
    }

    // Load the Google Font dynamically
    function loadGoogleFont(font) {
        const fontId = 'gf-' + font.replace(/\s+/g, '-').toLowerCase();
        if (!document.getElementById(fontId)) {
            const link = document.createElement('link');
            link.id = fontId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${font.replace(/ /g, '+')}&display=swap`;
            document.head.appendChild(link);
        }
    }

    // Optional: custom matcher to support font filtering
    function matchCustom(params, data) {
        if ($.trim(params.term) === '') return data;
        if (typeof data.text === 'undefined') return null;

        if (data.text.toLowerCase().includes(params.term.toLowerCase())) {
            return data;
        }

        return null;
    }
</script>
<?= $this->endSection() ?>