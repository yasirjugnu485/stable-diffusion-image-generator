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
     id="offcanvasAddPromptMergerDirectory"
     aria-labelledby="offcanvasAddPromptMergerDirectoryLabel"
     style="width: 600px; max-width: 100%">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"
            id="offcanvasAddPromptMergerDirectoryLabel">Add Prompt Merger Directory
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
                       value="addPromptMergerDirectory">
                <label for="directory"
                       class="form-label">
                    Prompt Merger Directory name
                </label>
                <input type="text"
                       class="form-control"
                       id="directory"
                       name="directory">
            </div>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Prompt Merger Directory
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-12 text-end">
            <button class="btn btn-primary"
                    type="button"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasAddPromptMergerDirectory">
                <i class="bi bi-plus-circle me-1"></i>
                Add Prompt Merger Directory
            </button>
        </div>
    </div>
</div>