<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ��Ͽ�Ѥߥ����å�
function check_regist($addr, $marketer_id) {
	$sql = sprintf('SELECT mr_mail_addr FROM t_marketer WHERE mr_mail_addr_low=%s AND mr_status<>9 AND mr_marketer_id<>%s%s',
			sql_char(strtolower($addr)),
			$_SESSION['ss_marketer_id'],
			$marketer_id ? " AND mr_marketer_id<>$marketer_id" : '');
	$result = db_exec($sql);
	return pg_numrows($result);
}

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_assistant.php');
$reg = &$_SESSION['ss_regist'];

// �ե����फ��ǡ�������
$reg->name1 = trim($name1);
$reg->name2 = trim($name2);
$reg->name1_kana = trim(mb_convert_kana($name1_kana, 'KCV'));
$reg->name2_kana = trim(mb_convert_kana($name2_kana, 'KCV'));
$reg->birthday_y = trim(mb_convert_kana($birthday_y, 'n'));
$reg->birthday_m = trim(mb_convert_kana($birthday_m, 'n'));
$reg->birthday_d = trim(mb_convert_kana($birthday_d, 'n'));
$reg->sex = $sex;
$reg->mail_addr = strip_blank($mail_addr);
$reg->mail_addr2 = strip_blank($mail_addr2);
$reg->password = trim($password);
$reg->password2 = trim($password2);
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;
$reg->permission = get_multi_flag($pmr, PMR_LEN);

// ���ϥ����å�
if ($reg->name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ��Ƥ���������';

if ($reg->name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������';

if ($reg->name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ��Ƥ���������';
elseif (!check_kana($reg->name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ��Ƥ���������';
elseif (!check_kana($reg->name2_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->birthday_y == '')
	$msg[] = '��ǯ������ǯ�����Ϥ��Ƥ���������';
elseif (!check_num($reg->birthday_y, 1900, date('Y')))
	$msg[] = '��ǯ������ǯ�����������Ϥ���Ƥ��ޤ���';

if ($reg->birthday_m == '')
	$msg[] = '��ǯ�����η�����Ϥ��Ƥ���������';
elseif (!check_num($reg->birthday_m, 1, 12))
	$msg[] = '��ǯ�����η���������Ϥ���Ƥ��ޤ���';

if ($reg->birthday_d == '')
	$msg[] = '��ǯ�������������Ϥ��Ƥ���������';
elseif (!check_num($reg->birthday_d, 1, 31))
	$msg[] = '��ǯ�������������������Ϥ���Ƥ��ޤ���';

if ($reg->sex == '')
	$msg[] = '���̤����򤷤Ƥ���������';

if ($reg->mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mail_addr2 == '')
	$msg[] = '�᡼�륢�ɥ쥹��ǧ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mail_addr != $reg->mail_addr2)
	$msg[] = '�᡼�륢�ɥ쥹�ȥ᡼�륢�ɥ쥹��ǧ�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($reg->mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (check_regist($reg->mail_addr, $reg->marketer_id))
	$msg[] = '���Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if ($reg->password == '')
	$msg[] = '�ѥ���ɤ����Ϥ��Ƥ���������';
elseif ($reg->password2 == '')
	$msg[] = '�ѥ���ɳ�ǧ�����Ϥ��Ƥ���������';
elseif ($reg->password != $reg->password2)
	$msg[] = '�ѥ���ɤȥѥ���ɳ�ǧ�����פ��Ƥ��ޤ���';
elseif (strlen($reg->password) < 6)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($reg->password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
?>

<? marketer_header('�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�', PG_NULL) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788">
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> </td>
<td width="12" rowspan="2"><img src="images/common/spacer.gif" width="18" height="10"></td>
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_ASSISTANT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
<td valign="top"> 

<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_07.gif" alt="MY SETTING" width="553" height="25" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_assistant/title01.gif" alt="����������Ȥ�����" width="200" height="28"> 
</td>
</tr>

<?
if (!$msg) {
?>

<form method="post" action="mkk_aregicode.php">
<input type="hidden" name="assistant" value="<?=$assistant?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr> 
<td width="30%" bgcolor="#e5eaf0">��</td>
<td width="70%" bgcolor="#FFFFFF"><?=htmlspecialchars($reg->name1)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">̾</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($reg->name2)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">����</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($reg->name1_kana)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">�ᥤ</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($reg->name2_kana)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">��ǯ����</td>
<td bgcolor="#FFFFFF"><?="$reg->birthday_y/$reg->birthday_m/$reg->birthday_d"?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">����</td>
<td bgcolor="#FFFFFF"><?=decode_sex($reg->sex)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($reg->mail_addr)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">�ѥ����</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($reg->password)?> </td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">����</td>
<td bgcolor="#FFFFFF"><?=decode_shokugyou($reg->shokugyou_cd)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">�ȼ�</td>
<td bgcolor="#FFFFFF"><?=decode_gyoushu($reg->gyoushu_cd)?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">����</td>
<td bgcolor="#FFFFFF"><?=decode_shokushu($reg->shokushu_cd)?></td>
</tr>
<tr> 
<td valign="top" bgcolor="#e5eaf0">����������Ȥθ���</td>
<td bgcolor="#FFFFFF"><? show_permission($reg->permission) ?></td>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td align="center"> 
<table width="300" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td> 
<?
if ($mode == '1') {
?>
<input name="image" type="image" src="images/mkk_assistant/bt03.gif" alt="���" width="108" height="31">
<?
} else {
?>
<input name="image2" type="image" src="images/mkk_assistant/bt04.gif" alt="�С�Ͽ" width="108" height="31">
<?
}
?>
</td>
<td><img src="images/common/spacer.gif" width="10" height="10"><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="mkk_upd_assistant.php?assistant=<?=$assistant?>&cont=1"><img src="images/mkk_assistant/bt06.gif" alt="������" width="108" height="31" border="0"></a>
</td>
</tr>
</table>
</td>
</tr>
</form>

<?
} else {
echo("<tr><td>");
	$hidden[] = "<input type='hidden' name='assistant' value='$assistant'>";
	$hidden[] = "<input type='hidden' name='cont' value='1'>";
	error_msg($msg, 'mkk_upd_assistant.php', $hidden);
echo("</tr></td>");
}
?>

<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>

</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>
