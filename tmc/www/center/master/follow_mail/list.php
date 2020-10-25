<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール一覧表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('マスタメンテナンス｜初期フォローメール') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■初期フォローメール一覧</td>
		<td class="lb">
			<input type="button" value="配信日設定" onclick="location.href='schedule.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(0,'VOL');
sort_header(0,'件名');
sort_header(0,'挨拶文');
sort_header(0,'メーカーこだわり情報');
sort_header(0,'ユーザーベネフィット情報');
sort_header(0,'車種情報／販売店情報');
sort_header(0,'購買動機');
?>
	</tr>
<?
for ($i = 0; $i < MAX_FOLLOW_MAIL; $i++) {
	$vol_no = $i + 1;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$vol_no?></td>
		<td align="center"><a href="subject_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>の件名を登録します。">件名-<?=$vol_no?></a></td>
		<td align="center"><a href="greeting_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>の挨拶文を登録します。">挨拶文-<?=$vol_no?></a></td>
		<td align="center"><a href="maker_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>のメーカーこだわり情報を登録します。">メーカーこだわり情報-<?=$vol_no?></a></td>
		<td align="center"><a href="benefit_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>のユーザーベネフィット情報を登録します。">ユーザーベネフィット情報-<?=$vol_no?></a></td>
		<td align="center"><a href="car_dealer_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>の車種情報／販売店情報を登録します。">車種情報／販売店情報-<?=$vol_no?></a></td>
		<td align="center"><a href="buy_list.php?vol_no=<?=$vol_no?>" title="初期フォローメールVol.<?=$vol_no?>の購買動機を登録します。">購買動機-<?=$vol_no?></a></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
