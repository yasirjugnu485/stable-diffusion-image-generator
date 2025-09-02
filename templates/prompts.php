<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if (count($params['prompts'])) {
    ?>
    <div class="container mb-5" style="max-width: 1600px">
        <div class="row">
            <div class="col-12">
                <h3>
                    Prompt Merger
                </h3>
                <?php
                foreach ($params['prompts'] as $prompt) {
                    ?>
                    <a href="/prompt-merger/<?php echo $prompt; ?>" style="text-decoration: none">
                        <button class="btn btn-secondary mb-2">
                            <?php echo $prompt; ?>
                        </button>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}