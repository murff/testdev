<?php
//////////////////////////UPDATE TO INCLUDE SEARCH FUNCTIONALLITY Sean/////////////////////////////////////////////////////////
        $search='';
        $statusparm = '';
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'status') {
            
            if (get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
                $statusparm = " and order_updates = 0 and status = 0 and `order/quote_status`='0'";
            } else if (get_customer_type($_SESSION['customer_group_id']) == 'admin') {
                $statusparm = " and order_updates = 1 and status = 0 and `order/quote_status`='0'";
            } else if (get_customer_type($_SESSION['customer_group_id']) == 'storeusers') {
                $statusparm = " and status <> 3 ";
            }
        }elseif (isset($_REQUEST['action']) && $_REQUEST['action']=='save') 
            {///////Added to existing IF to Utalize allorders.php as search page
            
            if($columname!='date_added'){ 
                $search = " and ".$columname." = '".$searchtext."' "; 
            }
            else{
                $search = " and ".$columname." between '".$searchtext."' ";
            }
            
        }
        
        if (get_customer_type($_SESSION['customer_group_id']) == 'admin' || get_customer_type($_SESSION['customer_group_id']) == 'storeadmin') {
            echo get_order('', $startpoint, $per_page, $statusparm,$search);
        } else {
            echo get_order($_SESSION['customer_id'], $startpoint, $per_page,$search);
        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_order($id = '', $startpoint, $per_page, $statusparam = '',$search='') {

    global $numrows, $page;
    
    $oid = (isset($_GET['oid']) && $_GET['oid'] == 'n') ? 's' : 'n';
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
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'status'){
            $where = ' WHERE r.customer_id=' . $id . ' ' . $statusparam . ' ';
        }else{
            $where=' WHERE r.customer_id='.$id.' '.$statusparam.' '.$search.' ';
        }
    }
    if ($where == '') {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'status'){
            $where = 'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status ' . $statusparam . ' ';
        }
        else{
            $where = 'WHERE c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status '.$statusparam.' '.$search.' ';
        }
    } else {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'status'){
            $where .= ' and c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status ' . $statusparam . ' ';
        }else{
            $where .= ' and c.customers_id=r.customer_id and cg.customers_group_id = c.customer_group_id and ro.id=r.status '.$statusparam.' '.$search.' ';
        }
    }
    
    $varreq = '';
    $pg = '';
    
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
    function sort_grid(){
        
    }
    $statement = "`rfq_order`  r,customers c,customers_groups cg,rfq_order_status ro " . $where . "";

    $my_table_head = '<table  width="100%" class="tablesorter" cellspacing="0" cellpadding="10" border="0">
            <thead><tr>
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
        }
        else {
            if ($order_val['status'] == 0 && $order_val['order_updates'] == 0) {
                $my_table .='<b style="color:#e17009;">' . PENDING_PRICING . '</b>';
            }
            if ($order_val['order_updates'] == 1 ) {
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
    
    
}
?>