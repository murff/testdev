<?php
/*
  $Id: create_account_process.php,v 1 2003/08/24 23:21:38 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
   
  Admin Create Accont
  (Step-By-Step Manual Order Entry Verion 1.0)
  (Customer Entry through Admin)
*/

  require('includes/application_top.php');
  require('includes/functions/password_funcs_create_account.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);


function customerGroup($id){
	
	$customer_group_id_query = tep_db_query("SELECT * FROM customers WHERE customers_id = '" . $id ."'" );
	//print_r($customer_group_id_query);
          $group_id_get = tep_db_fetch_array($customer_group_id_query);
          $group_id = $group_id_get['customer_group_id'];
		  
	$customer_group_query = tep_db_query("select customers_group_name from customers_groups where customers_group_id = '" . $group_id . "'");
          $group_value = tep_db_fetch_array($customer_group_query);
          $group_name = $group_value['customers_group_name'];	
		  return $group_name;
		
	}
	?>

<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<strong>Select the file to upload</strong><br />
<form action="import_customers.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>
</body>
<?php

$format1= 'User Name,Store No,Store Name,Address,City,Prov,Postal,Tel,Fax,Hours';
$format2 = 'Logo,Depot No,Store Name,Address,City,Email,Pats order specialist ,Prov,Postal,Tel,Fax';
$index_format1 = '4,5,6,7,1,9,10,11';
$index_format2 = '1,2,3,4,5,8,9,10';
$index_format1=array();
if($_FILES["file"]["error"] <= 0) {
	
	if ($handle = fopen($_FILES["file"]["tmp_name"], "r")) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	for($i=0;$i<count($data);$i++) {
		$data[$i] = trim($data[$i]);
	}
	if($data[1]=='Primary') {
		$index_format = explode(',',$index_format1);
		continue;
	} else if($data[1]=='Depot No') {
		$index_format = explode(',',$index_format2);
		continue;
	} else {
		echo '<br /><strong>Please check the format of the CSV file</strong>';
		break;
	}
  $gender = '';
  $firstname = tep_db_prepare_input($data[$index_format[0]]);
  $lastname = tep_db_prepare_input($data[$index_format[1]]);
  $dob = '';
  if($index_format[4]!=0)
  $email_address = tep_db_prepare_input($data[$index_format[4]]);
  $telephone = tep_db_prepare_input($data[$index_format[6]]);
  $fax = tep_db_prepare_input($data[$index_format[7]]);
  $newsletter = '';
  $password = tep_db_prepare_input($data[$index_format[0]].'-'.$data[$index_format[1]]);
  $confirmation = '';
  $street_address = tep_db_prepare_input($data[$index_format[2]]);
  $company = '';
  $suburb = '';
  $postcode = tep_db_prepare_input($data[$index_format[5]]);
  $city = tep_db_prepare_input($data[$index_format[3]]);
  $zone_id = 0;
  $state = '';
  $country = '';
  
  
  echo '<br />';
    
  /////////////////      RAMDOMIZING SCRIPT BY PATRIC VEVERKA       \\\\\\\\\\\\\\\\\\

$t1 = date("mdy"); 
srand ((float) microtime() * 10000000); 
$input = array ("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z"); 
$rand_keys = array_rand ($input, 3); 
$l1 = $input[$rand_keys[0]];
$r1 = rand(0,9); 
$l2 = $input[$rand_keys[1]];
$l3 = $input[$rand_keys[2]]; 
$r2 = rand(0,9); 

//$password = $l1.$r1.$l2.$l3.$r2; 

/////////////////    End of Randomizing Script   \\\\\\\\\\\\\\\\\\\

  $sql_data_array = array('customers_firstname' => $firstname,
                           'customers_lastname' => $lastname,
                           'customers_email_address' => $email_address,
                           'customers_telephone' => $telephone,
                           'customers_fax' => $fax,
                           'customers_newsletter' => $newsletter,
						   'customer_group_id' => $HTTP_POST_VARS['cgroup'],
                           'customers_password' => tep_encrypt_password_for_create_account($password));
                           //'customers_password' => $password,
                           //'customers_default_address_id' => 1);
						 
			//print_r($sql_data_array);
			//exit;

   if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
   if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

   tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

   $customer_id = tep_db_insert_id();

   $sql_data_array = array('customers_id' => $customer_id,
                           //change line below to suit your version
                           //'address_book_id' => 1,  //pre MS2
                           'entry_firstname' => $firstname,
                           'entry_lastname' => $lastname,
                           'entry_street_address' => $street_address,
                           'entry_postcode' => $postcode,
                           'entry_city' => $city,
                           'entry_country_id' => $country);

   if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
   if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
   if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
   if (ACCOUNT_STATE == 'true') {
     if ($zone_id > 0) {
       $sql_data_array['entry_zone_id'] = $zone_id;
       $sql_data_array['entry_state'] = '';
     } else {
       $sql_data_array['entry_zone_id'] = '0';
       $sql_data_array['entry_state'] = $state;
     }
   }

   tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

$address_id = tep_db_insert_id();

tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

   tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . tep_db_input($customer_id) . "', '0', now())");

   $customer_first_name = $firstname;
   //$customer_default_address_id = 1;
$customer_default_address_id = $address_id;
   $customer_country_id = $country;
   $customer_zone_id = $zone_id;
  /* tep_session_register('customer_id');
   tep_session_register('customer_first_name');
   tep_session_register('customer_default_address_id');
   tep_session_register('customer_country_id');
   tep_session_register('customer_zone_id');*/

    // build the message content
    $name = $firstname . " " . $lastname;

    if (ACCOUNT_GENDER == 'true') {
       if ($HTTP_POST_VARS['gender'] == 'm') {
         $email_text = EMAIL_GREET_MR;
       } else {
         $email_text = EMAIL_GREET_MS;
       }
    } else {
      $email_text = EMAIL_GREET_NONE;
    }
	//echo $customer_id;
	$cus_group=customerGroup($customer_id);
	//echo $email_address;
   // exit;
	 $email_text .= EMAIL_WELCOME .' Your User Name : '.$email_address ."\n". EMAIL_PASS_1 . $password . ' Your Group: '.$cus_group ."\n". EMAIL_PASS_2 . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    
	tep_mail($name, $email_address, EMAIL_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);    
}
}
}
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>