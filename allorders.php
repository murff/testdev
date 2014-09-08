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
if ($page <= 0){
    $page = 1;
}
$per_page = 10; // Set how many records do you want to display per page.

$startpoint = ($page * $per_page) - $per_page;

$columname = $_REQUEST['search_variable'];
$searchtext = $_REQUEST['search_value'];

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
<!-- Added to show date picker calendar SEAN ////////////////////////////////-->
 <link rel="stylesheet" href="css/pikaday.css">
 <link rel="stylesheet" href="css/site.css">
 <link rel="stylesheet" href="css/datagrid.css" />
 
<h1><?php echo ORDER_QUOTES_TITLE; ?></h1>
<form method="post" name="searchform"><?php echo SEARCH_TITLE; ?>
    <label for="select"></label>
    <select name="search_variable" id="search_variable" onchange="show_addres(this);">
        <option value="rfq_id"><?php echo ORDER_ID; ?></option> 
        <option value="po_no"><?php echo PO_NO; ?></option>
        <option value="issue_no"><?php echo ISSUE_NO; ?></option>
        <option value="serial"><?php echo SERIAL_NO; ?></option>
        <option value="date_added"><?php echo DATE; ?></option>
    </select>
    <input name="search_value" id="country_name" class="form-control txt-auto"/><input  id="datepicker" class="form-control txt-auto" style="display:none" value="click to add start date" />&nbsp;&nbsp;<input  id="datepicker1" class="form-control txt-auto" style="display:none" value="click to add end date"/>
    <input type="button" name="Button" id="button" value="<?php echo SEARCH_BUTTON; ?>" class="myButton" onclick="searchFilter();" />

    <a href="allorders.php"><input type="button" name="button2" id="button2" value="<?php echo CLEAR_BUTTON; ?>" class="myButton" /></a>
    <div id="loader"><img src="images/opc-ajax-loader.gif" /></div>
<!-- /////////////////////Call////JS// After input is initiated///////////////////////////////-->
    <script src="js/pikaday.js"></script>
    <script src="js/datagrid.js"></script>

</form>
   
<?php
    if ($messageStack->size('account') > 0) {
        echo $messageStack->output('account');
    }
?>
<div class="contentContainer">
    <div class="contentText">
        <?php 
            require('includes/datagrid.php');
        ?>
        <br/><br/>
    </div>
</div>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>