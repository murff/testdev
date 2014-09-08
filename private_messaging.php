<?php require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRIVATE_MESSAGING);
define('EMAIL_SEPARATOR', '------------------------------------------------------');
if($_REQUEST['act']=='save') {
 $price_query = tep_db_query("insert into private_messaging(customer_id,comments)values(".$_SESSION['customer_id'].",'".$_REQUEST['comment']."')");
 

	
		$email_order =   get_customer_fullname($_SESSION['customer_id']).ADD_NEW_MESSAGE . " \n" . " \n" .MESSAGE . "\n" .EMAIL_SEPARATOR . " \n" .$_REQUEST['comment'] . " \n" ." \n" .
		EMAIL_SEPARATOR . "\n\n" .	ADDED_ON. date("Y-m-d H:i:s A").               
		
 		" \n" . " \n" ." \n" .getSignature(); ;
	   
	
	 
  
	 
	 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , PRIVATE_MESSAGE_FROM .get_customer_fullname($_SESSION['customer_id']) , $email_order,get_customer_fullname($_SESSION['customer_id']), get_customer_emailaddress($_SESSION['customer_id']));

 	//	tep_mail(get_customer_fullname($customer_id_tmp), get_customer_emailaddress($customer_id_tmp), "New comment  " .strtoupper($order_query['order/quote']).'#'.$rfq_id. "", $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  
 
 
	
	
	        
         
 
 ?>
 <table width="100%" cellspacing="0" cellpadding="10" border="0">
  <tbody>
  <?php  $comment_his_sql = tep_db_query("select * from private_messaging where customer_id=".$_SESSION['customer_id']."  order by date_added asc");
  while($comment_info = tep_db_fetch_array($comment_his_sql)) {
	  

 	?>
      	 <tr class="">
        	<th colspan="4" align="left">
                <h3 class="customer"><?=get_customer_fullname($comment_info["customer_id"])?></h3>
                <small><?php echo POSTED_ON;?><?=date('d M, Y',strtotime($comment_info["date_added"]))?></small>
                <p><?=$comment_info["comments"]?></p>
          	</th>
      </tr>
 <?php } ?>
     
      <tr class="">
        <td colspan="4" align="left"><textarea name="comment_history" id="comment_history" cols="45" rows="5" placeholder="Add comment"></textarea></td>
        </tr>
     <tr class="">
        <td colspan="4" align="left"><div id="loader"><img src="images/opc-ajax-loader.gif" /></div> <input type="button" name="Order" id="Order" value="<?php echo SAVE_COMMENT;?>" class="myButton" onclick="updateComment();" /></td>
        </tr>
        </tbody></table>         
<?php
}
?>