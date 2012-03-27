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
			
			echo '<div class="Box"><h4>';
			echo T('Moderation');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Ban List', 'fourumme/admin/ban?Forum='.$this->Path).'</li>';
			echo '</div>';
			
			echo '<div class="Box"><h4 class="Active">';
			echo T('Statistics');
			echo '</h4><ul class="PanelInfo">';
			echo '<li><strong>Statistics</strong></li>';
			echo '</div>';
		 	?>

	</div><!-- panel -->
	
	<div id="display">
		<div class="Box">
			<h4 id="boxTitle">Statistics</h4>
			<ul>
				<li class="infoTitle"><p><strong>Forum Information</strong></p></li>
				<li><p>Coming Soon!</p></li>
				
			</ul>
			
			
			<div class='clear'></div>
		</div>
		
	</div>
			
</div>