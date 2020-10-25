//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		送信確認画面処理
//		FileName:	thankyou.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// 送信確認ページ処理クラス
public class thankyou extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "送信確認", "", "bgcolor='#FFCCCC' class='s1'");
		Body(pw);
		Footer(pw);

		session.invalidate();
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw)
			throws ServletException, IOException, SQLException {

		pw.println("<p align='center'><b><span class='s5'>ご購入ありがとうございました。</span><br>");
		pw.println("<font color='#FF0000' class='s1'>注<br>");
		pw.println("合計金額の他に送料（700円）かかります。<br>");
		pw.println("宅配代金引換をご希望の方は送料の他に手数料（400円）を頂きます。<br>");
		pw.println("ただし、15,000円以上お買い上げの場合、送料、手数料は無料とさせていただきます。</font>");
		pw.println("</b></p>");
		pw.println("<hr>");
		pw.println("<p align='center'><span class='s2'><font color='#FF0000'><b>初めての方へ（18歳以上年齢確認がまだの方）</b></font>");
		pw.println("</span></p>");
		pw.println("<p align='center'> 18歳以上の年齢を確認できる書類のコピーを、快楽市場までFAX、またはご送付してください。</p>");
		pw.println("<p align='center' class='s1'> 送付先・連絡先<br>");
		pw.println("162-0814　東京都新宿区新小川町3-10SEビル4F 快楽市場事務局<br>");
		pw.println("TEL03-5229-7512　FAX03-5229-7513</p>");
		pw.println("<p align='center' class='s1'> 18歳以上の確認が出来次第、メールにて連絡致します。<br>");
		pw.println("それまで料金のお振り込みはしないで下さい。<br>");
		pw.println("年齢確認ができるまで、商品の発送はできません。</p>");
		pw.println("<hr>");
		pw.println("<p align='center' class='s1'><span class='s2'><font color='#FF0000'><b>２度目以降の方へ</b></font></span></p>");
		pw.println("<p align='center' class='s1'> 料金のお支払いについては、 当ホームページ地下8階　決済方法について<br>");
		pw.println("<a href='http://www.kairakuichiba.co.jp/b8F/kessai.html'>http://www.kairakuichiba.co.jp/b8F/kessai.html</a><br>");
		pw.println("をご覧ください。</p>");
		pw.println("<form name='form1' action='"+ TopPage +"'>");
		pw.println("<div align='center'>");
		pw.println("<input type='submit' value='もどる'>");
		pw.println("</div>");
		pw.println("</form>");
	}
}
