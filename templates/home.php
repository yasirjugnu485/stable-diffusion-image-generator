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
                <div class="col-12">
                    <div class="alert alert-warning">
                        There are no recently images yet
                    </div>
                </div>
            <?php
        }
        ?>
    </div>
</div>
