<?php if (!defined('APPLICATION')) exit();

/**
  * Forum Controller
  * 
  * @package FourumMe
  */

class AdminController extends FourumMeController {
	public $Uses = array('Database', 'Form', 'ForumModel');
	
	public $Title;
	
	public function Initialize(){
		
		$this->MasterView = 'forumadmin';
		$this->AddCssFile('site.css');
		$this->AddCssFile('admin.css');
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		$Session = Gdn::Session();
		if(!$Session->UserID > 0)
			Redirect('fourumme/welcome');
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		
		$this->Title = $Name;
		$this->Path = $Forum;
		
		/*$this->Recent = $recent;

		$this->CurrentPage = 'about';*/
		
		$this->Render();

	}//end Index()
	
	public function Banner(){
		$Session = Gdn::Session();
		if(!$Session->UserID > 0)
			Redirect('fourumme/welcome');
		
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		$ForumID = $ForumInfo['ForumID'];
		
		$this->Form->SetModel($ForumModel);
		
		$this->Title = $Name;
		$this->Path = $Forum;
		
		if ($this->Form->IsPostBack() === TRUE){

		$this->Form->SetValidationResults($ForumModel->ValidationResults());

		$fields = $this->Form->FormValues();
		
		$ForumName = $fields['Name'];
		
		$ValidName = $ForumModel->CheckName($ForumName);
		
		if(!$ValidName)
			$this->Form->AddError('Name Not Available');

			$NameChanged = $ForumModel->UpdateName($ForumName, $Forum);
			
		if($NameChanged)
			Redirect('/admin/banner?Forum='.$Forum);
		}
		
		$this->View = 'banner';
		
		$this->Render();
		
	}
	
	public function Categories(){
		$Session = Gdn::Session();
		if(!$Session->UserID > 0)
			Redirect('fourumme/welcome');
		
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		
		$this->Title = $Name;
		$this->Path = $Forum;
		
		/*$this->Recent = $recent;

		$this->CurrentPage = 'about';*/
		
		$this->View = 'categories';
		
		$this->Render();

	}//end Index()
	
	public function Moderators(){
		$Session = Gdn::Session();
		if(!$Session->UserID > 0)
			Redirect('fourumme/welcome');
		
		
		$Forum = $this->Request->Get('Forum');
		
		$ForumModel = new ForumModel();
		
		$PathResult = $ForumModel->GetByPath($Forum);
		$ForumInfo = $PathResult[0];
		
		$Name = $ForumInfo['Name'];
		
		$this->Title = $Name;
		$this->Path = $Forum;
		
		/*$this->Recent = $recent;

		$this->CurrentPage = 'about';*/
		
		$this->View = 'moderators';
		
		$this->Render();
	}
	
	public function Ban(){
			$Session = Gdn::Session();
			if(!$Session->UserID > 0)
				Redirect('fourumme/welcome');


			$Forum = $this->Request->Get('Forum');

			$ForumModel = new ForumModel();

			$PathResult = $ForumModel->GetByPath($Forum);
			$ForumInfo = $PathResult[0];

			$Name = $ForumInfo['Name'];

			$this->Title = $Name;
			$this->Path = $Forum;

			/*$this->Recent = $recent;

			$this->CurrentPage = 'about';*/

			$this->View = 'ban';

			$this->Render();
	}
	
	public function Stats(){
			$Session = Gdn::Session();
			if(!$Session->UserID > 0)
				Redirect('fourumme/welcome');


			$Forum = $this->Request->Get('Forum');

			$ForumModel = new ForumModel();

			$PathResult = $ForumModel->GetByPath($Forum);
			$ForumInfo = $PathResult[0];

			$Name = $ForumInfo['Name'];

			$this->Title = $Name;
			$this->Path = $Forum;

			/*$this->Recent = $recent;

			$this->CurrentPage = 'about';*/

			$this->View = 'stats';

			$this->Render();
	}
};