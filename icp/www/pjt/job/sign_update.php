<?
/******************************************************
' System :ICPプロジェクトページ
' Content:署名設定ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// 会議室ヘッダ･フッタ取得
$sql = "SELECT jb_room_header,jb_room_header_web,jb_room_footer,jb_room_footer_web FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
function textarea_onoff() {
	var f = document.form1;
	document.all.header_disp.style.display = f.head[0].checked ? "" : "none";
	document.all.footer_disp.style.display = f.foot[0].checked ? "" : "none";
}
//-->
	</script>
</head>

<body onload="textarea_onoff()">
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="このジョブの詳細を表示します"><?=htmlspecialchars($job_name_cd)?>情報</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		署名設定更新
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<form method="post" name="form1" action="sign_update_comp.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td>■署名設定更新</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ヘッダ</th>
			<td width="560">
				<input type="radio" name="head" <?=value_checked('1', $fetch->jb_room_header != '' ? '1' : '2')?> id="head1" onclick="textarea_onoff()"><label for="head1">付ける</label>
				<input type="radio" name="head" <?=value_checked('2', $fetch->jb_room_header != '' ? '1' : '2')?> id="head2" onclick="textarea_onoff()"><label for="head2">付けない</label><br>
				<div id="header_disp">
				<textarea name="header" cols="60" rows="5" class="form"><?=htmlspecialchars($fetch->jb_room_header)?></textarea><br>
				<input type="checkbox" name="room_header_web" <?=value_checked(DBTRUE, $fetch->jb_room_header_web)?> id="head3"><label for="head3">Webで表示するときにも付ける</label>
				</div>
			</td>
		</tr>
		<tr>
			<th>フッタ</th>
			<td>
				<input type="radio" name="foot" <?=value_checked('1', $fetch->jb_room_footer != '' ? '1' : '2')?> id="foot1" onclick="textarea_onoff()"><label for="foot1">付ける</label>
				<input type="radio" name="foot" <?=value_checked('2', $fetch->jb_room_footer != '' ? '1' : '2')?> id="foot2" onclick="textarea_onoff()"><label for="foot2">付けない</label><br>
				<div id="footer_disp">
				<textarea name="footer" cols="60" rows="5" class="form"><?=htmlspecialchars($fetch->jb_room_footer)?></textarea><br>
				<input type="checkbox" name="room_footer_web" <?=value_checked(DBTRUE, $fetch->jb_room_footer_web)?> id="foot3"><label for="foot3">Webで表示するときにも付ける</label>
				</div>
			</td>
		</tr>
		</table>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="　更新　" class="form">
	<input type="button" value="キャンセル" class="form" onclick="history.back()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
