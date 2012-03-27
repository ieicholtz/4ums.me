<?php if (!defined('APPLICATION')) exit();
/**
  * About Index View
  * 
  * @package FourumMe
  */

?>

<div class="top">

	<div class="content">
		
		<div id="about">
			
			<h2>About 4um.me</h2>
			
			<p>4um.me is a free custom forum solution.  With one login you can browse hundreds of existing forums, or create and manage a forum of your own.</p>
			
			<ul>
				<li>View hundreds of user created forums covering a wide variety of categories, and interact with all your favorite forums with a single login.</li>
				<li>Can't find and existing forum on your favorite categories?  Create your own! In just a few simple steps you'll be ready to make your first topic, and the best part, it's free!</li>
				<li>The custom admin panel allows for a personalized forum without all the unnecessary features. Add a custom logo, appoint moderators, ban users, and customize your layout.</li>
			</ul>
		
		</div> <!-- about -->
		
		<div id="goal">
			
			<h2>Our Goal</h2>
			
			<p>The idea behind 4um.me is to create a unique and user friendly forum experience. By providing users with a platform that uses a single login to not only view and interact, but also to create and manage a personal forum of there own without all the hassles of hosting, installation, and licensing.</p>
			
		</div><!-- goal -->
		
		<div class="clear"></div>
		
	</div> <!-- content -->

</div> <!-- top -->

<div class="content">
	
<?php /*================================================================== 
      FUTURE: turn this into a module to eliminate repetitve calls
       ===================================================================*/ ?>
	
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