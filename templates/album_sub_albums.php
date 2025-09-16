<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['album_sub_albums']) && count($params['album_sub_albums']) &&
    isset($params['album']) && $params['album'] !== '/album') {
    ?>
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <h4>
                    Sub-Albums
                </h4>
                <?php if (count($params['album_sub_albums'])) {
                    foreach ($params['album_sub_albums'] as $subAlbum) {
                        ?>
                        <a href="<?php echo $subAlbum['link']; ?>" style="text-decoration: none">
                            <button class="btn btn-outline-secondary mb-2"
                                    type="button">
                                <?php echo $subAlbum['name']; ?>
                            </button>
                        </a>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-warning mt-1">
                        There are no Sub-Albums available
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}