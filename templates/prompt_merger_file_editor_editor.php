<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

if ($params['prompt_merger_directory'] !== 'Demo') {
    ?>
    <div class="offcanvas offcanvas-end"
         tabindex="-1"
         id="offcanvasPromptMergerFileEditorEditorDeleteFile"
         aria-labelledby="offcanvasPromptMergerFileEditorEditorDeleteFileLabel"
         style="width: 600px; max-width: 100%">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"
                id="offcanvasPromptMergerFileEditorEditorDeleteFileLabel">Delete Prompt Merger File
            </h5>
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                Be careful when deleting Prompt Merger Files. The Prompt Merger File will be irretrievably lost.
            </div>
            <form method="post">
                <div class="mb-3">
                    <input type="hidden"
                           name="action"
                           value="deletePromptMergerFile">
                    <input type="hidden"
                           name="directory"
                           value="<?php echo $params['prompt_merger_directory']; ?>">
                    <input type="hidden"
                           id="PromptMergerFileEditorEditorDeleteFile"
                           name="file">
                    <label for="PromptMergerFileEditorEditorDeleteFileInput"
                           class="form-label">
                        Prompt Merger File Name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="PromptMergerFileEditorEditorDeleteFileInput"
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
    <?php
}
?>

<div class="container">
    <div class="row">
        <h4>
            <?php echo str_replace('_', ' ', $params['prompt_merger_directory']); ?>
        </h4>
        <?php
        if (count($params['prompt_merger_files'])) {
            ?>
            <form method="post">
                <input type="hidden"
                       name="action"
                       value="editPromptMergerFiles">
                <input type="hidden"
                       name="directory"
                       value="<?php echo $params['prompt_merger_directory']; ?>">
                <div class="col-12">
                    <?php
                    foreach ($params['prompt_merger_files'] as $file) {
                        ?>
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-light">
                                <h5 class="float-start mt-2">
                                    <?php echo str_replace('_', ' ', $file['name']); ?>
                                </h5>
                                <?php
                                if (count($params['prompt_merger_files']) > 1 && $params['prompt_merger_directory'] !== 'demo') {
                                    ?>
                                    <button class="btn btn-danger float-end"
                                            type="button"
                                            onclick="promptMergerFileEditorEditor.deleteFile('<?php echo $file['name']; ?>')">
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
                                           value="<?php echo str_replace('_', ' ', $file['name']); ?>"
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
                if ($params['prompt_merger_directory'] !== 'Demo') {
                    ?>
                    <div class="text-end mb-5">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-floppy-fill me-1"></i>
                            Save Files
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
                <div class="alert alert-warning text-white">
                    No Prompt Merger Files available
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    class PromptMergerFileEditorEditor {
        deleteFile = (file) => {
            document.getElementById("PromptMergerFileEditorEditorDeleteFile").value = file;
            document.getElementById("PromptMergerFileEditorEditorDeleteFileInput").value = file.replaceAll("_", " ");
            let offcanvasPromptMergerFileEditorEditorDeleteFile = document.getElementById("offcanvasPromptMergerFileEditorEditorDeleteFile");
            const offcanvas = new bootstrap.Offcanvas(offcanvasPromptMergerFileEditorEditorDeleteFile);
            offcanvas.show();
        }
    }

    const promptMergerFileEditorEditor = new PromptMergerFileEditorEditor();
</script>