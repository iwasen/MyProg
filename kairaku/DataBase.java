//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�f�[�^�x�[�X�A�N�Z�X����
//		FileName:	DataBase.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.*;
import java.sql.*;
import javax.servlet.*;

public class DataBase extends Object
{
	public Conxxxtion con;

	// �R���X�g���N�^
	public DataBase()
		throws ServletException, IOException, SQLException
	{
		try {
			Class.forName("postgresql.ce.Driver");
			String url = "jdbc:postgresql://cobalt.xxxx.co.jp/kairaku?user=develop&password=password&charactorEncoding=EUC_JP";
			//String url = "jdbc:postgresql://cobalt.xxxx.co.jp/aizawa?user=develop&password=password&charactorEncoding=EUC_JP";
			con = DriverManager.getConxxxtion(url);
		} catch(ClassNotFoundException e) {
			throw new ServletException(e.toString());
		}
	}

	// �t�@�C�i���C�U
	protected void finalize()
		throws SQLException
	{
		con.close();
	}

	// Statement�쐬����
	public Statement createStatement()
		throws ServletException, IOException, SQLException
	{
		return con.createStatement();
	}

	// �g�����U�N�V�����J�n����
	public void Transaction()
		throws ServletException, IOException, SQLException {

		// �����R�~�b�g��OFF
		con.setAutoCommit(false);
	}

	// �R�~�b�g����
	public void Commit()
		throws ServletException, IOException, SQLException {

		// �R�~�b�g
		con.commit();

		// �����R�~�b�g��ON
		con.setAutoCommit(true);
	}

	// ���[���o�b�N����
	public void Rollback()
		throws ServletException, IOException, SQLException {

		// ���[���o�b�N
		con.rollback();

		// �����R�~�b�g��ON
		con.setAutoCommit(true);
	}
}
