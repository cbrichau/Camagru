<?php
class MCommentMng extends M_Manager
{
  /* *********************************************************** *\
      SELECT, ADD, COUNT
  \* *********************************************************** */

  public function select_comments_for_image($id_image)
  {
    $sql = 'SELECT publication_date, comment, username
            FROM comments
            JOIN users USING(id_user)
            WHERE id_image = :id_image
            ORDER BY publication_date DESC';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_image', $id_image, PDO::PARAM_INT);
    $query->execute();

    $comments = array();
    while ($r = $query->fetch())
    {
      $r['publication_date'] = date('l jS \of F Y \a\t H:i', strtotime($r['publication_date']));
    	$comments[] = new MComment($r);
    }
    return $comments;
  }

	public function add_comment(MComment $comment)
  {
    $sql = 'INSERT INTO comments
           (id_image, id_user, publication_date, comment)
           VALUES
           (:id_image, :id_user, now(), :comment)';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_image', $comment->get_id_image(), PDO::PARAM_STR);
    $query->bindValue(':id_user', $comment->get_id_user(), PDO::PARAM_INT);
    $query->bindValue(':comment', $comment->get_comment(), PDO::PARAM_STR);
    $query->execute();

    $emailMng = new MEmailMng();
    $emailMng->notify_new_comment($comment->get_id_image());
    return $this->_db->lastInsertId();
  }

  public function count_comments_per_image()
  {
    $sql = 'SELECT id_image, COUNT(*) as nb_comments
            FROM comments
            GROUP BY id_image';
    $query = $this->_db->prepare($sql);
    $query->execute();

    $comments = array();
    while ($r = $query->fetch())
      $comments[$r['id_image']] = $r['nb_comments'];
    return $comments;
  }

  /* *********************************************************** *\
      CHECK_COMMENT
      Checks input from $_POST is valid, or returns an error msg.
  \* *********************************************************** */

  public function check_comment(array $post)
  {
    if (empty($post['comment']))
      return 'Please enter a comment.';

    if ($this->is_valid_string_format($post['comment']) === FALSE)
      return 'Invalid comment.';

    if (empty($post['id_user']))
      return 'You must have an account to post comments.';

    $userMng = new MUserMng();
    $user = $userMng->select_user_by('id_user', $post['id_user']);
    if (is_null($user))
      return 'You must have an account to post comments.';

    return FALSE;
  }
}
