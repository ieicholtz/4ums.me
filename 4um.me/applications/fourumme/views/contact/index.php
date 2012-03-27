<?php if (!defined('APPLICATION')) exit();
/**
  * Contact Index View
  * 
  * @package FourumMe
  */

?>

<div class="top">

	<div class="content">
		
		<div id="contactInfo">
			
			<h2>Contact Info</h2>
			
			<div id="contactAbout">
			
			<h3>Tell us what you think!</h3>
			
			<p>4um.me is a brand new application designed to make forum interaction and creation a simple process. Your questions, comments, and bug reporting is vital to future growth and improvements. We highly value and appreciate all feedback.</p>
			
			</div>
			
			<ul>
				<li>
					<h4>4um.me</h4>
					<p class="listInfo"><strong>Email:</strong> development@4um.me</p>
					<p class="listInfo"><strong>Phone:</strong> (407)-558-8267</p>
					<p class="listInfo"><strong>Location:</strong> Orlando, Fl</p>
				</li>
				
				<li>
					<h4>Developer</h4>
					<p class="listInfo"><strong>Email:</strong>ieicholtz@gmail.com</p>
					<p class="listInfo"><strong>Phone:</strong>(407)-558-8267</p>
					<p class="listInfo"><strong>Location:</strong>Orlando, Fl</p>
				</li>
			</ul>
			
			<div class="clear"></div>
		
		</div>
		
		<div id="contactUs">
			
			<h2>Contact Us</h2>
			
			<?php
			
			echo '<p class="success">'.$this->Success.'</p>';

			echo $this->Form->Open();

			echo $this->Form->Errors();

			echo $this->Form->TextBox('Name');

			echo $this->Form->TextBox('Email');
			
			echo $this->Form->TextBox('Message', array('MultiLine' => TRUE));

			echo $this->Form->Close('Send');

			?>
			
		</div>
		
		<div class="clear"></div>
		
	</div> <!-- content -->

</div> <!-- top -->

<div class="content">
	
	<?php /*================================================================== 
	      FUTURE: turn this into a module to eliminate repetitve calls
	       ===================================================================*/ ?>
	
	<h3>Recent Forums</h3>
	
	<?php print_r(PATH_ROOT); ?>
	
	<ul class="recentForums">
	
		<li>
			<?php for($i = 0; $i < 5; $i++){
				$recent = $this->Recent[$i];
				echo Anchor($recent['Name'], 'forum?Forum='.$recent['Path'], 'rForum', array('alt'=>$recent['Path']));
			}?>
		</li>
		<li>
			<?php for($i = 5; $i < 10; $i++){
				$recent = $this->Recent[$i];
				echo Anchor($recent['Name'], 'forum?Forum='.$recent['Path'], 'rForum', array('alt'=>$recent['Path']));
			}?>
		</li>
		<li>
			<?php for($i = 10; $i < 15; $i++){
				$recent = $this->Recent[$i];
				echo Anchor($recent['Name'], 'forum?Forum='.$recent['Path'], 'rForum', array('alt'=>$recent['Path']));
			}?>
		</li>
		<li>
			<?php for($i = 15; $i < 20; $i++){
				$recent = $this->Recent[$i];
				echo Anchor($recent['Name'], 'forum?Forum='.$recent['Path'], 'rForum', array('alt'=>$recent['Path']));
			}?>
		</li>
	</ul>
	
</div> <!-- content -->