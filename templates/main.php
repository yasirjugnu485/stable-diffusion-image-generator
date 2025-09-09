<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/out/css/main.css">
    <link rel="stylesheet" href="/out/css/bootstrap.min.css">
    <link rel="stylesheet" href="/out/css/lightbox.css">
    <link rel="stylesheet" href="/out/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/out/css/multi-select-tag.min.css">
    <script src="/out/js/bootstrap.bundle.min.js"></script>
    <script src="/out/js/lightbox.js"></script>
    <script src="/out/js/multi-select-tag.min.js"></script>
</head>
<body>
<div class="content">
    <?php include(ROOT_DIR . 'templates/navbar.php'); ?>
    <?php include($params['template']);
    ?>
</div>
<?php
include(ROOT_DIR . 'templates/footer.php');
?>
</body>
</html>