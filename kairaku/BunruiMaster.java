//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���ރ}�X�^�[�X�V����
//		FileName:	BunruiMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class BunruiMaster extends BaseMaster
{
	// �^�C�g���w��
	protected String GetTitle() {
		return "���i���ރ}�X�^�[";
	}

	// Select���w��
	protected String GetSelectSql() {
		return "select MSB_BUNRUI_CODE,MSB_ORDER_NO,MSB_BUNRUI_NAME from M_SBUNRUI order by MSB_ORDER_NO";
	}

	// Delete���w��
	protected String GetDeleteSql() {
		return "delete from M_SBUNRUI";
	}

	// Insert���w��
	protected String GetInsertSql() {
		return "insert into M_SBUNRUI values";
	}

	// �J�����w�b�_�w��
	protected String[] GetHeader() {
		String[] header = {"���i���ރR�[�h", "�\����", "���i���ޖ�"};
		return header;
	}
}
