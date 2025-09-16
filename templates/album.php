<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

include(ROOT_DIR . 'templates/copy_image.php');
include(ROOT_DIR . 'templates/delete_image.php');
include(ROOT_DIR . 'templates/toast.php');
include(ROOT_DIR . 'templates/title.php');
include(ROOT_DIR . 'templates/breadcrumbs.php');
include(ROOT_DIR . 'templates/hr.php');
if (isset($params['album_sub_albums']) && count($params['album_sub_albums'])) {
    include(ROOT_DIR . 'templates/album_sub_albums.php');
    include(ROOT_DIR . 'templates/hr.php');
}
include(ROOT_DIR . 'templates/album_buttons.php');
if ($params['album'] !== '/album/') {
    include(ROOT_DIR . 'templates/images_title.php');
    include(ROOT_DIR . 'templates/images.php');
}