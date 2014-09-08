<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>
<style>
table a:link {
 color: #06F;
 font-weight: bold;
 text-decoration:none;
 
}
table a:visited {
 color: #999999;
 font-weight:bold;
 text-decoration:none;
}
table a:active,
table a:hover {
 color: #bd5a35;
 text-decoration:underline;
}
table {
 font-family:Arial, Helvetica, sans-serif;
 color:#666;
 font-size:12px;
 text-shadow: 1px 1px 0px #fff;
 background:#eaebec;
 margin:0px;
 padding:0px !important;
 border:#ccc 1px solid;

 -moz-border-radius:3px;
 -webkit-border-radius:3px;
 border-radius:3px;

 -moz-box-shadow: 0 1px 2px #d1d1d1;
 -webkit-box-shadow: 0 1px 2px #d1d1d1;
 box-shadow: 0 1px 2px #d1d1d1;
}
table th {
 padding:5px 5px 5px 5px;
 border-top:1px solid #fafafa;
 border-bottom:1px solid #e0e0e0;

 background: #ededed;
 background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
 background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child{
 text-align: left;
 padding-left:10px;
}
table tr:first-child th:first-child{
 -moz-border-radius-topleft:3px;
 -webkit-border-top-left-radius:3px;
 border-top-left-radius:3px;
}
table tr:first-child th:last-child{
 -moz-border-radius-topright:3px;
 -webkit-border-top-right-radius:3px;
 border-top-right-radius:3px;
}
table tr{
 text-align: center;
 padding-left:20px;
}
table tr td:first-child{
 text-align: left;
 padding-left:20px;
 border-left: 0;
}
table tr td {
 padding:10px;
 border-top: 1px solid #ffffff;
 border-bottom:1px solid #e0e0e0;
 border-left: 1px solid #e0e0e0;
 
 background: #fafafa;
 background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
 background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td{
 background: #f6f6f6;
 background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
 background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td{
 border-bottom:0;
}
table tr:last-child td:first-child{
 -moz-border-radius-bottomleft:3px;
 -webkit-border-bottom-left-radius:3px;
 border-bottom-left-radius:3px;
}
table tr:last-child td:last-child{
 -moz-border-radius-bottomright:3px;
 -webkit-border-bottom-right-radius:3px;
 border-bottom-right-radius:3px;
}
table tr:hover td{
 background: #f2f2f2;
 background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
 background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0); 
}
</style>
<?php
  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
  
 /* function getManfct_name($id)
{
	$manfct  = tep_db_fetch_array(tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = ".$id.""));
	return $manfct['manufacturers_name'];
}*/
  function get_order($id = ''){
	  $order_array= array();
	  $order_ids = tep_db_query("select rfq_od_id,rfq_id, user_id  from rfq_order_detail where `user_id` = '".$_SESSION['customer_id']."'");
	while($order_ids_list = tep_db_fetch_array($order_ids)){
		
		$order_array[] = $order_ids_list['rfq_id'];
		
	}
	if(empty($order_array)) {
		 $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0"><tr><td>You have not assigned any order yet</td></tr></table>';
		return $my_table_head;
	} else {
	  $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0">
              <tbody><tr >
			   <th>Date</th>
			   <th>PO#</th>
               <th >Name</th>
                <th>Group</th>
                 <th>Serial#</th>
				  <th>Issue#</th>
				   <th>View Detail</th>
				 
              </tr>';
			  $where='';
	  if($id!=''){
		  
		  $where=" WHERE customer_id=".$id;
		  
		  }
		  
	
	$userIDS =  implode(",",$order_array);
	//echo $userIDS; exit;
		  
	 $order_query = tep_db_query("select * from rfq_order where `order/quote_status` = '1' and rfq_id in (".$userIDS.") order by date_added desc");
	 
          $k=1;
while($order_val = tep_db_fetch_array($order_query)){
	$class="";
	if(($k%2)==0){
		
		$class="even";
	}else{
		$class="";
		}
	$customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address,c.customer_group_id from 
	   customers c  where c.customers_id = '" . (int)$order_val['customer_id']. "'");
          $customer_address = tep_db_fetch_array($customer_address_query);
	$my_table .='<tr class ="'.$class.'">';
	 $my_table .='<td class="dataTableContent">'.date("M d, Y h:i",strtotime($order_val['date_added'])).'</td>';
	$my_table .='<td class="dataTableContent">'.$order_val['po_no'].'</td>';
	  $my_table .='<td class="dataTableContent">'.$customer_address["customers_firstname"].' '.$customer_address["customers_lastname"].'</td>';
	   $my_table .='<td class="dataTableContent">';
	  if($customer_address['customer_group_id']==0){
		$my_table .='Custommer';
	  }else{
		 $my_table .=get_customer_group($customer_address['customer_group_id']);
	  }
	  $my_table .='</td>';
	 
	   $my_table .='<td class="dataTableContent">'.$order_val['serial'].'</td>';
	    $my_table .='<td class="dataTableContent">'.$order_val['issue_no'].'</td>';
		 
		  
	  
	 
	   $my_table .='<td class="dataTableContent"><a href="view_orderdetail.php?id='.$order_val["rfq_id"].'">View Detail</td>';
	
	  $my_table .='</tr>';
	  
 $k++;
	  }return $my_table_head.$my_table.'</table>';;
	}
}
?>

<h1><?php echo 'All Orders/All Quotes'; ?></h1>


<?php
  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }
?>

<div class="contentContainer">
 

  <div class="contentText">
  <?php  if(get_customer_type($_SESSION['customer_group_id'])=='admin' || get_customer_type($_SESSION['customer_group_id'])=='storeadmin') { echo get_order(); }else{ echo get_order($_SESSION['customer_id']); } ?>
   
  </div>


</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>