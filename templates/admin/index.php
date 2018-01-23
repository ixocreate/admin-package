<?php
/** @var \KiwiSuite\Admin\Config\AdminConfig $adminConfig */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $adminConfig->getProject()['name'] ?? 'Kiwi' ?></title>
    <base href="<?= $adminConfig->getUri() ?? '/' ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="description" content="<?= $adminConfig->getProject()['name'] ?? 'Kiwi' ?>">
    <meta name="author" content="<?= $adminConfig->getProject()['author'] ?? 'kiwi suite GmbH' ?>">
    <link rel="shortcut icon" href="<?= $assetsUrl ?>assets/img/icon.png">
    <?php foreach ($assets['styles'] as $style): ?>
        <link href="<?= $assetsUrl . $style ?>" rel="stylesheet"/>
    <?php endforeach ?>
</head>
<body>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<script>
    window.__theme = 'bs4';
    window.__kiwi =<?= json_encode([
        'apiUrl' => $adminConfig->getUri() . '/api',
        'sessionUrl' => $adminConfig->getUri() . '/session',
        'configUrl' => $adminConfig->getUri() . '/api/config', //TODO use url helper
        'project' => $adminConfig->getProject(),
    ]) ?>;
</script>
<?php foreach ($assets['scripts'] as $script): ?>
    <script type="text/javascript" src="<?= $assetsUrl . $script ?>"></script>
<?php endforeach ?>
</body>
</body>
</html>
