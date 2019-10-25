<form method="POST">
	<?php echo $validation_alert; ?>
  <h1><?php echo $output->get_head_title(); ?></h1>

  <?php echo $email_error_alert; ?>
  <input type="email" name="email" placeholder="email" value="<?php echo $email_posted; ?>">

	<?php echo $username_error_alert; ?>
  <input type="text" name="username" placeholder="username" value="<?php echo $username_posted; ?>">

	<?php echo $pass_error_alert; ?>
  <input type="password" name="pass" placeholder="password" value="">
  <input type="password" name="passcheck" placeholder="re-type password" value="">

	<input type="submit" name="register" value="Create my account">
</form>
