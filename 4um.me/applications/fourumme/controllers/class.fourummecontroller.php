<?php if (!defined('APPLICATION')) exit();

/**
  * FourumMe Controller
  * 
  * @package FourumMe
  */

class FourumMeController extends Gdn_Controller { 
	
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
	
	
	
} //end Class FourumMe Controller