<!-- app/Views/pagebuilder/editor.php -->
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title><?= esc($page['title']) ?></title>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <!-- GrapesJS Studio SDK CSS -->
 <link rel="stylesheet" href="https://unpkg.com/@grapesjs/studio-sdk/dist/style.css" />

 <style>
  body,
  html {
   margin: 0;
   padding: 0;
   height: 100%;
  }

  #studio-editor {
   height: 100vh;
  }
 </style>
</head>

<body>
 <div id="studio-editor"></div>

 <!-- GrapesJS Studio SDK JS -->
 <script src="https://unpkg.com/@grapesjs/studio-sdk/dist/index.umd.js"></script>
 <script>
  // Replace these with dynamic PHP variables
  const PAGE_ID = "<?= esc($page['id']) ?>";
  const SAVE_URL = "<?= site_url('pagebuilder/save') ?>";
  const LOAD_URL = "<?= site_url('pagebuilder/load/' . $page['id']) ?>";

  GrapesJsStudioSDK.createStudioEditor({
   root: '#studio-editor',
   licenseKey: '36a71551ddb24a7b98fe9ec049e4100f9c79b6c4cd0a4ddfac253164a76f6fb3', // use a proper key for production
   project: {
    type: 'web',
    id: PAGE_ID
   },
   identity: {
    id: PAGE_ID + '_user'
   },
   storage: {
    type: 'remote',
    autosaveChanges: 1,
    autosaveIntervalMs: 10000,
    remote: {
     storeUrl: SAVE_URL,
     loadUrl: LOAD_URL,
     contentTypeJson: true
    }
   },
   assets: {
    storageType: 'cloud'
   }
  });
 </script>
</body>

</html>