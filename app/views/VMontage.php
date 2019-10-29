<h1><?php echo $output->get_head_title(); ?></h1>

<section id="left">
  <div id="filters">
    <?php
    foreach($filter_paths as $paths)
      echo '<img src="'.Config::ROOT.$paths.'"/>';
    ?>
  </div>

  <?php
  if ($montage_error === TRUE || $upload_error === TRUE)
  {
    echo '
    <div class="alert-box error"><span>error:</span>
      Bad or missing input.
    </div>';
  }
  else
  {
    if (isset($uploaded_image))
    {
      $js_file = 'create_montage_photo.js';
      echo '<img src="'.$uploaded_image.'" id="photo">';
    }
    else
    {
      $js_file = 'create_montage_video.js';
      echo '<video id="video" autoplay></video>';
    }
    ?>
    <canvas id="canvas_preview"></canvas>
    <canvas id="canvas_photo"></canvas>
    <form id="form" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="photo">
      <input type="hidden" name="filter">
      <input type="hidden" name="width">
      <input type="hidden" name="height">
      <input type="hidden" name="montage">
      <input type="submit" name="button" value="Take photo" disabled>
    </form>

    <form id="form2" method="POST" enctype="multipart/form-data">
      Or <input type="file" name="photo"> <input type="submit" name="button" value="Upload photo" disabled>
    </form>
    <?php
  }
  ?>
</section>

<section id="right">
  <?php
  foreach($montage_paths as $path)
    echo '
    <p>
      <img src="'.Config::ROOT.$path.'"/>
      <a href="'.Config::ROOT.'index.php?cat=montage&id_image='.basename($path, ".png").'&remove">Delete</a>
    </p>';
  ?>
</section>

<script type="text/javascript">
  (function()
  {
    var form2 = document.getElementById('form2');
    form2.elements['photo'].onchange = function()
    {
      if(this.value)
        form2.elements['button'].removeAttribute('disabled');
    }
  })();
</script>
<script type="text/javascript" src="<?php echo Config::JS_PATH.$js_file.'?time='.time(); ?>"></script>
