<?php if (!defined('APPLICATION')) exit();

/**
 * Garden.Modules
 */

/**
 * Renders the "Start a New Discussion" button.
 */
class NewDiscussionModule extends Gdn_Module {

   public $Path;

   public function __construct($Sender = '') {
	
	$this->Path = '';
	
	 parent::__construct($Sender);
   }

   public function AssetTarget() {
      return 'Panel';
   }
   
   public function ToString() {
      if (Gdn::Session()->UserID > 0)
         return parent::ToString();

      return '';
   }
}