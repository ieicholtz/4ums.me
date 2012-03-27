<?php if (!defined('APPLICATION')) exit();
/**
  * Forum Model
  * 
  * @package FourumMe
  */

class CategoryModel extends Gdn_Model { 
	
	public function __construct() {
	      parent::__construct('Category');
	
}//end _construct();

	public function GetByName($Db){
		$database = $Db;
		$i = 0;	
		$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
		mysql_select_db($database, $connect);
		$result = mysql_query("SELECT Name, CountDiscussions FROM GDN_Category");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			 $results[$i] = array('Name'=>$row['Name'], 'CountDiscussions'=>$row['CountDiscussions']);
		     $i++;	
		}

		mysql_close($connect);
		
		return $results;
	
	}


}