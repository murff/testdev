<?php
/*
  Updated for osCommerce 2.3.3.4 2013/09/30 mommaroodles
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Copyright (c) 2005 osCommerce
  
  Released under the GNU General Public License 
*/

  require('includes/application_top.php');
  
    $cg_show_tax_array   = array(array('id' => '1', 'text' => ENTRY_GROUP_SHOW_TAX_YES),
                                 array('id' => '0', 'text' => ENTRY_GROUP_SHOW_TAX_NO));
    $cg_tax_exempt_array = array(array('id' => '1', 'text' => ENTRY_GROUP_TAX_EXEMPT_YES),
                                 array('id' => '0', 'text' => ENTRY_GROUP_TAX_EXEMPT_NO));
								 
	$group_type = array(
	array('id' => 'storeadmin', 'text' => ITEM_NET_ADMIN),
	array('id' => 'admin', 'text' => 'Admin'),
                                 array('id' => 'buyer', 'text' => 'Buyer'),
								 array('id' => 'customer', 'text' => 'customer')
								 );
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'update':
        $error = false;
	     $customers_group_id = tep_db_prepare_input($_GET['cID']);
		$customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
		$customers_group_type = tep_db_prepare_input($_POST['customers_group_type']);
		 /*if($customers_group_name1=='Staples Admin'){
					$$customers_group_name=STAPLES_ADMIN; }else 
		if($customers_group_name1==STAPLES_DEPOT){
					$customers_group_name=STAPLES_DEPOT; }else 
		if($customers_group_name1==STAPLES_STORE){
					$customers_group_name='Store/Customers'; }else 
	   if($customers_group_name1=='ItemNet Admin'){
					$customers_group_name=ITEM_NET_ADMIN; }else 
		if($customers_group_name1=='ItemNet User'){
					$customers_group_name=ITEM_NET_USERS; }
					echo $customers_group_name;
					echo "update " . TABLE_CUSTOMERS_GROUPS . " set customers_group_name='" . $customers_group_name ."', customers_group_type='" . $customers_group_type ."' where customers_group_id = '" . $customers_group_id ."'";
					exit;*/
        tep_db_query("update " . TABLE_CUSTOMERS_GROUPS . " set customers_group_name='" . $customers_group_name ."', customers_group_type='" . $customers_group_type ."' where customers_group_id = '" . $customers_group_id ."'");
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_group_id));
		break;
        
      case 'deleteconfirm':
        $group_id = tep_db_prepare_input($_GET['cID']);
        tep_db_query("delete from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id= " . $group_id); 
        $customers_id_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_group_id=" . $group_id);
        while($customers_id = tep_db_fetch_array($customers_id_query)) {
            tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set customers_group_id = '0' where customers_id=" . $customers_id['customers_id']);
        }     
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')))); 
        break;
        
      case 'newconfirm' :
        $customers_group_name = tep_db_prepare_input($_POST['customers_group_name']);
		 $customers_group_type = tep_db_prepare_input($_POST['customers_group_type']);
	    
        $last_id_query = tep_db_query("select MAX(customers_group_id) as last_cg_id from " . TABLE_CUSTOMERS_GROUPS . "");
        $last_cg_id_inserted = tep_db_fetch_array($last_id_query);
        $new_cg_id = $last_cg_id_inserted['last_cg_id'] +1;
        tep_db_query("insert into " . TABLE_CUSTOMERS_GROUPS . " set customers_group_id = " . $new_cg_id . ", customers_group_type = '" . $customers_group_type . "' ,customers_group_name = '" . tep_db_input($customers_group_name) . "'");
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('action'))));
        break;
    }
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  if ($_GET['action'] == 'edit') {
    $customers_groups_query = tep_db_query("select c.customers_group_id, c.customers_group_name, c.customers_group_show_tax, c.customers_group_tax_exempt, c.group_payment_allowed, c.group_shipment_allowed, c.group_order_total_allowed, c.group_specific_taxes_exempt,c.customers_group_type from " . TABLE_CUSTOMERS_GROUPS . " c  where c.customers_group_id = '" . (int)$_GET['cID'] . "'");
    $customers_groups = tep_db_fetch_array($customers_groups_query);
    $cInfo = new objectInfo($customers_groups);
    
   $payments_allowed = explode (";",$cInfo->group_payment_allowed);
   $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
   $order_total_allowed = explode (";",$cInfo->group_order_total_allowed);
   $group_tax_ids_exempt = explode (",",$cInfo->group_specific_taxes_exempt);
   $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
   $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
   $order_total_module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';

   $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
   $directory_array = array();
   if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

   $ship_directory_array = array();
   if ($dir = @dir($ship_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($ship_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
        }
      }
    }
    sort($ship_directory_array);
    $dir->close();
  }
  
   $order_total_directory_array = array();
   if ($dir = @dir($order_total_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($order_total_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $order_total_directory_array[] = $file; // array of all order total modules present in includes/modules/order_total
        }
      }
    }
    sort($order_total_directory_array);
    $dir->close();
  }
?>

<script type="text/javascript">
<!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
<!-- Edit Group -->
    <table border="0" width="98%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_EDIT_GROUP; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

	  <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onsubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php //echo CATEGORY_PERSONAL; ?></td>
      </tr>

      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <?php /*if($cInfo->customers_group_name==STAPLES_ADMIN){
					$var_name='Staples Admin'; }else if($cInfo->customers_group_name==STAPLES_DEPOT){
					$var_name=STAPLES_DEPOT; }else if($cInfo->customers_group_name=='Store/Customers'){
					$var_name=STAPLES_STORE; }else if($cInfo->customers_group_name==ITEM_NET_ADMIN){
					$var_name=ITEM_NET_ADMIN; }else if($cInfo->customers_group_name==ITEM_NET_USERS){
					$var_name=ITEM_NET_USERS; }*/?>
            <td class="main"><?php  echo tep_draw_input_field('customers_group_name',$cInfo->customers_group_name, 'maxlength="32"', false) . ENTRY_GROUP_NAME_MAX_LENGTH; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo 'Group Type'; ?></td>
            <td class="main"><?php   echo tep_draw_pull_down_menu('customers_group_type',$group_type,$cInfo->customers_group_type); ?></td>
          </tr>
      </table>
	</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr> 	
      <tr>
        <td align="left">
        <?php echo tep_draw_button(IMAGE_UPDATE, 'document',null,'primary'); ?>&nbsp;&nbsp;
        <?php echo tep_draw_button(IMAGE_CANCEL, 'document',tep_href_link(FILENAME_CUSTOMERS_GROUPS, ''));?>
      </tr>
      </form>

	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '70'); ?></td>
      </tr>

<?php
// Add new Group
  } else if($_GET['action'] == 'new') {   
?>
<script type="text/javascript">
<!--
function check_form() {
  var error = 0;

  var customers_group_name = document.customers.customers_group_name.value;
  
  if (customers_group_name == "") {
    error_message = "<?php echo ERROR_CUSTOMERS_GROUP_NAME; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_ADD_NEW_GROUP; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post', 'onsubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php //echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUPS_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('customers_group_name', '', 'maxlength="32"', false); ?></td>
          </tr>
           <tr>
            <td class="main"><?php echo 'Group Type'; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('customers_group_type', $group_type, '1'); ?></td>
          </tr>
        
          <tr>
            <td class="main">&#160;</td>
            <td class="main" style="line-height: 2"><?php //echo ENTRY_GROUP_SHOW_TAX_EXPLAIN_2; ?></td>
          </tr>
      
	 </table>
	</td>
      </tr>
      <!-- end insert tax rate exempt -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr> 	
      <tr>
        <td align="left">
        <?php echo tep_draw_button(IMAGE_UPDATE, 'document',null,'primary'); ?> &nbsp; &nbsp;
        <?php echo tep_draw_button(IMAGE_CANCEL, 'document',tep_href_link(FILENAME_CUSTOMERS_GROUPS, ''));?></td>
      </tr>
      </form>
<?php 
  } else { // end action=new - beginning of default
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_CUSTOMERS_GROUPS, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE_DEFAULT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>

          <?php
          switch ($_GET['listing']) {
              case "group":
              $order = "g.customers_group_name";
              break;
              case "group-desc":
              $order = "g.customers_group_name DESC";
              break;
              default:
              $order = "g.customers_group_id ASC";
          }
          ?>
	    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td> 
                 <td class="dataTableHeadingContent"><?php echo 'Group Type'; ?></td> 
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>	

<?php
    $search = '';
    if ( ($_GET['search']) && (tep_not_null($_GET['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = "where g.customers_group_name like '%" . $keywords . "%'";
    }

    $customers_groups_query_raw = "select g.customers_group_id, g.customers_group_name,g.customers_group_type from " . TABLE_CUSTOMERS_GROUPS . " g  " . $search . " order by $order";
    $customers_groups_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_groups_query_raw, $customers_groups_query_numrows);
    $customers_groups_query = tep_db_query($customers_groups_query_raw);

    while ($customers_groups = tep_db_fetch_array($customers_groups_query)) {

      if ((!isset($_GET['cID']) || (@$_GET['cID'] == $customers_groups['customers_group_id'])) && (!$cInfo)) {
        $cInfo = new objectInfo($customers_groups);
      }

      if ( (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php if($customers_groups['customers_group_name']==STAPLES_ADMIN){
					echo STAPLES_ADMIN; }else if($customers_groups['customers_group_name']==STAPLES_DEPOT){
					echo STAPLES_DEPOT; }else if($customers_groups['customers_group_name']==STAPLES_STORE){
					echo STAPLES_STORE; }else if($customers_groups['customers_group_name']==ITEM_NET_ADMIN){
					echo ITEM_NET_ADMIN; }else if($customers_groups['customers_group_name']==ITEM_NET_USERS){
					echo ITEM_NET_USERS; }?></td>
                 <td class="dataTableContent"><?php echo $customers_groups['customers_group_type']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_groups_split->display_count($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_groups_split->display_links($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
                  <?php
                    if (tep_not_null($_GET['search'])) {
                   ?>
                   <tr>
                    <td align="right" colspan="2"><?php echo tep_draw_button(IMAGE_BACK, 'triangle-1-w',tep_href_link(FILENAME_CUSTOMERS_GROUPS, ''));?></td>
                   </tr>
                    <?php
                   } else {
                    ?>
			      <tr>
                   <td align="right" colspan="2" class="smallText"><?php echo tep_draw_button('New Group', 'plus', tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $_GET['page'] . '&action=new')); ?></td> 
                  </tr>
                  <?php
                   	}
                  ?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'confirm':
        if ($_GET['cID'] != '0') {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><strong>' . TEXT_INFO_HEADING_DELETE_GROUP . '</strong>');
            $contents = array('form' => tep_draw_form('customers_groups', FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=deleteconfirm'));         
            $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><strong>' . $cInfo->customers_group_name . ' </strong>');                                                                             
            $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id)));                                                                                                                                          
        } else {
            $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><strong>' . TEXT_INFO_HEADING_DELETE_GROUP . '</strong>');
            $contents[] = array('text' => TEXT_NO_DELETE_GROUP . '<br><br><strong>' . $cInfo->customers_group_name . '</strong>');
        }
      break;    
      default:
          //if (isset($cInfo) && is_object($cInfo)) { 
             if (is_object($cInfo)) {
            $heading[] = array('text' => '<strong>' . $cInfo->customers_group_name . '</strong>');
            $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=confirm')));
          } 
        break;
}
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
