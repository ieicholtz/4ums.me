<?php if (!defined('APPLICATION')) exit();
/**
  * Contact Controller
  * 
  * @package FourumMe
  */

class ContactController extends FourumMeController { 
	
	public $Uses = array('Database', 'Form', 'FeedbackModel', 'ForumModel');
	
	Public $Validation;
	
	public function Initialize(){
		
		$this->MasterView = 'main';
		$this->AddCssFile('main.css');
		
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		
		$this->Success = '';
		
		$Session = Gdn::Session();
		
		$FeedbackModel = new FeedbackModel();
		$ForumModel = new ForumModel();
		
		$recent = $ForumModel->GetRecent();
		
		$this->Recent = $recent;
		$this->CurrentPage = 'contact';
	
		$this->Form->SetModel($FeedbackModel);
		
		$this->Form->AddHidden('DatePosted', date('Y-m-d H:00'));
		
		$this->Form->AddHidden('UserID', 0);
		
/*================================================================== 
FUTURE: move to seperate function, or create custom entry controller
===================================================================*/

		 //check form
		if ($this->Form->IsPostBack() === TRUE) {
		
		$this->FeedbackModel->DefineSchema();
	    $this->FeedbackModel->Validation->ApplyRule('Name', 'Required');
		$this->FeedbackModel->Validation->ApplyRule('Email', 'Email');
	    $this->FeedbackModel->Validation->ApplyRule('Message', 'Required', T('You must include a message'));
		
		if($Session->UserID > 0){
				
			$this->Form->SetFormValue('UserID', $Session->UserID);
		}	
		
		$fields = $this->Form->FormValues();
		
		$sent = $this->Form->Save($fields);
		
		if($sent){
			$this->Success = 'Message Sent, Thank You!';
		}
		
		}
		
		$this->Render();
		
		

	}//end Index()
	
	
} //end class Contact Controller