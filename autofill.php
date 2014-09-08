<?php require('includes/application_top.php');
if(isset($_GET['type'])){
    $result = tep_db_query("SELECT ".$_GET['type']." FROM rfq_order where ".$_GET['type']." LIKE '".strtoupper($_GET['name_startsWith'])."%'");    
    $data = array();
    while ($row = tep_db_fetch_array($result)) {
        array_push($data, $row[$_GET['type']]);    
    }    
    echo json_encode($data);
} ?>