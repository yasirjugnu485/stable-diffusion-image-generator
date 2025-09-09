<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ((isset($params['error']) && $params['error']) || (isset($params['success']) && $params['success'])) {
    ?>
    <div class="container" style="max-width: 1600px">
        <div class="row">
            <div class="col-12">
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
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="container" style="max-width: 1600px">
    <div class="row">
        <div class="col-12 text-dark">

            <h3 class="mb-3">
                <i class="bi bi-gear-fill"></i>
                Settings
            </h3>

            <form method="post"
                  id="form">
                <input type="hidden"
                       name="action"
                       value="saveSettings">

                <div class="card mb-4">
                    <div class="card-header bg-dark text-light">
                        <h5 class="mt-2">
                            Default Settings
                        </h5>
                    </div>
                    <div class="card-body text-dark mb-4">
                        <div class="row">

                            <div class="col-12 col-md-6">
                                <label for="host"
                                       class="form-label">
                                    Host
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="host"
                                       name="host"
                                       placeholder="Host"
                                       required
                                       value="<?php echo $params['host']; ?>">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="text-end mb-5">
                    <button class="btn btn-primary"
                            type="button"
                            onclick="document.getElementById('form').submit()">
                        <i class="bi bi-floppy-fill me-1"></i>
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

