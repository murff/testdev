<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<h1><?php echo 'My Account'; ?></h1>

<?php
  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }
?>

<div class="contentContainer">
 

  <div class="contentText">
    <ul class="accountLinkList"> 
    <?php 
	if(get_customer_group($_SESSION['customer_group_id'])=='admin')
	  {
		 echo
		 tep_draw_button('All order', 'triangle-1-e', tep_href_link('allorders.php', '', 'SSL')).'&nbsp;'.
		 	tep_draw_button('Update account info', 'triangle-1-e', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL')).'&nbsp;'.
		 tep_draw_button('Update address info', 'triangle-1-e', tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL')).'&nbsp;'.
		 tep_draw_button('Update pasword', 'triangle-1-e', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
	    
		} 
		else 
		{
			
		 /* echo tep_draw_button('My orders', 'triangle-1-e', tep_href_link('my_orders.php', '', 'SSL')). 
		 tep_draw_button('My quote', 'triangle-1-e', tep_href_link('my_quotes.php', '', 'SSL')).*/
 		 echo tep_draw_button('My orders', 'triangle-1-e', tep_href_link('allorders.php', '', 'SSL')).'&nbsp;'. 
		 tep_draw_button('My quote', 'triangle-1-e', tep_href_link('#', '', 'SSL')).'&nbsp;'.
		 tep_draw_button('Update account info', 'triangle-1-e', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL')).'&nbsp;'.
		 tep_draw_button('Update address info', 'triangle-1-e', tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL')).'&nbsp;'.
		 tep_draw_button('Update pasword', 'triangle-1-e', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
		
	  }
	 ?>
     </ul>
  </div>


</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
