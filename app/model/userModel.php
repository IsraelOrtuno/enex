<?php

class userModel extends Model
{

	public function getUsers($search='',$limit='0,10'){
		$search = addslashes($search);
		$limit = addslashes($limit);
		$sql = "SELECT * FROM `user` as `t1` LEFT JOIN `user_type` as `t2` ON (`t2`.`user_type_id`=`t1`.`user_type_id`) WHERE 1 ".(!empty($search)?" AND (`first_name` LIKE '%$search%' OR `last_name` LIKE '%$search%') ":'')." LIMIT $limit";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}

	public function getUserById($id=0)
	{
		$res = $this->_db->query("SELECT * FROM `user` as `t1` LEFT JOIN `user_type` as `t2` ON (`t2`.`user_type_id`=`t1`.`user_type_id`) WHERE `user_id`='".addslashes($id)."' LIMIT 1");
		return $res->fetch();
	}

	public function getUserTypes($id=0){
		$sql = "SELECT *,IF(`user_type_id` IN ($id),'selected','') as `selected` FROM `user_type` WHERE 1 ";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}

	public function addUser($data)
	{
		$field ='';
		$content = '';
		foreach($data as $k=>$v){
			$field .= "`$k`,";
			$content .= "'".addslashes($v)."',";
		}
		$field = rtrim($field,',');
		$content = rtrim($content,',');
		$sql = "INSERT INTO `user` ($field,is_login) VALUES ($content,0) ";
		$result = $this->_db->query($sql);

		if($result)return $this->_db->getLastInsertId();
		else return null;
	}

	public function updateUser($data,$user_id=0){
		if(empty($user_id) && !empty($data['user_id']))$user_id = $data['user_id'];
		else return NULL;
		$field = '';
		foreach($data as $k=>$v){
			$field .= "`$k`='".addslashes($v)."',";
		}
		$field = rtrim($field,',');
		$sql = "UPDATE `user` SET $field WHERE `user_id`='".addslashes($user_id)."'";
		return $this->_db->exec($sql);
	}

	public function deleteUser($user_id=0){
		$sql = "DELETE FROM `user` WHERE `user_id`=$user_id";
		return $this->_db->exec($sql);
	}
        
        public function siteStatByDay($month='',$year = ''){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT COUNT(*) as cnt, `thedate_visited` FROM `stattracker` WHERE MONTH(`thedate_visited`)='".addslashes($month)."' AND YEAR(`thedate_visited`)='".addslashes($year)."' GROUP BY `thedate_visited` ORDER BY `cnt` DESC LIMIT 0,10";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
        
        public function siteStatByPage($month='',$year = ''){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT COUNT(*) as cnt, `page` FROM `stattracker` WHERE MONTH(`thedate_visited`)='".addslashes($month)."' AND YEAR(`thedate_visited`)='".addslashes($year)."' GROUP BY `page` ORDER BY `cnt` DESC LIMIT 0,10";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
        
        public function siteStatByReferrer($month='',$year = ''){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT COUNT(*) as cnt, `from_page` FROM `stattracker` WHERE MONTH(`thedate_visited`)='".addslashes($month)."' AND YEAR(`thedate_visited`)='".addslashes($year)."' GROUP BY `from_page` ORDER BY `cnt` DESC LIMIT 0,10";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
        
        public function siteStatByBrowser($month='',$year = ''){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT COUNT(*) as cnt, `browser` FROM `stattracker` WHERE MONTH(`thedate_visited`)='".addslashes($month)."' AND YEAR(`thedate_visited`)='".addslashes($year)."' GROUP BY `browser` ORDER BY `cnt` DESC LIMIT 0,10";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
        
        public function volunteerStat($month='',$year = '', $user_type=3){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT COUNT(*) as cnt, `t1`.`user_id`,`first_name`, `last_name` FROM `log_record` as `t1` LEFT JOIN `user` as `t2` ON (`t2`.`user_id`=`t1`.`user_id`) LEFT JOIN `user_type` as `t3` ON (`t3`.`user_type_id`=`t2`.`user_type_id`) WHERE MONTH(`date`)='".addslashes($month)."' AND YEAR(`date`)='".addslashes($year)."' AND `t2`.`user_type_id`='".addslashes($user_type)."' GROUP BY `t1`.`user_id` ORDER BY `cnt` DESC LIMIT 0,10";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
        
        public function volunteerStatDetail($month='',$year = '',$id=0){
                if(empty($month))$month=date('m');
                if(empty($year))$year = date('Y');
		$sql = "SELECT * FROM `log_record` as `t1` LEFT JOIN `user` as `t2` ON (`t2`.`user_id`=`t1`.`user_id`) LEFT JOIN `log_event` as `t3` ON (`t3`.`log_event_id`=`t1`.`log_event_id`) WHERE MONTH(`date`)='".addslashes($month)."' AND YEAR(`date`)='".addslashes($year)."' AND `t2`.`user_id`=". addslashes($id)." ORDER BY `date` DESC";
		$result = array();
		$res = $this->_db->query($sql);
		while($r = $res->fetch()){
			$result[] = $r;
		}
		return $result;
	}
}

?>
