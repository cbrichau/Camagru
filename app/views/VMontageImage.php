<h1><?php echo $output->get_head_title(); ?></h1>

<?php
if ($image_path === FALSE)
{
  echo '
  <div class="alert-box error"><span>error:</span>
    '.$error_alert.'
  </div>';
}
else
{
	echo '<div id="image"><img src="'.Config::ROOT.$image_path.'"/></div>';

	if ($_SESSION['is_logged'] === FALSE)
		echo '<p>Please  log in or <a href="'.Config::ROOT.'index.php?cat=register">register</a> to post a comment.';
	else
	{
		echo $error_alert;
		?>
		<form method="POST">
	    <textarea name="comment" placeholder="Write a comment"><?php echo $comment_posted; ?></textarea>
	    <input type="hidden" name="id_image" value="<?php echo Router::$page['id_image']; ?>">
	    <input type="hidden" name="id_user" value="<?php echo (int)$_SESSION['id_user']; ?>">
	    <input type="submit" name="post_comment" value="Post comment">
	  </form>
		<?php
	}
	?>

	<div id="comments">
		<?php
		foreach ($comments as $comment)
		{
			?>
			<div>
				<p><span><?php echo $comment->get_username(); ?></span> <span><?php echo $comment->get_publication_date(); ?></span>
				<p><?php echo $comment->get_comment(); ?>
			</div>
			<?php
		}
		?>
	</div>

	<?php
}
?>
