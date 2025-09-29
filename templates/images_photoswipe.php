<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<script type="module">
    import PhotoSwipeLightbox from '/out/js/photoswipe/dist/photoswipe-lightbox.esm.js';

    const lightbox = new PhotoSwipeLightbox({
        gallery: '#photoswipe-gallery',
        children: '.photoswipe-children',
        pswpModule: () => import('/out/js/photoswipe/dist/photoswipe.esm.js')
    });
    lightbox.init();
</script>