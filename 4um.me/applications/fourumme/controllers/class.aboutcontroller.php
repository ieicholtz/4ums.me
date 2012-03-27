<?php if (!defined('APPLICATION')) exit();

/**
  * About Controller
  * 
  * @package FourumMe
  */

class AboutController extends FourumMeController { 
	
	public $Uses = array('Database', 'Form', 'ForumModel');
	
	
	public function Initialize(){
		
		$this->MasterView = 'main';
		$this->AddCssFile('main.css');
		
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		
		$ForumModel = new ForumModel();
		
		$recent = $ForumModel->GetRecent();
		
		$this->Recent = $recent;

		$this->CurrentPage = 'about';
		
		$this->Render();

	}//end Index()
	
} //end class About Controller