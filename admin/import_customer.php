<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_INCLUDES . 'template_top.php');
  echo "<br/>"; echo "<br/>"; echo "<br/>"; echo "<br/>"; echo "<br/>"; echo "<br/>";
  ?>

<style>
.alert-success {
    background-image: linear-gradient(to bottom, #dff0d8 0px, #c8e5bc 100%);
    background-repeat: repeat-x;
    border-color: #b2dba1;
	color:#3c763d;
}
.alert {
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.2);
	font-size:14px;
	padding:15px;

}



</style>
 <?php 
error_reporting(0);
//connect to the database
//$connect = mysql_connect("localhost","root","");
//mysql_select_db($connect,"itemnet"); //select the table
//
function get_country_id($name){
	
	$countries_id  = tep_db_fetch_array(tep_db_query("select countries_id from countries where countries_name = '".$name."'"));
	
	return $countries_id['countries_id'];
	
	}
	function get_zone_id($name){
	
	$zone_id  = tep_db_fetch_array(tep_db_query("select zone_id from zones where zone_code = '".$name."'"));
	
	return $zone_id['zone_id'];
	
	}
	function get_zone_id_name($name){
	
	$zone_id  = tep_db_fetch_array(tep_db_query("select zone_name from zones where zone_code = '".$name."'"));
	
	return $zone_id['zone_name'];
	
	}
if ($_FILES[csv][size] > 0) {

    //get the csv file
    $file = $_FILES[csv][tmp_name];
    $handle = fopen($file,"r");
	
    
    //loop through the csv file and insert into database
   // $data = fgetcsv($handle,1000,",","'");
	//print_r($data);
	//exit;
         while ($data = fgetcsv($handle,1000,",","'"))
		 {
			
			 if ($data[0]!='Customer ID')
			 // if ($data[0]!='customers_gender' && $data[1]!='customers_firstname' && $data[3]!='customers_lastname')
			  {
				 $cust_id=$data[0];
			
				 $import= explode('""',$data[0]);
				  $query=tep_db_query("SELECT customers_id FROM customers WHERE customers_id='".$cust_id."'");
				  $row=tep_db_fetch_array($query);
				 
				 if($row['customers_id']!='' || $row['customers_id']==$data[0]){
					  $zone_name=$data[10];
					   $name_zone=str_replace('"', '', $zone_name);
					   $zone_id=get_zone_id($name_zone);
					   
					   $country_name=$data[9];
					   $name_country=str_replace('"', '', $country_name);
					  $country_id=get_country_id($name_country);
					
					
					 tep_db_query("update  customers set customers_firstname ='$data[1]', customers_lastname='$data[2]', customers_email_address='$data[3]', customers_telephone='$data[4]'  where customers_id = '" . (int)$row['customers_id'] . "'");
					  tep_db_query("update  address_book set entry_street_address ='$data[5]', entry_postcode='$data[6]', entry_city='$data[7]', entry_state='$data[8]' , entry_country_id='$country_id' , entry_zone_id='$zone_id'  where customers_id = '" . (int)$row['customers_id'] . "'");
					 
					 }else{
				
				//echo "INSERT INTO customers (customers_firstname, customers_lastname, customers_email_address, customers_telephone) VALUES($data[1],$data[2],$data[3],$data[4])<br>";
				
				//echo "INSERT INTO address_book (customers_id, entry_street_address, entry_postcode, entry_city,entry_state, 	entry_country_id,entry_zone_id) VALUES($data[0],$data[5],$data[6],$data[7],$data[8],$country_id,$zone_id)";
				 tep_db_query("INSERT INTO customers (customers_firstname, customers_lastname, customers_email_address,customer_alternate_email, customers_username,
				customers_password, customers_telephone, customer_group_id) VALUES
                ('".addslashes($data[0])."','".addslashes($data[1])."','".addslashes($data[2])."','".addslashes($data[3])."','".addslashes($data[4])."','".md5($data[5])."','".addslashes($data[11])."','4')");
				$clast_id = tep_db_insert_id();
			tep_db_query("INSERT INTO address_book (customers_id, entry_street_address, entry_postcode, entry_city,entry_state, entry_country_id,entry_zone_id) 
			VALUES(".$clast_id.",'".addslashes($data[6])."','".addslashes($data[7])."','".addslashes($data[8])."','".get_zone_id_name($data[9])."',38,'".get_zone_id($data[9])."')");
	 
 $address_book_id = tep_db_insert_id();
tep_db_query("update customers set customers_gender='m', customers_default_address_id =".$address_book_id." where customers_id = '" .(int)$clast_id. "'");
						//exit;
				
					 }
				 }
	
			 
        
    }
    //

    //redirect
    header('Location: import_customer.php?success=1'); die;

}

?>

<?php if (!empty($_GET[success])) { echo '<div class="alert alert-success" role="alert">Your file has been imported.</b><br><br></div><br><br>'; } //generic success notice ?>
<div  style="margin-left:80px !important;">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your file: <br />
  <input name="csv" type="file" id="csv" />
  <input type="submit" name="Submit" value="Submit" />
</form>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>