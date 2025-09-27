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
     id="offcanvasInitImagesImageEditorDeleteImage"
     aria-labelledby="offcanvasInitImagesImageEditorDeleteImageLabel"
     style="width: 600px; max-width: 100%">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"
            id="offcanvasInitImagesImageEditorDeleteImageLabel">Delete Initialize Images Image
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
                       value="<?php echo $params['init_images_directory']; ?>">
                <input type="hidden"
                       id="offcanvasInitImagesImageEditorDeleteImageImage"
                       name="file">
                <label for="offcanvasInitImagesImageEditorDeleteImageInput"
                       class="form-label">
                    Delete Initialize Images Image Name
                </label>
                <input type="text"
                       class="form-control"
                       id="offcanvasInitImagesImageEditorDeleteImageInput"
                       name="file"
                       disabled>
            </div>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>
                    Delete Image
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row">
        <h4>
            <?php echo str_replace('_', ' ', $params['init_images_directory']); ?>
        </h4>
        <?php
        if (count($params['init_images_images'])) {
            ?>
            <form method="post">
                <input type="hidden"
                       name="action"
                       value="editInitImagesImages">
                <input type="hidden"
                       name="directory"
                       value="<?php echo $params['init_images_directory']; ?>">
                <div class="row">
                    <?php
                    foreach ($params['init_images_images'] as $image) {
                        ?>
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-dark text-light">
                                    <h5 class="float-start mt-2">
                                        <?php echo str_replace('_', ' ', $image['name']); ?>
                                    </h5>
                                    <?php
                                    if (count($params['init_images_images']) > 1 && $params['init_images_directory'] !== 'Demo') {
                                        ?>
                                        <button class="btn btn-danger float-end"
                                                type="button"
                                                onclick="initImagesEditorEditor.deleteInitImagesImage('<?php echo $image['name']; ?>', '<?php echo $image['file']; ?>')">
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
                                               value="<?php echo str_replace('_', ' ', $image['name']); ?>"
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
                if ($params['init_images_directory'] !== 'Demo') {
                    ?>
                    <div class="text-end mb-5">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-floppy-fill me-1"></i>
                            Save Images
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
                <div class="alert alert-warning text-white">
                    No Initialize Images available
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    class InitImagesEditorEditor {
        deleteInitImagesImage = (name, file) => {
            document.getElementById("offcanvasInitImagesImageEditorDeleteImageImage").value = file;
            document.getElementById("offcanvasInitImagesImageEditorDeleteImageInput").value = name.replace('_', ' ');
            let offcanvasInitImagesImageEditorDeleteImage = document.getElementById("offcanvasInitImagesImageEditorDeleteImage");
            const offcanvas = new bootstrap.Offcanvas(offcanvasInitImagesImageEditorDeleteImage);
            offcanvas.show();
        }
    }

    const initImagesEditorEditor = new InitImagesEditorEditor();
</script>
