<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div class="col-6 col-md-4 col-lg-3 mb-4"
     id="image-<?php echo $index; ?>">
    <?php
    if (file_exists(ROOT_DIR . $image['file'])) {
        ?>
        <a class="photoswipe-children"
           href="/image.php?image=<?php echo urlencode($image['file']); ?>"
           data-pswp-width="<?php echo $image['data']['width'] * 1000; ?>"
           data-pswp-height="<?php echo $image['data']['width'] * 1000; ?>">
            <img class="w-100 border rounded"
                 src="/image.php?image=<?php echo urlencode($image['file']); ?>">
        </a>
        <?php
    } else {
        ?>
        <img class="w-100 rounded"
             src="/out/img/image-not-found.png">
        <?php
    }
    ?>
</div>