<?php
function create_entries(string $slug, int $marginLeft, array $entry)
{
    foreach ($entry as $key => $value) {
        if (is_array($value)) {
            $slug .= '/' . $key;
            ?>
            <div style="margin-left: <?php echo $marginLeft; ?>px;">
                <div class="btn btn-primary w-100 text-start p-2 mb-1"
                     onclick="albumPicker.execute('<?php echo $slug; ?>');">
                    <?php echo str_replace('_', ' ', $key); ?>
                </div>
            </div>
            <?php
            create_entries($slug, $marginLeft + 20, $value);
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
            id="offcanvasAlbumPickerLabel">Select Album
        </h5>
        <button id="offcanvasAlbumPickerBtnClose"
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body">
        <?php
        if (count($params['album_data'])) {
            $slug = '/album';
            $marginLeft = 0;
            create_entries($slug, $marginLeft, $params['album_data']);
        } else {
            ?>
            <div class="alert alert-warning">
                There are no Albums available
            </div>
            <?php
        }
        ?>
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