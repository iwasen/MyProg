<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクト設定更新ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!$_SESSION[SCD]['operator_flag'])
	redirect("$top/index.php");

$pjt_id = $_SESSION[SCD]['pjt_id'];
$sql = "SELECT pj_name,pj_description,pj_pjt_cd FROM t_pjt WHERE pj_pjt_id=$pjt_id";
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
function on_submit_form1(f) {
	if (f.pj_name.value == "") {
		alert("プロジェクト名を入力してください。");
		f.pj_name.focus();
		return false;
	}
	return confirm("プロジェクトを更新します。よろしいですか？");
}
//-->
	</script>
</head>

<body>
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
		プロジェクト情報
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		更新
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<form method="post" name="form1" action="update_comp.php" onsubmit="return on_submit_form1(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td>■プロジェクト設定更新</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
				<tr>
					<th width="30%">プロジェクト名</th>
					<td width="70%"><input type="text" name="pj_name" <?=value($fetch->pj_name)?> size="50" class="form"></td>
				</tr>
				<tr>
					<th>プロジェクト詳細</th>
					<td><textarea name="description" cols="60" rows="5" class="form"><?=htmlspecialchars($fetch->pj_description)?></textarea></td>
				</tr>
				<tr>
					<th>プロジェクトコード</th>
					<td><?=htmlspecialchars($fetch->pj_pjt_cd)?></td>
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
