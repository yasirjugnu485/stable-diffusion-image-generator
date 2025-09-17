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
         id="offcanvasImageDelete"
         aria-labelledby="offcanvasImageDeleteLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasImageDeleteLabel">Delete Image
            </h5>
            <button type="button"
                    id="offcanvasImageDeleteClose"
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
                   id="offcanvasImageDeleteFile"
                   disabled>
            <div class="text-end">
                <button type="submit"
                        class="btn btn-danger"
                        onclick="imageDelete.deleteExecute()">
                    <i class="bi bi-trash me-1"></i>
                    Delete Image
                </button>
            </div>
        </div>
    </div>

    <script>
        class ImageDelete {
            constructor() {
                this.index = null;
                this.file = null;
            }

            deleteClick = (index, file) => {
                this.index = index;
                this.file = file;
                document.getElementById("offcanvasImageDeleteFile").value = file;
                let offcanvasImageDelete = document.getElementById("offcanvasImageDelete");
                const offcanvas = new bootstrap.Offcanvas(offcanvasImageDelete);
                offcanvas.show();
            }

            deleteExecute = () => {
                document.getElementById("offcanvasImageDeleteClose").click();
                document.getElementById("image-" + this.index).remove();
                this.delete();
            }

            async delete() {
                const url = window.location;
                const formData = new FormData();
                formData.append("action", "deleteImage");
                formData.append("image", this.file);
                await fetch(url, {
                    method: "POST",
                    body: formData,
                }).then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                }).then(response => {
                    if (response.success) {
                        toast.success(response.message);
                    } else {
                        toast.error(response.message);
                    }
                });
            }
        }

        const imageDelete = new ImageDelete();
    </script>
    <?php
}