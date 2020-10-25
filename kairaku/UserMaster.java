//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザーマスタ情報クラス
//		FileName:	UserMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.util.Date;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// ユーザーマスタ管理クラス
public class UserMaster
{
	public String UserCode;
	public String UserID;
	public String Password;
	public Date TourokuDate;
	public String Furigana;
	public String Shimei;
	public String Seibetsu;
	public int SeinenYear;
	public int SeinenMonth;
	public int SeinenDay;
	public int Nenrei;
	public boolean Over18Flag;
	public Date Over18Date;
	public String Shokugyou;
	public String Zip;
	public String Address;
	public String Tatemono;
	public String TelNo;
	public String FaxNo;
	public String MailAddr;
	public boolean MailList;
	public String Url;

	// インスタンス変数初期化
	public void Init() {
		UserCode = "";
		UserID = "";
		Password = "";
		TourokuDate = new Date();
		Furigana = "";
		Shimei = "";
		Seibetsu = "";
		SeinenYear = 0;
		SeinenMonth = 0;
		SeinenDay = 0;
		Nenrei = 0;
		Over18Flag = false;
		Over18Date = null;
		Shokugyou = "";
		Zip = "";
		Address = "";
		Tatemono = "";
		TelNo = "";
		FaxNo = "";
		MailAddr = "";
		MailList = false;
		Url = "";
	}

	// ユーザーマスタからユーザー情報を読み込み
	public boolean Read(String code)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select * from M_USER where MUS_USER_CODE='" + code + "'";
		ResultSet rs = st.executeQuery(sql);
		boolean result = rs.next();
		if (result) {
			// 取得できたらインスタンス変数に格納
			UserCode = rs.getString(1).trim();
			UserID = rs.getString(2).trim();
			Password = rs.getString(3).trim();
			TourokuDate = rs.getDate(4);
			Furigana = rs.getString(5).trim();
			Shimei = rs.getString(6).trim();
			Seibetsu = rs.getString(7).trim();
			SeinenYear = rs.getInt(8);
			SeinenMonth = rs.getInt(9);
			SeinenDay = rs.getInt(10);
			Nenrei = rs.getInt(11);
			Over18Flag = rs.getBoolean(12);
			Over18Date = rs.getDate(13);
			Shokugyou = rs.getString(14).trim();
			Zip = rs.getString(15).trim();
			Address = rs.getString(16).trim();
			Tatemono = rs.getString(17).trim();
			TelNo = rs.getString(18).trim();
			FaxNo = rs.getString(19).trim();
			MailAddr = rs.getString(20).trim();
			MailList = rs.getBoolean(21);
			Url = rs.getString(22).trim();
		}

		// データベースクローズ
		rs.close();
		st.close();

		return result;
	}

	// ユーザーマスタにデータ追加
	public void Store(boolean delete)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		try {
			// トランザクション開始
			db.Transaction();

			// 削除ありなら削除を実行
			if (delete) {
				String sql = "delete from M_USER where MUS_USER_CODE='" + UserCode + "'";
				st.executeUpdate(sql);
			}

			// Insert文作成
			String sql = "insert into M_USER values ("
				+ "'" + UserCode + "',"
				+ "'" + UserID + "',"
				+ "'" + Password + "',"
				+ "'" + TourokuDate + "',"
				+ "'" + Furigana + "',"
				+ "'" + Shimei + "',"
				+ "'" + Seibetsu + "',"
				+ SeinenYear + ","
				+ SeinenMonth + ","
				+ SeinenDay + ","
				+ Nenrei + ","
				+ Over18Flag + ","
				+ myutil.NullAble(Over18Date) + ","
				+ "'" + Shokugyou + "',"
				+ "'" + Zip + "',"
				+ "'" + Address + "',"
				+ "'" + Tatemono + "',"
				+ "'" + TelNo + "',"
				+ "'" + FaxNo + "',"
				+ "'" + MailAddr + "',"
				+ "'" + MailList + "',"
				+ "'" + Url + "')";

			// Insert文実行
			st.executeUpdate(sql);
			st.close();

			// 正常ならコミット
			db.Commit();
		} catch (SQLException e) {
			// エラーならロールバック
			db.Rollback();
			throw e;
		}
	}

	// ユーザーマスタからデータ削除
	public void Delete()
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// delete文実行
		String sql = "delete from M_USER where MUS_USER_CODE='" + UserCode + "'";
		st.executeUpdate(sql);
		st.close();
	}
}
