<?php

/**
 * Description of Moderator
 *
 * @author Antonio Israel Ortuno Peidro
 */
class ModeratorController extends Controller
{
    private $replaceChar = '*';
    
    public function __construct($action = null) {
        parent::__construct($action);
    }
    
    /**
     * Given a string, checks it looking for forbidden words. If any is found, it'll be replaced by its length $replaceChar (*)
     */
    public function checkString($string)
    {
        $forbiddenList = $this->_model->getWords();
        
        foreach ($forbiddenList as $forbidden)
            $string = str_replace($forbidden->word, str_repeat($this->replaceChar, strlen($forbidden->word)), $string);
        
        return $string;
    }
    
    /**
     * Returns the words list
     */
    public function getWords()
    {
        return $this->_model->getWords();
    }
    
    /**
     * Adds a new word to the list
     */
    public function addWord($word)
    {
        $this->_model->addWord($word);
    }
    
    /**
     * Deletes a word from the list
     */
    public function deleteWord($wordId)
    {
        $this->_model->deleteWord($wordId);
    }
}

?>
