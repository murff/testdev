<?php

/*

  $Id$



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2010 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');

 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INVOICE);

 // require(DIR_WS_CLASSES . 'currencies.php');

  //$currencies = new currencies();



  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

  $orders_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$oID . "'");


$get_order=tep_db_fetch_array($orders_query);
 
 function getManfct_id($id)
{
	$manfct_id  = tep_db_fetch_array(tep_db_query("select rfq_id,manufacturer,customer_id from  rfq_order where rfq_id = ".$id.""));
	return $manfct_id;
}

function get_country($id){
	
	$manfct  = tep_db_fetch_array(tep_db_query("select countries_name from countries where countries_id = ".$id.""));
	
	return $manfct['countries_name'];
	
	}

function get_customer_detail($id){
	
	$cut_deatail  = tep_db_fetch_array(tep_db_query("select customers_email_address,customers_telephone from customers where customers_id = ".$id.""));
	
	return $cut_deatail;
	
	}
function customer_information($id){
	
			$customer_address_query = tep_db_query("select * from  address_book where customers_id = '" . $id . "'");


		$get_customer_address=tep_db_fetch_array($customer_address_query); 
		$my_data='<tr><td>';
		$my_data.=$get_customer_address['entry_firstname'];
		$my_data.='  ';
		$my_data.=$get_customer_address['entry_lastname'];
		$my_data.='</td></tr><tr><td>';
		$my_data.=$get_customer_address['entry_street_address'];
		$my_data.='</td></tr><tr><td>';
		$my_data.=$get_customer_address['entry_suburb'];
		$my_data.='</td></tr><tr><td>';
		$my_data.=$get_customer_address['entry_state'];
		$my_data.=',';
		$my_data.=$get_customer_address['entry_postcode'];
		$my_data.='</td></tr><tr><td>';
		$my_data.=$get_customer_address['entry_state'] ;
		$my_data.=',';
		$my_data.=get_country($get_customer_address['entry_country_id']);
		$my_data.='</td></tr><tr><td>';
		
		$phone=get_customer_detail($id);
		
		$my_data.=$phone['customers_telephone'];
		$my_data.='</td></tr><tr><td>';
		$my_data.=$phone['customers_email_address'];
		$my_data.='</td></tr>';
		echo $my_data;
	} 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
table {
 font-family:Lucida Grande,Lucida Sans,Verdana,Arial,sans-serif;

 font-size:13px;
}
</style>
</head>

<body>



<!-- body_text //-->

<table border="0" width="100%" cellspacing="0" cellpadding="2">

  <tr>

    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td class="pageHeading">
        	<table>
            	<tr>
                	<td>
						<?php echo tep_image('images/logo.png', STORE_NAME); ?>
                    </td>
                 </tr>
                 <tr>
                 	<td>
						<?php echo nl2br(STORE_NAME_ADDRESS); ?>
                    </td>
                  </tr>
              </table>
         </td>
        <td class="pageHeading"  valign="top">
        	<table>
        		<tr>
        			<th align="left">
						<?php echo REMIT_TO; ?>
        			</th>
        		</tr>
        		<tr>
        			<td>
						<?php echo nl2br(STORE_NAME_ADDRESS); ?>
        			</td>
        		</tr>
        	</table>
        </td>

        <td class="pageHeading" align="left">
        	<table>
            	<tr>
                	<th colspan="2" align="left">
						<?php echo AR_INVOICE; ?>
                     </th>
                 </tr>
                 <tr>
                 	<th align="left"><?php echo STREET_NUMBER; ?></th>
                    <td align="center">123</td>
                 </tr>
                 <tr>
                 	<th align="left"><?php echo TOTAL_INVOICE; ?></th>
                    <td align="center">$36</td>
                 </tr>
                 <tr>
                 	<td align="left"><?php echo INVOICE_DATE; ?></td>
                    <td align="center">24-06-2014</td>
                 </tr>
                 <tr>
                 	<td align="left"><?php echo INVOICE_DUE_DATE; ?></td>
                    <td align="center">28-06-2014</td>
                 </tr>
                 <tr>
                 	<td align="left"><?php echo SALES; ?></td>
                    <td align="center">1222</td>
                 </tr>
                 <tr>
                 	<td align="left"><?php echo CUSTOMER_ASSISTAN_NUMBER; ?></td>
                    <td align="center">123</td>
                  </tr>
                  <tr>
                  	<td align="left"><?php echo CUSTOMER_PO_NO; ?></td>
                    <td align="center">12</td>
                  </tr>
                  <tr>
                  	<td align="left"><?php echo INVOICE_STATUS; ?></td>
                    <td align="center">Approved</td>
                  </tr>
              </table>
           </td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td ><table  width="100%" border="0" cellspacing="0" cellpadding="2">

      <tr>

        <td colspan="2"><?php echo tep_draw_separator(); ?></td>

      </tr>

      <tr>

        <td><table width="50%" cellpadding="2" align="center">

          <tr>

            <td ><strong><?php echo BILL_TO; ?></strong></td>

          </tr>
 <?php 
		   $customer=getManfct_id($oID);
		    $customer_id=$customer['customer_id'];
		 customer_information($customer_id);
		   
		  ?>

        </table></td>

        <td valign="top"><table width="50%" align="center">

          <tr>

            <td><strong><?php echo SHIP_TO; ?></strong></td>

          </tr>
 <?php 
		   $customer=getManfct_id($oID);
		    $customer_id=$customer['customer_id'];
		 customer_information($customer_id);
		   
		  ?>
        </table></td>

      </tr>

    </table></td>

  </tr>
 <tr>

            <td colspan="2"><?php echo tep_draw_separator(); ?></td>

          </tr>
<tr> <td align="center">
    	<table width="100%">
        
        	<tr>
        		<td ><?php echo CURRENT_INVOICE_NUMBER; ?></td> <td >12</td>
                <td ><?php echo DATE; ?></td> <td >24-06-2014</td>
                
                <td><?php echo TERM; ?></td> <td>yes</td>
                <td><?php echo NET_VALUE; ?></td> <td>500</td>
              
            </tr>
        </table>
      </td>
    </tr>
    <tr>

            <td colspan="2"><?php echo tep_draw_separator(); ?></td>

          </tr>
  <tr>

    <td>
    	<table cellpadding="10" width="100%">
        	<tr>
        		<th><?php echo LINE_TITLE; ?></th>
                <th><?php echo PART_TYPE_TITLE; ?></th>
                <th><?php echo PART_NUMBER_TITLE; ?></th>
                <th><?php echo DESCRIPTION_TITLE; ?></th>
                <th><?php echo QUANTITY_TITLE; ?></th>
                <th><?php echo UNIT_PRICE_TITLE; ?></th>
                <th><?php echo EXTENDED_TITLE; ?></th>
            </tr>
            <?php  $orders_query_detail = tep_db_query("select * from rfq_order_detail where rfq_id = '" . (int)$oID . "'");
			$i=1;
			while($rfq_order_detail=tep_db_fetch_array($orders_query_detail)){ ?>
            <tr>
        		<td align="center"><?php echo $i; ?></td>
                <td align="center"><?php echo $rfq_order_detail['part_type']; ?></td>
                <td align="center"><?php echo $rfq_order_detail['part_number']; ?></td>
                <td align="center"><?php echo $rfq_order_detail['description']; ?></td>
                <td align="center"><?php echo $rfq_order_detail['qty']; ?></td>
                <td align="center">$<?php echo $rfq_order_detail['price']; ?></td>
                <td align="center">Yes</td>
            </tr>
            <?php $i++;
			 } ?>
             <tr>
               <th colspan="7" align="right"><?php echo LINES_ITEM_TOTAL; ?></th>
               <td colspan="7" align="right">5000</td>
             </tr>
             <tr>
             	<th colspan="7" align="right"><?php echo TOTAL_INVOICE; ?></th>
               <td colspan="7" align="right">5600</td>
             </tr>
          </table>

    </td>

  </tr>
    
<tr>
	<td class="main" >
    	<table width="100%">
        	<tr>
            	<td>
                	<table>
                    	<tr>
                        	<td><?php echo COMMENTS; ?></td>
                        </tr>
                		<tr>
                        	<td>
                            	Goods remain the property of ItemNet International until paid in full.
                 			</td>
                       </tr>
                 		<tr>
                        	<td>
                            	All returned checks are subject to a $50 handling fee.
                 			</td>
              		   </tr>
                 		<tr>
                        	<td>
                            	Inboices not paid within established terms are subject to a 4% or $50 monthly charge.
                 			</td>
                        </tr>
                 		<tr>
                        	<td>
                            	Legal fees will be levied if collection is necessary.
                 			</td>
                       </tr>
                 		<tr>
                        	<td>
                            	Defective product must be reurrned within 30 days of purchase. No refunds. Exchange only at ItemNet's discretion.
                 			</td>
                       </tr>
                 		<tr>
                        	<td>
                            	Not all items are returnable. RMAs may be subject to a minimum 30% restocking fee.
                 			</td>
                       </tr>
                 		<tr>
                        	<td>
                            	Any products returned without an RMA or 7 days past RMA request will be refused or assessed additional charges
                 			</td>
                       </tr>
      		           <tr>
                       		<td>
                            	Core returns must be received by ItemNet within 7 days of purchase or full purchase price will be applied.
                 		</td>
                     </tr>
                </table>
             </td>
    	</tr>
     </table>
  </td>
<tr>
 
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>

          </tr>
<tr>
<td class="main"><table width="100%" cellpadding="10">
        	<tr>
        		<th><?php echo SHIPMENT_FOR_INVOICE_TITLE; ?></th>
                <th><?php echo SHIPPER_TITLE; ?></th>
                <th><?php echo SERVICE_TITLE; ?></th>
                <th><?php echo SHIPMENT_DATE; ?></th>
                <th><?php echo LINE_ITEM; ?></th>
                <th><?php echo PACKAGE_TITLE; ?></th>
            </tr>
            <tr>
        		<td align="center">Shipments for invoice</td>
                <td align="center">Shipper</td>
                <td align="center">Service</td>
                <td align="center">Shipment Date</td>
                <td align="center">Line Item</td>
                <td align="center">Packages</td>
            </tr>
            </table></td>
</tr>
</table>

<!-- body_text_eof //-->



<br />

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

