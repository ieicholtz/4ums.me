<div class="content">

	<div id="panel">
		<?php
			//Anchor('Admin', 'fourumme/admin?Forum='.$this->Path);
			echo '<div class="Box"><h4>';
			echo Anchor('Admin', 'fourumme/admin?Forum='.$this->Path);
			echo '</h4></div>';
			
			echo '<div class="Box"><h4 class="Active">';
			echo T('Appearance');
			echo '</h4><ul class="PanelInfo">';
			echo '<li><strong>Banner</strong></li>';
			echo '<li>'.Anchor('Categories', 'fourumme/admin/categories?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4>';
			echo T('Users');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Moderators', 'fourumme/admin/moderators?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4>';
			echo T('Moderation');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Ban List', 'fourumme/admin/ban?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4>';
			echo T('Statistics');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Statistics', 'fourumme/admin/stats?Forum='.$this->Path).'</li>';
			echo '</div>';
		 	?>

	</div><!-- panel -->
	
	<div id="display">
		<div class="Box">
			<h4 id="boxTitle">Banner</h4>
			<ul>
				<li class="infoTitle"><p><strong>Change the title of your forum</strong></p></li>
				<li><p>Rename your forum, this will change the title of the forum, and the text that displays for your forum throughout the site.</p></li>
				
			</ul>
			
			<?php
				 echo $this->Form->Open();
			     echo $this->Form->Errors();
				 echo $this->Form->Label('Current Name: '.$this->Title, 'Name');
				 echo $this->Form->TextBox('Name', Array('value'=>$this->Title));
				 echo $this->Form->Close('Submit Changes');
			
			?>
			<div class='clear'></div>
		</div>
		
	</div>
			
</div>