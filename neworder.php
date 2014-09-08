<?php

/*

  $Id$
//ravinder


  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2012 osCommerce



  Released under the GNU General Public License

*/

define('EMAIL_SEPARATOR', '------------------------------------------------------');



  require('includes/application_top.php');

  require('includes/classes/http_client.php');
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEW_ORDER);
//echo "<pre>";
//print_r($_SESSION); exit;

// if the customer is not logged on, redirect them to the login page

  if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }

 if(get_customer_type($_SESSION['customer_group_id'])=='storeusers') {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link('allorders.php', '', 'SSL'));

  }

// if there is nothing in the customers cart, redirect them to the shopping cart page

  //if ($cart->count_contents() < 1) {

  //  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

 // }


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);



 



 // dont show left and right block

 $dontshowleftright=1;

  

  require(DIR_WS_INCLUDES . 'template_top.php');
  $customer_info = tep_db_query("select  `order/quote` from rfq_order where customer_id = '" . (int)$id. "'");
      $customer_info_get= tep_db_fetch_array($customer_info);

  $request_type = array(array('id' => 'order', 'text' => 'Order'),
                                 array('id' => 'quote', 'text' => 'Quote'),
								 );
								
							if($_SESSION['language']=='english')	{
   $cat_query = tep_db_query("select a.categories_id, b.categories_name,b.price_a from " . TABLE_CATEGORIES . " a ," .TABLE_CATEGORIES_DESCRIPTION. " b where a.categories_id=b.categories_id and a.parent_id=0 and b.language_id=1 order by b.categories_name");  }
   
   
   if($_SESSION['language']=='french')
   {
   $cat_query = tep_db_query("select a.categories_id, b.categories_name,b.price_a from " . TABLE_CATEGORIES . " a ," .TABLE_CATEGORIES_DESCRIPTION. " b where a.categories_id=b.categories_id and a.parent_id=0 and b.language_id=2 order by b.categories_name");  }
     

  	    $cat_arrayt = array();

  	       

  		           $cat_arrayt[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);

//<option value="" selected="selected">Please Select</option><option value="19">ADAPTER</option><option value="49">BASE</option><option value="40">BATTERY</option><option value="98">DESKTOP OTHER</option><option value="58">DESKTOP POWER SUPPLY</option><option value="148">DESKTOP VIDEO CARD</option><option value="37">DVD</option><option value="1">FAN</option><option value="4">FRONT BEZEL</option><option value="61">GREASE</option><option value="43">HDD</option><option value="31">HINGE PAIR</option><option value="46">KEYBOARD BILINGUAL</option><option value="16">KEYBOARD ENGLISH</option><option value="10">LCD BACK COVER</option><option value="114">LCD COMPLETE ASSY</option><option value="52">LCD PANEL ONLY</option><option value="125">LCD WIRE SET</option><option value="55">MOTHERBOARD</option><option value="22">OTHER</option><option value="25">PALMREST</option><option value="7">RAM</option><option value="109">SSD</option><option value="13">SWITCH COVER</option><option value="34">THERMAL MODULE</option><option value="28">WIFI</option><option value="223">Z QUOTE</option>  		           

  			    			    

  			    			              while ($cat = tep_db_fetch_array($cat_query)) {

  			    			                $categories_name =    $cat['categories_name'] ;
											$categories_id =    $cat['categories_id'] ;

      if($cat['categories_id']=='19')$cat['categories_id']=35;

      if($cat['categories_id']=='40')$cat['categories_id']=65;

      if($cat['categories_id']=='49')$cat['categories_id']=0;

      if($cat['categories_id']=='98')$cat['categories_id']=0;

      if($cat['categories_id']=='58')$cat['categories_id']=30;

      if($cat['categories_id']=='148')$cat['categories_id']=110;

      if($cat['categories_id']=='37')$cat['categories_id']=0;

      if($cat['categories_id']=='1')$cat['categories_id']=25;

      if($cat['categories_id']=='4')$cat['categories_id']=25;

      if($cat['categories_id']=='61')$cat['categories_id']=10;

      if($cat['categories_id']=='43')$cat['categories_id']=80;

      if($cat['categories_id']=='31')$cat['categories_id']=30;

      if($cat['categories_id']=='46')$cat['categories_id']=85;

      if($cat['categories_id']=='16')$cat['categories_id']=35;

      if($cat['categories_id']=='10')$cat['categories_id']=27;

      if($cat['categories_id']=='114')$cat['categories_id']=350;

      if($cat['categories_id']=='52')$cat['categories_id']=135;

      if($cat['categories_id']=='125')$cat['categories_id']=27;

      if($cat['categories_id']=='55')$cat['categories_id']=250;

      if($cat['categories_id']=='22')$cat['categories_id']=35;

      if($cat['categories_id']=='25')$cat['categories_id']=45;

      if($cat['categories_id']=='7')$cat['categories_id']=25;

      if($cat['categories_id']=='109')$cat['categories_id']=0;

      if($cat['categories_id']=='13')$cat['categories_id']=30;

      if($cat['categories_id']=='34')$cat['categories_id']=0;

      if($cat['categories_id']=='28')$cat['categories_id']=0;

      if($cat['categories_id']=='223')$cat['categories_id']=0;

  			    			              																		  
											$cat_arrayt[] = array('id' =>  $categories_id,

  			    			                                               'text' => $categories_name);

  			    			              }
  			    			              
  			    			              
  			    			              
  			    			             

?>
<h1><?php echo PLACE_REQUEST_TITLE; ?></h1>
<?php if(isset($_SESSION['newOrderError']) && $_SESSION['newOrderError']!='') { ?>
  <div class="notification error"><p><span><?php echo ERROR; ?></span> <?php echo $_SESSION['newOrderError']; ?>.</p></div>
  <?php unset($_SESSION['newOrderError']); $_SESSION['newOrderError']='';}?>
 <div style="clear: both;"></div>
 <style>
 .errorquantity
{
border-color:#F00;	
	
}
 </style>
 <?php if(get_customer_type($_SESSION['customer_group_id'])=='customer') { ?>
<style>
.storeprice
{
visibility:hidden;	
}

</style>
<?php }echo tep_draw_form('neworder', tep_href_link('neworder_process.php', '', 'SSL'), 'post', 'id="neworder"', true) . tep_draw_hidden_field('action', 'process'); ?>

 <script type="text/javascript">

function show_addres(element)
{
         
    if (element.options[element.selectedIndex].value == 12){
            alert("Hi, I'm alert!");
        }
            
}
            
 
    
    
function updateTextField(s,t)

 {

   /* var select = document.getElementById(s);

    var input = document.getElementById(t);
 var valu = select.value;
    var stts= valu.split(':');

        input.value = stts[0];
*/


$.ajax({
   url:'price.php?p='+s,
    success: function(data){
 	//alert(data);
	var res = data.split("::");
	//return;
	
   $("#"+t).val(res[1]);
   $("#store"+t).html(res[0]);
   <?php if(get_customer_type($_SESSION['customer_group_id'])!='buyer'){ ?>
   if(res[1]==0 || res[1]=='0.00') {
	   
	 $("#Order").hide(); 
	 $("#Orderdisabled").show(); 
	
	 
   } else {
	 $("#Order").show(); 
	 $("#Orderdisabled").hide(); 
	   
   }
 <?php } ?>
   
   
    }
	
   });
   
 }


 </script>
  <div class="contentText">



    <table border="0" width="100%" cellspacing="1" cellpadding="2">

      <tr>

        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="4">

	      <tr>
		      <td><b><?php echo MANUFACTURE_TITLE;?> : </b></td>
		      <td> <?php

	    

	    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");

	    

	    $manufacturers_arrayt = array();

	              if (MAX_MANUFACTURERS_LIST < 2) {

	                $manufacturers_arrayt[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);

	              }

	    

	              while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {

	                $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);

	                $manufacturers_arrayt[] = array('id' => $manufacturers['manufacturers_id'],

	                                               'text' => $manufacturers_name);

	              }

	    

	              $content =  tep_draw_pull_down_menu('manufacturers_id', $manufacturers_arrayt, (isset($_POST['manufacturers_id']) ? $_POST['manufacturers_id'] : ''),  'onchange=show_addres(this); id="manufacturers_id"')  ;

	             

	    

	            echo   $content  ;

	    ?><br /><span style="color:#F00;font-weight:bold;" id = "manufacturers_error"></span></td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
	      </tr>
          <?php if(get_customer_type($_SESSION['customer_group_id'])=='admin') { ?>

	      <tr>

        <td><b><?php echo SHIPPING_TO_TITLE;?>   : </b></td><td>	<?php 
		 $customers_query = tep_db_query("select customers_id, CONCAT(customers_lastname , ' ' , customers_firstname) AS FullName from " . TABLE_CUSTOMERS . " where customer_group_id in(3,4) order by FullName");
		
		   $customers_arrayt = array();
		    //if (SHIPPING_TO_TITLE < 2) {

	                $customers_arrayt[] = array('id' => $_SESSION['customer_id'], 'text' => PULL_DOWN_DEFAULT);

	              //}
		  while ($customers = tep_db_fetch_array($customers_query)) {


	                $customers_arrayt[] = array('id' => $customers['customers_id'],

	                                               'text' =>  $customers['FullName']);

	              }

	    

	              $content =  tep_draw_pull_down_menu('customersid', $customers_arrayt, (isset($_POST['customers_id']) ? $_POST['customers_id'] : ''), '')  ;

	             

	    

	            echo   $content  ;

		
		 ?>
         </td>
         <td></td><td></td>

          </tr>
          <?php } ?>
       

          <tr>

        <td><b><?php echo SERIAL_TITLE ;?>  : 
          <input type="hidden" name="request_type" id="request_type" value="" />
        </b></td><td>	<?php echo tep_draw_input_field('serial_no', '','id = "serial_no"'); ?>
        <br /><span style="color:#F00;font-weight:bold;" id = "serial_error"></span>
        </td>
          <td><b><?php echo MODEL_TITLE ;?>  : </b></td><td>	<?php echo tep_draw_input_field('model_no', '','id = "model_no"'); ?>
           <br /><span style="color:#F00;font-weight:bold;" id = "model_error"></span>
          </td>

          </tr>

      

         

        </table></td>

        

      </tr>

      <tr>

      <td>

      <table border="0" width="100%" cellspacing="5" cellpadding="2">

      <tr>

      		<th><?php echo QUANTITY_TITLE ;?></th>

      	 

      		<th><?php echo PART_TYPE_TITLE ;?></th>

      	 

      		<th><?php echo DESCRIPTION_TITLE ;?></th>

      	 

      		<th><?php echo PART_NUMBER_TITLE ;?></th>
      		<th class="storeprice"><?php echo STAPLES_PRICE; ?></th>

      	 

      		<th><?php echo CUSTOMER_PRICE; ?></th>

      	 

       		</tr>

                <tr>

 

		<td valign="top" align="center">
		<?php echo tep_draw_input_field('cart_quantity1', $_POST['cart_quantity1'], 'size="2" , id = "qempty1" min="1" max="100000"','number') ;?>
        <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg1"></span>
        </td>
        

	 

		<td valign="top" align="center"><?php  

			echo  tep_draw_pull_down_menu('parttype1', $cat_arrayt, (isset($_POST['parttype1']) ? $_POST['parttype1'] : ''), ' id="parttype1" onchange="updateTextField(this.value,\'price1\')" ')  ;

		?>
         <br /><span style="color:#F00;font-weight:bold;" id = "parttype1msg"></span>
        </td>

 

		<td valign="top" align="center" id="saeed"><?php echo tep_draw_input_field('desc1', $_POST['descr1'],'size="12"   id="descr1"');?>
         <br /><span style="color:#F00;font-weight:bold;" id = "descr1msg"></span>
        </td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum1', $_POST['partnum1'],'size="8"  id="partnum1"');?>
        <br /><span style="color:#F00;font-weight:bold;" id = "partnum1msg"></span>
        </td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice1"></div></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price1', $_POST['price1'],' readonly size="4" required="required" id="price1" ');?></td>

 		</tr>

                <tr>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity2', $_POST['cart_quantity2'], 'size="2" id = "qempty2" min="1" max="100000"','number') ;?>
         <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg2"></span>
        </td>

	 

		<td valign="top" align="center"><?php  

					   

			reset($cat_arrayt);		    			    

					    			              echo  tep_draw_pull_down_menu('parttype2', $cat_arrayt, (isset($_POST['parttype2']) ? $_POST['parttype2'] : ''), ' id="parttype2" onchange="updateTextField(this.value,\'price2\')" ')  ;

				?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('desc2', $_POST['descr2'],'size="12"');?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum2', $_POST['partnum2'],'size="8"');?></td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice2"></div></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price2', $_POST['price2'],' readonly size="4" required="required" id="price2" ');?></td>

 		</tr>

                <tr>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity3', $_POST['cart_quantity3'], 'size="2" id = "qempty3" min="1" max="100000"','number') ;?>
         <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg3"></span>
        </td>

	 

		<td valign="top" align="center"><?php  

							   

					reset($cat_arrayt);		    			    

							    			              echo  tep_draw_pull_down_menu('parttype3', $cat_arrayt, (isset($_POST['parttype3']) ? $_POST['parttype3'] : ''), ' id="parttype3" onchange="updateTextField(this.value,\'price3\')" ')  ;

						?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('desc3', $_POST['descr3'],'size="12"');?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum3', $_POST['partnum3'],'size="8"');?></td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice3"></div></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price3', $_POST['price3'],' readonly size="4" id="price3" required="required"');?></td>

 		</tr>

                <tr>

	 

		<td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity4', $_POST['cart_quantity4'], 'size="2" id = "qempty4" min="1" max="100000"','number') ;?>
         <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg4"></span>
        </td>

 

		<td valign="top" align="center"><?php  

									   

							reset($cat_arrayt);		    			    

									    			              echo  tep_draw_pull_down_menu('parttype4', $cat_arrayt, (isset($_POST['parttype4']) ? $_POST['parttype4'] : ''), ' id="parttype4" onchange="updateTextField(this.value,\'price4\')" ')  ;

								?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('desc4', $_POST['descr4'],'size="12"');?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum4', $_POST['partnum4'],'size="8"');?></td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice4"></div></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price4', $_POST['price4'],' readonly size="4" required="required" id="price4" ');?></td>

 		</tr>

                <tr>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity5', $_POST['cart_quantity5'], 'size="2" id = "qempty5" min="1" max="100000"','number') ;?>
         <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg5"></span>
        </td>

 

		<td valign="top" align="center"><?php  

									   

							reset($cat_arrayt);		    			    

									    			              echo  tep_draw_pull_down_menu('parttype5', $cat_arrayt, (isset($_POST['parttype5']) ? $_POST['parttype5'] : ''), ' id="parttype5" onchange="updateTextField(this.value,\'price5\')" ')  ;

								?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('desc5', $_POST['descr5'],'size="12"');?></td>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum5', $_POST['partnum5'],'size="8"');?></td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice5"></div></td>

	 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price5', $_POST['price5'],' readonly size="4" required="required" id="price5" ');?></td>

 		</tr>

                <tr>

 

		<td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity6', $_POST['cart_quantity6'], 'size="2" id = "qempty6" min="1" max="100000"','number') ;?>
         <br /><span style="color:#F00;font-weight:bold;" id = "qemptymsg6"></span>
        </td>

 

		<td valign="top" align="center"><?php  

									   

							reset($cat_arrayt);		    			    

									    			              echo  tep_draw_pull_down_menu('parttype6', $cat_arrayt, (isset($_POST['parttype6']) ? $_POST['parttype6'] : ''), ' id="parttype6" onchange="updateTextField(this.value,\'price6\')" ')  ;

								?></td>

	 

		<td valign="top" align="center"><?php echo tep_draw_input_field('desc6', $_POST['descr6'],'size="12"');?></td>

	 

		<td valign="top" align="center"><?php echo tep_draw_input_field('partnum6', $_POST['partnum6'],'size="8"');?></td>
		<td valign="top" align="center"><div class="storeprice" id="storeprice6"></div></td>

	 

		<td valign="top" align="center"><?php echo tep_draw_input_field('price6', $_POST['price6'],' readonly size="4" required="required" id="price6" ');?></td>

 		</tr>

		 

		

		</table>

      </td></tr>

        <tr><td>

	

	  <table border="0" width="100%" cellspacing="0" cellpadding="2"><tr>

              <td><b><?php echo ISSUE_TITLE ;?>  : </b></td><td >	<?php echo tep_draw_input_field('issue_no', '','id = "issue_no"'); ?>
              <br /><span style="color:#F00;font-weight:bold;" id = "issue_error"></span></td>
                <td><b><?php echo PO_TITLE ;?> : </b></td><td>	<?php echo tep_draw_input_field('customer_po', '','id="customer_po"'); ?>
                <br /><span style="color:#F00;font-weight:bold;" id = "po_error"></span></td> 
                </tr>

             

                <tr>              <td colspan="4"><b><?php echo NOTES_TITLE ;?> : </b></td>

		</tr>

		<tr><td colspan="4">	<?php echo tep_draw_textarea_field('notes', 'soft', '70', '7');?></td>

      </tr></table></td></tr>

    </table>

  </div>

  
<div style="float: right;"><?php if(get_customer_type($_SESSION['customer_group_id'])=='buyer'){ ?>
 <input type="button" name="Order" id="Order" value="<?php echo ORDER; ?>" class="myButton2" />&nbsp;)
    <input type="submit" name="Quote" id="Quote" value="<?php echo QUOTE; ?>" class="myButton" />
<input type="hidden" id = "type_order_quote" value="" />

<?php } else { ?>
<input type="button" name="Orderdisabled" id="Orderdisabled" value="<?php echo ORDER; ?>" class="myButton2" style="display:none;" />

 <input type="submit" name="Order" id="Order" value="<?php echo ORDER; ?>"  class="myButton" />&nbsp;

   <input type="submit" name="Quote" id="Quote" value="<?php echo QUOTE; ?>"  class="myButton" />
<input type="hidden" id = "type_order_quote" value="" />
<?php }  ?>


</div></div>

</div>
</form>
<script>


 $(function() {
$("#Order").click(function() {
	$("#type_order_quote").val('Order');
	
	
});
$("#Quote").click(function() {
	$("#type_order_quote").val('Quote');
	
});
$("#neworder").submit(function(e) {
var tmp = $("#type_order_quote").val();

    var manfct = $("#manufacturers_id").val()
	if(manfct=='' || manfct==0)
	{
	e.preventDefault();
	$("#manufacturers_id").attr('class','errorquantity');
	$("#manufacturers_error").html('<?php echo MANUFACTURE_REQUIRED ;?>');
	} else {
	$("#manufacturers_id").removeAttr('class');
	$("#manufacturers_error").html('');
	}
	
	var serial = $("#serial_no").val()
	if(serial=='' || serial==0)
	{
		
	e.preventDefault();
	$("#serial_error").html('<?php echo SERIAL_REQUIRED ;?>');
	$("#serial_no").addClass('errorquantity');
	} else {
		$("#serial_error").html('');
	$("#serial_no").removeClass('errorquantity');
	}
	var Qty = $("#qempty1").val()
	if(Qty=='' || Qty==0)
	{
		
	e.preventDefault();
	$("#qemptymsg1").html('<?php echo QUANTITY_REQUIRED ;?>');
	$("#qempty1").addClass('errorquantity');
	} else {
		$("#qemptymsg1").html('');
	$("#qempty1").removeClass('errorquantity');
	}
	
	var model = $("#model_no").val()
	if(model=='' || model==0)
	{
	e.preventDefault();
	$("#model_error").html('<?php echo MODEL_REQUIRED ;?>');
	$("#model_no").addClass('errorquantity');
	} else {
		$("#model_error").html('');
	$("#model_no").removeClass('errorquantity');
	}
	
	var issue = $("#issue_no").val()
	if(issue=='' || issue==0)
	{
	e.preventDefault();
	$("#issue_error").html('<?php echo ISSUE_NUMBER_REQUIRED ;?>');
	$("#issue_no").addClass('errorquantity');
	} else {
		$("#issue_error").html('');
	$("#issue_no").removeClass('errorquantity');
	}
	
	var parttype = $("#parttype1").val()
	if(parttype=='' || parttype==0)
	{
	e.preventDefault();
	$("#parttype1msg").html('<?php echo PART_TYPE_REQUIRED ;?>');
	$("#parttype1").addClass('errorquantity');
	} else {
		$("#parttype1msg").html('');
	$("#parttype1").removeClass('errorquantity');
	}
	
	var partnum = $("#partnum1").val()
	if(partnum =='' || partnum ==0)
	{
	e.preventDefault();
	$("#partnum1msg").html('<?php echo PART_NUMBER_REQUIRED ;?>');
	$("#partnum1").addClass('errorquantity');
	} else {
		$("#partnum1msg").html('');
	$("#partnum1").removeClass('errorquantity');
	}
	
	
	var descr = $("#descr1").val()
	if(descr=='' || descr==0)
	{
	e.preventDefault();
	$("#descr1msg").html('<?php echo DESCRIPTION_REQUIRED ;?>');
	$("#descr1").addClass('errorquantity');
	} else {
		$("#descr1msg").html('');
	$("#descr1").removeClass('errorquantity');
	}
	

if(tmp=='Order') {
	if($("#customer_po").val()=='') {
		e.preventDefault();
		$("#po_error").html('<?php echo ENTER_PO_NUMBER ;?>');
	}
}
if(tmp=='Quote') {
	if($("#customer_po").val()!='') {
		e.preventDefault();
		$("#po_error").html('PO No is only for orders');
	}
	
	
	
}
	if($("#parttype1").val()!='' || $("#parttype1").val()!=0) {
	    if($("#qempty1").val()=='' || $("#qempty1").val()==0 || $("#qempty1").val()<0) { 
		$("#qempty1").addClass('errorquantity');
		$("#qempty1").val('');
		$("#qemptymsg1").html('value must be greater or 1 ');
		
		$("#qempty1").focus();
		e.preventDefault();
		} else
		{
			$("#qempty1").removeClass('errorquantity');
			$("#qemptymsg1").html('');
			
		}
     }
	 if($("#parttype2").val()!='' || $("#parttype2").val()!=0) {
	    if($("#qempty2").val()=='' || $("#qempty2").val()==0 || $("#qempty2").val()<0) { 
		$("#qempty2").addClass('errorquantity');
		$("#qempty2").val('');
		$("#qemptymsg2").html('value must be greater or 1 ')
		$("#qempty2").focus();
		e.preventDefault();
		} else
		{
			$("#qempty2").removeClass('errorquantity');
			$("#qemptymsg2").html('');
			
		}
     }
	  if($("#parttype3").val()!='' || $("#parttype3").val()!=0) {
	    if($("#qempty3").val()=='' || $("#qempty3").val()==0 || $("#qempty3").val()<0) { 
		$("#qempty3").addClass('errorquantity');
		$("#qempty3").val('');
		$("#qemptymsg3").html('value must be greater or 1 ')
		e.preventDefault();
		} else
		{
			$("#qempty3").removeClass('errorquantity');
			$("#qemptymsg3").html('');
		}
     }
	  if($("#parttype4").val()!='' || $("#parttype4").val()!=0) {
	    if($("#qempty4").val()=='' || $("#qempty4").val()==0 || $("#qempty4").val()<0) { 
		$("#qempty4").addClass('errorquantity');
		$("#qempty4").val('');
		$("#qemptymsg4").html('value must be greater or 1 ')
		e.preventDefault();
		} else
		{
			$("#qempty4").removeClass('errorquantity');
			$("#qemptymsg4").html('');
		}
     }
	 if($("#parttype5").val()!='' || $("#parttype5").val()!=0) {
	    if($("#qempty5").val()=='' || $("#qempty5").val()==0 || $("#qempty5").val()<0) { 
		$("#qempty5").addClass('errorquantity');
		$("#qempty5").val('');
		$("#qemptymsg5").html('value must be greater or 1 ')
		e.preventDefault();
		} else
		{
			$("#qempty5").removeClass('errorquantity');
			$("#qemptymsg5").html('');
		}
     }
	 if($("#parttype6").val()!='' || $("#parttype6").val()!=0) {
	    if($("#qempty6").val()=='' || $("#qempty6").val()==0 || $("#qempty6").val()<0) { 
		$("#qempty6").addClass('errorquantity');
		$("#qempty6").val('');
		$("#qemptymsg6").html('value must be greater or 1 ')
		e.preventDefault();
		} else
		{
			$("#qempty6").removeClass('errorquantity');
			$("#qemptymsg6").html('');
		}
     }
	/* var p1 = $("#price1").val();
	 var p2 = $("#price1").val();
	 var p3 = $("#price3").val();
	 var p4 = $("#price4").val();
	 var p5 = $("#price5").val();
	 var tot = p1+p2+p3+p4+p5;
	 if(parseInt(tot)<=0){
	e.preventDefault();	
	 $("#Order").hide(); 
	 $("#Orderdisabled").show();  
	 }*/
	 
	 
	 
});
});
</script>


<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>