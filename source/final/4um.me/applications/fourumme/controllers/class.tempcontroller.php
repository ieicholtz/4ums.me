<?php 

//TEMPORARY FOR APP LINKING

/**
  * Temp Controller
  * 
  * @package TEMP
  */

class TempController extends Gdn_Controller { 
	
		public $Uses = array('Database', 'Form', 'FeedbackModel', 'ForumModel');
	
	public function Initialize() {
		  
		  //Default Head Module
		  $this->Head = new HeadModule($this);
		
		  //Css Files
		  $this->AddCssFile('site.css');
		
		  //Js Files
		  $this->AddJsFile('jquery.js');
	      $this->AddJsFile('jquery.livequery.js');
	      $this->AddJsFile('jquery.form.js');
	      $this->AddJsFile('jquery.popup.js');
	      $this->AddJsFile('jquery.gardenhandleajaxform.js');
	      $this->AddJsFile('global.js');
		  $this->AddJsFile('main.js');
		  $this->AddJsFile('temp.js');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
		
	parent::Initialize();
	   
	} //end Initialize()
	
	public function Dashboard(){
		
		$Path = $this->Request->Get('Path');
		
		$ForumModel = new ForumModel();
		
		$values = $ForumModel->GetByPath($Path);
		
		foreach($values as $value){
			$Name = $value['Name'];
			$User = $value['UserID'];
		}
		
		SaveToConfig(array('Forum.User'=> $User, 'Forum.Title' => $Name, 'ForumDatabase.Name' => $Path));
			
		
		Redirect('dashboard/settings');
		
	}
	
	public function Discussions(){
		
		$Path = $this->Request->Get('Path');
		
		$ForumModel = new ForumModel();
		
		$values = $ForumModel->GetByPath($Path);
		
		foreach($values as $value){
			$Name = $value['Name'];
			$User = $value['UserID'];
		}
		
		SaveToConfig(array('Forum.User'=> $User, 'Forum.Title' => $Name, 'ForumDatabase.Name' => $Path));
			
		
		Redirect('discussions');
		
	}
	
	
	
} //end Class FourumMe Controller