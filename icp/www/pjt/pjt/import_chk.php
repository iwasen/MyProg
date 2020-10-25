<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクトインポートチェックページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/check.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/import.php");

// 入力パラメータ
$pjt_id = $_SESSION[SCD]['pjt_id'];

read_import_file($_FILES['import_file']['tmp_name'], $ok_ary, $ng_ary, $pjt_id);
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
function onsubmit_form1(f) {
	if (f.import_data.value == "") {
		alert("インポートインポートするデータがありません。");
		return false;
	}
	return confirm("メンバーをインポートします。よろしいですか？");
}
//-->
	</script>
</head>

<body>
<div align="center">

<? pjt_header('close') ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td>ホーム
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		プロジェクト情報
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		メンバーリスト
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		インポート確認
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
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td>まだインポートは完了していません。このページでインポートボタンを押すと、インポート完了となります。<br>
			修正する場合は、キャンセルボタンを押して前のページに戻り、正しいファイルをアップロードしてください。<br><br>
			</td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td>■インポート可能</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20" rowspan="2">ID</th>
			<th width="40" rowspan="2" style="font-size:10px;">組織ID</th>
			<th width="40" rowspan="2" style="font-size:10px;">メンバーID</th>
			<th width="75" rowspan="2">氏名</th>
			<th width="75" rowspan="2">ニックネーム</th>
			<th width="150" rowspan="2">メールアドレス</th>
			<th width="210" colspan="8">権限</th>
		</tr>
		<tr style="font-size:10px;">
			<th width="35">pjt管理</th>
			<th width="35">job管理</th>
			<th width="35">log DL</th>
			<th width="35">発言録DL</th>
			<th width="35">メンバーDL</th>
			<th width="35">発言集計DL</th>
		</tr>
<?
$n = count($ok_ary);
for ($i = 0; $i < $n; $i++) {
	$data = &$ok_ary[$i];
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=format_text($data['member_id'], '-')?></td>
			<td align="center"><?=$data['org_id']?></td>
			<td align="center"><?=htmlspecialchars($data['org_member_id'])?></td>
			<td><?=htmlspecialchars($data['name1'])?><?=htmlspecialchars($data['name2'])?></td>
			<td><?=htmlspecialchars($data['nickname'])?></td>
			<td><?=htmlspecialchars($data['mail_addr'])?></td>
			<td align="center"><?=check_staff_auth('プロジェクトメンバ管理', $data['staff_auth']) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('ジョブ管理', $data['staff_auth']) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言ログエクスポート', $data['staff_auth']) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言録エクスポート', $data['staff_auth']) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('参加者メンバエクスポート', $data['staff_auth']) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言集計エクスポート', $data['staff_auth']) ? '○' : '-'?></td>
		</tr>
<?
}
?>
		</table>

		<table border="0" cellspacing="0" cellpadding="2" width="760" class="contents">
		<tr>
			<td><br>■インポート不可</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20">ID</th>
			<th width="40" style="font-size:10px;">組織ID</th>
			<th width="40" style="font-size:10px;">メンバーID</th>
			<th width="100">氏名</th>
			<th width="100">ニックネーム</th>
			<th width="200">メールアドレス</th>
			<th width="260">理由</th>
		</tr>
<?
$n = count($ng_ary);
for ($i = 0; $i < $n; $i++) {
	$data = &$ng_ary[$i];
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=1?></td>
			<td align="center"><?=$data['org_id']?></td>
			<td align="center"><?=htmlspecialchars($data['org_member_id'])?></td>
			<td><?=htmlspecialchars($data['name1'])?><?=htmlspecialchars($data['name2'])?></td>
			<td><?=htmlspecialchars($data['nickname'])?></td>
			<td><?=htmlspecialchars($data['mail_addr'])?></td>
			<td class="warning">
<?
	foreach ($data['err'] as $err) {
?>
				・<?=htmlspecialchars($err)?><br>
<?
	}
?>
			</td>
<?
}
?>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<form method="post" name="form1" action="import_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="import_data" <?=value(serialize($ok_ary))?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="インポート" class="form">
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
