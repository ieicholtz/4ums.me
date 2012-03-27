<div class="content">

	<div id="panel">
		<?php
			//Anchor('Admin', 'fourumme/admin?Forum='.$this->Path);
			echo '<div class="Box"><h4>';
			echo Anchor('Admin', 'fourumme/admin?Forum='.$this->Path);
			echo '</h4></div>';
			
			echo '<div class="Box"><h4>';
			echo T('Appearance');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Banner', 'fourumme/admin/banner?Forum='.$this->Path).'</li>';
			echo '<li>'.Anchor('Categories', 'fourumme/admin/categories?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4>';
			echo T('Users');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Moderators', 'fourumme/admin/moderators?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4 class="Active">';
			echo T('Moderation');
			echo '</h4><ul class="PanelInfo">';
			echo '<li><strong>Ban List</strong></li>';
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
			<h4 id="boxTitle">Banned Users</h4>
			<ul>
				<li class="infoTitle"><p><strong>Manage the list of banned users for your forum</strong></p></li>
				<li><p>Add, Remove, or update the users who have been banned from your forum for violations of the terms of service agreement.</p></li>
				
			</ul>
			
			
			<div class='clear'></div>
		</div>
		
	</div>
			
</div>