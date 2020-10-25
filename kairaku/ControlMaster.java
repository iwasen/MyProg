//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		コントロールマスター操作クラス
//		FileName:	Contrxxxaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.text.DecimalFormat;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// コントロールマスター操作用クラス
public class Contrxxxaster
{
	// 新しい商品コード取得処理
	public String GetNewShouhinCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_SHOUHIN_CODE");

		DecimalFormat form = new DecimalFormat("00000");
		return form.format(code);
	}

	// 新しいユーザコード取得処理
	public String GetNewUserCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_USER_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// 新しいアンケートコード取得処理
	public String GetNewEnqueteCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_ENQUETE_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// 新しい受注コード取得処理
	public String GetNewJuchuCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_JUCHU_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// 新しいコード取得共通処理
	private int GetNewCode(String name)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select " + name + " from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// 取得できなかったら例外を発生させる
			throw new ServletException("Not found Control master");
		}

		// 次のコードを取得
		int icode = rs.getInt(1);

		// コードを＋１する
		sql = "update M_CONTROL set " + name + "=" + (icode + 1);
		st.executeUpdate(sql);

		// データベースクローズ
		rs.close();
		st.close();

		return icode;
	}

	// 商品コードキャンセル処理
	public void CancelShouhinCode(String code)
			throws ServletException, IOException, SQLException {
		CancelCode("MCN_SHOUHIN_CODE", code);
	}

	// ユーザーコードキャンセル処理
	public void CancelUserCode(String code)
			throws ServletException, IOException, SQLException {
		CancelCode("MCN_USER_CODE", code);
	}

	// コードキャンセル共通処理
	private void CancelCode(String name, String code)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select " + name + " from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// 取得できなかったら例外を発生させる
			throw new ServletException("Not found Control master");
		}

		// 次のコードを取得
		int icode = rs.getInt(1);

		// 直前のキャンセルだったらコードをひとつ戻す
		if (icode == Integer.parseInt(code) + 1) {
			sql = "update M_CONTROL set " + name + "=" + (icode - 1);
			st.executeUpdate(sql);
		}

		// データベースクローズ
		rs.close();
		st.close();
	}

	// 管理者用ＩＤ／パスワードチェック
	public boolean CheckAdmin(String id, String password)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select MCN_ADMIN_ID,MCN_ADMIN_PASS from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// 取得できなかったら例外を発生させる
			throw new ServletException("Control master read failed.");
		}

		// ＩＤとパスワードが一致しているかチェック
		boolean result = id.equals(rs.getString("MCN_ADMIN_ID").trim())
				&& password.equals(rs.getString("MCN_ADMIN_PASS").trim());

		// データベースクローズ
		rs.close();
		st.close();

		return result;
	}
	// メール送信情報クラス
	class MailInfo {
		String MailServer;
		String ToAddress;
		String CcAddress;
		String FromAddress;
	}
	// メール送信情報取得
	public MailInfo GetMailInfo()
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select * from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// 取得できなかったら例外を発生させる
			throw new ServletException("Not found Control master");
		}

		// メール情報インスタンス化
		MailInfo mailinfo = new MailInfo();

		// メール情報をインスタンス化したクラスにセット
		mailinfo.MailServer = rs.getString("MCN_MAIL_SERVER");
		mailinfo.ToAddress = rs.getString("MCN_MAIL_TO");
		mailinfo.CcAddress = rs.getString("MCN_MAIL_CC");
		mailinfo.FromAddress = rs.getString("MCN_MAIL_FROM");

		// データベースクローズ
		rs.close();
		st.close();

		return mailinfo;
	}
}
