<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

date_default_timezone_set('Etc/UTC');
require 'PHPMailerAutoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = $_POST;
    $emailQueja = $input['EMAIL'];
    $celular = $input['CELULAR'];
    $nombre = $input['NOMBRE'];
    $reservacion = $input['RESERVACION'];
	
	$oldName = "@name";
	$oldPhone = "@tel";
	$oldEmail = "@email";
	$oldReserv = "@reserv";
	

	$str=file_get_contents('correo.txt');
	$str=str_replace($oldName, $nombre, $str);
	$str=str_replace($oldPhone, $celular, $str);
	$str=str_replace($oldEmail, $emailQueja, $str);
	$str=str_replace($oldReserv, $reservacion, $str);

    $mail = new PHPMailer;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->isSMTP();
	$mail->SMTPDebug = 2;
    $mail->Host = 'mail.hotelfragata.mx';
    $mail->SMTPAuth = true;
    $mail->Protocol = 'mail';
    $mail->Username = 'smtp_admin@hotelfragata.mx';
    $mail->Password = 'smtpfragata';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 26;
    $mail->setFrom($emailQueja, $nombre);
    $mail->addAddress('smtp_admin@hotelfragata.mx');
    $mail->addReplyTo($emailQueja, $nombre);
    $mail->addCC('cancino12@hotmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Comentario de ' . $nombre;
    $mail->Body = $str;
	
    if (!$mail->send()) {
        $arr = array('RESPUESTA' => false);
        echo json_encode($arr);
    } else {
        $arr = array('RESPUESTA' => true);
		echo json_encode($arr);
    }	
	exit();
}
