<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ($params['init_images_directory'] !== 'Demo') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasInitImagesImageEditorButtonsDeleteDirectory"
         aria-labelledby="offcanvasInitImagesImageEditorButtonsDeleteDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasInitImagesImageEditorButtonsDeleteDirectoryLabel">Delete Initialize Images Directory
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Initialize Images Directories. All Initialize Images will be irretrievably
                lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deleteInitImagesDirectory">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['init_images_directory']; ?>">
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
         id="offcanvasInitImagesImageEditorButtonsRenameDirectory"
         aria-labelledby="offcanvasInitImagesImageEditorButtonsRenameDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasInitImagesImageEditorButtonsRenameDirectoryLabel">
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
                The Initialize Images Directory name may only contain numbers, letters, _ and -.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="renameInitImagesDirectory">
                    <label for="album"
                           class="form-label">
                        Initialize Images Directory name
                    </label>
                    <input type="text"
                           class="form-control"
                           name="directory"
                           value="<?php echo str_replace('_', ' ', $params['init_images_directory']); ?>">
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
         id="offcanvasInitImagesImageEditorButtonsAddImage"
         aria-labelledby="offcanvasInitImagesImageEditorButtonsAddImageLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasInitImagesImageEditorButtonsAddImageLabel">Add new Initialize Images Image
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                The Initialize Images Image name may only contain numbers, letters, _ and -.
            </div>
            <form method="post"
                  enctype='multipart/form-data'>
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="addInitImagesImage">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['init_images_directory']; ?>">
                    <label for="addInitImageName"
                           class="form-label">
                        Image Name
                    </label>
                    <input type="text"
                           class="form-control mb-3"
                           id="addInitImageName"
                           name="name">
                    <label for="addInitImageImage"
                           class="form-label">
                        Image (.png, .jpg, .jpeg)
                    </label>
                    <div class="input-group mb-3">
                        <input type="file"
                               class="form-control"
                               id="addInitImageImage"
                               name="image">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Image
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
        if ($params['init_images_directory'] === 'Demo') {
            ?>
            <div class="col-12">
                <div class="alert alert-warning mb-5">
                    The demo Initialize Images Directory is part of the GIT repository and cannot be changed or deleted.
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
                            href="#offcanvasInitImagesImageEditorButtonsDeleteDirectory">
                        <i class="bi bi-trash me-1"></i>
                        Delete Directory
                    </button>
                </div>
                <div class="float-end">
                    <button class="btn btn-primary mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasInitImagesImageEditorButtonsRenameDirectory">
                        <i class="bi bi-pencil"></i>
                        Rename Directory
                    </button>
                    <button class="btn btn-primary mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasInitImagesImageEditorButtonsAddImage">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Image
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>