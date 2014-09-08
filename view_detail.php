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
</style>
<script type="text/javascript">

 function updateComment()

 { 
 
 //alert("");
 $("#loader").show();

var comment=$("#comment_history").val();
comment=encodeURIComponent(comment);
alert(comment); 
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
<?php
 
require('includes/application_top.php');
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
if(isset($_GET['id'])){
	 
	 $cust_id= $_GET['id'];
	
}
        //setting up rfq_id to the current id from url
$rfq_id = tep_db_prepare_input($_GET['id']);
$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$rfq_id . "'");
$customers_email = tep_db_fetch_array($customers_email_query);
$customer_id_tmp = $customers_email['customer_id'];
$order_owner_id = $customers_email['order_owner'];

$order = tep_db_fetch_array(tep_db_query("SELECT * FROM `rfq_order` where rfq_id = '" . (int)$rfq_id . "'"));
   
if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='admin')
{
		
    tep_db_query("update rfq_order_comment_history set comments_viewed = 1 where `order_id` = ".$cust_id."");
}

if (isset($_GET['action']) && $_GET['action'] == 'rfqconfirm') {
    $sql_data_array = array('order_updates' => 0, 'status' => 1, 'date_added' => date("Y-m-d H:i:s"), 'expected_date' => date("dd/mm/yyyy"), '`order/quote_status`' => 1, 'po_no' => $_GET['po_no']);

    tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int) $_GET['id'] . "'");
    $list[] = array('', '', '', '', '', '', '', '', 'Items Detail', '', '', '', '', '', '');
    $list_products[] = array('Order No', 'PO No', 'Date', 'Serial', 'Model', 'Issue No', 'Manfacturer', 'Part Type', 'Description', 'Part Number', 'Qty', 'Staples Price', 'Notes', 'Name', 'Email');


    $qry_pro = tep_db_query("SELECT * FROM `rfq_order_detail` where rfq_id='" . $order['rfq_id'] . "'");
    while ($pro = tep_db_fetch_array($qry_pro)) {
        $list_products[] = array($order['rfq_id'], $order['po_no'], date("m/d/Y h:i A", strtotime($order['date_added'])), $order['serial'], $order['model'], $order['issue_no'], getManfct_name($order['manufacturer']),
            $pro['part_type'], $pro['description'], $pro['part_number'], $pro['qty'], $pro['price2'], $order['notes'], get_customer_fullname($order['customer_id']), get_customer_emailaddress($order['customer_id']));
    }
    $file = 'staplesorder_' . $rfq_id . '.csv';
    $filename = 'staplesorder_' . $rfq_id;
    if (file_exists($file)) {
        unlink($file);
    }
    $fp = fopen($file, 'w');

    foreach ($list as $fields) {
        fputcsv($fp, $fields);
    }

    foreach ($list_products as $pfields) {
        fputcsv($fp, $pfields);
    }

    fclose($fp);

    $email_order2 = UPDATED_QUOTE_ORDER_NO . $_GET['id'] . HAS_BEEN_ACCEPTED . EMAIL_SEPARATOR . "\n\n" .
            orderProductEmail($_GET['id']) .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "&action=view'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "&action=view</a>\n\n" . getSignature();

    //ger_customer_i function retun customer id
    $customer_id = get_customer_id($_GET['id']);

    $customers_email = tep_db_query("select * from customers where customers_id = '" . (int) $customer_id . "'");
    $customer_address = tep_db_fetch_array($customers_email);
    //////////////////////////////////////////////////////////////////////////////////////
    $email_order = ACCEPTED_THE_UPDATED_ORDER . $_GET['id'] . "\n\n" . EMAIL_SEPARATOR . "\n\n" .
            orderProductEmail($_GET['id']) .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?po=" . $_GET['id'] . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "</a>\n\n" . getSignature();

    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, UPDATED_QUOTE_ORDER_UPDATED, $email_order2, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));

    //tep_mail_attach(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , 'Updated Quote Order Accepted', $email_order2,get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']),'order.csv','order','csv');
    tep_mail_attach(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, UPDATED_QUOTE_ORDER_UPDATED, $email_order2, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']), $file, 'csv', $filename);
    unlink($file);
    tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), UPDATED_QUOTE_ORDER_UPDATED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    if ($order_owner_id != 0) {
        tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), UPDATED_QUOTE_ORDER_UPDATED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    if ($customer_id_tmp != 0) {
        $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
        $customer_address = tep_db_fetch_array($customers_email);
        if ($customer_address['staples_admin'] != 0) {
            tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), UPDATED_QUOTE_ORDER_UPDATED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
    }

    tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
    break;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
else {
    if (isset($_GET['action']) && $_GET['action'] == 'rfqdecl') {
        $sql_data_array = array('order_updates' => 2, 'status' => 2, 'date_added' => date("Y-m-d H:i:s"), '`order/quote_status`' => 0);

        tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int) $_GET['id'] . "'");


        $email_order = HAVE_DECLINE_UPDATED_ORDER_NO . $_GET['id'] . "\n\n" . EMAIL_SEPARATOR . "\n\n" .
                orderProductEmail($_GET['id']) .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "</a>\n\n" . getSignature();


        //ger_customer_i function retun customer id
        $customer_id = get_customer_id($_GET['id']);

        $customers_email = tep_db_query("select * from customers where customers_id = '" . (int) $customer_id . "'");
        $customer_address = tep_db_fetch_array($customers_email);
        //////////////////////////////////////////////////////////////////////////////////////
        $email_order2 = UPDATED_QUOTE_ORDER_NO . $_GET['id'] . HAS_BEEN_DECLINED . EMAIL_SEPARATOR . "\n\n" .
                orderProductEmail($_GET['id']) .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "&action=view'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $_GET['id'] . "&action=view</a>\n\n" . getSignature();

        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, UPDATED_QUOTE_ORDER_DECLINED, $email_order2, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));

        tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), UPDATED_QUOTE_ORDER_DECLINED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        if ($order_owner_id != 0) {
            tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), UPDATED_QUOTE_ORDER_DECLINED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
        if ($customer_id_tmp != 0) {
            $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
            $customer_address = tep_db_fetch_array($customers_email);
            if ($customer_address['staples_admin'] != 0) {
                tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), UPDATED_QUOTE_ORDER_DECLINED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
        }
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
        break;
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//when order is approved by itemnet admin
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['action']) && $_GET['action'] == 'confirm') {

    $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

    $email_order = QUOTE_OEDER_NO . $rfq_id . HAS_BEEN_ACCEPTED .
            EMAIL_SEPARATOR . "\n\n" .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

    //get_customer_id from a function
    $customer_id = get_customer_id($rfq_id);

    //query for customer information
    $customers_email = tep_db_query("select * from customers where customers_id = '" . (int) $customer_id . "'");
    $customer_address = tep_db_fetch_array($customers_email);


    $email_order2 = ACCEPTED_ORDER_NO . $rfq_id . "\n\n" .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

    //query for admin information
    $admin_email = tep_db_query("select * from customers where customers_id = '" . (int) $login_id . "'");
    $admin_address = tep_db_fetch_array($admin_email);
    $admin_address['customers_email_address'];
    $customer_address['customers_email_address'];

    tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'], 'Quote Order Accepted', $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'Quote Order Accepted', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


    tep_db_query("update rfq_order set status = 1 where rfq_id = '" . (int) $rfq_id . "'");
    tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
    break;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//when order is declined 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['action']) && $_GET['action'] == 'decline') {

    $rfq_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

    $email_order = QUOTE_OEDER_NO . $rfq_id . HAS_BEEN_DECLINED .
            EMAIL_SEPARATOR . "\n\n" .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

    //get_customer_id function retun customer id
    $customer_id = get_customer_id($rfq_id);

    $customers_email = tep_db_query("select * from customers where customers_id = '" . (int) $customer_id . "'");
    $customer_address = tep_db_fetch_array($customers_email);

    //////////////////////////////////////////////////////////////////////////////////////
    $email_order2 = HAVE_DECLINED_THE_ORDER_NO . $rfq_id . "\n\n" .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

    $admin_email = tep_db_query("select * from customers where customers_id = '" . (int) $login_id . "'");
    $admin_address = tep_db_fetch_array($admin_email);
    //echo $admin_address['customers_email_address'];
    tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'], QUOTE_ORDER_DECLINED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
////////////////////////////////////////////////////////////////////////////////////////

    tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], QUOTE_ORDER_DECLINED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    tep_db_query("update rfq_order set status = 2 where rfq_id = '" . (int) $rfq_id . "'");
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

function get_order1($id = '') {

    $my_table_head = '<table width="100%" cellspacing="0" cellpadding="10" border="0">
              <tbody>';
    $where = '';
    if ($id != '') {

        $where = " WHERE rfq_id=" . $id;
    }

    $order_query = tep_db_query("select * from rfq_order " . $where);
    $k = 1;
    while ($order_val = tep_db_fetch_array($order_query)) {
        $class = "";
        if (($k % 2) == 0) {

            $class = "even";
        } else {
            $class = "";
        }
        $customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address,c.customer_group_id from 
	   customers c  where c.customers_id = '" . (int) $order_val['customer_id'] . "'");
        $customer_address = tep_db_fetch_array($customer_address_query);
        $my_table .='<tr class ="' . $class . '"><th >' . CUSTOMER_NAME . '</th>';
        $my_table .='<td class="dataTableContent" align="left">' . $customer_address["customers_firstname"] . ' ' . $customer_address["customers_lastname"] . '</td></tr>';
        $my_table .='<tr class ="' . $class . '"> <th>' . CUSTOMER_EMAIL . '</th><td class="dataTableContent" align="left">' . $customer_address["customers_email_address"] . '</td></tr>';
        $my_table .='<tr class ="' . $class . '"><th>' . CUSTOMER_GROUP . '</th><td class="dataTableContent" align="left">';
        if ($customer_address['customer_group_id'] == 0) {
            $my_table .='Custommer';
        } else {
            $my_table .=get_customer_group($customer_address['customer_group_id']);
        }
        $my_table .='</td>';

        $my_table .='</tr>';
        $k++;
    }return $my_table_head . $my_table . '</table>';
}

function get_order2($id) {

    $my_table_head = '<table width="100%" cellspacing="0" cellpadding="10" border="0">
              <tbody>';
    $where = '';
    if ($id != '') {

        $where = " WHERE rfq_id=" . $id;
    }

    $order_query = tep_db_query("select * from rfq_order " . $where);
    $k = 1;
    while ($order_val = tep_db_fetch_array($order_query)) {
        $class = "";
        if (($k % 2) == 0) {

            $class = "even";
        } else {
            $class = "";
        }
        $customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address,c.customer_group_id from 
	   customers c  where c.customers_id = '" . (int) $order_val['customer_id'] . "'");
        $customer_address = tep_db_fetch_array($customer_address_query);
        $my_table .='<tr class ="' . $class . '"><th >' . MANFACTURER_NAME . '</th>';
        $my_table .='<td class="dataTableContent">' . getManfct_name($order_val["manufacturer"]) . '</td></tr>';
        $my_table .='<tr class ="' . $class . '"> <th>' . SERIAL . '</th><td class="dataTableContent">' . $order_val['serial'] . '</td></tr>';


        $my_table .='</tr>';
        $k++;
    }return $my_table_head . $my_table . '</table>';
}

//fetching values from database to show on view detail page

function get_order_detail($id) {

    $my_table_head = '<table width="100%" cellspacing="0" cellpadding="10" border="0">
             
			 
			  <tbody>
			  
			  <tr height="15px">
                    	 <th class="main"><strong>' . QUANTITY_TITLE . '</strong></th>
                       
                         <th class="main"><strong>' . PART_TYPE_TITLE . '</strong></th>
                       
                         <th class="main"><strong>' . DESCRIPTION_TITLE . '</strong></th>
						
                        <th class="main"><strong>' . PART_NUMBER_TITLE . '</strong></th>';
    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
        $my_table_head.='<th class="main"><strong>' . AVAILIBILITY . '</strong></th>';
    }

    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' && get_request_type($id) == 'order') {
        $my_table_head.='<th class="main"><strong>' . ASSIGN_USER . '</strong></th>';
    }

    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'buyer') {
        $my_table_head.='<th class="main"><strong>' . STAPLES_PRICE . '</strong></th>';
    }
    $my_table_head.='<th class="main"><strong>' . CUSTOMER_PRICE . '</strong></th>';
    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
        // $my_table_head.='<th class="main"><strong>Action</strong></th>';
    }

    $my_table_head.='</tr>';

    $order_query = tep_db_query("select * from rfq_order_detail where rfq_id = '" . $id . "'");
    $k = 1;
    global $totalprice;
    $is_present = false;
    while ($order_detail = tep_db_fetch_array($order_query)) {
        $is_present = true;
        $class = "";
        if (($k % 2) == 0) {

            $class = "even";
        } else {
            $class = "";
        }
        $sprice = (int) $order_detail["price"];
        if ($sprice > 0 && $sprice <= 99) {
            $stprice = number_format(($order_detail["price"] - 12) / 1.3);
        } else if ($sprice > 99) {
            $stprice = number_format(($order_detail["price"] / 1.3), '2', '.', ',');
        } else {

            $stprice = number_format($order_detail["price"], '2', '.', ',');
        }


        $totalprice +=$order_detail["price"];

        $my_table .='<tr height="25px"><td class="main">' .
                $order_detail["qty"] . '
					</td>
                   
                    <td class="main">' .
                $order_detail["part_type"] . ' 
					</td>
                  
                    <td class="main">' .
                $order_detail["description"] . '
					</td>
                   
                    <td class="main">' .
                $order_detail["part_number"] . ' 
					</td>';

        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
            $my_table .='<td class="main">';
            if ($order_detail["user_id"] == 0 && $order_detail["stock_availibility"] == '') {
                $my_table .= '---';
            } else if ($order_detail["user_id"] != 0 && $order_detail["stock_availibility"] == '') {
                $my_table .= '<b style="color:#e17009;">waiting</b>';
            } else if ($order_detail["user_id"] != 0 && $order_detail["stock_availibility"] != '') {
                if ($order_detail["stock_availibility"] == 'yes') {
                    $my_table .= '<b style="color:#390;">InStock</b>';
                } else if ($order_detail["stock_availibility"] == 'no') {
                    $my_table .= '<b style="color:#F00;">Out of Stock</b>';
                }
            }

            $my_table .='</td>';
        }

        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' && get_request_type($id) == 'order') {
            $my_table .='<td class="main"><div id="loaderuser"><img src="images/opc-ajax-loader.gif" /></div>' .
                    get_store_users($order_detail['rfq_od_id'], $order_detail['user_id']) . '
					</td>';
        }
        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'buyer') {
            $my_table .='<td class="main">$' .
                    $order_detail["price2"] . '
					</td>';
        }
        $my_table .='<td class="main">$' .
                $order_detail["price"] . '
					</td>';
        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
            // $my_table .='<td class="main"><img src="images/update.png" width="16" height = "16" alt="update order" title="update order" /></td>';
        }
        $my_table .='</tr>';




        $k++;
    }

    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' && get_request_type($id) == 'order') {
        $my_table .= '<tr><td>
<a id="update_assign_users" href="javascript:void(0);" onclick="update_assign_users()">Update</a>
</td>
</tr>
';
    }
    return $my_table_head . $my_table . '</table>';
}

function get_order_detailitemnetuser($id) {

    $my_table_head = '<table width="100%" cellspacing="0" cellpadding="10" border="0">
             
			 
			  <tbody>
			  
			  <tr height="15px">
                    	 <th class="main"><strong>' . QUANTITY_TITLE . '</strong></th>
                       
                         <th class="main"><strong>' . PART_TYPE_TITLE . '</strong></th>
                       
                         <th class="main"><strong>' . DESCRIPTION_TITLE . '</strong></th>
						
                        <th class="main"><strong>' . PART_NUMBER_TITLE . '</strong></th>';

    $my_table_head.='
		<th class="main"><strong>' . AVAILIBILITY . '</strong></th>
						 ';



    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'admin') {
        $my_table_head.='<th class="main"><strong>' . STAPLES_PRICE . '</strong></th>';
    }
    $my_table_head.='<th class="main"><strong>' . CUSTOMER_PRICE . '</strong></th>';
    if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
        // $my_table_head.='<th class="main"><strong>Action</strong></th>';
    }

    $my_table_head.='</tr>';

    $order_query = tep_db_query("select * from rfq_order_detail where rfq_id = '" . $id . "'");
    $k = 1;
    while ($order_detail = tep_db_fetch_array($order_query)) {
        $class = "";
        if (($k % 2) == 0) {

            $class = "even";
        } else {
            $class = "";
        }

        $my_table .='<tr height="25px" class ="' . $class . '"><td class="main">' .
                $order_detail["qty"] . '
					</td>
                   
                    <td class="main">' .
                $order_detail["part_type"] . ' 
					</td>
                  
                    <td class="main">' .
                $order_detail["description"] . '
					</td>
                   
                    <td class="main">' .
                $order_detail["part_number"] . ' 
					</td>';

        $disbaled = '';
        if ($_SESSION['customer_id'] != $order_detail['user_id']) {
            $disbaled = 'disabled=disbaled';
        }

        $my_table .='<td class="main"><div id="loaderuser"><img src="images/opc-ajax-loader.gif" /></div>&nbsp;
					 			<select name="inStock" id="inStock" ' . $disbaled . ' onchange="updateItemStatus(this.value,' . $order_detail['rfq_od_id'] . ')">
								  <option>select</option>
								  <option value="yes"';
        if ($order_detail['stock_availibility'] == 'yes') {
            $my_table .='selected=selected';
        }
        $my_table .='>In Stock</option>
								  <option value="no"';
        if ($order_detail['stock_availibility'] == 'no') {
            $my_table .='selected=selected';
        }
        $my_table .='>Out of Stock</option>
								</select></td>';
        //}
        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'admin') {
            $my_table .='<td class="main">$' .
                    get_category_price($order_detail["part_type"]) . '
					</td>';
        }
        $my_table .='<td class="main">$' .
                $order_detail["price"] . '
					</td>';
        if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
            // $my_table .='<td class="main"><img src="images/update.png" width="16" height = "16" alt="update order" title="update order" /></td>';
        }
        $my_table .='</tr>';




        $k++;
    }return $my_table_head . $my_table . '</table>';
}
?>
<?php
$customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . $cust_id . "'");
$cust_nfo = tep_db_fetch_array($customers_query);
$uppercase = ucfirst(get_request_type($cust_id));


if ($_SESSION['language'] == 'english') {
    if ($uppercase == 'Order' || $uppercase == 'Commander') {
        $uppercase = 'Order';
    }
    if ($uppercase == 'Quote' || $uppercase == 'Devis') {
        $uppercase = 'Quote';
    }
}


if ($_SESSION['language'] == 'french') {
    if ($uppercase == 'Order' || $uppercase == 'Commander') {
        $uppercase = 'Commander';
    }
    if ($uppercase == 'Quote' || $uppercase == 'Devis') {
        $uppercase = 'Devis';
    }
}
?>
	 

<h1><?php echo $uppercase . DETAIL_NO . $cust_id ?></h1><br>

<?php
if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' && $cust_nfo['order/quote_status'] == '0') {
    echo '&nbsp;&nbsp;' . tep_draw_button(EDIT_ORDER, 'update', tep_href_link('edit_order.php', tep_get_all_get_params(array('id', 'action')) . 'id=' . $cust_id)) . '&nbsp;';
}

if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {


    echo tep_draw_button(PICK_LIST, 'document', tep_href_link('pick_list.php', 'oID=' . $cust_id.'&cur_user='.$_SESSION['customer_id'] ), null, array('newwindow' => true)) . '&nbsp;' . tep_draw_button(PACKING_SLIP, 'document', tep_href_link('packing_invoice.php', 'oID=' . $cust_id), null, array('newwindow' => true)) . '&nbsp;';
}
if ($cust_nfo['order_updates'] == 0 && $cust_nfo['status'] == 0 && get_customer_type($_SESSION['customer_group_id']) != 'storeadmin' && get_customer_type($_SESSION['customer_group_id']) != 'storeusers' && get_customer_type($_SESSION['customer_group_id']) != 'customer' && get_customer_type($_SESSION['customer_group_id']) != 'buyer') {
    echo '&nbsp;&nbsp;' . tep_draw_button(DECLINE, 'trash', tep_href_link('view_detail.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqdecl')) . '&nbsp;';
    ?>
    			
    			<script>
    				$("#accept").click(function() {					
    					var po_no = $("#po_no").val();
    					if(po_no!='') {
    						location.href = '<?php echo htmlspecialchars_decode(tep_href_link('view_detail.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm')); ?>&po_no='+po_no;
    					} else {
    						$("#po_no_error").html(' PO No is mandatory');
    					}
    				});
    			</script>
    <?php
}

if ($cust_nfo['order_updates'] == 1 && $cust_nfo['status'] == 0 && (get_customer_type($_SESSION['customer_group_id']) == 'customer' || get_customer_type($_SESSION['customer_group_id']) == 'admin')) {
    //echo '&nbsp;&nbsp;'.tep_draw_button('Accept', '', tep_href_link('view_detail.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm')); 
    echo '&nbsp;&nbsp;' . tep_draw_button(DECLINE, 'trash', tep_href_link('view_detail.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqdecl')) . '&nbsp;';
    ?>
    			<button style="margin-top:5px;"  id = "accept" class = "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-priority-secondary">
    			<span class="ui-button-icon-primary ui-icon ui-icon-"></span><span class="ui-button-text">Accept</span></button>
    			<script>
    				$("#accept").click(function() {					
    					var po_no = $("#po_no").val();
    					if(po_no!='') {
    						location.href = '<?php echo htmlspecialchars_decode(tep_href_link('view_detail.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm')); ?>&po_no='+po_no;
    					} else {
    						$("#po_no_error").html(' PO No is mandatory');
    					}
    				});
    			</script>
    <?php
}

echo tep_draw_button(BACK, '', tep_href_link('allorders.php', ''));
?> &nbsp;&nbsp;
<?php
if ($cust_nfo['order_updates'] == 0 && $cust_nfo['status'] == 0 && get_customer_type($_SESSION['customer_group_id']) != 'storeadmin' && get_customer_type($_SESSION['customer_group_id']) != 'storeusers' && get_customer_type($_SESSION['customer_group_id']) != 'customer' && get_customer_type($_SESSION['customer_group_id']) != 'buyer') {
    echo '<br /><br /><strong>' . PO_TITLE . ' : </strong><input type="text" id ="po_no" /><span id="po_no_error" style="color:#F00;font-weight:bold;"></span>';
}
?>

<?php
if ($cust_nfo['order_updates'] == 1 && $cust_nfo['status'] == 0 && (get_customer_type($_SESSION['customer_group_id']) == 'customer' || get_customer_type($_SESSION['customer_group_id']) == 'admin')) {
    echo '<br /><br /><strong>' . PO_TITLE . ': </strong><input type="text" id ="po_no" /><span id="po_no_error" style="color:#F00;font-weight:bold;"></span>';
}
?>
<h3><?php echo CUSTOMER_DETAIL ?></h3>


<?php
if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
}
?>
<!--/////////////////////////////////REMOVE JS FROM HERE AND PUT JAVASCRIPT AT THE TOP//////////////-->

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
      <td width="22%" align="left" class="dataTableContent"><?php echo getManfct_name($cust_nfo['manufacturer']); ?></td>
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
      <td class="dataTableContent" align="left" colspan=3"><?php echo $cust_nfo['po_no']; ?></td>
     </tr>
     <tr>
      <th align="left" colspan="4"><?php echo NOTES; ?></th>
     </tr>
     <tr>
      <td class="dataTableContent" align="left" colspan="4"><?php echo $cust_nfo['notes']; ?></td>
    </tr>
     <tr class="">
      <td colspan="4"><?php
if (get_customer_type($_SESSION['customer_group_id']) == 'storeusers') {
    echo get_order_detailitemnetuser($cust_id);
} else {
    echo get_order_detail($cust_id);
}
?></td>
      
    </tr>
<?php //if(get_customer_type($_SESSION['customer_group_id'])=='storeadmin' || get_customer_type($_SESSION['customer_group_id'])=='customer')
//{ 
?>
      <tr class="">
      <th><?php echo TRACKING_NUMBER; ?></th>
      <td colspan="3" align="left" class="dataTableContent" ><input <?php if (get_customer_type($_SESSION['customer_group_id']) == 'customer' || get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'storeusers') echo 'disabled'; ?> type="text"  class="tracknumber" name="tracking_number" id="tracking_number" value="<?php echo $cust_nfo['tracking_number']; ?>" /></td>
      </tr>
     
       
      <th><?php echo ETA; ?></th>
      <td colspan="3" align="left" class="dataTableContent" ><input <?php if (get_customer_type($_SESSION['customer_group_id']) == 'customer' || get_customer_type($_SESSION['customer_group_id']) == 'buyer' || get_customer_type($_SESSION['customer_group_id']) == 'storeusers') echo 'disabled'; ?> type="date"  class="tracknumber" name="datepicker" id="datepicker" value="<?php echo $cust_nfo['expected_date']; ?>"  /></td>
      </tr>
       <tr class="">
      <th st><?php echo TRACKING_TEXT; ?></th>
      <td colspan="3" align="left" class="dataTableContent"><textarea <?php if (get_customer_type($_SESSION['customer_group_id']) == 'customer' || get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'storeusers') echo 'disabled'; ?> name="tracking_text" id="tracking_text" cols="45" rows="5"><?php echo $cust_nfo['tracking_text']; ?></textarea></td>
      </tr>
<?php // }
if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'storeusers') {
    ?> 
          
          <tr class="">
          <th><?php echo CURRENT_STATUS; ?></th>
          <td colspan="3" align="left" class="dataTableContent">
    <?php
    if ($cust_nfo['order_updates'] == 1) {
        echo '<b style="color:#e17009;"> <?php echo PENDING_ADMIN_APPROVAL; ?> </b>';
    } else {
        if ($cust_nfo['order/quote_status'] == '1') {
            ?>
                   <select name="order_status" id="order_status">
                 
                    <option value="1" <?php if ($cust_nfo['status'] == 1) echo "selected"; ?>><?php echo APPROVED; ?></option>
                    <option value="2" <?php if ($cust_nfo['status'] == 2) echo "selected"; ?>><?php echo DECLINED; ?></option>
                    <option value="3" <?php if ($cust_nfo['status'] == 3) echo "selected"; ?>><?php echo SHIPPED; ?></option>
                     <option value="4" <?php if ($cust_nfo['status'] == 4) echo "selected"; ?>><?php echo PROCESSING; ?></option>
                    <option value="5" <?php if ($cust_nfo['status'] == 5) echo "selected"; ?>><?php echo ON_ORDER; ?></option>
                  </select>
        <?php }


        else if ($cust_nfo['order/quote_status'] == '0') {
            ?>
                  <select name="order_status" id="order_status">
                    <option value="0" <?php if ($cust_nfo['status'] == 0) echo "selected"; ?>><?php echo PENDING_ADMIN_APPROVAL; ?></option>
            <?php if ((int) $totalprice > 0) { ?>
                        <option value="1" <?php if ($cust_nfo['status'] == 1) echo "selected"; ?>><?php echo ACCEPTED; ?></option>
                <?php }
            ?>
                    <option value="2" <?php if ($cust_nfo['status'] == 2) echo "selected"; ?>><?php echo DECLINED; ?></option>
                    <option value="3" <?php if ($cust_nfo['status'] == 3) echo "selected"; ?>><?php echo SHIPPED; ?></option>
                    <option value="4" <?php if ($cust_nfo['status'] == 4) echo "selected"; ?>><?php echo PROCESSING; ?></option>
                    <option value="5" <?php if ($cust_nfo['status'] == 5) echo "selected"; ?>><?php echo ON_ORDER; ?></option>
                  </select>
            <?php
        }
    }
    ?>
          </td>
          </tr>
    <?php }
?>
   
    
<?php if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin' || get_customer_type($_SESSION['customer_group_id']) == 'storeusers') { ?> 
     <tr class="">
            <td colspan="4" align="right" style="text-align:right;">  <input type="submit" name="Order" id="Order" value="<?php echo UPDATE_ORDER; ?>" class="myButton" /></td>
          </tr>

<?php } ?>
     
  </tbody>
</table>
</form>
<form name="commnetform" id="commnetform" method="post">
<h3><?php echo COMMENT_HISTORY; ?></h3>
<div class="commentHistory">
<table width="100%" cellspacing="0" cellpadding="10" border="0">
  <tbody>
<?php
$comment_his_sql = tep_db_query("select * from rfq_order_comment_history where order_id=" . $cust_id . " order by date_added asc");
while ($comment_info = tep_db_fetch_array($comment_his_sql)) {


    $gid = get_customer_geoup_id($comment_info['customer_id']);
    if (get_customer_type($gid) == 'customer' || get_customer_type($gid) == 'buyer') {
        ?>
              	 <tr class="">
                	<th colspan="4" align="left">
                        <h3 class="customer"><?= get_customer_fullname($comment_info["customer_id"]) ?></h3>
                        <small><?php echo POSTED_ON; ?><?= date('d M, Y', strtotime($comment_info["date_added"])) ?></small>
                        <p><?= $comment_info["comments"] ?></p>
                        
                  	</th>
              </tr>
    <?php } else { ?>   
             	<tr class="">
                	<td colspan="4" align="left">
                        <h3 class="customeradmin"><?= get_customer_fullname($comment_info["customer_id"]) ?>&nbsp;&nbsp;[<?php echo get_customer_group($gid) ?>]</h3>
                        <small><?php echo POSTED_ON; ?> <?= date('d M, Y', strtotime($comment_info["date_added"])) ?></small>
                        <p><?= $comment_info["comments"] ?></p>
                        </td>
              </tr>
    <?php
    }
}
if (get_customer_type($_SESSION['customer_group_id']) != 'storeusers') {
    ?>   
          <tr class="">
            <td colspan="4" align="left"><textarea name="txt_comment_history" id="comment_history" cols="45" rows="5" placeholder="Add comment" ></textarea></td>
            </tr>
         <tr class="">
            <td colspan="4" align="left"><div id="loader"><img src="images/opc-ajax-loader.gif" /></div> <input type="button" name="Order" id="Order" value="<?php echo SAVE_COMMENT; ?>" class="myButton" onclick="updateComment();" /></td>
            </tr>
<?php } ?>
        </tbody></table>
        
        </div>
        </form>
  </div>


</div>

<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>