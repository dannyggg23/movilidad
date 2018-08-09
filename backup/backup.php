<?php
// variables
$dbhost = 'localhost';
$dbname = 'movilidad';
$dbuser = 'root';
$dbpass = '';

$backup_file = $dbname. "-" .date("Y-m-d-H-i-s"). ".sql";

// comandos a ejecutar
$commands = array(
        "mysqldump --opt -h $dbhost -u $dbuser -p$dbpass -v $dbname > $backup_file",
      "bzip2 $backup_file"
);

// ejecución y salida de éxito o errores
foreach ( $commands as $command ) {
        system($command,$output);
       // echo $output;
}

 //####################
 require_once ('../vendor/phpmailer/phpmailer/src/Exception.php');
 require_once ('../vendor/phpmailer/phpmailer/src/OAuth.php');
 require_once ('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
 require_once ('../vendor/phpmailer/phpmailer/src/SMTP.php');
 require_once ('../vendor/phpmailer/phpmailer/src/POP3.php');
 $mail = new PHPMailer\PHPMailer\PHPMailer(); // the true param means it will throw exceptions on errors, which we need to catch
     
     $mail->IsSMTP(); // telling the class to use SMTP
     
     $body =utf8_decode("Backup Base de datos movilidad");
     
     try {
          //$mail->Host       = "mail.gmail.com"; // SMTP server
           $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
           $mail->SMTPAuth   = true;                  // enable SMTP authentication
           $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
           $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
          //$mail->Host       = "smtp.hotmail.com";      // sets GMAIL as the SMTP server
           $mail->Port       = 465;   // set the SMTP port for the GMAIL server
           $mail->SMTPKeepAlive = true;
           $mail->Mailer = "smtp";
           $mail->Username   = "dannyggg23@gmail.com";  // GMAIL username
           $mail->Password   = "..Danny..3Burguer";            // GMAIL password
           $mail->AddAddress("galloapatty@hotmail.com", 'abc');
           $mail->AddAddress("dannyggg23@gmail.com", 'abc');
           $mail->SetFrom('dannyggg23@gmail.com', 'Backup');
           $mail->addAttachment($backup_file);         // Add attachments
           $mail->Subject = 'Backup de la base de datos de movilidad';
           $mail->AltBody = ''; // optional - MsgHTML will create an alternate automatically
           $mail->MsgHTML($body);
           $mail->Send();
           echo "Message Sent OK</p>\n";
     } catch (phpmailerException $e) {
           echo $e->errorMessage(); //Pretty error messages from PHPMailer
     } catch (Exception $e) {
           echo $e->getMessage(); //Boring error messages from anything else!
     }
     
     ############################


?>