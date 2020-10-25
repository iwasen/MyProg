//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		選択商品確認処理
//		FileName:	SelectList.java
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

// 商品選択結果表示ページ処理クラス
public class SelectList extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "選択商品確認", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order) {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");

		pw.println("<p class='s5' align='left'>ご購入品の確認</p>");
		pw.println("<form name='fm' method='post' class='s1'>");
		pw.println("<table width='563' border='2' cellspacing='0' cellpadding='2' height='142' bgcolor='#CCFFCC' class='s1'>");

		pw.println("<tr>");
		pw.println("<td width='95' height='28'>");
		pw.println("<div align='center'>商品番号</div>");
		pw.println("</td>");
		pw.println("<td width='223' height='28'>");
		pw.println("<div align='center'>商品名</div>");
		pw.println("</td>");
		pw.println("<td width='113' height='28'>");
		pw.println("<div align='center'>単価（税込）</div>");
		pw.println("</td>");
		pw.println("<td width='81' height='28'>");
		pw.println("<div align='center'>個数</div>");
		pw.println("</td>");
		pw.println("<td width='81' height='28'>");
		pw.println("<div align='center'>小計</div>");
		pw.println("</td>");
		pw.println("</tr>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<input type='hidden' name='code' value='" + meisai.ShouhinCode + "'>");
			pw.println("<td width='95' height='45'>");
			pw.println("<div align='center'>" + meisai.ShouhinNo + "</div>");
			pw.println("</td>");
			pw.println("<td width='223' height='45'>");
			pw.println("<div align='center'>" + meisai.ShouhinName + "</div>");
			pw.println("</td>");
			pw.println("<td width='113' height='45'>");
			pw.println("<div align='center'>" + fmt.format(meisai.HanbaiKakaku) + "円</div>");
			pw.println("</td>");
			pw.println("<td width='81' height='45'>");
			pw.println("<div align='center'>");
			pw.println("<input type='text' name='kosuu' size='4' value='" + meisai.Kosuu + "'> " + meisai.Tani + "</div>");
			pw.println("</td>");
			pw.println("<td width='81' height='45'>");
			pw.println("<div align='center'>" + fmt.format(meisai.Shoukei) + "円</div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='5'>");
		pw.println("<p align='right'>合計　" + fmt.format(order.juchu.Total) + "円（税込み）</p>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");

		pw.println("<p><span class='s1'><font color='#FF0000'>");
		pw.println("上記の合計金額の他に送料（700円）がかかります。<br>");
		pw.println("宅配代金引換をご希望の方は送料の他に手数料（400円）を頂きます。<br>");
		pw.println("ただし、15,000円以上お買い上げの場合、送料、手数料は無料とさせていただきます。<br>");
		pw.println("また、HAB写真集に関しては、送料・手数料は、無料となります。お客様が、HAB写真集<br>");
		pw.println("と他の商品を同時に、購入した場合も送料・手数料は、頂きません。無料となります。");
		pw.println("</font></span></p>");

		pw.println("<p>個数の変更がこの画面で可能です。個数変更後、［再計算］をクリックすると、<br>");
		pw.println("合計金額を再計算します。<br>");
		pw.println("他の商品を購入したい場合は［他の商品も購入する］<br>");
		pw.println("ご購入を中止されたい時には［やめる］<br>");
		pw.println("以下の商品を購入したい時は［購入する］をクリックしてください。<br>");
		pw.println("<span class='s1'></span></p>");
		pw.println("<p><span class='s1'>");
		pw.println("<input type='submit' name='recalc' value='再計算'>");
		pw.println("<input type='submit' name='cancel' value='やめる'>");
		pw.println("<input type='submit' name='other' value='他の商品も購入する'>");
		pw.println("<input type='submit' name='ok' value='購入する'" + (order.juchu.Total == 0 ? " disabled" : "") + ">");
		pw.println("</span>");
		pw.println("</p>");
		pw.println("</form>");
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// 明細情報をクリアする
		order.DeleteMeisai();

		// 入力された商品のコードと個数を明細データとして保存
		String code[] = param.GetParams("code");
		String kosuu[] = param.GetParams("kosuu");
		if (code != null) {
			for (int i = 0; i < code.length; i++) {
				int ikosuu = Integer.parseInt(kosuu[i]);
				if (ikosuu > 0)
					order.AddMeisai(code[i], ikosuu);
			}
		}

		// 合計金額の計算
		int count = order.GetMeisaiNum();
		int total = 0;
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			total += meisai.HanbaiKakaku * meisai.Kosuu;
		}
		order.juchu.Total = total;

		// クリックされたボタンによる振り分け
		if (param.GetParam("recalc") != null) {
			// 「再計算」ならこのページを再表示
			doGetHandler(response, pw, session, param, order);
		} else if (param.GetParam("cancel") != null) {
			// 「やめる」ならセッションを解除し、トップページに戻る
			session.invalidate();
			response.sendRedirect(TopPage);
		} else if (param.GetParam("other") != null) {
			// 「他の商品も購入する」なら検索条件指定画面に戻る
			response.sendRedirect("Kensaku");
		} else if (param.GetParam("ok") != null) {
			// 「購入する」ならユーザＩＤ入力ページに行く
			response.sendRedirect("UserID");
		}
	}
}
