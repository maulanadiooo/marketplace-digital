<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../web.php';
require '../lib/result.php';
include '../lib/phpmailer/src/Exception.php';
include '../lib/phpmailer/src/PHPMailer.php';
include '../lib/phpmailer/src/SMTP.php';
if (!isset($_GET['email'])) {
	error_log("No direct script access allowed!!!");
	exit("No direct script access allowed!!!");
}
else {
    $user_email = $model->db_query($db, "*", "user", "email = '".$_GET['email']."'");
    if ($user_email['count'] <> 1) {
		error_log("No User Found!, user: ".$_GET['email']);
	} else {
	   $email_pembeli = mysqli_real_escape_string($db, decrypt($_GET['email']));
	   $nama = $user_email['rows']['nama'];
	   $isi_pesan = "Pembayaran Tidak Diterima";
	   kirim_email($email_pembeli, $nama, $isi_pesan, $isi_pesan);
	   $mail = new PHPMailer(true);
    
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'mail.privateemail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'no-reply@gubukdigital.net';                     // SMTP username
        $mail->Password   = 'Dio4pesek!';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom('no-reply@gubukdigital.net', 'Gubukdigital.net');
        $mail->addAddress($email_pembeli, $nama);     // Add a recipient
        $mail->addAddress($email_pembeli);               // Name is optional
        $mail->addReplyTo('support@gubukdigital.net', 'Gubukdigital.net');
        // $mail->addCC('diomaulana25@gmail.com');
    
        
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $isi_pesan;
        $mail->Body    = $isi_pesan;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        error_log("Email Terkirim ke ".$email_pembeli." dan ".$_GET['email']);
	}
    
    
}
