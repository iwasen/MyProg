//**********************************************************************
//		MCDB:�V��:����BOOK TIMES �A���P�[�g���W�V�X�e��
//
//		Title:		�f�[�^�x�[�X�A�N�Z�X����
//		FileName:	DataBase.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/19
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
			String url = "jdbc:postgresql://keropii.japanlink.ne.jp/booktimes?charactorEncoding=EUC_JP";
			con = DriverManager.getConxxxtion(url, "postgres", "");
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
