<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size('header') > 0) {
    echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
  }

?>

<div id="header" class="grid_24">
  <div id="storeLogo">
  <span style="font-family:Tahoma; font-size:26px; font-weight:bold; "><?php echo STAPLES_PARTS_STORE; ?></span>
  <?php //echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME) . '</a>'; ?>
  </div>
<script type="text/javascript">
  $("#headerShortcuts").buttonset();
</script>
</div>
<div id="headerShortcuts">
 <?php 
        $get_var2 = '';

          reset($HTTP_GET_VARS);

while (list($key, $value) = each($HTTP_GET_VARS)) {

            if ( is_string($value) && ($key != 'language') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {

              $get_var2 .= '&'.$key.'='.$value;

            }

}

		 

if($_GET['osCsid']!=""){

			  $get_var2 .= '&osCsid='.$_GET['osCsid'];

}
                 
if(get_customer_type($_SESSION['customer_group_id'])=='admin' || get_customer_type($_SESSION['customer_group_id'])=='storeadmin'){
		 
    echo    tep_draw_button(NEW_ORDER_QUOTE, 'triangle-1-e', tep_href_link('neworder.php', '', 'SSL')).'&nbsp;'.'&nbsp;'.
            tep_draw_button(VIEW_ALL_ORDERS, 'triangle-1-e', tep_href_link('allorders.php', '', 'SSL')).'&nbsp;'.'&nbsp;';
    
    echo    tep_draw_button(ACTIONABLE_ITEMS, 'triangle-1-e', tep_href_link('allorders.php', 'action=status', 'SSL')).'&nbsp;';
		
    if (tep_session_is_registered('customer_id')) {
        echo '&nbsp;'.'&nbsp;'.tep_draw_button(HEADER_TITLE_LOGOFF, null, tep_href_link(FILENAME_LOGOFF, '', 'SSL'));
      }
  
}
else if(get_customer_type($_SESSION['customer_group_id'])=='storeusers'){
		 
	echo    tep_draw_button(VIEW_ALL_ORDERS, 'triangle-1-e', tep_href_link('allordersuser.php', '', 'SSL')).'&nbsp;'.'&nbsp;'.
                tep_draw_button(PRIVATE_MESSAGE, 'triangle-1-e', tep_href_link('pmessage.php', '', 'SSL')).'&nbsp;'.'&nbsp;';
	
        echo    tep_draw_button(ACTIONABLE_ITEMS, 'triangle-1-e', tep_href_link('allorders.php', 'action=status', 'SSL')).'&nbsp;';
	
        
        if (tep_session_is_registered('customer_id')) {
            echo '&nbsp;'.'&nbsp;'.tep_draw_button(HEADER_TITLE_LOGOFF, null, tep_href_link(FILENAME_LOGOFF, '', 'SSL'));
        }
	    
}
else 
{
	echo	tep_draw_button(NEW_ORDER_QUOTE, 'triangle-1-e', tep_href_link('neworder.php', '', 'SSL')).'&nbsp;'.
                tep_draw_button(MY_ORDERS, 'triangle-1-e', tep_href_link('allorders.php', '', 'SSL')).'&nbsp;';
	
        echo    tep_draw_button(ACTIONABLE_ITEMS, 'triangle-1-e', tep_href_link('allorders.php', 'action=status', 'SSL')).'&nbsp;';
	
	if (tep_session_is_registered('customer_id')) {
             echo tep_draw_button(HEADER_TITLE_LOGOFF, null, tep_href_link(FILENAME_LOGOFF, '', 'SSL'));
        }
  
		
}
?>
  
<div style="float:right;">
<?php /*?><a href="<?php echo basename($PHP_SELF); ?>?language=en<?php echo $get_var2;?>"><img alt="English" title="English" src="<?php echo DIR_WS_LANGUAGES . 'english/images/icon.gif'; ?>"  /></a>&nbsp;&nbsp;
<a href="<?php echo basename($PHP_SELF); ?>?language=fr<?php echo $get_var2;?>"><img alt="French" title="French" src="<?php echo DIR_WS_LANGUAGES . 'french/images/icon.jpg'; ?>"  /></a>
<?php */
//print_r($_SESSION['language']);
?>

<span class="languagetitle"><?php echo LANGUAGE; ?>:&nbsp;&nbsp;</span>
<a href="<?php echo basename($PHP_SELF); ?>?language=en<?php echo $get_var2;?>"><span class="language <?php if($_SESSION['language']=='english') echo 'languagebold'; ?>">EN</span></a>&nbsp;&nbsp;
<a href="<?php echo basename($PHP_SELF); ?>?language=fr<?php echo $get_var2;?>"><span class="language <?php if($_SESSION['language']=='french') echo 'languagebold'; ?>">FR</span></a>


</div></div>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>

    
<?php

if (get_customer_fullname($_SESSION['customer_id']))
  {
         $customerfullname = get_customer_fullname($_SESSION['customer_id']);
        //echo $_SESSION['customer_firstname'];
  ?>
<div id="header"  class="languagetitle" style="font-size: 14px"><br><?php echo ('welcome: '. $customerfullname ?: 'Guest');?></div>
  <?php 
           
  }
  ?>
  
