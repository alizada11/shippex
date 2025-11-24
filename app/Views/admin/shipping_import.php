<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
 <h1>Paste Shipping Services HTML</h1>
 <form id="importForm">
  <div class="form-group">
   <label for="htmlInput">HTML</label>
   <textarea class="form-control" id="htmlInput" name="html" rows="10" placeholder="Paste HTML here..."></textarea>
  </div>
  <div class="form-group">
   <button type="button" id="previewBtn" class="btn btn-primary">Preview</button>
   <button type="button" id="importBtn" class="btn btn-success">Import</button>
  </div>
 </form>

 <div id="previewArea" style="display:none">
  <h3>Preview</h3>
  <div id="previewSummary" class="mb-3"></div>
  <div id="previewTableWrapper" style="max-height:400px; overflow:auto">
   <table class="table table-sm table-bordered" id="previewTable">
    <thead>
     <tr>
      <th>#</th>
      <th>Provider</th>
      <th>Service</th>
      <th>Price</th>
      <th>Transit Days</th>
      <th>Valid</th>
      <th>Errors</th>
     </tr>
    </thead>
    <tbody></tbody>
   </table>
  </div>
 </div>
</div>
<script>
 async function postJson(url, body) {
  const res = await fetch(url, {
   method: 'POST',
   headers: {
    'Content-Type': 'application/json'
   },
   body: JSON.stringify(body)
  });
  return res.json();
 }

 document.getElementById('previewBtn').addEventListener('click', async function() {
  const html = document.getElementById('htmlInput').value;
  if (!html) {
   alert('Please paste HTML');
   return;
  }
  const resp = await postJson('/shipping-services/import-preview', {
   html
  });
  if (resp.status !== 'ok') {
   alert('Preview failed');
   return;
  }
  const preview = resp.preview;
  const tbody = document.querySelector('#previewTable tbody');
  tbody.innerHTML = '';
  preview.forEach(item => {
   const tr = document.createElement('tr');
   tr.innerHTML = `
                <td>${item.index}</td>
                <td>${(item.record.provider_name||'')}</td>
                <td>${(item.record.service_name||'')}</td>
                <td>${(item.record.currency||'')}${(item.record.price!=null?item.record.price:'')}</td>
                <td>${(item.record.transit_days!=null?item.record.transit_days:'')}</td>
                <td>${item.valid?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'}</td>
                <td>${item.errors.length?item.errors.join(', '):''}</td>
            `;
   tbody.appendChild(tr);
  });
  document.getElementById('previewSummary').innerText = `Found ${preview.length} records`;
  document.getElementById('previewArea').style.display = 'block';
 });

 document.getElementById('importBtn').addEventListener('click', async function() {
  if (!confirm('Import parsed records now?')) return;
  const html = document.getElementById('htmlInput').value;
  const res = await postJson('/shipping-services/import-html', {
   html
  });
  if (res.status === 'ok') {
   alert('Inserted: ' + (res.inserted ? res.inserted.length : 0) + '\nErrors: ' + (res.errors ? res.errors.length : 0));
   location.reload();
  } else {
   alert('Import error');
  }
 });
</script>
<?= $this->endSection() ?>