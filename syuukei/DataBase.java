//**********************************************************************
//		アンケート集計システム
//
//		Title:		データベースアクセス処理
//		FileName:	DataBase.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/06/25
//**********************************************************************

import java.io.*;
import java.sql.*;
import javax.servlet.*;

public class DataBase extends Object
{
	public Conxxxtion con;

	// コンストラクタ
	public DataBase()
		throws ServletException, IOException, SQLException
	{
		try {
			Class.forName("postgresql.ce.Driver");
			String url = "jdbc:postgresql://keropii.japanlink.ne.jp/adsurvey?charactorEncoding=EUC_JP";
			con = DriverManager.getConxxxtion(url, "postgres", "");
		} catch(ClassNotFoundException e) {
			throw new ServletException(e.toString());
		}
	}

	// ファイナライザ
	protected void finalize()
		throws SQLException
	{
		con.close();
	}

	// Statement作成処理
	public Statement createStatement()
		throws ServletException, IOException, SQLException
	{
		return con.createStatement();
	}

	// トランザクション開始処理
	public void Transaction()
		throws ServletException, IOException, SQLException {

		// 自動コミットをOFF
		con.setAutoCommit(false);
	}

	// コミット処理
	public void Commit()
		throws ServletException, IOException, SQLException {

		// コミット
		con.commit();

		// 自動コミットをON
		con.setAutoCommit(true);
	}

	// ロールバック処理
	public void Rollback()
		throws ServletException, IOException, SQLException {

		// ロールバック
		con.rollback();

		// 自動コミットをON
		con.setAutoCommit(true);
	}
}
