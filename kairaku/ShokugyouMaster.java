//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		職業マスタ更新処理
//		FileName:	ShokugyouMaster.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

public class ShokugyouMaster extends BaseMaster
{
	// タイトル指定
	protected String GetTitle() {
		return "職業マスター";
	}

	// Select文指定
	protected String GetSelectSql() {
		return "select MSG_SHOKUGYOU_CODE,MSG_ORDER_NO,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NO";
	}

	// Delete文指定
	protected String GetDeleteSql() {
		return "delete from M_SHOKUGYOU";
	}

	// Insert文指定
	protected String GetInsertSql() {
		return "insert into M_SHOKUGYOU values";
	}

	// カラムヘッダ指定
	protected String[] GetHeader() {
		String[] header = {"職業コード", "表示順", "職業名"};
		return header;
	}
}
