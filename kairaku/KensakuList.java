//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		一覧表示商品指定処理
//		FileName:	KensakuList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// 検索結果表示ページ処理クラス
public class KensakuList extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "一覧表示商品指定", "", "bgcolor='#CCFFFF' class='s1'");
		Body(pw, param);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Param param)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select M_SHOUHIN.*,MSB_BUNRUI_NAME,MCT_CATEGORY_NAME"
				+ " from M_SHOUHIN,M_SBUNRUI,M_CATEGORY"
				+ " where MSH_BUNRUI_CODE=MSB_BUNRUI_CODE and MSH_CATEGORY_CODE=MCT_CATEGORY_CODE %1"
				+ " order by MSH_SHOUHIN_CODE";

		String str;
		String where = "";

		// 商品分類コードの指定があれば条件に加える
		str = param.GetParam("BunruiCode");
		if (str != null && !str.equals(""))
			where += " and MSH_BUNRUI_CODE='" + str + "'";

		// カテゴリーコードの指定があれば条件に加える
		str = param.GetParam("CategoryCode");
		if (str != null && !str.equals(""))
			where += " and MSH_CATEGORY_CODE='" + str + "'";

		// 期間の指定があれば条件に加える
		str = param.GetParam("kikan");
		if (str != null && !str.equals("")) {
			GregorianCalendar date = new GregorianCalendar();
			date.add(Calendar.MONTH, -Integer.parseInt(str));
			where += " and MSH_TOUROKU_DATE>'" + date.getTime() + "'";
		}

		// 主演の指定があれば条件に加える
		str = param.GetParam("shuen");
		if (str != null && !str.equals(""))
			where += " and MSH_ACTOR_NAME like '%" + str + "%'";

		// SQL 実行
		sql = myutil.ReplaceString(sql, "%1", where);
		ResultSet rs = st.executeQuery(sql);
		int count = 0;

		// 結果をhtmlに出力
		pw.println("<p class='s6' align='center'>検索結果</p>");
		pw.println("<form name='fm' method='post'>");
		while (rs.next()) {
			pw.println("<input type='hidden' name='code' value='" + rs.getString("MSH_SHOUHIN_CODE") + "'>");
			pw.println("<table width='825' border='2' cellspacing='0' cellpadding='4' height='117' bgcolor='#FFFFCC' class='s3'>");
			pw.println("<tr>");
			pw.println("<td width='63' height='25'>");
			pw.println("<div align='center'>" + rs.getString("MSH_SHOUHIN_NO") + "</div>");
			pw.println("</td>");
			pw.println("<td width='143' rowspan='3'>");
			pw.println("<div align='center'><a href='" + pictLogicalPath + rs.getString("MSH_GAZOU2_FILE") + "' target='new'><img src='" + pictLogicalPath + rs.getString("MSH_GAZOU1_FILE") + "' width='121' height='90'></a></div>");
			pw.println("</td>");
			pw.println("<td width='128' height='25'>");
			pw.println("<div align='center'>" + ConvertBlank(rs.getString("MSH_SERIES_NAME")) + "</div>");
			pw.println("</td>");
			pw.println("<td width='179' height='25'>");
			pw.println("<div align='center'>" + ConvertBlank(rs.getString("MSH_ACTOR_NAME")) + "</div>");
			pw.println("</td>");
			pw.println("<td width='127' rowspan='3'>");
			pw.println("<p align='center'>定価" + fmt.format(rs.getInt("MSH_TEIKA_YEN")) + "円</p>");
			pw.println("<p align='center' class='s1'><font color='#FF0000'>売価" + fmt.format(rs.getInt("MSH_HANBAI_YEN")) + "円</font>");
			pw.println("</p>");
			pw.println("</td>");
			pw.println("<td width='78' rowspan='3'>");
			pw.println("<div align='center'>かごに入れる<br>");
			pw.println("<input type='checkbox' name='basket' value='" + count + "'>");
			pw.println("</div>");
			pw.println("</td>");
			pw.println("<td width='61' rowspan='3' nowrap>");
			pw.println("<div align='center'>");
			pw.println("<input type='text' name='kosuu' size='4' value='1'> " + rs.getString("MSH_TANI_TEXT") + "</div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("<tr>");
			pw.println("<td width='63' rowspan='2'>");
			pw.println("<div align='center'>" + rs.getString("MSB_BUNRUI_NAME") + "</div>");
			pw.println("</td>");
			pw.println("<td width='128' height='23'>");
			pw.println("<div align='center' class='s3'>" + rs.getString("MCT_CATEGORY_NAME") + "</div>");
			pw.println("</td>");
			pw.println("<td rowspan='2' width='179'> ");
			pw.println("<div align='center'>" + ConvertBlank(ConvertCRLF(rs.getString("MSH_KAISETSU_TEXT"))) + "</div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("<tr>");
			pw.println("<td width='128' height='27'>");
			pw.println("<div align='center' class='s1'><font color='#0000CC'>" + rs.getString("MSH_SHOUHIN_NAME") + "</font></div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("</table>");

			count++;
		}

		// ０件だった場合のメッセージ
		if (count == 0) {
			pw.println("<h3>指定された条件に合う商品はありません。</h3>");
		}

		// ボタン部分の出力
		pw.println("<p><br>");
		pw.println("<span class='s1'>");
		pw.println("<input type='submit' name='register' value='レジへ行く'>");
		pw.println("<input type='submit' name='continue' value='買い物を続ける'>");
		pw.println("</span></p>");
		pw.println("</form>");

		// データベースクローズ
		rs.close();
		st.close();
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// 選択結果の取得
		String[] code = param.GetParams("code");
		String[] basket = param.GetParams("basket");
		String[] kosuu = param.GetParams("kosuu");
		// 「かごに入れる」がチェックされているデータを明細データとして保存
		if (basket != null) {
			for (int i = 0; i < basket.length; i++) {
				int index = Integer.parseInt(basket[i]);
				int ikosuu = Integer.parseInt(kosuu[index]);
				if (ikosuu > 0) {
					order.AddMeisai(code[index], ikosuu);
				}
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
		if (param.GetParam("register") != null) {
			// 「レジへ行く」の場合は選択商品確認画面へ
			response.sendRedirect("SelectList");
		} else if (param.GetParam("continue") != null) {
			// 「買い物を続ける」の場合は検索条件指定画面へ
			response.sendRedirect("Kensaku");
		}
	}
}
