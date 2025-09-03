<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasAdd"
     aria-labelledby="offcanvasAddLabel"
     style="min-width: 600px">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"
            id="offcanvasAddLabel">Add new Initialize Images Directory
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-4">
            The Initialize Images Directory name may only contain numbers, letters, _ and -.
        </div>
        <form method="post">
            <div class="mb-3">
                <input type="hidden"
                       name="action"
                       value="addInitImages">
                <label for="directory"
                       class="form-label">
                    Initialize Images Directory name
                </label>
                <input type="text"
                       class="form-control"
                       id="directory"
                       name="initImages">
            </div>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container mb-5" style="max-width: 1600px">
    <div class="row">
        <?php
        if (isset($params['error'])) {
            ?>
            <div class="col-12">
                <div class="alert alert-danger mb-5">
                    <?php echo $params['error']; ?>
                </div>
            </div>
            <?php
        }
        if (isset($params['success'])) {
            ?>
            <div class="col-12">
                <div class="alert alert-success mb-5">
                    <?php echo $params['success']; ?>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="col-12 text-end mb-5">
            <button class="btn btn-primary"
                    type="button"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasAdd">
                <i class="bi bi-plus-circle me-1"></i>
                Add
            </button>
        </div>

        <?php
        if (count($params['init_images'])) {
            ?>
            <div class="col-12">
                <h3>
                    Initialize Images Directories
                </h3>
                <?php
                foreach ($params['init_images'] as $initImage) {
                    ?>
                    <a href="/initialize-images/<?php echo $initImage; ?>" style="text-decoration: none">
                        <button class="btn btn-secondary mb-2">
                            <?php echo $initImage; ?>
                        </button>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <div class="col-12">
                <h3>
                    Initialize Images Directories
                </h3>
                <div class="alert alert-warning">
                    There are no Initialize Images Directories yet
                </div>
            </div>

            <?php
        }
        ?>
    </div>
</div>
