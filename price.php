<?php require('includes/application_top.php');

$categories_id = $_REQUEST['p'];
 $price_query = tep_db_query("select price_a,price_b from categories_description where categories_id = '" . (int)$categories_id . "'");
          
$price_val = tep_db_fetch_array($price_query);
	if(get_customer_group($_SESSION['customer_group_id'])=='buyer') {
		
		echo  (int)$price_a=$price_val['price_a'];
	} 
	else {
		if((int)$price_val['price_a'] > 0) {
		if((int)$price_val['price_a'] < 100) {
		echo  ($price_val['price_a'].'::'.$price_val['price_b']);	
		} else {
		echo  ($price_val['price_a'].'::'.$price_val['price_b']);	
		}
		} else {
			
		echo $price_val['price_a'].'::'.$price_val['price_b'];
		}
	}
?>