<?php
echo '<h1>'.$output->get_head_title().'</h1>';

if ($like_error_alert === TRUE)
	echo '<div class="alert-box error"><span>error:</span> Please log in or <a href="'.Config::ROOT.'register">register</a> to like</div>';

for ($i = $pagination['start_i']; $i < $pagination['end_i']; $i++)
{
	$id_image = basename($montage_paths[$i], ".png");
	$nb_likes = $likes[$id_image];
	$nb_comments = (isset($comments[$id_image])) ? $comments[$id_image] : 0;

	echo '<div class="montage">
	  			<a href="'.Config::ROOT.'montage/'.$id_image.'">
	  				<img src="'.Config::ROOT.$montage_paths[$i].'"/>
	  			</a>
	  			<span>'.$nb_likes.' likes
	  				<form method="POST">
	  					<input type="hidden" name="id_image" value="'.$id_image.'">
	  					<input type="submit" name="like" value="+">
	  				</form>
	  			</span>
	  			<span>'.$nb_comments.' comments</span>
	  		</div>';
}

echo '<div id="pagination"><p>Page : '

for ($i = 1; $i <= $pagination['nb_pages']; $i++)
	echo '<a href="'.Config::ROOT.'?page='.$i.'">'.$i.'</a> ';

echo '</div>';
