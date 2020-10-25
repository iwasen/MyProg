//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�R���g���[���}�X�^�[����N���X
//		FileName:	Contrxxxaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.text.DecimalFormat;
import java.io.IOException;
import java.sql.*;
import javax.servlet.ServletException;

// �R���g���[���}�X�^�[����p�N���X
public class Contrxxxaster
{
	// �V�������i�R�[�h�擾����
	public String GetNewShouhinCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_SHOUHIN_CODE");

		DecimalFormat form = new DecimalFormat("00000");
		return form.format(code);
	}

	// �V�������[�U�R�[�h�擾����
	public String GetNewUserCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_USER_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// �V�����A���P�[�g�R�[�h�擾����
	public String GetNewEnqueteCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_ENQUETE_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// �V�����󒍃R�[�h�擾����
	public String GetNewJuchuCode()
			throws ServletException, IOException, SQLException {

		int code = GetNewCode("MCN_JUCHU_CODE");

		DecimalFormat form = new DecimalFormat("000000");
		return form.format(code);
	}

	// �V�����R�[�h�擾���ʏ���
	private int GetNewCode(String name)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select " + name + " from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// �擾�ł��Ȃ��������O�𔭐�������
			throw new ServletException("Not found Control master");
		}

		// ���̃R�[�h���擾
		int icode = rs.getInt(1);

		// �R�[�h���{�P����
		sql = "update M_CONTROL set " + name + "=" + (icode + 1);
		st.executeUpdate(sql);

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return icode;
	}

	// ���i�R�[�h�L�����Z������
	public void CancelShouhinCode(String code)
			throws ServletException, IOException, SQLException {
		CancelCode("MCN_SHOUHIN_CODE", code);
	}

	// ���[�U�[�R�[�h�L�����Z������
	public void CancelUserCode(String code)
			throws ServletException, IOException, SQLException {
		CancelCode("MCN_USER_CODE", code);
	}

	// �R�[�h�L�����Z�����ʏ���
	private void CancelCode(String name, String code)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select " + name + " from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// �擾�ł��Ȃ��������O�𔭐�������
			throw new ServletException("Not found Control master");
		}

		// ���̃R�[�h���擾
		int icode = rs.getInt(1);

		// ���O�̃L�����Z����������R�[�h���ЂƂ߂�
		if (icode == Integer.parseInt(code) + 1) {
			sql = "update M_CONTROL set " + name + "=" + (icode - 1);
			st.executeUpdate(sql);
		}

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}

	// �Ǘ��җp�h�c�^�p�X���[�h�`�F�b�N
	public boolean CheckAdmin(String id, String password)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select MCN_ADMIN_ID,MCN_ADMIN_PASS from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// �擾�ł��Ȃ��������O�𔭐�������
			throw new ServletException("Control master read failed.");
		}

		// �h�c�ƃp�X���[�h����v���Ă��邩�`�F�b�N
		boolean result = id.equals(rs.getString("MCN_ADMIN_ID").trim())
				&& password.equals(rs.getString("MCN_ADMIN_PASS").trim());

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return result;
	}
	// ���[�����M���N���X
	class MailInfo {
		String MailServer;
		String ToAddress;
		String CcAddress;
		String FromAddress;
	}
	// ���[�����M���擾
	public MailInfo GetMailInfo()
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// select�����s
		String sql = "select * from M_CONTROL";
		ResultSet rs = st.executeQuery(sql);
		if (!rs.next()) {
			// �擾�ł��Ȃ��������O�𔭐�������
			throw new ServletException("Not found Control master");
		}

		// ���[�����C���X�^���X��
		MailInfo mailinfo = new MailInfo();

		// ���[�������C���X�^���X�������N���X�ɃZ�b�g
		mailinfo.MailServer = rs.getString("MCN_MAIL_SERVER");
		mailinfo.ToAddress = rs.getString("MCN_MAIL_TO");
		mailinfo.CcAddress = rs.getString("MCN_MAIL_CC");
		mailinfo.FromAddress = rs.getString("MCN_MAIL_FROM");

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return mailinfo;
	}
}
