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

		// Role Table
		$Construct->Table('Role');

		$RoleTableExists = $Construct->TableExists();

		$Construct
		   ->PrimaryKey('RoleID')
		   ->Column('Name', 'varchar(100)')
		   ->Column('Description', 'varchar(500)', TRUE)
		   ->Column('Sort', 'int', TRUE)
		   ->Column('Deletable', 'tinyint(1)', '1')
		   ->Column('CanSession', 'tinyint(1)', '1')
		   ->Set($Explicit, $Drop);

		if (!$RoleTableExists || $Drop) {
		   // Define some roles.
		   // Note that every RoleID must be a power of two so that they can be combined as a bit-mask.
		   $RoleModel = Gdn::Factory('RoleModel');
		   $RoleModel->Database = $Database;
		   $RoleModel->SQL = $SQL;
		//   $RoleModel->Define(array('Name' => 'Banned', 'RoleID' => 1, 'Sort' => '1', 'Deletable' => '1', 'CanSession' => '0', 'Description' => 'Banned users are not allowed to participate or sign in.'));
		   $RoleModel->Define(array('Name' => 'Guest', 'RoleID' => 2, 'Sort' => '2', 'Deletable' => '0', 'CanSession' => '0', 'Description' => 'Guests can only view content. Anyone browsing the site who is not signed in is considered to be a "Guest".'));
		   $RoleModel->Define(array('Name' => 'Applicant', 'RoleID' => 4, 'Sort' => '3', 'Deletable' => '0', 'CanSession' => '1', 'Description' => 'Users who have applied for membership, but have not yet been accepted. They have the same permissions as guests.'));
		   $RoleModel->Define(array('Name' => 'Member', 'RoleID' => 8, 'Sort' => '4', 'Deletable' => '1', 'CanSession' => '1', 'Description' => 'Members can participate in discussions.'));
		   $RoleModel->Define(array('Name' => 'Moderator', 'RoleID' => 32, 'Sort' => '5', 'Deletable' => '1', 'CanSession' => '1', 'Description' => 'Moderators have permission to edit most content.'));
		   $RoleModel->Define(array('Name' => 'Administrator', 'RoleID' => 16, 'Sort' => '6', 'Deletable' => '1', 'CanSession' => '1', 'Description' => 'Administrators have permission to do anything.'));
		   $RoleModel->Define(array('Name' => 'Confirm Email', 'RoleID' => 3, 'Sort' => '7', 'Deletable' => '1', 'CanSession' => '1', 'Description' => 'Users must confirm their emails before becoming full members. They get assigned to this role.'));
		   unset($RoleModel);
		}

		// User Table
		$Construct->Table('User');

		$PhotoIDExists = $Construct->ColumnExists('PhotoID');
		$PhotoExists = $Construct->ColumnExists('Photo');

		$Construct
			->PrimaryKey('UserID')
		   ->Column('Name', 'varchar(50)', FALSE, 'key')
		   ->Column('Password', 'varbinary(100)') // keep this longer because of some imports.
			->Column('HashMethod', 'varchar(10)', TRUE)
		   ->Column('Photo', 'varchar(255)', NULL)
		   ->Column('About', 'text', TRUE)
		   ->Column('Email', 'varchar(200)', FALSE, 'index')
		   ->Column('ShowEmail', 'tinyint(1)', '0')
		   ->Column('Gender', array('m', 'f'), 'm')
		   ->Column('CountVisits', 'int', '0')
		   ->Column('CountInvitations', 'int', '0')
		   ->Column('CountNotifications', 'int', NULL)
		   ->Column('InviteUserID', 'int', TRUE)
		   ->Column('DiscoveryText', 'text', TRUE)
		   ->Column('Preferences', 'text', TRUE)
		   ->Column('Permissions', 'text', TRUE)
		   ->Column('Attributes', 'text', TRUE)
		   ->Column('DateSetInvitations', 'datetime', TRUE)
		   ->Column('DateOfBirth', 'datetime', TRUE)
		   ->Column('DateFirstVisit', 'datetime', TRUE)
		   ->Column('DateLastActive', 'datetime', TRUE)
		   ->Column('LastIPAddress', 'varchar(15)', TRUE)
		   ->Column('DateInserted', 'datetime')
		   ->Column('InsertIPAddress', 'varchar(15)', TRUE)
		   ->Column('DateUpdated', 'datetime', TRUE)
		   ->Column('UpdateIPAddress', 'varchar(15)', TRUE)
		   ->Column('HourOffset', 'int', '0')
			->Column('Score', 'float', NULL)
		   ->Column('Admin', 'tinyint(1)', '0')
		   ->Column('Banned', 'tinyint(1)', '0') // 1 means banned, otherwise not banned
		   ->Column('Deleted', 'tinyint(1)', '0')
		   ->Set($Explicit, $Drop);

		// Make sure the system user is okay.
		$SystemUserID = C('Garden.SystemUserID');
		if ($SystemUserID) {
		   $SysUser = Gdn::UserModel()->GetID($SystemUserID);

		   if (!$SysUser || GetValue('Deleted', $SysUser)) {
		      $SystemUserID = FALSE;
		      RemoveFromConfig('Garden.SystemUserID');
		   }
		}

		if (!$SystemUserID) {
		   // Try and find a system user.
		   $SystemUserID = Gdn::SQL()->GetWhere('User', array('Name' => 'System', 'Admin' => 2))->Value('UserID');
		   if ($SystemUserID)
		      SaveToConfig('Garden.SystemUserID', $SystemUserID);
		}

		// UserRole Table
		$Construct->Table('UserRole');

		$UserRoleExists = $Construct->TableExists();

		$Construct
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('RoleID', 'int', FALSE, 'primary')
		   ->Set($Explicit, $Drop);

		if (!$UserRoleExists) {
		   // Assign the guest user to the guest role
		   $SQL->Replace('UserRole', array(), array('UserID' => 0, 'RoleID' => 2));
		   // Assign the admin user to admin role
		   $SQL->Replace('UserRole', array(), array('UserID' => 1, 'RoleID' => 16));
		}

		// User Meta Table
		$Construct->Table('UserMeta')
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('Name', 'varchar(255)', FALSE, array('primary', 'index'))
		   ->Column('Value', 'text', TRUE)
		   ->Set($Explicit, $Drop);

		// Create the authentication table.
		$Construct->Table('UserAuthentication')
			->Column('ForeignUserKey', 'varchar(255)', FALSE, 'primary')
			->Column('ProviderKey', 'varchar(64)', FALSE, 'primary')
			->Column('UserID', 'int', FALSE, 'key')
			->Set($Explicit, $Drop);

		$Construct->Table('UserAuthenticationProvider')
		   ->Column('AuthenticationKey', 'varchar(64)', FALSE, 'primary')
		   ->Column('AuthenticationSchemeAlias', 'varchar(32)', FALSE)
		   ->Column('Name', 'varchar(50)', TRUE)
		   ->Column('URL', 'varchar(255)', TRUE)
		   ->Column('AssociationSecret', 'text', FALSE)
		   ->Column('AssociationHashMethod', 'varchar(20)', FALSE)
		   ->Column('AuthenticateUrl', 'varchar(255)', TRUE)
		   ->Column('RegisterUrl', 'varchar(255)', TRUE)
		   ->Column('SignInUrl', 'varchar(255)', TRUE)
		   ->Column('SignOutUrl', 'varchar(255)', TRUE)
		   ->Column('PasswordUrl', 'varchar(255)', TRUE)
		   ->Column('ProfileUrl', 'varchar(255)', TRUE)
		   ->Column('Attributes', 'text', TRUE)
		   ->Set($Explicit, $Drop);

		$Construct->Table('UserAuthenticationNonce')
		   ->Column('Nonce', 'varchar(200)', FALSE, 'primary')
		   ->Column('Token', 'varchar(128)', FALSE)
		   ->Column('Timestamp', 'timestamp', FALSE)
		   ->Set($Explicit, $Drop);

		$Construct->Table('UserAuthenticationToken')
		   ->Column('Token', 'varchar(128)', FALSE, 'primary')
		   ->Column('ProviderKey', 'varchar(64)', FALSE, 'primary')
		   ->Column('ForeignUserKey', 'varchar(255)', TRUE)
		   ->Column('TokenSecret', 'varchar(64)', FALSE)
		   ->Column('TokenType', array('request', 'access'), FALSE)
		   ->Column('Authorized', 'tinyint(1)', FALSE)
		   ->Column('Timestamp', 'timestamp', FALSE)
		   ->Column('Lifetime', 'int', FALSE)
		   ->Set($Explicit, $Drop);

		$Construct->Table('Session')
			->Column('SessionID', 'char(32)', FALSE, 'primary')
			->Column('UserID', 'int', 0)
			->Column('DateInserted', 'datetime', FALSE)
			->Column('DateUpdated', 'datetime', FALSE)
			->Column('TransientKey', 'varchar(12)', FALSE)
			->Column('Attributes', 'text', NULL)
			->Set($Explicit, $Drop);

		$Construct->Table('AnalyticsLocal')
		   ->Engine('InnoDB')
		   ->Column('TimeSlot', 'varchar(8)', FALSE, 'unique')
		   ->Column('Views', 'int', NULL)
		   ->Set(FALSE, FALSE);

		// Only Create the permission table if we are using Garden's permission model.
		$PermissionModel = Gdn::PermissionModel();
		$PermissionModel->Database = $Database;
		$PermissionModel->SQL = $SQL;
		$PermissionTableExists = FALSE;
		if($PermissionModel instanceof PermissionModel) {
		   $PermissionTableExists = $Construct->TableExists('Permission');

			// Permission Table
			$Construct->Table('Permission')
				->PrimaryKey('PermissionID')
				->Column('RoleID', 'int', 0, 'key')
				->Column('JunctionTable', 'varchar(100)', TRUE) 
				->Column('JunctionColumn', 'varchar(100)', TRUE)
				->Column('JunctionID', 'int', TRUE)
				// The actual permissions will be added by PermissionModel::Define()
				->Set($Explicit, $Drop);
		}

		// Define the set of permissions that Garden uses.
		$PermissionModel->Define(array(
		   'Garden.Email.Manage',
		   'Garden.Settings.Manage',
		   'Garden.Settings.View',
		   'Garden.Routes.Manage',
		   'Garden.Messages.Manage',
		   'Garden.Applications.Manage',
		   'Garden.Plugins.Manage',
		   'Garden.Themes.Manage',
		   'Garden.SignIn.Allow' => 1,
		   'Garden.Registration.Manage',
		   'Garden.Applicants.Manage',
		   'Garden.Roles.Manage',
		   'Garden.Users.Add',
		   'Garden.Users.Edit',
		   'Garden.Users.Delete',
		   'Garden.Users.Approve',
		   'Garden.Activity.Delete',
		   'Garden.Activity.View' => 1,
		   'Garden.Profiles.View' => 1,
		   'Garden.Profiles.Edit' => 'Garden.SignIn.Allow',
		   'Garden.Moderation.Manage',
		   'Garden.AdvancedNotifications.Allow'
		   ));

		if (!$PermissionTableExists) {

		   // Set initial guest permissions.
		   $PermissionModel->Save(array(
		      'RoleID' => 2,
		      'Garden.Activity.View' => 1,
		      'Garden.Profiles.View' => 1,
		      'Garden.Profiles.Edit' => 0
		      ));

		   // Set initial confirm email permissions.
		   $PermissionModel->Save(array(
		       'RoleID' => 3,
		       'Garden.Signin.Allow' => 1,
		       'Garden.Activity.View' => 1,
		       'Garden.Profiles.View' => 1,
		       'Garden.Profiles.Edit' => 0
		       ));

		   // Set initial applicant permissions.
		   $PermissionModel->Save(array(
		      'RoleID' => 4,
		      'Garden.Signin.Allow' => 1,
		      'Garden.Activity.View' => 1,
		      'Garden.Profiles.View' => 1,
		      'Garden.Profiles.Edit' => 0
		      ));

		   // Set initial member permissions.
		   $PermissionModel->Save(array(
		      'RoleID' => 8,
		      'Garden.SignIn.Allow' => 1,
		      'Garden.Activity.View' => 1,
		      'Garden.Profiles.View' => 1,
		      'Garden.Profiles.Edit' => 1
		      ));

		   // Set initial moderator permissions.
		   $PermissionModel->Save(array(
		      'RoleID' => 32,
		      'Garden.SignIn.Allow' => 1,
		      'Garden.Activity.View' => 1,
		      'Garden.Moderation.Manage' => 1,
		      'Garden.Profiles.View' => 1,
		      'Garden.Profiles.Edit' => 1
		      ));

		   // Set initial admininstrator permissions.
		   $PermissionModel->Save(array(
		      'RoleID' => 16,
		      'Garden.Settings.Manage' => 1,
		      'Garden.Routes.Manage' => 1,
		      'Garden.Applications.Manage' => 1,
		      'Garden.Plugins.Manage' => 1,
		      'Garden.Themes.Manage' => 1,
		      'Garden.SignIn.Allow' => 1,
		      'Garden.Registration.Manage' => 1,
		      'Garden.Applicants.Manage' => 1,
		      'Garden.Roles.Manage' => 1,
		      'Garden.Users.Add' => 1,
		      'Garden.Users.Edit' => 1,
		      'Garden.Users.Delete' => 1,
		      'Garden.Users.Approve' => 1,
		      'Garden.Activity.Delete' => 1,
		      'Garden.Activity.View' => 1,
		      'Garden.Profiles.View' => 1,
		      'Garden.Profiles.Edit' => 1,
		      'Garden.AdvancedNotifications.Allow' => 1
		      ));
		}
		$PermissionModel->ClearPermissions();

		// Photo Table
		$Construct->Table('Photo');

		$PhotoTableExists = $Construct->TableExists('Photo');

		$Construct
			->PrimaryKey('PhotoID')
		   ->Column('Name', 'varchar(255)')
		   ->Column('InsertUserID', 'int', TRUE, 'key')
		   ->Column('DateInserted', 'datetime')
		   ->Set($Explicit, $Drop);

		// Invitation Table
		$Construct->Table('Invitation')
			->PrimaryKey('InvitationID')
		   ->Column('Email', 'varchar(200)')
		   ->Column('Code', 'varchar(50)')
		   ->Column('InsertUserID', 'int', TRUE, 'key')
		   ->Column('DateInserted', 'datetime')
		   ->Column('AcceptedUserID', 'int', TRUE)
		   ->Set($Explicit, $Drop);

		// Activity Table
		// Column($Name, $Type, $Length = '', $Null = FALSE, $Default = NULL, $KeyType = FALSE, $AutoIncrement = FALSE)
		$Construct->Table('Activity');
		$EmailedExists = $Construct->ColumnExists('Emailed');

		$Construct
			->PrimaryKey('ActivityID')
		   ->Column('CommentActivityID', 'int', TRUE, 'key')
		   ->Column('ActivityTypeID', 'int')
		   ->Column('ActivityUserID', 'int', TRUE, 'key')
		   ->Column('RegardingUserID', 'int', TRUE, 'key')
		   ->Column('Story', 'text', TRUE)
		   ->Column('Route', 'varchar(255)', TRUE)
		   ->Column('CountComments', 'int', '0')
		   ->Column('InsertUserID', 'int', TRUE, 'key')
		   ->Column('DateInserted', 'datetime')
		   ->Column('InsertIPAddress', 'varchar(15)', TRUE)
		   ->Column('Emailed', 'tinyint(1)', 0)
		   ->Set($Explicit, $Drop);

		if (!$EmailedExists) {
		   $SQL->Put('Activity', array('Emailed' => 1));
		}

		// ActivityType Table
		$Construct->Table('ActivityType')
			->PrimaryKey('ActivityTypeID')
		   ->Column('Name', 'varchar(20)')
		   ->Column('AllowComments', 'tinyint(1)', '0')
		   ->Column('ShowIcon', 'tinyint(1)', '0')
		   ->Column('ProfileHeadline', 'varchar(255)')
		   ->Column('FullHeadline', 'varchar(255)')
		   ->Column('RouteCode', 'varchar(255)', TRUE)
		   ->Column('Notify', 'tinyint(1)', '0') // Add to RegardingUserID's notification list?
		   ->Column('Public', 'tinyint(1)', '1') // Should everyone be able to see this, or just the RegardingUserID?
		   ->Set($Explicit, $Drop);

		// Insert some activity types
		///  %1 = ActivityName
		///  %2 = ActivityName Possessive: Username
		///  %3 = RegardingName
		///  %4 = RegardingName Possessive: Username, his, her, your
		///  %5 = Link to RegardingName's Wall
		///  %6 = his/her
		///  %7 = he/she
		///  %8 = RouteCode & Route
		if ($SQL->GetWhere('ActivityType', array('Name' => 'SignIn'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'SignIn', 'FullHeadline' => '%1$s signed in.', 'ProfileHeadline' => '%1$s signed in.'));
		if ($SQL->GetWhere('ActivityType', array('Name' => 'Join'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'Join', 'FullHeadline' => '%1$s joined.', 'ProfileHeadline' => '%1$s joined.'));
		if ($SQL->GetWhere('ActivityType', array('Name' => 'JoinInvite'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'JoinInvite', 'FullHeadline' => '%1$s accepted %4$s invitation for membership.', 'ProfileHeadline' => '%1$s accepted %4$s invitation for membership.'));
		if ($SQL->GetWhere('ActivityType', array('Name' => 'JoinApproved'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'JoinApproved', 'FullHeadline' => '%1$s approved %4$s membership application.', 'ProfileHeadline' => '%1$s approved %4$s membership application.'));
		$SQL->Replace('ActivityType', array('AllowComments' => '1', 'FullHeadline' => '%1$s created an account for %3$s.', 'ProfileHeadline' => '%1$s created an account for %3$s.'), array('Name' => 'JoinCreated'), TRUE);

		if ($SQL->GetWhere('ActivityType', array('Name' => 'AboutUpdate'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'AboutUpdate', 'FullHeadline' => '%1$s updated %6$s profile.', 'ProfileHeadline' => '%1$s updated %6$s profile.'));
		if ($SQL->GetWhere('ActivityType', array('Name' => 'WallComment'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'ShowIcon' => '1', 'Name' => 'WallComment', 'FullHeadline' => '%1$s wrote on %4$s %5$s.', 'ProfileHeadline' => '%1$s wrote:')); 
		if ($SQL->GetWhere('ActivityType', array('Name' => 'PictureChange'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'PictureChange', 'FullHeadline' => '%1$s changed %6$s profile picture.', 'ProfileHeadline' => '%1$s changed %6$s profile picture.'));
		//if ($SQL->GetWhere('ActivityType', array('Name' => 'RoleChange'))->NumRows() == 0)
		   $SQL->Replace('ActivityType', array('AllowComments' => '1', 'FullHeadline' => '%1$s changed %4$s permissions.', 'ProfileHeadline' => '%1$s changed %4$s permissions.', 'Notify' => '1'), array('Name' => 'RoleChange'), TRUE);
		if ($SQL->GetWhere('ActivityType', array('Name' => 'ActivityComment'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'ShowIcon' => '1', 'Name' => 'ActivityComment', 'FullHeadline' => '%1$s commented on %4$s %8$s.', 'ProfileHeadline' => '%1$s', 'RouteCode' => 'activity', 'Notify' => '1'));
		if ($SQL->GetWhere('ActivityType', array('Name' => 'Import'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'Import', 'FullHeadline' => '%1$s imported data.', 'ProfileHeadline' => '%1$s imported data.', 'Notify' => '1', 'Public' => '0'));
		//if ($SQL->GetWhere('ActivityType', array('Name' => 'Banned'))->NumRows() == 0)
		$SQL->Replace('ActivityType', array('AllowComments' => '0', 'FullHeadline' => '%1$s banned %3$s.', 'ProfileHeadline' => '%1$s banned %3$s.', 'Notify' => '0', 'Public' => '1'), array('Name' => 'Banned'), TRUE);
		//if ($SQL->GetWhere('ActivityType', array('Name' => 'Unbanned'))->NumRows() == 0)
		$SQL->Replace('ActivityType', array('AllowComments' => '0', 'FullHeadline' => '%1$s un-banned %3$s.', 'ProfileHeadline' => '%1$s un-banned %3$s.', 'Notify' => '0', 'Public' => '1'), array('Name' => 'Unbanned'), TRUE);

		$WallPostType = $SQL->GetWhere('ActivityType', array('Name' => 'WallPost'))->FirstRow(DATASET_TYPE_ARRAY);
		if (!$WallPostType) {
		   $WallPostTypeID = $SQL->Insert('ActivityType', array('AllowComments' => '1', 'ShowIcon' => '1', 'Name' => 'WallPost', 'FullHeadline' => '%3$s wrote on %2$s %5$s.', 'ProfileHeadline' => '%3$s wrote:'));
		   $WallCommentTypeID = $SQL->GetWhere('ActivityType', array('Name' => 'WallComment'))->Value('ActivityTypeID');

		   // Update all old wall comments to wall posts.
		   $SQL->Update('Activity')
		      ->Set('ActivityTypeID', $WallPostTypeID)
		      ->Set('ActivityUserID', 'RegardingUserID', FALSE)
		      ->Set('RegardingUserID', 'InsertUserID', FALSE)
		      ->Where('ActivityTypeID', $WallCommentTypeID)
		      ->Where('RegardingUserID is not null')
		      ->Put();
		}

		// Message Table
		$Construct->Table('Message')
			->PrimaryKey('MessageID')
		   ->Column('Content', 'text')
		   ->Column('Format', 'varchar(20)', TRUE)
		   ->Column('AllowDismiss', 'tinyint(1)', '1')
		   ->Column('Enabled', 'tinyint(1)', '1')
		   ->Column('Application', 'varchar(255)', TRUE)
		   ->Column('Controller', 'varchar(255)', TRUE)
		   ->Column('Method', 'varchar(255)', TRUE)
		   ->Column('AssetTarget', 'varchar(20)', TRUE)
			->Column('CssClass', 'varchar(20)', TRUE)
		   ->Column('Sort', 'int', TRUE)
		   ->Set($Explicit, $Drop);

		$Prefix = $SQL->Database->DatabasePrefix;

		if ($PhotoIDExists && !$PhotoExists) {
		   $Construct->Query("update {$Prefix}User u
		   join {$Prefix}Photo p
		      on u.PhotoID = p.PhotoID
		   set u.Photo = p.Name");
		}

		if ($PhotoIDExists) {
		   $Construct->Table('User')->DropColumn('PhotoID');
		}

		// This is a fix for erroneos unique constraint.
		if ($Construct->TableExists('Tag')) {
		   $Db = Gdn::Database();
		   $Px = Gdn::Database()->DatabasePrefix;

		   $DupTags = Gdn::SQL()
		      ->Select('Name')
		      ->Select('TagID', 'min', 'TagID')
		      ->Select('TagID', 'count', 'CountTags')
		      ->From('Tag')
		      ->GroupBy('Name')
		      ->Having('CountTags >', 1)
		      ->Get()->ResultArray();

		   foreach ($DupTags as $Row) {
		      $Name = $Row['Name'];
		      $TagID = $Row['TagID'];
		      // Get the tags that need to be deleted.
		      $DeleteTags = Gdn::SQL()->GetWhere('Tag', array('Name' => $Name, 'TagID <> ' => $TagID))->ResultArray();
		      foreach ($DeleteTags as $DRow) {
		         // Update all of the discussions to the new tag.
		         Gdn::SQL()->Options('Ignore', TRUE)->Put(
		            'TagDiscussion', 
		            array('TagID' => $TagID), 
		            array('TagID' => $DRow['TagID']));

		         // Delete the tag.
		         Gdn::SQL()->Delete('Tag', array('TagID' => $DRow['TagID']));
		      }
		   }
		}

		$Construct->Table('Tag')
			->PrimaryKey('TagID')
		   ->Column('Name', 'varchar(255)', FALSE, 'unique')
		   ->Column('Type', 'varchar(10)', TRUE, 'index')
		   ->Column('InsertUserID', 'int', TRUE, 'key')
		   ->Column('DateInserted', 'datetime')
		   ->Engine('InnoDB')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Log')
		   ->PrimaryKey('LogID')
		   ->Column('Operation', array('Delete', 'Edit', 'Spam', 'Moderate', 'Error'))
		   ->Column('RecordType', array('Discussion', 'Comment', 'User', 'Registration', 'Activity'), FALSE, 'index')
		   ->Column('RecordID', 'int', NULL, 'index')
		   ->Column('RecordUserID', 'int', NULL) // user responsible for the record
		   ->Column('RecordDate', 'datetime')
		   ->Column('RecordIPAddress', 'varchar(15)', NULL, 'index')
		   ->Column('InsertUserID', 'int') // user that put record in the log
		   ->Column('DateInserted', 'datetime') // date item added to log
		   ->Column('InsertIPAddress', 'varchar(15)', NULL)
		   ->Column('OtherUserIDs', 'varchar(255)', NULL)
		   ->Column('DateUpdated', 'datetime', NULL)
		   ->Column('ParentRecordID', 'int', NULL, 'index')
		   ->Column('Data', 'text', NULL) // the data from the record.
		   ->Column('CountGroup', 'int', NULL)
		   ->Engine('InnoDB')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Regarding')
		   ->PrimaryKey('RegardingID')
		   ->Column('Type', 'varchar(255)', FALSE, 'key')
		   ->Column('InsertUserID', 'int', FALSE)
		   ->Column('DateInserted', 'datetime', FALSE)
		   ->Column('ForeignType', 'varchar(32)', FALSE)
		   ->Column('ForeignID', 'int(11)', FALSE)
		   ->Column('OriginalContent', 'text', TRUE)
		   ->Column('ParentType', 'varchar(32)', TRUE)
		   ->Column('ParentID', 'int(11)', TRUE)
		   ->Column('ForeignURL', 'varchar(255)', TRUE)
		   ->Column('Comment', 'text', FALSE)
		   ->Column('Reports', 'int(11)', TRUE)
		   ->Engine('InnoDB')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Ban')
		   ->PrimaryKey('BanID')
		   ->Column('BanType', array('IPAddress', 'Name', 'Email'), FALSE, 'unique')
		   ->Column('BanValue', 'varchar(50)', FALSE, 'unique')
		   ->Column('Notes', 'varchar(255)', NULL)
		   ->Column('CountUsers', 'uint', 0)
		   ->Column('CountBlockedRegistrations', 'uint', 0)
		   ->Column('InsertUserID', 'int')
		   ->Column('DateInserted', 'datetime')
		   ->Engine('InnoDB')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Spammer')
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('CountSpam', 'usmallint', 0)
		   ->Column('CountDeletedSpam', 'usmallint', 0)
		   ->Set($Explicit, $Drop);
		
		$Construct->Table('Category');
		$CategoryExists = $Construct->TableExists();
		$PermissionCategoryIDExists = $Construct->ColumnExists('PermissionCategoryID');

		$LastDiscussionIDExists = $Construct->ColumnExists('LastDiscussionID');

		$Construct->PrimaryKey('CategoryID')
		   ->Column('ParentCategoryID', 'int', TRUE)
		   ->Column('TreeLeft', 'int', TRUE)
		   ->Column('TreeRight', 'int', TRUE)
		   ->Column('Depth', 'int', TRUE)
		   ->Column('CountDiscussions', 'int', '0')
		   ->Column('CountComments', 'int', '0')
		   ->Column('DateMarkedRead', 'datetime', NULL)
		   ->Column('AllowDiscussions', 'tinyint', '1')
		   ->Column('Archived', 'tinyint(1)', '0')
		   ->Column('Name', 'varchar(255)')
		   ->Column('UrlCode', 'varchar(255)', TRUE)
		   ->Column('Description', 'varchar(500)', TRUE)
		   ->Column('Sort', 'int', TRUE)
		   ->Column('PermissionCategoryID', 'int', '-1') // default to root.
		   ->Column('InsertUserID', 'int', FALSE, 'key')
		   ->Column('UpdateUserID', 'int', TRUE)
		   ->Column('DateInserted', 'datetime')
		   ->Column('DateUpdated', 'datetime')
		   ->Column('LastCommentID', 'int', NULL)
		   ->Column('LastDiscussionID', 'int', NULL)
		   ->Set($Explicit, $Drop);

		$RootCategoryInserted = FALSE;
		if ($SQL->GetWhere('Category', array('CategoryID' => -1))->NumRows() == 0) {
		   $SQL->Insert('Category', array('CategoryID' => -1, 'TreeLeft' => 1, 'TreeRight' => 4, 'InsertUserID' => 1, 'UpdateUserID' => 1, 'DateInserted' => Gdn_Format::ToDateTime(), 'DateUpdated' => Gdn_Format::ToDateTime(), 'Name' => 'Root', 'UrlCode' => '', 'Description' => 'Root of category tree. Users should never see this.', 'PermissionCategoryID' => -1));
		   $RootCategoryInserted = TRUE;
		}

		if ($Drop || !$CategoryExists) {
		   $SQL->Insert('Category', array('ParentCategoryID' => -1, 'TreeLeft' => 2, 'TreeRight' => 3, 'InsertUserID' => 1, 'UpdateUserID' => 1, 'DateInserted' => Gdn_Format::ToDateTime(), 'DateUpdated' => Gdn_Format::ToDateTime(), 'Name' => 'General', 'UrlCode' => 'general', 'Description' => 'General discussions', 'PermissionCategoryID' => -1));
		} elseif ($CategoryExists && !$PermissionCategoryIDExists) {
		   if (!C('Garden.Permissions.Disabled.Category')) {
		      // Existing installations need to be set up with per/category permissions.
		      $SQL->Update('Category')->Set('PermissionCategoryID', 'CategoryID', FALSE)->Put();
		      $SQL->Update('Permission')->Set('JunctionColumn', 'PermissionCategoryID')->Where('JunctionColumn', 'CategoryID')->Put();
		   }
		}

		if ($CategoryExists) {
		   $CategoryModel = new CategoryModel();
		   $CategoryModel->RebuildTree();
		   unset($CategoryModel);
		}

		// Construct the discussion table.
		$Construct->Table('Discussion');

		$FirstCommentIDExists = $Construct->ColumnExists('FirstCommentID');
		$BodyExists = $Construct->ColumnExists('Body');
		$LastCommentIDExists = $Construct->ColumnExists('LastCommentID');
		$LastCommentUserIDExists = $Construct->ColumnExists('LastCommentUserID');
		$CountBookmarksExists = $Construct->ColumnExists('CountBookmarks');

		$Construct
		   ->PrimaryKey('DiscussionID')
		   ->Column('Type', 'varchar(10)', NULL, 'index')
		   ->Column('ForeignID', 'varchar(30)', NULL, 'index') // For relating foreign records to discussions
		   ->Column('CategoryID', 'int', FALSE, 'key')
		   ->Column('InsertUserID', 'int', FALSE, 'key')
		   ->Column('UpdateUserID', 'int')
		   ->Column('LastCommentID', 'int', TRUE)
		   ->Column('Name', 'varchar(100)', FALSE, 'fulltext')
			->Column('Body', 'text', FALSE, 'fulltext')
			->Column('Format', 'varchar(20)', TRUE)
		   ->Column('Tags', 'varchar(255)', NULL)
		   ->Column('CountComments', 'int', '1')
		   ->Column('CountBookmarks', 'int', NULL)
		   ->Column('CountViews', 'int', '1')
		   ->Column('Closed', 'tinyint(1)', '0')
		   ->Column('Announce', 'tinyint(1)', '0')
		   ->Column('Sink', 'tinyint(1)', '0')
		   ->Column('DateInserted', 'datetime', NULL)
		   ->Column('DateUpdated', 'datetime')
		   ->Column('InsertIPAddress', 'varchar(15)', TRUE)
		   ->Column('UpdateIPAddress', 'varchar(15)', TRUE)
		   ->Column('DateLastComment', 'datetime', NULL, 'index')
			->Column('LastCommentUserID', 'int', TRUE)
			->Column('Score', 'float', NULL)
		   ->Column('Attributes', 'text', TRUE)
		   ->Column('RegardingID', 'int(11)', TRUE, 'index')
		   ->Engine('MyISAM')
		   ->Set($Explicit, $Drop);

		$Construct->Table('UserCategory')
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('CategoryID', 'int', FALSE, 'primary')
		   ->Column('DateMarkedRead', 'datetime', NULL)
		   ->Column('Unfollow', 'tinyint(1)', 0)
		   ->Set($Explicit, $Drop);

		// Allows the tracking of relationships between discussions and users (bookmarks, dismissed announcements, # of read comments in a discussion, etc)
		// Column($Name, $Type, $Length = '', $Null = FALSE, $Default = NULL, $KeyType = FALSE, $AutoIncrement = FALSE)
		$Construct->Table('UserDiscussion')
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('DiscussionID', 'int', FALSE, array('primary', 'key'))
			->Column('Score', 'float', NULL)
		   ->Column('CountComments', 'int', '0')
		   ->Column('DateLastViewed', 'datetime', NULL) // null signals never
		   ->Column('Dismissed', 'tinyint(1)', '0') // relates to dismissed announcements
		   ->Column('Bookmarked', 'tinyint(1)', '0')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Comment')
			->PrimaryKey('CommentID')
			->Column('DiscussionID', 'int', FALSE, 'key')
			->Column('InsertUserID', 'int', TRUE, 'key')
			->Column('UpdateUserID', 'int', TRUE)
			->Column('DeleteUserID', 'int', TRUE)
			->Column('Body', 'text', FALSE, 'fulltext')
			->Column('Format', 'varchar(20)', TRUE)
			->Column('DateInserted', 'datetime', NULL, 'key')
			->Column('DateDeleted', 'datetime', TRUE)
			->Column('DateUpdated', 'datetime', TRUE)
		   ->Column('InsertIPAddress', 'varchar(15)', TRUE)
		   ->Column('UpdateIPAddress', 'varchar(15)', TRUE)
			->Column('Flag', 'tinyint', 0)
			->Column('Score', 'float', NULL)
			->Column('Attributes', 'text', TRUE)
			->Engine('MyISAM')
			->Set($Explicit, $Drop);

		// Allows the tracking of already-read comments & votes on a per-user basis.
		$Construct->Table('UserComment')
		   ->Column('UserID', 'int', FALSE, 'primary')
		   ->Column('CommentID', 'int', FALSE, 'primary')
		   ->Column('Score', 'float', NULL)
		   ->Column('DateLastViewed', 'datetime', NULL) // null signals never
		   ->Set($Explicit, $Drop);

		// Add extra columns to user table for tracking discussions & comments
		$Construct->Table('User')
		   ->Column('CountDiscussions', 'int', NULL)
		   ->Column('CountUnreadDiscussions', 'int', NULL)
		   ->Column('CountComments', 'int', NULL)
		   ->Column('CountDrafts', 'int', NULL)
		   ->Column('CountBookmarks', 'int', NULL)
		   ->Set();

		$Construct->Table('Draft')
		   ->PrimaryKey('DraftID')
		   ->Column('DiscussionID', 'int', TRUE, 'key')
		   ->Column('CategoryID', 'int', TRUE, 'key')
		   ->Column('InsertUserID', 'int', FALSE, 'key')
		   ->Column('UpdateUserID', 'int')
		   ->Column('Name', 'varchar(100)', TRUE)
		   ->Column('Tags', 'varchar(255)', NULL)
		   ->Column('Closed', 'tinyint(1)', '0')
		   ->Column('Announce', 'tinyint(1)', '0')
		   ->Column('Sink', 'tinyint(1)', '0')
		   ->Column('Body', 'text')
		   ->Column('Format', 'varchar(20)', TRUE)
		   ->Column('DateInserted', 'datetime')
		   ->Column('DateUpdated', 'datetime', TRUE)
		   ->Set($Explicit, $Drop);

		// Insert some activity types
		///  %1 = ActivityName
		///  %2 = ActivityName Possessive
		///  %3 = RegardingName
		///  %4 = RegardingName Possessive
		///  %5 = Link to RegardingName's Wall
		///  %6 = his/her
		///  %7 = he/she
		///  %8 = RouteCode & Route

		// X added a discussion
		/*if ($SQL->GetWhere('ActivityType', array('Name' => 'NewDiscussion'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'NewDiscussion', 'FullHeadline' => '%1$s started a %8$s.', 'ProfileHeadline' => '%1$s started a %8$s.', 'RouteCode' => 'discussion', 'Public' => '0'));

		// X commented on a discussion.
		if ($SQL->GetWhere('ActivityType', array('Name' => 'NewComment'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'NewComment', 'FullHeadline' => '%1$s commented on a discussion.', 'ProfileHeadline' => '%1$s commented on a discussion.', 'RouteCode' => 'discussion', 'Public' => '0'));

		// People's comments on discussions
		if ($SQL->GetWhere('ActivityType', array('Name' => 'DiscussionComment'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'DiscussionComment', 'FullHeadline' => '%1$s commented on %4$s %8$s.', 'ProfileHeadline' => '%1$s commented on %4$s %8$s.', 'RouteCode' => 'discussion', 'Notify' => '1', 'Public' => '0'));

		// People mentioning others in discussion topics
		if ($SQL->GetWhere('ActivityType', array('Name' => 'DiscussionMention'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'DiscussionMention', 'FullHeadline' => '%1$s mentioned %3$s in a %8$s.', 'ProfileHeadline' => '%1$s mentioned %3$s in a %8$s.', 'RouteCode' => 'discussion', 'Notify' => '1', 'Public' => '0'));

		// People mentioning others in comments
		if ($SQL->GetWhere('ActivityType', array('Name' => 'CommentMention'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'CommentMention', 'FullHeadline' => '%1$s mentioned %3$s in a %8$s.', 'ProfileHeadline' => '%1$s mentioned %3$s in a %8$s.', 'RouteCode' => 'comment', 'Notify' => '1', 'Public' => '0'));

		// People commenting on user's bookmarked discussions
		if ($SQL->GetWhere('ActivityType', array('Name' => 'BookmarkComment'))->NumRows() == 0)
		   $SQL->Insert('ActivityType', array('AllowComments' => '0', 'Name' => 'BookmarkComment', 'FullHeadline' => '%1$s commented on your %8$s.', 'ProfileHeadline' => '%1$s commented on your %8$s.', 'RouteCode' => 'bookmarked discussion', 'Notify' => '1', 'Public' => '0'));

		$PermissionModel = Gdn::PermissionModel();
		$PermissionModel->Database = $Database;
		$PermissionModel->SQL = $SQL;

		// Define some global vanilla permissions.
		$PermissionModel->Define(array(
			'Vanilla.Settings.Manage',
			'Vanilla.Categories.Manage',
			'Vanilla.Spam.Manage'
			));

		// Define some permissions for the Vanilla categories.
		$PermissionModel->Define(array(
			'Vanilla.Discussions.View' => 1,
			'Vanilla.Discussions.Add' => 1,
			'Vanilla.Discussions.Edit' => 0,
			'Vanilla.Discussions.Announce' => 0,
			'Vanilla.Discussions.Sink' => 0,
			'Vanilla.Discussions.Close' => 0,
			'Vanilla.Discussions.Delete' => 0,
			'Vanilla.Comments.Add' => 1,
			'Vanilla.Comments.Edit' => 0,
			'Vanilla.Comments.Delete' => 0),
			'tinyint',
			'Category',
			'PermissionCategoryID'
			);

		if ($RootCategoryInserted) {
		   // Get the root category so we can assign permissions to it.
		   $GeneralCategoryID = -1; //$SQL->GetWhere('Category', array('Name' => 'General'))->Value('PermissionCategoryID', 0);

		   // Set the initial guest permissions.
		   $PermissionModel->Save(array(
		      'Role' => 'Guest',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.View' => 1
		      ), TRUE);

		   $PermissionModel->Save(array(
		      'Role' => 'Confirm Email',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.View' => 1
		      ), TRUE);

		   $PermissionModel->Save(array(
		      'Role' => 'Applicant',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.View' => 1
		      ), TRUE);

		   // Set the intial member permissions.
		   $PermissionModel->Save(array(
		      'Role' => 'Member',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.Add' => 1,
		      'Vanilla.Discussions.View' => 1,
		      'Vanilla.Comments.Add' => 1
		      ), TRUE);

		   // Set the initial moderator permissions.
		   $PermissionModel->Save(array(
		      'Role' => 'Moderator',
		      'Vanilla.Categories.Manage' => 1,
		      'Vanilla.Spam.Manage' => 1,
		      ), TRUE);

		   $PermissionModel->Save(array(
		      'Role' => 'Moderator',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.Add' => 1,
		      'Vanilla.Discussions.Edit' => 1,
		      'Vanilla.Discussions.Announce' => 1,
		      'Vanilla.Discussions.Sink' => 1,
		      'Vanilla.Discussions.Close' => 1,
		      'Vanilla.Discussions.Delete' => 1,
		      'Vanilla.Discussions.View' => 1,
		      'Vanilla.Comments.Add' => 1,
		      'Vanilla.Comments.Edit' => 1,
		      'Vanilla.Comments.Delete' => 1
		      ), TRUE);

		   // Set the initial administrator permissions.
		   $PermissionModel->Save(array(
		      'Role' => 'Administrator',
		      'Vanilla.Settings.Manage' => 1,
		      'Vanilla.Categories.Manage' => 1,
		      'Vanilla.Spam.Manage' => 1,
		      ), TRUE);

		   $PermissionModel->Save(array(
		      'Role' => 'Administrator',
		      'JunctionTable' => 'Category',
		      'JunctionColumn' => 'PermissionCategoryID',
		      'JunctionID' => $GeneralCategoryID,
		      'Vanilla.Discussions.Add' => 1,
		      'Vanilla.Discussions.Edit' => 1,
		      'Vanilla.Discussions.Announce' => 1,
		      'Vanilla.Discussions.Sink' => 1,
		      'Vanilla.Discussions.Close' => 1,
		      'Vanilla.Discussions.Delete' => 1,
		      'Vanilla.Discussions.View' => 1,
		      'Vanilla.Comments.Add' => 1,
		      'Vanilla.Comments.Edit' => 1,
		      'Vanilla.Comments.Delete' => 1
		      ), TRUE);
		}


		/*
		Apr 26th, 2010
		Removed FirstComment from :_Discussion and moved it into the discussion table.
		*/
		/*$Prefix = $SQL->Database->DatabasePrefix;

		if ($FirstCommentIDExists && !$BodyExists) {
		   $Construct->Query("update {$Prefix}Discussion, {$Prefix}Comment
		   set {$Prefix}Discussion.Body = {$Prefix}Comment.Body,
		      {$Prefix}Discussion.Format = {$Prefix}Comment.Format
		   where {$Prefix}Discussion.FirstCommentID = {$Prefix}Comment.CommentID");

		   $Construct->Query("delete {$Prefix}Comment
		   from {$Prefix}Comment inner join {$Prefix}Discussion
		   where {$Prefix}Comment.CommentID = {$Prefix}Discussion.FirstCommentID");
		}

		if (!$LastCommentIDExists || !$LastCommentUserIDExists) {
		   $Construct->Query("update {$Prefix}Discussion d
		   inner join {$Prefix}Comment c
		      on c.DiscussionID = d.DiscussionID
		   inner join (
		      select max(c2.CommentID) as CommentID
		      from {$Prefix}Comment c2
		      group by c2.DiscussionID
		   ) c2
		   on c.CommentID = c2.CommentID
		   set d.LastCommentID = c.CommentID,
		      d.LastCommentUserID = c.InsertUserID
		where d.LastCommentUserID is null");
		}

		if (!$CountBookmarksExists) {
		   $Construct->Query("update {$Prefix}Discussion d
		   set CountBookmarks = (
		      select count(ud.DiscussionID)
		      from {$Prefix}UserDiscussion ud
		      where ud.Bookmarked = 1
		         and ud.DiscussionID = d.DiscussionID
		   )");
		}

		// Update lastcommentid & firstcommentid
		if ($FirstCommentIDExists)
		   $Construct->Query("update {$Prefix}Discussion set LastCommentID = null where LastCommentID = FirstCommentID");

		// This is the final structure of the discussion table after removed & updated columns.
		if ($FirstCommentIDExists) {
		   $Construct->Table('Discussion')->DropColumn('FirstCommentID');
		   $Construct->Reset();
		}

		$Construct->Table('TagDiscussion')
		   ->Column('TagID', 'int', FALSE, 'primary')
		   ->Column('DiscussionID', 'int', FALSE, 'primary')
		   ->Engine('InnoDB')
		   ->Set($Explicit, $Drop);

		$Construct->Table('Tag')
		   ->Column('CountDiscussions', 'int', 0)
		   ->Set();

		$Categories = Gdn::SQL()->Where("coalesce(UrlCode, '') =", "''", FALSE, FALSE)->Get('Category')->ResultArray();
		foreach ($Categories as $Category) {
		   $UrlCode = Gdn_Format::Url($Category['Name']);
		   if (strlen($UrlCode) > 50)
		      $UrlCode = $Category['CategoryID'];

		   Gdn::SQL()->Put(
		      'Category',
		      array('UrlCode' => $UrlCode),
		      array('CategoryID' => $Category['CategoryID']));
		}

		// Moved this down here because it needs to run after GDN_Comment is created
		if (!$LastDiscussionIDExists) {
		   $SQL->Update('Category c')
		      ->Join('Comment cm', 'c.LastCommentID = cm.CommentID')
		      ->Set('c.LastDiscussionID', 'cm.DiscussionID', FALSE, FALSE)
		      ->Put();
		}*/
		
		return TRUE;
		
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