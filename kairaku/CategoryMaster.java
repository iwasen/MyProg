//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		カテゴリーマスター更新処理
//		FileName:	CategoryMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class CategoryMaster extends BaseMaster
{
	// タイトル指定
	protected String GetTitle() {
		return "カテゴリーマスター";
	}

	// Select文指定
	protected String GetSelectSql() {
		return "select MCT_CATEGORY_CODE,MCT_ORDER_NO,MCT_CATEGORY_NAME from M_CATEGORY order by MCT_ORDER_NO";
	}

	// Delete文指定
	protected String GetDeleteSql() {
		return "delete from M_CATEGORY";
	}

	// Insert文指定
	protected String GetInsertSql() {
		return "insert into M_CATEGORY values";
	}

	// カラムヘッダ指定
	protected String[] GetHeader() {
		String[] header = {"カテゴリーコード", "表示順", "カテゴリー名"};
		return header;
	}
}
