<?php
/* *************************************************************** *\
    "Montage n°X" page.
    Shows a given montage and its comments.
		Also allows logged users to post comments.
\* *************************************************************** */

$error_alert = '';
$comment_posted = '';

// Gets the image's path based on the id_image from the URL.
$imageMng = new MImageMng();
$image_path = $imageMng->select_single_montage(Router::$page['id_image']);

// Checks the image exists.
if ($image_path === FALSE)
 $error_alert = 'This image doesn\'t exist.';
else
{
	// Processes the removal of a previous montage.
	if (isset($_GET['remove']))
	{
    list($timestamp, $id_user_image) = explode('-', Router::$page['id_image']);
    if ($id_user_image == $_SESSION['id_user'])
      $imageMng->delete_montage(Router::$page['id_image']);
	}

	// Processes the posting of a new comment.
	$commentMng = new MCommentMng();
	if (isset($_POST['post_comment']))
	{
		// Checks the input is valid, or returns an error message.
		$error = $commentMng->check_comment($_POST);
		if ($error === FALSE)
		{
			$comment = new MComment($_POST);
			$commentMng->add_comment($comment);
		}
		else
		{
			$comment_posted = $_POST['comment'];
			$error_alert = '<div class="alert-box error"><span>error:</span> '.$error.'</div>';
		}
	}

	// Gets the comments on the image as an array.
	$comments = $commentMng->select_comments_for_image(Router::$page['id_image']);
}

// Sets the output values and calls the views.
$output->set_head_title('Montage n°'.Router::$page['id_image']);

require_once(Config::VIEW_HEADER);
require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
