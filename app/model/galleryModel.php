<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author Pin Hung, LEE
 */
class galleryModel extends Model {

    //This function insert album Records into the photo_album table with $userID, $albumName
    public function createAlbum($userID, $albumName) {
        $sql = "INSERT INTO photo_album (user_id,album_name,create_date)
                VALUES ('$userID','$albumName',TIMESTAMP(CURRENT_TIMESTAMP))";

        $result = $this->_db->exec($sql);
        return $result;
    }

    //This function insert image Records to photo table with $albumID, $photoName, $photo_url, $thumbnail_url
    public function uploadImage($albumID, $photoName, $photo_url, $thumbnail_url) {
        $sql = "INSERT INTO photo (album_id, photo_name, upload_date, photo_url, thumbnail_url)
                VALUES ('$albumID','$photoName',TIMESTAMP(CURRENT_TIMESTAMP),'$photo_url','$thumbnail_url')";

        $result = $this->_db->exec($sql);
        return $result;
    }

    //This function insert Records into the photo_comment table with $photoID, $userID, $comment
    public function submitComment($photoID, $userID, $comment) {
        $sql = "INSERT INTO photo_comment (photo_id,user_id,comment,submit_date)
                VALUES ('$photoID','$userID','$comment',TIMESTAMP(CURRENT_TIMESTAMP))";

        $result = $this->_db->exec($sql);
        return $result;
    }

    //This function get all album(s) from photo_album table
    public function getAlbum() {
        $sql = "SELECT photo_album.album_id, photo_album.album_name, user.user_id, user.first_name, user.last_name
                FROM photo_album
                INNER JOIN user
                ON photo_album.user_id = user.user_id";

        $result = $this->_db->query($sql);

        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return false;
    }

    //This function get all album name(s) from photo_album table based on $albumID
    public function getAlbumName($albumID) {
        $sql = "SELECT photo_album.album_name, photo_album.album_id, photo_album.user_id, user.avatar, user.first_name, user.last_name
                FROM photo_album
                INNER JOIN user
                ON user.user_id = photo_album.user_id
                WHERE album_id = $albumID";

        $result = $this->_db->query($sql);

        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return false;
    }

    //This function get all thumbnail(s) from photo table based on $albumID
    public function getThumbnail($albumID) {
        $sql = "SELECT photo.photo_id, photo.photo_name, photo.photo_url, photo.thumbnail_url, photo_album.album_name, photo_album.user_id
                FROM photo
                INNER JOIN photo_album
                ON photo.album_id = photo_album.album_id
                WHERE photo.album_id = $albumID";

        $result = $this->_db->query($sql);

        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return false;
    }

    //This function get all photo(s) from photo table based on $photoID
    public function getPhoto($photoID) {
        $sql = "SELECT photo.photo_url, photo.upload_date
                FROM photo
                WHERE photo.photo_id = '$photoID'";

        $result = $this->_db->query($sql);

        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return false;
    }

    //This function get all comment(s) from photo_comment table based on $photoID
    public function getComment($photoID) {
        $sql = "SELECT user.user_id, user.first_name, user.last_name, user.avatar, photo_comment.comment_id, photo_comment.comment, photo_comment.submit_date
                FROM photo_comment
                INNER JOIN user
                ON photo_comment.user_id = user.user_id
                WHERE photo_comment.photo_id = '$photoID'
                ORDER BY photo_comment.comment_id";

        $result = $this->_db->query($sql);

        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return false;
    }

    //This function delete album from photo_album table based on $albumID
    public function deleteAlbum($albumID) {
        $sql = "DELETE FROM photo_album WHERE album_id = '$albumID'";

        $result = $this->_db->exec($sql);
        return $result;
    }

    //This function delete image from photo table based on $imageID
    public function deleteImage($imageID) {
        $sql = "DELETE FROM photo WHERE photo_id = '$imageID'";

        $result = $this->_db->exec($sql);
        return $result;
    }

    //This function delete comment from photo_commet table based on $commentID
    public function deleteComment($commentID) {
        $sql = "DELETE FROM photo_comment WHERE comment_id = '$commentID'";

        $result = $this->_db->exec($sql);
        return $result;
    }

}

?>
