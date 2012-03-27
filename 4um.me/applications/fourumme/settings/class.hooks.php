<?php if (!defined('APPLICATION')) exit(); 

/**
  * FourumMe Hooks
  * 
  * @package FourumMe
  */

class FourumMeHooks implements Gdn_IPlugin {
  
   public function Setup() {
      
      include(PATH_APPLICATIONS . DS . 'fourumme' . DS . 'settings' . DS . 'structure.php');
	  $Save = array( 
		'Routes.DefaultController' => 'fourumme/welcome',
		'FourumMe.Setup' => TRUE,
		'Garden.Registration.Method' => 'Basic',
		'Garden.Registration.ConfirmEmail' => FALSE
		);
	  
      
      SaveToConfig($Save);
   }

	public function AdminController_Render_Before(&$Sender) {
      
		
    }
   
   /**
    * Special function automatically run upon clicking 'Disable' on your application.
    */
   public function OnDisable() {
     
   }
   
  
   public function CleanUp() {
      
   }

}//end FourumMe Hooks