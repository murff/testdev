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
#loader, #loaderuser
{
margin:10px;
display:none;	
}

table tr:hover td.custom{
 background: #FFF !important;
 background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
 background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0); 
}
</style>
<?php
  require('includes/application_top.php');

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
if(isset($_GET['id'])){
	 
	 $cust_id= $_GET['id'];
	
	}



if(isset($_GET['action']) && $_GET['action']=='confirm' ){
	
	 $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

	  $email_order = "Your Quote Order NO " .$rfq_id. " Has been Accepted \n" . 
                 EMAIL_SEPARATOR . "\n\n" .
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "'>".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "</a>\n\n" ;
		
		//get_customer_id from a function
      $customer_id=get_customer_id($rfq_id);
	  
	  //query for customer information
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
	  
	  
	 $email_order2  = "You have Accepted the order NO ".$rfq_id. "\n\n" .
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_detail.php?id=".$rfq_id."'>".HTTP_SERVER_DIRPATH."view_detail.php?id=".$rfq_id."</a>\n\n" ;
		
		//query for admin information
		$admin_email = tep_db_query("select * from customers where customers_id = '" . (int)$login_id. "'");
      $admin_address = tep_db_fetch_array($admin_email);
	  $admin_address['customers_email_address'];
	   $customer_address['customers_email_address'];
	  
	 	 tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'] , 'Quote Order Accepted', $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);	  
		
	tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Accepted', $email_order,$admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'] );
		
      
          tep_db_query("update rfq_order set status = 1 where rfq_id = '" . (int)$rfq_id . "'");
	 tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
        break;
	
	
	
	}
	
if(isset($_GET['action']) && $_GET['action']=='decline' ){
	
	 $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		
		$email_order = "Your Quote Order NO " .$rfq_id. " Has been Declined \n" . 
                 EMAIL_SEPARATOR . "\n\n" .
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "'>".HTTP_SERVER_DIRPATH."view_detail.php?id=" .$rfq_id. "</a>\n\n" ;
	   
	  //get_customer_id function retun customer id
      $customer_id=get_customer_id($rfq_id);
	  
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
		
		  //////////////////////////////////////////////////////////////////////////////////////
	 $email_order2 = "You have Declined the order NO ".$rfq_id. "\n\n" .
 		 'View Order:' . "\n" .EMAIL_SEPARATOR . "\n<a href='".HTTP_SERVER_DIRPATH."view_detail.php?id=".$rfq_id."'>".HTTP_SERVER_DIRPATH."view_detail.php?id=".$rfq_id."</a>\n\n" ;
		 
	$admin_email = tep_db_query("select * from customers where customers_id = '" . (int)$login_id. "'");
      $admin_address = tep_db_fetch_array($admin_email);
	  //echo $admin_address['customers_email_address'];
		  tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'] , 'Quote Order Declined', $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);	 
////////////////////////////////////////////////////////////////////////////////////////
		
	tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Declined', $email_order,$admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'] );
           
          tep_db_query("update rfq_order set status = 2 where rfq_id = '" . (int)$rfq_id . "'");
          tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
		  
        break;
	
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
	$my_table .='<tr class ="'.$class.'"><th >Name</th>';
	  $my_table .='<td class="dataTableContent" align="left">'.$customer_address["customers_firstname"].' '.$customer_address["customers_lastname"].'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"> <th>Email</th><td class="dataTableContent" align="left">'.$customer_address["customers_email_address"].'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"><th>Group</th><td class="dataTableContent" align="left">';
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
	$my_table .='<tr class ="'.$class.'"><th >Manufacturer</th>';
	  $my_table .='<td class="dataTableContent">'.getManfct_name($order_val["manufacturer"]).'</td></tr>';
	  $my_table .='<tr class ="'.$class.'"> <th>Serial No</th><td class="dataTableContent">'.$order_val['serial'].'</td></tr>';
	 
	
	  $my_table .='</tr>';
 $k++;
	  }return $my_table_head.$my_table.'</table>';
}


 
  function get_order_detail($id){

	  $my_table_head='<table width="100%" cellspacing="0" cellpadding="10" border="0">
             
			 
			  <tbody>
			  
			  <tr height="15px">
                    	 <th class="main"><strong>Qty</strong></th>
                       
                         <th class="main"><strong>Part Type</strong></th>
                       
                         <th class="main"><strong>Description</strong></th>
						
                        <th class="main"><strong>Part Number</strong></th>';
						
                         $my_table_head.='
						 <th class="main"><strong>Availibility?</strong></th>
						 ';
						 
						// }
						 
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
while($order_detail = tep_db_fetch_array($order_query)){
	$class="";
	if(($k%2)==0){
		
		$class="even";
	}else{
		$class="";
		}
         
                $my_table .='<tr height="25px" class ="'.$class.'"><td class="main">'.
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
					
					// if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' && get_request_type($id)=='order') {
                  /*  $my_table .='<td class="main">&nbsp;'.
					get_customer_fullname($order_detail['user_id']).'
					&nbsp;--</td>';*/
					$disbaled = '';
					if($_SESSION['customer_id']!=$order_detail['user_id']){ $disbaled = 'disabled=disbaled'; }
					
					 $my_table .='<td class="main"><div id="loaderuser"><img src="images/opc-ajax-loader.gif" /></div>&nbsp;
					 			<select name="inStock" id="inStock" '.$disbaled.' onchange="updateItemStatus(this.value,'.$order_detail['rfq_od_id'].')">
								  <option>select</option>
								  <option value="yes"';
								  if($order_detail['stock_availibility']=='yes') {
									$my_table .='selected=selected';  
								  }
								  $my_table .='>In Stock</option>
								  <option value="no"';
								  if($order_detail['stock_availibility']=='no') {
									$my_table .='selected=selected';  
								  }
								  $my_table .='>Out of Stock</option>
								</select></td>';
					//}
					 if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin') {
                    $my_table .='<td class="main">$'.
					get_category_price($order_detail["part_type"]).'
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
	  }return $my_table_head.$my_table.'</table>';
}
?>
 <?php  $customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . $cust_id . "'");
        $cust_nfo = tep_db_fetch_array($customers_query); 
	?>
<h1><?php echo  strtoupper(get_request_type($cust_id)).' Detail NO: '.$cust_id ?></h1><br>

<?php 




//echo '&nbsp;'.tep_draw_button('Back', '', tep_href_link('allordersuser.php', '')) ;
?> &nbsp;&nbsp;


<h3><?php echo 'Customer Detail'; ?></h3>


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
 
function updateItemStatus(valu,orderid)

 { 
 
 //alert("");
 $("#loaderuser").show();


 $.ajax({
   url:'update_stock.php?act=save&val='+valu+'&orderid='+orderid,
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
  <h3><?php echo 'Order Detail'; ?></h3>
 
  <table width="100%" cellspacing="0" cellpadding="10" border="0">
  <tbody>
    <tr class="">
      <th width="23%" align="left">Manfacturer
        <input type="hidden" name="cID" id="cID" value="<?php echo $cust_id; ?>" /></th>
      <td width="22%" align="left" class="dataTableContent"><?php echo  getManfct_name($cust_nfo['manufacturer']); ?></td>
      <th width="22%" align="left" class="dataTableContent">Serial</th>
      <td width="33%" align="left" class="dataTableContent"><?php echo $cust_nfo['serial']; ?></td>
    </tr>
    <tr class="">
      <th align="left">Model</th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['model']; ?></td>
      <th align="left">Issue No</th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['issue_no']; ?></td>
    </tr>
    <tr class="">
      <th align="left">PO No</th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['po_no']; ?></td>
      <th align="left">Notes</th>
      <td class="dataTableContent" align="left"><?php echo $cust_nfo['notes']; ?></td>
    </tr>
     <tr class="">
      <td colspan="4" class="custom"><?php  echo get_order_detail($cust_id);

  ?></td>
      
    </tr>
    <?php if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin')
	  { ?>
      <tr class="">
      <th>Tracking Number</th>
      <td colspan="3" align="left" class="dataTableContent"><input type="text" disabled="disabled" name="tracking_number" id="tracking_number" value="<?php echo $cust_nfo['tracking_number']; ?>" /></td>
      </tr>
      
       <tr class="">
      <th>Tracking Text</th>
      <td colspan="3" align="left" class="dataTableContent"><textarea disabled="disabled" name="tracking_text" id="tracking_text" cols="45" rows="5"><?php echo $cust_nfo['tracking_text']; ?></textarea></td>
      </tr>
      <?php } if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin') {?> 
      
      <tr class="">
      <th>Status</th>
      <td colspan="3" align="left" class="dataTableContent"><select name="order_status" id="order_status" disabled="disabled">
        <option value="0" <?php if($cust_nfo['status']==0) echo "selected"; ?>>New</option>
        <option value="1" <?php if($cust_nfo['status']==1) echo "selected"; ?>>Accepted</option>
        <option value="2" <?php if($cust_nfo['status']==2) echo "selected"; ?>>Declined</option>
        <option value="3" <?php if($cust_nfo['status']==3) echo "selected"; ?>>Shipped</option>
      </select></td>
      </tr>
     
    <?php } ?>
    
     <?php 
    if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin') {?> 
<?php /*?> <tr class="">
        <td colspan="4" align="right" style="text-align:right;">  <input type="submit" name="Order" id="Order" value="Update Order" class="myButton" /></td>
      </tr><?php */?>

 <?php } ?>
     
  </tbody>
</table>
<br /><br />
</form>

  </div>


</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>