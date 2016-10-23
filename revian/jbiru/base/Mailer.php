<?php

/**
/*!
 *  Mailer extensions
 *  Copy Right (c)2016 
 *  author	: Abu Dzunnuraini
 *  email	: almprokdr@gmail.com
 *  penggunaan
 *  use jbiru\base\Mailer

 * ---------------------------------------------------
 * Cara sederhana mengirim email dari localhost 
 * menggunakan PHPMailer dengan akun google (xxx@gmail.com)
 * [*] Turn On Less Secure Apps
 * [1] Masuk akun gmail
 * [2] Buka halaman untuk settingan Less secure apps
 * [3] Pada Access for less secure apps pilih Turn On.
 * ---------------------------------------------------
 *  
 * define('_GMAIL_','xxxxx@gmail.com');
 * define('_RMAIL_','yyyyy@gmail.com');
 * define('_PASWD_','password');
 * define('_DATA_PATH_',dirname(__DIR__).'/../../data');

 * 'components' => [
 * 		...
 * 		'mailer'=>[
 * 			'class'=>'jbiru\base\Mailer',
 * 			'traceLevel'=>YII_DEBUG ? 2 : 0,
 * 			'transport'=>[
 * 				 'host'=>'smtp.gmail.com',
 * 				 'username'=>_GMAIL_,
 * 				 'password'=>_PASWD_,
 * 				 'port'=>587,
 * 				 'encryption'=>'tls',
 * 			],
 * 		],
 
 *  'params'=>[
 * 		...
 * 		'corporate'=>'PT. xxxx',
 * 		'dataPath'=>_DATA_PATH_,
 * 		'adminEmail'=>_GMAIL_,

 * Untuk mengirim email, bisa menggunakan kode ini:
 * 		$to=[
 * 			'aaa@gmail.com'=>'Ahmad Reza Rahmani',
 * 			'bbb@yahoo.co.id'=>'Ibrahim Ahmad',
 * 		];
 * atau 
 * 		$to=[
 * 			'almprokdr@gmail.com',
 * 			'almpro_kdr@yahoo.co.id'
 * 		];
 * 		$dir='{directory html email}';
 * 		$msg=[
 * 			'content'=>[@file_get_contents($dir.'contents.html'),$dir],
 * 			'attachment'=>[
 * 				$dir.'{attachment1}',
 * 				$dir.'{attachment1}',
 * 				...
 * 			]
 * 		];
 * 		$subject='Subject email';
 * 		$mail=Yii::$app->mailer->gmailSend($to, $subject, $msg);
*/

namespace jbiru\base;

use Yii;

class Mailer{

	public function gmailSend($to=[], $subject, $msg=[]){
		$msg=(object)$msg;
		if((!isset($msg->content[0])||(empty($to)))){ 
			return ['success'=>false,'msg'=>'Tidak boleh email kosong'];
		}
		$opt=(object)$this->transport;
		$from=$opt->username; 
		$cap=isset(Yii::$app->params['corporate'])?Yii::$app->params['corporate']:'';
		require_once(dirname(__FILE__) . '/Classes/PHPMailer/PHPMailerAutoload.php');
		$mail = new \PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = isset($this->traceLevel)?$this->traceLevel:0;
		$mail->Debugoutput = 'text';
		$mail->Host = $opt->host;
		$mail->Port = $opt->port;
		$mail->SMTPSecure = $opt->encryption;
		$mail->SMTPAuth = true;
		$mail->Username = $opt->username;
		$mail->Password = $opt->password;
		$mail->setFrom($from, $cap);
		if(isset(Yii::$app->params['replayEmail'])){ 
			$mail->addReplyTo(Yii::$app->params['replayEmail'], $from->info);
		}
		foreach($to as $k=>$v){
			if(is_int($k)) $mail->addAddress($v, ''); else $mail->addAddress($k, $v);
		}
		$mail->Subject = $subject;
		if(isset($msg->content[1])){ 
			$mail->msgHTML($msg->content[0],$msg->content[1]); 
		}else{ 
			$mail->msgHTML($msg->content[0]);
		}
		if(isset($msg->altbody)) $mail->AltBody = $msg->altbody;
		if((isset($msg->attachment))&&(is_array($msg->attachment))&&(!empty($msg->attachment))){
			foreach($msg->attachment as $attachment){
				$mail->addAttachment($attachment);
			}
		}
		ob_start();	
		$send=$mail->send();
		$trace=ob_get_contents(); 
		ob_end_clean(); 
		if ($send){
			return ['success'=>true, 'trace'=>$trace];
		} else {
			return ['success'=>false, 'msg'=>$mail->ErrorInfo, 'trace'=>$trace];
		}
	}

}
