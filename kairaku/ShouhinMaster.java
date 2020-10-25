//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���i�}�X�^���N���X
//		FileName:	ShouhinMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.Date;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// ���i�}�X�^�Ǘ��N���X
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

	// �C���X�^���X�ϐ�������
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

	// ���i�}�X�^���珤�i����ǂݍ���
	public boolean Read(String code)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select * from M_SHOUHIN where MSH_SHOUHIN_CODE='" + code + "'";
		ResultSet rs = st.executeQuery(sql);
		boolean result = rs.next();
		if (result) {
			// �擾�ł�����C���X�^���X�ϐ��Ɋi�[
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

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return result;
	}

	// ���i�}�X�^�Ƀf�[�^�ǉ�
	public void Store(boolean delete)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		try {
			// �g�����U�N�V�����J�n
			db.Transaction();

			// �폜����Ȃ�폜�����s
			if (delete) {
				String sql = "delete from M_SHOUHIN where MSH_SHOUHIN_CODE='" + ShouhinCode + "'";
				st.executeUpdate(sql);
			}

			// Insert���쐬
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

			// Insert�����s
			st.executeUpdate(sql);
			st.close();

			// ����Ȃ�R�~�b�g
			db.Commit();
		} catch (SQLException e) {
			// �G���[�Ȃ烍�[���o�b�N
			db.Rollback();
			throw e;
		}
	}

	// ���i�}�X�^����f�[�^�폜
	public void Delete()
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// delete�����s
		String sql = "delete from M_SHOUHIN where MSH_SHOUHIN_CODE='" + ShouhinCode + "'";
		st.executeUpdate(sql);
		st.close();
	}
}
