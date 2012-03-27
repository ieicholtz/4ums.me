<div class="content">

	<div id="panel">
		<?php
			//Anchor('Admin', 'fourumme/admin?Forum='.$this->Path);
			echo '<div class="Box"><h4 class="Active">';
			echo T('Admin');
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
			
			echo '<div class="Box"><h4>';
			echo T('Statistics');
			echo '</h4><ul class="PanelInfo">';
			echo '<li>'.Anchor('Statistics', 'fourumme/admin/stats?Forum='.$this->Path).'</li>';
			echo '</div>';
		 	?>

	</div><!-- panel -->
	
	<div id="display">
		<div class="Box">
			<h4 id="boxTitle">Get Started</h4>
			<ul>
				<li class="infoTitle"><p><strong>Welcome to your admin panel</strong></p></li>
				<li><p>This is the administrative dashboard for your new community. Check out the configuration options to the left: from here you can configure how your community works.</p></li>
				
				<li class="infoTitle"><p><strong>Where is my forum?</strong></p></li>
				<li><p>You can access your forum by clicking the Forum Home link in the top right or by following this link: <a href="fourumme/forum?Forum=<?php echo $this->Path?>">http://www.4um.me/forum?Forum=<?php echo $this->Path?></a></p></li>
					
				<li class="infoTitle"><p><strong>Organize your categories</strong></p></li>
				<li><p>Discussion categories are used to help your users organize their discussions in a way that is meaningful for your community. You can add or remove categories for your forum using the menu on the left</p></li>
		</div>
		
	</div>
			
</div>