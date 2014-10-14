<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of imController
 *
 * @author Segun
 */
class imController extends AppController {

//put your code here

    public $layout = 'backend_default';
    private $user;
    protected $_requresLogin = true;
    private $offlineMessage = 1;
    private $onlineMessage = 2;

    public function __construct($action = null) {
        parent::__construct($action);
        $this->user = $this->login->getUser();
        
        
        $this->set('user', $this->user);
    }

    //This function loads the home page
    public function index() {
        

        $user_id = $this->user->user_id;
        $user_type_id = $this->user->user_type_id;
        $result = "";
        $messageFilter = 0;
        $successmsg = 0;
        if (isset($_GET['sucessmsg'])) {//get the success message set by the user and display it on the screen
            $successmsg = $this->sanitize($_GET['sucessmsg']);
            if ($successmsg == 1) {
                $this->set('success', 'Message have been sent successfully!!!');
            } else if ($successmsg == 2) {
                $this->set('success', 'Message(s) have been deleted successfully!!!');
            } else if ($successmsg == 3) {
                $this->set('success', 'Message(s) have been Mark as Unread successfully!!!');
            } else if ($successmsg == 4) {
                $this->set('success', 'Message(s) have been Reported to the administrator!!!');
            }
        } else {
            $this->set('success', '');
        }
        if ($this->isPostAction()) {
            $messageFilter = $this->sanitize($_POST['messageFilter']);
            if ($messageFilter == 0) {//Get the inbox dropdown selected by the user,calls the appropriate function and displays the message on the screen
                $result = $this->_model->fetchInboxMessagesByUserId($user_id);
                $this->set('optionSelected', 0);
                $this->set('error', 'Sorry there are no new Messages Available');
            } else if ($messageFilter == 1) {//Get the sent dropdown selected by the user,calls the appropriate function and displays the message on the screen
                $result = $this->_model->fetchSentMessagesByUserId($user_id);
                $this->set('optionSelected', 1);
                $this->set('error', 'Sorry there are no sent Messages Available');
            } else if ($messageFilter == 2) {//Get the im conversation dropdown selected by the user,calls the appropriate function and displays the message on the screen
                $result = $this->_model->fetchOnlineMessagesByUserId($user_id);
                $this->set('optionSelected', 2);
                $this->set('error', 'Sorry there are no online Messages Available');
            }
        } else {
            $result = $this->_model->fetchInboxMessagesByUserId($user_id);
            $this->set('optionSelected', 0);
            $this->set('error', 'Sorry there are no new Messages Available');
        }
        $this->set('messages', $result);
        $this->set('user_type', $user_type_id);
    }

    //This function creates a new message for the user
    public function newMessage() {
        $this->layout = 'empty';
        if ($this->isPostAction()) {
            $saveButton = "";
            $sendButton = "";
            $sender = $this->user->user_id;
            $to = 0;
            $messageType = "1";
            $message = "";
            $message = $this->sanitize($_POST['messageContent']); //Get the message field from the message box
            $sendTo = $this->sanitize($_POST['sentTo']); //Get the email address of the user wants to sent message to
            $to = $this->_model->getUserIdByEmail($sendTo);
            if (!$to) {
                echo "Sorry email address entered is invalid";
            } else {
                echo "";
                $to = $to->user_id;
                $result = $this->_model->createNewMessage($sender, $to, $this->offlineMessage, $message);
            }
        }
    }

    //The function allows user to perform various actions
    public function actionMessage() {
        $user_id = $this->user->user_id;
        $user_type_id = "2";

        $messageFilter = $this->sanitize($_GET['m']);
        if ($messageFilter == 0) {//Get the inbox dropdown selected by the user,calls the appropriate function and displays the message on the screen
            $result = $this->_model->fetchInboxMessagesByUserId($user_id);
            $this->set('optionSelected', 0);
            $this->set('error', 'Sorry there are no new Messages Available');
        } else if ($messageFilter == 1) {//Get the sent dropdown selected by the user,calls the appropriate function and displays the message on the screen
            $result = $this->_model->fetchSentMessagesByUserId($user_id);
            $this->set('optionSelected', 1);
            $this->set('error', 'Sorry there are no sent Messages Available');
        } else if ($messageFilter == 2) {//Get the im conversration dropdown selected by the user,calls the appropriate function and displays the message on the screen
            $result = $this->_model->fetchOnlineMessagesByUserId($user_id);
            $this->set('optionSelected', 2);
            $this->set('error', 'Sorry there are no online Messages Available');
        }

        $this->set('messages', $result);
        $this->set('user_type', $user_type_id);

        $view = "N";

        if (isset($_GET['v'])) {
            $view = $this->sanitize($_GET['v']);
        }
        $this->set('view', $view);
    }

    //This function allow user to select multiple message and perform action on the message
    public function actionMethod() {

        if ($this->isPostAction()) {
            $user_id = $this->user->user_id;
            $deleteButton = "";
            $markButton = "";
            $reportButton = "";
            $viewButton = "";
            $messages = $_POST['message_id'];

            if (isset($_POST['btnDelete'])) {
                $deleteButton = $_POST['btnDelete'];
                if ($deleteButton == "Delete") {//checks if the button selected by the user is delete and then delete multiple messages 
                    if (count($messages) < 0) {
                        $this->set('result', 'No messages were selected...');
                    } else {
                        for ($i = 0; $i < count($messages); $i++) {
                            if ($messages[$i] != "") {
                                $result = $this->_model->deleteMessages($messages[$i]);
                            }
                        }
                        return header('location:' . 'index.php?c=im&a=index&sucessmsg=2');
                    }
                }
            } else if (isset($_POST['btnMark'])) {
                $markButton = $_POST['btnMark'];
                if ($markButton == "Mark as Unread") {//checks if the button selected by the user is mark as unread and then mark multiple messages as unread
                    if (count($messages) < 0) {
                        $this->set('result', 'No messages were selected...');
                    } else {
                        for ($i = 0; $i < count($messages); $i++) {
                            if ($messages[$i] != "") {
                                $result = $this->_model->updatetoMarkedMessage($messages[$i], 0);
                            }
                        }
                        return header('location:' . 'index.php?c=im&a=index&sucessmsg=3');
                    }
                }
            } else if (isset($_POST['btnReport'])) {
                $reportButton = $_POST['btnReport'];
                if ($reportButton == "Report") {//checks if the button selected by the user is report and then report multiple messages
                    if (count($messages) < 0) {
                        $this->set('result', 'No messages were selected...');
                    } else {
                        for ($i = 0; $i < count($messages); $i++) {
                            if ($messages[$i] != "") {
                                $this->_model->updateReportMessage($messages[$i]);
                                $id = $this->_model->getUserMessageByMessageId($messages[$i])->id;
                                $result = $this->_model->reportMessage($id);
                            }
                        }
                        return header('location:' . 'index.php?c=im&a=index&sucessmsg=4');
                    }
                }
            } else if (isset($_POST['btnView'])) {
                
            } else {
                $this->set('result', 'Error no action selected...');
            }
        }
    }

    //This function allows user to only perform action on one message
    public function performSingleAction() {
        $user_id = $this->user->user_id;
        $view = $this->user->user_type_id;
        $message_id = 0;

        if (isset($_GET['v'])) {
            $view = $this->sanitize($_GET['v']);
        }

        if (isset($_GET['m_id'])) {
            $message_id = $this->sanitize($_GET['m_id']);
        }

        if ($view != "" && $view == "D") {//checks if the action selected by the user is delete and delete message
            $result = $this->_model->deleteMessages($message_id);
            return header('location:' . 'index.php?c=im&a=index&sucessmsg=2');
        } else if ($view != "" && $view == "R") {//checks if the action selected by the user is report and report message
            $report = $this->_model->updateReportMessage($message_id);

            $id = $this->_model->getUserMessageByMessageId($message_id)->id;
            $result = $this->_model->reportMessage($id);
            return header('location:' . 'index.php?c=im&a=index&sucessmsg=4');
        } else if ($view != "" && $view == "M") {//checks if the action selected by the user is mark as unread and mark message as unread
            $result = $this->_model->updatetoMarkedMessage($message_id, 0);
            return header('location:' . 'index.php?c=im&a=index&sucessmsg=3');
        }
    }

    //This function displays the view for the message page
    public function viewMessage() {
        $messageid = $this->sanitize($_GET['mid']);
        $messageFilter = $this->sanitize($_GET['m']);
        if ($messageFilter == 0) {//Get the mark as unread message selected by the user,calls the appropriate function and displays the message on the screen
            $this->_model->updatetoMarkedMessage($messageid, 1);
            $result = $this->_model->getInboxMessageById($messageid);
            $this->set('result', $result);
            $this->set('view', $messageFilter);
        } else if ($messageFilter == 1) {//Get the sent message selected by the user,calls the appropriate function and displays the message on the screen
            $result = $this->_model->getSentMessageById($messageid);
            $this->set('result', $result);
            $this->set('view', $messageFilter);
        } else if ($messageFilter == 2) {//Get the inbox message selected by the user,calls the appropriate function and displays the message on the screen
            $result = $this->_model->getInboxMessageById($messageid);
            $this->set('result', $result);
            $this->set('view', $messageFilter);
        }
        $this->set('view', $messageFilter);
    }

    //This function displays the chatbox for the user
    //Several pieces of code relating to the chat features have been modified based on http://anantgarg.com/2009/05/13/gmail-facebook-style-jquery-chat/ access on 12/12/2011
    public function chat() {
        $value = "";
        $name = "";
        if ($_GET['action'] == "chatheartbeat") {
            $value = "chatHeartbeat";
        }
        if ($_GET['action'] == "sendchat") {
            $value = "sendChat";
        }
        if ($_GET['action'] == "closechat") {
            $value = "closeChat";
        }
        if ($_GET['action'] == "startchatsession") {
            $value = "startChatSession";
        }

        if (!isset($_SESSION['chatHistory'])) {
            $_SESSION['chatHistory'] = array();
        }

        if (!isset($_SESSION['openChatBoxes'])) {
            $_SESSION['openChatBoxes'] = array();
        }

        if ($value == "chatHeartbeat") {
            $chats = $this->_model->fetchOnlineSentMessagesByUserId($this->user->user_id);
            $items = '';

            $chatBoxes = array();

            foreach ($chats as $chat) {

                $name = $chat->first_name;

                if (!isset($_SESSION['openChatBoxes'][$name]) && isset($_SESSION['chatHistory'][$name])) {
                    $items = $_SESSION['chatHistory'][$name];
                }
                $chat->content = $this->sanitize($chat->content);

                $items .= <<<EOD
{
"s": "0",
"f": "{$name}",
"m": "{$chat->content}"
},
EOD;

                if (!isset($_SESSION['chatHistory'][$name])) {
                    $_SESSION['chatHistory'][$name] = '';
                }

                $_SESSION['chatHistory'][$name] .= <<<EOD
{
"s": "0",
"f": "{$name}",
"m": "{$chat->content}"
},
EOD;

                unset($_SESSION['tsChatBoxes'][$name]);
                $_SESSION['openChatBoxes'][$name] = $chat->sent_time;
            }

            if (!empty($_SESSION['openChatBoxes'])) {
                foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
                    if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                        $now = time() - strtotime($time);
                        $time = date('g:iA M dS', strtotime($time));
                        $message = "Sent at $time";
                        if ($now > 180) {
                            $items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

                            if (!isset($_SESSION['chatHistory'][$chatbox])) {
                                $_SESSION['chatHistory'][$chatbox] = '';
                            }

                            $_SESSION['chatHistory'][$chatbox] .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
                            $_SESSION['tsChatBoxes'][$chatbox] = 1;
                        }
                    }
                }
            }

            $sql = $this->_model->updatetoMarkedMessageByUserId($this->user->user_id, 1);

            if ($items != '') {
                $items = substr($items, 0, -1);
            }
            header('Content-type: application/json');
            ?>
            {
            "items": [
            <?php echo $items; ?>
            ]
            }

            <?php
            exit(0);
        } else if ($value == "sendChat") {
            $from = $this->user->user_id;
            $to = $_POST['to'];
            $id = $_POST['id'];
            $message = $_POST['message'];

            $_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());

            $messagesan = $this->sanitize($message);

            if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
                $_SESSION['chatHistory'][$_POST['to']] = '';
            }

            $_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
{
"s": "1",
"f": "{$to}",
"m": "{$messagesan}"
},
EOD;


            unset($_SESSION['tsChatBoxes'][$_POST['to']]);
            $result = $this->_model->createNewMessage($from, $id, $this->onlineMessage, $message);

            echo "1";
            exit(0);
        } else if ($value == "startChatSession") {
            $items = '';
            if (!empty($_SESSION['openChatBoxes'])) {
                foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
                    $items .= $this->chatBoxSession($chatbox);
                }
            }

            if ($items != '') {
                $items = substr($items, 0, -1);
            }

            header('Content-type: application/json');
            ?>
            {
            "username": "<?php echo $this->user->first_name; ?>",
            "items": [
            <?php echo $items; ?>
            ]
            }

            <?php
            exit(0);
        } else if ($value == "closeChat") {
            unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
            echo "1";
            exit(0);
        }
    }

    //This function displays the user details which user selected to chat with
    public function getAjaxUserDetails() {
        $this->layout = 'empty'; //

        $type = 0;
        $user_id = $this->user->user_id;
        $user_name = "";
        if (isset($_GET['s'])) {
            $user_name = $this->sanitize($_GET['s']);
        }
        if (isset($_GET['type'])) {
            $type = $this->sanitize($_GET['type']);
        }

        $userList = new loginModel();
        if ($type == 1) {//
            if (strlen($user_name) == 0) {//fetches all the user details when name is empty but passes the user input if it is not
                $result = $userList->getOtherUsers($user_id);
            } else {
                $result = $userList->findUserByName($user_id, $user_name);
            }
        } else if ($type == 2) {//fetch user details used for autosuggest searchfield
            $result = $userList->findUserByDetail($user_id, $user_name);
        }
        header('Content-type: application/json');
        echo json_encode($result->fetchAll(PDO::FETCH_OBJ), JSON_NUMERIC_CHECK);
    }

    //This function clears function remove text based for securtiy 
    //Several pieces of code relating to the function have been modified <http://anantgarg.com/2009/05/13/gmail-facebook-style-jquery-chat/ access on 12/12/2011>
    private function sanitize($text) {//for security
        $text = htmlspecialchars($text, ENT_QUOTES); // Removing any HTML character
        $text = str_replace("\n\r", "\n", $text);
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\n", "<br>", $text);
        $text = mysql_real_escape_string($text); // Preventing SQL injection
        return $text;
    }

    //This function is used by the chat() function creates a new chat session based on http://anantgarg.com/2009/05/13/gmail-facebook-style-jquery-chat/ access on 12/12/2011
    private function chatBoxSession($chatbox) {
        $items = '';
        if (isset($_SESSION['chatHistory'][$chatbox])) {
            $items = $_SESSION['chatHistory'][$chatbox];
        }
        return $items;
    }

}
?>
