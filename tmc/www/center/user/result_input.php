<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ユーザ情報表示・引継ぎ情報入力
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/result.php");

// 入力パラメータ
$user_id = $_GET['user_id'];

// 対応結果取得
$sql = "SELECT t_user_result.*,ups_name_kanji,ups_mail_addr,urg_handover_date"
		. " FROM t_user_personal"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " JOIN t_user_regist ON urg_user_id=ups_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=ups_user_id"
		. " WHERE ups_user_id=$user_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	var f = document.form1;
	if (f.hot.value == "1") {
		if (f.rank.value == "") {
			alert("ランクを選択してください。");
			f.rank.focus();
			return false;
		}
		return confirm("引継ぎを行います。よろしいですか？");
	} else
		return confirm("引継ぎ情報を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? center_header('ユーザ情報表示｜引継ぎ情報入力') ?>

<div align="center">
<form method="post" name="form1" action="result_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■引継ぎ情報を入力してください。</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>お客様意向</td>
	</tr>
	<tr>
		<td class="m1">その他関心のある車</td>
		<td class="n1">
			<select name="sonota_kanshin"><?= select_sonota_kanshin(DEFAULT_SELECT, $fetch->urs_sonota_kanshin) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">関心ありメーカー</td>
		<td class="n1">
			<select name="kanshin_maker"><?= select_kanshin_maker(DEFAULT_SELECT, $fetch->urs_kanshin_maker) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">関心あり車名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kanshin_car" size=30 maxlength=15 <?=value($fetch->urs_kanshin_car)?>>
			<span class="note">（全角１５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">次週イベント誘致</td>
		<td class="n1">
			<select name="jishuu_enent"><?= select_jishuu_enent(DEFAULT_SELECT, $fetch->urs_jishuu_enent) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">資料送付</td>
		<td class="n1">
			<input type="checkbox" name="shiryou_souhu[]" value="0" <?=checked($fetch->urs_shiryou_souhu{0})?>>案内せず
			<input type="checkbox" name="shiryou_souhu[]" value="1" <?=checked($fetch->urs_shiryou_souhu{1})?>>関心あり
			<input type="checkbox" name="shiryou_souhu[]" value="2" <?=checked($fetch->urs_shiryou_souhu{2})?>>資料送付希望
			<input type="checkbox" name="shiryou_souhu[]" value="3" <?=checked($fetch->urs_shiryou_souhu{3})?>>不明
		</td>
	</tr>
	<tr>
		<td class="m1">資料送付コメント</td>
		<td class="n1">
			<input class="kanji" type="text" name="shiryou_comment" size=30 maxlength=15 <?=value($fetch->urs_shiryou_comment)?>>
			<span class="note">（全角１５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">情報提供の継続</td>
		<td class="n1">
			<select name="teikyou_keizoku"><?= select_teikyou_keizoku(DEFAULT_SELECT, $fetch->urs_teikyou_keizoku) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">情報提供形態</td>
		<td class="n1">
			<input type="checkbox" name="teikyou_keitai[]" value="0" <?=checked($fetch->urs_teikyou_keitai{0})?>>訪問
			<input type="checkbox" name="teikyou_keitai[]" value="1" <?=checked($fetch->urs_teikyou_keitai{1})?>>ＴＥＬ
			<input type="checkbox" name="teikyou_keitai[]" value="2" <?=checked($fetch->urs_teikyou_keitai{2})?>>ＤＭ
			<input type="checkbox" name="teikyou_keitai[]" value="3" <?=checked($fetch->urs_teikyou_keitai{3})?>>ＦＡＸ
			<input type="checkbox" name="teikyou_keitai[]" value="4" <?=checked($fetch->urs_teikyou_keitai{4})?>>E-mail
			<input type="checkbox" name="teikyou_keitai[]" value="5" <?=checked($fetch->urs_teikyou_keitai{5})?>>ケータイ
		</td>
	</tr>
	<tr>
		<td class="m1">お店への要望</td>
		<td class="n1">
			<select name="mise_youbou"><?= select_mise_youbou(DEFAULT_SELECT, $fetch->urs_mise_youbou) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">コメント</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant" size=60 maxlength=30 <?=value($fetch->urs_commant)?>>
			<span class="note">（全角３０文字まで）</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>保有状況</td>
	</tr>
	<tr>
		<td class="m1">趣味など</td>
		<td class="n1">
			<input class="kanji" type="text" name="shumi" size=100 maxlength=50 <?=value($fetch->urs_shumi)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>テレコール結果</td>
	</tr>
	<tr>
		<td class="m1">ランク</td>
		<td class="n1">
			<select name="rank"><?= select_rank(DEFAULT_SELECT, $fetch->urs_rank) ?></select>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>その他</td>
	</tr>
	<tr>
		<td class="m1">コメント</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant1" size=100 maxlength=50 <?=value($fetch->urs_commant1)?>>
			<span class="note">（全角５０文字まで）</span>
			<input class="kanji" type="text" name="commant2" size=100 maxlength=50 <?=value($fetch->urs_commant2)?>>
			<span class="note">（全角５０文字まで）</span>
			<input class="kanji" type="text" name="commant3" size=100 maxlength=50 <?=value($fetch->urs_commant3)?>>
			<span class="note">（全角５０文字まで）</span>
			<input class="kanji" type="text" name="commant4" size=100 maxlength=50 <?=value($fetch->urs_commant4)?>>
			<span class="note">（全角５０文字まで）</span><br>
		</td>
	</tr>
	<tr>
		<td class="m1">併有車情報＆家族情報</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant5" size=100 maxlength=50 <?=value($fetch->urs_commant5)?>>
			<span class="note">（全角５０文字まで）</span>
			<input class="kanji" type="text" name="commant6" size=100 maxlength=50 <?=value($fetch->urs_commant6)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>購入済み車種等</td>
	</tr>
	<tr>
		<td class="m1">購入状況</td>
		<td class="n1">
			<select name="kounyuu_joukyou"><?= select_kounyuu_joukyou(DEFAULT_SELECT, $fetch->urs_kounyuu_joukyou) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">購入車名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kounyuu_shamei" size=40 maxlength=20 <?=value($fetch->urs_kounyuu_shamei)?>>
			<span class="note">（全角２０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<?
if ($fetch->urg_handover_date == '') {
?>
<input type="submit" value="引継ぎ情報の登録のみを行う" onclick="document.form1.hot.value=''">
<input type="submit" value="登録して引継ぎを行う" onclick="document.form1.hot.value='1'">
<?
}
?>
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="user_id" <?=value($user_id)?>>
<input type="hidden" name="hot">
</form>
</div>

<? center_footer() ?>

</body>
</html>
