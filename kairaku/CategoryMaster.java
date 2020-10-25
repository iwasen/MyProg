//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�J�e�S���[�}�X�^�[�X�V����
//		FileName:	CategoryMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class CategoryMaster extends BaseMaster
{
	// �^�C�g���w��
	protected String GetTitle() {
		return "�J�e�S���[�}�X�^�[";
	}

	// Select���w��
	protected String GetSelectSql() {
		return "select MCT_CATEGORY_CODE,MCT_ORDER_NO,MCT_CATEGORY_NAME from M_CATEGORY order by MCT_ORDER_NO";
	}

	// Delete���w��
	protected String GetDeleteSql() {
		return "delete from M_CATEGORY";
	}

	// Insert���w��
	protected String GetInsertSql() {
		return "insert into M_CATEGORY values";
	}

	// �J�����w�b�_�w��
	protected String[] GetHeader() {
		String[] header = {"�J�e�S���[�R�[�h", "�\����", "�J�e�S���[��"};
		return header;
	}
}
