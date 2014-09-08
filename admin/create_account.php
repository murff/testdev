<?php
/*
  $Id: create_account.php,v 1 2003/08/24 23:21:27 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
   
  Admin Create Accont
  (Step-By-Step Manual Order Entry Verion 1.0)
  (Customer Entry through Admin)
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
if(isset($_REQUEST['action']) && $_REQUEST['checkuser']=='checkuser') {
	
	$num = tep_db_num_rows(tep_db_query("select customers_username from customers where customers_username = '".$_REQUEST['user']."'"));
	return $num; exit;
}
require('includes/form_check.js.php'); ?>
<style>
.staples
{
display:none;	
}
</style>
<script>
function showstaples(strng)
{
	if(strng==3)
	{
		$(".staples").show();
	}
	 else
	{
	$(".staples").hide();	
	}
	
}
$("#account_edit").submit(function(e) {
var usr = $("#customers_username").val();
$.ajax({
   url:'create_account.php?action=checkuser&user='+usr,
    success: function(data){
 	//alert(data);
	if(data >0) {
			e.preventDefault();
	$("#customers_username").addClass('errorquantity');
	$("#customers_username").focus();
	$("#customers_username").val('');
	}
	
    }
	
   });

});

</script>
<style>
.storeprice
{
visibility:hidden;	
}
.errorquantity
{
border-color:#F00;	
	
}
</style>
<?php header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" id="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_CREATE_ACCOUNT; ?></td>
          </tr>
	  <tr><td>For Bulk Import <a href="import_customers.php">Click Here</a></td></tr>
        </table></td>
      </tr>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  //$email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;
  $account['entry_zone_id'] = STORE_ZONE;

  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'template_bottom.php');
?>
<!-- footer_eof //-->
<br>
<script>
$("#customers_password").val('');
</script>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>