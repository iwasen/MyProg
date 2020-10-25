//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�������N���X
//		FileName:	Order.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.sql.*;
import java.io.IOException;
import javax.servlet.ServletException;

// �I�[�_���Ǘ��N���X
public class Order
{
	// ���[�U�o�^���N���X
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

	// �A���P�[�g���ʃN���X
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

	// �󒍏��N���X
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

	// �󒍖��׏��N���X
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

	// �C���X�^���X�ϐ�
	public User user = new User();
	public Enquete enquete = new Enquete();
	public Juchu juchu = new Juchu();
	public Vector meisai = new Vector();
	public boolean AuthError = false;
	public String bunrui;

	// ���[�U�h�c���݃`�F�b�N����
	public boolean UserIDCheck(String userID)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// ���[�U�}�X�^���烆�[�U�h�c�Ō���
		String sql = "select * from M_USER where MUS_USER_ID='" + userID + "'";
		ResultSet rs = st.executeQuery(sql);

		// ���ʂ�ۑ�
		boolean result = rs.next();

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return result;
	}

	// ���[�U�F�؏���
	public boolean AuthenticationCheck(String userID, String password)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// ���[�U�}�X�^���烆�[�U�h�c�ƃp�X���[�h���w�肵�Č���
		String sql = "select * from M_USER where MUS_USER_ID='" + userID + "' and MUS_PASSWORD_TEXT='" + password + "'";
		ResultSet rs = st.executeQuery(sql);

		// ����΃��[�U�o�^�����擾
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

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return result;
	}

	// �󒍖��א��擾
	public int GetMeisaiNum() {
		return meisai.size();
	}

	// �󒍖��ׂ�ǉ�
	public void AddMeisai(String code, int kosuu)
				throws ServletException, IOException, SQLException {

		Enumeration values = meisai.elements();
		Meisai item;

		// ���łɓ������i�R�[�h������Ό���u��������
		while(values.hasMoreElements()) {
			item = (Meisai)values.nextElement();
			if (item.ShouhinCode.equals(code)) {
				item.Kosuu = kosuu;
				item.Shoukei = item.HanbaiKakaku * item.Kosuu;
				return;
			}
		}

		// ������Ζ��ׂ�V�K�ɍ쐬
		item = new Meisai();

		// ���i���Ȃǂ����i�}�X�^����擾
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

		// ���׏���ǉ�
		meisai.addElement(item);
	}

	// �C���f�b�N�X�ɂ��󒍖��׏��擾
	public Meisai GetMeisai(int index) {
		return (Meisai)meisai.elementAt(index);
	}

	// ���׏��S�폜
	public void DeleteMeisai() {
		meisai.removeAllElements();
	}
}
