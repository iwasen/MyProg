<!--#INCLUDE FILE="../melmel_inc.asp"-->
<html>
<head>
<title>めるめる・net本登録</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<LINK rel="stylesheet" href="../melmel.css">
</head>
<body>
<%
Session("seq") = Request.Form("seq")
Session("password") = Request.Form("password")

Dim DB, RS, SQL
Dim mel_id

Set DB = OpenDB()
SQL = "SELECT *" & vbCrLf & _
      "FROM T_PROVREG LEFT OUTER JOIN (SELECT * FROM T_MELMEL WHERE MM_status<>9) AS SUB1 ON PR_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE REPLACE(STR(PR_seq_no,5), ' ', '0')='" & Session("seq") & "' AND PR_password='" & Session("password") & "' AND PR_status=1"
Set RS = DB.Execute(SQL)

If RS.EOF Then
%>
<B>本登録</B><BR><P>
ID 仮パスワードにエラーがあります。
<%
Else
  mel_id = RS("MM_mel_id")
  Session("mel_id") = mel_id
%>
<script LANGUAGE="javascript">
<!--
var IsChange = false;
function changePasswd() { IsChange = true; }

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function len_chk(v1) {
	if (v1.length < 6) {
		return(false);
	}
	return(true);
}

function form_validator(theform)
{
<%
If IsNull(mel_id) Then
%>
	if ( theform.h_name.value == "" ) {
		alert("発行人の名前 : この欄は必ず入力してください。");
		theform.h_name.focus();
		return(false);
	}
	if ( theform.madd.value == "" ) {
		alert("メールアドレス : この欄は必ず入力してください。");
		theform.madd.focus();
		return(false);
	}
<%
End If
%>
	if ( theform.m_name.value == "" ) {
		alert("メールマガジン名 : この欄は必ず入力してください。");
		theform.m_name.focus();
		return(false);
	}
	if (theform.busuu.value == "" ) {
		alert("発行部数 : この欄は必ず入力してください。");
		theform.busuu.focus();
		return(false);
	}
	if (!num_chk(theform.busuu.value)){
		alert("発行部数：発行部数は半角の数値でお答え下さい。");
		theform.busuu.focus();
		return(false);
	}
  if (theform.keisen_id.value == "") {
    alert("広告罫線：広告罫線を選択してください。");
    theform.keisen_id.focus();
    return false;
  }
  var cnt = 0;
  if (theform.cat_int.checked)
    cnt++;
  if (theform.cat_pcc.checked)
    cnt++;
  if (theform.cat_soft.checked)
    cnt++;
  if (theform.cat_mvl.checked)
    cnt++;
  if (theform.cat_bns.checked)
    cnt++;
  if (theform.cat_seiji.checked)
    cnt++;
  if (theform.cat_kabu.checked)
    cnt++;
  if (theform.cat_fsn.checked)
    cnt++;
  if (theform.cat_lif.checked)
    cnt++;
  if (theform.cat_shumi.checked)
    cnt++;
  if (theform.cat_res.checked)
    cnt++;
  if (theform.cat_tvl.checked)
    cnt++;
  if (theform.cat_spt.checked)
    cnt++;
  if (theform.cat_gamble.checked)
    cnt++;
  if (theform.cat_hlt.checked)
    cnt++;
  if (theform.cat_art.checked)
    cnt++;
  if (theform.cat_shp.checked)
    cnt++;
  if (theform.cat_ent.checked)
    cnt++;
  if (theform.cat_game.checked)
    cnt++;
  if (theform.cat_movie.checked)
    cnt++;
  if (theform.cat_uranai.checked)
    cnt++;
  if (theform.cat_std.checked)
    cnt++;
  if (theform.cat_shikaku.checked)
    cnt++;
  if (theform.cat_gogaku.checked)
    cnt++;
  if (theform.cat_nws.checked)
    cnt++;
  if (theform.cat_zatsu.checked)
    cnt++;
  if (theform.cat_kenshou.checked)
    cnt++;
  if (theform.cat_homepage.checked)
    cnt++;
  if (theform.cat_hitokoto.checked)
    cnt++;
  if (theform.cat_other.checked)
    cnt++;
  if (cnt > 2) {
    alert("メルマガのカテゴリは２つ以内で選択してください。")
    return false;
  }
<%
If IsNull(mel_id) Then
%>
	if ((theform.password.value == "") || (!IsChange)) {
		alert("パスワード : 発行者パスワードを設定してください。");
		theform.password.focus();
		return(false);
	}
	if (theform.password.value != theform.password2.value) {
		alert("パスワード : パスワードが一致しません。");
		theform.password.focus();
		return(false);
	}
	if (!len_chk(theform.password.value)) {
		alert("パスワード : パスワードは6文字以上でなければなりません。");
		theform.password.focus();
		return(false);
	}
	if ( theform.bk_nm.value == "" ) {
		alert("銀行・信用金庫名 : この欄は必ず入力してください。");
		theform.bk_nm.focus();
		return(false);
	}
	if ( theform.bk_st.value == "" ) {
		alert("銀行・信用金庫支店名 : この欄は必ず入力してください。");
		theform.bk_st.focus();
		return(false);
	}
	if ( theform.bk_acc.value == "" ) {
		alert("口座番号 : この欄は必ず入力してください。");
		theform.bk_acc.focus();
		return(false);
	}
	if ( theform.bk_knm.value == "" ) {
		alert("口座名義人 : この欄は必ず入力してください。");
		theform.bk_knm.focus();
		return(false);
	}
<%
End If
%>
  return true;
}
//-->
</script>
<B>本登録情報</B>
<P>規約を確認後、本登録を行ってください。その際、必ず、パスワードを変更してください。<BR>
<P>仮登録の時いただいた情報を表示してあります。必要であれば適宜修正してください。<BR>

<P><center>

<%=String(80, "-")%><BR>

</center>
<strong><font size="3" color="#FF0000">※ご注意・・・めるめる･netから配信される広告は、2001年2月1日より、「まぐまぐ」から発行されるメルマガに掲載することができなくなります。予めご了承の上、「本登録」ボタンを押してください。</font></strong><center>

<form method="POST" action="registmgzn_4.asp" ONSUBMIT="return form_validator(this)" name="theform">
  <input type="hidden" name="seq" value="<%=Right("00000" & RS("PR_seq_no"), 5)%>">
  <input type="hidden" name="mel_id" value="<%=mel_id%>">
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td width="201">■発行者のお名前</td>
      <td width="449"><input type="text" name="h_name" size="40" value="<%=RS("PR_h_name")%>"></td>
    </tr>
    <tr>
      <td width="201">■発行者のe-mailアドレス</td>
      <td width="449"><input type="text" name="madd" size="50" value="<%=RS("PR_mail_adr")%>"></td>
    </tr>
    <tr>
      <td width="201">■（法人の場合）会社名</td>
      <td width="449"><input type="text" name="c_name" size="50" value="<%=RS("PR_c_name")%>"></td>
    </tr>
<%
Else
%>
    <tr>
      <td width="201">■発行者のお名前</td>
      <td width="449"><input type="hidden" name="h_name" value="<%=RS("PR_h_name")%>"><%=RS("PR_h_name")%></td>
    </tr>
    <tr>
      <td width="201">■発行者のe-mailアドレス</td>
      <td width="449"><input type="hidden" name="madd" value="<%=RS("PR_mail_adr")%>"><%=RS("PR_mail_adr")%></td>
    </tr>
    <tr>
      <td width="201">■（法人の場合）会社名</td>
      <td width="449"><input type="hidden" name="c_name" value="<%=RS("PR_c_name")%>"><%=RS("PR_c_name")%></td>
    </tr>
<%
End If
%>
    <tr>
      <td width="201">■メルマガ名</td>
      <td width="449"><input type="text" name="m_name" size="50" value="<%=RS("PR_m_name")%>"></td>
    </tr>
    <tr>
      <td width="201">■発行部数(誌数ではありません!)</td>
      <td width="449"><input type="text" name="busuu" size="8" value="<%=RS("PR_busuu")%>">部 （複数の発行システムご利用の際は、合計部数を記入してください）</td>
    </tr>
    <tr>
      <td width="201">■発行頻度</td>
      <td width="449"><input type="text" name="hindo" size="30" value="<%=RS("PR_hindo")%>"><br>（不定期発行の方もだいたいの頻度をお書きください・・・例えば月に１～２回とか）</td>
    </tr>
    <tr>
      <td width="201">■メルマガ発行スタンド</td>
      <td width="449">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
            <td><input type="checkbox" name="mms_melonpan" value="1"<%=Checked(RS("PR_mms_melonpan"))%>>めろんぱん</td>
            <td><input type="checkbox" name="mms_mag" value="1"<%=Checked(RS("PR_mms_mag"))%>>まぐまぐ</td>
            <td><input type="checkbox" name="mms_pubz" value="1"<%=Checked(RS("PR_mms_pubz"))%>>Pubzine</td>
            <td><input type="checkbox" name="mms_melma" value="1"<%=Checked(RS("PR_mms_melma"))%>>melma!</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="mms_macky" value="1"<%=Checked(RS("PR_mms_macky"))%>>Macky</td>
            <td><input type="checkbox" name="mms_kapu" value="1"<%=Checked(RS("PR_mms_kapu"))%>>カプライト</td>
            <td><input type="checkbox" name="mms_emag" value="1"<%=Checked(RS("PR_mms_emag"))%>>E-MAGAZINE</td>
            <td><input type="checkbox" name="mms_tengoku" value="1"<%=Checked(RS("PR_mms_tengoku"))%>>メルマガ天国</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="mms_tiara" value="1"<%=Checked(RS("PR_mms_tiara"))%>>ティアラオンライン</td>
            <td><input type="checkbox" name="mms_merubox" value="1"<%=Checked(RS("PR_mms_merubox"))%>>MERU-BOX</td>
            <td><input type="checkbox" name="mms_dokuji" value="1"<%=Checked(RS("PR_mms_dokuji"))%>>独自配信</td>
            <td><input type="checkbox" name="mms_other" value="1"<%=Checked(RS("PR_mms_other"))%>>その他</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="201">■めろんぱんのマガジンID（めろんぱんで配信している場合のみ）</td>
      <td width="449"><input type="text" name="melonpan_id" maxlength="10" value="<%=RS("PR_melonpan_id")%>"></td>
    </tr>
    <tr>
      <td width="201">■まぐまぐのマガジンID（まぐまぐで配信している場合のみ）</td>
      <td width="449"><input type="text" name="mgmg_id" maxlength="10" value="<%=RS("PR_mgmg_id")%>">（１０桁でご記入ください）</td>
    </tr>
    <tr>
      <td width="201">■広告罫線</td>
      <td>
        <select name="keisen_id" style="font-family:ＭＳ ゴシック;font-size:9pt">
<%
Dim RS2
SQL = "SELECT KE_keisen_id,KE_start_line FROM T_KEISEN ORDER BY KE_keisen_id"
Set RS2 = DB.Execute(SQL)

Writeln "<option value='' selected>お好みの広告罫線を選択してください</option>"
Do Until RS2.EOF
  Writeln "<option value='" & RS2("KE_keisen_id") & "'>" & ReplaceTag(ZenkakuRight(RS2("KE_start_line"), 30)) & "</option>"
  RS2.MoveNext
Loop
RS2.Close
%>
        </select>
      </td>
    </tr>
    <tr>
      <td width="201">■１行の文字数</td>
      <td width="449">
        <select name="moji_suu">
          <option value="35">全角35文字</option>
          <option value="36">全角36文字</option>
          <option value="37">全角37文字</option>
          <option value="38">全角38文字</option>
        </select> （メールマガジンの１行の全角文字数を選択してください）
      </td>
    </tr>
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td>■メルマガの紹介文<br>　（全角250文字まで）</td>
      <td>
        <textarea name="shoukai_text" cols=60 rows=4></textarea>
      </td>
    </tr>
    <tr>
      <td>■購読用ホームページのＵＲＬ</td>
      <td>
        <input type="text" name="koudoku_url" size="62" value="http://">
      </td>
    </tr>
    <tr>
      <td>■メルマガ詳細説明用のＵＲＬ</td>
      <td>
        <input type="text" name="shousai_url" size="62" value="http://">
      </td>
    </tr>
    <tr>
      <td>■読者向け紹介ページへの掲載</td>
      <td>
        <input type="radio" name="shoukai_flag1" value="1" checked>希望する　<input type="radio" name="shoukai_flag1" value="0">希望しない
        <br>（掲載を希望する場合は、紹介文と購読用URLを必ず入力してください）
      </td>
    </tr>
<%
End If
%>
  </table>
    	
  <P>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">■あなたのメルマガのおおよその読者層を教えてください。</td>
    </tr>
      	
    <tr>
      <td width="120">メルマガのカテゴリ<br>（２つ以内で選択してください）</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
            <td><input type="checkbox" name="cat_int" value="1"<%=Checked(RS("PR_cat_int"))%>>インターネット</td>
            <td><input type="checkbox" name="cat_pcc" value="1"<%=Checked(RS("PR_cat_pcc"))%>>パソコン/コンピュータ全般</td>
            <td><input type="checkbox" name="cat_soft" value="1"<%=Checked(RS("PR_cat_soft"))%>>ソフトウェア/プログラミング</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_mvl" value="1"<%=Checked(RS("PR_cat_mvl"))%>>携帯/モバイル</td>
            <td><input type="checkbox" name="cat_bns" value="1"<%=Checked(RS("PR_cat_bns"))%>>ビジネス全般</td>
            <td><input type="checkbox" name="cat_seiji" value="1"<%=Checked(RS("PR_cat_seiji"))%>>政治/経済</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_kabu" value="1"<%=Checked(RS("PR_cat_kabu"))%>>株式/投資</td>
            <td><input type="checkbox" name="cat_fsn" value="1"<%=Checked(RS("PR_cat_fsn"))%>>ファッション/美容</td>
            <td><input type="checkbox" name="cat_lif" value="1"<%=Checked(RS("PR_cat_lif"))%>>暮らし/日記</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_shumi" value="1"<%=Checked(RS("PR_cat_shumi"))%>>趣味</td>
            <td><input type="checkbox" name="cat_res" value="1"<%=Checked(RS("PR_cat_res"))%>>グルメ/レストラン</td>
            <td><input type="checkbox" name="cat_tvl" value="1"<%=Checked(RS("PR_cat_tvl"))%>>旅行/地域</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_spt" value="1"<%=Checked(RS("PR_cat_spt"))%>>スポーツ/レジャー</td>
            <td><input type="checkbox" name="cat_gamble" value="1"<%=Checked(RS("PR_cat_gamble"))%>>ギャンブル</td>
            <td><input type="checkbox" name="cat_hlt" value="1"<%=Checked(RS("PR_cat_hlt"))%>>健康/医療/育児</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_art" value="1"<%=Checked(RS("PR_cat_art"))%>>アート/文芸</td>
            <td><input type="checkbox" name="cat_shp" value="1"<%=Checked(RS("PR_cat_shp"))%>>ショッピング</td>
            <td><input type="checkbox" name="cat_ent" value="1"<%=Checked(RS("PR_cat_ent"))%>>エンターテイメント</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_game" value="1"<%=Checked(RS("PR_cat_game"))%>>ゲーム/クイズ</td>
            <td><input type="checkbox" name="cat_movie" value="1"<%=Checked(RS("PR_cat_movie"))%>>映画/音楽/芸能</td>
            <td><input type="checkbox" name="cat_uranai" value="1"<%=Checked(RS("PR_cat_uranai"))%>>占い</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_std" value="1"<%=Checked(RS("PR_cat_std"))%>>学習/教育</td>
            <td><input type="checkbox" name="cat_shikaku" value="1"<%=Checked(RS("PR_cat_shikaku"))%>>資格</td>
            <td><input type="checkbox" name="cat_gogaku" value="1"<%=Checked(RS("PR_cat_gogaku"))%>>語学</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_nws" value="1"<%=Checked(RS("PR_cat_nws"))%>>ニュース</td>
            <td><input type="checkbox" name="cat_zatsu" value="1"<%=Checked(RS("PR_cat_zatsu"))%>>雑学</td>
            <td><input type="checkbox" name="cat_kenshou" value="1"<%=Checked(RS("PR_cat_kenshou"))%>>懸賞/プレゼント</td>
          </tr>
          <tr>
            <td><input type="checkbox" name="cat_homepage" value="1"<%=Checked(RS("PR_cat_homepage"))%>>ホームページ紹介</td>
            <td><input type="checkbox" name="cat_hitokoto" value="1"<%=Checked(RS("PR_cat_hitokoto"))%>>一日ひとこと系</td>
            <td><input type="checkbox" name="cat_other" value="1"<%=Checked(RS("PR_cat_other"))%>>その他</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">年齢及び性別 だいたいこんな人－ということでチェックしてください。</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="age_10m" value="1"<%=Checked(RS("PR_age_10m"))%>>10代男</td>
          	<td><input type="checkbox" name="age_20m" value="1"<%=Checked(RS("PR_age_20m"))%>>20代男</td>
          	<td><input type="checkbox" name="age_30m" value="1"<%=Checked(RS("PR_age_30m"))%>>30代男</td>
          	<td><input type="checkbox" name="age_40m" value="1"<%=Checked(RS("PR_age_40m"))%>>40代男</td>
          	<td><input type="checkbox" name="age_50m" value="1"<%=Checked(RS("PR_age_50m"))%>>50代～男</td>
          </tr>
          <tr>
          	<td><input type="checkbox" name="age_10f" value="1"<%=Checked(RS("PR_age_10f"))%>>10代女</td>
          	<td><input type="checkbox" name="age_20f" value="1"<%=Checked(RS("PR_age_20f"))%>>20代女</td>
          	<td><input type="checkbox" name="age_30f" value="1"<%=Checked(RS("PR_age_30f"))%>>30代女</td>
          	<td><input type="checkbox" name="age_40f" value="1"<%=Checked(RS("PR_age_40f"))%>>40代女</td>
          	<td><input type="checkbox" name="age_50f" value="1"<%=Checked(RS("PR_age_50f"))%>>50代～女</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">職業　だいたいこんな人－ということでチェックしてください。</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="shk_stu" value="1"<%=Checked(RS("PR_shk_stu"))%>>学生</td>
          	<td><input type="checkbox" name="shk_enp" value="1"<%=Checked(RS("PR_shk_enp"))%>>社会人</td>
          	<td><input type="checkbox" name="shk_syf" value="1"<%=Checked(RS("PR_shk_syf"))%>>主婦</td>
          	<td><input type="checkbox" name="shk_other" value="1"<%=Checked(RS("PR_shk_other"))%>>その他</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="120">地域　だいたいこんな所－ということでチェックしてください。わからなければ全国を。</td>
      <td width="530">
        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="kihon">
          <tr>
          	<td><input type="checkbox" name="are_ht" value="1"<%=Checked(RS("PR_are_ht"))%>>北海道/東北</td>
          	<td><input type="checkbox" name="are_kt" value="1"<%=Checked(RS("PR_are_kt"))%>>関東</td>
          	<td><input type="checkbox" name="are_cb" value="1"<%=Checked(RS("PR_are_cb"))%>>中部</td>
          	<td><input type="checkbox" name="are_kk" value="1"<%=Checked(RS("PR_are_kk"))%>>近畿</td>
          </tr>
          <tr>
          	<td><input type="checkbox" name="are_cs" value="1"<%=Checked(RS("PR_are_cs"))%>>中国/四国</td>
          	<td><input type="checkbox" name="are_ks" value="1"<%=Checked(RS("PR_are_ks"))%>>九州</td>
          	<td><input type="checkbox" name="are_fr" value="1"<%=Checked(RS("PR_are_fr"))%>>海外</td>
          	<td><input type="checkbox" name="are_zk" value="1"<%=Checked(RS("PR_are_zk"))%>>全国</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<%
If IsNull(mel_id) Then
%>
  <p>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">■パスワードを設定してください。</td>
    </tr>
    <tr>
      <td width="120">発行者パスワード</td>
      <td width="530"><input type="password" name="password" size="10" maxlength="10" onChange="changePasswd()">（６文字以上の半角英数字で設定してください）</td>
    </tr>
    <tr>
      <td width="120">パスワードの再入力</td>
      <td width="530"><input type="password" name="password2" size="10" maxlength="10"></td>
    </tr>
  </table>
<%
End If
%>

  <p>
  <table border="1" width="650" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" class="kihon">
    <tr>
      <td align="center" colspan="2" width="600">■振込み銀行口座</td>
    </tr>
<%
If IsNull(mel_id) Then
%>
    <tr>
      <td width="120">銀行・信用金庫名</td>
      <td width="530"><input type="text" name="bk_nm" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">支店名</td>
      <td width="530"><input type="text" name="bk_st" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">預金口座種類</td>
      <td width="530"><input type="radio" name="bk_knd" value=1 checked>普通 <input type="radio" name="bk_knd" value=2>当座 <input type="radio" name="bk_knd" value=3>貯蓄</td>
    </tr>
    <tr>
      <td width="120">口座番号</td>
      <td width="530"><input type="text" name="bk_acc" size="80" maxlength="100"></td>
    </tr>
    <tr>
      <td width="120">口座名義人名</td>
      <td width="530"><input type="text" name="bk_knm" size="80" maxlength="100"><br>（必ず全角カナでご記入ください）</td>
    </tr>
<%
Else
%>
    <tr>
      <td width="120">銀行・信用金庫名</td>
      <td width="530"><%=RS("MM_bk_nm")%></td>
    </tr>
    <tr>
      <td width="120">支店名</td>
      <td width="530"><%=RS("MM_bk_st")%></td>
    </tr>
    <tr>
      <td width="120">預金口座種類</td>
      <td width="530">
<%
  Select Case RS("MM_bk_knd")
  Case 1
    Response.Write "普通"
  Case 2
    Response.Write "当座"
  Case 3
    Response.Write "貯蓄"
  Case Else
    Response.Write "不明"
  End Select
%>
      </td>
    </tr>
    <tr>
      <td width="120">口座番号</td>
      <td width="530"><%=RS("MM_bk_acc")%></td>
    </tr>
    <tr>
      <td width="120">口座名義人名</td>
      <td width="530"><%=RS("MM_bk_knm")%></td>
    </tr>
<%
End If
%>
  </table>
  <p><input type="submit" value="本登録"></p>
</form>
	
</center>
<%
End If
%>
</body>
</html>
