//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		分類マスター更新処理
//		FileName:	BunruiMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class BunruiMaster extends BaseMaster
{
	// タイトル指定
	protected String GetTitle() {
		return "商品分類マスター";
	}

	// Select文指定
	protected String GetSelectSql() {
		return "select MSB_BUNRUI_CODE,MSB_ORDER_NO,MSB_BUNRUI_NAME from M_SBUNRUI order by MSB_ORDER_NO";
	}

	// Delete文指定
	protected String GetDeleteSql() {
		return "delete from M_SBUNRUI";
	}

	// Insert文指定
	protected String GetInsertSql() {
		return "insert into M_SBUNRUI values";
	}

	// カラムヘッダ指定
	protected String[] GetHeader() {
		String[] header = {"商品分類コード", "表示順", "商品分類名"};
		return header;
	}
}
