//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		商品マスタ一覧処理
//		FileName:	ShouhinList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// 商品一覧画面処理クラス
public class ShouhinList extends BaseServlet1
{
	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// ログインチェック
			if (!LoginCheck(request)) {
				// ログインしていない場合は強制的にトップページに戻す
				response.sendRedirect(TopPage);
				return;
			}

			MyUtil myutil = new MyUtil();
			Param param = new Param(request);
			PrintWriter pw = GetPrintWriter(response);
			String no;

			// 表示する氏名を取得
			no = param.GetParam("no");
			if (no == null)
				no = myutil.GetCookieValue(request, "ShouhinNo");
			else
				myutil.PutCookie(response, "ShouhinNo", no);

			Header(pw, "商品一覧");
			Body(pw, no);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, String no)
		throws ServletException, IOException, SQLException {

		String code;
		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
		int count = 0;

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select MSH_SHOUHIN_CODE,MSH_SHOUHIN_NO,MSH_SHOUHIN_NAME,MSB_BUNRUI_NAME,MCT_CATEGORY_NAME,MSH_TOUROKU_DATE"
				+ " from M_SHOUHIN,M_SBUNRUI,M_CATEGORY"
				+ " where MSH_BUNRUI_CODE=MSB_BUNRUI_CODE and MSH_CATEGORY_CODE=MCT_CATEGORY_CODE";
		if (no != null && !no.equals(""))
			sql += " and MSH_SHOUHIN_NO like '" + no + "%'";
		sql += " order by MSH_SHOUHIN_CODE";
		ResultSet rs = st.executeQuery(sql);

		// カラムヘッダ出力
		pw.println("<table border=1 width='96%' bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th width='9%'>商品コード</th>");
		pw.println("<th width='15%'>商品番号</th>");
		pw.println("<th width='38%'>商品名</th>");
		pw.println("<th width='10%'>商品分類</th>");
		pw.println("<th width='18%'>カテゴリー</th>");
		pw.println("<th width='10%'>登録日</th>");
		pw.println("</tr>");

		// レコード情報出力
		while (rs.next()) {
			pw.println("<tr>");
			code = rs.getString(1);
			pw.println("<td><a href='ShouhinItem?code=" + code + "'>" + code + "</td>");
			pw.println("<td>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td>" + rs.getString(4) + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + formatter.format(rs.getDate(6)) + "</td>");
			pw.println("</tr>");
			count++;
		}
		pw.println("</table>");

		// 件数表示
		pw.println("<div align='right'>件数：" + count + "</div>");

		// データベースクローズ
		rs.close();
		st.close();
	}
}
