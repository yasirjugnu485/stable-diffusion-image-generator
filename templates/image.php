<div class="col-12 mb-4">
    <div class="card border border-dark">
        <div class="card-header bg-dark text-light">
            <h5 class="float-start mb-0">
                <?php
                    echo $image['file'];
                ?>
            </h5>
            <h5 class="float-end mb-0">
                <?php
                echo $image['mode'];
                ?>
            </h5>
        </div>
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-4">
                    <?php
                        if (file_exists(ROOT_DIR . $image['file'])) {
                            ?>
                                <img class="w-100 border rounded"
                                     src="/image.php?image=<?php echo urlencode($image['file']);?>">
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
                <div class="col-4">
                    <?php
                    if (isset($image['payload']['init_images']) && file_exists(ROOT_DIR . $image['payload']['init_images'])) {
                        ?>
                        <img class="w-100 border rounded"
                             src="/image.php?image=<?php echo urlencode($image['payload']['init_images']);?>">
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
                <div class="col-4">
                    <?php
                        if (isset($image['payload']['override_settings']['sd_model_checkpoint'])) {
                            ?>
                            <div class="mb-4">
                                <strong>
                                    Model:
                                </strong>
                                <?php
                                echo $image['payload']['override_settings']['sd_model_checkpoint'];
                                ?>
                            </div>
                            <?php
                        }
                        if (isset($image['payload']['prompt'])) {
                            ?>
                                <div class="mb-4">
                                    <strong>
                                        Prompt:
                                    </strong>
                            <?php
                            echo $image['payload']['prompt'];
                            ?>
                                </div>
                            <?php
                        }
                        if (isset($image['payload']['steps'])) {
                            ?>
                            <div class="mb-4">
                                <strong>
                                    Steps:
                                </strong>
                                <?php
                                echo $image['payload']['steps'];
                                ?>
                            </div>
                            <?php
                        }
                        if (isset($image['payload']['width']) && isset($image['payload']['height'])) {
                            ?>
                            <div class="mb-4">
                                <strong>
                                    Size:
                                </strong>
                                <?php
                                echo $image['payload']['width'] . ' x ' . $image['payload']['height'];
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>