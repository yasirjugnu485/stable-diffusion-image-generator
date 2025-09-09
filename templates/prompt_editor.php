<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ($params['directory'] !== 'demo') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeletePromptMergerDirectory"
         aria-labelledby="offcanvasDeletePromptMergerDirectoryLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeletePromptMergerDirectoryLabel">Delete Prompt Merger Directory
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Prompt Merger Directories. All Prompt files will be irretrievably lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deletePromptMergerDirectory">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['directory']; ?>">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasDeletePromptMergerFile"
         aria-labelledby="offcanvasDeletePromptMergerFileLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasDeletePromptMergerFileLabel">Delete Prompt Merger File
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Prompt Merger Files. The prompts will be irretrievably lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deletePromptMergerFile">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['directory']; ?>">
                    <input type="hidden"
                           id="deletePromptMergerFile"
                           name="file">
                    <label for="deletePromptMergerFileInput"
                           class="form-label">
                        Prompt Merger File Name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="deletePromptMergerFileInput"
                           name="file"
                           disabled>
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasAddPromptMergerFile"
         aria-labelledby="offcanvasAddPromptMergerFileLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasAddPromptMergerFileLabel">Add new Prompt Merger File
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                The Prompt Merger File name may only contain numbers, letters, _ and -.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="addPromptMergerFile">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['directory']; ?>">
                    <label for="addPromptMergerFileName"
                           class="form-label">
                        Prompt Merger File Name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="addPromptMergerFileName"
                           name="name">
                </div>
                <div class="text-end">
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<div class="container" style="max-width: 1600px">
    <div class="row">
        <?php
        if ($params['directory'] === 'demo') {
            ?>
            <div class="col-12">
                <div class="alert alert-warning mb-5">
                    The demo Prompt Merger Directory is part of the GIT repository and cannot be changed or deleted.
                </div>
            </div>
            <?php
        }
        ?>

        <?php if (isset($params['error'])) {
            ?>
            <div class="col-12">
                <div class="alert alert-danger mb-5">
                    <?php echo $params['error']; ?>
                </div>
            </div>
            <?php
        }
        if (isset($params['success'])) {
            ?>
            <div class="col-12">
                <div class="alert alert-success mb-5">
                    <?php echo $params['success']; ?>
                </div>
            </div>
            <?php
        }

        if ($params['directory'] !== 'demo') {
            ?>
            <div class="col-12 mb-5">
                <div class="float-start">
                    <button class="btn btn-danger"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDeletePromptMergerDirectory">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                </div>
                <div class="float-end">
                    <button class="btn btn-primary"
                            type="button"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasAddPromptMergerFile">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add
                    </button>
                </div>
            </div>
            <?php
        }
        ?>

        <h3>
            <?php echo $params['directory']; ?>
        </h3>
        <?php
        if (count($params['files'])) {
            ?>
            <form method="post">
                <input type="hidden"
                       name="action"
                       value="editPromptMergerFiles">
                <input type="hidden"
                       name="directory"
                       value="<?php echo $params['directory']; ?>">
                <div class="col-12">
                    <?php
                    foreach ($params['files'] as $file) {
                        ?>
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-light">
                                <h5 class="float-start mt-2">
                                    <?php echo $file['name']; ?>
                                </h5>
                                <?php
                                if (count($params['files']) > 1 && $params['directory'] !== 'demo') {
                                    ?>
                                    <button class="btn btn-danger float-end"
                                            type="button"
                                            onclick="promptEditor.deleteFile('<?php echo $file['name']; ?>')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name<?php echo $file['name']; ?>"
                                           class="form-label">
                                        File name
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="name<?php echo $file['name']; ?>"
                                           value="<?php echo $file['name']; ?>"
                                           name="name[<?php echo $file['name']; ?>]">
                                </div>
                                <div>
                                    <label for="content<?php echo $file['name']; ?>"
                                           class="form-label">
                                        Prompts
                                    </label>
                                    <textarea
                                            class="form-control"
                                            id=content<?php echo $file['name']; ?>"
                                            rows="20"
                                            name="content[<?php echo $file['name']; ?>]"><?php echo $file['content']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php
                if ($params['directory'] !== 'demo') {
                    ?>
                    <div class="text-end mb-5">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-floppy-fill me-1"></i>
                            Save
                        </button>
                    </div>
                    <?php
                }
                ?>
            </form>
            <?php
        } else {
            ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    No Prompt Merger Files available yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    class PromptEditor
    {
        deleteFile = (file) => {
            document.getElementById('deletePromptMergerFile').value = file;
            document.getElementById('deletePromptMergerFileInput').value = file;
            let offcanvasDeletePromptMergerFile = document.getElementById('offcanvasDeletePromptMergerFile');
            const offcanvas = new bootstrap.Offcanvas(offcanvasDeletePromptMergerFile);
            offcanvas.show();
        }
    }

    const promptEditor = new PromptEditor();
</script>