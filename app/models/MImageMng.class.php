<?php
class MImageMng extends M_Manager
{
  /* *********************************************************** *\
      SELECT
  \* *********************************************************** */

  public function select_all_images($folder)
  {
    $img_paths = array_reverse(glob(Config::IMAGES_PATH.$folder.'/*.*'));
    return $img_paths;
  }

  public function select_user_montages($id_user)
  {
    $img_paths = array_reverse(glob(Config::IMAGES_PATH.'montages/*-'.$id_user.'.png'));
    return $img_paths;
  }

  public function select_single_montage($id_image)
  {
    $img_path = Config::IMAGES_PATH.'montages/'.$id_image.'.png';
    if (file_exists($img_path))
      return $img_path;
    else
      return FALSE;
  }

  /* *********************************************************** *\
      PAGINATION
  \* *********************************************************** */

  public function get_pagination_values(array $montages, array $get)
  {
    $nb_montages = count($montages);
    $montages_per_page = 6;
    $pagination['nb_pages'] = ceil ($nb_montages / $montages_per_page);

    if (isset($get['page']) && $this->is_valid_int_format($get['page']) === TRUE)
      $pagination['current_page'] = $get['page'];
    else
      $pagination['current_page'] = 1;

    $pagination['start_i'] = ($pagination['current_page'] - 1) * $montages_per_page;

    $pagination['end_i'] = $pagination['start_i'] + $montages_per_page;
    if ($pagination['end_i'] > $nb_montages)
      $pagination['end_i'] = $nb_montages;

    return $pagination;
  }

  /* *********************************************************** *\
      LIKES
  \* *********************************************************** */

  public function count_likes_per_image()
  {
    try
    {
      $likes = array();

      $sql = 'SELECT id_image, SUM(nb_likes) as nb_likes
              FROM likes
              GROUP BY id_image';
      $query = $this->_db->prepare($sql);
      $query->execute();
      while ($r = $query->fetch())
        $likes[$r['id_image']] = $r['nb_likes'];
      return $likes;
    }
    catch (PDOException $e)
    {
      $error_msg = $e->getMessage();
      //show error page
    }
  }

  public function add_like($id_image)
  {
    try
    {
      $sql = 'UPDATE likes
              SET nb_likes = nb_likes + 1
              WHERE id_image = :id_image';
      $query = $this->_db->prepare($sql);
      $query->bindValue(':id_image', $id_image, PDO::PARAM_STR);
      $query->execute();
    }
    catch (PDOException $e){ die('DB error: '.$e->getMessage()); }
  }

  private function initialise_likes($id_image)
  {
    try
    {
      $sql = 'INSERT INTO likes
             (id_image, nb_likes)
             VALUES
             (:id_image, 0)';
      $query = $this->_db->prepare($sql);
      $query->bindValue(':id_image', $id_image, PDO::PARAM_STR);
      $query->execute();
    }
    catch (PDOException $e){ die('DB error: '.$e->getMessage()); }
  }

  /* *********************************************************** *\
      CREATE
  \* *********************************************************** */

  public function create_montage($id_user, $photo, $filter, $width, $height)
  {
    list($filter_real_width, $filter_real_height) = getimagesize($filter);

    $photo = imagecreatefromstring(base64_decode(str_replace(' ', '+', str_replace('data:image/png;base64,', '', $photo))));
    $filter = imagecreatefrompng($filter);

    imagecopyresized($photo, $filter, 0, 0, 0, 0, $width, $height, $filter_real_width, $filter_real_height);

    $id_image = time().'-'.$id_user;
    imagepng($photo, Config::IMAGES_PATH.'montages/'.$id_image.'.png');
    $this->initialise_likes($id_image);

    imagedestroy($photo);
    imagedestroy($filter);
  }

  /* *********************************************************** *\
      CHECK_MONTAGE_values
      Checks input from $_POST is valid,
      or returns error = TRUE.
  \* *********************************************************** */

  public function check_montage_photo(array $post)
  {
    if (!isset($post['photo']) ||
        empty($post['photo']) ||
        $this->is_valid_string_format($post['photo']) === FALSE)
      return TRUE;
    return FALSE;
  }

  public function check_montage_filter(array $post)
  {
    if (!isset($post['filter']) ||
        empty($post['filter']) ||
        $this->is_valid_url_format($post['filter']) === FALSE)
      return TRUE;
    return FALSE;
  }

  public function check_montage_width(array $post)
  {
    if (!isset($post['width']) ||
        empty($post['width']) ||
        $this->is_valid_int_format($post['width']) === FALSE)
      return TRUE;
    return FALSE;
  }

  public function check_montage_height(array $post)
  {
    if (!isset($post['height']) ||
        empty($post['height']) ||
        $this->is_valid_int_format($post['height']) === FALSE)
      return TRUE;
    return FALSE;
  }
}
