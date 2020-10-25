//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�E�ƃ}�X�^�X�V����
//		FileName:	ShokugyouMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class ShokugyouMaster extends BaseMaster
{
	// �^�C�g���w��
	protected String GetTitle() {
		return "�E�ƃ}�X�^�[";
	}

	// Select���w��
	protected String GetSelectSql() {
		return "select MSG_SHOKUGYOU_CODE,MSG_ORDER_NO,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NO";
	}

	// Delete���w��
	protected String GetDeleteSql() {
		return "delete from M_SHOKUGYOU";
	}

	// Insert���w��
	protected String GetInsertSql() {
		return "insert into M_SHOKUGYOU values";
	}

	// �J�����w�b�_�w��
	protected String[] GetHeader() {
		String[] header = {"�E�ƃR�[�h", "�\����", "�E�Ɩ�"};
		return header;
	}
}
