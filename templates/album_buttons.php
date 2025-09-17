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
     id="offcanvasAlbumButtonsAddSubAlbum"
     aria-labelledby="offcanvasAlbumButtonsAddSubAlbumLabel"
     style="width: 600px; max-width: 100%">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"
            id="offcanvasAlbumButtonsAddSubAlbumLabel">
            Add new Sub-Album
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-4">
            The Sub-Album name may only contain numbers, letters, _ and -.
        </div>
        <form method="post">
            <div class="mb-3">
                <input type="hidden"
                       name="action"
                       value="addAlbum">
                <label for="album"
                       class="form-label">
                    Sub-Album name
                </label>
                <input type="text"
                       class="form-control"
                       id="album"
                       name="album">
            </div>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Sub-Album
                </button>
            </div>
        </form>
    </div>
</div>

<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasAlbumButtonsRenameAlbum"
     aria-labelledby="offcanvasAlbumButtonsRenameAlbumLabel"
     style="width: 600px; max-width: 100%">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"
            id="offcanvasAlbumButtonsRenameAlbumLabel">
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
            The Album name may only contain numbers, letters, _ and -.
        </div>
        <form method="post">
            <div class="mb-3">
                <input type="hidden"
                       name="action"
                       value="renameAlbum">
                <label for="album"
                       class="form-label">
                    Album name
                </label>
                <input type="text"
                       class="form-control"
                       id="album"
                       name="album"
                       value="<?php echo $params['images_title']; ?>">
            </div>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-pencil"></i>
                    Rename Album
                </button>
            </div>
        </form>
    </div>
</div>

<?php
if ($params['album'] !== '/album') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasAlbumButtonsDeleteAlbum"
         aria-labelledby="offcanvasAlbumButtonsDeleteAlbumLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasAlbumButtonsDeleteAlbumLabel">Delete Album
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Albums. All Images, Sub-Albums and Sub-Album Images will irretrievably lose.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deleteAlbum">
                    <input type="hidden"
                           name="album"
                           value="<?php echo $params['album']; ?>">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete Album
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

        <div class="col-12 mb-5">
            <?php
            if (count($params['request_index']) > 1) {
                ?>
                <div class="float-start">
                    <button class="btn btn-danger mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasAlbumButtonsDeleteAlbum">
                        <i class="bi bi-trash me-1"></i>
                        Delete Album
                    </button>
                </div>
                <?php
            }
            ?>
            <div class="float-end text-end">
                <?php
                if (count($params['request_index']) > 1) {
                    ?>
                    <button class="btn btn-primary mb-2"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasAlbumButtonsRenameAlbum">
                        <i class="bi bi-pencil"></i>
                        Rename Album
                    </button>
                    <?php
                }
                ?>
                <button class="btn btn-primary mb-2"
                        type="button"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasAlbumButtonsAddSubAlbum">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Sub-Album
                </button>
            </div>
        </div>
    </div>
</div>