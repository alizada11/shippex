<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-4">
  <div class="card shadow-sm">
    <div class="card-header border-0">
      <h5><?= isset($post) && $post->id ? 'Edit Post' : 'Create Post' ?></h5>

    </div>
    <div class="card-body">
      <form method="post"
        action="<?= isset($post) && $post->id ? '/admin/blog/posts/' . $post->id : '/admin/blog/posts' ?>"
        enctype="multipart/form-data"
        class="space-y-4 bg-white ">

        <?= csrf_field() ?>

        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text"
            name="title"
            value="<?= esc($post->title ?? old('title')) ?>"
            class="form-control" required>
        </div>

        <!-- Slug -->
        <div class="mb-3">
          <label class="form-label">Slug (auto if empty)</label>
          <input type="text"
            name="slug"
            value="<?= esc($post->slug ?? old('slug')) ?>"
            class="form-control">
        </div>

        <div>
          <label class="block text-sm font-medium">Category</label>
          <select name="category_id" class="mt-1 w-full border rounded p-2" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat):
              // Determine id and name based on whether $cat is object or array
              $catId = is_object($cat) ? $cat->id : $cat['id'];
              $catName = is_object($cat) ? $cat->name : $cat['name'];
              $selected = (isset($post) && (
                (is_object($post) && $post->category_id == $catId) ||
                (is_array($post) && $post['category_id'] == $catId)
              )) ? 'selected' : '';
            ?>
              <option value="<?= $catId ?>" <?= $selected ?>>
                <?= esc($catName) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>


        <div>
          <label class="mb-2 label">Thumbnail</label>
          <input type="file" name="thumbnail" accept="image/*" class="mt-1 form-control" />
          <?php if (!empty($post->thumbnail)): ?>
            <img src="<?= base_url('uploads/blog/' . $post->thumbnail) ?>" width="72px" height="52px" class="object-cover rounded" />
          <?php endif; ?>
        </div>

        <!-- Content with CKEditor -->
        <div class="mb-3">
          <label class="form-label">Content</label>
          <textarea id="editor" name="content"><?= $post->content ?? old('content') ?></textarea>
        </div>

        <!-- Status -->
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="draft" <?= (isset($post) && $post->status === 'draft') ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= (isset($post) && $post->status === 'published') ? 'selected' : '' ?>>Published</option>
          </select>
        </div>
        <div class="d-flex align-items-center justify-content-between">

          <!-- Submit -->
          <button type="submit" class="btn btn-primary">
            <?= isset($post) && $post->id ? 'Update Post' : 'Create Post' ?>
          </button>
          <a class="btn btn-secondary" href="<?= base_url('admin/blog/posts') ?>"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Load CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

<script>
  class MyUploadAdapter {
    constructor(loader) {
      this.loader = loader;
    }

    upload() {
      return this.loader.file
        .then(file => new Promise((resolve, reject) => {
          const data = new FormData();
          data.append('upload', file);

          fetch('/admin/blog/upload-image', {
              method: 'POST',
              body: data,
              headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
              }
            })
            .then(res => res.json())
            .then(res => {
              if (res.url) {
                resolve({
                  default: res.url
                });
              } else {
                reject(res.error || 'Upload failed');
              }
            })
            .catch(err => reject(err));
        }));
    }

    abort() {}
  }

  function MyCustomUploadPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
      return new MyUploadAdapter(loader);
    };
  }

  // Initialize editor
  ClassicEditor
    .create(document.querySelector('#editor'), {
      extraPlugins: [MyCustomUploadPlugin],
    })
    .then(editor => {
      editor.ui.view.editable.element.style.minHeight = '400px';
    })
    .catch(error => console.error(error));
</script>

<?= $this->endSection() ?>