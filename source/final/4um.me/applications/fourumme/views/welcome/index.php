<?php if (!defined('APPLICATION')) exit();
/**
  * Welcome Index View
  * 
  * @package FourumMe
  */
?>

<div class="welcomeTop">
	<div class="content">
		
		<?php 
		echo Img('/applications/fourumme/design/img/logo.png', '4um.me logo'); 
		echo Anchor('Sign In', '/entry/signin', array('class'=>'Popup', 'id'=>'signInLink')); ?>
	
		<div class="clear"></div>
		
		<div id="info">

			<h1>The Simple Forum Solution</h1>

			<h2>4um.me is a free custom forum solution.  With one login you can browse hundreds of existing forums, or create and manage a forum of your own.</h2>

		</div> <!-- info -->
		
		<div id="register">
			
			<p id="registerTitle"><?php echo T("Sign Up Now!") ?></p>
			   
			<?php
			
			//Define Text and path for terms of service radio button
			$TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
			$TermsOfServiceText = sprintf(T('I agree to the <a id="TermsOfService" class="Popup" target="terms" href="%s">terms of service</a>'), Url($TermsOfServiceUrl));
			
			//open registration form  
			echo $this->Form->Open(array('Action' => Url('/fourumme/welcome/index'), 'id' => 'Form_User_Register'));
			echo $this->Form->Errors();
			
			//Email Input field & Error Text
            echo $this->Form->TextBox('Email');
            echo '<span id="EmailUnavailable" class="Incorrect" style="display: none;">'.T('Email Unavailable').'</span>';

			//Username Input field & Error Text
            echo $this->Form->TextBox('Name');
            echo '<span id="NameUnavailable" class="Incorrect" style="display: none;">'.T('Name Unavailable').'</span>'; ?>
			
			<!-- dummy password input fields for text display -->
			<input type="text" id="fakePass"/>
			
			<?php echo $this->Form->Input('Password', 'password'); ?>
			
			<input type="text" id="confFakePass"/>
			
			<?php // Password Confirmation Input & Error Text
            echo $this->Form->Input('PasswordMatch', 'password');
            echo '<span id="PasswordsDontMatch" class="Incorrect" style="display: none;">'.T("Passwords don't match").'</span>'; 
			
			echo $this->Form->CheckBox('TermsOfService', $TermsOfServiceText, array('value' => '1')); 
			
			echo $this->Form->Button('Get Started');
			
			echo $this->Form->Close(); ?>
	
		</div> <!-- Register -->

		<div class="clear"></div>	
		
	</div> <!-- top content -->
</div> <!-- welcomeTop -->

<div class="content">
	
		<ul class="feature">
			<li><? echo Img('/applications/fourumme/design/img/browse.png', 'browse icon'); ?></li>
			<li><h4>Browse</h4></li>
			<li><p>View hundreds of user created forums covering a wide variety of categories, and interact with 		  all your favorite forums with a single login.</p></li>
		</ul>
		
		<ul class="feature">
			<li><? echo Img('/applications/fourumme/design/img/create.png', 'create icon'); ?></li>
			<li><h4>Create</h4></li>
			<li><p>Can't find and existing forum on your favorite categories?  Create your own! In just a few simple steps you'll be ready to make your first topic, and the best part, it's free! </p></li>
		</ul>
		
		<ul class="feature">
			<li><? echo Img('/applications/fourumme/design/img/manage.png', 'manage icon'); ?></li>
			<li><h4>Manage</h4></li>
			<li><p>The custom admin panel allows for a personalized forum without all the unnecessary features. Add a custom logo, appoint moderators, ban users, and customize your layout.</p></li>
		</ul>  
		
		<div class="clear"></div>
	
</div> <!-- bottom content -->