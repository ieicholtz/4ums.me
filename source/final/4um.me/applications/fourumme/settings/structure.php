<?php if (!defined('APPLICATION')) exit();

/**
  * FourumMe Structure
  * 
  * @package FourumMe
  */

if (!isset($Drop))
   $Drop = FALSE; 
   
if (!isset($Explicit))
   $Explicit = FALSE;


 
//Get default DB and assign Driver & Structure Class
$Database = Gdn::Database();
$SQL = $Database->SQL();
$Construct = $Database->Structure();

//Main Forums Table
$Construct->Table('Forums')
   ->PrimaryKey('ForumID')
   ->Column('UserID', 'int', FALSE)
   ->Column('Name', 'varchar(200)', FALSE)
   ->Column('Path', 'varchar(15)', FALSE)
   ->Column('DateCreated', 'datetime', FALSE)
   ->Set($Explicit, $Drop);

$Construct->Table('Feedback')
	->PrimaryKey('ReportID')
	->Column('UserID', 'int', TRUE)
	->Column('Name', 'varchar(64)', FALSE)
	->Column('Email', 'varchar(200)', FALSE)
	->Column('Message', 'text', FALSE)
	->Column('DatePosted', 'datetime', FALSE)
	->Set($Explicit, $Drop);


$PermissionModel = Gdn::PermissionModel();
$PermissionModel->Database = $Database;
$PermissionModel->SQL = $SQL;


$PermissionModel->Define(array(
	'FourumMe.Pages.Manage'
));

// Set the initial administrator permissions.
$PermissionModel->Save(array(
   'Role' => 'Administrator',
   'FourumMe.Pages.Manage' => 1
   ), TRUE);