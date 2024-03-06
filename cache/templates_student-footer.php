<?php class_exists('Template') or exit; ?>
<?php foreach($scripts as $script): ?>
    <script src="<?php echo $script ?>" type="text/javascript"></script>
<?php endforeach; ?>

<script type="text/javascript">
    jQuery.fn.exists = function(){ return this.length > 0; }

    function formatFileSize(bytes) {
      if (typeof bytes !== 'number') {
        return '';
      }
      if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
      }
      if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
      }
      return (bytes / 1000).toFixed(2) + ' KB';
    }

    $(function() {

        $.getJSON('<?php echo $manage_url ?>', function(data){
            $('#fileupload table > tbody').html(tmpl('template-download',data));
        })
        .fail(function() {
            console.log( "error" );
        });
    });
</script>
