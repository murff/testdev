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
    ul.pagination {
        text-align:center;
        color:#829994;
    }
    ul { list-style:none;}
    ul.pagination li {
        display:inline;
        padding:0 3px;
    }
    ul.pagination a {
        color:#666;
        display:inline-block;
        padding:5px 10px;
        border:1px solid #ccc;
        text-decoration:none;
    }
    ul.pagination a:hover, 
    ul.pagination a.current {
        background:#666;
        color:#fff;
    }
    #loader, #loaderuser
    {
        margin:10px;
        display:none;	
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
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ALL_ORDER);
global $per_page, $startpoint, $statement, $page;
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
if ($page <= 0)
    $page = 1;

$per_page = 10; // Set how many records do you want to display per page.

$startpoint = ($page * $per_page) - $per_page;

function newCommentStatus($oid) {
    $num = tep_db_fetch_array(tep_db_query("SELECT count(*) as total from rfq_order_comment_history where `order_id` = " . $oid . " and `comments_viewed` =0"));
    if ($num['total'] > 0) {
        if ($_SESSION['language'] == 'english') {
            return '<span style="color:#06F ">Yes</span>';
        }
        if ($_SESSION['language'] == 'french') {
            return '<span style="color:#06F ">Qui</span>';
        }
    } else
        return '--';
}
?>

<?php
/* function getManfct_name($id)
  {
  $manfct  = tep_db_fetch_array(tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = ".$id.""));
  return $manfct['manufacturers_name'];
  } */

function get_order($id = '', $startpoint, $per_page, $statusparam = '') {
    global $numrows, $page;
    $oid = isset($_GET['oid']) && $_GET['oid'] == 'n' ? 's' : 'n';
    $date_added = isset($_GET['date_added']) && $_GET['date_added'] == 'n' ? 's' : 'n';
    $po_no = isset($_GET['po_no']) && $_GET['po_no'] == 'n' ? 's' : 'n';
    $customer_id = isset($_GET['customer_id']) && $_GET['customer_id'] == 'n' ? 's' : 'n';
    $group_name = isset($_GET['group_name']) && $_GET['group_name'] == 'n' ? 's' : 'n';
    $serial = isset($_GET['serial']) && $_GET['serial'] == 'n' ? 's' : 'n';
    $issue_no = isset($_GET['issue_no']) && $_GET['issue_no'] == 'n' ? 's' : 'n';
    $order_quote = isset($_GET['order_quote']) && $_GET['order_quote'] == 'n' ? 's' : 'n';
    $status = isset($_GET['status']) && $_GET['status'] == 'n' ? 's' : 'n';

    $_GET['order_by'] = isset($_GET['order_by']) ? $_GET['order_by'] : 's';


    $where = '';
    if ($id != '') {

        $where = ' WHERE r.customer_id=' . $id . ' ' . $statusparam . ' ';
    }
    if ($where == '') {
        $where = 'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status ' . $statusparam . ' ';
    } else {
        $where .= ' and c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status ' . $statusparam . ' ';
    }
    //var_dump("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added desc");
    // $where = $where==''?'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status':' and c.customer_id=r.customer_id and cg.customers_group_id = c.customers_group_id';
    //var_dump("select * from rfq_order r,customers c,customers_groups cg " . $where . " order by r.date_added desc");
    $varreq = '';
    $pg = '';
    //  if($_REQUEST['page'] && $_REQUEST['page']!=''){
    // $pg = '&page='.$_REQUEST['page'];  
    //  }

    $clasoid = 'class="header"';
    $clasdate_added = 'class="header"';
    $claspo_no = 'class="header"';
    $clascustomer_id = 'class="header"';
    $clasgroup_name = 'class="header"';
    $classerial = 'class="header"';
    $clasissue_no = 'class="header"';
    $clasorder_quote = 'class="header"';
    $classtatus = 'class="header"';
    if ($_GET['oid'] == 'n') {
        $varreq = '&oid=n';
        $pgoid = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $clasoid = 'class="headerSortDown"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.rfq_id asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['oid'] == 's') {
        $pgoid = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $varreq = '&oid=s';
        $clasoid = 'class="headerSortUp"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.rfq_id desc LIMIT " . $startpoint . " , " . $per_page . "");
    } else if ($_GET['date_added'] == 'n') {
        $varreq = '&date_added=n';
        $pgdate_added = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $clasdate_added = 'class="headerSortDown"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['date_added'] == 's') {
        $varreq = '&date_added=s';
        $pgdate_added = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $clasdate_added = 'class="headerSortUp"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.date_added desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['po_no'] == 'n') {
        $varreq = '&po_no=n';
        $pgpo_no = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $claspo_no = 'class="headerSortDown"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.po_no asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['po_no'] == 's') {
        $varreq = '&po_no=s';
        $pgpo_no = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $claspo_no = 'class="headerSortUp"';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.po_no desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['customer_id'] == 'n') {
        $varreq = '&customer_id=n';
        $clascustomer_id = 'class="headerSortDown"';
        $pgcustomer_id = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by c.customers_firstname asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['customer_id'] == 's') {
        $varreq = '&customer_id=s';
        $clascustomer_id = 'class="headerSortUp"';
        $pgcustomer_id = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by c.customers_firstname desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['group_name'] == 'n') {
        $varreq = '&group_name=n';
        $clasgroup_name = 'class="headerSortDown"';
        $pggroup_name = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by cg.customers_group_name asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['group_name'] == 's') {
        $varreq = '&group_name=s';
        $clasgroup_name = 'class="headerSortUp"';
        $pggroup_name = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by cg.customers_group_name desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['serial'] == 'n') {
        $varreq = '&serial=n';
        $classerial = 'class="headerSortDown"';
        $pgserial = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.serial asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['serial'] == 's') {
        $varreq = '&serial=s';
        $classerial = 'class="headerSortUp"';
        $pgserial = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.serial desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['issue_no'] == 'n') {
        $varreq = '&issue_no=n';
        $clasissue_no = 'class="headerSortDown"';
        $pgissue_no = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.issue_no asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['issue_no'] == 's') {
        $varreq = '&issue_no=s';
        $clasissue_no = 'class="headerSortUp"';
        $pgissue_no = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.issue_no desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['order_quote'] == 'n') {
        $varreq = '&order_quote=n';
        $clasorder_quote = 'class="headerSortDown"';
        $pgorder_quote = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . "  order by r.`order/quote` asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['order_quote'] == 's') {
        $varreq = '&order_quote=s';
        $clasorder_quote = 'class="headerSortUp"';
        $pgorder_quote = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.`order/quote` desc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['status'] == 'n') {
        $varreq = '&status=n';
        $classtatus = 'class="headerSortDown"';
        $pgstatus = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by ro.`name` asc LIMIT " . $startpoint . " , " . $per_page . "");
    } elseif ($_GET['status'] == 's') {
        $varreq = '&status=s';
        $classtatus = 'class="headerSortUp"';
        $pgstatus = isset($_GET['page']) && $_GET['page'] != '' ? '&page=' . $_REQUEST['page'] : '';
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro  " . $where . " order by r.`order_updates` asc,ro.name desc LIMIT " . $startpoint . " , " . $per_page . "");
    } else {
        //echo "select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added desc LIMIT ".$startpoint." , ".$per_page.""; 
        $order_query = tep_db_query("select * from rfq_order r,customers c,customers_groups cg,rfq_order_status ro " . $where . " order by r.date_added desc LIMIT " . $startpoint . " , " . $per_page . "");
    }
    $statement = "`rfq_order`  r,customers c,customers_groups cg,rfq_order_status ro " . $where . "";

    //$numrows = tep_db_num_rows($order_query);
    // echo $numrows; exit;
    $my_table_head = '<table  width="100%" class="tablesorter" cellspacing="0" cellpadding="10" border="0">
              <thead><tr >
			   <th ' . $clasoid . ' width="8%"><a href="allorders.php?oid=' . $oid . $pgoid . '">' . ORDER_ID . '</th>
			  <th ' . $clasdate_added . ' ><a href="allorders.php?date_added=' . $date_added . $pgdate_added . '">' . DATE . '</a></th>
			   <th width="7%" ' . $claspo_no . '><a href="allorders.php?po_no=' . $po_no . $pgpo_no . '">' . PO_NO . '</a></th>
               <th ' . $clascustomer_id . '><a href="allorders.php?customer_id=' . $customer_id . $pg . '">' . NAME . '</a></th>
                <th ' . $clasgroup_name . '><a href="allorders.php?group_name=' . $group_name . $pg . '">' . GROUP . '</a></th>
                 <th ' . $classerial . ' width="8%><a href="allorders.php?serial=' . $serial . $pg . '">' . SERIAL_NO . '</a></th>
				  <th ' . $clasissue_no . ' width="7%"><a href="allorders.php?issue_no=' . $issue_no . $pg . '">' . ISSUE_NO . '</a></th>
                  <th>' . VIEW_DETAIL . '</th>
				  <th width="10%">' . NEW_COMMENT . '</th>
				  <th ' . $clasorder_quote . ' width="10%"><a href="allorders.php?order_quote=' . $order_quote . $pg . '">' . REQUEST_TYPE_TITLE . '</a></th>
                   <th ' . $classtatus . ' width="12%"><a href="allorders.php?status=' . $status . $pg . '">' . STATUS_TITLE . '</a></th>
              </tr></thead> <tbody>';

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
        $my_table .='<tr class ="' . $class . '">';
        $my_table .='<td class="dataTableContent">' . $order_val["rfq_id"] . '</td>';
        $my_table .='<td class="dataTableContent">' . date("m/d/Y h:i A", strtotime($order_val['date_added'])) . '</td>';
        $my_table .='<td class="dataTableContent">' . $order_val['po_no'] . '</td>';
        $my_table .='<td class="dataTableContent">' . $customer_address["customers_firstname"] . ' ' . $customer_address["customers_lastname"] . '</td>';
        $my_table .='<td class="dataTableContent">';
        if ($customer_address['customer_group_id'] == 0) {
            $my_table .='Custommer';
        } else {
            $my_table .=get_customer_group($customer_address['customer_group_id']);
        }
        $my_table .='</td>';

        $my_table .='<td class="dataTableContent">' . $order_val['serial'] . '</td>';
        $my_table .='<td class="dataTableContent">' . $order_val['issue_no'] . '</td>';
        $my_table .='<td class="dataTableContent"><a href="view_detail.php?id=' . $order_val["rfq_id"] . '">' . VIEW_DETAIL . '</td>';
        $my_table .='<td class="dataTableContent">' . newCommentStatus($order_val["rfq_id"]) . '</td>';
        
        if ($_SESSION['language'] == 'english') {
            if ($order_val['order/quote_status'] == 1) {
                $my_table .='<td class="dataTableContent">' . ORDER . '</td>';
            } else {
                $my_table .='<td class="dataTableContent">' . QUOTE . '</td>';
            }
        }

        if ($_SESSION['language'] == 'french') {

            if ($order_val['order/quote_status'] == 1) {
                $my_table .='<td class="dataTableContent">' . ORDER . '</td>';
            } else {
                $my_table .='<td class="dataTableContent">' . QUOTE . '</td>';
            }
        }

        $my_table .='<td class="dataTableContent">';


        if ($order_val['order/quote_status'] == '1' && $order_val['status'] == 1) {

            $my_table .=APPROVED;
        } else {
            if ($order_val['status'] == 0 && $order_val['order_updates'] == 0) {
                $my_table .='<b style="color:#e17009;">' . PENDING_PRICING . '</b>';
            }
            if ($order_val['order_updates'] == 1) {
                $my_table .='<b style="color:#e17009;">' . PENDING_CUSTOMER_APPROVEL . '</b>';
            }
            if ($order_val['status'] == 1) {
                $my_table .='<b style="color:#390;">' . ACCEPTED . '</b>';
            }
            if ($order_val['status'] == 2) {
                $my_table .='<b style="color:#F00;">' . DECLINED . '</b>';
            }
            if ($order_val['status'] == 3) {
                $my_table .='<b style="color:#090;">' . SHIPPED . '</b>';
            }
            if ($order_val['status'] == 4) {
                $my_table .='<b style="color:#106EB8;">' . PROCESSING . '</b>';
            }
            if ($order_val['status'] == 5) {
                $my_table .='<b style="color:#FF9B09;">' . ON_ORDER . '</b>';
            }
        }
        $my_table .='</td>';
        $my_table .='</tr>';

        $k++;
    }

    return $my_table_head . $my_table . '</tbody></table><br /><br />' . pagination($statement, $per_page, $page, 'allorders.php?' . tep_get_all_get_params(array('page', 'order_by')));
    ;
}
?>

<h1><?php echo ORDER_QUOTES_TITLE; ?></h1>
<form method="post" name="searchform"><?php echo SEARCH_TITLE; ?>
    <label for="select"></label>
    <select name="search_variable" id="search_variable">
        <option value="rfq_id"><?php echo ORDER_ID; ?></option> 
        <option value="po_no"><?php echo PO_NO; ?></option>
        <option value="issue_no"><?php echo ISSUE_NO; ?></option>
        <option value="serial"><?php echo SERIAL_NO; ?></option>
    </select>
    <input name="search_value" id="country_name" class="form-control txt-auto"/>
    <input type="button" name="Button" id="button" value="<?php echo SEARCH_BUTTON; ?>" class="myButton" onclick="searchFilter();" />

    <a href="allorders.php"><input type="button" name="button2" id="button2" value="<?php echo CLEAR_BUTTON; ?>" class="myButton" /></a>
    <div id="loader"><img src="images/opc-ajax-loader.gif" /></div>
</form>
<script>$('#country_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'autofill.php',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: $('#search_variable').val()
                },
                success: function(data) {
                    // alert(data)
                    response($.map(data, function(item) {
                        return {
                            label: item,
                            value: item
                        }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0
    });
    function searchFilter()

    {

        //alert("");
        $("#loader").show();

        var search_variable = $("#search_variable").val();
        var search_value = $("#country_name").val();
//alert(search_value);
        $.ajax({
            url: 'searchorder.php?act=save&search_variable=' + search_variable + '&search_value=' + search_value,
            success: function(data) {
                //alert(data);
                $("#loader").hide();
                $(".contentText").html(data);
                // $("#feedbackcommon"+act_id).show();


            }
        });
    }

</script>
<link rel="stylesheet" href="themes/blue/style.css" type="text/css" media="print, projection, screen" />
        <?php /* ?><script src="jquery.tablesorter.js"></script>
          <script>
          $(document).ready(function()
          {
          $("#myTable").tablesorter({headers: { 7:{sorter: false}}});
          }
          );
          </script>
          <?php */ ?>
        <?php
        if ($messageStack->size('account') > 0) {
            echo $messageStack->output('account');
        }
        ?>

<div class="contentContainer">


    <div class="contentText">
        <?php
        $statusparm = '';
        if ($_REQUEST['action'] && $_REQUEST['action'] == 'status') {
            if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
                $statusparm = " and order_updates = 0 and status = 0 and `order/quote_status`='0'";
            } else if (get_customer_type($_SESSION['customer_group_id']) == 'admin') {
                $statusparm = " and order_updates = 1 and status = 0 and `order/quote_status`='0'";
            } else if (get_customer_type($_SESSION['customer_group_id']) == 'storeusers') {
                $statusparm = " and status <> 3 ";
            }
        }

        if (get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
            echo get_order('', $startpoint, $per_page, $statusparm);
        } else {
            echo get_order($_SESSION['customer_id'], $startpoint, $per_page);
        }
        ?>
        <br /><br />
<?php
//echo $numrows.'kk'; exit;
//  if($numrows >10) {
?>
    </div>


</div>

<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>