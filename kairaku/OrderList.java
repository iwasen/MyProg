//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		受注情報入力処理
//		FileName:	OrderList.java
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

// 商品オーダリスト表示ページ処理クラス
public class OrderList extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "受注情報入力", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Script(pw);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");
		Order.User user = order.user;
		Order.Juchu juchu = order.juchu;

		// 届け先情報の初期値をユーザ情報から設定
		juchu.Zip = user.Zip;
		juchu.Address = user.Address;
		juchu.Tatemono = user.Tatemono;
		juchu.TelNo = user.TelNo;

		// 受注情報を表示
		pw.println("<form name='form1' method='post' class='s1' onSubmit='return OnSubmit();'>");
		pw.println("<p class='s5' align='center'><font color='#330099'>▼" + user.Shimei + "様　オーダー▼</font></p>");
		pw.println("<p align='center' class='s5'>購入品</p>");
		pw.println("<div align='center'>");
		pw.println("<table width='560' border='2' cellspacing='1' cellpadding='1' class='s1' bgcolor='#FFFFCC'>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<td width='60' nowrap>" + meisai.ShouhinNo + "</td>");
			pw.println("<td width='241' nowrap>" + meisai.ShouhinName + "</td>");
			pw.println("<td width='74' nowrap>" + meisai.Kosuu + meisai.Tani + "</td>");
			pw.println("<td width='163' nowrap>");
			pw.println("<div align='right'>" + fmt.format(meisai.Shoukei) + "円</div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='4' nowrap>");
		pw.println("<div align='right'>合計金額（税込）　" + fmt.format(order.juchu.Total) + "円</div>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		pw.println("<span class='s1'><font color='#FF0000'>");
		pw.println("上記の合計金額の他に送料（700円）がかかります。<br>");
		pw.println("宅配代金引換をご希望の方は送料の他に手数料（400円）を頂きます。<br>");
		pw.println("ただし、15,000円以上お買い上げの場合、送料、手数料は無料とさせていただきます。<br>");
		pw.println("また、HAB写真集に関しては、送料・手数料は、無料となります。お客様が、HAB写真集<br>");
		pw.println("と他の商品を同時に、購入した場合も送料・手数料は、頂きません。無料となります。");
		pw.println("</font></span>");

		pw.println("</div>");
		pw.println("<blockquote><font color='#330099'>");
		pw.println("　　登録先住所以外の場所に送付をご希望の場合は<br>");
		pw.println("　　下記「お届け先郵便番号」「お届け先住所」「お届け先建物」「お届け先電話番号」欄に<br>");
		pw.println("　　ご希望の郵便番号、住所、建物、電話番号を入力して「次へ」をクリックして下さい。<br>");
		pw.println("　　お届け先はご注文の度に指定することが出来ます。<br>");
		pw.println("</font></blockquote>");
		pw.println("<table cellpadding=5 class='s1'>");
		pw.println("<tr>");
		pw.println("<td align='right'>お支払い方法</td>");
		pw.println("<td>");
		myutil.ShiharaiSelect(pw, "", null, null);
		pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>お届け先郵便番号</td>");
		pw.println("<td><input type='text' name='postnum' size='10' value='" + juchu.Zip + "'>");
		pw.println("　<font color='#0000FF'>半角数字　例：123-4567 </font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>お届け先住所</td>");
		pw.println("<td><input type='text' name='tjuusyo' size='80' value='" + juchu.Address + "'>");
		pw.println("　<font color='#0000FF'>全角かな・漢字</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>お届け先建物</td>");
		pw.println("<td><input type='text' name='tatemono' size='40' value='" + juchu.Tatemono + "'>");
		pw.println("　<font color='#0000FF'>全角かな・漢字</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>お届け先電話番号</td>");
		pw.println("<td><input type='text' name='tel' value='" + juchu.TelNo + "'>");
		pw.println("　<font color='#0000FF'>例　00-0000-0000</font></td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<p><center>");
		pw.println("<input type='submit' name='next' value=' 次へ '>");
		pw.println("</center></p>");
		pw.println("</form>");
	}

	// JavaScript出力処理
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" var form1 = document.form1;",
			" if (form1.postnum.value == '') {",
			"  alert('お届け先郵便番号を入力してください。');",
			"  form1.postnum.focus();",
			"  return false;",
			" }",
			" if (form1.tjuusyo.value == '') {",
			"  alert('お届け先住所を入力してください。');",
			"  form1.tjuusyo.focus();",
			"  return false;",
			" }",
			" if (form1.tel.value == '') {",
			"  alert('お届け先電話番号を入力してください。');",
			"  form1.tel.focus();",
			"  return false;",
			" }",
			" return true;",
			"}"
		};

		JavaScript(pw, script);
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Order.Juchu juchu = order.juchu;

		if (param.GetParam("next") != null) {
			// 入力された届け先情報を受注情報に保存
			juchu.Zip = param.GetParam("postnum");
			juchu.Address = param.GetParam("tjuusyo");
			juchu.Tatemono = param.GetParam("tatemono");
			juchu.TelNo = param.GetParam("tel");
			juchu.Shiharai = param.GetParam("ShiharaiCode");

			// 商品オーダ確認画面へリダイレクト
			response.sendRedirect("OrderVerify");
		}
	}
}
