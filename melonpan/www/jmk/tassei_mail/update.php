<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者達成メール設定
'******************************************************/

$title_text = $TT_tassei;
$title_color = $TC_OTHER;

switch ($next_action) {
case 'new':
	$sql = sprintf("insert into m_tassei_mail (tm_subject,tm_from,tm_header,tm_body,tm_footer,tm_num,tm_status) values (%s,%s,%s,%s,%s,%s,'1')",
				sql_char($tm_subject),
				sql_char($tm_from),
				sql_char($tm_header),
				sql_char($tm_body),
				sql_char($tm_footer),
				sql_number($tm_num));
				db_exec($sql);
	$msg = "登録しました。";
	break;
case 'update':
	$sql = sprintf("update m_tassei_mail set tm_subject=%s,tm_from=%s,tm_header=%s,tm_body=%s,tm_footer=%s,tm_num=%s,tm_status=%s,tm_regist_date='now' where tm_seq_no=$seq_no",
				sql_char($tm_subject),
				sql_char($tm_from),
				sql_char($tm_header),
				sql_char($tm_body),
				sql_char($tm_footer),
				sql_number($tm_num),
				sql_char($tm_status));
				db_exec($sql);
	$msg = "更新しました。";
	break;
case 'delete':
	$sql = "delete from m_tassei_mail where tm_seq_no=".$seq_no;
	db_exec($sql);
	$msg = "削除しました。";
	break;
default:
	redirect('index.php');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1">

<?= $msg ?><br><br>
<input type="button" value=" 戻る " onclick="location.href='index.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
