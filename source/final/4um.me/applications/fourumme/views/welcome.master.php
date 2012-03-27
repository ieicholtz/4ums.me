<?php echo '<?xml version="1.0" encoding="utf-8"?>'; 
/**
  * Welcome Master View
  * 
  * @package FourumMe
  */
?>
<!doctype html>

<head>	
<?php $this->RenderAsset('Head'); ?>
</head>

<body id="<?php echo $BodyIdentifier;?>" class="<?php echo $this->CssClass; ?>">
	
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

		<li class="active">Home</li>
				
		<?php
		  $about= array('text'=>'About', 'url'=>'about', 'alt'=>'about');
		  $contact= array('text'=>'Contact', 'url'=>'contact', 'alt'=>'contact');
		  $links = array($about, $contact);
		
		  foreach($links as $link){
				
			echo '<li>'.Anchor($link['text'], $link['url'], $link['alt']).'</li>';
		  }?>
				
	</ul> <!-- footerLinks -->
		
	<div class="clear"></div>
		
  </div><!-- footer -->
			
</footer>
	
</body>

</html>