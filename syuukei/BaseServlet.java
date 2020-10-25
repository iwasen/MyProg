//**********************************************************************
//		アンケート集計システム
//
//		Title:		フォーム基本処理
//		FileName:	BaseServlet.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import java.sql.*;

// マスターメンテナンス共通のクラス
public class BaseServlet extends HttpServlet {

	// 出力文字コード
	//protected final String Encoding = "iso-2022-jp";
	protected final String Encoding = "Shift_JIS";

	// PrintWriter 取得処理
	protected PrintWriter GetPrintWriter(HttpServletResponse response)
			throws IOException {

		response.setContentType("text/html; charset=" + Encoding);
		return response.getWriter();
	}

	// JavaScript出力処理
	protected void JavaScript(PrintWriter pw, String[] script) {
		int i;

		pw.println("<script language='JavaScript'><!--");

		for (i = 0; i < script.length; i++) {
			pw.println(script[i]);
		}

		pw.println("// --></script>");
	}

	// 都道府県選択リスト作成処理
	protected void TodouhukenSelect(PrintWriter pw, String name, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MTD_TODOUFUKEN_CODE,MTD_TODOUFUKEN_NAME from M_TODOUFUKEN order by MTD_ORDER_NUM";

		pw.println("<select size=1 name='" + name + "'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// 職業選択リスト作成処理
	protected void ShokugyouSelect(PrintWriter pw, String name, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_CODE,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NUM";

		pw.println("<select size=1 name='" + name + "'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// 選択リスト作成共通処理（ＤＢから）
	private void OutputOptionDB(PrintWriter pw, String sql, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String code;
		String name;
		String sel;

		// 先頭に追加する項目
		if (top != null) {
			if (def.equals(""))
				sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + top + "</option>");
		}

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(sql);

		// 全レコードを読み込み、<option>タグを付けて出力
		while (rs.next()) {
			code = rs.getString(1).trim();
			name = rs.getString(2).trim();

			if (def.equals(code))
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + code + "'" + sel + ">" + name + "</option>");
		}

		// 最後に追加する項目
		if (bottom != null) {
			if (def.equals(""))
			sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + bottom + "</option>");
		}

		// データベースクローズ
		rs.close();
		st.close();
	}

	// チェックボックスの内容を文字列に変換
	protected String GetCheckbox(Param param, String name, int num)
			throws ServletException, IOException {

		StringBuffer buf = new StringBuffer(num);
		int i;

		// 結果を "0" クリア
		for (i = 0; i < num; i++)
			buf.append('0');

		// チェックされた項目に "1" をセット
		String[] val = param.GetParams(name);
		if (val != null) {
			for (i = 0; i < val.length; i++) {
				int no = Integer.parseInt(val[i]);
				buf.setCharAt(no, '1');
			}
		}

		return buf.toString();
	}

	// null の場合、ブランクに変換
	protected String NullToBlank(Object value) {

		if (value == null)
			return "";
		else
			return value.toString();
	}

	// 指定したページにジャンプ（sendRedirect()がNetscapeで文字化けするのでその代わり）
	protected void GotoPage(PrintWriter pw, String url) {

		pw.println("<html>");
		pw.println("<head>");
		pw.println("<meta http-equiv='Refresh' content='0; URL=" + url + "'>");
		pw.println("</head>");
		pw.println("</html>");
	}

  // エラーメッセージ表示
  protected void ErrorMessage(PrintWriter pw, String message) {

		pw.println("<html>");
		pw.println("<head>");
		pw.println("<title>Error Message</title>");
		pw.println("</head>");
    pw.println("<body>");
    pw.println(message);
    pw.println("</body>");
		pw.println("</html>");
  }
}
