<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success text-white">
                Image generation has started
            </div>
        </div>
    </div>
</div>

<script>
    class Generate {
        async startGenerating() {
            const url = "/generate";
            const response = await fetch(url);
        }
    }

    const generate = new Generate();
    generate.startGenerating();
</script>