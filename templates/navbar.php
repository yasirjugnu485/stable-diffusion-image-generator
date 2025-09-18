<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

?>
<nav class="navbar navbar-expand-lg bg-body-tertiary bg-primary navbar-dark mb-5">
    <div class="container-fluid">
        <a class="navbar-brand text-light float-start"
           href="/">
            <img class="float-start me-2 logo"
                 src="/out/img/stable-diffusion.png">
            <div class="float-start">
                <div class="brand-sm">
                    Stable Diffusion
                </div>
                <div class="brand-lg">
                    Image Generator
                </div>
            </div>
            <div class="clearfix"></div>
        </a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbar"
                aria-controls="navbar"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse"
             id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        txt2img
                    </a>
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['txt2img']) == 0) {
                        echo ' bg-warning';
                    } ?>">
                        <?php
                        if (count($params['navbar']['types']['txt2img'])) {
                            ?>
                            <li>
                                <a class="dropdown-item text-dark" href="/txt2img">
                                    Overview
                                </a>
                            </li>
                            <?php
                            foreach ($params['navbar']['types']['txt2img'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/txt2img/<?php echo $entry['slug']; ?>">
                                        <?php echo $entry['name']; ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="dropdown-item bg-warning text-dark">
                                No txt2img images available
                            </div>
                            <?php
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        img2img
                    </a>
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['img2img']) == 0) {
                        echo ' bg-warning';
                    } ?>">
                        <?php
                        if (count($params['navbar']['types']['img2img'])) {
                            ?>
                            <li>
                                <a class="dropdown-item text-dark" href="/img2img">
                                    Overview
                                </a>
                            </li>
                            <?php
                            foreach ($params['navbar']['types']['img2img'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/img2img/<?php echo $entry['slug']; ?>">
                                        <?php echo $entry['name']; ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="dropdown-item bg-warning text-dark">
                                No img2img images available
                            </div>
                            <?php
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        loop
                    </a>
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['loop']) == 0) {
                        echo ' bg-warning';
                    } ?>">
                        <?php
                        if (count($params['navbar']['types']['loop'])) {
                            ?>
                            <li>
                                <a class="dropdown-item text-dark" href="/loop">
                                    Overview
                                </a>
                            </li>
                            <?php
                            foreach ($params['navbar']['types']['loop'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/loop/<?php echo $entry['slug']; ?>">
                                        <?php echo $entry['name']; ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="dropdown-item bg-warning text-dark">
                                No loop images available
                            </div>
                            <?php
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        checkpoints
                    </a>
                    <ul class="dropdown-menu<?php if (count($params['navbar']['checkpoints']) == 0) {
                        echo ' bg-warning';
                    } ?>">
                        <?php
                        if (count($params['navbar']['checkpoints'])) {
                            ?>
                            <li>
                                <a class="dropdown-item text-dark" href="/checkpoints">
                                    Overview
                                </a>
                            </li>
                            <?php
                            foreach ($params['navbar']['checkpoints'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/checkpoints/<?php echo $entry; ?>">
                                        <?php echo $entry; ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="dropdown-item bg-warning text-dark">
                                No checkpoints available
                            </div>
                            <?php
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        album
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item text-dark" href="/album">
                                Overview
                            </a>
                        </li>
                        <?php
                        if (count($params['navbar']['albums'])) {
                            foreach ($params['navbar']['albums'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/album/<?php echo $entry; ?>">
                                        <?php echo str_replace('_', ' ', $entry); ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </li>

            </ul>
            <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="bi bi-lightning-fill"></i>
                        Image Generator
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item text-dark" href="/prompt-merger">
                                <i class="bi bi-files"></i>
                                Prompt Merger
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-dark" href="/initialize-images">
                                <i class="bi bi-images"></i>
                                Initialize Images
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-dark" href="/settings">
                                <i class="bi bi-gear-fill"></i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-dark" href="/generator">
                                <i class="bi bi-lightning-fill"></i>
                                Image Generator
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>