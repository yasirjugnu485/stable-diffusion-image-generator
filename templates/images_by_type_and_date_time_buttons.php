<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['type']) && isset($params['date_time'])) {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeleteDirectory"
         aria-labelledby="offcanvasDeleteDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeleteDirectoryLabel">Delete Directory
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Images Directories. All generated Images in this directory will be
                irretrievably lost.
            </div>
            <form method="post">
                <input type="hidden"
                       name="action"
                       value="deleteDirectory">
                <input type="hidden"
                       name="type"
                       value="<?php echo $params['type']; ?>">
                <input type="hidden"
                       name="dateTime"
                       value="<?php echo $params['date_time']; ?>">
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete Directory
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 mb-5">
                <div class="float-start">
                    <button class="btn btn-danger mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDeleteDirectory">
                        <i class="bi bi-trash me-1"></i>
                        Delete Directory
                    </button>
                </div>
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
    <?php
}
?>