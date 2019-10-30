<?php
/* *************************************************************** *\
    "Create montage" page.
    Shows montage filters, the webcam stream and a form to
    alternatively post a picture, and the user's previous montages.
\* *************************************************************** */

$output->set_head_title('Create montage');
require_once(Config::VIEW_HEADER);

// Only accessible to logged users.
// If user isn't logged, invites him/her to do so.
if ($_SESSION['is_logged'] === TRUE)
{
  $upload_error = FALSE;
  $montage_error = FALSE;
	$id_user = (int)$_SESSION['id_user'];
	$imageMng = new MImageMng();

  // Processes photo upload.
	if (isset($_FILES['photo']))
	{
    // Checks upload is valid and, if so, saves that image.
    $upload_error = $imageMng->check_uploaded_photo($_FILES) ||
                    $id_user < 0;
    if ($upload_error === FALSE)
      $uploaded_image = $imageMng->save_uploaded_photo($_FILES, $id_user);
	}
  // Processes new montage creation.
	else if (isset($_POST['montage']))
	{
    // Checks the input is valid, or returns "error = TRUE".
		$montage_error = $imageMng->check_montage_photo($_POST) ||
						         $imageMng->check_montage_filter($_POST) ||
						         $imageMng->check_montage_width($_POST) ||
						         $imageMng->check_montage_height($_POST) ||
						         $id_user < 0;

    // Executes the montage if there's no error in the input.
		if ($montage_error === FALSE)
      $imageMng->create_montage($id_user, $_POST['photo'], $_POST['filter'], $_POST['width'], $_POST['height']);
	}

  // Gets the filter pictures and the user's previous montages as arrays of file paths.
  $filter_paths = $imageMng->select_all_images('filters');
  $montage_paths = $imageMng->select_user_montages($id_user);
  require_once(Router::$page['view']);
}
else
{
  echo '<p>This page is reserved to members.
        <br/>Please log into your account or <a href="'.Config::ROOT.'index.php?cat=register">Register</a>';
}

require_once(Config::VIEW_FOOTER);
