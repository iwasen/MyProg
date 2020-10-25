//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザーマスタ一覧処理
//		FileName:	UserList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ユーザー一覧画面処理クラス
public class UserList extends BaseServlet1
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
			String name;

			// 表示する氏名を取得
			name = param.GetParam("name");
			if (name == null)
				name = myutil.GetCookieValue(request, "UserName");
			else
				myutil.PutCookie(response, "UserName", name);

			Header(pw, "ユーザー一覧");
			Body(pw, name);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, String name)
		throws ServletException, IOException, SQLException {

		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
		int count = 0;

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select MUS_USER_CODE,MUS_USER_ID,MUS_PASSWORD_TEXT,MUS_18OVER_FLAG,MUS_SHIMEI_TEXT,MUS_FURIGANA_TEXT,MUS_MAIL_ADDR,MUS_TEL_NO from M_USER";
		if (name != null && !name.equals(""))
			sql += " where MUS_SHIMEI_TEXT like '" + name + "%'";
		sql += " order by MUS_USER_CODE";
		ResultSet rs = st.executeQuery(sql);

		// カラムヘッダ出力
		pw.println("<table border=1 width='96%' bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th width='10%'>ユーザーID</th>");
		pw.println("<th width='10%'>パスワード</th>");
		pw.println("<th width='10%'>18歳以上</th>");
		pw.println("<th width='20%'>氏名</th>");
		pw.println("<th width='20%'>ふりがな</th>");
		pw.println("<th width='15%'>e-mail</th>");
		pw.println("<th width='15%'>電話番号</th>");
		pw.println("</tr>");

		// レコード情報出力
		while (rs.next()) {
			pw.println("<tr>");
			pw.println("<td><a href='UserUpdate?code=" + rs.getString(1) + "'>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td align='center'>" + (rs.getBoolean(4) ? "●" : "<br>") + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + rs.getString(6) + "</td>");
			pw.println("<td>" + rs.getString(7) + "</td>");
			pw.println("<td>" + rs.getString(8) + "</td>");
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
