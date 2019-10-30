<?php
echo '<h1>'.$output->get_head_title().'</h1>';

if ($like_error_alert === TRUE)
{
	?>
	<div class="alert-box error"><span>error:</span>
		Please log in or <a href="<?php echo Config::ROOT; ?>index.php?cat=register">register</a> to like.
	</div>
	<?php
}

for ($i = $pagination['start_i']; $i < $pagination['end_i']; $i++)
{
	$id_image = basename($montage_paths[$i], ".png");
	$nb_comments = (isset($comments[$id_image])) ? $comments[$id_image] : 0;

	if (isset($likes[$id_image]) && $_SESSION['is_logged'] === TRUE)
	{
		$nb_likes = count($likes[$id_image]);
		$user_has_liked = (in_array($_SESSION['id_user'], $likes[$id_image])) ? TRUE : FALSE;
	}
	else
	{
		$nb_likes = 0;
		$user_has_liked = FALSE;
	}

	echo '<div class="montage">';

	echo '<a href="'.Config::ROOT.'index.php?cat=montage&id_image='.$id_image.'">
					<img src="'.Config::ROOT.$montage_paths[$i].'"/>
				</a>';

	echo '<span>'.$nb_likes.' likes ';

		if (!$user_has_liked)
			echo '<form method="POST">
							<input type="hidden" name="id_image" value="'.$id_image.'">
							<input type="submit" name="like" value="+">
						</form>';

	echo '</span>
				<span>'.$nb_comments.' comments</span>
				</div>';
}
?>

<div id="pagination"><p>Page :
	<?php
	for ($i = 1; $i <= $pagination['nb_pages']; $i++)
	{
		if ($i == $pagination['current_page'])
			echo ' '.$i.' ';
		else
			echo ' <a href="'.Config::ROOT.'index.php?cat=home&page='.$i.'">'.$i.'</a> ';
	}
	?>
</div>
