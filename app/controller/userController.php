<?php

/*
   * To change this template, choose Tools | Templates
   * and open the template in the editor.
*/

/**
 * Description of userController
 *
 * @author Segun
 */
class userController extends AppController {

	public $layout = 'backend_default';
        protected $_requresLogin = true;

        public function __construct($action = null) {
            parent::__construct($action);
            
            $this->addStyle('backend/user.css');
            
            $this->user = $this->login->getUser();
            $this->set('user', $this->user);
        }

	public function index()
	{
            $errormsg = isset($_GET['errormsg'])?$_GET['errormsg']:'';
            $successmmsg = isset($_GET['successmsg'])?$_GET['successmsg']:'';
            $this->set('errormsg',$errormsg);
            $this->set('successmsg',$successmmsg);
            $page = isset($_GET['page'])?$_GET['page']:0;
            $search = isset($_GET['search'])?$_GET['search']:'';
            $this->set('page',$page);
            $this->set('data',$this->_model->getUsers($search,$page.',10'));
            $this->set('title','User Management');

	}

	public function add(){
            $errormsg = isset($_GET['errormsg'])?$_GET['errormsg']:'';
            $successmmsg = isset($_GET['successmsg'])?$_GET['successmsg']:'';
            $this->set('errormsg',$errormsg);
            $this->set('successmsg',$successmmsg);
            $this->set('usertype', $this->_model->getUserTypes());
            if(isset($_SESSION['data'])){
            	if(empty($errormsg))unset($_SESSION['data']);
		else $this->set('input',$_SESSION['data']);
            }
            $this->set('title','Add new User');
	}

	public function addpost(){
		if($_POST){
			$data=array();
			$data['user_id']=isset($_POST['dataid'])?$_POST['dataid']:0;
			$data['user_type_id']=isset($_POST['user_type'])?$_POST['user_type']:0;
			$data['first_name']=isset($_POST['first_name'])?$_POST['first_name']:'';
			$data['last_name']=isset($_POST['last_name'])?$_POST['last_name']:'';
			$data['address']=isset($_POST['address'])?$_POST['address']:'';
			$data['email']=isset($_POST['email'])?$_POST['email']:'';
			$data['post_code']=isset($_POST['postcode'])?$_POST['postcode']:'';
			$data['phone_no']=isset($_POST['phone'])?$_POST['phone']:'';
			$data['password']=isset($_POST['password'])?md5($_POST['password']):'';
			if($data['user_id']>0 && empty($data['password']))unset($data['password']);
			$data['gender']=isset($_POST['gender'])?$_POST['gender']:0;
			$data['dob']=isset($_POST['birthday'])?$_POST['birthday']:'';
			$data['deleted']=isset($_POST['active'])?$_POST['active']:0;
			$_SESSION['data'] = $data;

			if($data['user_id']==0 && empty($data['password']))header('Location: index.php?c=user&a=add&errormsg='.rawurlencode('Please fill the password.'));
			if(!empty($data['first_name']) && !empty($data['last_name'])){
				if($data['user_id']>0){
					if($this->_model->updateUser($data))header('Location: index.php?c=user&a=index&successmsg='.rawurlencode('User Modification is successful'));
					else header('Location: index.php?c=user&a=edit&id='.$data['user_id'].'&errormsg='.rawurlencode('User Modification is failed'));
				}else{
					if($this->_model->addUser($data))header('Location: index.php?c=user&a=index&successmsg='.rawurlencode('User Registration is successfull'));
					else header('Location: index.php?c=user&a=add&errormsg='.rawurlencode('User Registration is failed'));
				}
			}else header('Location: index.php?c=user&a='.($data['user_id']>0?'edit':'add').'&errormsg='.rawurlencode('First name and last name cannot be empty.'));
		}
	}

	public function edit(){
            $id = isset($_GET['id'])?$_GET['id']:0;
            $data = $this->_model->getUserById($id);
            $this->set('input',$data);
            $this->set('usertype',$this->_model->getUserTypes($data['user_type_id']));
            $errormsg = isset($_GET['errormsg'])?$_GET['errormsg']:'';
            $successmmsg = isset($_GET['successmsg'])?$_GET['successmsg']:'';
            $this->set('errormsg',$errormsg);
            $this->set('successmsg',$successmmsg);
            $this->template = 'add';
            $this->set('title','Edit User');
	}

	public function delete(){
		$id = isset($_GET['id'])?$_GET['id']:0;
		if($this->_model->deleteUser($id))header('Location: index.php?c=user&a=index&successmsg='.rawurlencode('User Deletion is successfull'));
		else header('Location: index.php?c=user&a=index&errormsg='.rawurlencode('User deletion is failed.'));
	}
        
        public function sitestat()
	{
            $month=isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
            $year=isset($_REQUEST['year'])?$_REQUEST['year']:date('Y');
            $this->set('month',$month);
            $this->set('year',$year);
            $this->set('byday',$this->_model->siteStatByDay($month,$year));
            $this->set('bypage',$this->_model->siteStatByPage($month,$year));
            $this->set('bybrowser',$this->_model->siteStatByBrowser($month,$year));
            $this->set('byreferrer',$this->_model->siteStatByReferrer($month,$year));
            $this->set('title','Site Statistic');
	}
        
        public function volunteerstat()
	{
            $month=isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
            $year=isset($_REQUEST['year'])?$_REQUEST['year']:date('Y');
            $this->set('month',$month);
            $this->set('year',$year);
            $this->set('data',$this->_model->volunteerStat($month,$year));
            $this->set('title','Volunteer Statistic');
	}
        
        public function volunteerstatdetail()
	{
            $id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
            $month=isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
            $year=isset($_REQUEST['year'])?$_REQUEST['year']:date('Y');
            $this->set('month',$month);
            $this->set('year',$year);
            $this->set('id',$id);
            $this->set('data',$this->_model->volunteerStatDetail($month,$year,$id));
            $this->set('title','Volunteer Statistic Detail');
	}
}

?>
