<div class="container" style="max-width: 1600px">
    <div class="row">
        <div class="col-12">
            <h2>
                <?php echo $params['header']; ?>
            </h2>
        </div>
        <?php
        if (count($params['data'])) {
            foreach ($params['data']['payloads'] as $image) {
                include(ROOT_DIR . 'templates/image.php');
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