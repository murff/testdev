<?php require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_UPDATE_STOCK);
define('EMAIL_SEPARATOR', '------------------------------------------------------');
if($_REQUEST['act']=='save') {
 $price_query = tep_db_query("update rfq_order_detail set stock_availibility = '".$_REQUEST['val']."' where rfq_od_id = ".$_REQUEST['orderid']."");
 
 		$oidItem = tep_db_fetch_array(tep_db_query("select * from rfq_order_detail where rfq_od_id = ".$_REQUEST['orderid'].""));
       
	    $rfq_id = ($oidItem['rfq_id']);
	
		$email_order = ORDER_NO.$_REQUEST['orderid'].WITH_ITEM.($oidItem['part_type']).' ('.$oidItem['description'].')'. STOCK_UPDATED. " \n" . " \n" .
		STOCK_AVAILIBILITY . "\n" .EMAIL_SEPARATOR . "\n" .$_REQUEST['val']. "\n\n" .
		
		" \n". EMAIL_SEPARATOR . "\n\n" .
		orderProductEmailassignItems($oidItem['rfq_id'],$_REQUEST['orderid']).              
		
 		" \n" . " \n" . EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "'>".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "</a>\n\n" .getSignature();
	
	    $email_order2 = ORDER_NO.$_REQUEST['orderid'].WITH_ITEM .($oidItem['part_type']).' ('.$oidItem['description'].')'.STOCK_UPDATED. " \n" . " \n" .
		STOCK_AVAILIBILITY . "\n" .EMAIL_SEPARATOR . "\n" .$_REQUEST['val']. "\n\n" .
		" \n". EMAIL_SEPARATOR . "\n\n" .
		orderProductEmailassignItems($oidItem['rfq_id'],$_REQUEST['orderid']).                
		
 		" \n" . " \n" . EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_orderdetail.php?id=" .$rfq_id. "'>".HTTP_SERVER_DIRPATH."view_orderdetail.php?id=" .$rfq_id. "</a>\n\n" .getSignature();
	
	
	   $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$_SESSION['customer_id']. "'");
       $customer_address = tep_db_fetch_array($customers_email);
			
	  $admin_email = tep_db_query("select c.customers_id,c.customers_firstname,c.customers_lastname, c.customers_email_address, c.customer_group_id, cg.customers_group_id,cg.customers_group_type
	  from customers c, customers_groups cg where c.customer_group_id = cg.customers_group_id and cg.customers_group_type ='storeadmin'");
      $admin_address = tep_db_fetch_array($admin_email);
	 
	 
	tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'] ,ORDER_NO.$_REQUEST['orderid'].WITH_ITEM.($oidItem['part_type']).STOCK_UPDATED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	 

	
	tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], HAVE_UPDATED_STOCK_ORDER.$_REQUEST['orderid'].WITH_ITEM .($oidItem['part_type']).'', $email_order2,STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
           
         
}
 ?>