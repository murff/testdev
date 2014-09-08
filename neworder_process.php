<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
 */
define('EMAIL_SEPARATOR', '------------------------------------------------------');

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEW_ORDER_PROCESS);
require('includes/classes/http_client.php');
//echo "<pre>";
//print_r($_SESSION); exit;
// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'NONSSL'));
}

$logedin_admin_id = $_SESSION['customer_id'];

function get_customer_info($id) {

    $customer_info = tep_db_query("select * from customers where customers_id = '" . (int) $id . "'");
    $customer_info_get = tep_db_fetch_array($customer_info);

    return $customer_info_get;
}

function check_order_quote($id) {

    $customer_info = tep_db_query("select  `order/quote` from rfq_order where customer_id = '" . (int) $id . "'");
    $customer_info_get = tep_db_fetch_array($customer_info);

    return $customer_info_get['order/quote'];
}

$orderEror = '';
if (isset($_REQUEST['manufacturers_id']) && $_REQUEST['manufacturers_id'] == '') {
    $orderEror .= 'Manfacturer is required. &nbsp;&nbsp;';
}
if (isset($_REQUEST['serial_no']) && $_REQUEST['serial_no'] == '') {
    $orderEror .= 'Serial Number is required. &nbsp;&nbsp;';
}
if (isset($_REQUEST['model_no']) && $_REQUEST['model_no'] == '') {
    $orderEror .= 'Model Number is required. &nbsp;&nbsp;';
}
if (isset($_REQUEST['issue_no']) && $_REQUEST['issue_no'] == '') {
    $orderEror .= 'Issue Number is required. &nbsp;&nbsp;';
}


if ($orderEror != '') {
    $_SESSION['newOrderError'] = $orderEror;
    tep_redirect(tep_href_link('neworder.php'));
    exit;
}

$rtype = '';

if (isset($_POST['Order']) && $_POST['Order'] != "") {
    $rtype = $_POST['Order'];
    $ordStatus = 1;
    $orderquote_status = 1;
} else if (isset($_POST['Quote']) && $_POST['Quote'] != "") {
    $rtype = $_POST['Quote'];
    $ordStatus = 0;
    $orderquote_status = 0;
}
$request_type_new_order = $rtype;

$_SESSION['orderRequestType'] = $request_type_new_order;

//echo $request_type_new_order; exit;
// process the selected shipping method

if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {

    if (($_SESSION['customer_group_id']) == '2') {
        $custid = $HTTP_POST_VARS['customersid'];
     
       // $custid = $_SESSION['customer_id'];
        $customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int) $customer_id . "' and ab.customers_id = '" . (int) $customer_id . "' and c.customers_default_address_id = ab.address_book_id");
        $customer_address = tep_db_fetch_array($customer_address_query);
    } else {
        $customer_address_query = tep_db_query("select c.customers_id, c.customers_firstname, c.staples_admin, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int) $customer_id . "' and ab.customers_id = '" . (int) $customer_id . "' and c.customers_default_address_id = ab.address_book_id");
        $customer_address = tep_db_fetch_array($customer_address_query);
        $custid = $customer_address['customers_id'];
    }
    $manufacturers_info = tep_db_fetch_array(tep_db_query("select  manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id='" . $_REQUEST['manufacturers_id'] . "'"));

// lets start with the email confirmation
    $prodinfo = array();
    $catinfo = array();
    $products_ordered = '';

//exit(0);
    $sql = tep_db_query("INSERT INTO `rfq_order` (customer_id, `manufacturer`, `serial`, `model`, `issue_no`, `po_no`, `notes`, `date_added`,`order/quote`,`order/quote_status`,status,order_owner) VALUES 
   ('" . $custid . "','" . $_REQUEST['manufacturers_id'] . "', '" . $_REQUEST['serial_no'] . "', '" . $_REQUEST['model_no'] . "', '" . $_REQUEST['issue_no'] . "', '" . $_REQUEST['customer_po'] . "', 
   '" . special_char($_REQUEST['notes']) . "', '" . date("Y-m-d H:i:s") . "','" . $request_type_new_order . "'," . $ordStatus . "," . $orderquote_status . ", " . $_SESSION['customer_id'] . ")");
    $last_id = tep_db_insert_id();
    $_SESSION['lastorderid'] = $last_id;
    for ($i = 1; $i <= 6; $i++) {
        if ($_REQUEST['partnum' . $i] != '') {

            $catid = $_REQUEST['parttype' . $i];
            //echo ; exit;
            $prodinfo = tep_db_fetch_array(tep_db_query("select  b.products_name from " . TABLE_PRODUCTS . " a," . TABLE_PRODUCTS_DESCRIPTION . " b where a.products_id=b.products_id and a.products_model='" . $_REQUEST['partnum' . $i] . "'"));

            $catinfo = tep_db_fetch_array(tep_db_query("select a.categories_id, b.price_a, b.categories_name from " . TABLE_CATEGORIES . " a ," . TABLE_CATEGORIES_DESCRIPTION . " b where a.categories_id=b.categories_id and a.parent_id=0 and b.categories_id='" . $catid . "'"));


            $products_ordered .= $_REQUEST['cart_quantity' . $i] . ' x ' . stripslashes($prodinfo['products_name']) . ' (' . $_POST['partnum' . $i] . ') = ' . $_POST['price' . $i] . "\t" . $catinfo['categories_name'] . "\t" . $_POST['descr' . $i] . "\n";
            tep_db_query("INSERT INTO `rfq_order_detail` (`rfq_id`, `qty`, `part_type`, `part_type_id`, `description`, `part_number`, `price`,`price2`) VALUES 
 ('" . $last_id . "', '" . $_REQUEST['cart_quantity' . $i] . "', '" . $catinfo['categories_name'] . "', '" . $catid . "', '" . $_POST['desc' . $i] . "', '" . $_POST['partnum' . $i] . "', '" . ($_POST['price' . $i]) . "', '" . $catinfo['price_a'] . "');");
        }
    }
    //exit;
    $email_order = STORE_NAME . "\n" .
            EMAIL_SEPARATOR . "\n\n" . orderProductEmail($last_id) .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $last_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $last_id . "</a>\n\n\n" . getSignature();





    // -------------------- mail order to admin ------------------

    $email_order2 = STORE_NAME . "\n" .
            EMAIL_SEPARATOR . "\n\n" . orderProductEmail($last_id) .
            VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "admin/rfq_orders.php?page=1&cID=" . $last_id . "&action=view'>" . HTTP_SERVER_DIRPATH . "admin/rfq_orders.php?page=1&cID=" . $last_id . "&action=view</a>\n\n\n" . getSignature();

    ;

    // --------------   End ---------------------------------
    if (($_SESSION['customer_group_id']) == '2') {
        // echo STORE_OWNER_EMAIL_ADDRESS;
        $customer_order_query = tep_db_query("select * from customers c where c.customers_id = '" . (int) $HTTP_POST_VARS['customersid'] . "'");
        $customer_order_row = tep_db_fetch_array($customer_order_query);

        // exit;
        // ---- To Admin
        $customer_admin = tep_db_query("select * from customers c where c.customers_id = '" . (int) $customer_order_row['staples_admin'] . "'");
        $customer_admin_row = tep_db_fetch_array($customer_admin);
        $customer_admin_row['customers_firstname'] = "Staple Online Parts Ordering";
        if ($customer_order_row['staples_admin'] != 0 && $_SESSION['customer_id'] != $customer_order_row['staples_admin']) {



            tep_mail($customer_admin_row['customers_firstname'] . ' ' . $customer_admin_row['customers_lastname'], $customer_admin_row['customers_email_address'], 'Request For ' . $request_type_new_order . ' has been placed for user ' . $customer_order_row['customers_firstname'] . ' ' . $customer_order_row['customers_lastname'], $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
        if ($ordStatus == 1 || $ordStatus == 0) {
            $order = tep_db_fetch_array(tep_db_query("SELECT * FROM `rfq_order` where rfq_id = '" . (int) $last_id . "'"));
            //$list[] = array('','','','','','','','','Items Detail','','','','','','');
            if ($ordStatus == 1) {
                $list_products[] = array('Order No', 'PO No', 'Date', 'Serial', 'Model', 'Issue No', 'Manfacturer', 'Part Type', 'Description', 'Part Number', 'Qty', 'Staples Price', 'Notes', 'Name', 'Email');
            } else {
                $list_products[] = array('Quote No', 'PO No', 'Date', 'Serial', 'Model', 'Issue No', 'Manfacturer', 'Part Type', 'Description', 'Part Number', 'Qty', 'Staples Price', 'Notes', 'Name', 'Email');
            }


            $qry_pro = tep_db_query("SELECT * FROM `rfq_order_detail` where rfq_id='" . $order['rfq_id'] . "'");
            while ($pro = tep_db_fetch_array($qry_pro)) {
                $list_products[] = array($order['rfq_id'], $order['po_no'], date("m/d/Y h:i A", strtotime($order['date_added'])), $order['serial'], $order['model'], $order['issue_no'], getManfct_name($order['manufacturer']),
                    $pro['part_type'], $pro['description'], $pro['part_number'], $pro['qty'], $pro['price2'], reverse_char($order['notes']), get_customer_fullname($order['customer_id']), get_customer_emailaddress($order['customer_id']));
            }
            $file = 'staplesorder_' . $last_id . '.csv';
            $filename = 'staplesorder_' . $last_id;
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
            tep_mail_attach(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, REQUEST_FOR . $request_type_new_order . '', $email_order, $customer_admin_row['customers_firstname'], $customer_admin_row['customers_email_address'], $file, 'csv', $filename);
            unlink($file);
        } else {
            tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, REQUEST_FOR . $request_type_new_order . '', $email_order, $customer_admin_row['customers_firstname'] . ' ' . $customer_admin_row['customers_lastname'], $customer_admin_row['customers_email_address']);
        }
        //--- To Store Admin 
        tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], YOU_PLACED_REQUEST_FOR . $request_type_new_order . '', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        //  To Customer
        tep_mail($customer_order_row['customers_firstname'] . ' ' . $customer_order_row['customers_lastname'], $customer_order_row['customers_email_address'], YOU_REQUEST . $request_type_new_order . PLACED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    } else if (($_SESSION['customer_group_id']) == '3') {
        // echo STORE_OWNER_EMAIL_ADDRESS;
        $customer_staples_admin = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address from customers c 
	where c.customers_id = '" . (int) $customer_address['staples_admin'] . "'");
        $customer_order_row = tep_db_fetch_array($customer_staples_admin);
        $customer_order_row['customers_firstname'] = "Staple Online Parts Ordering";

        // exit;
        // ---- To store Admin
        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'REQUEST FOR ' . $request_type_new_order . '', $email_order2, $customer_address['customers_firstname'], $customer_address['customers_email_address']);
        //--- To customer
        tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], YOU_PLACED_REQUEST_FOR . $request_type_new_order . '', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        //  To admin
        tep_mail($customer_order_row['customers_firstname'] . ' ' . $customer_order_row['customers_lastname'], $customer_order_row['customers_email_address'], '' . $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'] . REQUESTED . $request_type_new_order . '', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    } else {

        if ($ordStatus == 1 || $ordStatus == 0) {
            $order = tep_db_fetch_array(tep_db_query("SELECT * FROM `rfq_order` where rfq_id = '" . (int) $last_id . "'"));
            //$list[] = array('','','','','','','','','Items Detail','','','','','','');
            if ($ordStatus == 1) {
                $list_products[] = array('Order No', 'PO No', 'Date', 'Serial', 'Model', 'Issue No', 'Manfacturer', 'Part Type', 'Description', 'Part Number', 'Qty', 'Staples Price', 'Notes', 'Name', 'Email');
            } else {
                $list_products[] = array('Quote No', 'PO No', 'Date', 'Serial', 'Model', 'Issue No', 'Manfacturer', 'Part Type', 'Description', 'Part Number', 'Qty', 'Staples Price', 'Notes', 'Name', 'Email');
            }


            $qry_pro = tep_db_query("SELECT * FROM `rfq_order_detail` where rfq_id='" . $order['rfq_id'] . "'");
            while ($pro = tep_db_fetch_array($qry_pro)) {
                $list_products[] = array($order['rfq_id'], $order['po_no'], date("m/d/Y h:i A", strtotime($order['date_added'])), $order['serial'], $order['model'], $order['issue_no'], getManfct_name($order['manufacturer']),
                    $pro['part_type'], $pro['description'], $pro['part_number'], $pro['qty'], $pro['price2'], reverse_char($order['notes']), get_customer_fullname($order['customer_id']), get_customer_emailaddress($order['customer_id']));
            }
            $file = 'staplesorder_' . $last_id . '.csv';
            $filename = 'staplesorder_' . $last_id;
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
            $customer_admin_row['customers_firstname'] = "Staple Online Parts Ordering";
            tep_mail_attach(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, REQUEST_FOR . $request_type_new_order . '', $email_order, $customer_admin_row['customers_firstname'], $customer_admin_row['customers_email_address'], $file, 'csv', $filename);
            unlink($file);
        }

        //tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , 'REQUEST FOR '.$request_type_new_order.'', $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
        //tep_mail('Abhi', 'ahdsan@gmail.com', 'REQUEST FOR QUOTE', $email_order, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
        tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], 'REQUEST FOR ' . $request_type_new_order . '', $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
}

tep_redirect(tep_href_link('confirm.php'));

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);


// dont show left and right block
$dontshowleftright = 1;

require(DIR_WS_INCLUDES . 'template_top.php');
?>


<div class="contentText">

    <h3><?php echo REQUEST_SENT_SUCCESSFULLY . '<a href="' . HTTP_SERVER_DIRPATH . 'neworder.php">' . CLICK_HERE_FOR_NEW_ORDER . '</a>'; ?></h3>

</div>


<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>