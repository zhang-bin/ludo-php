<?php
/**
 * 发送邮件的一个帮助类
 *
 * @author Libok.Zhou Libok.Zhou <libkhorse@gmail.com>
 * @version $Id: Mail.php 22 2012-07-20 12:30:10Z zhangbin $
 */
class Mailer
{
    public static function send($client, $title, $body, $smtp, $path = array(), $name = array())
    {
		if (empty($body)) return false;

		$mail = new PHPMailer();           // SMTP服务器地址
		$mail->isSMTP();
		$mail->SMTPAuth = true; 		// 设置为安全验证方式
		$mail->Host = $smtp['host'];
		$mail->Port = $smtp['port'];
		$mail->Username = $smtp['username'];      // 登录用户名
		$mail->Password = $smtp['password'];           	// 登录密码

		$mail->From = $smtp['username'];		// 发件人地址(username@163.com)
		$mail->FromName = SITE_TITLE;
		$mail->CharSet = PROGRAM_CHARSET;
		$mail->Encoding = 'base64';

		if ($smtp['isSsl']) $mail->SMTPSecure = 'ssl';

		$mail->WordWrap   = 50;
		if(!empty($path)){
			foreach($path as $key=>$v){
				$mail->addAttachment($v,$name[$key]); // 添加附件,并指定名称
			}
		}

		$mail->isHTML(true);			// 是否支持html邮件，true 或false

		$mail->Subject = $title;

		$mail->Body    =  $body;
		if (!is_array($client)) $client = array($client);
		foreach($client as $i => $v){
			$mail->addAddress($v);
		}

		if (!$mail->send()) {
			return false;
		} else {
			return true;
		}
	}
}
