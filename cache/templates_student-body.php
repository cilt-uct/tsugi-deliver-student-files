<?php class_exists('Template') or exit; ?>
<section>
    <div class="row">
        <div class="col-lg-12">
            <form id="fileupload">
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <thead>
                        <tr>
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
