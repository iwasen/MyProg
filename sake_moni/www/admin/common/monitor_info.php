<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��˥�������ɽ��
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/lifeinfo.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �֥�å��ꥹ��ɽ��
function decode_blacklist($code) {
	if ($code != '') {
		$sql = "SELECT bl_name FROM m_blacklist WHERE bl_blacklist_flg=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ᥤ�����
set_global('monitor', '��˥�������', '��˥�������ɽ��', BACK_TOP);

$sql = "SELECT * FROM t_monitor WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('��˥����ɣĤ�����', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">�����ܾ���</td>
		<td align="right"><input type="button" value="���Ĥ��롡" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m1" width="20%">̾���ʴ�����</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mn_name1_kana $fetch->mn_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1"><?=format_date($fetch->mn_birthday)?></td>
	</tr>
	<tr>
		<td class="m1">̤����</td>
		<td class="n1"><?=decode_mikikon($fetch->mn_mikikon)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex($fetch->mn_sex)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->mn_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�ʼ����</td>
		<td class="n1"><?=format_zip($fetch->mn_jitaku_zip)?></td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�ʼ����</td>
		<td class="n1"><?=decode_area($fetch->mn_jitaku_area)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_shokugyou($fetch->mn_shokugyou_cd)?></td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><? checkbox_chain($monitor_id, 4, true) ?></td>
	</tr>
	<tr>
		<td class="m1">���ʾҲ�ʸ</td>
		<td class="n1">
			<textarea class="kanji" cols=60 rows=5 readonly><?=htmlspecialchars($fetch->mn_self_introduction)?></textarea><br>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���Ŀ;���</td>
	</tr>
	<tr>
		<td class="m1">���𽻽꣱</td>
		<td class="n1"><?=htmlspecialchars($fetch->mn_jitaku_addr1)?></td>
	</tr>
	<tr>
		<td class="m1">���𽻽ꣲ</td>
		<td class="n1"><?=htmlspecialchars($fetch->mn_jitaku_addr2)?></td>
	</tr>
	<tr>
		<td class="m1">���������ֹ�</td>
		<td class="n1"><?=htmlspecialchars($fetch->mn_jitaku_tel)?></td>
	</tr>
	<tr>
		<td class="m1">����FAX�ֹ�</td>
		<td class="n1"><?=htmlspecialchars($fetch->mn_jitaku_fax)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_monitor_status($fetch->mn_status)?></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->mn_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->mn_update_date)?></td>
	</tr>
</table>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
