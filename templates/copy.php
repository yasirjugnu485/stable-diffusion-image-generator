<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['copy'])) {

    function create_entries(string $slug, int $marginLeft, array $entry)
    {
        foreach ($entry as $key => $value) {
            if (is_array($value)) {
                ?>
                <div style="margin-left: <?php echo $marginLeft; ?>px;">
                    <div class="btn btn-outline-primary w-100 text-start p-2 mb-1"
                         onclick="albumPicker.execute('<?php echo $slug . '/' . $key; ?>');">
                        <?php echo str_replace('_', ' ', $key); ?>
                    </div>
                </div>
                <?php
                create_entries($slug . '/' . $key, $marginLeft + 20, $value);
            }
        }
    }

    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasAlbumPicker"
         aria-labelledby="offcanvasAlbumPickerLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasAlbumPickerLabel">Copy
            </h5>
            <button id="offcanvasAlbumPickerBtnClose"
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-5">
                <h5>
                    Albums
                </h5>
                <?php
                if (count($params['copy']['albums'])) {
                    $slug = '/album';
                    $marginLeft = 0;
                    create_entries($slug, $marginLeft, $params['copy']['albums']);
                } else {
                    ?>
                    <div class="alert alert-warning">
                        There are no Albums available
                    </div>
                    <?php
                }
                ?>
            </div>

            <div>
                <h5>
                    Initialize Image Directories
                </h5>
                <?php
                if (count($params['copy']['init_images'])) {
                    $slug = '/init_images';
                    $marginLeft = 0;
                    create_entries($slug, $marginLeft, $params['copy']['init_images']);
                } else {
                    ?>
                    <div class="alert alert-warning">
                        There are no Initialize Images Directories available
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <form id="formAlbumPicker"
          method="post">
        <input id="albumPickerAction"
               type="hidden"
               name="action">
        <input id="albumPickerSource"
               type="hidden"
               name="source">
        <input id="albumPickerDestination"
               type="hidden"
               name="destination">
    </form>

    <script>
        class AlbumPicker {
            copy = (source) => {
                document.getElementById("albumPickerAction").value = 'copyEntry';
                document.getElementById("albumPickerSource").value = source;
                let offcanvasElement = document.getElementById("offcanvasAlbumPicker");
                let offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                offcanvas.show();
            }

            execute = (destination) => {
                document.getElementById("albumPickerDestination").value = destination;
                document.getElementById("offcanvasAlbumPickerBtnClose").click();
                document.getElementById("formAlbumPicker").submit();
            }
        }

        const albumPicker = new AlbumPicker();
    </script>

    <?php
}