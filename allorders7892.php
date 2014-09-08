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
.arrow-up {
	width: 0; 
	height: 0; 
	border-left: 5px solid transparent;
	border-right: 5px solid transparent;
	
	border-bottom: 5px solid black;
}

.arrow-down {
	width: 0; 
	height: 0; 
	border-left: 20px solid transparent;
	border-right: 20px solid transparent;
	
	border-top: 20px solid #f00;
}
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
<style type="text/css">
 
    th[data-sort]{
      cursor:pointer;
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
   ?>
 
 <?php
 /* function getManfct_name($id)
{
	$manfct  = tep_db_fetch_array(tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = ".$id.""));
	return $manfct['manufacturers_name'];
}*/
  function get_order($id = ''){
	  $oid = isset($_GET['oid']) && $_GET['oid']=='n'?'s':'n';
	$date_added = isset($_GET['date_added']) && $_GET['date_added']=='n'?'s':'n';
	$po_no = isset($_GET['po_no']) && $_GET['po_no']=='n'?'s':'n';
	$customer_id = isset($_GET['customer_id']) && $_GET['customer_id']=='n'?'s':'n';
	$group_name = isset($_GET['group_name']) && $_GET['group_name']=='n'?'s':'n';
	$serial = isset($_GET['serial']) && $_GET['serial']=='n'?'s':'n';
	$issue_no = isset($_GET['issue_no']) && $_GET['issue_no']=='n'?'s':'n';
	$order_quote = isset($_GET['order_quote']) && $_GET['order_quote']=='n'?'s':'n';
	$status = isset($_GET['status']) && $_GET['status']=='n'?'s':'n';

	  $_GET['order_by'] = isset($_GET['order_by'])?$_GET['order_by']:'s';
	 
			
			  $where='';
	  if($id!=''){
		  
		  $where=" WHERE r.customer_id=".$id;
		  
		  }
	if($where=='') {
		$where = 'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status';
	} else {
		$where .= ' and c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status';
	}
	//var_dump("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added desc");
	// $where = $where==''?'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status':' and c.customer_id=r.customer_id and cg.customers_group_id = c.customers_group_id';
	 //var_dump("select * from rfq_order r,customers c,customers_groups cg " . $where . " order by r.date_added desc");
     $clasoid= 'class="header"';
	  $clasdate_added= 'class="header"';
	   $claspo_no= 'class="header"';
	    $clascustomer_id= 'class="header"';
		 $clasgroup_name= 'class="header"';
		 $classerial= 'class="header"';
		 $clasissue_no= 'class="header"';
		 $clasorder_quote= 'class="header"';
		  $classtatus= 'class="header"';
		  if($_GET['oid'] == 'n') { 
		  $clasoid ='class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.rfq_id asc");
	 } elseif($_GET['oid'] == 's') {
		 $clasoid ='class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.rfq_id desc");
	 }
		  
	else  if($_GET['date_added'] == 'n') { 
		  $clasdate_added ='class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added asc");
	 } elseif($_GET['date_added'] == 's') {
		 $clasdate_added ='class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.date_added desc");
	 } elseif($_GET['po_no'] == 'n') {
		  $claspo_no= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.po_no asc");
	 } elseif($_GET['po_no'] == 's') {
		  $claspo_no= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.po_no desc");
	 } elseif($_GET['customer_id'] == 'n') {
		  $clascustomer_id= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by c.customers_firstname asc");
	 } elseif($_GET['customer_id'] == 's') {
		  $clascustomer_id= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by c.customers_firstname desc");
	 } elseif($_GET['group_name'] == 'n') {
		 $clasgroup_name= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by cg.customers_group_name asc");
	 } elseif($_GET['group_name'] == 's') {
		 $clasgroup_name= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by cg.customers_group_name desc");
	 } elseif($_GET['serial'] == 'n') {
		  $classerial= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.serial asc");
	 } elseif($_GET['serial'] == 's') {
		  $classerial= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.serial desc");
	 } elseif($_GET['issue_no'] == 'n') {
		 $clasissue_no= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.issue_no asc");
	 } elseif($_GET['issue_no'] == 's') {
		 $clasissue_no= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.issue_no desc");
	 }  elseif($_GET['order_quote'] == 'n') {
		  $clasorder_quote= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.`order/quote` asc");
	 } elseif($_GET['order_quote'] == 's') {
		  $clasorder_quote= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.`order/quote` desc");
	 }  elseif($_GET['status'] == 'n') {
		  $classtatus= 'class="headerSortDown"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by ro.`name` asc");
	 } elseif($_GET['status'] == 's') {
		  $classtatus= 'class="headerSortUp"';
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.`order_updates` asc,ro.name desc");
	 } else  {
	 	$order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added desc");
	 }
	 
	 
	  $my_table_head='<table  width="100%" class="tablesorter" cellspacing="0" cellpadding="10" border="0">
              <thead><tr >
			   <th '.$clasoid.' ><a href="allorders2.php?oid='.$oid.'">oID</th>
			  <th '.$clasdate_added.' ><a href="allorders2.php?date_added='.$date_added.'">Date</a></th>
			   <th width="7%" '.$claspo_no.'><a href="allorders2.php?po_no='.$po_no.'"><span>PO#</span></a></th>
               <th '.$clascustomer_id.'><a href="allorders2.php?customer_id='.$customer_id.'">Name</a></th>
                <th '.$clasgroup_name.'><a href="allorders2.php?group_name='.$group_name.'">Group</a></th>
                 <th '.$classerial.'><a href="allorders2.php?serial='.$serial.'">Serial#</a></th>
				  <th '.$clasissue_no.' width="7%"><a href="allorders2.php?issue_no='.$issue_no.'">Issue#</a></th>
                  <th>View Detail</th>
				  <th '.$clasorder_quote.' width="10%"><a href="allorders2.php?order_quote='.$order_quote.'">Request </br>Type</a></th>
                   <th '.$classtatus.' width="12%"><a href="allorders2.php?status='.$status.'">Status</a></th>
              </tr></thead> <tbody>';
	 
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
	 $my_table .='<td class="dataTableContent">'.$order_val["rfq_id"].'</td>';
	 $my_table .='<td class="dataTableContent">'.date("m/d/Y h:i A",strtotime($order_val['date_added'])).'</td>';
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
		
		 
	  
	 
	   $my_table .='<td class="dataTableContent"><a href="view_detail.php?id='.$order_val["rfq_id"].'">View Detail</td>';
	  $my_table .='<td class="dataTableContent">'.$order_val['order/quote'].'</td>';
	  $my_table .='<td class="dataTableContent">';
	 
	  
	  if($order_val['order/quote']=='order'){
		  
		  $my_table .='Approved';
		  
		  }else {
				if($order_val['status']==0 && $order_val['order_updates']==0){ 
				$my_table .='<b style="color:#e17009;">Pending Pricing</b>';
				}
				if($order_val['order_updates']==1){ 
				$my_table .='<b style="color:#e17009;">Pending Customer Approval</b>';
				}
				if($order_val['status']==1){ 
				$my_table .='<b style="color:#390;">Accepted</b>';
				}
				if($order_val['status']==2){ 
				$my_table .='<b style="color:#F00;">Declined</b>';
				}
		  }
	  $my_table .='</td>';
	  $my_table .='</tr>';
	  
 $k++;
	  }return $my_table_head.$my_table.'</tbody></table>';;
}
?>

<h1><?php echo 'All Orders/All Quotes'; ?></h1>
<link rel="stylesheet" href="themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script src="jquery.tablesorter.js"></script>
 <script>
   $(document).ready(function() 
    { 
        $("#myTable").tablesorter({headers: { 7:{sorter: false}}}); 
    } 
); 
  </script>

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