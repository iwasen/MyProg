<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員個人検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('member', '会員情報管理', '会員個人検索', BACK_TOP);

$sql = "update t_member set mb_id=" . sql_char($member_id)
				. ",mb_password=" . sql_char($password)
				. ",mb_mail_addr=" . sql_char($mail_addr)
				. ",mb_name1=" . sql_char($name1)
				. ",mb_name2=" . sql_char($name2)
				. ",mb_name1_kana=" . sql_char($name1_kana)
				. ",mb_name2_kana=" . sql_char($name2_kana)
				. ",mb_sex=" . sql_number($sex)
				. ",mb_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d")
				. ",mb_area_cd=" . sql_number($area_cd)
				. ",mb_address=" . sql_char($address)
				. ",mb_family_ctg=" . sql_number($family_ctg)
				. ",mb_keitai_cd=" . sql_number($keitai_cd)
				. ",mb_keitai_etc=" . sql_char($keitai_etc)
				. ",mb_sozai_cd=" . sql_number($sozai_cd)
				. ",mb_hebel_flag=" . sql_number($hebel_flag)
				. ",mb_chikunen_cd=" . sql_number($chikunen_cd)
				. ",mb_room_cd=" . sql_number($room_cd)
				. ",mb_space_cd=" . sql_number($space_cd)
				. ",mb_boiler_cd=" . sql_number($boiler_cd)
				. ",mb_boiler_etc=" . sql_char($boiler_etc)
				. ",mb_gas_kind_cd=" . sql_number($gas_kind_cd)
				. ",mb_gas_type_cd=" . sql_number($gas_type_cd)
				. ",mb_water_month=" . sql_number($water_month)
				. ",mb_gcar_num=" . sql_number($gcar_num)
				. ",mb_dcar_num=" . sql_number($dcar_num)
				. ",mb_bike_num=" . sql_number($bike_num)
				. ",mb_kind=" . sql_number($kind)
				. ",mb_ol_flag=" . sql_number($ol_flag)
				. ",mb_gm_flag=" . sql_number($gm_flag)
				. ",mb_gl_flag=" . sql_number($gl_flag)
				. " where mb_seq_no=" . sql_number($seq_no);
db_exec($sql);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">会員登録情報を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
