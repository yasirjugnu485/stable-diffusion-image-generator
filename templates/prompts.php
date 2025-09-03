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
            id="offcanvasAddLabel">Add new Prompt Merger Directory
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-4">
            The Prompt Merger Directory name may only contain numbers, letters, _ and -.
        </div>
        <form method="post">
            <div class="mb-3">
                <input type="hidden"
                       name="action"
                       value="addPromptDirectory">
                <label for="directory"
                       class="form-label">
                    Prompt Merger Directory name
                </label>
                <input type="text"
                       class="form-control"
                       id="directory"
                       name="prompt">
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

<div class="container" style="max-width: 1600px">
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
        if (count($params['prompts'])) {
            ?>
            <div class="col-12 mb-5">
                <h3>
                    Prompt Merger Directories
                </h3>
                <?php
                foreach ($params['prompts'] as $prompt) {
                    ?>
                    <a href="/prompt-merger/<?php echo $prompt; ?>" style="text-decoration: none">
                        <button class="btn btn-secondary mb-2">
                            <?php echo $prompt; ?>
                        </button>
                    </a>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <div class="col-12 mb-5">
                <h3>
                    Prompt Merger Directories
                </h3>
                <div class="alert alert-warning">
                    There are no Prompt Merger Directories yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>