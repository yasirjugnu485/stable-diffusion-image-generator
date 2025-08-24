<div class="container" style="max-width: 1600px">
    <div class="row">
        <h2>
            Recently generated images
        </h2>
        <?php
        if (count($params['data'])) {
            foreach ($params['data']['payloads'] as $image) {
                include (ROOT_DIR . 'templates/image.php');
            }

        } else {
            ?>

            <?php
        }
        ?>
    </div>
</div>
