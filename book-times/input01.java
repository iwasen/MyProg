//**********************************************************************
//		MCDB:新聞:○○BOOK TIMES アンケート収集システム
//
//		Title:		バスケットシステム基本クラス
//		FileName:	input01.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/19
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.util.StringTokenizer;
import java.util.Date;
import java.util.GregorianCalendar;
import java.sql.Statement;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class input01 extends BaseServlet {

	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType設定およびPrintWriter取得
			PrintWriter pw = GetPrintWriter(response);

			Html(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// "GET"メソッドhtml出力
	public void Html(PrintWriter pw) 
			throws ServletException, IOException, SQLException {

		pw.println("<HTML>");
		pw.println("<HEAD>");
		pw.println("<TITLE>BOOK TIMESアンケート</TITLE>");
		Script(pw);
		pw.println("</HEAD>");
		pw.println("<BODY BGCOLOR='#ffffff' TEXT='042255' LEFTMARGIN='0' TOPMARGIN='5' MARGINWIDTH='0' MARGINHEIGHT='5'>");
		pw.println("<CENTER>");
		pw.println("<FORM method='post' name='form1'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=10 CELLPADDING=0 width='600'>");
		pw.println("<tr><td align=center><IMG SRC='/img/ttle01.gif' width=600 height=42 alt='BOOK TIMES 5'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("アンケートにご協力いただき、ありがとうございます。<br>");
		pw.println("<b>このページは○○新聞「BOOK TIMES　５月号」のアンケート受付フォームです。</b><br>");
		pw.println("下記アンケートにお答え頂き、<b>６月１６日（金）までに</b>送信して下さい。");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</TABLE>");
		pw.println("<br>");
		pw.println("<!---------アンケートにお答えになる方について------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR><TD BGCOLOR='ffffff'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR>");
		pw.println("<TD colspan=2 BGCOLOR='4288FF'>");
		pw.println("<font size=+1 color='#ffffff'><b>■ プレゼントご希望の書籍名をお書き下さい。（書評で紹介された書籍の中からお選び下さい。）</b></font>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='BFD7FF' colspan=2 align=center>");
		pw.println("<INPUT TYPE=Text name='present' SIZE='50'><br>抽選で２１名様にプレゼント");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<TR>");
		pw.println("<TD width='5%'>");
		pw.println("<IMG SRC='/img/d.gif'>");
		pw.println("</td>");
		pw.println("<td width='95%' BGCOLOR='FFffff'>");
		pw.println("<br><font size=+1><b>Q1：アンケートにお答えになる方について教えてください。</b></font><br>");
		pw.println("*印は必須項目です。");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</TABLE>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>お名前*（全角）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>姓<INPUT TYPE=Text name='shimeisei' SIZE='20'>　名<INPUT TYPE=Text name='shimeimei' SIZE='20'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>フリガナ（全角カナ）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>姓<INPUT TYPE=Text name='furiganasei' SIZE='20'>　名<INPUT TYPE=Text name='furiganamei' SIZE='20'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>メールアドレス*（半角）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><INPUT TYPE=Text name='mailaddr' SIZE='50'><br>");
		pw.println("<nobr><input type='radio' name='mailkind' value='0'>仕事用　</nobr>");
		pw.println("<nobr><input type='radio' name='mailkind' value='1'>自宅用（プライベート）　</nobr>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>ご住所*（賞品送付先）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		pw.println("〒<input type=text size='3' name='zip1'>-<input type=text size='4' name='zip2'>（数字は半角）<br>");
		TodouhukenSelect(pw, "todoufuken", "", "都道府県名", null);
		pw.println("<input type='text' name='address' size='40'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>電話番号*（半角）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><INPUT TYPE=Text name='tel1' SIZE='5'>-<INPUT TYPE=Text name='tel2' SIZE='5'>-<INPUT TYPE=Text name='tel3' SIZE='5'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>性別</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><input type='radio' name='seibetsu' value='0'>男性　<input type='radio' name='seibetsu' value='1'>女性</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>生年月日（半角）</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>１９<INPUT TYPE=Text name='seinen' SIZE='2' maxlength=2>年<INPUT TYPE=Text name='seitsuki' SIZE='2' maxlength=2>月<INPUT TYPE=Text name='sexxx' SIZE='2' maxlength=2>日</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>職業</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		ShokugyouSelect(pw, "shokugyou", "", null, null);
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>今、興味あること</b><br>（複数回答可）</font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		pw.println("＜音楽＞<br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='0'>J-POP　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='1'>洋楽ROCK&POPS　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='2'>洋楽DANCE&SOUL　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='3'>ジャズ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='4'>クラシック　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='5'>演歌・歌謡曲　</nobr><br><br>");
		pw.println("＜スポーツ＞<br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='6'>野球　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='7'>サッカー　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='8'>ゴルフ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='9'>テニス　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='10'>陸上　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='11'>マリンスポーツ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='12'>ウィンタースポーツ（スキー等）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='13'>相撲　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='14'>プロレス・格闘技　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='15'>モータースポーツ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='16'>スポーツ：その他　</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='17'>映画　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='18'>アート　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='19'>文学・文芸（読書）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='20'>マンガ・アニメ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='21'>パソコン　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='22'>ゲーム　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='23'>車・オートバイ　</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='24'>国内旅行　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='25'>海外旅行　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='26'>ドライブ・ツーリング　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='27'>キャンプ・アウトドア　</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='28'>財テク・マネー　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='29'>健康・フィットネス　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='30'>ペット　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='31'>料理・グルメ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='32'>ファッション　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='33'>ショッピング　</nobr><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='34'>その他<font size=-0>→具体的にご記入ください。</font>（<input type=text name='q1text' size='10'>）</nobr>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("<!---------／アンケートにお答えになる方について------------->");
		pw.println("<br>");
		pw.println("<!---------Q2：BOOK TIMESをどう読まれたか教えてください。------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='ffffFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>Q2：BOOK TIMESをどう読まれたか教えてください。</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（１）BOOK TIMESは毎月１回金曜日に掲載されているのはご存知ですか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<input type='radio' name='q21radio' value='0'>はい　<input type='radio' name='q21radio' value='1'>いいえ");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（２）これまでにBOOK TIMESを読んだことはありますか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q22radio' value='0'>いつも読んでいる　</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='1'>時々読んでいる　</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='2'>初めて読んだ　</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='3'>その他（<input type=text size='10' name='q22text'>）　</nobr>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（３）BOOK TIMESについての感想をおしえてください。</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<textarea rows='2' cols='70' name='q23text'></textarea>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（４）BOOK TIMESをお読みになって興味をお持ちになる本はありますか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q24radio' value='0'>いつもある　</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='1'>時々ある　</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='2'>ない　</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='3'>その他（<input type=text size='10' name='q24text'>）</nobr>　");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>上記の（４）で、「いつもある」又は「時々ある」とお答えになった方に伺います。　その本を購");
		pw.println("入しましたか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q25radio' value='0'>購入したことがある　</nobr>");
		pw.println("<nobr><input type='radio' name='q25radio' value='1'>購入したことがない　</nobr>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（６）今後、BOOK TIMESで企画して欲しいコーナーは何ですか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<textarea rows='2' cols='70' name='q26text'></textarea>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------／Q2：BOOK TIMESをどう読まれたか教えてください。------------->");
		pw.println("<br><br>");
		pw.println("<!---------Q3：書籍に関するアンケート　（3～5問）------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='FFFFFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>Q3：書籍に関するアンケート</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（１）好きな本のジャンルをお知らせください。</b><br>（複数回答可）</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td>");
		pw.println("<td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='0'>小説（国内・海外、現代・その他）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='1'>ノンフィクション　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='2'>歴史　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='3'>ハウツー（実用書）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='4'>紀行もの　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='5'>ビジネス　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='6'>エッセイ　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='7'>タレントもの　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='8'>児童書　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='9'>絵本　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='10'>コミック　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='11'>デジタル関連　</nobr>");
		pw.println("<br>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='12'>その他<font size=-0>→具体的にご記入ください。</font>（<input type=text name='q31text' size='10'>）</nobr>");
		pw.println("</td>");
		pw.println("</tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（２）好きな作家をお知らせください。</b>（３名まで）</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("（<input type=text size='15' name='q32text1'>）　（<input type=text size='15' name='q32text2'>）　（<input type=text size='15' name='q32text3'>）　");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>（３）本を選ぶ基準をお知らせください。</b><br>（複数回答可）</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='0'>作家　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='1'>出版社　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='2'>書評（新聞）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='3'>書評（雑誌）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='4'>広告（新聞・雑誌・車内広告・看板・チラシ）　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='5'>店頭の告知で　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='6'>友人の推薦　</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='7'>売上ランキング　</nobr>");
		pw.println("<br>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='8'>その他<font size=-0>→具体的にご記入ください。</font>（<input type=text name='q33text' size='10'>）</nobr>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------／Q3：書籍に関するアンケート　（3～5問）------------->");
		pw.println("<br><br>");
		pw.println("<!---------最後に------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='FFFFFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>最後に</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='4288FF' colspan=2>");
		pw.println("<font color='#ffffff'><b>今後も、アンケートにご協力いただけますか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='BFD7FF' colspan=2>");
		pw.println("アンケートのメールをお送りしてもよろしいですか？<br>");
		pw.println("<nobr><input type='radio' name='q41radio' value='0'>よい　</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='1'>興味のある内容ならよい　</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='2'>プレゼントがあるならよい　</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='3'>協力できない　</nobr>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='4288FF' colspan=2>");
		pw.println("<font color='#ffffff'><b>アンケートのメールをお送りするアドレスは上記のアドレスでよろしいですか？</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='BFD7FF' colspan=2>");
		pw.println("<nobr><input type='radio' name='q42radio' value='0'>上記のアドレスでよい　</nobr><br>");
		pw.println("<nobr><input type='radio' name='q42radio' value='1'>別のアドレスを希望→メールアドレス </nobr>");
		pw.println("<nobr>（<input type=text name='q42text' size='20'>）</nobr>");
		pw.println("</td></tr>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------／最後に------------->");
		pw.println("<IMG SRC='/img/d.gif' WIDTH='5' HEIGHT='10'><BR>");
		pw.println("ご記入が終わりましたら送信ボタンをクリックして下さい。<p>");
		pw.println("<A href='JavaScript:SubmitEnquete()'><IMG SRC='/img/sousin.gif' border=0></A>　<A href='JavaScript:document.form1.reset()'><IMG SRC='/img/clear.gif' border=0></A>");
		pw.println("</FORM>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("<IMG SRC='/img/d.gif' WIDTH='5' HEIGHT='20'><BR>");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='600'>");
		pw.println("<TR>");
		pw.println("<TD align=center>");
		pw.println("<HR WIDTH='600'>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD align=center>");
		pw.println("BOOK TIMESアンケート事務局");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("</CENTER>");
		pw.println("<BR>");
		pw.println("<BR>");
		pw.println("</BODY>");
		pw.println("</HTML>");
	}

	// JavaScript出力
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function SubmitEnquete() {",
			" with (document.form1) {",
			"  if (present.value == '') {",
			"   alert('ご希望の書籍名を入力してください。');",
			"   present.focus();",
			"   return;",
			"  }",
			"  if (shimeisei.value == '') {",
			"   alert('お名前（姓）を入力してください。');",
			"   shimeisei.focus();",
			"   return;",
			"  }",
			"  if (shimeimei.value == '') {",
			"   alert('お名前（名）を入力してください。');",
			"   shimeimei.focus();",
			"   return;",
			"  }",
			"  if (mailaddr.value == '') {",
			"   alert('メールアドレスを入力してください。');",
			"   mailaddr.focus();",
			"   return;",
			"  }",
			"  if (mailaddr.value.indexOf('@') < 1) {",
			"    alert('正しいメールアドレスを入力してください。');",
			"    mailaddr.focus();",
			"    mailaddr.select();",
			"    return;",
			"  }",
			"  if (mailkind[0].checked == false && mailkind[1].checked == false) {",
			"   alert('自宅用または仕事用を選択してください。');",
			"   mailkind(0).focus();",
			"   return;",
			"  }",
			"  if (zip1.value == '') {",
			"   alert('郵便番号を入力してください。');",
			"   zip1.focus();",
			"   return;",
			"  }",
			"  if (isNaN(Number(zip1.value))) {",
			"   alert('正しい郵便番号を入力してください。');",
			"   zip1.focus();",
			"   zip1.select();",
			"   return;",
			"  }",
			"  if (zip2.value == '') {",
			"   alert('郵便番号を入力してください。');",
			"   zip2.focus();",
			"   return;",
			"  }",
			"  if (isNaN(Number(zip2.value))) {",
			"   alert('正しい郵便番号を入力してください。');",
			"   zip2.focus();",
			"   zip2.select();",
			"   return;",
			"  }",
			"  if (todoufuken.value == '') {",
			"   alert('都道府県を選択してください。');",
			"   todoufuken.focus();",
			"   return;",
			"  }",
			"  if (address.value == '') {",
			"   alert('ご住所を入力してください。');",
			"   address.focus();",
			"   return;",
			"  }",
			"  if (tel1.value == '') {",
			"   alert('電話番号を入力してください。');",
			"   tel1.focus();",
			"   return;",
			"  }",
			"  if (tel2.value == '') {",
			"   alert('電話番号を入力してください。');",
			"   tel2.focus();",
			"   return;",
			"  }",
			"  if (tel3.value == '') {",
			"   alert('電話番号を入力してください。');",
			"   tel3.focus();",
			"   return;",
			"  }",
			"  if (seinen.value != '') {",
			"   var nyear = Number(seinen.value);",
			"   if (isNaN(nyear) || seinen.value.indexOf('.',0) >= 0) {",
			"    alert('年が正しくありません。');",
			"    seinen.focus();",
			"    seinen.select();",
			"    return;",
			"   }",
			"   if (seitsuki.value == '') {",
			"    alert('月を入力してください。');",
			"    seitsuki.focus();",
			"    return;",
			"   }",
			"   var nmonth = Number(seitsuki.value);",
			"   if (isNaN(nmonth) || nmonth < 1 || nmonth > 12 || seitsuki.value.indexOf('.',0) >= 0) {",
			"    alert('月が正しくありません。');",
			"    seitsuki.focus();",
			"    seitsuki.select();",
			"    return;",
			"   }",
			"   if (sexxx.value == '') {",
			"    alert('日を入力してください。');",
			"    sexxx.focus();",
			"    return;",
			"   }",
			"   var ndate = Number(sexxx.value);",
			"   if (isNaN(ndate) || ndate < 1 || ndate > 31 || sexxx.value.indexOf('.',0) >= 0) {",
			"    alert('日が正しくありません。');",
			"    sexxx.focus();",
			"    sexxx.select();",
			"    return;",
			"   }",
			"  }",
			"  submit();",
			" }",
			"}"
		};

		JavaScript(pw, script);
	}


	// "POST"メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// 入力パラメータ取得クラス生成
			Param param = new Param(request);

			// アンケート内容をＤＢに保存
			StoreDB(param, response);

			// ContentType設定およびPrintWriter取得
			PrintWriter pw = GetPrintWriter(response);

			// 39.html へジャンプ
			GotoPage(pw, "/39.html");
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// アンケート内容ＤＢに保存処理
	private void StoreDB(Param param, HttpServletResponse response)
			throws ServletException, IOException, SQLException {

		// 生年月日を取得
		String SeinenGappi;
		if (!param.GetParam("seinen").equals("")) {
			int year = Integer.parseInt(param.GetParam("seinen"));
			int month = Integer.parseInt(param.GetParam("seitsuki"));
			int date = Integer.parseInt(param.GetParam("sexxx"));
			SeinenGappi = "'" + new GregorianCalendar(year + 1900, month - 1, date).getTime() + "'";
		} else
			SeinenGappi = "null";
			
		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		String sql = "insert into D_ENQUETE("
			+ "DEN_ENQUETE_DATE,"
			+ "DEN_PRESENT_TEXT,"
			+ "DEN_NAME_SEI,"
			+ "DEN_NAME_MEI,"
			+ "DEN_FURIGANA_SEI,"
			+ "DEN_FURIGANA_MEI,"
			+ "DEN_EMAIL_TEXT,"
			+ "DEN_EMAIL_CODE,"
			+ "DEN_ZIP1_NO,"
			+ "DEN_ZIP2_NO,"
			+ "DEN_TODOUFUKEN_CODE,"
			+ "DEN_ADDRESS_TEXT,"
			+ "DEN_TEL1_NO,"
			+ "DEN_TEL2_NO,"
			+ "DEN_TEL3_NO,"
			+ "DEN_SEIBETSU_CODE,"
			+ "DEN_SEINENGAPPI_DATE,"
			+ "DEN_SHOKUGYOU_CODE,"
			+ "DEN_Q1FLAG,"
			+ "DEN_Q1_TEXT,"
			+ "DEN_Q21_CODE,"
			+ "DEN_Q22_CODE,"
			+ "DEN_Q22_TEXT,"
			+ "DEN_Q23_TEXT,"
			+ "DEN_Q24_CODE,"
			+ "DEN_Q24_TEXT,"
			+ "DEN_Q25_CODE,"
			+ "DEN_Q26_TEXT,"
			+ "DEN_Q31_FLAG,"
			+ "DEN_Q31_TEXT,"
			+ "DEN_Q321_TEXT,"
			+ "DEN_Q322_TEXT,"
			+ "DEN_Q323_TEXT,"
			+ "DEN_Q33_FLAG,"
			+ "DEN_Q33_TEXT,"
			+ "DEN_KYOURYOKU_CODE,"
			+ "DEN_ENQMAIL_CODE,"
			+ "DEN_ENQMAIL_TEXT"
			+ ") values ("
			+ "'" + new Date() + "',"
			+ "'" + param.GetParam("present") + "',"
			+ "'" + param.GetParam("shimeisei") + "',"
			+ "'" + param.GetParam("shimeimei") + "',"
			+ "'" + param.GetParam("furiganasei") + "',"
			+ "'" + param.GetParam("furiganamei") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("mailaddr")) + "',"
			+ "'" + param.GetParam("mailkind") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("zip1")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("zip2"))+ "',"
			+ "'" + param.GetParam("todoufuken") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("address")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel1")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel2")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel3")) + "',"
			+ "'" + NullToBlank(param.GetParam("seibetsu")) + "',"
			+ SeinenGappi + ","
			+ "'" + param.GetParam("shokugyou") + "',"
			+ "'" + GetCheckbox(param, "q1check", 35) + "',"
			+ "'" + param.GetParam("q1text") + "',"
			+ "'" + NullToBlank(param.GetParam("q21radio")) + "',"
			+ "'" + NullToBlank(param.GetParam("q22radio")) + "',"
			+ "'" + param.GetParam("q22text") + "',"
			+ "'" + param.GetParam("q23text") + "',"
			+ "'" + NullToBlank(param.GetParam("q24radio")) + "',"
			+ "'" + param.GetParam("q24text") + "',"
			+ "'" + NullToBlank(param.GetParam("q25radio")) + "',"
			+ "'" + param.GetParam("q26text") + "',"
			+ "'" + GetCheckbox(param, "q31check", 13) + "',"
			+ "'" + param.GetParam("q31text") + "',"
			+ "'" + param.GetParam("q32text1") + "',"
			+ "'" + param.GetParam("q32text2") + "',"
			+ "'" + param.GetParam("q32text3") + "',"
			+ "'" + GetCheckbox(param, "q33check", 9) + "',"
			+ "'" + param.GetParam("q33text") + "',"
			+ "'" + NullToBlank(param.GetParam("q41radio")) + "',"
			+ "'" + NullToBlank(param.GetParam("q42radio")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("q42text")) + "')";

		st.executeUpdate(sql);
		st.close();

	}
}