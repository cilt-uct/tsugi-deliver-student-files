<?php class_exists('Template') or exit; ?>
<!DOCTYPE html>
<html>
<body>
<!-- <?php echo $simple_url ?>-->

<form action="<?php echo $manage_url ?>"
    method="POST"
    enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="files" id="files">

  <input type="submit" value="Upload Image" name="submit">
</form>

<form action="<?php echo $manage_url ?>"
    method="POST"
    enctype="multipart/form-data">
  Multiple
  <input type="file" name="files[]" multiple>

  <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>