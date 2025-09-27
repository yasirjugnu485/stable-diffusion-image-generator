<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

include(ROOT_DIR . 'templates/title.php');
include(ROOT_DIR . 'templates/breadcrumbs.php');
include(ROOT_DIR . 'templates/hr.php');
include(ROOT_DIR . 'templates/image_copy.php');
include(ROOT_DIR . 'templates/image_delete.php');
include(ROOT_DIR . 'templates/toast.php');
include(ROOT_DIR . 'templates/used_modes.php');
include(ROOT_DIR . 'templates/used_checkpoints.php');
if (count($params['used_checkpoints'])) {
    include(ROOT_DIR . 'templates/hr.php');
}
include(ROOT_DIR . 'templates/home_buttons.php');
include(ROOT_DIR . 'templates/images_title.php');
include(ROOT_DIR . 'templates/images.php');