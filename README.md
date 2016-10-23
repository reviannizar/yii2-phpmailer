# yii2-phpmailer

### Ekstensi kirim email dengan phpmailer dengan account gmail

Cara sederhana mengirim email dari localhost 
menggunakan PHPMailer dengan akun google (xxx@gmail.com)

[*] Turn On Less Secure Apps
1.  Masuk akun gmail
2.  Buka halaman untuk settingan Less secure apps
3.  Pada Access for less secure apps pilih Turn On.

### Cara Instalasi

1. Download phpmailer https://github.com/PHPMailer/PHPMailer

2. Download dan ekstak ke folder vendor 

~~~
[@vendor/revian/jbiru/]
~~~

3. Tambah / edit bagian bawah file config/web.php 
  
~~~
	'components'=>[
		...
		'mailer'=>[
			'class'=>'jbiru\base\Mailer',
			'traceLevel'=>YII_DEBUG ? 2 : 0,
			'transport'=>[
				 'host'=>'smtp.gmail.com',
				 'username'=>_GMAIL_,
				 'password'=>_PASWD_,
				 'port'=>587,
				 'encryption'=>'tls',
			],
		],
	],
	'params'=>[
		...
		'corporate'=>'PT. Radio Bonansa FM',
		'dataPath'=>_DATA_PATH_,
		'adminEmail'=>_GMAIL_,
		//'replayEmail'=>_RMAIL_,

~~~
3. Cara penggunaan
  
~~~
		$to=[
			'aaa@gmail.com'=>'Ahmad Reza Rahmani',
			'bbb@yahoo.co.id'=>'Ibrahim Ahmad',
		];
		//atau 
		$to=[
			'almprokdr@gmail.com',
			'almpro_kdr@yahoo.co.id'
		];
		//isi dengan directory html, jpg, dll
		$dir='{directory html email}'; 
		$msg=[
			'content'=>[@file_get_contents($dir.'contents.html'),$dir],
			'attachment'=>[
				$dir.'{attachment1}',
				$dir.'{attachment1}',
				...
			]
		];
		$subject='Subject email';
		$mail=Yii::$app->mailer->gmailSend($to, $subject, $msg);

  
~~~

