<?php if (!defined('APPLICATION')) exit();
/**
  * Forum Model
  * 
  * @package FourumMe
  */

class ForumModel extends Gdn_Model { 
	
	public function __construct() {
	      parent::__construct('Forums');
	
	}//end _construct();
	
	
	//Get 20 Most Recent Forums
	public function GetRecent(){
		$recent = $this->SQL->Select('Name, Path')
			->From('Forums')
			->OrderBy('ForumID', 'desc')
			->Limit(20,0)
			->Get()->Result(DATASET_TYPE_ARRAY);

		return $recent;

	}// end GetRecent();
	
	//Get by Path
	public function GetByPath($Path){
		$result = $this->SQL->Select('Name, UserID')
			->From('Forums')
			->Where('Path', $Path)
			->Get()->Result(DATASET_TYPE_ARRAY);
		
		return $result;

	}// end GetRecent();
	
	
	//Get forums for a specific user
	public function GetByUserID($UserID){

		$result = $this->SQL->Select('Name, Path')
			->From('Forums')
			->Where('UserID', $UserID)
			->Get()->Result(DATASET_TYPE_ARRAY);

		if(sizeof($result) > 0){
			return $result;
		}else{

			return FALSE;
		}
	}//end GetByUserID
	
	public function UpdateName($NewName, $ForumPath){
		$Name = $NewName;
		$Path = $ForumPath;
		$result = $this->SQL->Update('Forums')
			->Set('Name', $Name)
			->Where('Path', $Path)
			->Put();
			
		if(sizeof($result) > 0){
			return TRUE;
		}else{

			return FALSE;
		}
		
	}
	
	public function Save($FormPostValues) {
	
		$this->DefineSchema();
		
		$ForumID = ArrayValue('ForumID', $FormPostValues);
		$UserID = ArrayValue('UserID', $FormPostValues);
		$Name = ArrayValue('Name', $FormPostValues);
		$Path = ArrayValue('Path', $FormPostValues);
		$DateCreated = ArrayValue('DateCreated', $FormPostValues);
		
		$Insert = $ForumID > 0 ? FALSE : TRUE;
	    if($Insert)
	       $this->AddInsertFields($FormPostValues);
	
	    $this->Validation->ApplyRule('Name', 'Required');
		$this->Validation->ApplyRule('Path', 'Required');
		$this->Validation->ApplyRule('Path', 'Length', T('Path Must be less than 15 characters'));
		$this->Validation->ApplyRule('TermsOfService', 'Required', T('You must agree to the terms of service.'));
		
		if($this->Validate($FormPostValues))
			$result = $this->Insert($FormPostValues);
			
		return $result;
		
	}// end Save
	
	public function CheckPath($Path){
		
		$Check = $this->SQL->Select('Path')
			->From('Forums')
			->Where('Path', $Path)
			->Get()->Result(DATASET_TYPE_ARRAY);

		if(sizeof($Check) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	
	}// end CheckPath
	
	public function CheckName($Name){
		
		$Check = $this->SQL->Select('Path')
			->From('Forums')
			->Where('Name', $Name)
			->Get()->Result(DATASET_TYPE_ARRAY);

		if(sizeof($Check) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	
	}//end CheckName
	
	public function CreateDatabase($Fields){
		
		$ValidPath = $Fields['Path'];
		$ValidName = $Fields['Name'];
		$validID = $Fields['UserID'];
		
		$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
		$sql = "CREATE DATABASE ".$ValidPath;
		
		if(mysql_query($sql, $connect)){
			
			SaveToConfig(array(
				//'Forum.Title' => $ValidName,
				//'Forum.User' => $ValidID,
	            'ForumDatabase.Host' => C('Database.Host'),
				'ForumDatabase.User' => C('Database.User'),
				'ForumDatabase.Password' => C('Database.Password'),
	            'ForumDatabase.Name' => $ValidPath));
			
			
			return TRUE;
		}
		
		return FALSE;
		
	}//end CreateDatabase
	
	// replaces structure.php to setup database
	public function DatabaseSetup($Path){
		
		if (!isset($Drop))
		   $Drop = FALSE;

		if (!isset($Explicit))
		   $Explicit = TRUE;

		$Database = Gdn::ForumDatabase();
		$SQL = $Database->SQL();
		$Construct = $Database->Structure();

		$Construct->Table('ForumData')
		   ->PrimaryKey('ForumDataID')
		   ->Column('ForumID', 'int', FALSE)
		   ->Column('Name', 'varchar(200)', FALSE)
		   ->Column('Path', 'varchar(15)', FALSE)
		   ->Column('DateCreated', 'datetime', FALSE)
		   ->Set($Explicit, $Drop);



		$Construct->Table('ForumUsers')
		   ->PrimaryKey('ForumUserID')
		   ->Column('UserID', 'int', FALSE)
		   ->Column('Admin', 'tinyint(1)', '0')
		   ->Column('Moderator', 'tinyint(1)', '0')
		   ->Column('Banned', 'tinyint(1)', '0')
		   ->Set($Explicit, $Drop);
		
		$Construct->Table('Category');
	
		$Construct->PrimaryKey('CategoryID')
		   ->Column('ParentCategoryID', 'int', TRUE)
		   ->Column('CountDiscussions', 'int', '0')
		   ->Column('Name', 'varchar(255)')
		   ->Column('UrlCode', 'varchar(255)', TRUE)
		   ->Column('Description', 'varchar(500)', TRUE)
		   ->Column('InsertUserID', 'int', FALSE, 'key')
		   ->Column('UpdateUserID', 'int', TRUE)
		   ->Column('DateInserted', 'datetime')
		   ->Column('LastCommentID', 'int', NULL)
		   ->Column('LastDiscussionID', 'int', NULL)
		   ->Set($Explicit, $Drop);
		
		$Construct->Table('Discussion');
		
		$Construct
		   ->PrimaryKey('DiscussionID')
		   ->Column('CategoryID', 'int', FALSE, 'key')
		   ->Column('InsertUserID', 'int', FALSE, 'key')
		   ->Column('UpdateUserName', 'varchar(100)')
		   ->Column('LastCommentID', 'int', TRUE)
		   ->Column('Name', 'varchar(100)', FALSE, 'fulltext')
		   ->Column('Body', 'text', FALSE, 'fulltext')
		   ->Column('Format', 'varchar(20)', TRUE)
		   ->Column('Tags', 'varchar(255)', NULL)
		   ->Column('CountComments', 'int', '1')
		   ->Column('Closed', 'tinyint(1)', '0')
		   ->Column('DateInserted', 'datetime', NULL)
		   ->Column('Attributes', 'text', TRUE)
		   ->Column('RegardingID', 'int(11)', TRUE, 'index')
		   ->Engine('MyISAM')
		   ->Set($Explicit, $Drop);
		
		
		return TRUE;
		
	}
	
	public function SaveAdmin($fields){
		
		$path = $fields['Path'];
		$UserID = $fields['UserID'];
		$Date = date('Y-m-d H:00');
		
		$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
		mysql_select_db($path, $connect);
		mysql_query("INSERT INTO GDN_ForumUsers (UserID, Admin) VALUES ('$UserID', 1)");
		mysql_query("INSERT INTO GDN_Category (Name, InsertUserID, DateInserted) VALUES ('All Discussions', '$UserID', '$Date')");
		mysql_close($connect);
	}
	
	public function GetCategoryName($Db){
		
			$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
			mysql_select_db($Db, $connect);
			$result = mysql_query("SELECT * FROM Gdn_Category");

			/*while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				printf("Name: %s", $row["name"]);
			}

			$send = mysql_free_result($result);*/
			mysql_close($connect);

			return $result;
		
	}
	
	
	

	

/*	
	
public function Create($fields){
	
	$path = $fields['Path'];
	
	if($path == ''){
		$path = $this->_generateRandomPath();
	}
	
	$connect = mysql_connect(C('Database.Host'), C('Database.User'), C('Database.Password'));
	$sql = "CREATE DATABASE ".$path;
	
	if(mysql_query($sql, $connect)){
		
			RemoveFromConfig('TempDatabase.Name');
			SaveToConfig('TempDB.Name', $path);
			
			return TRUE;
	}
	
}

private function _generateRandomPath(){
	
	$length = 5;
	$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	
	for($i = 0; $i < $length; $i++){
		
		$string .= $characters[mt_rand(0, strlen($characters))];
	}
	
	return $string;
}*/



	
	
} //end class Forum Model