<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>

<div class="container">
    <div class="row">
        <div class="col-12 mb-5">
            <div class="float-end text-end">
                <?php
                if ($params['view'] === 'thumbnails') {
                    ?>
                    <form method="post"
                          class="float-end">
                        <input type="hidden"
                               name="action"
                               value="changeView">
                        <input type="hidden"
                               name="view"
                               value="list">
                        <button class="btn btn-primary ms-1 mb-2"
                                type="submit">
                            <i class="bi bi-list"></i>
                        </button>
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="post"
                          class="float-end">
                        <input type="hidden"
                               name="action"
                               value="changeView">
                        <input type="hidden"
                               name="view"
                               value="thumbnails">
                        <button class="btn btn-primary ms-1 mb-2"
                                type="submit">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>