<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (count($params['images'])) {
    ?>
    <div id="modal-lightbox" class="modal">
        <span class="close cursor" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <?php
            $index = 1;
            foreach ($params['images'] as $image) {
                if (file_exists(ROOT_DIR . $image['file'])) {
                    ?>
                    <div class="mySlides">
                        <img class="w-100"
                             src="/image.php?image=<?php echo urlencode($image['file']); ?>"
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

    <script>
        function openModal() {
            document.getElementById("modal-lightbox").style.display = "block";
        }

        function closeModal() {
            document.getElementById("modal-lightbox").style.display = "none";
        }

        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("demo");
            let captionText = document.getElementById("caption");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
        }
    </script>
    <?php
}