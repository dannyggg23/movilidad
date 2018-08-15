<?php
//######################
$host="localhost";
$username="root";
$password="";
$database_name="movilidad";

$conn=mysqli_connect($host,$username,$password,$database_name);

$tables=array();
$sql="SHOW TABLES";
$result=mysqli_query($conn,$sql);

while($row=mysqli_fetch_row($result)){
$tables[]=$row[0];
}

$backupSQL="";
foreach($tables as $table){
$query="SHOW CREATE TABLE $table";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_row($result);
$backupSQL.="\n\n".$row[1].";\n\n";

$query="SELECT * FROM $table";
$result=mysqli_query($conn,$query);

$columnCount=mysqli_num_fields($result);

for($i=0;$i<$columnCount;$i++){
while($row=mysqli_fetch_row($result)){
$backupSQL.="INSERT INTO $table VALUES(";
for($j=0;$j<$columnCount;$j++){
$row[$j]=$row[$j];

if(isset($row[$j])){
$backupSQL.='"'.$row[$j].'"';
}else{
$backupSQL.='""';
}
if($j<($columnCount-1)){
$backupSQL.=',';
}
}
$backupSQL.=");\n";
}
}
$backupSQL.="\n";
}

if(!empty($backupSQL)){
$backup_file_name=$database_name.'_backup_'.time().'.sql';
$fileHandler=fopen($backup_file_name,'w+');
$number_of_lines=fwrite($fileHandler,$backupSQL);
fclose($fileHandler);

// header('Content-Description: File Transfer');
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename='.basename($backup_file_name));
// header('Content-Transfer-Encoding: binary');
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: '.filesize($backup_file_name));
// ob_clean();
// flush();

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
           $mail->Username   = "movilidadlatacunga@gmail.com";  // GMAIL username
           $mail->Password   = "Movilidad123";            // GMAIL password
           //$mail->AddAddress("galloapatty@hotmail.com", 'abc');
           $mail->AddAddress("movilidadlatacunga@gmail.com", 'abc');
      
           $mail->SetFrom('movilidadlatacunga@gmail.com', 'Backup');
           $mail->addAttachment($backup_file_name);         // Add attachments
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