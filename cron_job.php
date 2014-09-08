<?php

require('includes/application_top.php');

?>
<?php  
    //name report dynamically using date
   // $report_name=  date("Ymd").'_report.csv';
    $report_name=  date("Ymd-hms").'_report.csv';
   
    //outline path
    $cd='/home/itemnet/public_html/report/'.$report_name;
//    $cd='/home/itplus/public_html/report/'.$report_name;
   
    
    if(!file_exists($cd)){
//////////////////////////////////create a file pointer connected to the output stream///////////////////////////
        $output = fopen($cd, 'w');
        if($output){ 
            $orders_query_detail = tep_db_query("select o.rfq_id, s.name, m.manufacturers_name, o.serial, o.model, d.part_type, d.description, d.part_number, "
			. " d.subs, d.qty, o.notes, d.action, d.vendor, d.pt, d.price2, d.buy, d.notes1, o.tracking_number, o.date_shipped, o.expected_date, o.po_no, "
			. " o.issue_no, CONCAT_WS(' ', c.customers_lastname, c.customers_firstname) AS customer_name, c.customers_email_address, a.entry_street_address, "
			. " a.entry_street_address, a.entry_state, o.date_added "
			. " FROM rfq_order o JOIN rfq_order_detail d ON o.rfq_id =  d.rfq_id JOIN customers c ON o.customer_id = c.customers_id JOIN "
			. " address_book a ON c.customers_id = a.customers_id JOIN manufacturers e ON o.manufacturer = e.manufacturers_id "
			. " JOIN manufacturers m ON o.manufacturer = m.manufacturers_id JOIN rfq_order_status s ON o.status = s.id");
					
//                        $orders_query_detail = tep_db_query("select o.rfq_id, s.name, m.manufacturers_name, o.serial, o.model, d.part_type, d.description, d.part_number, "
//                        . " d.subs, d.qty, o.notes, d.action, d.vendor, d.pt, d.price2, d.buy, d.notes1, o.tracking_number, o.expected_date, o.po_no, "
//                        . " o.issue_no, CONCAT_WS(' ', c.customers_lastname, c.customers_firstname) AS customer_name, c.customers_email_address, a.entry_street_address, "
//                        . " a.entry_street_address, a.entry_state, o.date_added "
//                        . " FROM rfq_order o JOIN rfq_order_detail d ON o.rfq_id =  d.rfq_id JOIN customers c ON o.customer_id = c.customers_id JOIN "
//                        . " address_book a ON c.customers_id = a.customers_id JOIN manufacturers e ON o.manufacturer = e.manufacturers_id "
//                        . " JOIN manufacturers m ON o.manufacturer = m.manufacturers_id JOIN rfq_order_status s ON o.status = s.id");
//					
            $arr= array();
            $arr [] = 'Order Number';
            $arr [] = 'Status';
            $arr [] = 'Manufacturer';
            $arr [] = 'Serial Number';
            $arr [] = 'Model';
            $arr [] = 'Part Type';
            $arr [] = 'Description';
            $arr [] = 'Part Number';
            $arr [] = 'Subs';
            $arr [] = 'Quantity';
            $arr [] = 'Customer Notes';
            $arr [] = 'Action';
            $arr [] = 'Vendor';
            $arr [] = 'PT';
            $arr [] = 'Staples Price';
            $arr [] = 'Buy Price';
            $arr [] = 'Internal Notes';
            $arr [] = 'Tracking Number';
            $arr [] = 'Ship Date';
            $arr [] = 'ETA';
            $arr [] = 'PO Number';	
            $arr [] = 'Issue Number';
            $arr [] = 'Customer Name';
            $arr [] = 'Customer Email';
            $arr [] = 'Customer Address';            
            $arr [] = 'Province';
            $arr [] = 'Order Date';
           
           ////////////////Add Headers////////////////////////// 
            fputcsv($output, $arr);
            //fputcsv($output);
		   
           while($rfq_order_detail=tep_db_fetch_array($orders_query_detail)){ 
              
                              fputcsv($output, $rfq_order_detail);                 
              }
          
          fclose($output);
          /////////////////////////////////////////////////SET VARS for cron Job////////////////////////////////////////////
          $to_name='ItemNet';
          $to_email_address=array();
          $email_subject='Hourly Staples Store Report';
          $email_text='This is an automated email of the hourly Staples Store report.';
          $from_email_name='ItemNet Parts Store';
          $from_email_address='staples@itemnet.ca';
//          $file='/home/itplus/public_html/report/'.$report_name;
          $file='/home/itemnet/public_html/report/'.$report_name;
          $filetype='csv';
          $filename=$report_name;
         
         s_mail_multiple_rep($to_name, $to_email_address=array('staples@itemnet.ca','steve@itemnet.ca','larry@itemnet.ca') , $email_subject, $email_text, $from_email_name, $from_email_address,$file,$filetype,$filename);
//          s_mail_multiple_rep($to_name, $to_email_address=array('sthomas@secureitcanada.com','murffbond@gmail.com') , $email_subject, $email_text, $from_email_name, $from_email_address,$file,$filetype,$filename);
          unlink($output);  
          //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          echo 'Success creating, saving and emailing file'.' '. "<br>";
          //Delete old files from the system  
          rid_reports();
        }
       
    }
 else if(file_exists($cd)){
    //////////////Email notification that file exist/////////////////////////////////
    //rid_reports();
    echo 'The current file your attempting to create already exist';
        
}
function rid_reports(){
   
    $dir = '/home/itemnet/public_html/report/';
//    $dir = '/home/itplus/public_html/report/';
        $one_d_past = date("Ymd")-1;
        if(is_dir($dir)){
            $exist_files = glob($dir. '*.csv');
            foreach($exist_files as $copy){
                
                if(substr($copy,strlen($dir),8)<=$one_d_past){
                    
                    $cur_file=substr($copy,strlen($dir));
                    //echo $cur_file. "<br>";
                    $file_delete= $dir.$cur_file;
                    
                    if(file_exists($file_delete)) {
                                            
                        //echo "The following file(s) were deleted".':  '.$file_delete. "<br>";
                        unlink($file_delete);
                    }
              
                }
            }
        } 
      
            
}
?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
