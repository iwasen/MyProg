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

// ソート条件
$order_by = order_by(6, 0, 'mb_id', 'mb_name1_kana || mb_name2_kana', 'mb_mail_addr', 'mb_zip', 'mb_area_cd','mb_regist_date');

// 表示行数条件
$limit = disp_limit();

$sql = "select mb_seq_no,mb_id,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,mb_mail_addr,mb_zip,mb_area_cd,mb_address,mb_regist_date"
	. " from t_member left join t_present_list on mb_seq_no=li_mb_seq_no where li_status=3 and li_ps_no=$ps_no $order_by";
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

function open_detail(seq_no) {
	window.open("../../inc/detail.php?seq_no=" + seq_no, "_blank", "width=450,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no");
}

function open_mail() {
	window.open("<?=$top?>/etc/mail_template/edit.php?template_id=present2" , "_blank", "scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}

function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}

function onSubmit_form2(f) {
	f.delivery_date_y.value = document.form1.delivery_date_y.value;
	f.delivery_date_m.value = document.form1.delivery_date_m.value;
	f.delivery_date_d.value = document.form1.delivery_date_d.value;

	if (f.delivery_date_y.value == "") {
		alert("指定日を入力してください。");
		document.form1.delivery_date_y.focus();
		return false;
	} else {
		if (f.delivery_date_y.value < "2002" || f.delivery_date_y.value > "2010") {
			alert("指定日を確認してください。");
			document.form1.delivery_date_y.focus();
			return false;
		}
		if (!check_date(f.delivery_date_y.value, f.delivery_date_m.value, f.delivery_date_d.value)) {
			alert("指定日が正しくありません。");
			document.form1.delivery_date_y.focus();
			return(false);
		}
	}

	return confirm("指定アドレスにcsvファイルを送信します。よろしいですか？");
}

//-->
</script>
</head>
<body onload="document.form1.delivery_date_y.focus()">
<? page_header() ?>
<form method="post" name="form1">
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■手配待ち一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$nrow?>件）</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
	<tr>
		<td class="lc">
			<nobr>発送日：西暦
			<input type="text" name="delivery_date_y" size=6 maxlength=4>年
			<select name="delivery_date_m"><? select_month('', '') ?></select>月
			<select name="delivery_date_d"><? select_day('', '') ?></select>日
			</nobr>
		<span class="note"><font size=2>　※ 発送日を指定して下さい。</font></span>
		</td>
	</tr>
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
		sort_header(0, '解除');
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
		<td><a href="javascript:open_detail(<?=$fetch->mb_seq_no?>)" title="会員の登録情報を表示します"><?=htmlspecialchars($fetch->mb_id)?></a></td>
		<td align="left"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
		<td align="left"><a href="mailto:<?=$fetch->mb_mail_addr?>" title="会員にメールします"><?=$fetch->mb_mail_addr?></a></td>
		<td align="center"><?=substr($fetch->mb_zip,0,3)?>-<?=substr($fetch->mb_zip,3,4)?></td>
		<td align="left"><?=htmlspecialchars(decode_area($fetch->mb_area_cd))." ".$fetch->mb_address?></td>
		<td align="center"><?=format_date($fetch->mb_regist_date)?></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=5 checked></td>
		<td align="center"><input type="radio" name="r_check[<?=$i?>]" value=0></td>
		
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="　実行　" onclick="document.form2.next_action.value='delivery'">
<input type="button" value="ﾒｰﾙ確認"  onclick="javascript:open_mail()">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="delivery_date_y">
<input type="hidden" name="delivery_date_m">
<input type="hidden" name="delivery_date_d">
<input type="hidden" name="ps_no" value="<?=$ps_no?>">
</div>
</form>
<? page_footer() ?>
</body>
</html>
