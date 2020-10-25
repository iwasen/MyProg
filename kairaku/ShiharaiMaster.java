//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�x�����@�}�X�^�X�V����
//		FileName:	ShiharaiMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class ShiharaiMaster extends BaseMaster
{
	// �^�C�g���w��
	protected String GetTitle() {
		return "�x�����@�}�X�^�[";
	}

	// Select���w��
	protected String GetSelectSql() {
		return "select MSI_SHIHARAI_CODE,MSI_ORDER_NO,MSI_SHIHARAI_NAME from M_SHIHARAI order by MSI_ORDER_NO";
	}

	// Delete���w��
	protected String GetDeleteSql() {
		return "delete from M_SHIHARAI";
	}

	// Insert���w��
	protected String GetInsertSql() {
		return "insert into M_SHIHARAI values";
	}

	// �J�����w�b�_�w��
	protected String[] GetHeader() {
		String[] header = {"�x�����@�R�[�h", "�\����", "�x�����@��"};
		return header;
	}
}
