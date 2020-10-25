<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メールアドレス発行
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('marketer', 'マーケター管理', 'メールアドレス発行', BACK_TOP);

// 希望メールドメイン取得
$fetch = get_system_info('sy_mail_domain');
$mail_domain = $fetch->sy_mail_domain;

$sql = "SELECT mr_mail_addr,mr_name1,mr_name2 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm('選択したメールアドレスを発行します。よろしいですか？');
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■発行するメールアドレスを選択してください</td>
	</tr>
	<tr>
		<td class="m1" width="20%">マーケター名</td>
		<td class="n1"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">登録メールアドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->mr_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">希望メールアドレス</td>
		<td class="n1">
<?
$sql = "SELECT we_choice_no,we_account,mr_marketer_id"
		. " FROM w_choice_email LEFT JOIN t_marketer ON we_account||'@$mail_domain'=mr_free_email"
		. " WHERE we_member_id=$marketer_id AND we_member_kind=2"
		. " ORDER BY we_choice_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$checked = ' checked';
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($fetch->mr_marketer_id == null) {
?>
			<input type="radio" name="account" <?=value($fetch->we_account)?><?=$checked?>><?=htmlspecialchars($fetch->we_account)?>@<?=$mail_domain?><br>
<?
		$checked = '';
	} else {
?>
			<input type="radio" name="account" disabled><?=htmlspecialchars($fetch->we_account)?>@<?=$mail_domain?>　（使用済み）<br>
<?
	}
}
?>
			<input type="radio" name="account" value="@"<?=$checked?>>その他 <input type="text" name="account2" size=20 maxlength=20>@<?=$mail_domain?>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　決定　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
