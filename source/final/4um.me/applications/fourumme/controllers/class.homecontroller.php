<?php if (!defined('APPLICATION')) exit();
/**
  * Home Controller
  * 
  * @package FourumMe
  */

class HomeController extends FourumMeController { 
	
	public $Uses = array('Database', 'Form', 'ForumModel');
	
	public $ForumModel;
	
	public $UserForums;
	
	public function Initialize(){
		
		$this->MasterView = 'main';
		$this->AddCssFile('main.css');
		
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		
		$Session = Gdn::Session();
		$UserID = $Session->UserID;
		
		if(!$UserID > 0){
			Redirect('welcome');
		}

		$ForumModel = new ForumModel();
		
		$UserForums = $ForumModel->GetByUserID($UserID);
		
		if($UserForums == FALSE){
			$this->Create();
		}else{
			$this->Dashboard($UserForums);
		}
	

	}//end Index()
	
	public function Create() {
		
	//Set UserID variable
		$Session = Gdn::Session();
		$UserID = $Session->UserID;

		$ForumModel = new ForumModel();
		
	//Make sure User is logged in
		if(!$UserID > 0){
			Redirect('welcome');
		}
		
	//set user forms list and update most recent
		$this->Recent = $ForumModel->GetRecent();
		
	//Add neccesary fields
		$this->Form->SetModel($ForumModel);
		$this->Form->AddHidden('UserID', $UserID);
		$this->Form->AddHidden('DateCreated', date('Y-m-d H:00'));
	
		if ($this->Form->IsPostBack() === TRUE){

		$this->Form->SetValidationResults($ForumModel->ValidationResults());

	//set vars for form values
		$fields = $this->Form->FormValues();
		$Path = strtolower($this->Form->GetFormValue('Path'));
		$Name = $this->Form->GetFormValue('Name');
		
	//make sure path is all lowercase
		$this->Form->SetFormValue('Path', $Path);

	//check form fields to make sure they arent already in use
		$ValidPath = $ForumModel->CheckPath($Path);
		$ValidName = $ForumModel->CheckName($Name);

	//trigger errors if not unique
		if(!$ValidPath)
			$this->Form->AddError('Path Not Available');

		if(!$ValidName)
			$this->Form->AddError('Name Not Available');
	
	//save form values to database
		$Valid = $this->Form->Save($fields);

	//create new database
		if($Valid)
			$ValidDB = $ForumModel->CreateDatabase($fields);
	//setup the database
		if($ValidDB)
			$DBSetup = $ForumModel->DatabaseSetup($Path);
	//set custom route
			if($DBSetup)

			Gdn::Router()->SetRoute($Path,'forums', 'Internal' );

			$Session->Stash('Path', $Path, FALSE);

			Redirect('dashboard/settings');
		
		
	}
		
	$this->CurrentPage = 'home';
	$this->View = 'new';
	$this->Render();
	
	}//end Create();
	
	public function Dashboard($forums) {
	
	//Set UserID variable
		$Session = Gdn::Session();
		$UserID = $Session->UserID;
			
		$ForumModel = new ForumModel();

    //Make sure User is logged in
		if(!$UserID > 0){
			Redirect('welcome');
		}
			
	//set user forms list and update most recent
		$this->Forums = $forums;
		$this->Recent = $ForumModel->GetRecent();

    //Add neccesary fields
		$this->Form->SetModel($ForumModel);
		$this->Form->AddHidden('UserID', $UserID);
		$this->Form->AddHidden('DateCreated', date('Y-m-d H:00'));

		if ($this->Form->IsPostBack() === TRUE) {

		$this->Form->SetValidationResults($ForumModel->ValidationResults());

	//set vars for form values
		$fields = $this->Form->FormValues();
		$Path = strtolower($this->Form->GetFormValue('Path'));
		$Name = $this->Form->GetFormValue('Name');
	
	//make sure path is all lowercase
		$this->Form->SetFormValue('Path', $Path);
		
	
		
	//check form fields to make sure they arent already in use
		$ValidPath = $ForumModel->CheckPath($Path);
		$ValidName = $ForumModel->CheckName($Name);
		
	//trigger errors if not unique
		if(!$ValidPath)
			$this->Form->AddError('Path Not Available');
		
		if(!$ValidName)
			$this->Form->AddError('Name Not Available');
			
	//save form values to database
		$Valid = $this->Form->Save($fields);
	
	//create new database
		if($Valid)
			$ValidDB = $ForumModel->CreateDatabase($fields);
	//setup the database
		if($ValidDB)
			$DBSetup = $ForumModel->DatabaseSetup($Path);
	//set custom route
		if($DBSetup)
			
		Gdn::Router()->SetRoute($Path,'forums', 'Internal' );
		
		
		Redirect('dashboard/settings');
			
	}
	
	
	
	$this->Temp = $Session->Stash();
	$this->CurrentPage = 'home';
	$this->View = 'index';
	$this->Render();
	
	}//end Dashboard();
		
	/*Public Function Create(){
		
		if($this->Form->IsPostBack()){
			
			$ForumModel = new ForumModel();
			
			$this->Form->SetModel($ForumModel);
			
			$fields = $this->Form->FormValues();
			
			$created = $ForumModel->Create($fields);
			
			
			if($created){
				
				$this->Form->Save($test);
				
	            /*$Database = Gdn::Database($newDB);
	            $Database->Init();
	            $Drop = FALSE; // Gdn::Config('Garden.Version') === FALSE ? TRUE : FALSE;
	            $Explicit = FALSE;
	            try {
	               include(PATH_APPLICATIONS . DS . 'vanilla' . DS . 'settings' . DS . 'structure.php');
	            } catch (Exception $ex) {
	               $this->Form->AddError($ex);
	            }

	            if ($this->Form->ErrorCount() > 0)
	               return FALSE;
			}
			
			$this->RedirectTo('main');
			
			
		}
		
		
		
	}*/
	
} //end class Home Controller