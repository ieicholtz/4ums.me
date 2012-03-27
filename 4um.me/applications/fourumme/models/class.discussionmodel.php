<?php if (!defined('APPLICATION')) exit();
/**
  * Forum Model
  * 
  * @package FourumMe
  */

class DiscussionModel extends Gdn_Model { 
	
	public function __construct() {
	      parent::__construct('Discussion');
	
}//end _construct();

	public function GetByName($Db){
		$database = $Db;
		$i = 0;	
		$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
		mysql_select_db($database, $connect);
		$result = mysql_query("SELECT DiscussionID, Name, CountComments, Closed, UpdateUserName, DateInserted FROM GDN_Discussion");
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			 $results[$i] = array(
				'DiscussionID'=>$row['DiscussionID'], 
				'Name'=>$row['Name'], 
				'CountComments'=>$row['CountComments'],
				'Closed'=>$row['Closed'],
				'UpdateUserName'=>$row['UpdateUserName'],
				'DateInserted'=>$row['DateInserted']
				);
		     $i++;	
		}

		mysql_close($connect);
		
		return $results;
	
	}
	
	
	public function Save($FormPostValues) {
	
		$this->DefineSchema();
		
		$UserID = ArrayValue('InsertUserID', $FormPostValues);
		$Name = ArrayValue('Name', $FormPostValues);
		$Path = ArrayValue('Path', $FormPostValues);
	
	    $this->Validation->ApplyRule('Name', 'Required');
		$this->Validation->ApplyRule('Body', 'Required');

		if($this->Validate($FormPostValues));
			return TRUE;
		
	}// end Save
	
	public function AddDiscussion($Db, $Fields){
		$database = $Db;
		$CategoryID = $Fields['CategoryID'] +1;
		$InsertUserID = $Fields['InsertUserID'];
		$Name = $Fields['Name'];
		$Body = $Fields['Body'];
		$UpdateUserName = $Fields['UpdateUserName'];
		$Date = $Fields['DateInserted'];
		
		print_r($Fields);
	
		$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
		mysql_select_db($database, $connect);
		$result = mysql_query("INSERT INTO GDN_Discussion (CategoryID, InsertUserID, Name, Body, UpdateUserName, DateInserted, CountComments) VALUES ('$CategoryID', '$InsertUserID', '$Name', '$Body', '$UpdateUserName', '$Date', 1)");

		return TRUE;
	}
	

}