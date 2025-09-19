<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div class="container mb-5">
    <div class="row">
        <div class="col-12 text-dark">

            <h4 class="mb-3">
                Loras
            </h4>

            <div class="card mb-4">
                <div class="card-header bg-dark text-light">
                    <h5 class="mt-2">
                        loras.json
                    </h5>
                </div>
                <div id="jsonViewer"
                     class="card-body text-dark">

                </div>
            </div>
        </div>
    </div>
</div>

<div id="lorasJson"
     class="d-none">
    <?php echo $params['json']; ?>
</div>

<script>
    class InspectorLorasLoras {
        constructor() {
            let element = document.getElementById('lorasJson');
            if (typeof(element) == 'undefined' || element == null) {
                return;
            }
            let json = JSON.parse(element.innerHTML);
            document.getElementById('jsonViewer').innerHTML = jsonViewer(json, false);
        }


    }
    const inspectorLorasLoras = new InspectorLorasLoras();
</script>