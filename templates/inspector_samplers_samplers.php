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
                Samplers
            </h4>

            <div class="card mb-4">
                <div class="card-header bg-dark text-light">
                    <h5 class="mt-2">
                        samplers.json
                    </h5>
                </div>
                <div id="jsonViewer" class="card-body text-dark">

                </div>
            </div>
        </div>
    </div>
</div>

<div id="samplersJson"
     class="d-none">
    <?php echo $params['json']; ?>
</div>

<script>
    class InspectorSamplersSamplers {
        constructor() {
            let element = document.getElementById('samplersJson');
            if (typeof(element) == 'undefined' || element == null) {
                return;
            }
            let json = JSON.parse(element.innerHTML);
            document.getElementById('jsonViewer').innerHTML = jsonViewer(json, false);
        }


    }
    const inspectorSamplersSamplers = new InspectorSamplersSamplers();
</script>