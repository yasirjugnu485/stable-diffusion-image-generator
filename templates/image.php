<div class="col-12 mb-4">
    <div class="card border border-dark">
        <div class="card-header bg-dark text-light">
            <h5 class="float-left mb-0 mt-1">
                <?php
                $split = explode('/', $image['file']);
                echo end($split);
                ?>
            </h5>
            <div class="float-right bg-primary px-2 py-1 rounded">
                <?php
                echo $image['mode'];
                ?>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <h4>
                        Created image
                    </h4>
                    <?php
                    if (file_exists(ROOT_DIR . $image['file'])) {
                        ?>
                        <img class="w-100 border rounded"
                             src="/image.php?image=<?php echo urlencode($image['file']); ?>">
                        <?php
                    } else {
                        ?>
                        <div class="text-center">
                            <img class="w-100 rounded"
                                 src="/out/img/image-not-found.png">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0 d-block<?php if (!isset($image['payload']['init_images']) || !file_exists(ROOT_DIR . $image['payload']['init_images'])) {
                    echo ' d-none';
                } ?>">
                    <h4>
                        Initial image
                    </h4>
                    <?php
                    if (isset($image['payload']['init_images']) && file_exists(ROOT_DIR . $image['payload']['init_images'])) {
                        ?>
                        <img class="w-100 border rounded"
                             src="/image.php?image=<?php echo urlencode($image['payload']['init_images']); ?>">
                        <?php
                    } else {
                        ?>
                        <div class="text-center">
                            <img class="w-100 rounded"
                                 src="/out/img/image-not-found.png">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-12 col-lg-4">
                    <h4>
                        Data
                    </h4>
                    <strong>
                        File:
                    </strong>
                    <?php
                    echo $image['file'];
                    ?>
                    </p>
                    <?php
                    if (isset($image['payload']['override_settings']['sd_model_checkpoint'])) {
                        ?>
                        <p>
                            <strong>
                                Model:
                            </strong>
                            <?php
                            echo $image['payload']['override_settings']['sd_model_checkpoint'];
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['payload']['prompt'])) {
                        ?>
                        <p>
                            <strong>
                                Prompt:
                            </strong>
                            <?php
                            echo $image['payload']['prompt'];
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['payload']['steps'])) {
                        ?>
                        <p>
                            <strong>
                                Steps:
                            </strong>
                            <?php
                            echo $image['payload']['steps'];
                            ?>
                        </p>
                        <?php
                    }
                    if (isset($image['payload']['width']) && isset($image['payload']['height'])) {
                    ?>
                    <p>
                        <strong>
                            Size:
                        </strong>
                        <?php
                        echo $image['payload']['width'] . ' x ' . $image['payload']['height'];
                        ?>
                        <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>