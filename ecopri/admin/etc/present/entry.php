<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:プレゼント管理リスト表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/decode.php");

// アンケート状態
function decode_enq_status($code) {
	switch ($code) {
	case 0:
		return '作成中';
	case 4:
		return '実施待ち';
	case 5:
		return '実施中';
	case 6:
		return '一時停止';
	case 7:
		return '終了';
	case 9:
		return '削除済み';
	}
	return '不明';
}

//メイン処理
set_global('etc', 'その他管理', 'プレゼント管理', BACK_TOP);

// セッション登録
//get_session_vars($pset, 'monitor_enquete', 'displine', 'sort_col', 'sort_dir', 'page');
// ソート条件
$order_by = order_by(6, 0, 'mb_id','mb_name1_kana || mb_name2_kana', 'mb_mail_addr', 'mb_zip', 'mb_area_cd', 'mb_regist_date');

$sql = "select mb_seq_no,mb_id,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,mb_mail_addr,mb_zip,mb_area_cd,mb_address,mb_regist_date"
	. " from t_member left join t_present_list on mb_seq_no=li_mb_seq_no and li_ps_no=$ps_no where mb_step=3 and li_mb_seq_no is null and mb_kind is null"
	. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onSubmit_form2(f) {
	return confirm("登録します。よろしいですか？");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■該当者一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$nrow?>名）</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='list.php'">
		</td>
	</tr>
<!--
	<tr>
		<td class="lc">
			<nobr>発送日：
			<select name="end_date_y"><? select_year(2002, '', $enquete->end_date_y) ?></select>年
			<select name="end_date_m"><? select_month('', $enquete->end_date_m) ?></select>月
			<select name="end_date_d"><? select_day('', $enquete->end_date_d) ?></select>日
			</nobr>
		<span class="note"><font size=2>　※ 発送日を指定して、該当する会員の発送欄にチェックして下さい。</font></span>
		</td>
	</tr>
-->
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, '氏名');
		sort_header(3, 'メールアドレス');
		sort_header(4, '郵便番号');
		sort_header(5, '住所');
		sort_header(6, '登録日');
		sort_header(0, '手配');
		sort_header(0, '問合せ');
		sort_header(0, '除外');
?>
	</tr>
</form>
<form method="post" name="form2" action="regist.php" onsubmit="return onSubmit_form2(this)">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<input type="hidden" name="seq_no[<?=$i?>]" <?=value($fetch->mb_seq_no)?>>
		<td><a href="#" title="会員の登録情報を表示します"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td align="left"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
		<td align="left"><a href="mailto:<?=$fetch->mb_mail_addr?>" title="会員にメールします"><?=$fetch->mb_mail_addr?></a></td>
		<td align="center"><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td align="left"><?=htmlspecialchars(decode_area($fetch->mb_area_cd))." ".$fetch->mb_address?></td>
		<td align="center"><?=format_date($fetch->mb_regist_date)?></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=3 checked></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=1></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=9></td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="　登録　" onclick="document.form2.next_action.value='regist'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="ps_no" value="<?=$ps_no?>">
</div>
</form>

<? page_footer() ?>
</body>
</html>
