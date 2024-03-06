<?php class_exists('Template') or exit; ?>
<section>
    <div class="row">
        <div class="col-lg-12">
            <?php if ($context_id == '1') { ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle"></i>
                This is a test site and the files contained in this site will be removed every 5 minutes!
            </div>
            <?php } ?>
            <!-- The file upload form used as target for the file upload widget -->
            <form
                id="fileupload"
                action="<?php echo $manage_url ?>"
                method="POST"
                enctype="multipart/form-data">

                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-7">
                        <span>
                            <input type="checkbox" class="toggle" />
                        </span>

                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="fas fa-plus"></i>
                            <span>Add files...</span>
                            <input type="file" name="files[]" multiple />
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="fas fa-file-upload"></i>
                            <span>Upload all</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="fas fa-ban"></i>
                            <span>Cancel all</span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="far fa-trash-alt"></i>
                            <span>Delete selected</span>
                        </button>

                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>

                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div
                            class="progress progress-striped active"
                            role="progressbar"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <div id="dropzone" class="fade">Drop files to upload ...</div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <!-- <th>&nbsp;</th> -->
                            <th>Name</th>
                            <th>Size</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody class="files"></tbody>
                </table>
            </form>

        </div>
    </div>
</section>

<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery"
    class="blueimp-gallery blueimp-gallery-controls"
    aria-label="image gallery"
    aria-modal="true"
    role="dialog"
    data-filter=":even">
    <div class="slides" aria-live="polite"></div>
    <h3 class="title"></h3>
    <a class="prev"
        aria-controls="blueimp-gallery"
        aria-label="previous slide"
        aria-keyshortcuts="ArrowLeft"></a>
    <a class="next"
    aria-controls="blueimp-gallery"
    aria-label="next slide"
    aria-keyshortcuts="ArrowRight"></a>
    <a class="close"
        aria-controls="blueimp-gallery"
        aria-label="close"
        aria-keyshortcuts="Escape"></a>
    <a class="play-pause"
        aria-controls="blueimp-gallery"
        aria-label="play slideshow"
        aria-keyshortcuts="Space"
        aria-pressed="false"
        role="button"></a>
    <ol class="indicator"></ol>
</div>
