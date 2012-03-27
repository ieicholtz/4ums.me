<?php if (!defined('APPLICATION')) exit();
/**
  * Feedback Model
  * 
  * @package FourumMe
  */



class FeedbackModel extends Gdn_Model { 
	
	public function __construct() {
	      parent::__construct('Feedback');
	}
	
	public function Save($FormPostValues){
		
		$this->DefineSchema();
		
		$ReportID = ArrayValue('ReportID', $FormPostValues);
		$UserID = ArrayValue('UserID', $FormPostValues);
		$Name = ArrayValue('Name', $FormPostValues);
		$Email = ArrayValue('Email', $FormPostValues);
		$Message = ArrayValue('Message', $FormPostValues);
		$DatePosted = ArrayValue('DatePosted', $FormPostValues);
		
		$Insert = $ReportID > 0 ? FALSE : TRUE;
	    if ($Insert)
	       $this->AddInsertFields($FormPostValues);
	
	    $this->Validation->ApplyRule('Name', 'Required');
		$this->Validation->ApplyRule('Email', 'Email');
		$this->Validation->ApplyRule('Message', 'Required');
		
		if($this->Validate($FormPostValues)){
			
			$ReportID = $this->Insert($FormPostValues);
		}
		
		return $ReportID;
		
	}
	

	
	
} //end class Feedback Model