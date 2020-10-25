<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:ô���Ծ��󹹿�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����åޥ�����Ͽ', '���ô���Ծ��󹹿�', BACK_TOP);

if ($back_url == '')
	$back_url = 'list.php';

switch ($next_action) {
case 'new':
	$sql = "SELECT ts_tantousha_id FROM m_tantousha WHERE ts_tantousha_id='$ts_tantousha_id'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = sprintf("INSERT INTO m_tantousha (ts_tantousha_id,ts_client_id,ts_name1,ts_name2,ts_name1_kana,ts_name2_kana,ts_mail_addr,ts_tel_no,ts_fax_no,ts_shokushu_cd,ts_unit,ts_post,ts_password,ts_marketer_id,ts_agent_id)"
			. "VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_char(trim($ts_tantousha_id)),
			sql_number($cl_id),
			sql_char(trim($ts_name1)),
			sql_char(trim($ts_name2)),
			sql_char(trim($ts_name1_kana)),
			sql_char(trim($ts_name2_kana)),
			sql_char(trim($ts_mail_addr)),
			sql_char(trim($ts_tel_no)),
			sql_char(trim($ts_fax_no)),
			sql_number($ts_shokushu_cd),
			sql_char(trim($ts_unit)),
			sql_char(trim($ts_post)),
			sql_char(trim($ts_password)),
			sql_number($ts_marketer_id),
			sql_number($ts_agent_id));
			db_exec($sql);
		$msg = 'ô���Ծ������Ͽ���ޤ�����';
		$back = "location.href='$back_url'";
	} else {
		$msg = '���Ϥ�����ô����ID�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
	$title = "ô���Ծ��󿷵���Ͽ";
	break;
case 'update':
	if ($ts_tantousha_id != $ts_id) {
		$sql = "SELECT ts_tantousha_id FROM m_tantousha WHERE ts_tantousha_id=" . sql_char(trim($ts_tantousha_id));
		$result = db_exec($sql);
		if (pg_numrows($result) != 0) {
			$msg = '���Ϥ���ô����ID�ϴ�����Ͽ����Ƥ��ޤ���';
			$back = 'history.back()';
		}
	} else {
		$sql = "UPDATE m_tantousha SET"
			. " ts_tantousha_id=" . sql_char(trim($ts_tantousha_id))
			. ",ts_client_id=" . sql_number($cl_id)
			. ",ts_name1=" . sql_char(trim($ts_name1))
			. ",ts_name2=" . sql_char(trim($ts_name2))
			. ",ts_name1_kana=" . sql_char(trim($ts_name1_kana))
			. ",ts_name2_kana=" . sql_char(trim($ts_name2_kana))
			. ",ts_mail_addr=" . sql_char(trim($ts_mail_addr))
			. ",ts_tel_no=" . sql_char(trim($ts_tel_no))
			. ",ts_fax_no=" . sql_char(trim($ts_fax_no))
			. ",ts_shokushu_cd=" . sql_number($ts_shokushu_cd)
			. ",ts_unit=" . sql_char(trim($ts_unit))
			. ",ts_post=" . sql_char(trim($ts_post))
			. ",ts_password=" . sql_char(trim($ts_password))
			. ",ts_marketer_id=" . sql_number($ts_marketer_id)
			. ",ts_agent_id=" . sql_number($ts_agent_id)
			. " WHERE ts_tantousha_id='$ts_id'";
		db_exec($sql);
		$title = "ô������Ͽ���󹹿�";
		$msg = 'ô���Ծ���򹹿����ޤ�����';
		$back = "location.href='$back_url'";
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_tantousha WHERE ts_tantousha_id='$ts_id'";
	db_exec($sql);
	$title = "ô������Ͽ���󹹿�";
	$msg = 'ô���Ծ���������ޤ�����';
		$back = "location.href='$back_url'";
	break;
default:
	redirect($back_url);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
