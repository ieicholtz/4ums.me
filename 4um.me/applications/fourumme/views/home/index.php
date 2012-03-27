<?php if (!defined('APPLICATION')) exit();
/**
  * Home Index View
  * 
  * @package FourumMe
  */
$Session = Gdn::Session();
?>

<div class="top">

	<div class="content">
		
		<div class="myForums">
			
			<h2>My Forums</h2>
			
			<?php echo Anchor('New Forum', '#', 'showForum');
			
			$TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
			$TermsOfServiceText = sprintf(T('I agree to the <a id="TermsOfService" class="Popup" target="terms" href="%s">terms of service</a>'), Url($TermsOfServiceUrl));
			
			echo $this->Form->Open(array('class'=>'toggleCreate'));

			echo $this->Form->Errors();
			
			echo $this->Form->TextBox('Name');
			
			echo $this->Form->TextBox('Path');
			echo '<span>Ex: http://www.4um.me/forum?Forum=ForumPath</span>';
			
			echo $this->Form->CheckBox('TermsOfService', $TermsOfServiceText, array('value' => '1'));
		
			echo $this->Form->Close('Create');
			
			?>
			
			<div class="clear"></div>
			<div id="spacer" class="spacer"></div>
			
			<?php
			
			foreach($this->Forums as $forum){
				echo '<div class="displayForums"><p class="forumTitle">'.$forum['Name'].'</li>';
				echo '<ul class="adminLinks"><li>'.Anchor('Admin','admin?Forum='.$forum['Path'],'forumLink', array('alt'=>$forum['path'])).'</li>';
				echo '<li>|</li><li>'.Anchor('Home','forum?Forum='.$forum['Path'],'forumLink', array('alt'=>$forum['path'])).'</li></ul></div>';
				echo '<div class="spacer"></div>';
			}
		
			?>
			
		</div>
		
		<div id="userProfile">
			
			<h2><?php echo $Session->User->Name; ?></h2>
			
			<ul>
				<li><? echo Anchor('Change My Picture', '/profile/picture', array('class'=>'Popup')); ?></li>
				<li><? echo Anchor('Edit Account', '/profile/edit', array('class'=>'Popup')); ?></li>
				<li><? echo Anchor('Change Password', '/profile/password', array('class'=>'Popup')); ?></li>
			</ul>
			
		</div>
		
		<div class="clear"></div>
		
	</div> <!-- content -->

</div> <!-- top -->

<div class="content">
	
	<h3>Recent Forums</h3>
	
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