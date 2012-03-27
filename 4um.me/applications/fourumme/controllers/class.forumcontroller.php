<?php if (!defined('APPLICATION')) exit();

/**
  * Forum Controller
  * 
  * @package FourumMe
  */

class ForumController extends FourumMeController {
	public $Uses = array('Database', 'Form', 'ForumModel', 'CategoryModel', 'DiscussionModel', 'UserModel');
	
	public $Title;
	
	public function Initialize(){
		
		$this->MasterView = 'forum';
		$this->AddCssFile('style.css');
		$this->AddCssFile('site.css');
		$this->AddCssFile('forum.css');
		//$this->AddCssFile('main.css');
		
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		$CategoryModel = new CategoryModel();
		$DiscussionModel = new DiscussionModel();
		$UserModel = new UserModel();
		
		$Categories = $CategoryModel->GetByName($Forum);
		$Discussions = $DiscussionModel->GetByName($Forum);
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		$User = $ForumInfo['UserID'];
		$this->Path = $Forum;
		$this->User = $User;
		$this->Title = $Name;
		
		$this->Categories = $Categories;
		
		$this->Discussions = $Discussions;
		
		$this->Render();

	}//end Index()
	
	public function PostDiscussion(){
		$Session = Gdn::Session();
		$UserID = $Session->UserID;
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		$DiscussionModel = new DiscussionModel();
		$CategoryModel = new CategoryModel();
		
		$Categories = $CategoryModel->GetByName($Forum);
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		$User = $ForumInfo['UserID'];
		$this->Path = $Forum;
		$this->User = $User;
		$this->Title = $Name;
		
		$this->Categories = $Categories;
		
		$this->Form->SetModel($DiscussionModel);
		
		$this->Form->AddHidden('InsertUserID', $UserID);
		$this->Form->AddHidden('DateInserted', date('Y-m-d H:00'));
		$this->Form->AddHidden('UpdateUserName', $Session->User->Name);
		
		if ($this->Form->IsPostBack() === TRUE){
			
		$this->Form->SetValidationResults($DiscussionModel->ValidationResults());
		
		$fields = $this->Form->FormValues();
		
		$CategoryID = $fields['CategoryID'] + 1;
		
		$Valid = $this->Form->Save($fields);
		
		if($Valid)
			$AddDiscussion = $DiscussionModel->AddDiscussion($Forum, $fields);
			
			Redirect('/forum?Forum='.$Forum);
		
		}
		$this->View = 'postdiscussion';
		
		$this->Render();

	}//end Index()
};