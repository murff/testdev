<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>
<?php define('EMAIL_SEPARATOR', '------------------------------------------------------');?>
<style>
table a:link {
 color: #666;
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
#commnetform table th
{
	padding-left:20px !important;
}
.customer
{
margin-bottom:3px !important;	
}
.customeradmin {
	margin-bottom:3px !important;
	color:#06F !important;
}
.notification {
	font-size: 13px;
	line-height: 18px;
	margin-bottom: 15px;
	position: relative;
	padding: 14px 40px 14px 18px;
	-webkit-box-shadow:  0px 2px 0px 0px rgba(0, 0, 0, 0.03);
	box-shadow:  0px 2px 0px 0px rgba(0, 0, 0, 0.03);
}

.notification p {margin: 0;}
.notification span {font-weight: 600;}

.notification.success,
.notification.success strong {
	background-color: #EBF6E0;
	color: #5f9025;
	border: 1px solid #b3dc82;
}
</style>
<?php
  require('includes/application_top.php');
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONFIRM);
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VIEW_DETAIL);
//return customer id
function get_customer_id($id){

	$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$id . "'");
        $customers_email = tep_db_fetch_array($customers_email_query);
		$cust_id=$customers_email['customer_id'];
		return $cust_id;
	}
	
//session id
 $login_id=$_SESSION['customer_id'];

//get id form url
	if(isset($_SESSION['lastorderid'])){
	 
	 $cust_id= $_SESSION['lastorderid'];
	
	}
	
	if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin')
	{
		
		tep_db_query("update rfq_order_comment_history set comments_viewed = 1 where `order_id` = ".$cust_id."");
	}


	

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
  function get_order1($id = ''){

	  $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0">
              <tbody>';
			  $where='';
	  if($id!=''){
		  
		  $where=" WHERE rfq_id=".$id;
		  
		  }
		  
	 $order_query = tep_db_query("select * from rfq_order " . $where);
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
	$my_table .='<tr class ="'.$class.'"><th >'.CUSTOMER_NAME.'</th>';
	  $my_table .='<td class="dataTableContent" align="left">'.$customer_address["customers_firstname"].' '.$customer_address["customers_lastname"].'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"> <th>'.CUSTOMER_EMAIL.'</th><td class="dataTableContent" align="left">'.$customer_address["customers_email_address"].'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"><th>'.CUSTOMER_GROUP.'</th><td class="dataTableContent" align="left">';
	  if($customer_address['customer_group_id']==0){
		$my_table .='Custommer';
	  }else{
		 $my_table .=get_customer_group($customer_address['customer_group_id']);
	  }
	  $my_table .='</td>';
	 
	  $my_table .='</tr>';
 $k++;
	  }return $my_table_head.$my_table.'</table>';
}


function get_order2($id){

	  $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0">
              <tbody>';
			  $where='';
	  if($id!=''){
		  
		  $where=" WHERE rfq_id=".$id;
		  
		  }
		  
	 $order_query = tep_db_query("select * from rfq_order " . $where);
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
	$my_table .='<tr class ="'.$class.'"><th '.MANFACTURER_NAME.'</th>';
	  $my_table .='<td class="dataTableContent">'.getManfct_name($order_val["manufacturer"]).'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"> <th>'.SERIAL.'</th><td class="dataTableContent">'.$order_val['serial'].'</td></tr>';
	 
	
	  $my_table .='</tr>';
 $k++;
	  }return $my_table_head.$my_table.'</table>';
}


 
  function get_order_detail($id){

	  $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0">
             
			 
			  <tbody>
			  
			  <tr height="15px">
                    	 <th class="main"><strong>'.QUANTITY_TITLE.'</strong></th>
                       
                         <th class="main"><strong>'.PART_TYPE_TITLE.'</strong></th>
                       
                         <th class="main"><strong>'.DESCRIPTION_TITLE.'</strong></th>
						
                        <th class="main"><strong>'.PART_NUMBER_TITLE.'</strong></th>';
						if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin') {
                         $my_table_head.='<th class="main"><strong>'.AVAILIBILITY.'</strong></th>';
						 }
						 
						if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' && get_request_type($id)=='order') {
                         $my_table_head.='<th class="main"><strong>'.ASSIGN_USER.'</strong></th>';
						 }
						 
                         if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin') {
                         $my_table_head.='<th class="main"><strong>'.STAPLES_PRICE.'</strong></th>';
						 }
                          $my_table_head.='<th class="main"><strong>'.CUSTOMER_PRICE.'</strong></th>';
						  if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin') {
                        // $my_table_head.='<th class="main"><strong>Action</strong></th>';
						 }
						  
                    $my_table_head.='</tr>';
		  
	$order_query= tep_db_query("select * from rfq_order_detail where rfq_id = '" .$id. "'");
          $k=1;
		global  $totalprice;
$is_present = false;
while($order_detail = tep_db_fetch_array($order_query)){
	$is_present = true;
	$class="";
	if(($k%2)==0){
		
		$class="even";
	}else{
		$class="";
		}
		$sprice = (int)$order_detail["price"];
		if($sprice > 0 && $sprice <=99){
		$stprice = 	number_format(($order_detail["price"]-12)/1.3);
		}
		else if($sprice >99){
		$stprice = 	number_format(($order_detail["price"]/1.3), '2', '.', ',');
		}
		else {
			
		$stprice = number_format($order_detail["price"], '2', '.', ',');
		}
		
		
		$totalprice +=$order_detail["price"];
         
                $my_table .='<tr height="25px"><td class="main">'.
    				 $order_detail["qty"].'
					</td>
                   
                    <td class="main">'.
					$order_detail["part_type"].' 
					</td>
                  
                    <td class="main">'.
					$order_detail["description"].'
					</td>
                   
                    <td class="main">'.
					$order_detail["part_number"].' 
					</td>';
					
					 if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin') {
                    $my_table .='<td class="main">';
					if($order_detail["user_id"]==0 && $order_detail["stock_availibility"]==''){
					$my_table .= '---';	
					}
					else if($order_detail["user_id"]!=0 && $order_detail["stock_availibility"]==''){
					$my_table .= '<b style="color:#e17009;">waiting</b>';		
					}
					else if($order_detail["user_id"]!=0 && $order_detail["stock_availibility"]!=''){
						if($order_detail["stock_availibility"]=='yes'){
					$my_table .= '<b style="color:#390;">InStock</b>';
					}
					else if($order_detail["stock_availibility"]=='no'){
					$my_table .= '<b style="color:#F00;">Out of Stock</b>';
					}
					}
					
					$my_table .='</td>';
					 }
					
					 if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' && get_request_type($id)=='order') {
                    $my_table .='<td class="main"><div id="loaderuser"><img src="images/opc-ajax-loader.gif" /></div>'.
					get_store_users($order_detail['rfq_od_id'],$order_detail['user_id']).'
					</td>';
					 }
					 if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin') {
                    $my_table .='<td class="main">$'.
					$order_detail["price2"].'
					</td>';
					 }
                    $my_table .='<td class="main">$'.
					$order_detail["price"].'
					</td>';
					if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin') {
                   // $my_table .='<td class="main"><img src="images/update.png" width="16" height = "16" alt="update order" title="update order" /></td>';
					 }
                    $my_table .='</tr>';
                  
	  
	  
	  
 $k++;
	  }


return $my_table_head.$my_table.'</table>';
}
?>


    <?php if($_SESSION['orderRequestType']=='quote') { ?>
  <div class="notification success"><?php echo SUCCESS_QUOTE; ?></div>
  <?php } else {?>
  
  <div class="notification success"><?php echo SUCCESS_ORDER; ?></div>
  <?php }?>
<?php  $customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . $cust_id . "'");
        $cust_nfo = tep_db_fetch_array($customers_query); 
$uppercase = ucfirst(get_request_type($cust_id));


if ($_SESSION['language']=='english') {
if($uppercase=='Order' || $uppercase=='Commander'){$uppercase='Order';}
if($uppercase=='Quote' || $uppercase=='Devis'){$uppercase='Quote';}
}


if ($_SESSION['language']=='french') {
    if($uppercase=='Order' || $uppercase=='Commander'){ $uppercase='Commander'; }
    if($uppercase=='Quote' || $uppercase=='Devis'){ $uppercase='Devis'; }
}
	?>
	<h1><?php echo  $uppercase.DETAIL_NO.$cust_id ?></h1><br>

<?php 
if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' && $cust_nfo['order/quote_status']=='0') {
		echo '&nbsp;&nbsp;'.tep_draw_button('Edit order', 'update', tep_href_link('edit_order.php', tep_get_all_get_params(array('id', 'action')) . 'id=' . $cust_id)).'&nbsp;'; 

}

//var_dump($cust_nfo['order_updates'],1, get_customer_type($_SESSION['customer_group_id']),'storeadmin',get_customer_type($_SESSION['customer_group_id']));
	
	
?> &nbsp;&nbsp;


<h3><?php echo CUSTOMER_DETAIL; ?></h3>


<?php
  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }
?>
<script type="text/javascript">

 function updateComment()

 { 
 
 //alert("");
 $("#loader").show();

var comment=$("#comment_history").val();
var cid=$("#cID").val();
 $.ajax({
   url:'comment_history.php?act=save&comment='+comment+'&cID='+cid,
    success: function(data){
 	//alert(data);
	 $("#loader").hide();
   $(".commentHistory").html(data);
  // $("#feedbackcommon"+act_id).show();
   
   
    }
   });
 }
 
//$('#update_assign_users').click(function() {
function update_assign_users() {
console.log('hi');
var $list = $('.assigned_user');
var is_all_filled = true;
for(var i=0;i<$list.length;i++) {
	if($($list[i]).val()=='') {
		is_all_filled = false;
	}
}
if(is_all_filled) {
	for(var i=0;i<$list.length;i++) {
		assignUser($($list[i]).val(), $($list[i]).attr('rfq_id'));
	}
}
}
//});
function assignUser(valu,orderid)

 { 
 
 //alert("");
 $("#loaderuser").show();


 $.ajax({
   url:'assign_user.php?act=save&val='+valu+'&orderid='+orderid,
    success: function(data){
 	//alert(data);
	 $("#loaderuser").hide();
   //$(".commentHistory").html(data);
  // $("#feedbackcommon"+act_id).show();
      }
   });
 }
 </script>

<div class="contentContainer">
 <?php echo tep_draw_form('neworder', tep_href_link('updateorder_process.php', '', 'SSL'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="contentText">
  
  <?php echo get_order1($cust_id); ?>
  <h3><?php echo CUSTOMER_ORDER_DETAIL; ?></h3>
 

  <table width="100%" cellspacing="0" cellpadding="10" border="0">
  <tbody>
    <tr class="">
      <th width="23%" align="left"><?php echo MANFACTURER_NAME; ?>
        <input type="hidden" name="cID" id="cID" value="<?php echo $cust_id; ?>" /></th>
      <td width="22%" align="left" class="dataTableContent"><?php echo  getManfct_name($cust_nfo['manufacturer']); ?></td>
      <th width="22%" align="left" class="dataTableContent"><?php echo SERIAL; ?></th>
      <td width="33%" align="left" class="dataTableContent"><?php echo $cust_nfo['serial']; ?></td>
    </tr>
    <tr class="">
      <th align="left"><?php echo MODEL; ?></th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['model']; ?></td>
      <th align="left"><?php echo ISSUE_NO; ?></th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['issue_no']; ?></td>
    </tr>
    <tr class="">
      <th align="left"><?php echo PO_TITLE; ?></th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['po_no']; ?></td>
      <th align="left"><?php echo NOTES; ?></th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['notes']; ?></td>
    </tr>
     <tr class="">
      <td colspan="4"><?php  echo get_order_detail($cust_id);

  ?></td>
      
    </tr>
    <?php //if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='customer')
	  //{ ?>
    
     
  </tbody>
</table>
</form>


  </div>


</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>