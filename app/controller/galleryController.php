<?php

class galleryController extends AppController {

    // Set 'Layout' as 'backend_default' layout
    public $layout = 'backend_default';
    protected $_requresLogin = true;
    private $user;

    public function __construct($action = null) {
        parent::__construct($action);

        $this->user = $this->login->getUser();
        $this->set('user', $this->user);
        $this->addStyle('backend/gallery.css');
    }

    public function index() {
        // Get album from database. Return to template.
        $result = $this->_model->getAlbum();
        $this->set('albums', $result);

        /*
         * This code handle create new album from template
         * Directory will be created once photo has been uploaded
         * Directory path will be save to database
         */
        if ($this->isPostAction()) {
            // SET path
            $thisdir = getcwd();
            $gallery = $thisdir . "/app/web/images/gallery/";
            // Get albumname from template through 'POST' method
            $albumName = $_POST['albumname'];
            $albumName = str_replace("'", "\'", $albumName);
            $userID = $this->user->user_id;
            // Check IF the photo already exists
            if (file_exists($gallery . $albumName)) {
                echo $albumName . " already exists.";
            } else {
                try {
                    // Store image's information into database
                    $result = $this->_model->createAlbum($userID, $albumName);
                    // Check IF error occur
                    if ($result < 1)
                        return "There was an error inserting Records in database";
                    else
                    // Refresh page
                        return header("location: " . $_SERVER['REQUEST_URI']);
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        } else {
            $albumID = "";
            if (isset($_GET['deleteAlbum'])) {
                $albumID = $_GET['deleteAlbum'];
            }
            if ($albumID != "") {
                try {
                    $this->deleteThisAlbum($albumID);
                    return header("location: " . 'index.php?c=gallery&a=index');
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            } else {
                return "Please select the album that you wish to delete";
            }
        }
    }

    public function album() {
        // Get 'albumID' from url 'album='
        $albumID = $_GET['album'];
        //getThumbnail by $albumID from database. Return $result to template.
        $result = $this->_model->getThumbnail($albumID);
        $this->set('thumbnails', $result);
        //getAlbumName by $albumID from database. Return $result to template.
        $result = $this->_model->getAlbumName($albumID);
        $this->set('albums', $result);
        /*
         * This code handle image uploading from template.
         * Image will be stored in pre-set directory.
         * Image path will be stored in database.
         */
        if ($this->isPostAction()) {
            // SET path
            $max_width = 120;
            $max_x = 1024;
            $gallery = getcwd().'/app/web/images/gallery/';
            $album_path = $albumID . '/';
            $image_directory = $gallery . 'photos/' . $album_path;
            $thumb_directory = $gallery . 'thumbs/' . $album_path;
            $photoName = strtolower($_FILES['photo']['name']);
            $photoName = str_replace(" ", "_", $photoName);
            $photo_url = IMAGES_GALLERY . 'photos/' . $album_path . $photoName;
            $thumbnail_url = IMAGES_GALLERY . 'thumbs/' . $album_path . $photoName;

            if ($photoName == "") {
                $result = "Please select a photo.";
                return $result;
            } elseif (file_exists($image_directory . $photoName)) {
                echo $photoName . " already exists.";
            } else {
                // Check IF image type match the format [gif, jpg/jpeg, png]
                if ((($_FILES["photo"]["type"] == "image/gif")
                        || ($_FILES["photo"]["type"] == "image/jpg")
                        || ($_FILES["photo"]["type"] == "image/jpeg")
                        || ($_FILES["photo"]["type"] == "image/pjpeg")
                        || ($_FILES["photo"]["type"] == "image/png"))
                        && ($_FILES["photo"]["size"] < 15000000)) {
                    // SET path
                    $source = $_FILES['photo']['tmp_name'];
                    $target = $image_directory . $photoName;

                    if (preg_match('/[.]jpg$/', $photoName)) {
                        $image = imagecreatefromjpeg($source);
                    } else if (preg_match('/[.]gif$/', $photoName)) {
                        $image = imagecreatefromgif($source);
                    } else if (preg_match('/[.]png$/', $photoName)) {
                        $image = imagecreatefrompng($source);
                    }
                    $old_x = imagesx($image);
                    $old_y = imagesy($image);
                    $new_x = $max_x;
                    $new_y = floor($old_y * ($max_x / $old_x));
                    $nm = imagecreatetruecolor($new_x, $new_y);
                    imagecopyresized($nm, $image, 0, 0, 0, 0, $new_x, $new_y, $old_x, $old_y);

                    // Check IF photo already exists
                    if (!file_exists($image_directory)) {
                        if (mkdir($image_directory)) {
                            // Move uploaded file [tmp_file] to Gallery Directory
                            //move_uploaded_file($source, $target)
                            if (preg_match('/[.]jpg$/', $photoName)) {
                                imagejpeg($nm, $target, 100);
                            } elseif (preg_match('/[.]gif$/', $photoName)) {
                                imagegif($nm, $target);
                            } elseif (preg_match('/[.]png$/', $photoName)) {
                                imagepng($nm, $target);
                            }
                            // Calling a private function : 'createThumbnail' function
                            $this->createThumbnail($photoName, $target, $max_width, $thumb_directory);
                            // Store the image path in the database.
                            $result = $this->_model->uploadImage($albumID, $photoName, $photo_url, $thumbnail_url);
                            // Refresh page
                            return header("location: " . $_SERVER['REQUEST_URI']);
                        } else {
                            return ("Photo album has not been created!");
                            // Refresh page
                            return header("location: " . $_SERVER['REQUEST_URI']);
                        }
                    } else {
                        // Move uploaded file [tmp_file] to Gallery Directory
                        if (preg_match('/[.]jpg$/', $photoName)) {
;
                            imagejpeg($nm, $target, 100);
                        } elseif (preg_match('/[.]gif$/', $photoName)) {
                            imagegif($nm, $target);
                        } elseif (preg_match('/[.]png$/', $photoName)) {
                            imagepng($nm, $target);
                        }
                        // Calling a private function : 'createThumbnail' function
                        $this->createThumbnail($photoName, $target, $max_width, $thumb_directory);
                        // Store the image path in the database.
                        $result = $this->_model->uploadImage($albumID, $photoName, $photo_url, $thumbnail_url);
                        // Refresh page
                        return header("location: " . $_SERVER['REQUEST_URI']);
                    }
                } else {
                    echo ("Invalid file!");
                }
            }
        }
    }

    public function photo() {
        // Get photo based on Photo ID. Return to template.
        $photoID = $_GET['photo'];
        // Get photo from database. Return to template.
        $result = $this->_model->getPhoto($photoID);
        $this->set('photos', $result);
        // Get Album Name based on Album ID. Return to template.
        $albumID = $_GET['album'];
        // Get Album Name from Database. Return to template.
        $result = $this->_model->getAlbumName($albumID);
        $this->set('albums', $result);
        // Get comments based on Photo ID. Return to template.
        $result = $this->_model->getComment($photoID);
        $this->set('comments', $result);
        /*
         * This code handle comment submitted from template.
         * Photo comment will be stored in database.
         */
        if ($this->isPostAction()) {
            $userID = $this->user->user_id;
            // Get comment from template through 'POST' method
            $comment = $_POST['comment'];
            // Get photo from URL
            $photoID = $_GET['photo'];
            if (!$comment) {
                return header("location: " . $_SERVER['REQUEST_URI']);
            } else {
                try {
                    $comment = str_replace("'", "\'", $comment);
                    // Submit a comment to the database based on Photo ID and User ID.
                    $result = $this->_model->submitComment($photoID, $userID, $comment);

                    if ($result < 1)
                        return "There was an error inserting Records in database";
                    else
                    // Refresh page
                        return header("location: " . $_SERVER['REQUEST_URI']);
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        }
    }

    public function deleteAlbum() {
        // Get album from database. Return to template.
        $result = $this->_model->getAlbum();
        $this->set('albums', $result);
        /*
         * This code handle delete album from template.
         * Image will be deleted from directory.
         * Image path will be deleted from database.
         */
        if ($this->isPostAction()) {
            // Get deleteAlbum from template through 'POST' method
            $albumID = $_POST['deleteAlbum'];
            // Set path

            if ($albumID != 'NULL') {
                try {
                    // Delete an album based on Album ID.
                    //$result = $this->_model->deleteAlbum($albumID);
                    $this->deleteThisAlbum($albumID);

                    if ($result < 1)
                        return "There was an error inserting Records in database";
                    else
                        return header("location: " . $_SERVER['REQUEST_URI']);
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            } else {
                return "Please select the album that you wish to delete";
            }
        }
    }

    public function deleteImage() {
        // Delete photo based on photo_id
        $albumID = $_GET['album'];
        $imageID = $_GET['image'];
        $photoName = $_GET['photo'];
        // Set path
        $gallery = getcwd().'/app/web/images/gallery/';
        $image_dir = $gallery . 'photos' . '/' . $albumID . '/' . $photoName;
        $thumb_dir = $gallery . 'thumbs' . '/' . $albumID . '/' . $photoName;
        // Check IF the photo is exists in gallery directory
        if (file_exists($image_dir)) {
            // Unlink image
            unlink($image_dir);
            unlink($thumb_dir);
            // Remove image
            rmdir($image_dir);
            rmdir($thumb_dir);
            // Delete image from database based from imageID
            $result = $this->_model->deleteImage($imageID);
        }
        // Return to album page based on 'albumID'
        return header("location: " . 'index.php?c=gallery&a=album&album=' . $albumID);
    }

    public function deleteComment() {
        // Delete a comment from database based on comment_id
        $albumID = $_GET['album'];
        $photoID = $_GET['photo'];
        $commentID = $_GET['comment'];
        // Delete comment based on 'commentID'
        $result = $this->_model->deleteComment($commentID);
        // Return to photo page based on 'albumID' and 'photoID'
        return header("location: " . 'index.php?c=gallery&a=photo&album=' . $albumID . '&photo=' . $photoID);
    }

    /* ----- PRIVATE FUNCTION ----- */
    /*
     * This function create thumbnail and store thumbnail in directory named "thumbs".
     * This function is specially created for and used by Photo Gallery
     */

    private function createThumbnail($photoName, $target, $max_width, $thumb_directory) {
        //Differenciate image type before create thumbnail
        if (preg_match('/[.]jpg$/', $photoName)) {
            $im = imagecreatefromjpeg($target);
        } else if (preg_match('/[.]gif$/', $photoName)) {
            $im = imagecreatefromgif($target);
        } else if (preg_match('/[.]png$/', $photoName)) {
            $im = imagecreatefrompng($target);
        }
        //Create image thumbnail.
        $old_x = imagesx($im);
        $old_y = imagesy($im);
        $new_x = $max_width;
        $new_y = floor($old_y * ($max_width / $old_x));
        $nm = imagecreatetruecolor($new_x, $new_y);
        imagecopyresized($nm, $im, 0, 0, 0, 0, $new_x, $new_y, $old_x, $old_y);
        //Save image thumbnail to pre-setted directory.
        if (!file_exists($thumb_directory)) {
            //New directory will be created based on album name, if directory is not exist.
            if (mkdir($thumb_directory)) {
                imagejpeg($nm, $thumb_directory . $photoName);
            } else {
                return ("Thumbnail has not been created.");
            }
        } else {
            imagejpeg($nm, $thumb_directory . $photoName);
        }
    }

    private function deleteThisAlbum($albumID) {
        $thisdir = getcwd().'/app/web/images/gallery/';
        $image_dir = $thisdir . 'photos/' . $albumID;
        $thumb_dir = $thisdir . 'thumbs/' . $albumID;
        if (is_dir($image_dir) && is_dir($thumb_dir)) {
            foreach (scandir($image_dir) as $item) {
                if ($item == '.' || $item == '..')
                    continue;
                // Unlink directory
                unlink($image_dir . DIRECTORY_SEPARATOR . $item);
            }
            // Remove directory
            rmdir($image_dir);

            foreach (scandir($thumb_dir) as $item) {
                if ($item == '.' || $item == '..')
                    continue;
                // Unlink directory
                unlink($thumb_dir . DIRECTORY_SEPARATOR . $item);
            }
            // Remove directory
            rmdir($thumb_dir);
            // Delete Album from database
            $result = $this->_model->deleteAlbum($albumID);
        } else {
            // Delete Album from database
            $result = $this->_model->deleteAlbum($albumID);
        }
    }

}

?>