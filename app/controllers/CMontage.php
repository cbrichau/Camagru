<?php
/* *************************************************************** *\
    "Create montage" page.
    Shows montage filters, the webcam stream and a form to
    alternatively post a picture, and the user's previous montages.
\* *************************************************************** */

// Only accessible to logged users.
// If user isn't logged, invites him/her to do so.
if ($_SESSION['is_logged'] === FALSE)
{
  $error_alert = '<p>This page is reserved to members.';
  $error_alert .= '<br/>Please log into your account or <a href="'.Config::ROOT.'register">Register</a>';
}
else
{
  $montage_error_alert = FALSE;
	$id_user = (int)$_SESSION['id_user'];
	$imageMng = new MImageMng();

  // Processes new montage creation
	if (isset($_POST['montage']))
	{
    // Checks the input is valid, or returns "error = TRUE".
		$error = $imageMng->check_montage_photo($_POST) ||
						 $imageMng->check_montage_filter($_POST) ||
						 $imageMng->check_montage_width($_POST) ||
						 $imageMng->check_montage_height($_POST) ||
						 $id_user <= 0;
		if ($error === FALSE)
			$imageMng->create_montage($id_user, $_POST['photo'], $_POST['filter'], $_POST['width'], $_POST['height']);
	  else
			$montage_error_alert = TRUE;
	}

  // Gets the filter pictures and the user's previous montages as arrays of file paths.
  $filter_paths = $imageMng->select_all_images('filters');
  $montage_paths = $imageMng->select_user_montages($id_user);
}

// Sets the output values and calls the views.
$output->set_head_title('Create montage');

require_once(Config::VIEW_HEADER);
if ($_SESSION['is_logged'] === FALSE)
  echo $error_alert;
else
  require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
