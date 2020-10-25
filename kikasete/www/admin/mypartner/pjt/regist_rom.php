<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・閲覧者登録
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー実施ジョブ　閲覧者登録', BACK_TOP);

$sql = "SELECT ppj_room_name,ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$icp_job_id = $fetch->ppj_icp_job_id;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function add_member() {
	var f = document.form1;
	f.action = 'regist_rom.php';
	f.submit();
}
function onsubmit_form1(f) {
	var ok = false;
	for (var i = 0; i < f["name[]"].length; i++) {
		if (f["name[]"][i].value != "") {
			if (f["mail_addr[]"][i].value == "") {
				alert("メールアドレスを入力してください。");
				f["mail_addr[]"][i].focus();
				return false;
			}
			if (!f["mail_addr[]"][i].value.match(/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/)) {
				alert("メールアドレスが正しくありません。");
				f["mail_addr[]"][i].focus();
				return false;
			}
			if (f["password[]"][i].value == "") {
				alert("パスワードを入力してください。");
				f["password[]"][i].focus();
				return false;
			}
			ok = true;
		}
	}
	if (!ok) {
		alert("閲覧者を入力してください。");
		f["name[]"][0].focus();
		return false;
	}
	return confirm("閲覧者を登録します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($fetch->ppj_room_name)?></td>
	</tr>
</table>

<form method="post" name="form1" action="regist_rom_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan="3">■閲覧者登録</td>
	</tr>
	<tr>
		<td class="m1" align="center">名前</td>
		<td class="m1" align="center">メールアドレス</td>
		<td class="m1" align="center">パスワード</td>
	</tr>
<?
if (!isset($num))
	$num = 5;
for ($i = 0; $i < $num; $i++) {
	$class = 'n' . ($i + 1) % 2;
?>
	<tr>
		<td class="<?=$class?>"><input type="text" name="name[]" size="20" <?=value($name[$i])?>></td>
		<td class="<?=$class?>"><input type="text" name="mail_addr[]" size="30" <?=value($mail_addr[$i])?>></td>
		<td class="<?=$class?>"><input type="password" name="password[]" size="20" <?=value($password[$i])?>></td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan="3" align="right"><input type="button" value="　閲覧者追加　" onclick="add_member()"></td>
	</tr>
</table>
<br>
<div align="center">
	<input type="submit" value="　登録　">
	<input type="button" value="　戻る　" onclick="location.href='pjt_job.php?pjt_id=<?=$pjt_id?>'">
	<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
	<input type="hidden" name="num" <?=value($num + 1)?>>
	<input type="hidden" name="icp_job_id" <?=value($icp_job_id)?>>
</div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
