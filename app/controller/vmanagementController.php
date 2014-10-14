<?php

class vmanagementController extends AppController
{
      public $layout = 'backend_default';
      protected $_requresLogin = true;
    /**
     * function gets the list of events and sets it on the view
     */
      
    public function __construct($action = null) {
        parent::__construct($action);
        
        $this->addStyle('backend/vmanagement.css');
        $this->addStyle('backend/gallery.css');
        
        $this->user = $this->login->getUser();
        $this->set('user', $this->user);
    }
    
    public function index()
    {
        if ($this->isPostAction()) {
            $search = $_POST['search'];
            $filter = $_POST['filter'];
            
            $events = $this->_model->get_event_list_by($search, $filter);
        }
        else
        {
            $events = $this->_model->get_event_list();
        }
        $this->set('event_list', $events);
    }
    
    /**
     * 
     */
    public function event_details()
    {
        $event = $this->_model->get_event_details($_GET['event_id']);
        $event_users = $this->_model->get_event_users($_GET['event_id']);
        $this->set('event', $event);
        $this->set('event_users', $event_users);
            
    }
    
    /**
     * 
     */
    public function create_event()
    {
        if ($this->isPostAction())
        {
            $name = $_POST['event_name'];
            $date = $_POST['event_date'];
            $description = $_POST['event_description'];
            $capacity = $_POST['event_capacity'];
            $skills_required = $_POST['event_skills_required_id'];
            $event_type_name = $_POST['event_type_name'];
            $user_id = $_POST['user_id'];
//            echo $name.'-'.$date.'-'.$description.'-'.$capacity.'-'.$skills_required.'-'.$event_type_name.'-'.$user_id;
            
           $this->_model->create_event($name, $date, $description, $capacity, $skills_required, $event_type_name, $user_id);
           $this->set('event_created', true);
        }
           $events = $this->_model->get_event_list();
           $this->set('event_list', $events);
           $users = $this->_model->get_user_list();
           $this->set('user_list', $users);
           $skills = $this->_model->get_skill_type();
           $this->set('skill_list', $skills);
    }
    
    public function modify_event()
    {
        if ($this->isPostAction())
        {
            $id = $_POST['event_id'];
            $name = $_POST['event_name'];
            $date = $_POST['date'];
            $description = $_POST['description'];
            $capacity = $_POST['capacity'];
            $skills_required = $_POST['skills_required_id'];
            $event_type_id = $_POST['event_type_id'];
            $user_id = $_POST['user_id'];
            
            $this->_model->modify_event($id,$name,$date,$description,$capacity,$skills_required,$event_type_id, $user_id);
            
            $this->set('event_modified', true);
        }
        else {
            $event = $this->_model->get_event_details($_GET['event_id']);
            $this->set('event', $event);
        }
        $events = $this->_model->get_event_list();
        $this->set('event_list', $events);
            
    }
    /**  public function event_signup()
    {
        $event_signup = $this->_model->get_user_list();
     *  $this->set('user_list', $event_signup);
        
    }*/
    public function enable_skill_audit()
    {
        $skills = $this->_model->get_skill_type_user();        
        //$skill_type_user = $this->_model->get_skill_type_user($_GET['user_id']);
        $this->set('skills', $skills);
        $events = $this->_model->get_event_list();
        $this->set('event_list', $events);
        //$this->set('skill_type_user', $skill_type_user);
    }
     public function add_skill() {
        if ($this->isPostAction())
        {
            $skill_type_name = $_POST['skill_name'];
            $level = $_POST['skill_level'];
            $user_id = $_POST['user_id'];
            
            
            $this->_model->add_skill($skill_type_name,$level,$user_id);
            $this->set('skill_created', true);
            
            
        }
        $events = $this->_model->get_event_list();
        $this->set('event_list', $events);
            
        
    }
    public function event_signup(){
            $skills = $this->_model->get_skill_list();
            $this->set('skill_list', $skills);
        if ($this->isPostAction())
        {
            $name = $_POST['skill_name'];
            $this->_model->get_skill_list($name);
            
            $this->set('skill_required_added', true);
        }    
        
    }
        public function modify_enable_skill_audit()
        {
             if ($this->isPostAction())
             {
                    $id = $_GET['skill_type_id'];
                    $name = $_POST['skill_type_name'];
                    $level = $_POST['level'];
                    $user_id = $_POST['user_id'];
                    
                            
                    $this->_model->modify_skill($id,$name,$level,$user_id);
                    $this->set('skill_audited', true); 
                    
                    
             }    
            else {
                $skill = $this->_model->get_skill_details($_GET['skill_type_id']);
                $this->set('skill', $skill);
                $events = $this->_model->get_event_list();
                $this->set('event_list', $events);
            }
        }
        public function delete_enable_skill_audit()
        {
            $this->_model->delete_enable_skill_audit($_GET['skill_type_id']);
        }
        
        public function delete_event()
        {
            $this->_model->delete_event($_GET['event_id']);
        }
    }
?>