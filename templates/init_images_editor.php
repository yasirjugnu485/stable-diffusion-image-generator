<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ($params['directory'] !== 'demo') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeleteInitImagesDirectory"
         aria-labelledby="offcanvasDeleteInitImagesDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeleteInitImagesDirectoryLabel">Delete Initialize Images Directory
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
                           value="<?php echo $params['directory']; ?>">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeleteInitImagesImage"
         aria-labelledby="offcanvasDeleteImageLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeleteImageLabel">Delete Initialize Images Image
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Initialize Images Image. The Initialize Images Image will be irretrievably
                lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deleteInitImagesImage">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['directory']; ?>">
                    <input type="hidden"
                           id="deleteInitImagesImage"
                           name="file">
                    <label for="deleteInitImagesImageInput"
                           class="form-label">
                        Delete Initialize Images Image Name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="deleteInitImagesImageInput"
                           name="file"
                           disabled>
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasAddImage"
         aria-labelledby="offcanvasAddImageLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasAddImageLabel">Add new Initialize Images Image
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
                           value="<?php echo $params['directory']; ?>">
                    <label for="addImageName"
                           class="form-label">
                        Image Name
                    </label>
                    <input type="text"
                           class="form-control mb-3"
                           id="addImageName"
                           name="name">
                    <label for="addImageImage"
                           class="form-label">
                        Image (.png, .jpg, .jpeg)
                    </label>
                    <div class="input-group mb-3">
                        <input type="file"
                               class="form-control"
                               id="addImageImage"
                               name="image">
                    </div>
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
    <?php
}
?>

<div class="container" style="max-width: 1600px">
    <div class="row">
        <?php
        if ($params['directory'] === 'demo') {
            ?>
            <div class="col-12">
                <div class="alert alert-warning mb-5">
                    The demo Initialize Images Directory is part of the GIT repository and cannot be changed or deleted.
                </div>
            </div>
            <?php
        }
        ?>

        <?php if (isset($params['error'])) {
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

        if ($params['directory'] !== 'demo') {
            ?>
            <div class="col-12 mb-5">
                <div class="float-start">
                    <button class="btn btn-danger"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDeleteInitImagesDirectory">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
                <div class="float-end">
                    <button class="btn btn-primary"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasAddImage">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add
                    </button>
                </div>
            </div>
            <?php
        }
        ?>

        <h3>
            <?php echo $params['directory']; ?>
        </h3>
        <?php
        if (count($params['images'])) {
            ?>
            <form method="post">
                <input type="hidden"
                       name="action"
                       value="editInitImagesImages">
                <input type="hidden"
                       name="directory"
                       value="<?php echo $params['directory']; ?>">
                <div class="row">
                    <?php
                    foreach ($params['images'] as $image) {
                        ?>
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-dark text-light">
                                    <h5 class="float-start mt-2">
                                        <?php echo $image['name']; ?>
                                    </h5>
                                    <?php
                                    if (count($params['images']) > 1 && $params['directory'] !== 'demo') {
                                        ?>
                                        <button class="btn btn-danger float-end"
                                                type="button"
                                                onclick="initImagesEditor.deleteInitImagesImage('<?php echo $image['name']; ?>', '<?php echo $image['file']; ?>')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <input type="hidden"
                                               name="file[<?php echo $image['name']; ?>]"
                                               value="<?php echo $image['file']; ?>">
                                        <label for="name<?php echo $image['name']; ?>"
                                               class="form-label">
                                            Image name
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="name<?php echo $image['name']; ?>"
                                               value="<?php echo $image['name']; ?>"
                                               name="name[<?php echo $image['name']; ?>]">
                                    </div>
                                    <div>
                                        <img class="w-100 border rounded"
                                             src="/image.php?image=<?php echo urlencode($image['url']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php
                if ($params['directory'] !== 'demo') {
                    ?>
                    <div class="text-end mb-5">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-floppy-fill me-1"></i>
                            Save
                        </button>
                    </div>
                    <?php
                }
                ?>
            </form>
            <?php
        } else {
            ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    No Initialize Images available yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    class InitImagesEditor {
        deleteInitImagesImage = (name, file) => {
            document.getElementById('deleteInitImagesImage').value = file;
            document.getElementById('deleteInitImagesImageInput').value = name;
            let offcanvasDeleteInitImagesImage = document.getElementById('offcanvasDeleteInitImagesImage')
            const offcanvas = new bootstrap.Offcanvas(offcanvasDeleteInitImagesImage)
            offcanvas.show()
        }
    }

    const initImagesEditor = new InitImagesEditor();
</script>