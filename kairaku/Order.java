//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		注文情報クラス
//		FileName:	Order.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.sql.*;
import java.io.IOException;
import javax.servlet.ServletException;

// オーダ情報管理クラス
public class Order
{
	// ユーザ登録情報クラス
	class User {
		String UserCode;
		String UserID;
		String Password;
		java.util.Date Touroku;
		String Furigana;
		String Shimei;
		String Seibetsu;
		int SeiNen;
		int SeiTsuki;
		int Sexxx;
		int Nenrei;
		boolean Over18Flag;
		java.util.Date Over18Date;
		String Shokugyou;
		String Zip;
		String Address;
		String Tatemono;
		String TelNo;
		String FaxNo;
		String EMail;
		boolean MailingList;
		String Url;
	};

	// アンケート結果クラス
	class Enquete {
		String EnqueteCode;
		String UserCode;
		String Setsumon1;
		String Setsumon2;
		String Setsumon3;
		String Setsumon3Koumoku;
		String Setsumon4;
		String Setsumon4Koumoku;
		String Setsumon5Title;
		String Setsumon4Url;
	};

	// 受注情報クラス
	class Juchu {
		String JuchuCode;
		java.util.Date JuchuDate;
		String UserCode;
		boolean Over18Flag;
		String Zip;
		String Address;
		String Tatemono;
		String TelNo;
		int Total;
		String Shiharai;
	};

	// 受注明細情報クラス
	class Meisai {
		String ShouhinCode;
		String MeisaiCode;
		String ShouhinNo;
		String ShouhinName;
		String Tani;
		int HanbaiKakaku;
		int Kosuu;
		int Shoukei;
	};

	// インスタンス変数
	public User user = new User();
	public Enquete enquete = new Enquete();
	public Juchu juchu = new Juchu();
	public Vector meisai = new Vector();
	public boolean AuthError = false;
	public String bunrui;

	// ユーザＩＤ存在チェック処理
	public boolean UserIDCheck(String userID)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// ユーザマスタからユーザＩＤで検索
		String sql = "select * from M_USER where MUS_USER_ID='" + userID + "'";
		ResultSet rs = st.executeQuery(sql);

		// 結果を保存
		boolean result = rs.next();

		// データベースクローズ
		rs.close();
		st.close();

		return result;
	}

	// ユーザ認証処理
	public boolean AuthenticationCheck(String userID, String password)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// ユーザマスタからユーザＩＤとパスワードを指定して検索
		String sql = "select * from M_USER where MUS_USER_ID='" + userID + "' and MUS_PASSWORD_TEXT='" + password + "'";
		ResultSet rs = st.executeQuery(sql);

		// あればユーザ登録情報を取得
		boolean result = rs.next();
		if (result) {
			user.UserCode = rs.getString(1);
			user.UserID = rs.getString(2);
			user.Password = rs.getString(3);
			user.Touroku = rs.getDate(4);
			user.Furigana = rs.getString(5);
			user.Shimei = rs.getString(6);
			user.Seibetsu = rs.getString(7);
			user.SeiNen = rs.getInt(8);
			user.SeiTsuki = rs.getInt(9);
			user.Sexxx = rs.getInt(10);
			user.Nenrei = rs.getInt(11);
			user.Over18Flag = rs.getBoolean(12);
			user.Over18Date = rs.getDate(13);
			user.Shokugyou = rs.getString(14);
			user.Zip = rs.getString(15);
			user.Address = rs.getString(16);
			user.Tatemono = rs.getString(17);
			user.TelNo = rs.getString(18);
			user.FaxNo = rs.getString(19);
			user.EMail = rs.getString(20);
			user.MailingList = rs.getBoolean(21);
			user.Url = rs.getString(22);
		}

		// データベースクローズ
		rs.close();
		st.close();

		return result;
	}

	// 受注明細数取得
	public int GetMeisaiNum() {
		return meisai.size();
	}

	// 受注明細を追加
	public void AddMeisai(String code, int kosuu)
				throws ServletException, IOException, SQLException {

		Enumeration values = meisai.elements();
		Meisai item;

		// すでに同じ商品コードがあれば個数を置き換える
		while(values.hasMoreElements()) {
			item = (Meisai)values.nextElement();
			if (item.ShouhinCode.equals(code)) {
				item.Kosuu = kosuu;
				item.Shoukei = item.HanbaiKakaku * item.Kosuu;
				return;
			}
		}

		// 無ければ明細を新規に作成
		item = new Meisai();

		// 商品名などを商品マスタから取得
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select MSH_SHOUHIN_NO,MSH_SHOUHIN_NAME,MSH_TANI_TEXT,MSH_HANBAI_YEN"
				+ " from M_SHOUHIN"
				+ " where MSH_SHOUHIN_CODE='" + code + "'";
		ResultSet rs = st.executeQuery(sql);
		if (rs.next()) {
			item.ShouhinCode = code;
			item.ShouhinNo = rs.getString(1);
			item.ShouhinName = rs.getString(2);
			item.Tani = rs.getString(3);
			item.HanbaiKakaku = rs.getInt(4);
			item.Kosuu = kosuu;
			item.Shoukei = item.HanbaiKakaku * item.Kosuu;
		}
		rs.close();
		st.close();

		// 明細情報を追加
		meisai.addElement(item);
	}

	// インデックスによる受注明細情報取得
	public Meisai GetMeisai(int index) {
		return (Meisai)meisai.elementAt(index);
	}

	// 明細情報全削除
	public void DeleteMeisai() {
		meisai.removeAllElements();
	}
}
