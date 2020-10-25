<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ユーザ情報表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

// ユーザIDかメールアドレスを判別
if (is_numeric($_GET['user_id']))
	$where = "ups_user_id={$_GET['user_id']}";
else
	$where = "ups_mail_addr='{$_GET['user_id']}'";

// ユーザ登録情報取得
$sql = "SELECT *"
		. " FROM t_user_personal"
		. " JOIN t_user_manage ON umg_user_id=ups_user_id"
		. " JOIN t_user_car ON ucr_user_id=ups_user_id"
		. " JOIN t_user_campaign ON ucm_user_id=ups_user_id"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " LEFT JOIN t_mail_cancel_result ON mcr_cancel_cd=ust_cancel_cd"
		. " WHERE $where";

$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect("not_found.php");
if (pg_num_rows($result) >= 2)
	redirect("list.php?mail_addr={$_GET['user_id']}");
$fetch = pg_fetch_object($result, 0);
$user_id = $fetch->ups_user_id;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('ユーザ情報表示') ?>

<div align="center">
<form>
<table border=0 cellspacing=2 cellpadding=3 width="90%" style="font-size:85%">
	<tr>
		<td class="m0">■ユーザ登録情報</td>
		<td align="right">
			<input type="button" value="引継ぎ情報" onclick="location.href='result_input.php?user_id=<?=$user_id?>'">
			<input type="button" value="送受信履歴" onclick="location.href='mail_log.php?user_id=<?=$user_id?>'">
			<input type="button" value="　閉じる　" onclick="window.close()">
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="90%" style="font-size:85%">
	<tr class="subhead">
		<td colspan=2>ユーザ個人情報</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ユーザＩＤ</td>
		<td class="n1"><?=$fetch->ups_user_id?></td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kana)?></td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1"><?=$fetch->ups_zip_cd?></td>
	</tr>
	<tr>
		<td class="m1">住所0</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address0)?></td>
	</tr>
	<tr>
		<td class="m1">住所1</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address1)?></td>
	</tr>
	<tr>
		<td class="m1">住所2</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_address2)?></td>
	</tr>
	<tr>
		<td class="m1">電話番号</td>
		<td class="n1"><?=$fetch->ups_tel_no?></td>
	</tr>
	<tr>
		<td class="m1">携帯電話番号</td>
		<td class="n1"><?=$fetch->ups_keitai_no?></td>
	</tr>
	<tr>
		<td class="m1">メールアドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=decode_sex_cd($fetch->ups_sex_cd)?></td>
	</tr>
	<tr>
		<td class="m1">年齢</td>
		<td class="n1"><?=$fetch->ups_age?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>ユーザ管理情報</td>
	</tr>
	<tr>
		<td class="m1">お客様CD</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_customer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">販売店コード</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_dealer_cd)?></td>
	</tr>
	<tr>
		<td class="m1">販売店名</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_dealer_name)?></td>
	</tr>
	<tr>
		<td class="m1">応募種別</td>
		<td class="n1"><?=decode_oubo_shubetsu($fetch->umg_oubo_shubetsu)?></td>
	</tr>
	<tr>
		<td class="m1">実施回</td>
		<td class="n1"><?=$fetch->umg_jisshi_kai?></td>
	</tr>
	<tr>
		<td class="m1">購入希望車名</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_kounyu_kibou_car)?></td>
	</tr>
	<tr>
		<td class="m1">初度登録年月</td>
		<td class="n1"><?=$fetch->umg_regist_ym?></td>
	</tr>
	<tr>
		<td class="m1">応募日（来店日）</td>
		<td class="n1"><?=format_datetime($fetch->umg_oubo_date)?></td>
	</tr>
	<tr>
		<td class="m1">自社・他社区分</td>
		<td class="n1"><?=decode_jisha_tasha_kubun($fetch->umg_jisha_tasha_kubun)?></td>
	</tr>
	<tr>
		<td class="m1">フォロー区分</td>
		<td class="n1"><?=decode_follow_kubun($fetch->umg_follow_kubun)?></td>
	</tr>
	<tr>
		<td class="m1">コメント</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_comment)?></td>
	</tr>
	<tr>
		<td class="m1">担当店舗CD</td>
		<td class="n1"><?=$fetch->umg_tantou_shop_cd?></td>
	</tr>
	<tr>
		<td class="m1">担当店舗名称</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_tantou_shop_name)?></td>
	</tr>
	<tr>
		<td class="m1">担当スタッフ名</td>
		<td class="n1"><?=htmlspecialchars($fetch->umg_tantou_staff_name)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>車保有情報</td>
	</tr>
	<tr>
		<td class="m1">車両メーカー</td>
		<td class="n1"><?=decode_car_maker($fetch->ucr_car_maker)?></td>
	</tr>
	<tr>
		<td class="m1">車種名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucr_car_model)?></td>
	</tr>
	<tr>
		<td class="m1">次回車検</td>
		<td class="n1"><?=$fetch->ucr_jikai_shaken?></td>
	</tr>
	<tr>
		<td class="m1">購入販売店コード</td>
		<td class="n1"><?=$fetch->ucr_dealer_cd?></td>
	</tr>
	<tr>
		<td class="m1">購入販売店種別</td>
		<td class="n1"><?=decode_dealer_type($fetch->ucr_dealer_type)?></td>
	</tr>
	<tr>
		<td class="m1">購入販売店名</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucr_dealer_name)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>キャンペーン応募情報</td>
	</tr>
	<tr>
		<td class="m1">来店目的</td>
		<td class="n1"><?=decode_raiten_mokuteki($fetch->ucm_raiten_mokuteki)?></td>
	</tr>
	<tr>
		<td class="m1">その他理由</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucm_sonota_riyuu)?></td>
	</tr>
	<tr>
		<td class="m1">CP認知その他内容</td>
		<td class="n1"><?=htmlspecialchars($fetch->ucm_cp_ninchi)?></td>
	</tr>
	<tr>
		<td class="m1">キャンペーン認知</td>
		<td class="n1"><?=decode_campaign_ninchi($fetch->ucm_campaign_ninchi)?></td>
	</tr>
	<tr>
		<td class="m1">情報提供許諾</td>
		<td class="n1"><?=decode_jouhou_teikyou_kyodaku($fetch->ucm_jouhou_teikyou_kyodaku)?></td>
	</tr>
	<tr class="subhead">
		<td colspan=2>ステータス情報</td>
	</tr>
	<tr>
		<td class="m1">状態コード</td>
		<td class="n1"><?=decode_user_status($fetch->ust_status)?></td>
	</tr>
	<tr>
		<td class="m1">継続リレーションメール</td>
		<td class="n1"><?=$fetch->rel_stop_flag == DBTRUE ? '一時停止' : '通常配信'?></td>
	</tr>
	<tr>
		<td class="m1">メール購読解除理由</td>
		<td class="n1"><?=htmlspecialchars($fetch->mcr_cancel_text)?></td>
	</tr>
</table>
</form>
</div>

<? center_footer() ?>

</body>
</html>
