<?php


require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EDIT_ORDER_PROCESS);


if($_POST['id']!=''){
    $send_id=$_POST['id'];
    
    //echo $_GET['id'];
    tep_redirect(tep_href_link('edit_order.php?id='.$send_id));
    
}
else{
    
    tep_redirect(tep_href_link('allorders.php', ''));
}
define('EMAIL_SEPARATOR', '------------------------------------------------------');
$rfq_id = tep_db_prepare_input($HTTP_POST_VARS['id']);
$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int) $rfq_id . "'");
$customers_email = tep_db_fetch_array($customers_email_query);
$customer_id_tmp = $customers_email['customer_id'];
$order_owner_id = $customers_email['order_owner'];



$manfacturer = tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']);
$serial = tep_db_prepare_input($HTTP_POST_VARS['serial_no']);
$model = tep_db_prepare_input($HTTP_POST_VARS['model_no']);
$issue_no = tep_db_prepare_input($HTTP_POST_VARS['issue_no']);
$po_no = tep_db_prepare_input($HTTP_POST_VARS['customer_po']);
$notes = tep_db_prepare_input($HTTP_POST_VARS['notes']);
$price = tep_db_prepare_input($HTTP_POST_VARS['pricest']);


$sql_data_array = array('manufacturer' => $manfacturer,
    'serial' => $serial,
    'model' => $model,
    'issue_no' => $issue_no,
    'po_no' => $po_no,
    'notes' => $notes
   
   );
//condition to update status of order depending on changing price
//echo "<script> alert(".")</script>";
 
if($price == 0){
        echo "<script>alert('I got you ".$price."')</script>";
         $sql_data_array['order_updates'] = 0;
    }
    else{
        $sql_data_array['order_updates'] = 1;
         echo "<script>alert('I got you ".$price."')</script>";
    }       
 echo "<script>alert('I got you ".$price."')</script>";
tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int) $rfq_id . "'");

foreach ($_POST['cart_quantity'] as $key => $val) {
    $customers_email_query = tep_db_query("select * from rfq_order_detail where rfq_id = '" . (int) $id . "'");
    $catinfo = tep_db_fetch_array(tep_db_query("select a.categories_id, b.categories_name from " . TABLE_CATEGORIES . " a ,"
                    . TABLE_CATEGORIES_DESCRIPTION . " 
	b where a.categories_id=b.categories_id and a.parent_id=0 and b.categories_id='" . $_POST['parttype'][$key] . "'"));

    $products_ordered .= $val . ' x ' . $catinfo['categories_name'] . ' (' . $_POST['partnum'][$key] . ') = ' . 
            ($_POST['price'][$key]) . "\t" . $_POST['desc'][$key] . "\n";
    $sql_data_array2 = array('qty' => $val,
        'description' => $_POST['desc'][$key],
        'part_type_id' => $_POST['parttype'][$key],
        'part_number' => $_POST['partnum'][$key],
        'price2' => ($_POST['pricest'][$key]),
        'price' => ($_POST['price'][$key]),
        'subs' => ($_POST['subs'][$key]),
        'action' => ($_POST['action'][$key]),
        'vendor' => ($_POST['vendor'][$key]),
        'pt' => ($_POST['pt'][$key]),
        'buy' => ($_POST['buy'][$key]),
        'notes1' => ($_POST['notes1'][$key]),
    );

    //	print_r($sql_data_array2);
    //exit;
    tep_db_perform('rfq_order_detail', $sql_data_array2, 'update', "rfq_od_id = '" . (int) $key . "'");
}
$email_order = STORE_NAME . "\n" .
        EMAIL_SEPARATOR . "\n\n" . orderProductEmail($rfq_id) .
        VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";


//ger_customer_i function retun customer id
//echo get_customer_fullname($customer_id_tmp); exit;

tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, QUOTE_ORDER_UPDATED, $email_order, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));

tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), QUOTE_ORDER_UPDATED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

if ($order_owner_id != 0) {
    tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), QUOTE_ORDER_UPDATED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
if ($customer_id_tmp != 0) {
    $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
    $customer_address = tep_db_fetch_array($customers_email);
    if ($customer_address['staples_admin'] != 0) {
        tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), QUOTE_ORDER_UPDATED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
}


?>