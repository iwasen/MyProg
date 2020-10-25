<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員個人検索
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
//include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
//include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// 登録状況
function decode_step($code) {
	if ($code) {
		switch($code) {
		case 1:
			return "仮登録１";
			break;
		case 2:
			return "仮登録２";
			break;
		case 3:
			return "本登録";
			break;
		case 9:
			return "退会";
			break;
		default:
			return "不明";
			break;
		}
	}
}

$sql = "SELECT * FROM t_member LEFT JOIN t_point ON pt_seq_no=mb_seq_no WHERE mb_seq_no=" . sql_number($seq_no);
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('モニターＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=会員情報?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload() {
	var f = document.form1;
	if (f.family_ctg)
		show_hide('family', f.disp_family.checked);
}

function show_hide(id, show) {
	if (!document.form1.disp_family.disabled)
	document.all(id)[0].style.display = show ? "" : "none";
}

function onSubmit_form1(f) {
	if (f.member_id.value == "") {
		alert("会員IDを入力してください。");
		f.member_id.focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	return confirm("会員登録情報を更新します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="onload()">

<div align="center">
<form method="post" name="form1">
<table width=410 class="small">
	<tr>
		<td align="right" colspan=2><input type="button" value="　閉じる　" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■会員情報</td>
	</tr>
	<tr>
		<td class="m1" width=100>登録状況</td>
		<td class="n1"><?=decode_step($fetch->mb_step)?></td>
	</tr>
	<tr>
		<td class="m1">会員ID</td>
		<td class="n1"><?=$fetch->mb_id?></td>
	</tr>
	<tr>
		<td class="m1">パスワード</td>
		<td class="n1"><?=$fetch->mb_password?></td>
	</tr>
	<tr>
		<td class="m1">メールアドレス</td>
		<td class="n1"><?=$fetch->mb_mail_addr?></td>
	</tr>
<?
if ($fetch->mb_step != 1) {
?>
	<tr>
		<td class="m1">名前（漢字）</td>
		<td class="n1"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1"><?=$fetch->mb_name1_kana?>&nbsp;<?=$fetch->mb_name2_kana?></td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=decode_sex($fetch->mb_sex)?></td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1">
			西暦&nbsp;<?=get_datepart('Y', $fetch->mb_birthday)?>年
			<?=get_datepart('M', $fetch->mb_birthday)?></select>月
			<?=get_datepart('D', $fetch->mb_birthday)?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1"><?=substr($fetch->mb_zip, 0, 3)?>-<?=substr($fetch->mb_zip, 3, 4)?></td>
	</tr>
	<tr>
		<td class="m1">自宅住所</td>
		<td class="n1"><?=decode_area($fetch->mb_area_cd)?>&nbsp;<?=$fetch->mb_address?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■同居のご家族</td>
	</tr>
	<tr>
		<td class="m1">家族カテゴリ</td>
		<input type="hidden" name="family_ctg" <?=value($fetch->mb_family_ctg)?>>
		<td class="n1">
			<?=decode_family_ctg($fetch->mb_family_ctg)?>&nbsp;&nbsp;
			<input type="checkbox" name="disp_family" onclick="show_hide('family', checked)" <?=$fetch->mb_family_ctg > 1 ? '': 'disabled';?>>
			<span class="note">家族構成表示</span></td>
	</tr>
<?
if ($fetch->mb_family_ctg > 1) {
?>
	<tr id="family">
		<td class="m1">家族構成</td>
		<td class="n1">
			<table border=0 cellspacing=2 cellpadding=0 width="100%">
<?
	$sql = "select * from t_family where fm_mb_seq_no=" . sql_number($seq_no);
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
				<tr id="family">
					<td align="right" width=30><?=$i + 1?>.</td>
					<td width=100><font size=-1><?=decode_family_rel($fetch2->fm_family_rel_cd)?></font></td>
					<td><font size=-1><?=$fetch2->fm_age?> 歳</font></td>
				</tr>
<?
	}
?>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=2>■住居について</td>
	</tr>
	<tr>
		<td class="m1">住居形態</td>
		<td class="n1">
			形態：&nbsp;<?=decode_keitai($fetch->mb_keitai_cd)?><br>
			素材：&nbsp;<?=decode_sozai($fetch->mb_sozai_cd)?><br>
			へーベルハウス：&nbsp;<?=$fetch->mb_hebel_flag == 1?'はい':'いいえ'?>
		</td>
	</tr>
	<tr>
		<td class="m1">建築年次</td>
		<td class="n1"><?=decode_chikunen($fetch->mb_chikunen_cd)?>&nbsp;年</td>
	</tr>
	<tr>
		<td class="m1">住居間取り</td>
		<td class="n1"><?=decode_room($fetch->mb_room_cd)?></td>
	</tr>
	<tr>
		<td class="m1">居住面積</td>
		<td class="n1"><?=decode_space($fetch->mb_space_cd)?>&nbsp;m3</td>
	</tr>
	<tr>
		<td class="m1">給湯器タイプ</td>
		<td class="n1"><?=decode_boiler($fetch->mb_boiler_cd)?></td>
	</tr>
	<tr>
		<td class="m1">ガス種</td>
		<td class="n1"><?=decode_gas_kind($fetch->mb_gas_kind_cd)?>
<?
if ($fetch->mb_gas_kind_cd == 1) {
?>
			<font size=2>※都市ガスの方のみ→ </font>
			<!-- 都市ガス選択者のみ有効 -->
			<?=decode_gas_type($fetch->mb_gas_type_cd)?>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">水道請求月</td>
		<td class="n1"><?=$fetch->mb_water_month == 1?'奇数月':'偶数月'?></td>
	</tr>
	<tr>
		<td class="m1">車所有</td>
		<td class="n1">
			<table width=100%>
				<tr>
					<td width=70><font size=2>ガソリン車</font></td>
					<td><font size=2><?=$fetch->mb_gcar_num?>&nbsp;台</font></td>
				</tr>
				<tr>
					<td><font size=2>ディーゼル車</font></td>
					<td><font size=2><?=$fetch->mb_dcar_num?>&nbsp;台</font></td>
				</tr>
				<tr>
					<td><font size=2>バイク</font></td>
					<td><font size=2><?=$fetch->mb_bike_num?>&nbsp;台</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■入力項目情報</td>
	</tr>
	<tr>
		<td class="m1">灯油</td>
		<td class="n1"><?=decode_input($fetch->mb_ol_flag)?></td>
	</tr>
	<tr>
		<td class="m1">ゴミ</td>
		<td class="n1"><?=decode_input($fetch->mb_gm_flag)?></td>
	</tr>
	<tr>
		<td class="m1">ガソリン</td>
		<td class="n1"><?=decode_input($fetch->mb_gl_flag)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">会員種別</td>
		<td class="n1"><?=$fetch->mb_kind == 1?'関係者':'一般会員'?></select></td>
	</tr>
	<tr>
		<td class="m1">ポイント</td>
		<td class="n1"><?=$fetch->pt_point != ''?$fetch->pt_point:0?>&nbsp;ポイント</td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->mb_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->mb_modify_date)?></td>
	</tr>
<?
}
?>
</table>
<input type="button" value="　閉じる　" onclick="window.close()">
</div>
</body>
</html>
