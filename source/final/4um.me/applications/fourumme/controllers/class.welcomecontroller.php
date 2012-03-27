<?php if (!defined('APPLICATION')) exit();

/**
  * Welcome Controller
  * 
  * @package FourumMe
  */

class WelcomeController extends FourumMeController { 
	
	//Included Models
	public $Uses = array('Database', 'Form', 'UserModel');
	
	//Form Model
	public $Form;
	
	//User Model
	public $UserModel;
	
	//Default for Username Validation
	public $UsernameError = ''; 
	
	public function  __construct() {
		parent::__construct();
		
		//Set Username Error string  
	    $this->UsernameError = T('UsernameError', 'Username can only contain letters, numbers, underscores, and must be between 3 and 20 characters long.');

	} // end _construct();
	
	public function Initialize(){
		
		//Set Masterview
		$this->MasterView = 'welcome';
		
		//Css Files
		$this->AddCssFile('welcome.css');
		
		//Js class files
		$this->AddJsFile('jquery.js');
		$this->AddJsFile('jquery.livequery.js');
		$this->AddJsFile('jquery.form.js');
		$this->AddJsFile('jquery.popup.js');
		$this->AddJsFile('jquery.gardenhandleajaxform.js');
		
		//Js Files
		$this->AddJsFile('welcome.js');
		$this->AddJsFile('entry.js');
		$this->AddJsFile('global.js');
		$this->AddJsFile('registration.js');
	
		parent::Initialize();
	
	}//end Initialize()
	
	public function Index(){
		
		$session = Gdn::Session();
		
		if($session->UserID > 0){
			Redirect('fourumme/home');
		}
	
		$this->FireEvent("Register");

	    $this->Form->SetModel($this->UserModel);

	    $this->GenderOptions = array(
	         'm' => T('Male'),
	         'f' => T('Female')
	      );

	    $this->Form->AddHidden('ClientHour', date('Y-m-d H:00')); // Use the server's current hour as a default
	    $this->Form->AddHidden('Target', $this->Target());

		$this->CurrentPage = 'welcome';
		
/*================================================================== 
FUTURE: move to seperate function, or create custom entry controller
===================================================================*/
		
		  //check form
		 if ($this->Form->IsPostBack() === TRUE) {


	         $this->UserModel->DefineSchema();
	         $this->UserModel->Validation->ApplyRule('Name', 'Username', $this->UsernameError);
	         $this->UserModel->Validation->ApplyRule('TermsOfService', 'Required', T('You must agree to the terms of service.'));
	         $this->UserModel->Validation->ApplyRule('Password', 'Required');
	         $this->UserModel->Validation->ApplyRule('Password', 'Match');
	
			try {
				
				//change user role
	            $Values = $this->Form->FormValues();
	            unset($Values['Roles']);
	            $AuthUserID = $this->UserModel->Register($Values);
				
				//check auth & start session
	            if (!$AuthUserID) {
	               $this->Form->SetValidationResults($this->UserModel->ValidationResults());
	            } else {
	               // The user has been created successfully, so sign in now.
	               Gdn::Session()->Start($AuthUserID);

	               if ($this->Form->GetFormValue('RememberMe'))
	                  Gdn::Authenticator()->SetIdentity($AuthUserID, TRUE);

	               try {
	                  $this->UserModel->SendWelcomeEmail($AuthUserID, '', 'Register');
	               } catch (Exception $Ex) {
	               }

	               $this->FireEvent('RegistrationSuccessful');

	               $Route = $this->RedirectTo();
	               if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
	                  $this->RedirectUrl = Url($Route);
	               } else {
	                  if ($Route !== FALSE)
	                     Redirect($Route);
	               }
	            }
	         } catch (Exception $Ex) {
	            $this->Form->AddError($Ex);
	         }
	      }
	      $this->Render();

	}//end Index()
	
	public function RedirectTo() {
      $Target = $this->Target();
		return $Target == '' ? Gdn::Router()->GetDestination('DefaultController') : $Target;
   }
		
	public function Target($Target = FALSE) {
	      if ($Target === FALSE) {
	         $Target = $this->Form->GetFormValue('Target', FALSE);
	         if (!$Target)
	            $Target = $this->Request->Get('Target', '/');
	      }

	      // Make sure that the target is a valid url.
	      if (!preg_match('`(^https?://)`', $Target)) {
	         $Target = '/'.ltrim($Target, '/');
	      } else {
	         $MyHostname = parse_url(Gdn::Request()->Domain(),PHP_URL_HOST);
	         $TargetHostname = parse_url($Target, PHP_URL_HOST);

	         // Only allow external redirects to trusted domains.
	         $TrustedDomains = C('Garden.TrustedDomains');
				if (!is_array($TrustedDomains))
					$TrustedDomains = array();

				// Add this domain to the trusted hosts
				$TrustedDomains[] = $MyHostname;
	         $Sender->EventArguments['TrustedDomains'] = &$TrustedDomains;
	         $this->FireEvent('BeforeTargetReturn');

				if (count($TrustedDomains) == 0) {
					// Only allow http redirects if they are to the same host name.
					if ($MyHostname != $TargetHostname)
						$Target = '';
				} else {
					// Loop the trusted domains looking for a match
					$Match = FALSE;
					foreach ($TrustedDomains as $TrustedDomain) {
						if (StringEndsWith($TargetHostname, $TrustedDomain, TRUE))
							$Match = TRUE;
					}
					if (!$Match)
						$Target = '';
				}
	      }
	      return $Target;
	   }
		
} //end class Welcome Controller