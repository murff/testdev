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

require('includes/classes/http_client.php');

if (isset($HTTP_GET_VARS['id'])) {
    $edit_id = $HTTP_GET_VARS['id'];
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EDIT_ORDER);

// if the customer is not logged on, redirect them to the login page

if (!tep_session_is_registered('customer_id')) {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (get_customer_type($_SESSION['customer_group_id']) == 'storeusers') {

    $navigation->set_snapshot();

    tep_redirect(tep_href_link('allordersuser.php', '', 'SSL'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
//if ($cart->count_contents() < 1) {
//  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
// }


require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);
// dont show left and right block

$dontshowleftright = 1;



require(DIR_WS_INCLUDES . 'template_top.php');

$request_type = array(array('id' => 'order', 'text' => 'Order'),
    array('id' => 'quote', 'text' => 'Quote'),
);

if ($_SESSION['language'] == 'english') {
    $cat_query = tep_db_query("select a.categories_id, b.categories_name,b.price_a from " . TABLE_CATEGORIES . " a ," . TABLE_CATEGORIES_DESCRIPTION . " b where a.categories_id=b.categories_id and a.parent_id=0 and b.language_id=1 order by b.categories_name");
}


if ($_SESSION['language'] == 'french') {
    $cat_query = tep_db_query("select a.categories_id, b.categories_name,b.price_a from " . TABLE_CATEGORIES . " a ," . TABLE_CATEGORIES_DESCRIPTION . " b where a.categories_id=b.categories_id and a.parent_id=0 and b.language_id=2 order by b.categories_name");
}

//echo "select a.categories_id, b.categories_name from " . TABLE_CATEGORIES . " a ," .TABLE_CATEGORIES_DESCRIPTION. " b where a.categories_id=b.categories_id and a.parent_id=0 order by b.categories_name";	    

$cat_arrayt = array();



$cat_arrayt[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
while ($cat = tep_db_fetch_array($cat_query)) {

    $categories_name = $cat['categories_name'];
    $categories_id = $cat['categories_id'];

    if ($cat['categories_id'] == '19')
        $cat['categories_id'] = 35;

    if ($cat['categories_id'] == '40')
        $cat['categories_id'] = 65;

    if ($cat['categories_id'] == '49')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '98')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '58')
        $cat['categories_id'] = 30;

    if ($cat['categories_id'] == '148')
        $cat['categories_id'] = 110;

    if ($cat['categories_id'] == '37')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '1')
        $cat['categories_id'] = 25;

    if ($cat['categories_id'] == '4')
        $cat['categories_id'] = 25;

    if ($cat['categories_id'] == '61')
        $cat['categories_id'] = 10;

    if ($cat['categories_id'] == '43')
        $cat['categories_id'] = 80;

    if ($cat['categories_id'] == '31')
        $cat['categories_id'] = 30;

    if ($cat['categories_id'] == '46')
        $cat['categories_id'] = 85;

    if ($cat['categories_id'] == '16')
        $cat['categories_id'] = 35;

    if ($cat['categories_id'] == '10')
        $cat['categories_id'] = 27;

    if ($cat['categories_id'] == '114')
        $cat['categories_id'] = 350;

    if ($cat['categories_id'] == '52')
        $cat['categories_id'] = 135;

    if ($cat['categories_id'] == '125')
        $cat['categories_id'] = 27;

    if ($cat['categories_id'] == '55')
        $cat['categories_id'] = 250;

    if ($cat['categories_id'] == '22')
        $cat['categories_id'] = 35;

    if ($cat['categories_id'] == '25')
        $cat['categories_id'] = 45;

    if ($cat['categories_id'] == '7')
        $cat['categories_id'] = 25;

    if ($cat['categories_id'] == '109')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '13')
        $cat['categories_id'] = 30;

    if ($cat['categories_id'] == '34')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '28')
        $cat['categories_id'] = 0;

    if ($cat['categories_id'] == '223')
        $cat['categories_id'] = 0;

    $cat_arrayt[] = array('id' => $categories_id,
        'text' => $categories_name);
}

//print_r($cat_arrayt);   
//exit;			    
?>
<h1><?php echo PLACE_REQUEST_TITLE; ?></h1>
<div style="clear: both;"></div>
<?php if (get_customer_type($_SESSION['customer_group_id']) != 'admin') { ?>
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
<?php }echo tep_draw_form('neworder', tep_href_link('edit_order_process.php', 'action=update', 'SSL'), 'post', '', true) . tep_draw_hidden_field('id', $edit_id); ?>

<script type="text/javascript">

    function updateTextField(s, t)

    {



        $.ajax({
            url: 'price.php?p=' + s,
            success: function(data) {
                //alert(data);
                var res = data.split("::");
                //return;

                $("#price" + t).val(res[1]);
                $("#store" + t).val(res[0]);
                if (res[1] == 0 || res[1] == '0.00') {

                    $("#Order").hide();
                    $("#Orderdisabled").show();


                } else {
                    $("#Order").show();
                    $("#Orderdisabled").hide();

                }



            }
        });
    }
    function get_newprice(valu, id)
    {
        var validatePrice = function(valu) {
            return /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(valu);
        }
        //alert(); // False
        if (validatePrice(valu) == false)
        {
            $("#store" + id).addClass('errorquantity');
            $("#store" + id).val('');
            $("#pricemsg" + id).html('value must be greater or 1 ')
            e.preventDefault();

        } else {
            $("#store" + id).removeClass('errorquantity');
            $("#pricemsg" + id).html('');
            if (parseInt(valu) > 0){
                //alert(valu);
                if (parseInt(valu) <= 99) {
                    var prix = (valu * 1.3) + 12;
                } else if (parseInt(valu) > 99) {
                    var prix = (valu * 1.3);
                }
                //alert(prix);
                $("#price" + id).val(prix);
            } else if (parseInt(valu) == 0 || parseInt(valu) == '0.00'){
            var elements = document.getElementsByTagName("input")
            for (var i = 0; i < elements.length; i++) {
                if(elements[i].value != "")
                    alert(element[i]);
                //Do something
            }
                document.getElementById('update').disabled=true; 
                alert(valu);               
                
            }
        }
    }


</script>





<div class="contentText">


    <?php
    $customers_query = tep_db_query("select * from rfq_order where rfq_id = '" . (int) $edit_id . "'");
    $customers = tep_db_fetch_array($customers_query);
    
    ?>
    <table border="0" width="100%" cellspacing="1" cellpadding="2">

        <tr>

            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="4">

                    <tr>
                        <td><b><?php echo MANUFACTURE_TITLE; ?></b></td>
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



                            $content = tep_draw_pull_down_menu('manufacturers_id', $manufacturers_arrayt, $customers['manufacturer'], 'required="required"');





                            echo $content;
                            ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php if (get_customer_type($_SESSION['customer_group_id']) == 'admin') { ?>

                        <tr>

                            <td><b><?php echo CUSTOMERS; ?> </b></td><td>	<?php
                                $customers_query = tep_db_query("select customers_id, customers_lastname, customers_firstname from " . TABLE_CUSTOMERS . " order by customers_firstname");
                                $customers_arrayt = array();
                                while ($customers = tep_db_fetch_array($customers_query)) {


                                    $customers_arrayt[] = array('id' => $customers['customers_id'],
                                        'text' => $customers['customers_lastname'] . ' ' . $customers['customers_firstname']);
                                }



                                $content = tep_draw_pull_down_menu('customersid', $customers_arrayt, (isset($_POST['customers_id']) ? $_POST['customers_id'] : ''), '');





                                echo $content;
                                ?></td>
                            <td></td><td></td>

                        </tr>
                    <?php } ?>


                    <tr>

                        <td><b><?php echo SERIAL_TITLE; ?>  : 
                                <input type="hidden" name="request_type" id="request_type" value="" />
                            </b></td><td>	<?php echo tep_draw_input_field('serial_no', $customers['serial']); ?></td>
                        <td><b><?php echo MODEL_TITLE; ?>  : </b></td><td>	<?php echo tep_draw_input_field('model_no', $customers['model']); ?></td>

                    </tr>
                </table></td>
            <?php $customers_order_detail = tep_db_query("select * from rfq_order_detail where rfq_id = '" . $customers['rfq_id'] . "'"); ?>
        </tr>

        <tr>

            <td>

                <table border="0" width="100%" cellspacing="5" cellpadding="2">


                    <?php while ($order_detail = tep_db_fetch_array($customers_order_detail)) { ?>

                        <tr>

                            <th><?php echo QUANTITY_TITLE; ?></th>
                            <th><?php echo PART_TYPE_TITLE; ?></th>
                            <th><?php echo DESCRIPTION_TITLE; ?></th>
                            <th><?php echo PART_NUMBER_TITLE; ?></th>
                            <th ><?php echo STAPLES_PRICE; ?></th>
                            <th><?php echo CUSTOMER_PRICE; ?></th>
                        </tr>
                        <tr>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('cart_quantity[' . $order_detail['rfq_od_id'] . ']', $order_detail['qty'], 'size="2" required="required"'); ?></td>

                            <td valign="top" align="center"><?php
                                echo tep_draw_pull_down_menu('parttype[' . $order_detail['rfq_od_id'] . ']', $cat_arrayt, $order_detail['part_type_id'], ' id="parttype1" onchange="updateTextField(this.value,' . $order_detail['rfq_od_id'] . ')" required="required"');
                                ?></td>
                            <td valign="top" align="center" id="saeed"><?php echo tep_draw_input_field('desc[' . $order_detail['rfq_od_id'] . ']', $order_detail['description'], 'size="12"'); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('partnum[' . $order_detail['rfq_od_id'] . ']', $order_detail['part_number'], 'size="8" required="required" '); ?></td>
                            <td valign="top" align="center"><?php
                                $sprice = (int) $order_detail['price'];
                                if ($sprice > 0 && $sprice <= 99) {
                                    $stprice = number_format(($order_detail["price"] - 12) / 1.3);
                                } else if ($sprice > 99) {
                                    $stprice = number_format(($order_detail["price"] / 1.3), '2', '.', ',');
                                } else {

                                    $stprice = number_format($order_detail["price"], '2', '.', ',');
                                }
                                echo tep_draw_input_field('pricest[' . $order_detail['rfq_od_id'] . ']', $order_detail['price2'], ' size="4" required="required" min="1" id="store' . $order_detail['rfq_od_id'] . '" onchange="get_newprice(this.value,' . $order_detail['rfq_od_id'] . ')"');
                                ?>
                                <br /><span style="color:#F00;font-weight:bold;" id = "pricemsg<?php echo $order_detail['rfq_od_id']; ?>"></span></td>

                            <td valign="top" align="center"><?php echo tep_draw_input_field('price[' . $order_detail['rfq_od_id'] . ']', $order_detail['price'], ' size="4" id="price' . $order_detail['rfq_od_id'] . '" '); ?></td>
                        </tr>
                        <tr>

                            <th><?php echo Subs; ?></th>
                            <th><?php echo Action; ?></th>
                            <th><?php echo Vendor; ?></th>
                            <th><?php echo PT; ?></th>
                            <th ><?php echo Buy; ?></th>
                            <th><?php echo Notes; ?></th>  

                        </tr>

                        <tr>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('subs[' . $order_detail['rfq_od_id'] . ']', $order_detail['subs'], 'size="8"  '); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('action[' . $order_detail['rfq_od_id'] . ']', $order_detail['action'], 'size="8"  '); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('vendor[' . $order_detail['rfq_od_id'] . ']', $order_detail['vendor'], 'size="8"  '); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('pt[' . $order_detail['rfq_od_id'] . ']', $order_detail['pt'], 'size="8"  '); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('buy[' . $order_detail['rfq_od_id'] . ']', $order_detail['buy'], 'size="8"  '); ?></td>
                            <td valign="top" align="center"><?php echo tep_draw_input_field('notes1[' . $order_detail['rfq_od_id'] . ']', $order_detail['notes1'], 'size="8"  '); ?></td>

                        </tr>


                    <?php } ?>


                </table>

            </td></tr>

        <tr><td>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><b><?php echo ISSUE_TITLE; ?>  : </b></td><td><?php echo tep_draw_input_field('issue_no', $customers['issue_no'], 'required=required'); ?></td>
                        <td><b><?php echo PO_TITLE; ?> : </b></td><td><?php echo tep_draw_input_field('customer_po', $customers['po_no']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><b><?php echo NOTES_TITLE; ?> : </b></td>
                    </tr>
                    <tr>
                       <td colspan="4">	<?php echo tep_draw_textarea_field('notes', 'soft', '70', '7', $customers['notes']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

</div>

<div style="float: right;">

    <input type="submit" name="update" id="update" value="<?php echo UPDATE_TITLE; ?>" class="myButton" />

</div></div>

</div>
</form>



<?php
        
                            echo var_dump($sprice);

require(DIR_WS_INCLUDES . 'template_bottom.php');

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>