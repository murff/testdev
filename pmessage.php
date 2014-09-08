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
<?php
  require('includes/application_top.php');
  if(get_customer_type($_SESSION['customer_group_id'])!='storeusers') {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link('allorders.php', '', 'SSL'));

  }
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VIEW_DETAIL);
//return customer id
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  //require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  //$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
 ?>

 <?php  $customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . $cust_id . "'");
        $cust_nfo = tep_db_fetch_array($customers_query); 

	?>






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
   url:'private_messaging.php?act=save&comment='+comment+'&cID='+cid,
    success: function(data){
 	//alert(data);
	 $("#loader").hide();
   $(".commentHistory").html(data);
  // $("#feedbackcommon"+act_id).show();
   
   
    }
   });
 }
 
 
 </script>

<div class="contentContainer">

  <div class="contentText">
  
 <h1><?php echo 'Private Messages'; ?></h1>
 
 
 <form name="commnetform" id="commnetform" method="post">
<h3><?php echo COMMENT_HISTORY; ?></h3>
<div class="commentHistory">
<table width="100%" cellspacing="0" cellpadding="10" border="0">
  <tbody>
  <?php  $comment_his_sql = tep_db_query("select * from private_messaging where customer_id=".$_SESSION['customer_id']."  order by date_added asc");
  while($comment_info = tep_db_fetch_array($comment_his_sql)) {
	  

 	?>
      	 <tr class="">
        	<th colspan="4" align="left">
                <h3 class="customer"><?=get_customer_fullname($comment_info["customer_id"])?></h3>
                <small>posted on: <?=date('d M, Y',strtotime($comment_info["date_added"]))?></small>
                <p><?=$comment_info["comments"]?></p>
          	</th>
      </tr>
 <?php } ?>
     
      <tr class="">
        <td colspan="4" align="left"><textarea name="comment_history" id="comment_history" cols="45" rows="5" placeholder="Add comment"></textarea></td>
        </tr>
     <tr class="">
        <td colspan="4" align="left"><div id="loader"><img src="images/opc-ajax-loader.gif" /></div> <input type="button" name="Order" id="Order" value="Save comment" class="myButton" onclick="updateComment();" /></td>
        </tr>
        </tbody></table>
        
        </div>
        </form>
  </div>


</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>