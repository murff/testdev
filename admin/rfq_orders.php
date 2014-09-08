<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
define('EMAIL_SEPARATOR', '------------------------------------------------------');

function get_customer_id($id){
	
	$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$id . "'");
        $customers_email = tep_db_fetch_array($customers_email_query);
        $customers_email_info = new objectInfo($customers_email);
		$cust_id=$customers_email_info->customer_id;
		return $cust_id;
	}
function getSignature(){
	
	 return nl2br(STORE_NAME_ADDRESS). "\n" ."<a href='http://itemnet.ca'>itemnet.ca</a>";  
	   
   }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;

  if (tep_not_null($action)) {

    switch ($action) {
      case 'update':
        $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $manfacturer = tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']);
        $serial = tep_db_prepare_input($HTTP_POST_VARS['serial']);
        $model = tep_db_prepare_input($HTTP_POST_VARS['model']);
       $issue_no = tep_db_prepare_input($HTTP_POST_VARS['issue_no']);
        $po_no = tep_db_prepare_input($HTTP_POST_VARS['po_no']);
        $notes = tep_db_prepare_input($HTTP_POST_VARS['notes']);
       

     

      if ($error == false) {

        $sql_data_array = array('manufacturer' => $manfacturer,
                                'serial' => $serial,
                                'model' => $model,
                                'issue_no' => $issue_no,
                                'po_no' => $po_no,
                                'notes' => $notes,
								'order_updates' =>1 
								);

      

        tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int)$rfq_id . "'");

		foreach($_POST['qty'] as $key=> $val){
			  $products_ordered .= $val . ' x ' .  ' (' . $_POST['part_number'][$key] . ') = ' .$_POST['price'][$key]."\t" .$_POST['description'][$key] . "\n";
			 $sql_data_array2 = array('qty' => $val,
                                'description' => $_POST['description'][$key],
                                'part_number' => $_POST['part_number'][$key],
                                'price' => $_POST['price'][$key]
								);

			
			tep_db_perform('rfq_order_detail', $sql_data_array2, 'update', "rfq_od_id = '" . (int)$key. "'");

			}
			
			 
			 $email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n\n" .
		 'Manufacturer Name ' . "\n" .EMAIL_SEPARATOR . "\n" .getManfct_name($manfacturer). "\n\n" .
		 'Serial No: ' . "\n" .EMAIL_SEPARATOR . "\n" .$serial. "\n\n" .
		 'Model No:' . "\n" .EMAIL_SEPARATOR . "\n" .$model. "\n\n" .'Products Ordered'. "\n".EMAIL_SEPARATOR . "\n" . $products_ordered."\n\n".
		 'Issue No: ' . "\n" .EMAIL_SEPARATOR . "\n" .$issue_no. "\n\n" .
		 'PO No:' . "\n" .EMAIL_SEPARATOR . "\n" .$po_no. "\n\n" .
		 'Notes' . "\n" .EMAIL_SEPARATOR . "\n" .$notes. "\n\n".
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/view_detail.php?id=" .$rfq_id. "'>http://itemnet.ca/cartest/view_detail.php?id=" .$rfq_id. "</a>\n\n".getSignature();
 $po="po".$po_no;
 
 //ger_customer_i function retun customer id
  $customer_id=get_customer_id($rfq_id);
 
  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
  $customer_address = tep_db_fetch_array($customers_email);

 tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Updated', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
       //  tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$rfq_id . "'");
 //exit;
       
        tep_redirect(tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $rfq_id));

        } else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }

        break;
      case 'rfqconfirm':
        $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

		$email_order = "Your Quote Order NO " .$rfq_id. " Has been Accepted \n" . 
                 EMAIL_SEPARATOR . "\n\n" .
				 orderProductEmail($rfq_id).
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/view_detail.php?id=" .$rfq_id. "'>http://itemnet.ca/cartest/view_detail.php?id=" .$rfq_id. "</a>\n\n".getSignature();

	 
	 //ger_customer_i function retun customer id
      $customer_id=get_customer_id($rfq_id);
	  
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
	  
	  //////////////////////////////////////////////////////////////////////////////////////
	$email_order2 = "You have Accepted the order NO ".$rfq_id. "\n\n" .
	 orderProductEmail($rfq_id).
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$rfq_id."&action=view'>http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$rfq_id."&action=view</a>\n\n".getSignature();
		
		 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , 'Quote Order Accepted', $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
////////////////////////////////////////////////////////////////////////////////////////
	  
		
	tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Accepted', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
      
          tep_db_query("update rfq_order set status = 1, `order/quote_status` = '1' where rfq_id = '" . (int)$rfq_id . "'");
       

      /*  tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where rfq_id = '" . (int)$rfq_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where rfq_id = '" . (int)$rfq_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$rfq_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where rfq_id = '" . (int)$rfq_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where rfq_id = '" . (int)$rfq_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$rfq_id . "'");*/

        tep_redirect(tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action'))));
        break;
		  case 'rfqdecl':
        $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		
		$email_order = "Your Quote Order NO " .$rfq_id. " Has been Declined \n" . 
                 EMAIL_SEPARATOR . "\n\n" .
				  orderProductEmail($rfq_id).
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/vieworder.php?po=" .$rfq_id. "'>http://itemnet.ca/cartest/vieworder.php?po=" .$rfq_id. "</a>\n\n".getSignature();
	   
	  //ger_customer_i function retun customer id
      $customer_id=get_customer_id($rfq_id);
	  
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
		
		  //////////////////////////////////////////////////////////////////////////////////////
	$email_order2 = "You have Declined the order NO ".$rfq_id. "\n\n" .
	 orderProductEmail($rfq_id).
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$rfq_id."&action=view'>http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$rfq_id."&action=view</a>\n\n".getSignature();
		
		 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , 'Quote Order Declined', $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
////////////////////////////////////////////////////////////////////////////////////////
		
	tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Declined', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
           
          tep_db_query("update rfq_order set status = 2 where rfq_id = '" . (int)$rfq_id . "'");
          tep_redirect(tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action'))));
		  
        break;
      default:
        $customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
		/************************************* query for order details********************************/
		$customers_order_detail= tep_db_query("select * from rfq_order_detail where rfq_id = '" . $cInfo->rfq_id . "'");
		
       
        
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($action == 'edit' || $action == 'update') {
?>

<?php
  }
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <?php   if ($action == 'view') {
    $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
                              array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="10">
          <tr>
            <td class="pageHeading"><?php echo 'Customers RFQ Detail'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php 
		echo tep_draw_button(IMAGE_EDIT, 'document', tep_href_link('rfq_orders.php', 
		tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=edit'));
		echo '&nbsp;&nbsp;'.tep_draw_button('Accept', '', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm')) ; 
		echo '&nbsp;&nbsp;'.tep_draw_button('Decline', 'trash', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqdecl')); 
		echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', 'rfq_orders.php', tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onsubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle">&nbsp;</td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="10">

          <tr height="25px">
            <td class="main" width="9%"><?php echo '<strong>Manfacturer</strong>'; ?> </td>
            <td class="main" align="left">
<?php /*?><?php
 
    echo tep_draw_input_field('manfacturer', $cInfo->manufacturer, 'maxlength="32"', false);
 
?><?php */?>

<?php
echo  getManfct_name($cInfo->manufacturer);
     

     
     ?>
</td>
 <td class="main"></td>
<tr height="25px">
            <td class="main"><?php echo '<strong>Serial</strong>'; ?></td>
            <td class="main">
<?php
 
    echo  $cInfo->serial;
 
?></td>
          </tr>
          <tr height="25px">
            <td class="main"><?php echo '<strong>Model</strong>'; ?></td>
            <td class="main">
<?php
 
    echo $cInfo->model;
 
?></td>
          </tr>
          <tr height="35px">
            <td colspan="3">
            	<table width="50%" border="0" cellspacing="2" cellpadding="8">
                	<tr height="15px">
                    	 <td class="main"><?php echo '<strong>Qty</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Part Type</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Description</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Part Number</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Price</strong>'; ?></td>
                    </tr>
                    <?php while( $order_detail = tep_db_fetch_array($customers_order_detail)){ ?>
                    <tr height="25px">     
            			<td class="main">
					<?php
    				echo $order_detail['qty'];
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo $order_detail['part_type']; 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo  $order_detail['description'];
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo  $order_detail['part_number']; 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo  $order_detail['price'];
					?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
          </tr>
         
           <tr height="25px">
            <td class="main"><?php echo '<strong>Issue No</strong>'; ?></td>
            <td class="main">
<?php
 
    echo  $cInfo->issue_no;
 
?></td>
          </tr>
           <tr height="25px">
            <td class="main"><?php echo '<strong>PO No</strong>'; ?></td>
            <td class="main">
<?php
 
    echo  $cInfo->po_no;
 
?></td>
          </tr>
 		  <tr height="15px">
            <td class="main"><?php echo '<strong>Notes</strong>'; ?></td>
            <td class="main">
<?php
 
    //echo tep_draw_input_field('notes', $cInfo->notes, 'maxlength="32"', false);
	echo $cInfo->notes;
 
?></td>
          </tr>  
           
         

    
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="left" class="smallText" colspan="2"><?php echo  tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('action')))); ?></td>
      </tr></form></table>
<?php
  } 

 else  if ($action == 'edit' || $action == 'update') {
    $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
                              array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Customers RFQ Edit'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', 'rfq_orders.php', tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onsubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle"></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="10">

          <tr height="25px">
            <td class="main" width="9%"><?php echo '<strong>Manfacturer</strong>'; ?> </td>
            <td class="main" align="left">
<?php /*?><?php
 
    echo tep_draw_input_field('manfacturer', $cInfo->manufacturer, 'maxlength="32"', false);
 
?><?php */?>
<?php

     

     $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");

     

     $manufacturers_arrayt = array();

               if (MAX_MANUFACTURERS_LIST < 2) {

                 $manufacturers_arrayt[] = array('id' => '', 'text' => 'Pleae select');

               }

     

               while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {

                 $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);

                 $manufacturers_arrayt[] = array('id' => $manufacturers['manufacturers_id'],

                                                'text' => $manufacturers_name);

               }

     

               $content =  tep_draw_pull_down_menu('manufacturers_id', $manufacturers_arrayt, $cInfo->manufacturer, '')  ;

              

     

             echo   $content  ;

     ?>
</td>
 <td class="main"></td>
<tr height="25px">
            <td class="main"><?php echo '<strong>Serial</strong>'; ?></td>
            <td class="main">
<?php
 
    echo tep_draw_input_field('serial', $cInfo->serial, 'maxlength="32"', false);
 
?></td>
          </tr>
          <tr height="25px">
            <td class="main"><?php echo '<strong>Model</strong>'; ?></td>
            <td class="main">
<?php
 
    echo tep_draw_input_field('model', $cInfo->model, 'maxlength="32"', false);
 
?></td>
          </tr>
          <tr height="35px">
            <td colspan="3">
            	<table border="0" cellspacing="2" cellpadding="2">
                	<tr height="15px">
                    	 <td class="main"><?php echo '<strong>Qty</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Part Type</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Description</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Part Number</strong>'; ?></td>
                         <td class="main" width="3%"></td>
                         <td class="main"><?php echo '<strong>Price</strong>'; ?></td>
                    </tr>
                    <?php while( $order_detail = tep_db_fetch_array($customers_order_detail)){ ?>
                    <tr height="25px">     
            			<td class="main">
					<?php
    				echo tep_draw_input_field('qty['.$order_detail['rfq_od_id'].']', $order_detail['qty'], 'maxlength="32" size="2"', false); 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo tep_draw_input_field('part_type['.$order_detail['rfq_od_id'].']',$order_detail['part_type'], 'maxlength="32"', false); 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo tep_draw_input_field('description['.$order_detail['rfq_od_id'].']', $order_detail['description'], 'maxlength="32" size="12"', false); 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo tep_draw_input_field('part_number['.$order_detail['rfq_od_id'].']', $order_detail['part_number'], 'maxlength="32"  size="8"', false); 
					?></td>
                    <td class="main"></td>
                    <td class="main">
					<?php
    				echo tep_draw_input_field('price['.$order_detail['rfq_od_id'].']', $order_detail['price'], 'maxlength="32"  size="4"', false); 
					?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
          </tr>
         
           <tr height="25px">
            <td class="main"><?php echo '<strong>Issue No</strong>'; ?></td>
            <td class="main">
<?php
 
    echo tep_draw_input_field('issue_no', $cInfo->issue_no, 'maxlength="32"', false);
 
?></td>
          </tr>
           <tr height="25px">
            <td class="main"><?php echo '<strong>PO No</strong>'; ?></td>
            <td class="main">
<?php
 
    echo tep_draw_input_field('po_no', $cInfo->po_no, 'maxlength="32"', false);
 
?></td>
          </tr>
 		  <tr height="15px">
            <td class="main"><?php echo '<strong>Notes</strong>'; ?></td>
            <td class="main">
<?php
 
    //echo tep_draw_input_field('notes', $cInfo->notes, 'maxlength="32"', false);
	echo tep_draw_textarea_field('notes', 'no', '50', '10', $text = $cInfo->notes, $parameters = $cInfo, $reinsert_value = true);
 
?></td>
          </tr>  
           
         

    
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="smallText" colspan="2"><?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('action')))); ?></td>
      </tr></form></table>
<?php
  } else {
?>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
               <td class="dataTableHeadingContent"><?php echo 'Name'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Email'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Manufacturer'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Serial No'; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo 'Model'; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo 'Issue No'; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo 'Po No'; ?></td>
                   <td class="dataTableHeadingContent" align="right"><?php echo 'Status'; ?></td>
                  
                <td class="dataTableHeadingContent" align="right"><?php echo 'Action'; ?>&nbsp;</td>
              </tr>
<?php
  
    $customers_query_raw = "select * from rfq_order";
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
     

      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['rfq_id']))) && !isset($cInfo)) {
        
        $cInfo = new objectInfo($customers);
      }

      if (isset($cInfo) && is_object($cInfo) && ($customers['rfq_id'] == $cInfo->rfq_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers['rfq_id']) . '\'">' . "\n";
      }
	   $customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from 
	   customers c  where c.customers_id = '" . (int)$customers['customer_id']. "'");
          $customer_address = tep_db_fetch_array($customer_address_query);
?>
				 <td class="dataTableContent"><?php echo $customer_address['customers_firstname'].' '.$customer_address['customers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $customer_address['customers_email_address']; ?></td>
                 <td class="dataTableContent"><?php echo getManfct_name($customers['manufacturer']); ?></td>
                <td class="dataTableContent"><?php echo $customers['serial']; ?></td>
                <td class="dataTableContent" align="right"><?php echo ($customers['model']); ?></td>
                 <td class="dataTableContent" align="right"><?php echo ($customers['issue_no']); ?></td>
                  <td class="dataTableContent" align="right"><?php echo ($customers['po_no']); ?></td>
                   <td class="dataTableContent" align="right">
				   <?php if($customers['status']==0) echo "<b style=\"color:#e17009\">new</b>"; ?>
                   <?php if($customers['status']==1) echo "<b style=\"color:#2e6e9e\">accepted</b>"; ?>
                    <?php if($customers['status']==2) echo "<b style=\"color:#F00\">declined</b>"; ?>
                   
                   </td>
                  
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['rfq_id'] == $cInfo->rfq_id))
				 { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { 
				 echo '<a href="' . tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID')) . 'cID=' . $customers['rfq_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td class="smallText" align="right" colspan="2"><?php echo tep_draw_button(IMAGE_RESET, 'arrowrefresh-1-w', tep_href_link('rfq_orders.php')); ?></td>
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

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</strong>');

      $contents = array('form' => tep_draw_form('customers', 'rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm'));
      $contents[] = array('text' => 'Are you sure you want to Accept' . '<br /><br /><strong>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</strong>');
      if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button('Accept', '', null, 'primary'));
      break;
	  
	  case 'decline':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</strong>');

      $contents = array('form' => tep_draw_form('customers', 'rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqdecl'));
      $contents[] = array('text' => 'Are you sure you want to Decline' . '<br /><br /><strong>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</strong>');
      if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button('Decline', '', null, 'primary'));
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<strong>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</strong>');

        $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link('rfq_orders.php', 
		tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=edit')) . 
		tep_draw_button('Accept', '', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=confirm')) .
		tep_draw_button('Decline', 'trash', tep_href_link('rfq_orders.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=decline'))  
		 /*tep_draw_button(IMAGE_EMAIL, 'mail-closed', tep_href_link(FILENAME_MAIL, 'customer=' . $cInfo->customers_email_address))*/);
        
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
    </table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>