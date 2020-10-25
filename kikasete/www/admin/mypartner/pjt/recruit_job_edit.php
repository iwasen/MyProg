<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・Myパートナー情報変更
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー情報変更', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("このＭｙアンケートを更新します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■概要設定内容</td>
	</tr>
	<tr>
		<td class="m1" width="20%">アンケート種類</td>
		<td class="n1" width="80%">
			<input type="radio" name="enq_type" value='1'>メールアンケート
			<input type="radio" name="enq_type" value='2' checked>Ｗｅｂアンケート
		</td>
	</tr>
	<tr>
		<td class="m1">開始日時</td>
		<td class="n1">
			<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">年
			<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">月
			<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">日
		</td>
	</tr>
	<tr>
		<td class="m1">終了日時</td>
		<td class="n1">
			<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">年
			<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">月
			<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">日
			<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">時
		</td>
	</tr>
	<tr>
		<td class="m1">ポイント数</td>
		<td class="n1">
			<input class="number" type="text" name="point" size="3" maxlength="3" value="40">ポイント
		</td>
	</tr>
	<tr>
		<td class="m1">最大発信数</td>
		<td class="n1">
			<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">人
		</td>
	</tr>
	<tr>
		<td class="m1">実施状態</td>
		<td class="n1"><select name="status"><option value='5'>実施中</option><option value='6'>一時停止</option><option value='7' selected>終了</option><option value='9'>削除済み</option></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケート内容</td>
	</tr>
	<tr>
		<td class="m1">アンケートタイトル</td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size="50" maxlength="20" value="「キャラクター」に関するアンケート">
		</td>
	</tr>
	<tr>
		<td class="m1">説明文</td>
		<td class="n1">
			<textarea class="kanji" rows="20" cols="70" name="description">今回は、「あなたの好きなキャラクター」についてお聞きしたいと思います。
下記【参加条件】にあてはまる方ならどなたでもお答えいただけますので、
ご協力のほど、よろしくお願いします。

━━━━━━━━━━━━━━━━━━━━━━━━━━
回答締め切り：４月２６日（月）１７：００必着
━━━━━━━━━━━━━━━━━━━━━━━━━━

＋━━【参加条件】━━━━━━━━＋

　　■ ３０歳〜３９歳の女性
　　■ このアンケートURLお知らせメールを受け取られたご本人さま


アンケートにお答えいただいた方全員に【４０ポイント】をお付けいたします。

━━≫アンケート開始≪━━━━━━━━━━━━━━━━━━━━━━━</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q１</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text1">次にあげるキャラクターのうち、あなたがご存知のキャラクターはどれですか？知っているもの全てにチェックをしてください。</textarea>
		<td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type1">
				<option value='1'>単一選択(SA)
				<option value='2' selected>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q２</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text2">次にあげるキャラクターのうち、あなたが好きなキャラクターはどれですか？好きなもの全てにチェックをしてください。</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type2">
				<option value='1'>単一選択(SA)
				<option value='2' selected>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q３</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text3">次にあげるキャラクターのうち、あなたが１番好きなキャラクターはどれですか？好きなもの１つだけにチェックをしてください。</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type3">
				<option value='1' selected>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q４</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text4">次にあげるキャラクターのうち、あなたが嫌いなキャラクターはどれですか？嫌いなもの全てにチェックをしてください。</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type4">
				<option value='1'>単一選択(SA)
				<option value='2' selected>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q５</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text5"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type5">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q６</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text6"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type6">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q７</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text7"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type7">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q８</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text8"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type8">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q９</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text9"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type9">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q１０</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text10"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type10">
				<option value='1'>単一選択(SA)
				<option value='2'>複数選択(MA)
				<option value='3'>自由回答(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■条件設定内容</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<input type="checkbox" name="sex[]" value='1'>男性&nbsp;
			<input type="checkbox" name="sex[]" value='2' checked>女性
		</td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='1'><font size=2 class='honbun2'>１０才未満</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='2'><font size=2 class='honbun2'>１０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='3'><font size=2 class='honbun2'>１０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='4'><font size=2 class='honbun2'>２０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='5'><font size=2 class='honbun2'>２０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='6' checked><font size=2 class='honbun2'>３０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='7' checked><font size=2 class='honbun2'>３０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='8'><font size=2 class='honbun2'>４０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='9'><font size=2 class='honbun2'>４０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='10'><font size=2 class='honbun2'>５０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='11'><font size=2 class='honbun2'>５０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='12'><font size=2 class='honbun2'>６０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='13'><font size=2 class='honbun2'>６０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='14'><font size=2 class='honbun2'>７０才以上</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1">
			<input type="checkbox" name="mikikon[]" value='1'>独身&nbsp;
			<input type="checkbox" name="mikikon[]" value='2'>既婚&nbsp;
			<input type="checkbox" name="mikikon[]" value='3'>その他
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'><font size=2 class='honbun2'>北海道</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'><font size=2 class='honbun2'>青森県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'><font size=2 class='honbun2'>岩手県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'><font size=2 class='honbun2'>宮城県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'><font size=2 class='honbun2'>秋田県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'><font size=2 class='honbun2'>山形県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'><font size=2 class='honbun2'>福島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'><font size=2 class='honbun2'>茨城県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'><font size=2 class='honbun2'>栃木県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'><font size=2 class='honbun2'>群馬県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11' checked><font size=2 class='honbun2'>埼玉県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12' checked><font size=2 class='honbun2'>千葉県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13' checked><font size=2 class='honbun2'>東京都</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14' checked><font size=2 class='honbun2'>神奈川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'><font size=2 class='honbun2'>新潟県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'><font size=2 class='honbun2'>富山県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'><font size=2 class='honbun2'>石川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'><font size=2 class='honbun2'>福井県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'><font size=2 class='honbun2'>山梨県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'><font size=2 class='honbun2'>長野県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'><font size=2 class='honbun2'>岐阜県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'><font size=2 class='honbun2'>静岡県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'><font size=2 class='honbun2'>愛知県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'><font size=2 class='honbun2'>三重県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'><font size=2 class='honbun2'>滋賀県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'><font size=2 class='honbun2'>京都府</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'><font size=2 class='honbun2'>大阪府</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'><font size=2 class='honbun2'>兵庫県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'><font size=2 class='honbun2'>奈良県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'><font size=2 class='honbun2'>和歌山県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'><font size=2 class='honbun2'>鳥取県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'><font size=2 class='honbun2'>島根県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'><font size=2 class='honbun2'>岡山県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'><font size=2 class='honbun2'>広島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'><font size=2 class='honbun2'>山口県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'><font size=2 class='honbun2'>徳島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'><font size=2 class='honbun2'>香川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'><font size=2 class='honbun2'>愛媛県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'><font size=2 class='honbun2'>高知県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'><font size=2 class='honbun2'>福岡県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'><font size=2 class='honbun2'>佐賀県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'><font size=2 class='honbun2'>長崎県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'><font size=2 class='honbun2'>熊本県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'><font size=2 class='honbun2'>大分県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'><font size=2 class='honbun2'>宮崎県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'><font size=2 class='honbun2'>鹿児島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'><font size=2 class='honbun2'>沖縄県</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'><font size=2 class='honbun2'>会社員</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'><font size=2 class='honbun2'>会社経営・役員</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'><font size=2 class='honbun2'>公務員</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'><font size=2 class='honbun2'>自営業</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'><font size=2 class='honbun2'>パート・アルバイト</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'><font size=2 class='honbun2'>専門職（弁護士・医師・会計士等）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'><font size=2 class='honbun2'>専門職（SOHO・デザイナー・クリエイター等）</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'><font size=2 class='honbun2'>教職</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'><font size=2 class='honbun2'>学生</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'><font size=2 class='honbun2'>専業主婦・主夫</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'><font size=2 class='honbun2'>無職</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">コンビニ</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='1' checked><font size=2 class='honbun2'>セブンイレブン</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='2'><font size=2 class='honbun2'>ローソン</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='3'><font size=2 class='honbun2'>ファミリーマート</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='4'><font size=2 class='honbun2'>サンクス</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='5'><font size=2 class='honbun2'>ミニストップ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='6'><font size=2 class='honbun2'>am.pm.</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='7'><font size=2 class='honbun2'>ヤマザキ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='8'><font size=2 class='honbun2'>サークルＫ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='9'><font size=2 class='honbun2'>スリーエフ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='10'><font size=2 class='honbun2'>その他</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">スーパー</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='1'><font size=2 class='honbun2'>イトーヨーカドー</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='2'><font size=2 class='honbun2'>ダイエー</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='3'><font size=2 class='honbun2'>ジャスコ</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='4'><font size=2 class='honbun2'>西友</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='5'><font size=2 class='honbun2'>生協</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='6'><font size=2 class='honbun2'>マイカル</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='7'><font size=2 class='honbun2'>マルエツ</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='8'><font size=2 class='honbun2'>東急ストア</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='9'><font size=2 class='honbun2'>ユニー</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='10'><font size=2 class='honbun2'>その他</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">興味ジャンル</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='1'><font size=2 class='honbun2'>グルメ・料理</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='2'><font size=2 class='honbun2'>飲み物（お酒以外）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='3'><font size=2 class='honbun2'>お酒</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='4'><font size=2 class='honbun2'>ファッション</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='5'><font size=2 class='honbun2'>ビジネス</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='6'><font size=2 class='honbun2'>お金（貯蓄・投資・保険）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='7'><font size=2 class='honbun2'>学校・資格</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='8'><font size=2 class='honbun2'>エンターテインメント</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='18'><font size=2 class='honbun2'>旅行・レジャー</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='9'><font size=2 class='honbun2'>コンピュータ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='10'><font size=2 class='honbun2'>アート</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='11'><font size=2 class='honbun2'>スポーツ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='12'><font size=2 class='honbun2'>健康</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='13'><font size=2 class='honbun2'>介護・福祉</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='14'><font size=2 class='honbun2'>住宅</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='15'><font size=2 class='honbun2'>インテリア</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='16'><font size=2 class='honbun2'>自動車・バイク</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='17'><font size=2 class='honbun2'>新製品・新商品</font></td>
</tr>
</table>
		</td>
	</tr>
<tr>
	<td class="m1">メンバーリスト</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag"  onclick="show_hide('member_list_c', checked)">指定する
		<span id="member_list_c">&nbsp;
			<font class="note">（メンバーグループIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">除外条件</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag"  onclick="show_hide('member_list_c', checked)">指定する
		<span id="member_list_c">&nbsp;
			<font class="note">（メンバーグループIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
			<input type="button" value="選択" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3></textarea>
		</span>
	</td>
</tr>

	<tr>
		<td class="m0" colspan=2>■メール内容</td>
	</tr>
	<tr>
		<td class="m1">メールタイトル</td>
		<td class="n1">
			<input type="text" name="mail_title" size="50" maxlength="100" value="「キャラクターに関するアンケート」ご協力お願いします">
		</td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_header">―――――――――――――――――――――――――――― きかせて・net
■締め切り　：　2004年4月26日17時
■ポイント　：　40
―――――――――――――――――――――――――――――――――――</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1">
			<textarea rows="20" cols="70" wrap="hard" name="mail_contents">%MONITOR_NAME% さん、こんにちは

今回は、「キャラクターに関するアンケート」にご協力いただきたいと
思っています。
このメールを受け取った方で参加条件にあてはまる方ならどなたでも
参加できるアンケートですので、ぜひぜひご回答ください！

　　　　　┏━━━━━━━━━━━━━━━━━━━━━━┓
　　　　　　回答締め切り：４月２６日（月）１７時必着
　　　　　┗━━━━━━━━━━━━━━━━━━━━━━┛

┌───────
■参加条件
└────────────────────────────────

 　・３０〜３９歳までの女性
　・このメールを直接受取られた方

┌───────
■アンケート詳細
└────────────────────────────────

　▼質問総数…………４問
　▼締切日……………４月２６日（月）１７時必着
　▼謝礼ポイント……回答いただいた方全員に【４０】ポイント
　　　　　　　　　　差し上げます。

┌───────
■有効回答
└────────────────────────────────

有効回答は、上記条件に当てはまり、このアンケートのＵＲＬが記載
されたメールを受け取ったご本人さま、１回限りとさせていただきます。

　　　　　　　　　　　　　　▽　▽　▽

□回答上の手順━…━…━…━…━…━…━…━…━…━…━…━…━…━

　以下のＵＲＬからログインして、アンケートにご回答下さい。
⇒ http://www.kikasete.net/monitor/m_remlenq3.php?id=%ENQUETE_PARAM%

　※あなたのログインアドレスは
⇒ %MAIL_ADDR% です。

□回答上の注意━…━…━…━…━…━…━…━…━…━…━…━…━…━

　●このアンケートを受け取ったご本人さま以外の方が回答されても、
　　ポイント加算の対象とはなりません。

　●記入もれや、回答内容に不備があった場合などはポイントをおつけする
　　ことができなくなります。ご回答の際は、ご注意いただけますよう
　　お願いいたします。


それでは、ご回答よろしくお願いします。</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_footer">―――――――――――――――――――――――――――――――――――
このメールの返信でお問い合わせを頂いても、お答えすることが出来ません。
ご注意ください。

■アンケートに対するお問い合わせ
　このアンケートに対するお問合わせは、きかせて・net 事務局へご連絡く
　ださい。（askmyenq@kikasete.net）

■ポイントの確認／登録情報の変更／きかせて・net事務局へのご質問等
　Ｍｙページへログイン後、各メニューへお進み下さい。
　http://www.kikasete.net/</textarea>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" value="1047">
<input type="hidden" name="my_enq_no" value="2">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
