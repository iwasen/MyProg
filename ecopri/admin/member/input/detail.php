<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員データ入力状況一覧
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/format.php");

// 修正ボタン表示
function disp_button($eng_code, $eng) {
	if ($eng_code == $eng)
		echo "<br><input type='button' value='修正する' onclick=\"onclick_enter('" . $eng . "')\">";
}
// 表示色チェック
function check_color($num,$flag,$eng_code,$eng,$name, $i) {
	if ($eng_code == $eng) {
			echo "<input type='text' name='" . $name . "[" . $i . "]' size=6 maxlength=6 " . value($num) . ">";
	} else {
		switch ($flag) {
		case 1:
			echo "<font color='blue'>" . number_format($num) . "</font>";
			break;
		case 6:
			echo "<font color='green'>" . number_format($num) . "</font>";
			break;
		default:
			echo "<font color='red'>" . number_format($num) . "</font>";
			break;
		}
	}
}
// コミットチェック
function check_commit($flag,$auto_flag) {
	if ($flag == 1) {
		if ($auto_flag == 1)
			echo "<font color='red'>強制</font>";
		elseif ($auto_flag == 3)
			echo "<font color='blue'>自己</font>";
	}
}

//メイン処理
set_global('member', '会員情報管理', '会員データ入力状況', BACK_TOP);

$sql = "select * from t_member left join t_base_data on mb_seq_no=bd_mb_seq_no where mb_seq_no=$seq_no order by bd_month";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$fetch = pg_fetch_object($result, 0);
	$name = "$fetch->mb_name1 $fetch->mb_name2";

$use_org = "bd_{$eng}_use";
$sum_org = "bd_{$eng}_sum";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function open_edit(seq_no) {
	window.open("edit.php?seq_no=<?=$seq_no?>" , "_blank", "width=750,scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}
function open_login(seq_no) {
	window.open("login.php?seq_no=<?=$seq_no?>" , "_blank", "width=750,scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function onclick_edit(eng) {
	var f = document.form1;
	if (f.eng.value == eng) {
		f.eng.value = '';
		f.action = "detail.php";
		f.submit();
	} else {
		f.eng.value = eng;
		f.action = "detail.php";
		f.submit();
	}
}
function onclick_enter(eng) {
	var f = document.form1;
	for (var i = 0; i < <?=$nrow?>; i++) {
		if (!num_chk(f["use[" + i + "]"].value)) {
			alert("使用量は半角数値で入力してください。");
			f["use[" + i + "]"].focus();
			return false;
		}
		if (f.eng.value != 'gm') {
			if (!num_chk(f["sum[" + i + "]"].value)) {
				alert("金額は半角数値で入力してください。");
				f["sum[" + i + "]"].focus();
				return false;
			}
			if (f["use[" + i + "]"].value <= 0 && f["sum[" + i + "]"].value > 0) {
				alert("使用量を入力してください。");
				f["use[" + i + "]"].focus();
				return false;
			}
			if (f["use[" + i + "]"].value > 0 && f["sum[" + i + "]"].value <= 0) {
				alert("金額を入力してください。");
				f["sum[" + i + "]"].focus();
				return false;
			}
		}
	}
	if (confirm("入力値を修正登録します。よろしいですか？")) {
		f.action = "update.php";
		f.submit();
	}
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
		<td class="lt">■<b><?="$fetch->mb_name1 $fetch->mb_name2"?></b> 様の入力状況
		<span class="note">（※ <font color="red">赤：平均値</font>、<font color="blue">青：会員入力値</font>、<font color="green">緑：事務局による修正入力</font>）</span><?=$fetch->mb_water_month==1?'*':''?>
		</td>
		<td class="lb">
			<input type="button" value="ﾛｸﾞｲﾝ情報" onclick="javascript:open_login(<?=$seq_no?>)">
			<input type="button" value="　変更情報　" onclick="javascript:open_edit(<?=$seq_no?>)">
			<input type="button" value="　戻る　" onclick="location.href='list.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="seq_no" <?=value($seq_no)?>>
<input type="hidden" name="eng" <?=value($eng)?>>

<table <?=LIST_TABLE?> width="100%">
	<tr bgcolor="#FFDAB9">
		<td align="center" rowspan=2>年月</td>
		<td align="center" colspan=2><a href='Javascript:onClick=onclick_edit("el")'>電気</a><?=disp_button('el', $eng)?></td>
		<td align="center" colspan=2><a href='Javascript:onClick=onclick_edit("gs")'>ガス</a><?=disp_button('gs', $eng)?></td>
		<td align="center" colspan=2><a href='Javascript:onClick=onclick_edit("wt")'>水道</a><?=disp_button('wt', $eng)?></td>
		<td align="center"><a href='Javascript:onClick=onclick_edit("gm")'>ゴミ</a><?=disp_button('gm', $eng)?></td>
		<td align="center" colspan=2><a href='Javascript:onClick=onclick_edit("gl")'>ガソリン</a><?=disp_button('gl', $eng)?></td>
		<td align="center" colspan=2><a href='Javascript:onClick=onclick_edit("ol")'>灯油</a><?=disp_button('ol', $eng)?></td>
		<td align="center" rowspan=2>完了</td>
	</tr>
	<tr bgcolor="#FFE4E1">
		<td align="center">使用料</td>
		<td align="center">請求額</td>
		<td align="center">使用料</td>
		<td align="center">請求額</td>
		<td align="center">使用料</td>
		<td align="center">請求額</td>
		<td align="center">使用料</td>
		<td align="center">使用料</td>
		<td align="center">請求額</td>
		<td align="center">使用料</td>
		<td align="center">請求額</td>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=get_datepart('Y',$fetch->bd_month)."/".sprintf("%02d",get_datepart('M',$fetch->bd_month))?></td>
		<td align="right"><?=check_color($fetch->bd_el_use,$fetch->bd_el_use_flag,'el',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_el_sum,$fetch->bd_el_sum_flag,'el',$eng,'sum', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_gs_use,$fetch->bd_gs_use_flag,'gs',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_gs_sum,$fetch->bd_gs_sum_flag,'gs',$eng,'sum', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_wt_use,$fetch->bd_wt_use_flag,'wt',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_wt_sum,$fetch->bd_wt_sum_flag,'wt',$eng,'sum', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_gm_use,$fetch->bd_gm_use_flag,'gm',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_gl_use,$fetch->bd_gl_use_flag,'gl',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_gl_sum,$fetch->bd_gl_sum_flag,'gl',$eng,'sum', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_ol_use,$fetch->bd_ol_use_flag,'ol',$eng,'use', $i)?></td>
		<td align="right"><?=check_color($fetch->bd_ol_sum,$fetch->bd_ol_sum_flag,'ol',$eng,'sum', $i)?></td>
		<td align="center"><?=check_commit($fetch->bd_commit_flag,$fetch->bd_auto_commit)?></td>
		<input type="hidden" name="ym[<?=$i?>]" <?=value(get_datepart('Y',$fetch->bd_month)."-".sprintf("%02d",get_datepart('M',$fetch->bd_month))."-01")?>>
		<input type="hidden" name="use_org[<?=$i?>]" <?=value($fetch->$use_org)?>>
		<input type="hidden" name="sum_org[<?=$i?>]" <?=value($fetch->$sum_org)?>>
	</tr>
<?
}
?>
</table>
</div>
</form>

<? page_footer() ?>
</body>
</html>
