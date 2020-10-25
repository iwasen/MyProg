//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		支払方法マスタ更新処理
//		FileName:	ShiharaiMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class ShiharaiMaster extends BaseMaster
{
	// タイトル指定
	protected String GetTitle() {
		return "支払方法マスター";
	}

	// Select文指定
	protected String GetSelectSql() {
		return "select MSI_SHIHARAI_CODE,MSI_ORDER_NO,MSI_SHIHARAI_NAME from M_SHIHARAI order by MSI_ORDER_NO";
	}

	// Delete文指定
	protected String GetDeleteSql() {
		return "delete from M_SHIHARAI";
	}

	// Insert文指定
	protected String GetInsertSql() {
		return "insert into M_SHIHARAI values";
	}

	// カラムヘッダ指定
	protected String[] GetHeader() {
		String[] header = {"支払方法コード", "表示順", "支払方法名"};
		return header;
	}
}
