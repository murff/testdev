<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
 */
?>
<?php define('EMAIL_SEPARATOR', '------------------------------------------------------'); ?>

<?php

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_UPDATE_ORDER_PROCESS);
//return customer id
$rfq_id = $HTTP_POST_VARS['cID'];
$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int) $rfq_id . "'");
$customers_email = tep_db_fetch_array($customers_email_query);
$customer_id_tmp = $customers_email['customer_id'];
$order_owner_id = $customers_email['order_owner'];

function get_customer_id($id) {

    $customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int) $id . "'");
    $customers_email = tep_db_fetch_array($customers_email_query);
    $cust_id = $customers_email['customer_id'];
    return $cust_id;
}

function get_order_status($id) {

    $status_query = tep_db_query("select status from rfq_order where rfq_id = '" . (int) $id . "'");
    $order_st = tep_db_fetch_array($status_query);

    return $order_st['status'];
}

//session id
$login_id = $_SESSION['customer_id'];

//get id form url
if (isset($_GET['id'])) {

    $cust_id = $_GET['id'];
}
if ($_POST) {
    //exit;
    $rfq_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);
    if ($_POST['tracking_number']) {
        tep_db_query("update rfq_order set tracking_number = '" . $_POST['tracking_number'] . "', tracking_text = '" . $_POST['tracking_text'] . "' where rfq_id = '" . (int) $rfq_id . "'");
        //tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
    }
    if ($_POST['datepicker']) {
        $date = $_POST['datepicker'];
        $dd = date_create($date);
        $expected_date = date_format($dd, "Y-m-d");
      tep_db_query("update rfq_order set expected_date = '" . $expected_date . "'  where rfq_id = '" . (int) $rfq_id . "'");  
    }
    if (isset($HTTP_POST_VARS['order_status']) && $HTTP_POST_VARS['order_status'] == 1 && get_order_status($rfq_id) != $HTTP_POST_VARS['order_status']) {



        $email_order = QUOTE_ORDER_NO . $rfq_id . HAS_BEEN_ACCEPTED .
                EMAIL_SEPARATOR . "\n\n" .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

        //get_customer_id from a function
        $customer_id = get_customer_id($rfq_id);

        //query for customer information
        $customers_email = tep_db_query("select * from customers where customers_id = '" . (int) $customer_id . "'");
        $customer_address = tep_db_fetch_array($customers_email);


        $email_order2 = ACCEPTED_THE_ORDER . $rfq_id . "\n\n" .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

        //query for admin information
        $admin_email = tep_db_query("select * from customers where customers_id = '" . (int) $login_id . "'");
        $admin_address = tep_db_fetch_array($admin_email);



        tep_mail($admin_address['customers_firstname'] . ' ' . $admin_address['customers_lastname'], $admin_address['customers_email_address'], QUOTE_ORDER_ACCEPTED, $email_order2, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], QUOTE_ORDER_ACCEPTED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


        tep_db_query("update rfq_order set status = 1 where rfq_id = '" . (int) $rfq_id . "'");
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
        break;
    }

    if (isset($HTTP_POST_VARS['order_status']) && $HTTP_POST_VARS['order_status'] == 2 && get_order_status($rfq_id) != $HTTP_POST_VARS['order_status']) {

        $rfq_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);

        $email_order = QUOTE_ORDER_NO . $rfq_id . HAS_BEEN_DECLINED .
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

        tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], QUOTE_ORDER_DECLINED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        tep_db_query("update rfq_order set status = 2 where rfq_id = '" . (int) $rfq_id . "'");
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));

        break;
    }

    // Shipped
    if (isset($HTTP_POST_VARS['order_status']) && $HTTP_POST_VARS['order_status'] == 3 && get_order_status($rfq_id) != $HTTP_POST_VARS['order_status']) {

        $rfq_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);

        $email_order = QUOTE_ORDER_NO . $rfq_id . HAS_BEEN_SHIPPED .
                EMAIL_SEPARATOR . "\n\n" . orderProductEmail($rfq_id) .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";



        //////////////////////////////////////////////////////////////////////////////////////
        $email_order2 = SHIPPED_ORDER_NO . $rfq_id . "\n\n" .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, QUOTE_ORDER_SHIPPED, $email_order, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));


        tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), QUOTE_ORDER_SHIPPED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        //echo get_customer_fullname($order_owner_id); exit;
        if ($order_owner_id != 0) {
            tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), QUOTE_ORDER_SHIPPED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
        if ($customer_id_tmp != 0) {
            $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
            $customer_address = tep_db_fetch_array($customers_email);
            if ($customer_address['staples_admin'] != 0) {
                tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), QUOTE_ORDER_SHIPPED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
        }
        tep_db_query("update rfq_order set status = 3 where rfq_id = '" . (int) $rfq_id . "'");
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));

        break;
    }

    //Processed
    if (isset($HTTP_POST_VARS['order_status']) && $HTTP_POST_VARS['order_status'] == 4 && get_order_status($rfq_id) != $HTTP_POST_VARS['order_status']) {

        $rfq_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);

        $email_order = QUOTE_ORDER_NO . $rfq_id . SET_TO_PROCESS .
                EMAIL_SEPARATOR . "\n\n" . orderProductEmail($rfq_id) .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";



        //////////////////////////////////////////////////////////////////////////////////////
        $email_order2 = SHIPPED_ORDER_NO . $rfq_id . "\n\n" .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, QUOTE_ORDER_PROCESSING, $email_order, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));


        tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), QUOTE_ORDER_PROCESSING, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        //echo get_customer_fullname($order_owner_id); exit;
        if ($order_owner_id != 0) {
            tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), QUOTE_ORDER_PROCESSING, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
        if ($customer_id_tmp != 0) {
            $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
            $customer_address = tep_db_fetch_array($customers_email);
            if ($customer_address['staples_admin'] != 0) {
                tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), QUOTE_ORDER_PROCESSING, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
        }
        tep_db_query("update rfq_order set status = 4 where rfq_id = '" . (int) $rfq_id . "'");
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));

        break;
    }

    // Delayed
    if (isset($HTTP_POST_VARS['order_status']) && $HTTP_POST_VARS['order_status'] == 5 && get_order_status($rfq_id) != $HTTP_POST_VARS['order_status']) {

        $rfq_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);

        $email_order = QUOTE_ORDER_NO . $rfq_id . HAS_BEEN_DELAYED .
                EMAIL_SEPARATOR . "\n\n" . orderProductEmail($rfq_id) .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";



        //////////////////////////////////////////////////////////////////////////////////////
        $email_order2 = SHIPPED_ORDER_NO . $rfq_id . "\n\n" .
                VIEW_ORDER . "\n" . EMAIL_SEPARATOR . "\n<a href='" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "'>" . HTTP_SERVER_DIRPATH . "view_detail.php?id=" . $rfq_id . "</a>\n\n";

        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, QUOTE_ORDER_DELAYED, $email_order, get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));


        tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), QUOTE_ORDER_DELAYED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        //echo get_customer_fullname($order_owner_id); exit;
        if ($order_owner_id != 0) {
            tep_mail(get_customer_fullname($order_owner_id), get_customer_emailaddress($order_owner_id), QUOTE_ORDER_DELAYED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
        if ($customer_id_tmp != 0) {
            $customers_email = tep_db_query("select staples_admin from customers where customers_id = '" . (int) $customer_id_tmp . "'");
            $customer_address = tep_db_fetch_array($customers_email);
            if ($customer_address['staples_admin'] != 0) {
                tep_mail(get_customer_fullname($customer_address['staples_admin']), get_customer_emailaddress($customer_address['staples_admin']), QUOTE_ORDER_DELAYED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
        }
        tep_db_query("update rfq_order set status = 5 where rfq_id = '" . (int) $rfq_id . "'");
        tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));

        break;
    }



    tep_redirect(tep_href_link('allorders.php', tep_get_all_get_params(array('cID', 'action'))));
}
?>