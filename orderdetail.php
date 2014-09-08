<?php
/*
  $Id: stats_products_viewed.php,v 1.29 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  

  
  $order = tep_db_fetch_array(tep_db_query('SELECT * FROM `rfq_order` where rfq_id=1001'));
   
  //$list[] = array('Order No','Name', 'Email', 'Group', 'Manfacturer','Model','Issue No','PO No','Notes');
  $list[] = array('','','','','','','','Items Detail','','','','','','');
  $list_products[] = array('Order No','PO No','Serial','Model','Issue No','Manfacturer','Part Typee','Description','Part Number','Qty','Staples Price','Notes','Name','Email');
 	 
	 
	 $qry_pro = tep_db_query("SELECT * FROM `rfq_order_detail` where rfq_id='".$order['rfq_id']."'");
	 while($pro = tep_db_fetch_array($qry_pro)){
	 	$list_products[] = array($order['rfq_id'],$order['po_no'],$order['serial'],$order['model'],$order['issue_no'], getManfct_name($order['manufacturer']),
		$pro['part_type'],$pro['description'],$pro['part_number'],$pro['qty'],$order['serial'],$pro['price2'],get_customer_fullname($pro['customer_id']),get_customer_emailaddress($pro['customer_id']));
  
	 }
  $fp = fopen('invoice.csv', 'w');

  foreach ($list as $fields) {
	  fputcsv($fp, $fields);
  }
  
  foreach ($list_products as $pfields) {
	  fputcsv($fp, $pfields);
  }

  fclose($fp);
  
