<?php
/* ************************************************************** *\
    Manages emails (no matching object).
\* ************************************************************** */

class MEmailMng extends M_Manager
{
	/* *********************************************************** *\
      SEND_EMAIL:
      Sends the email given a destinator, subject and message.
  \* *********************************************************** */

	private function send_email($to, $subject, $message)
	{
		$from = 'cbrichau@student.s19.be';

		$headers = 'MIME-Version: 1.0'.'\r\n'.
							 'Content-type:text/html;charset=UTF-8'.'\r\n'.
							 'From: <'.$from.'>';

		$content = wordwrap($message, 70);

		mail($to, $subject, $content, $headers);
	}

	/* *********************************************************** *\
      SEND_REGISTRATION_CONFIRMATION, NOTIFY_NEW_COMMENT:
			Define the emails to send in both cases.
  \* *********************************************************** */

	public function send_registration_confirmation(MUser $user)
	{
		$to = $user->get_email();
		$code = $user->get_id_user().'-'.$user->get_email_confirmed();
		$subject = 'Validate your registration';
		$message = 'Please click on the link to validate your registration: '.Config::ROOT.'index.php?cat=register&confirm='.$code;
		$this->send_email($to, $subject, $message);
	}

	public function send_reset_confirmation(MUser $user, $password_confirmation_code)
	{
		$to = $user->get_email();
		$code = $user->get_id_user().'-'.$password_confirmation_code;
		$subject = 'Validate your new password';
		$message = 'Please click on the link to validate your new password: '.Config::ROOT.'index.php?cat=reset&confirm='.$code;
		$this->send_email($to, $subject, $message);
	}

	public function notify_new_comment($id_image)
	{
		$split = explode('-', $id_image);
		$id_user = $split[1];

		$userMng = new MUserMng();
		$user = $userMng->select_user_by('id_user', $id_user);

		if ($user->get_notifications_on() == TRUE)
		{
			$to = $user->get_email();
			$subject = 'You have a new comment';
			$message = 'You have a new comment on your image: '.Config::ROOT.'index.php?cat=montage&id_image='.$id_image;
			$this->send_email($to, $subject, $message);
		}
	}
}
