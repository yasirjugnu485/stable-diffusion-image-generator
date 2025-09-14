<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div id=toast-success"
     class="toast toast-success bg-success">
    <div class="toast-header">
        Success
        <button type="button"
                class="btn-close"
                data-bs-dismiss="toast">
        </button>
    </div>
    <div id="toast-success-body"
         class="toast-body rounded-bottom bg-white text-dark"></div>
</div>

<div id=toast-error"
     class="toast toast-error bg-error">
    <div class="toast-header">
        Success
        <button type="button"
                class="btn-close"
                data-bs-dismiss="toast">
        </button>
    </div>
    <div id="toast-error-body"
         class="toast-body rounded-bottom bg-white text-dark"></div>
</div>

<script>
    class Toast {
        success = (message) => {
            document.getElementById("toast-success-body").innerHTML = message;
            var toastElList = [].slice.call(document.querySelectorAll('.toast-success'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(toast => toast.show())
        }
        error = (message) => {
            document.getElementById("toast-error-body").innerHTML = message;
            var toastElList = [].slice.call(document.querySelectorAll('.toast-error'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(toast => toast.show())
        }
    }

    const toast = new Toast();

    <?php
    if ((isset($params['error']) && $params['error']) || (isset($params['success']) && $params['success'])) {
    ?>
    window.addEventListener('DOMContentLoaded', function () {
        <?php
        if (isset($params['success']) && $params['success']) {
        ?>
        toast.success("<?php echo $params['success']; ?>");
        <?php
        }
        ?>
        <?php
        if (isset($params['error']) && $params['error']) {
        ?>
        toast.error("<?php echo $params['error']; ?>");
        <?php
        }
        ?>
    });
    <?php
    }
    ?>
</script>