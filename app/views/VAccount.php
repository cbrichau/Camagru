<form method="POST">
  <?php echo $modif_success_alert; ?>
  <h1><?php echo $output->get_head_title(); ?></h1>

  <?php echo $email_error_alert; ?>
  <input type="email" name="email" placeholder="email" value="<?php echo $email_form; ?>">

  <?php echo $username_error_alert; ?>
  <input type="text" name="username" placeholder="username" value="<?php echo $username_form; ?>">

  <?php echo $pass_error_alert; ?>
  <input type="password" name="pass" placeholder="password" value="">
  <input type="password" name="passcheck" placeholder="re-type password" value="">

  <input type="checkbox" name="notifications_on" id="notif" <?php echo $notif_form; ?>>
  <label for="notif">Get email notifications when my pics are commented.</label>

  <input type="submit" name="modify" value="Modify my account">
</form>
