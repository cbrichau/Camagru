<?php
class MImageMng extends M_Manager
{
  /* *********************************************************** *\
      SELECT, DELETE
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

  public function delete_montage($id_image)
  {
    $img_path = Config::IMAGES_PATH.'uploads/'.$id_user.'-*.png';
    if (file_exists($img_path))
      unlink($img_path);
    header('Location: '.Config::ROOT.'index.php?cat=montage');
  }

  public function delete_uploaded_image($id_user)
  {
    $img_paths = glob(Config::IMAGES_PATH.'uploads/*-'.$id_user.'.png');
    foreach ($img_paths as $img_path)
      unlink($img_path);
  }

  /* *********************************************************** *\
      PAGINATION
      Creates an array for the pagination values
      (current page, last page, etc.)
  \* *********************************************************** */

  public function get_pagination_values(array $montages, array $get)
  {
    $nb_montages = count($montages);
    $montages_per_page = 6;
    $pagination['nb_pages'] = ceil($nb_montages / $montages_per_page);
    $pagination['nb_pages'] = ($pagination['nb_pages'] == 0) ? 1 : $pagination['nb_pages'];

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
      - Counts the likes for a given image.
      - Adds a like to a given image.
      - Initialises the likes to zero on creation of a new image.
  \* *********************************************************** */

  public function count_likes_per_image()
  {
    $sql = 'SELECT id_image, SUM(nb_likes) as nb_likes
            FROM likes
            GROUP BY id_image';
    $query = $this->_db->prepare($sql);
    $query->execute();

    $likes = array();
    while ($r = $query->fetch())
      $likes[$r['id_image']] = $r['nb_likes'];
    return $likes;
  }

  public function add_like($id_image)
  {
    $sql = 'UPDATE likes
            SET nb_likes = nb_likes + 1
            WHERE id_image = :id_image';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_image', $id_image, PDO::PARAM_STR);
    $query->execute();
  }

  private function initialise_likes($id_image)
  {
    $sql = 'INSERT INTO likes
           (id_image, nb_likes)
           VALUES
           (:id_image, 0)';
    $query = $this->_db->prepare($sql);
    $query->bindValue(':id_image', $id_image, PDO::PARAM_STR);
    $query->execute();
  }

  /* *********************************************************** *\
      CREATE_MONTAGE
      Creates the montage from the photo and filter.
  \* *********************************************************** */

  public function create_montage($id_user, $photo, $filter, $width, $height)
  {
    list($filter_real_width, $filter_real_height) = getimagesize($filter);

    if (strpos($photo, 'data:image/png;base64') !== false)
      $photo = imagecreatefromstring(base64_decode(str_replace(' ', '+', str_replace('data:image/png;base64,', '', $photo))));
    else
      $photo = imagecreatefrompng($photo);
    $filter = imagecreatefrompng($filter);

    imagecopyresized($photo, $filter, 0, 0, 0, 0, $width, $height, $filter_real_width, $filter_real_height);

    $id_image = time().'-'.$id_user;
    imagepng($photo, Config::IMAGES_PATH.'montages/'.$id_image.'.png');
    $this->initialise_likes($id_image);

    imagedestroy($photo);
    imagedestroy($filter);
  }

  /* *********************************************************** *\
      CHECK/SAVE_UPLOADED_PHOTO
      Checks the uploaded photo is valid, or returns error = TRUE.
      Saves the uploaded photo in images/uploads.
  \* *********************************************************** */

  public function check_uploaded_photo(array $files)
  {
    if (empty($files['photo']) ||
        !isset($files['photo']['error']) ||
        !isset($files['photo']['name']) ||
        !isset($files['photo']['type']) ||
        !isset($files['photo']['size']) ||
        $files['photo']['error'] != 0 ||
        $this->is_valid_string_format($files['photo']['name']) === FALSE ||
        $this->is_valid_string_format($files['photo']['tmp_name']) === FALSE)
      return TRUE;

    list($name, $ext) = explode('.', $files['photo']['name']);
    $type = $files['photo']['type'];
    $size = $files['photo']['size'];

    $whitelist_ext = array('jpeg','jpg','png','gif');
    $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');
    $max_size = 1000000;

    if (!in_array($ext, $whitelist_ext) ||
        !in_array($type, $whitelist_type) ||
        $size > $max_size)
      return TRUE;

    return FALSE;
  }

  public function save_uploaded_photo(array $files, $id_user)
  {
    $photo = imagecreatefromstring(file_get_contents($files['photo']['tmp_name']));
    $id_image = time().'-'.$id_user;
    $img_path = Config::IMAGES_PATH.'uploads/'.$id_image.'.png';
    imagepng($photo, $img_path);
    imagedestroy($photo);
    return $img_path;
  }

  /* *********************************************************** *\
      CHECK_MONTAGE_values
      Checks the input for the creation of a montage is valid,
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
        $this->is_valid_float_format($post['width']) === FALSE)
      return TRUE;
    return FALSE;
  }

  public function check_montage_height(array $post)
  {
    if (!isset($post['height']) ||
        empty($post['height']) ||
        $this->is_valid_float_format($post['height']) === FALSE)
      return TRUE;
    return FALSE;
  }
}
