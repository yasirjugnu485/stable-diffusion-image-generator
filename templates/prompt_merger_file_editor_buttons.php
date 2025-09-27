<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ($params['prompt_merger_directory'] !== 'Demo') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasPromptMergerFileEditorButtonsDeleteDirectory"
         aria-labelledby="offcanvasPromptMergerFileEditorButtonsDeleteDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasPromptMergerFileEditorButtonsDeleteDirectoryLabel">Delete Prompt Merger Directory
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Prompt Merger Directories. All Prompt files will be irretrievably lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deletePromptMergerDirectory">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['prompt_merger_directory']; ?>">
                </div>
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

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasPromptMergerFileEditorButtonsRenameDirectory"
         aria-labelledby="offcanvasPromptMergerFileEditorButtonsRenameDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasPromptMergerFileEditorButtonsRenameDirectoryLabel">
                Rename Album
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
                           value="renamePromptMergerDirectory">
                    <label for="album"
                           class="form-label">
                        Prompt Merger Directory name
                    </label>
                    <input type="text"
                           class="form-control"
                           name="directory"
                           value="<?php echo str_replace('_', ' ', $params['prompt_merger_directory']); ?>">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-pencil"></i>
                        Rename Directory
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasPromptMergerFileEditorButtonsAddFile"
         aria-labelledby="offcanvasPromptMergerFileEditorButtonsAddFileLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasPromptMergerFileEditorButtonsAddFileLabel">Add new Prompt Merger File
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                The Prompt Merger File name may only contain numbers, letters, _ and -.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="addPromptMergerFile">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['prompt_merger_directory']; ?>">
                    <label for="addPromptMergerFileName"
                           class="form-label">
                        Prompt Merger File name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="addPromptMergerFileName"
                           name="name">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add File
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<div class="container">
    <div class="row">
        <?php
        if ($params['prompt_merger_directory'] === 'Demo') {
            ?>
            <div class="col-12 mb-5">
                <div class="alert alert-warning text-white">
                    The demo Prompt Merger Directory is part of the GIT repository and cannot be changed or deleted.
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="col-12 mb-5">
                <div class="float-start">
                    <button class="btn btn-danger mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasPromptMergerFileEditorButtonsDeleteDirectory">
                        <i class="bi bi-trash me-1"></i>
                        Delete Directory
                    </button>
                </div>
                <div class="float-end">
                    <button class="btn btn-primary mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasPromptMergerFileEditorButtonsRenameDirectory">
                        <i class="bi bi-pencil"></i>
                        Rename Directory
                    </button>
                    <button class="btn btn-primary mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasPromptMergerFileEditorButtonsAddFile">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add File
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>