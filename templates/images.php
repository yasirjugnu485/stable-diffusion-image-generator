<?php
if (count($params['data'])) {
    ?>
    <div id="modal-lightbox" class="modal">
        <span class="close cursor" onclick="closeModal()">&times;</span>
        <div class="modal-content">

            <?php
            $index = 1;
            foreach ($params['data']['payloads'] as $image) {
                if (file_exists(ROOT_DIR . $image['file'])) {
                    ?>
                    <div class="mySlides">
                        <img src="/image.php?image=<?php echo urlencode($image['file']); ?>" style="width:100%"
                             onclick="openModal(); currentSlide(<?php echo $index; ?>)">
                    </div>
                    <?php
                    $index++;
                }
            }
            ?>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
            <div class="caption-container">
                <p id="caption"></p>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="container mb-5" style="max-width: 1600px">
    <div class="row">
        <div class="col-12">
            <?php include(ROOT_DIR . 'templates/breadcrumbs.php'); ?>
        </div>
        <?php
        if (count($params['data'])) {
            $index = 1;
            foreach ($params['data']['payloads'] as $image) {
                include(ROOT_DIR . 'templates/image.php');
                $index++;
            }
        } else {
            ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    There are no images yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>