<?
// �����Х��顼������
define('SYSTEM_MAIL',	'aizawa2@xxxxx-xxxx.co.jp');

if (!isset($_ENV['TG_NO_ERROR_HANDLER']))			// �����Х��顼����
  set_error_handler('my_error_handler');

// �����Х��顼����
function my_error_handler($errno, $errstr, $errfile, $errline) {
	$mail_addr = SYSTEM_MAIL;
	if (isset($_ENV['E3_ERROR_MAIL_ADDR']))
		$mail_addr = $_ENV['E3_ERROR_MAIL_ADDR'];

	if ($errno & (E_ERROR | E_WARNING | E_PARSE)) {
?>
<table width=450 border=1 cellspacing=0 cellpadding=0 align="center">
<tr>
<td bordercolor="#000000" bgcolor="#CCFFFF">
<table width=95% border=0 cellspacing=5 cellpadding=5 align="center">
<tr>
<td>
�֤������ޥ��󥱡��ȥ����ƥ�ǥ��顼��ȯ���������ޤ�����<br>
�������Ǥ򤪤������ޤ������ѿ������������ޤ���<br>
����������������ޤ��������󥱡��Ȼ�̳�ɤޤǤ�Ϣ��<br>
	���������ޤ��褦���ꤤ�������ޤ���<br>
<br>
<div align="center">
<input type="button" value="�Ĥ���" onclick="window.close()">
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
<?
		$err =
			"Town Guide System Error\n\n" .
			"Date: " . strftime('%c') . "<br>" .
			"ErrNo: $errno\n" .
			"ErrStr: $errstr\n" .
			"ErrFile: $errfile\n" .
			"ErrLine: $errline\n\n" .
			'$_GET: ' . serialize($_GET) . "<br><br>" .
			'$_POST: ' . serialize($_POST) . "<br><br>" .
			'$_SESSION: ' . serialize($_SESSION) . "<br><br>" .
			'$_SERVER: ' . serialize($_SERVER) . "<br><br>";
		error_log($err, 1, $mail_addr);
		exit;
	}
}
?>