<?php

/*

  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PICK_LIST);

//if the customer is not logged on, redirect them to the login page

  if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }

 if(get_customer_type($_SESSION['customer_group_id'])!='storeadmin') {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }
if(isset($HTTP_GET_VARS['oID'])) {
    
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
    //$user_id=get_user_id($cust_id);
    $orders_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int)$oID . "'");
    $get_order=tep_db_fetch_array($orders_query);
    
    $user_group = tep_db_query("SELECT * FROM customers where customers_id = " . $HTTP_GET_VARS['cur_user'] . "");
    $users=tep_db_fetch_array($user_group);
    $user_id=$users['customer_group_id'];
    
if($user_id == 5 || $user_id == 6){    
//$order = new order($oID);
       function getManfct_id($id){
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
            $my_data.=$get_customer_address['entry_city'];
            $my_data.=', ';
            $my_data.=$get_customer_address['entry_state'];
            $my_data.=', ';
            $my_data.=$get_customer_address['entry_postcode'];
            $my_data.='</td></tr><tr><td>';
            $my_data.=get_country($get_customer_address['entry_country_id']);
            $my_data.='</td></tr><tr><td>';
            $phone=get_customer_detail($id);

            $my_data.=$phone['customers_telephone'];
            $my_data.='</td></tr><tr><td>';
            $my_data.='</td></tr>';
            echo $my_data;
    } 
    //echo $customer_id;
}
else{
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    //echo $customer_id;
}

}

else {
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
    *{
   margin:0;
   padding:0;
}
 
body{
   text-align:center; /*For IE6 Shenanigans*/
   font-size: 16px;
}
 
#wrapper{
   width:90%;
   margin:0 auto;
   margin-top: 20px;
   text-align:left;
   border-bottom-color: #000;
   border: 2px;
}
table.sample {
        width:100%;
	border-width: 0px;
	border-spacing: 5px;
	border-style: outset;
	border-color: gray;
	border-collapse: separate;
	background-color: white;   
table {
        font-family:Lucida Grande,Lucida Sans,Verdana,Arial,sans-serif;
        font-size:20px;
      }

</style>
</head>
<body>
<!-- body_text //-->
<div id="wrapper">
<table class="sample">
  <tr>
       <td align="center">
            <p style="font-family:Lucida Grande; font-size:40px; font-weight: bold;"><?php echo PICK_LIST; ?></p>
            
       </td>
  </tr>
  <tr>
          <td colspan="2"><?php //echo tep_draw_separator(); ?></td>
  </tr>
  <tr>
    <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="pageHeading">
                    <table width="100%">
                        <tr>
                            
                            <td valign="bottom" align="justify" width="30%">
                              <?php 
                                
                                    //echo nl2br(STORE_NAME_ADDRESS); 
                                    //$defined_vars = get_defined_vars();
                                
                                ?>
                         </td>
                        </tr>
                    </table>
                 </td>
            </tr>
            <tr>
                <td >
                    <table  width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr height="25px">
                            <td colspan="2"><?php //echo tep_draw_separator(); ?></td>
                        </tr>
                        <tr width="80%">
                            <td valign="top" >
                                <table width="90%" align="right">
                                    <tr>
                                      <td><strong><?php echo SHIP_TO; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php 
                                                $cur_customer=getManfct_id($oID);
                                                $cur_customer_id=$cur_customer['customer_id'];
                                                customer_information($cur_customer_id);
                                            ?>
                                        </td>
                                    </tr>  
                                </table>
                            </td>
                            <td>
                                <table width="65%" cellpadding="2" align="center">
                                     <?php  $orders_results = tep_db_query("SELECT * FROM rfq_order WHERE rfq_id = '" . (int)$oID . "'"); 
                                     $rfq_order_detail=tep_db_fetch_array($orders_results);
                                     $date=date();
                                     ?>
                                    <tr>  
                                        <td align="left">Order #: </td><td><?php echo $oID; ?></td>
                                    </tr>
                                    
                                    <tr>  
                                        <td align="left">Model: </td><td><?php echo $rfq_order_detail['model']; ?></td>
                                    </tr>
                                    <tr>  
                                        <td align="left">Issue #:</td><td><?php echo $rfq_order_detail['issue_no']; ?></td>
                                    </tr>
                                    <tr>
                                       <td align="left">PO #:</td> <td><?php echo $rfq_order_detail['po_no']; ?>  </td>
                                    </tr>
                                    <tr>
                                       <td align="left">Serial #:</td> <td><?php echo $rfq_order_detail['serial']; ?>  </td>
                                    </tr>
                                    <tr>
                                       <td align="left">Date: </td> <td><?php echo  date("Y/m/d"); ?></td>
                                    </tr>
                                 
                                </table>
                            </td>
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
                    <tr align="center">
                          <th><?php echo QUANTITY_TITLE; ?></th>
                          <th><?php echo MANUFACTURE_TITLE; ?></th>
                          <th><?php echo PART_NUMBER_TITLE; ?></th>
                          <th><?php echo DESCRIPTION_TITLE; ?></th>
                          <th><?php echo PART_TYPE_TITLE; ?></th>
                          <th><?php echo WAREHOUSE_LOCATION; ?></th>
                          <th><?php echo SUBS; ?></th>
                          <th><?php echo PT; ?></th>
                          <th><?php echo NOTES1; ?></th>
                    </tr>
                    <tr>
                        <td colspan="10"><?php echo tep_draw_separator(); ?></td>
                    </tr>
                     <?php  $orders_query_detail = tep_db_query("select * from rfq_order_detail where rfq_id = '" . (int)$oID . "'");
                        while($rfq_order_detail=tep_db_fetch_array($orders_query_detail)){ ?>
                    <tr>
                          <td align="center" width="5%"><?php echo $rfq_order_detail['qty']; ?></td>
                          <td align="center" width="5%">
                              <?php $mfg_id=getManfct_id($rfq_order_detail['rfq_id']);
                                    echo  getManfct_name($mfg_id['manufacturer']);
                              ?>
                          </td>
                          <td align="center"><?php echo $rfq_order_detail['part_number']; ?></td>
                          <td align="center" width="12%"><?php echo $rfq_order_detail['description']; ?></td>
                          <td align="center" style="font-size: 15px;" width="12%"><?php echo $rfq_order_detail['part_type']; ?></td>
                          <td align="center" width="20%"><?php echo $rfq_order_detail['action']; ?></td>
                          <td align="center" width="5%"><?php echo $rfq_order_detail['subs']; ?></td>
                          <td align="center" width="5%"><?php echo $rfq_order_detail['pt']; ?></td>
                          <td align="center" width="30%"><?php echo $rfq_order_detail['notes1']; ?></td>
                          
                          
                          
                    </tr>
                      <?php 
                           //echo $get_order;        
                      
                        } 
                      ?>
                </table>
              </td>
            </tr>
        </table>
    </td>
  </tr>
</table>
<!-- body_text_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

