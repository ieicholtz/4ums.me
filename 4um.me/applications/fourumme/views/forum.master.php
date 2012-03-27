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
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
   <div id="Frame">
      <div id="Head">
         <div class="Menu">
            <h1><a class="Title" href="<?php echo Url('fourumme/forum?Forum='.$this->Path); ?>"><span><?php echo $this->Title ?></span></a></h1>
            <?php
					if ($this->Menu) {
						if($this->User == $Session->UserID)
						$this->Menu->AddLink('Admin', 'Admin', 'fourumme/admin?Forum='.$this->Path);
						
						
						$this->Menu->AddLink('4um.me', T('4um.me'), '/fourumme/welcome');
						
						
						if($Session->UserID > 0){
							$this->Menu->AddLink('SignOut', T('Sign Out'), SignOutUrl());
						}else{
							$this->Menu->AddLink('SignUp', T('Sign Up'), 'fourumme/welcome');
						}
					
						echo $this->Menu->ToString();
					}
				?>
            <div class="Search"><?php
					$Form = Gdn::Factory('Form');
					$Form->InputPrefix = '';
					echo 
						$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
						$Form->TextBox('Search'),
						$Form->Button('Go', array('Name' => '')),
						$Form->Close();
				?>
			</div><!-- Search -->
         </div><!-- Menu -->
      </div><!-- Head -->

  <div id="Body">
         
	<div id="Content">
				
		<?php $this->RenderAsset('Content'); ?>
	
	</div><!-- Content -->
	
    <div id="Panel">
		<?php
         	echo Anchor(T('Start a New Discussion'), 'fourumme/forum/postdiscussion?Forum='.$this->Path, 'BigButton NewDiscussion');
         ?>
		
		<div class="Box BoxCategories">
		   <h4><?php echo T('Categories'); ?></h4>
		   <ul class="PanelInfo PanelCategories">
		<?php
		   	
			for ($i = 0; $i <= sizeof($this->Categories) - 1; $i++) {
			 	echo '<li><span><strong>'.Anchor(Gdn_Format::Text(T($this->Categories[$i]['Name'])), '/discussions').'</strong><span class="Count">'.number_format(sizeof($this->Discussions)).'</span></span></li>';
			
			}
		 	
		?>
		   </ul>
		</div><!-- Box Box Categories -->
	</div><!-- Panel -->
    </div><!-- Body -->
      
	<div id="Foot">
			<?php
				$this->RenderAsset('Foot');
				echo Wrap(Anchor(T('Powered by Vanilla'), C('Garden.VanillaUrl')), 'div');
			?>
	</div><!-- Foot -->
   
</div><!-- Frame -->
	
</body>
</html>
