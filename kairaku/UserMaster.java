//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�[�}�X�^���N���X
//		FileName:	UserMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.util.Date;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// ���[�U�[�}�X�^�Ǘ��N���X
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

	// �C���X�^���X�ϐ�������
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

	// ���[�U�[�}�X�^���烆�[�U�[����ǂݍ���
	public boolean Read(String code)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select * from M_USER where MUS_USER_CODE='" + code + "'";
		ResultSet rs = st.executeQuery(sql);
		boolean result = rs.next();
		if (result) {
			// �擾�ł�����C���X�^���X�ϐ��Ɋi�[
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

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return result;
	}

	// ���[�U�[�}�X�^�Ƀf�[�^�ǉ�
	public void Store(boolean delete)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		try {
			// �g�����U�N�V�����J�n
			db.Transaction();

			// �폜����Ȃ�폜�����s
			if (delete) {
				String sql = "delete from M_USER where MUS_USER_CODE='" + UserCode + "'";
				st.executeUpdate(sql);
			}

			// Insert���쐬
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

	// ���[�U�[�}�X�^����f�[�^�폜
	public void Delete()
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// delete�����s
		String sql = "delete from M_USER where MUS_USER_CODE='" + UserCode + "'";
		st.executeUpdate(sql);
		st.close();
	}
}
