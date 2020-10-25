<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:スタッフ登録リスト 
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('nigaoe', '似顔絵情報管理', '似顔絵受付リスト', BACK_TOP);

$sql = "UPDATE t_nigaoe_data SET nd_status=" . sql_number($nd_status)
					. ",nd_random_id=" . sql_char($nd_random_id)
					. ",nd_product_cd=" . sql_char($nd_product_cd)
					. ",nd_campaign_cd=" . sql_char($nd_campaign_cd)
					. ",nd_nouki_date=" . sql_date("$nd_nouki_date_y/$nd_nouki_date_m/$nd_nouki_date_d $nd_nouki_date_h:$nd_nouki_date_mi:00")
					. ",nd_priority=" . sql_number($nd_priority)
					. ",nd_staff_id=" . sql_number($nd_staff_id)
					. ",nd_mail_addr=" . sql_char($nd_mail_addr)
					. ",nd_sex_code=" . sql_number($nd_sex_code)
					. ",nd_brushwork=" . sql_number($nd_brushwork)
					. ",nd_age=" . sql_number($nd_age)
					. ",nd_name_text=" . sql_char($nd_name_text)
					. " WHERE nd_nigaoe_id=$nd_id";
db_exec($sql);
$msg = '似顔絵情報を更新しました。';
$ret = 'location.href=\'list.php\'';
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
