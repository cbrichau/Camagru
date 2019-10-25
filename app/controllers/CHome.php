<?php
/* *********************************************************** *\
    "Gallery" (home) page.
    Shows all the users' montages.
\* *********************************************************** */

$imageMng = new MImageMng();

// Selects the montages as an array of file paths.
$montage_paths = $imageMng->select_all_images('montages');

// Defines the pagination values.
$pagination = $imageMng->get_pagination_values($montage_paths, $_GET);

// Processes 'Like' button clicks.
$like_error_alert = FALSE;
if (isset($_POST['like']))
{
	if ($_SESSION['is_logged'] === TRUE)
		$imageMng->add_like($_POST['id_image']);
	else
		$like_error_alert = TRUE;
}

// Gets the number of likes and comments per image
// as an array(id_image => value).
$likes = $imageMng->count_likes_per_image();
$commmentMng = new MCommentMng();
$comments = $commmentMng->count_comments_per_image();

// Sets the output values and calls the views.
$output->set_head_title('Gallery (page '.$pagination['current_page'].')');

require_once(Config::VIEW_HEADER);
require_once(Router::$page['view']);
require_once(Config::VIEW_FOOTER);
