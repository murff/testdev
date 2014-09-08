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
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VIEW_ORDER);
  require('includes/classes/http_client.php');

 $cus_id = $_GET['po'];

// if the customer is not logged on, redirect them to the login page
//if (!isset($HTTP_GET_VARS['customer_id'])) {

  if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();
	//echo tep_session_is_registered('customer_id');
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }
  function get_customer_id($id){
	
	$customers_email_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$id . "'");
        $customers_email = tep_db_fetch_array($customers_email_query);
        //$customers_email_info = new objectInfo($customers_email);
		//$cust_id=$customers_email_info->customer_id;
		return $customers_email['customer_id'];
	}
//}


// if there is nothing in the customers cart, redirect them to the shopping cart page

  //if ($cart->count_contents() < 1) {

  //  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

 // }


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);



 if (isset($_GET['po']) && $_GET['action']=='rfqconfirm') {
	 $sql_data_array = array('order_updates' => 0,'status' => 1,'`order/quote_status`' => 1);
	 
	 tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int)$_GET['po'] . "'");
	 
	 
	 $email_order2 = ACCEPTED_THE_UPDATED_ORDER.$_GET['po']. "\n\n" .
 		 VIEW_ORDER . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$_GET['po']."&action=view'>http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$_GET['po']."&action=view</a>\n\n" ;
	 
	 //ger_customer_i function retun customer id
      $customer_id=get_customer_id($_GET['po']);
	  
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
	 //////////////////////////////////////////////////////////////////////////////////////
	 $email_order = UPDATED_QUOTE_ORDER_NO.$_GET['po'].HAS_BEEN_ACCEPTED. 
                 EMAIL_SEPARATOR . "\n\n" .
 		 VIEW_ORDER . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/vieworder.php?po=" .$_GET['po']. "'>http://itemnet.ca/cartest/vieworder.php?po=" .$_GET['po']. "</a>\n\n" ;
		 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , UPDATED_QUOTE_ORDER_UPDATED, $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
////////////////////////////////////////////////////////////////////////////////////////
	
	 tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], UPDATED_QUOTE_ORDER_UPDATED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	 
	 }else 
	 if (isset($_GET['po']) && $_GET['action']=='rfqdecl') {
	$sql_data_array = array('order_updates' => 2,'status' => 2,'`order/quote_status`' => 0);
	 
	 tep_db_perform('rfq_order', $sql_data_array, 'update', "rfq_id = '" . (int)$_GET['po'] . "'");
	 
	 
	 $email_order = HAVE_DECLINE_UPDATED_ORDER_NO.$_GET['po']. "\n\n" .
 		 VIEW_ORDER . "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$_GET['po']."&action=view'>http://itemnet.ca/cartest/admin/rfq_orders.php?page=1&cID=".$_GET['po']."&action=view</a>\n\n" ;
	 
	 //ger_customer_i function retun customer id
      $customer_id=get_customer_id($_GET['po']);
	  
	  $customers_email = tep_db_query("select * from customers where customers_id = '" . (int)$customer_id. "'");
      $customer_address = tep_db_fetch_array($customers_email);
	 //////////////////////////////////////////////////////////////////////////////////////
	 $email_order2 = UPDATED_QUOTE_ORDER_NO .$_GET['po'].HAS_BEEN_DECLINED . 
                 EMAIL_SEPARATOR . "\n\n" .
 		 VIEW_ORDER. "\n" .EMAIL_SEPARATOR . "\n<a href='http://itemnet.ca/cartest/vieworder.php?po=" .$_GET['po']. "'>http://itemnet.ca/cartest/vieworder.php?po=" .$_GET['po']. "</a>\n\n" ;
		 tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS , UPDATED_QUOTE_ORDER_DECLINED, $email_order2, $customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address']);
////////////////////////////////////////////////////////////////////////////////////////
	
	 tep_mail($customer_address['customers_firstname'] . ' ' . $customer_address['customers_lastname'], $customer_address['customers_email_address'], UPDATED_QUOTE_ORDER_DECLINED, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	 
	 }




 // dont show left and right block

 $dontshowleftright=1;

  

  require(DIR_WS_INCLUDES . 'template_top.php');

 ?>
 




 



<h1><?php //echo 'Place a new Request for Quote'; ?></h1>



 

 

  <div style="clear: both;"></div>



 

 



 



 
<?php /*?><?php
echo "test 1";
echo 
print_r($result);


print_r($res);
echo "test";
echo $res['customer_id'];
?>
<?php */
function getManfct_name($id)
{
	$manfct  = tep_db_fetch_array(tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = ".$id.""));
	return $manfct['manufacturers_name'];
}
$result= tep_db_query('SELECT * FROM rfq_order where rfq_id='.$cus_id.'');

$res= tep_db_fetch_array($result);
$rfq_id_fro= $res['rfq_id'];

 
?>
 
  <div class="contentText">

    <table border="0" width="100%" cellspacing="1" cellpadding="2">

      <tr>

        <td width="100%" valign="top">
        	<table border="0" width="100%" cellspacing="0" cellpadding="2">
			
            <?php /*?><tr>
          

            <td width="20%"><strong>Status:</strong></td>
            <td width="80%"><?php if($res['status']==0) echo "<b style=\"color:#e17009\">Pending</b>"; ?>
                   <?php if($res['status']==1) echo "<b style=\"color:#2e6e9e\">Accepted</b>"; ?>
                    <?php if($res['status']==2) echo "<b style=\"color:#F00\">Declined</b>"; ?>

	    </td>

          </tr><?php */?>

		
          <tr>
          

            <td width="20%"><b>Manufacturer : </b></td><td width="80%"><?php echo getManfct_name($res['manufacturer']);?>

	    </td>

          </tr>

          <tr>

        <td><b>Serial  :  </b></td><td>	<?php echo $res['serial'];?></td>

          </tr>

          <tr>

        <td><b>Model  : </b></td><td>	<?php echo $res['model'];?></td>

          </tr>

         

        </table></td>

        

      </tr>
      
      <tr>

      <td>

      <table border="0" width="100%" cellspacing="5" cellpadding="2">

      <tr>

      		<th>Qty</th>

      	 

      		<th>Part Type</th>

      	 

      		<th>Description</th>

      	 

      		<th>Part Number</th>

      	 

      		<th>Price</th>

      	 

       		</tr>
            <?php
$result1= tep_db_query('SELECT * FROM rfq_order_detail where rfq_id='.$rfq_id_fro.'');

 
 
 while($res1= tep_db_fetch_array($result1)){ 
?>

                <tr>

 

		<td valign="top" align="center"><?php echo  $res1['qty'];?></td>

	 

		<td valign="top" align="center"><?php echo  $res1['part_type'];?></td>

 

		<td valign="top" align="center"><?php echo  $res1['description'];?></td>

 

		<td valign="top" align="center"><?php echo  $res1['part_number'];?></td>

 

		<td valign="top" align="center"><?php echo  $res1['price'];?></td>

 		</tr>
<?php } ?>
	
		</table>

      </td></tr>

        <tr><td>

	

	  <table border="0" width="100%" cellspacing="0" cellpadding="6"><tr>

              <td width="20%"><b>Issue No  : </b></td><td width="80%" ><?php echo $res['issue_no'];?>	</td>

                </tr>

                <tr><td><b>PO No : </b></td><td><?php echo $res['po_no'];?></td>

                </tr>

                <tr>              <td><b>Notes : </b></td><td><?php echo $res['notes']; ?></td>

		</tr>

		<tr><td colspan="2">	<?php 
		if($res['order_updates']==1) {
		echo '&nbsp;&nbsp;'.tep_draw_button('Accept', '', tep_href_link('vieworder.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqconfirm')) ; 
		echo '&nbsp;&nbsp;'.tep_draw_button('Decline', 'trash', tep_href_link('vieworder.php', tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->rfq_id . '&action=rfqdecl')); 
		
		}?></td>

                </tr></table></td></tr>

    </table>

  </div>

  

  

  



	 

 



    <div style="float: right;"><?php //echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'primary'); ?></div>

  </div>

</div>



 



</form>



<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>

