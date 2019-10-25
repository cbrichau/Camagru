<h1><?php echo $output->get_head_title(); ?></h1>

<section id="left">
  <div id="filters">
    <?php
    foreach($filter_paths as $paths)
      echo '<img src="'.Config::ROOT.$paths.'"/>';
    ?>
  </div>

  <?php
  if ($montage_error_alert === TRUE)
  	echo '<div class="alert-box error"><span>error:</span> Bad or missing form input.</div>';
  ?>
  <video id="video" autoplay></video>
  <canvas id="canvas_preview"></canvas>
  <canvas id="canvas_photo"></canvas>
  <form id="form" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="photo">
    <input type="hidden" name="filter">
    <input type="hidden" name="width">
    <input type="hidden" name="height">
    <input type="hidden" name="montage">
    <input type="submit" value="Take photo">
  </form>
</section>

<section id="right">
  <?php
  foreach($montage_paths as $path)
    echo '
    <p>
      <img src="'.Config::ROOT.$path.'"/>
      <a href="'.Config::ROOT.'montage/'.basename($path, ".png").'?remove">Delete</a>
    </p>';
  ?>
</section>

<script type="text/javascript" src="<?php echo Config::JS_PATH; ?>create_montage.js"></script>
