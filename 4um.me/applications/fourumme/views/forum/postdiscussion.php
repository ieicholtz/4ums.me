<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
$CatNames = array();
for ($i = 0; $i <= sizeof($this->Categories)-1; $i++) {
		array_push($CatNames, $this->Categories[$i]['Name']);
}

?>

<div id="DiscussionForm">
   <h1><?php echo 'New Discussion' ?></h1>
   <?php
      echo $this->Form->Open();
      echo $this->Form->Errors();
		
	  echo '<div class="P">';
	  echo $this->Form->Label('Discussion Title', 'Name');
	  echo Wrap($this->Form->TextBox('Name', array('maxlength' => 100, 'class' => 'InputBox BigInput')), 'div', array('class' => 'TextBoxWrapper'));
	  echo '</div>';
	  echo '<div class="P">';
			echo '<div class="Category">';
			echo $this->Form->Label('Category', 'CategoryID'), ' ';
			echo $this->Form->DropDown('CategoryID', $CatNames);
			echo '</div>';
		echo '</div>';
		
		echo '<div class="P">';
		echo $this->Form->Label('Discussion Body', 'Body');
	    echo Wrap($this->Form->TextBox('Body', array('MultiLine' => TRUE)), 'div', array('class' => 'TextBoxWrapper'));
		echo '</div>';
		
		echo '<div class="Buttons">';
		echo $this->Form->Button((property_exists($this, 'Discussion')) ? 'Save' : 'Post Discussion', array('class' => 'Button DiscussionButton'));
		echo Anchor(T('Cancel'), 'fourumme/forum?Forum='.$this->Path, 'Cancel');
	    echo '</div>';

	echo '</div>';

   ?>
</div>