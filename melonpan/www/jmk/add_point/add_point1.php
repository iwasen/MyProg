<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者・読者/ポイント付与
'******************************************************/

$title_text = $TT_add_point;
$title_color = $TC_MASTER;

if ($search_id2 == "hakkousha") {
	$where = " publisher_id=$search_id1";
	$kind = "2";
} elseif ($search_id2 == "dokusha") {
	$where = " reader_id=$search_id1";
	$kind = "1";
} elseif ($search_id2 == "navi") {
	$where = " melonpai_id=$search_id1";
	$kind = "3";
}
if ($search_mail_adr2 == "hakkousha") {
	$where = " mail_add_main='$search_mail_adr1'";
	$kind = "2";
} elseif ($search_mail_adr2 == "dokusha") {
	$where = " mail_addr='$search_mail_adr1'";
	$kind = "1";
} elseif ($search_mail_adr2 == "navi") {
	$where = " melonpai_mail_addr='$search_mail_adr1'";
	$kind = "3";
}
if ($regist_list == "hakkousha") {
	$where = " publisher_id=$id";
	$kind = "2";
} elseif ($regist_list == "dokusha") {
	$where = " reader_id=$id";
	$kind = "1";
} elseif ($regist_list == "navi") {
	$where = " melonpai_id=$id";
	$kind = "3";
}

if ($kind == "2") {
	$range = "('01', '03', '11')";
	$sql = "SELECT publisher_id, owner_nm_kanj, mail_add_main, pt_melonpan FROM M_PUBLISHER WHERE" . $where;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$id = $fetch->publisher_id;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
		$pt_melonpan = $fetch->pt_melonpan;
	} else {
		header ("location: ../admin/err_msg.php?id=5308");
		exit;
	}
} elseif ($kind == "1"){
	$range = "('24', '31')";
	$sql = "SELECT reader_id, mail_addr, pt_melonpan FROM M_MELONPA WHERE" . $where;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$id = $fetch->reader_id;
		$mail_addr = $fetch->mail_addr;
		$pt_melonpan = $fetch->pt_melonpan;
	} else {
		header ("location: ../admin/err_msg.php?id=5308");
		exit;
	}
} elseif ($kind == "3"){
	$range = "('51', '61')";
	$sql = "SELECT melonpai_id, melonpai_name, melonpai_mail_addr, melonpai_point FROM M_MELONPAI WHERE" . $where;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$id = $fetch->melonpai_id;
		$melonpai_name = $fetch->melonpai_name;
		$melonpai_mail_addr = $fetch->melonpai_mail_addr;
		$melonpai_point = $fetch->melonpai_point;
	} else {
		header ("location: ../admin/err_msg.php?id=5308");
		exit;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.mag_id.focus();
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnClick_history(kind) {
  var win;
  win = window.open("../melmaga_detail/point_history.php?kind=" + kind + "&id=<?= $id ?>", "history", "scrollbars=yes,resizable=yes,width=700,height=500");
  win.focus();
}
function OnClick_info(kind) {
	if (kind == "2") {
	  var win;
	  win = window.open("../melmaga_detail/publisher.php?pub_id=<?= $id ?>", "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
	  win.focus();
	}
	if (kind == "1") {
	  var win;
	  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=<?= $id ?>", "info_mymelonpan", "scrollbars=yes,resizable=yes,width=500,height=380");
	  win.focus();
	}
	if (kind == "3") {
	  var win;
	  win = window.open("../melmaga_detail/melonpai.php?melonpai_id=" + <?= $id ?>, "info_melonpai", "scrollbars=yes,resizable=yes,width=650,height=400");
	  win.focus();
	}
}

function  ShowHide( id, show ) {
	var disp = document.all(id).style.display;
	if (show == 'close')
		document.all(id).style.display = "none";
	else
	if (show == 'show')
		document.all(id).style.display = "block";
}
function OnSubmit_form1() {
	fm = document.form1;
  if (fm.add_point.value == "") {
    alert("ポイントを入力してください。");
    fm.add_point.focus();
    return false;
  }
	if (<?= $kind ?> == "3") {
		if (fm.add_point.value < <?= -$melonpai_point ?>) {
	    alert("この値を指定することはできません。");
	    fm.add_point.focus();
	    return false;
		}
	} else {
		if (fm.add_point.value < <?= -$pt_melonpan ?>) {
	    alert("この値を指定することはできません。");
	    fm.add_point.focus();
	    return false;
		}
	}
	if (fm.add_point.value < 0 && fm.invest[1].checked) {
    alert("付与ポイントがマイナスの場合は、予約指定できません。");
    fm.add_point.focus();
    return false;
	}
	if (fm.invest[1].checked) {
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
		var dt = new Date(fm.reserve_year.value, fm.reserve_month.value - 1, fm.reserve_day.value);
		if (dd >= dt) {
			alert("明日以降の日付を指定してください。");
			fm.reserve_day.focus();
			return(false);
		}
	}
	return confirm("実行します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="add_point2.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width=60%>
		<tr>
			<td>■ポイント付与設定</td>
			<td align="right">
				<input type="button" value="ポイント履歴" onclick='OnClick_history("<?= $kind ?>")'>
				<input type="button" value="登録情報" onclick='OnClick_info("<?= $kind ?>")'>
			</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=6 width=60%>
		<tr>
			<td class="m4" width="25%"><nobr>属性</nobr></td>
			<td class="n6"><? if ($kind == "2") { print "【発行者】"; } elseif ($kind == "1") { print "【読者】"; } elseif ($kind == "3") { print "【メルマガナビ】"; } ?></td>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>登録名</nobr></td>
			<td class="n6"><? if ($kind == "2") { print htmlspecialchars($owner_nm_kanj); } elseif ($kind == "3") { print htmlspecialchars($melonpai_name); } ?></td>
		</tr>
		<tr>
			<td class="m4"><nobr>メールアドレス</nobr></td>
			<td class="n6"><? if ($kind == "2") { print htmlspecialchars($mail_add_main); } elseif ($kind == "1") { print htmlspecialchars($mail_addr); } elseif ($kind == "3") { print $melonpai_mail_addr; } ?></td>
		</tr>
		<tr>
			<td class="m4"><nobr>対象メルマガID</nobr></td>
			<td class="n6"><input type="text" name="mag_id" maxlength=6 size=10><font class="note"><nobr>（任意）</nobr></font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>付与ポイント</nobr></td>
			<td class="n6"><input size=10 name="add_point" maxlength=10> ポイント</td>
		</tr>
		<tr>
			<td class="m4"><nobr>付与日時</nobr></td>
			<td class="n6">
				<input type="radio" name="invest" value="1" onclick="ShowHide('kind2', 'close')" checked>即日
				<input type="radio" name="invest" value="2" onclick="ShowHide('kind2', 'show')">予約
				<span ID="kind2" style="display:none;">
					<select name="reserve_year"><? select_year(2001, '', date("Y")); ?></select>年
					<select name="reserve_month"><? select_month('', date("m")); ?></select>月
					<select name="reserve_day"><? select_day('', date("d")); ?></select>日
					<font class="note"><nobr>　（※マイナスポイントの予約指定はできません。）</nobr></font>
				</span>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>ポイント種別</nobr></td>
			<td class="n6">
<?
$sql = "SELECT attribution_cd, attribution FROM M_POINT_ATTRIBUTION WHERE attribution_cd IN " . $range . "ORDER BY attribution_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$chk = "";
	$attribution = $fetch->attribution;
	$attribution_cd = $fetch->attribution_cd;
	if ($kind == "2" && $attribution_cd == "11") { $chk = " checked"; }
	if ($kind == "1" && $attribution_cd == "31") { $chk = " checked"; }
	if ($kind == "3" && $attribution_cd == "51") { $chk = " checked"; }
	print "<input type=\"radio\" name=\"attribution\" value=" . $attribution_cd . $chk . "><font size=-1>" . $attribution . "</font><br>" ;
}

?>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>残ポイント</nobr></td>
			<td class="n6"><? if ($kind == "3") { print number_format($melonpai_point); } else { print number_format($pt_melonpan); } ?> ポイント</td>
		</tr>
	</table><br>
	<input type="hidden" name="id" value="<?= $id ?>" >
	<input type="hidden" name="kind" value="<?= $kind ?>" >
	<input type="submit" value=" 実行 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
