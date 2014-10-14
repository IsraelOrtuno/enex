<?php

class bulletinBoardController extends AppController
{

    public $layout = 'backend_default';
    private $moderator;
    protected $_requresLogin = true;
    
    public function __construct($action = null) {
        parent::__construct($action);
        
        $this->moderator = new ModeratorController();
        $this->user = $this->login->getUser();
        $this->set('user', $this->user);
        $this->addStyle('backend/bulletin/default.css');
    }
    
    public function index()
    {
        $topics = $this->_model->getTopicList();       
        $this->set('topics', $topics);
        
        $this->permissions();
    }
    
    public function showTopic()
    {
        if (isset($_GET['topic_id']))
        {
            $topic      = $this->_model->getTopic($_GET['topic_id']);
            $messages   = $this->_model->getTopicMessages($_GET['topic_id']);

            $this->set('topic', $topic);
            $this->set('messages', $messages);
        }
        else
        {
            $this->error('Topic id not specified as GET parameter.');
        }
    }
    
    public function createTopic()
    {
        if ($this->isPostAction())
        {
            if (empty($_POST['title']) || empty($_POST['message']))                                       //// !!! TO DO
            {
                if (isset($_POST['title']))
                    $this->set('title', $_POST['title']);
                if (isset($_POST['message']))
                    $this->set('message', $_POST['message']);
                $this->set('error', 'All fields are required.');
            }
            else
                $this->_model->createTopic($this->moderator->checkString($_POST['title']), $this->moderator->checkString($_POST['message']), $this->user->user_id);
        }
        $this->index();
        $this->template = 'index';
    }
    
    public function deleteTopic()
    {
        if (isset($_GET['topic_id']))
        {
            $topic = $this->_model->getTopic($_GET['topic_id']);
            
            if ($topic->user_id == $this->user->user_id || ($this->permissions() == 'some' && $topic->user_type_id == 3) || ($this->permissions() == 'all' && ($topic->user_type_id == 2 || $topic->user_type_id == 3)))
            {
                $this->_model->deleteTopic($_GET['topic_id']);
                $this->gotoAction('index');
            }
            else
                $this->error('You do not have enough permissions to delete a topic.');
        }
        else
            $this->error('Topic id not specified as GET parameter.');
    }
    
    public function modifyTopic()
    {
        if (!isset($_GET['topic_id']))
            $this->error('Topic id not specified as GET parameter.');
        else
        {
            $topic = $this->_model->getTopic($_GET['topic_id']);
            
            if ($topic->user_id == $this->user->user_id || ($this->permissions() == 'some' && $topic->user_type_id == 3) || ($this->permissions() == 'all' && ($topic->user_type_id == 2 || $topic->user_type_id == 3)))
            {
                if ($this->isPostAction())
                {
                    if (empty($_POST['title']) || empty($_POST['message']))                                       //// !!! TO DO
                    {
                        if (isset($_POST['title']))
                            $this->set('title', $_POST['title']);
                        if (isset($_POST['message']))
                            $this->set('message', $_POST['message']);
                        $this->set('error', 'All fields are required.');
                    }
                    else
                    {
                        $topic = $this->_model->getTopic($_GET['topic_id']);
                        $this->_model->updateTopic($_GET['topic_id'], $this->moderator->checkString($_POST['title']), $this->moderator->checkString($_POST['message']));
                        $this->gotoAction('index');
                    }
                }
            }
            else
                $this->error('You do not have enough permissions to modify a topic.');
            
            $this->set('topic', $topic);
        }
    }
    
    public function postMessage()
    {
        if (!isset($_GET['topic_id']))
            $this->error('Topic id not specified as GET parameter.');
        else
        {
            if ($this->isPostAction())
            {
                if (empty($_POST['message']))    
                    $this->set('error', 'All fields are required.');
                else
                    $this->_model->createMessage($_GET['topic_id'], $this->moderator->checkString($_POST['message']), $this->user->user_id);
            }
            $this->gotoAction("showTopic&topic_id={$_GET['topic_id']}");
        }
    }
    
    public function deleteMessage()
    {
        if (isset($_GET['message_id']))
        {
            $message = $this->_model->getMessage($_GET['message_id']);
            
            if ($message->user_id == $this->user->user_id || ($this->permissions() == 'some' && $message->user_type_id == 3) || ($this->permissions() == 'all' && ($message->user_type_id == 2 || $message->user_type_id == 3)))
            {
                $this->_model->deleteMessage($_GET['message_id']);
                $this->gotoAction("showTopic&topic_id=$message->topic_id");
            }
            else
                $this->error('You do not have enough permissions to delete a message.');
        }
        else
            $this->error('Message id not specified as GET parameter.');
    }
    
    public function modifyMessage()
    {
        if (!isset($_GET['message_id']))
            $this->error('Topic id not specified as GET parameter.');
        else
        {
            $message = $this->_model->getMessage($_GET['message_id']);
            
            if ($message->user_id == $this->user->user_id || ($this->permissions() == 'some' && $message->user_type_id == 3) || ($this->permissions() == 'all' && ($message->user_type_id == 2 || $message->user_type_id == 3)))
            {
                if ($this->isPostAction())
                {
                    if (empty($_POST['message']))                                       /// TO DO!
                        $this->set('error', 'All fields are required.');
                    else
                    {
                        $topic = $this->_model->getTopic($_GET['message_id']);
                        $this->_model->updateMessage($_GET['message_id'], $this->moderator->checkString($_POST['message']));
                        $this->gotoAction("showTopic&topic_id=$message->topic_id");
                    }
                }
            }
            else
                $this->error('You do not have enough permissions to modify a message.');
            
            $this->set('message', $message);
        }
    }
    
    public function manageForbiddenWords()
    {
        if (!$this->userAdmin())
            $this->error('You do not have enough permissions to access this area');
        else
        {
            $words = $this->moderator->getWords();
            $this->set('words', $words);
        }
    }
    
    public function addForbiddenWord()
    {
        $this->template = 'manageForbiddenWords';
        
        if (!$this->userAdmin())
            $this->error('You do not have enough permissions to access this area');
        else
        {
            if ($this->isPostAction())
            {
                if (!isset($_POST['word']))
                    $this->set('error', 'Word field required.');
                else
                {
                    $this->moderator->addWord($_POST['word']);
                    $this->gotoAction('manageForbiddenWords');
                }
            }
            else
                $this->error('Unespected error, try again.');
        }
    }
    
    public function deleteForbiddenWord()
    {
        if (!$this->userAdmin())
            $this->error('You do not have enough permissions to access this area');
        else
        {
            if (isset($_GET['word_id']))
            {
                $this->moderator->deleteWord($_GET['word_id']);
                $this->gotoAction('manageForbiddenWords');
            }
            else
                $this->error('Word id not specified as GET parameter.');
        }
    }
    
    public function policy()
    {
        // emprty
    }
    
    private function error($message)
    {
        $this->template = 'error';
        $this->set('error', $message);
    }
    
    private function permissions()
    {
        if ($this->userAdmin())
        {
            $this->set('actions', 'all');
            return 'all';    
        }
        elseif ($this->userManager()) 
        {
            $this->set('actions', 'some'); 
            return 'some';
        }
        
        $this->set('actions', 'own');
        return 'own';
    }
    
    private function userAdmin()
    {
        return $this->user->user_type_id==1?true:false;
    }
    
    private function userManager()
    {
        return $this->user->user_type_id=2?true:false;
    }
}

?>
