<?
// ��SSL�ڡ���URL����
function http_url($page) {
	$url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// SSL�ڡ���URL����
function https_url($page) {
	$url = "https://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// �إå�ɽ��
function haimail_header($top) {
?>
<table border="0" cellspacing="0" cellpadding="0" width="737">
<tr>
	<td width="202"><img src="<?=$top?>/img/title_left.gif" alt="�Ϥ��᡼�롦����" width="202" height="111" border="0"></td>
	<td width="535">
		<table border="0" cellspacing="0" cellpadding="0" width="535">
		<tr bgcolor="#2f2180">
			<td><img src="<?=$top?>/img/space.gif" width="535" height="3" border="0"></td>
		</tr>
		<tr bgcolor="#2f2180">
			<form name="login" action="8/index.php" method="post">
			<td>
			<table border="0" cellspacing="0" cellpadding="0" width="535">
			<tr>
				<td width="65"><img src="<?=$top?>/img/mypage.gif" alt="MyPage" width="58" height="22" border="0"></td>
				<td width="65"><img src="<?=$top?>/img/mailaddress.gif" alt="�᡼�륢�ɥ쥹" width="65" height="12" border="0"></td>
				<td width="125"><input type="text" name="mail_adr" size="20" style="font-size:12px"></td>
				<td width="50"><img src="<?=$top?>/img/password.gif" alt="�ѥ����" width="49" height="12" border="0"></td>
				<td width="75"><input type="password" name="password" size="10" style="font-size:12px"></td>
				<td width="70"><input type="image" src="<?=$top?>/img/login_ssl.gif" alt="SSL������" width="65" height="18" border="0" onclick="document.login.action='<?=https_url("$top/8/index.php")?>'"></td>
				<td width="85"><input type="image" src="<?=$top?>/img/login.gif" alt="��SSL������" width="75" height="18" border="0" onclick="document.login.action='<?=http_url("$top/8/index.php")?>'"></td>
			</tr>
			</table>
			</td>
			</form>
		</tr>
		<tr bgcolor="#2f2180">
			<td>
			<table border="0" cellspacing="0" cellpadding="0" width="535">
			<tr>
				<td width="232"><img src="<?=$top?>/img/space.gif" width="232" height="19" border="0"></td>
				<td width="185"><a href="<?=$top?>/9/9-1.php" title="�ѥ���ɤ�˺��Ƥ��ޤä����Ϥ�����"><img src="<?=$top?>/img/password_right.gif" alt="�ѥ���ɤ�˺��Ƥ��ޤä����Ϥ�����" width="185" height="14" border="0"></a></td>
				<td width="118"><a href="<?=$top?>/#" title="SSL�ˤĤ��ƤϤ�����"><img src="<?=$top?>/img/about_ssl.gif" alt="SSL�ˤĤ��ƤϤ�����" width="118" height="14" border="0"></a></td>
			</tr>
			</table>
			</td>
		</tr>
		<tr bgcolor="#2f2180">
			<td><img src="<?=$top?>/img/head.jpg" alt="interactive communication network" width="535" height="66" border="0"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle">
		<img height="16" width="14" src="<?=$top?>/img/space.gif"><img src="<?=$top?>/img/home.gif" width="53" height="16" alt="HOME"><a href="<?=$top?>/2/index.html"><img src="<?=$top?>/img/himail_net.gif" width="130" height="16" border="0" alt="�Ϥ��᡼�롦net�äơ�"></a><a href="<?=$top?>/movie/index.html"><img src="<?=$top?>/img/tsushin2.gif" width="119" height="16" border="0" alt="�Ϥ��᡼��ǲ��̿�"></a><a href="<?=$top?>/3/index.html"><img src="<?=$top?>/img/promise.gif" width="44" height="16" border="0" alt="����«"></a><a href="<?=$top?>/4/index.html"><img src="<?=$top?>/img/policy.gif" width="119" height="16" border="0" alt="�ץ饤�Х����ݥꥷ��"></a><a href="<?=$top?>/5/index.html"><img src="<?=$top?>/img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="<?=$top?>/6/index.php"><img src="<?=$top?>/img/use_business.gif" width="108" height="16" border="0" alt="�ӥ��ͥ������Ѥ���"></a><a href="<?=$top?>/7/index.html"><img src="<?=$top?>/img/use_personal.gif" width="88" height="16" border="0" alt="�Ŀͤ����Ѥ���"></a></td>
</tr>
</table>
<?
}

// �եå�ɽ��
function haimail_footer($top) {
?>

<table width="598" border="0" cellspacing="0" MM_NOCONVERT="TRUE" cellpadding="0">
<tr>
	<td width="20"></td>
	<td colspan="2" align="center" valign="top"><font size="1">
		<hr width="100%" size="1">
		�֤Ϥ��᡼�롦net�פϳ�����ҡ����������������Ĥ��Ƥ��ޤ���<br>
		Copyright (C) 2001-2004 xx xxxxx, Inc. All rights reserved. <br><br><a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"><img src="<?=$top?>/img/xxxxxxx_logo_i.gif" width="43" height="46" border="0" alt="xx xxxxx"></a>
		<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"target="top"> </a></font>
	</td>
</tr>
</table>
<?
}
?>