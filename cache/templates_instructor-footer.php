<?php class_exists('Template') or exit; ?>
<?php foreach($scripts as $script): ?>
    <script src="<?php echo $script ?>" type="text/javascript"></script>
<?php endforeach; ?>

<script type="text/javascript">
    jQuery.fn.exists = function(){ return this.length > 0; }

    $(function() {

        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '<?php echo $manage_url ?>'
        });

        $('#fileupload')
            .bind('fileuploaddrop', function (e, data) {
                console.log('drop');
            })
            .bind('fileuploadchange', function (e, data) {
                console.log('change');
            })
            .bind('fileuploadalways', function (e, data) {
                console.log('always');
            });

            // Load existing files:
        $('#fileupload').addClass('fileupload-processing');

        $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').fileupload('option', 'url'),
                dataType: 'json',
                context: $('#fileupload')[0]
        })
        .always(function () {
            $(this).removeClass('fileupload-processing');
        })
        .done(function (result) {
            $(this)
            .fileupload('option', 'done')
            // eslint-disable-next-line new-cap
            .call(this, $.Event('done'), { result: result });
        });

        $(document).bind('dragover', function (e) {
            var dropZone = $('#dropzone'),
                timeout = window.dropZoneTimeout;
            if (timeout) {
                clearTimeout(timeout);
            } else {
                dropZone.addClass('in');
            }
            var hoveredDropZone = $(e.target).closest(dropZone);
            dropZone.toggleClass('hover', hoveredDropZone.length);
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });
    });
</script>
