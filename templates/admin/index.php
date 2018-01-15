<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'kiwi' ?></title>
    <base href="<?= $baseUrl ?? '/' ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="description" content="<?= $title ?? 'Kiwi Administration' ?>">
    <meta name="author" content="<?= $author ?? 'kiwi suite' ?>">
    <link rel="shortcut icon" href="<?= $assetsUrl ?? '' ?>assets/img/icon.png">
    <?php foreach ($styles as $style): ?>
        <link href="<?= $assetsUrl ?? '' ?><?= $style ?>" rel="stylesheet"/>
    <?php endforeach ?>
</head>
<body>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<script>
    window.__theme = 'bs4';
    window.__kiwi =<?= json_encode($config) ?>;
</script>
<?php foreach ($scripts as $script): ?>
    <script type="text/javascript" src="<?= $assetsUrl ?? '' ?><?= $script ?>"></script>
<?php endforeach ?>
</body>
</body>
</html>
