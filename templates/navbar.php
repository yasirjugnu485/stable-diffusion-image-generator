<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-light"
           href="/">Stable Diffusion Image Generator</a>
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['txt2img']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['txt2img'])) {
                            foreach ($params['navbar']['txt2img'] as $entry) {
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['img2img']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['img2img'])) {
                            foreach ($params['navbar']['img2img'] as $entry) {
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
                    <ul class="dropdown-menu<?php if (count($params['navbar']['loop']) == 0) { echo ' bg-warning'; } ?>">
                        <?php
                        if (count($params['navbar']['loop'])) {
                            foreach ($params['navbar']['loop'] as $entry) {
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
            </ul>
        </div>
    </div>
</nav>