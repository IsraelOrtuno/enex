<?php

/**
 * Description of ModeratorModel
 *
 * @author Antonio Israel Ortuno Peidro
 */
class ModeratorModel extends Model{
    
    /**
     * Returns forbidden words list
     */
    public function getWords()
    {
        $sql = "SELECT * FROM forbidden_word
                ORDER BY forbidden_word_id DESC";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return array();
    }
    
    /**
     * Adds a new word to the list
     */
    public function addWord($word)
    {
        $word = addslashes($word);
        
        $sql = "INSERT INTO forbidden_word (word)
                VALUES ('$word')";
        $this->_db->query($sql);
    }
    
    /**
     * Deletes a word from the list
     */
    public function deleteWord($wordId)
    {
        $sql = "DELETE FROM forbidden_word
                WHERE forbidden_word_id=$wordId";
        $this->_db->query($sql);
    }
}

?>
