<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (isset($params['images']) && count($params['images'])) {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeleteImage"
         aria-labelledby="offcanvasDeleteImageLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeleteImageLabel">Delete Image
            </h5>
            <button type="button"
                    id="offcanvasDeleteImageClose"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Images. The Image be irretrievably lost.
            </div>
            <input type="text"
                   class="form-control mb-4"
                   id="offcanvasDeleteImageFile"
                   disabled>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-danger"
                        onclick="offcanvasDeleteImage.deleteExecute()">
                    <i class="bi bi-trash me-1"></i>
                    Delete Image
                </button>
            </div>
        </div>
    </div>

    <script>
        class OffcanvasDeleteImage {
            constructor() {
                this.index = null;
                this.file = null;
            }

            deleteClick = (index, file) => {
                this.index = index;
                this.file = file;
                document.getElementById('offcanvasDeleteImageFile').value = file;
                let offcanvasDeleteImage = document.getElementById('offcanvasDeleteImage');
                const offcanvas = new bootstrap.Offcanvas(offcanvasDeleteImage);
                offcanvas.show();
            }

            deleteExecute = () => {
                document.getElementById('offcanvasDeleteImageClose').click();
                document.getElementById('image-' + this.index).remove();
                this.delete();
            }

            async delete() {
                const url = window.location;
                const formData = new FormData();
                formData.append('action', 'deleteImage');
                formData.append('image', this.file);
                const response = await fetch(url,
                    {
                        'method': 'POST',
                        'body': formData,
                    }
                );
            }
        }

        const offcanvasDeleteImage = new OffcanvasDeleteImage();
    </script>
    <?php
}