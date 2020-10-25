<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜似顔絵関連マスタ｜画風マスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('master_st', 'マスタ管理｜似顔絵関連マスタ', '画風マスタ', BACK_TOP);

$sql = "SELECT * FROM m_brushwork WHERE bw_brushwork_cd = $bw_cd";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("画風IDが不正", __FILE__);
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
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.bw_name.value == "") {
		alert("画風名を入力してください。");
		f.bw_name.focus();
		return false;
	}
	return confirm("画風情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("画風情報を削除します。よろしいですか？");
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
		<td class="m0" colspan=2>■登録する画風情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">画風コード<?=MUST_ITEM?></td>
		<td class="n1">
			<?=$fetch->bw_brushwork_cd?>
		</td>
	</tr>
	<tr>
		<td class="m1">画風名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="bw_name" size=60 maxlength=50 <?=value($fetch->bw_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">画風詳細</td>
		<td class="n1">
			<textarea class="kanji" name="bw_explain" cols=50 rows=5><?=$fetch->bw_explain?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="bw_cd" <?=value($bw_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
