<?php
/**
 * 发送邮件的一个帮助类
 *
 * @author Libok.Zhou Libok.Zhou <libkhorse@gmail.com>
 * @version $Id: Mail.php 22 2012-07-20 12:30:10Z zhangbin $
 */

include_once LD_UTIL_PATH.'/phpmailer/class.phpmailer.php';
include_once LD_UTIL_PATH.'/phpmailer/class.smtp.php';
class Mail {
	static function sendMailToClient($client,$title,$body){
		$smtp = Load::conf('Smtp');
	
		$mail = new PHPMailer();           // SMTP服务器地址
		$mail->Username = $smtp['username'];      // 登录用户名
	
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 		// 设置为安全验证方式
		$mail->Host= $smtp['smtp'];  
		$mail->Password = $smtp['password'];           	// 登录密码
	
		$mail->From = $smtp['host'];		// 发件人地址(username@163.com)
		$mail->FromName = "彩云网络游戏云服务平台";	
		$mail->CharSet="utf-8";
		$mail->Encoding = "base64";

		$mail->WordWrap   = 50;
		$mail->IsHTML(true);			// 是否支持html邮件，true 或false
	       
		//$mail->AddAddress("$client");		//客户邮箱地址
		$mail->Subject = $title;
	
		$mail->Body    =  $body;
		if (!is_array($client)) $client = array($client);
		foreach($client as $i => $v){
			$mail->AddAddress($v);
		}
 
		if(!$mail->Send()){
		   return FALSE; 
		}else{
  			return TRUE; 
		}
	} 
}