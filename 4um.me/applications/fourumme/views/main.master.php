<?php echo '<?xml version="1.0" encoding="utf-8"?>'; 
/**
  * Main Master View
  * 
  * @package FourumMe
  */
	
$Session = Gdn::Session();

?>
<!doctype html>

<head>
	<?php $this->RenderAsset('Head'); ?>	
</head>

<body id="<?php echo $BodyIdentifier;?>" class="<?php echo $this->CssClass; ?>">
	
<header>
		
  <div class="header">
			
	<?php 
		echo Img('/applications/fourumme/design/img/logo2.png', '4um.me logo');
		
		if($Session->UserID > 0){
			echo Anchor('Logout', SignOutUrl(), 'leave', array('id'=>'headerLink'));
		}else{ ?>
			<ul>
				<li><?php echo Anchor('Sign In', 'entry/signin', 'Popup'); ?></li>
				<li> or </li>
				<li><?php echo Anchor('Sign Up Now!', 'entry/register', 'Popup', array('id'=>'signUpLink')); ?></li>
			</ul>
	<?php } ?>
					
  </div><!-- header -->
		
</header>

  <div class="main">
		
		<?php $this->RenderAsset('Content'); ?>
		
		<div class="push"></div>

  </div><!-- main -->
	
<footer>
		
  <div class="footer">
	
	<ul id="legal" class="footerContent">

		<li>Copyright &copy; 2012 4um.me.</li>
		<li> All rights reserved.</li>
		<?php $TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
			echo '<li>'.Anchor('Terms of Service', $TermsOfServiceUrl, 'Popup').'</li>'; ?>

	</ul><!-- legal -->
	
	<ul id="footerLinks" class="footerContent">
				
		<?php
		  $home= array('text'=>'Home', 'url'=>'fourumme/home', 'alt'=>'home');
		  $about= array('text'=>'About', 'url'=>'about', 'alt'=>'about');
		  $contact= array('text'=>'Contact', 'url'=>'contact', 'alt'=>'contact');
		  $links = array($home, $about, $contact);
		
		  foreach($links as $link){
				
			if($link['alt'] != $this->CurrentPage){
				
				echo '<li>'.Anchor($link['text'], $link['url'], $link['alt']).'</li>';
				
			}else{
				
				echo '<li class="active">'.$link['text'].'</li>';
			}
				
		} ?>
				
	</ul> <!-- footerLinks -->
			
    <div class="clear"></div>
		
  </div><!-- footer -->
			
</footer>
	
</body>

</html>