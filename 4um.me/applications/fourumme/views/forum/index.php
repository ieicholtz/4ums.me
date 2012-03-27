<ul class="DataList Discussions">
   
	   <?php
		if(sizeof($this->Discussions) == 0){
		  	echo  '<div class="Empty">';
		  	echo  T('No discussions were found.');
			echo '</div>';
		
		}else{
		
		for ($i = 0; $i <= sizeof($this->Discussions)-1; $i++) {
			$Name = $this->Discussions[$i]['Name'];
			$Comments = $this->Discussions[$i]['CountComments'];
			$Closed = $this->Discussions[$i]['Closed'];
			$User = $this->Discussions[$i]['UpdateUserName'];
			$Date = $this->Discussions[$i]['DateInserted'];
			
			echo '<li class="'.$CssClass.'">';
			echo '<div class="ItemContent Discussion">';
			echo Anchor($Name, '#', 'Title');
			echo '<div class="Meta">';
			if($Closed == '1'){
				echo '<span class="Closed">';
				echo T('Closed'); 
				echo '</span>';
			}
			echo '<span class="CommentCount">';
			if($Comments == 1){
				echo $Comments.' comment';
			}else{
				echo $Comments.' comments';
			}
			echo '</span>';
			echo '<span class="LastCommentBy">'.T('Started by '.$User).'</span>';
            echo '<span class="LastCommentDate">'.Gdn_Format::Date($Date);
			echo '</div></div>';
			echo '</li>';
			
		}
		}?>
</ul>