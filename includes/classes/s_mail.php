<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class s_mail {
    function s_mailer( $eamil_to, $email_from,$subject,$body_txt,$file_path,$file_name) {

        $email_to = "sthomas@secureitcanada.com"; // The email you are sending to (example)
        $email_from = "root@cp1.lipowered.net"; // The email you are sending from (example)
        $email_subject = "Report"; // The Subject of the email
        $email_txt = "text body of message"; // Message that the email has in it
        $fileatt_path = "/home/itplus/public_html/report/"; // Path to the file (example)
        $fileatt_type = "application/octet-stream"; // File Type
        $fileatt_name = $report_name; // Filename that will be used for the file as the attachment
        $fileatt=$fileatt_path.$fileatt_name;
        $file = fopen($fileatt,'rb');
        $data = fread($file,filesize($fileatt));
        fclose($file);
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        $headers="From: $email_from"; // Who the email is from (example)
        $headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mime_boundary}\"";
        $email_message .= "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $email_txt;
        $email_message .= "\n\n";
        $data = chunk_split(base64_encode($data));
        $email_message .= "--{$mime_boundary}\n" .
        "Content-Type: {$fileatt_type};\n" .
        " name=\"{$fileatt_name}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data . "\n\n" .
        "--{$mime_boundary}--\n";

        mail($email_to,$email_subject,$email_message,$headers);

  }
    
}
?>


