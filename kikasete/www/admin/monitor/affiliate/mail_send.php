<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト管理一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
?>
<html>
<head>
<title>配信メール</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body>
<?
$sql = "SELECT af_affiliate_mgr_id,afa_mail_no,afa_title,afa_content"
		. " FROM t_affiliate"
		. " JOIN t_affiliate_mail ON afa_affiliate_id=af_affiliate_id"
		. " WHERE af_affiliate_id=$affiliate_id"
		. " ORDER BY afa_mail_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
<table border="1" cellspacing="3" cellpadding="3" bordercolor="#000099">
<tr>
	<td width="25%">お問合せID</td><td width="75%"><?="{$fetch->af_affiliate_mgr_id}_{$affiliate_id}_{$fetch->afa_mail_no}"?></td>
</tr>
<tr>
	<td width="25%">subject</td><td width="75%"><?=htmlspecialchars($fetch->afa_title)?></td>
</tr>
<tr>
	<td colspan="2"><pre><?=htmlspecialchars($fetch->afa_content)?></pre></td>
</tr>
</table>
<br>
<?
}
?>
</body>
</html>
