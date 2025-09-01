<nav class="navbar navbar-expand-lg bg-body-tertiary bg-primary navbar-dark mb-5">
    <div class="container-fluid">
        <a class="navbar-brand text-light"
           href="/">
            <img src="/out/img/stable-diffusion.png" style="height: 2rem; margin-top: -0.25rem;">
            Image Generator
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['txt2img']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['types']['txt2img'])) {
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['img2img']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['types']['img2img'])) {
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['types']['loop']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['types']['loop'])) {
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['checkpoints']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['checkpoints'])) {
                            foreach ($params['navbar']['checkpoints'] as $entry) {
                                ?>
                                <li>
                                    <a class="dropdown-item text-dark" href="/chekpoints/<?php echo $entry; ?>">
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

            </ul>
        </div>
    </div>
</nav>