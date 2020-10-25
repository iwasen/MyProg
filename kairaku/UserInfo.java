//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザーマスタ情報表示処理
//		FileName:	UserInfo.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.util.GregorianCalendar;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ユーザー一覧画面処理クラス
public class UserInfo extends BaseServlet1
{
	// "GET"メソッド処理
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// ログインチェック
			if (!LoginCheck(request)) {
				// ログインしていない場合は強制的にトップページに戻す
				response.sendRedirect(TopPage);
				return;
			}

			Param param = new Param(request);
			response.setContentType("application/vnd.ms-excel; charset=iso-2022-jp");
			PrintWriter pw = response.getWriter();

			Body(pw, param);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Param param)
		throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select M_USER.*,MSG_SHOKUGYOU_NAME from M_USER,M_SHOKUGYOU where MUS_SHOKUGYO_CODE=MSG_SHOKUGYOU_CODE";
		String year = param.GetParam("year");
		if (year != null && !year.equals("")) {
			int nyear = Integer.parseInt(param.GetParam("year"));
			int nmonth = Integer.parseInt(param.GetParam("month"));
			int ndate = Integer.parseInt(param.GetParam("date"));
			GregorianCalendar date = new GregorianCalendar(nyear, nmonth - 1, ndate);
			sql += " and MUS_TOUROKU_DATE >= '" + date.getTime() + "'";
		}
		sql += " order by MUS_USER_CODE";
		ResultSet rs = st.executeQuery(sql);

		// ヘッダ部出力
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<title>ユーザー一覧</title>");
		pw.println("</head>");
		pw.println("<body>");

		// カラムヘッダ出力
		pw.println("<table border=1 BORDERCOLOR='gray'>");
		pw.println("<tr>");
		//pw.println("<th width='10%' bgcolor='#FFE0E0'>ユーザーコード</th>");
		pw.println("<th width='10%' bgcolor='#FFE0E0'>ユーザーＩＤ</th>");
		pw.println("<th width='10%' bgcolor='#FFE0E0'>パスワード</th>");
		pw.println("<th width='20%' bgcolor='#FFE0E0'>登録日</th>");
		pw.println("<th width='20%' bgcolor='#FFE0E0'>ふりがな</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>氏名</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>性別</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>生年月日</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>年齢</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>18歳以上確認</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>18歳以上確認日</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>職業</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>郵便番号</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>住所</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>建物名</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>連絡先電話番号</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>FAX番号</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>メールアドレス</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>メーリングリスト登録</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>ＵＲＬ</th>");
		pw.println("</tr>");
		pw.println("</thead>");

		// レコード情報出力
		while (rs.next()) {
			pw.println("<tr>");
			//pw.println("<td>" + rs.getString(1) + "</td>");
			pw.println("<td>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td>" + myutil.DateToString(rs.getDate(4)) + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + rs.getString(6) + "</td>");
			pw.println("<td>" + rs.getString(7) + "</td>");
			pw.println("<td>" + rs.getString(8) + "/" + rs.getString(9) + "/" + rs.getString(10) + "</td>");
			pw.println("<td>" + rs.getInt(11) + "</td>");
			pw.println("<td>" + rs.getBoolean(12) + "</td>");
			pw.println("<td>" + myutil.DateToString(rs.getDate(13)) + "</td>");
			pw.println("<td>" + rs.getString("MSG_SHOKUGYOU_NAME") + "</td>");
			pw.println("<td>" + rs.getString(15) + "</td>");
			pw.println("<td>" + rs.getString(16) + "</td>");
			pw.println("<td>" + rs.getString(17) + "</td>");
			pw.println("<td>" + rs.getString(18) + "</td>");
			pw.println("<td>" + rs.getString(19) + "</td>");
			pw.println("<td>" + rs.getString(20) + "</td>");
			pw.println("<td>" + rs.getBoolean(21) + "</td>");
			pw.println("<td>" + rs.getString(22) + "</td>");
			pw.println("</tr>");
		}
		pw.println("</table>");

		pw.println("</body>");
		pw.println("</html>");

		// データベースクローズ
		rs.close();
		st.close();
	}
}
