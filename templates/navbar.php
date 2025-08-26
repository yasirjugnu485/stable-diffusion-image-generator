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
                    <a class="nav-link dropdown-toggle text-light" h
                       ref="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        txt2img
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                            if (count($params['navbar']['txt2img'])) {
                                foreach ($params['navbar']['txt2img'] as $entry) {
                                    ?>
                                        <li>
                                            <a class="dropdown-item" href="/txt2img/<?php echo $entry['slug']; ?>">
                                                <?php echo $entry['name']; ?>
                                            </a>
                                        </li>
                                    <?php
                                }
                            } else {

                            }
                        ?>
                    </ul>
                </li>
                <li class="nav-item text-light">
                    <a class="nav-link active text-light"
                       aria-current="page"
                       href="/img2img">
                        img2img
                    </a>
                </li>
                <li class="nav-item text-light">
                    <a class="nav-link active text-light"
                       aria-current="page"
                       href="/loop">
                        loop
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>