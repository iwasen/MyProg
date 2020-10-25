//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		商品マスタ情報クラス
//		FileName:	ShouhinMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.Date;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// 商品マスタ管理クラス
public class ShouhinMaster
{
	public String ShouhinCode;
	public String ShouhinName;
	public String ShouhinNo;
	public String BunruiCode;
	public String CategoryCode;
	public Date TourokuDate;
	public String SeriesName;
	public String ActorName;
	public String KaisetsuText;
	public int TeikaYen;
	public int HanbaiYen;
	public int ShiireYen;
	public String TaniText;
	public String Gazou1File;
	public String Gazou2File;

	public String BunruiName;
	public String CategoryName;

	// インスタンス変数初期化
	public void Init() {
		ShouhinCode = "";
		ShouhinName = "";
		ShouhinNo = "";
		BunruiCode = "";
		CategoryCode = "";
		TourokuDate = new Date();
		SeriesName = "";
		ActorName = "";
		KaisetsuText = "";
		TeikaYen = 0;
		HanbaiYen = 0;
		ShiireYen = 0;
		TaniText = "";
		Gazou1File = "";
		Gazou2File = "";

		BunruiName = "";
		CategoryName = "";
	}

	// 商品マスタから商品情報を読み込み
	public boolean Read(String code)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select文実行
		String sql = "select * from M_SHOUHIN where MSH_SHOUHIN_CODE='" + code + "'";
		ResultSet rs = st.executeQuery(sql);
		boolean result = rs.next();
		if (result) {
			// 取得できたらインスタンス変数に格納
			ShouhinCode = rs.getString(1).trim();
			ShouhinName = rs.getString(2).trim();
			ShouhinNo = rs.getString(3).trim();
			BunruiCode = rs.getString(4).trim();
			CategoryCode = rs.getString(5).trim();
			TourokuDate = rs.getDate(6);
			SeriesName = rs.getString(7).trim();
			ActorName = rs.getString(8).trim();
			KaisetsuText = rs.getString(9).trim();
			TeikaYen = rs.getInt(10);
			HanbaiYen = rs.getInt(11);
			ShiireYen = rs.getInt(12);
			TaniText = rs.getString(13).trim();
			Gazou1File = rs.getString(14).trim();
			Gazou2File = rs.getString(15).trim();
		}

		// データベースクローズ
		rs.close();
		st.close();

		return result;
	}

	// 商品マスタにデータ追加
	public void Store(boolean delete)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		try {
			// トランザクション開始
			db.Transaction();

			// 削除ありなら削除を実行
			if (delete) {
				String sql = "delete from M_SHOUHIN where MSH_SHOUHIN_CODE='" + ShouhinCode + "'";
				st.executeUpdate(sql);
			}

			// Insert文作成
			String sql = "insert into M_SHOUHIN values ("
				+ "'" + ShouhinCode + "',"
				+ "'" + ShouhinName + "',"
				+ "'" + ShouhinNo + "',"
				+ "'" + BunruiCode + "',"
				+ "'" + CategoryCode + "',"
				+ "'" + TourokuDate + "',"
				+ "'" + SeriesName + "',"
				+ "'" + ActorName + "',"
				+ "'" + KaisetsuText + "',"
				+ "'" + TeikaYen + "',"
				+ "'" + HanbaiYen + "',"
				+ "'" + ShiireYen + "',"
				+ "'" + TaniText + "',"
				+ "'" + Gazou1File + "',"
				+ "'" + Gazou2File + "')";

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

	// 商品マスタからデータ削除
	public void Delete()
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// delete文実行
		String sql = "delete from M_SHOUHIN where MSH_SHOUHIN_CODE='" + ShouhinCode + "'";
		st.executeUpdate(sql);
		st.close();
	}
}
