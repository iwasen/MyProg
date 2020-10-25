// LixxxtrlEx.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "LixxxtrlEx.h"
#include "CInPlaceList.h"
#include "InPlaceEdit.h"

//#include <comdef.h>
#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx

CLixxxtrlEx::CLixxxtrlEx()
{
	// 選択タイプ
	m_nSelectType			= 1;	// 複数行選択

	// ドラッグ＆ドロップ
	m_bPermissionDragDrop	= FALSE;
	m_bDragging				= FALSE;
	m_nMoveCount			= 0;

	//ドラッグ中のイメージリストを作成する
	m_pimageListDrag.Create(IDB_BITMAP_POST_DRAG, 40, 1, RGB(100,100,100));

	// キー入力
	m_nEditRow	= -99;
	m_nEditCol	= -99;
// Modify ... ( ADD )
	m_bEditShow	 = 0;
// By Y.Itabashi (xxxxx) 2007.03.05

}

CLixxxtrlEx::~CLixxxtrlEx()
{
}


BEGIN_MESSAGE_MAP(CLixxxtrlEx, CLixxxtrl)
	//{{AFX_MSG_MAP(CLixxxtrlEx)
	ON_NOTIFY_REFLECT(LVN_BEGINDRAG, OnBeginDrag)
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	ON_WM_VSCROLL()
	ON_NOTIFY_REFLECT(LVN_ENDLABELEDIT, OnEndlabeledit)
	ON_WM_LBUTTONDOWN()
	ON_WM_HSCROLL()
	ON_NOTIFY_REFLECT(LVN_BEGINLABELEDIT, OnBeginlabeledit)
	ON_NOTIFY_REFLECT(NM_CLICK, OnClick)
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_NOTIFY_REFLECT(NM_DBLCLK, OnDblclk)
	ON_NOTIFY_REFLECT(NM_CUSTOMDRAW, OnCustomDraw)
	ON_NOTIFY_REFLECT(LVN_DELETEITEM, OnDeleteitem)
	ON_WM_CAPTURECHANGED()
	ON_WM_SETFOCUS()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetColumnAction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CLixxxtrlExの列毎の処理可／不可フラグを設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nCol		[I] 列番号
//		int			nAct		[I] 設定するフラグタイプ
//										1 : ソート可／不可
//										2 : 検索可／不可
//		BOOL		bFlg		[I] 設定フラグ
//										TRUE : 可
//										FALSE : 不可
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnAction(int nCol, int nAct, BOOL bFlg)
{
	//-------------------------------------------------------
	// 入力引数チェック
	//-------------------------------------------------------
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if ( nCol < 0 || nCol > nColumnCount) return;

	//-------------------------------------------------------
	// 値設定
	//-------------------------------------------------------
	switch (nAct) {

	case eCOLUMNACTTYPE_SORT:
		// ソート可／不可設定
		M_aColumnActFlag[nCol].bSort = bFlg;
		break;

	case eCOLUMNACTTYPE_SEARCH:
		// 検索可／不可設定
		M_aColumnActFlag[nCol].bSearch = bFlg;
		break;
	}
}


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetColumnType
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CLixxxtrlExの列毎のデータタイプ／ユーザー入力タイプを設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nCol		[I] 列番号
//		int			nDataType	[I] 列のデータタイプ
//										0 : 文字列
//										1 : 整数
//										2 : 実数
//										3 : ＯＮ／ＯＦＦチェック
//		int			nInputType	[I] ユーザー入力タイプ
//										0 : ユーザー入力不可
//										1 : エディットボックス入力
//										2 : ＯＮ／ＯＦＦチェック
//										3 : コンボボックス選択
//									
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		NG : ( データタイプが文字列もしくは数値 ) かつ、入力タイプがＯＮ／ＯＦＦチェック
//		NG : データタイプがＯＮ／ＯＦＦにもかかわらず入力タイプがＯＮ／ＯＦＦチェック以外
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnType(int nCol, int nDataType, int nInputType)
{
	//-------------------------------------------------------
	// 入力引数チェック
	//-------------------------------------------------------
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if ( nCol < 0 || nCol > nColumnCount) return;

	if ( ( nDataType == eITEMTYPE_STRING
		|| nDataType == eITEMTYPE_INUM
		|| nDataType == eITEMTYPE_RNUM )
		&& nInputType == eINPUTTYPE_ONOFFCHECK ) return;

	if ( nDataType == eITEMTYPE_ONOFFCHECK
		&& nInputType != eINPUTTYPE_ONOFFCHECK ) return;

	//-------------------------------------------------------
	// 値設定
	//-------------------------------------------------------
	M_aColumnActFlag[nCol].nItemType = nDataType;
	M_aColumnActFlag[nCol].nInputType = nInputType;

	//-------------------------------------------------------
	// 書式初期化
	//-------------------------------------------------------
	switch ( nDataType ) {
	case eITEMTYPE_STRING:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%s" );
		break;
	case eITEMTYPE_INUM:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%d" );
		break;
	case eITEMTYPE_RNUM:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%lf" );
		break;
	case eITEMTYPE_ONOFFCHECK:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%c" );
		break;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetSelectType
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CLixxxtrlExの単一／複数行選択設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nSelectType	[I] 選択タイプ
//										0 : 単一行選択
//										1 : 複数行選択
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		属性値だけでなくCLixxxtrl::ModifyStyleにて振る舞いも変更
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
void CLixxxtrlEx::SetSelectType(int nSelectType)
{
	if (nSelectType != eSELECTTYPE_SINGLE && nSelectType != eSELECTTYPE_MULTI) return;

	m_nSelectType = nSelectType;
	switch (nSelectType) {

	case eSELECTTYPE_SINGLE:
		ModifyStyle( NULL, LVS_SINGLESEL );
		break;
	case eSELECTTYPE_MULTI:
		ModifyStyle( LVS_SINGLESEL, NULL );
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetValue
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイテムセット
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		int			nCol			[I] 列
//		LPCTSTR		pValue			[I]	文字列
//	{	int			nValue			[I] 整数	}
//	{	double		fValue			[I] 実数	}
//  {   char		cValue			[I] ＯＮ／ＯＦＦ }
//										"1" : ＯＮ
//										""  : ＯＦＦ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int							[O] ステータス
//										> 0 : 正常終了、挿入した行番号
//										 -1 : セットに失敗
//										 -2 : セットする箇所のデータタイプの型が異なる
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		サブアイテムのセット(nCol>0)の場合、InsertItemではなくSetItemを行う
//		動作結果はInsertItem、SetItemに準拠
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
// 文字列セット
int CLixxxtrlEx::SetValue(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

//	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_STRING ) return -2;
	CString sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, pValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= (LPTSTR)(LPCTSTR)sValue;
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= (LPTSTR)(LPCTSTR)sValue;
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// 整数セット
int CLixxxtrlEx::SetValue(int nRow, int nCol, int nValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_INUM ) return -2;

	CString	sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, nValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// 実数セット
int CLixxxtrlEx::SetValue(int nRow, int nCol, double fValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_RNUM ) return -2;

	CString	sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, fValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// ＯＮ／ＯＦＦセット
int CLixxxtrlEx::SetValue(int nRow, int nCol, bool bValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_ONOFFCHECK ) return -2;

	CString	sValue;
	if ( bValue )
		sValue = "*";
	else
		sValue = "";

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// 文字列セット
int CLixxxtrlEx::SetValue2(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= (LPTSTR)pValue;
		item.cchTextMax	= strlen(pValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= (LPTSTR)pValue;
		item.cchTextMax	= strlen(pValue);

		return SetItem(&item);
	}
	return -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::ModifyValue
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイテム変更
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		int			nCol			[I] 列
//		LPCTSTR		pValue			[I]	文字列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int							[O] ステータス
//										> 0 : 正常終了、挿入した行番号
//										 -1 : セットに失敗
//										 -2 : セットする箇所のデータタイプの型が異なる
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		文字列をカラムのデータタイプに合わせて変換してセット
//		書式変更の必要な場合使用
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
// 文字列セット
int CLixxxtrlEx::ModifyValue(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	CString sValue;
	switch ( M_aColumnActFlag[nCol].nItemType ) {
	case eITEMTYPE_STRING:
		sValue.Format( M_aColumnActFlag[nCol].sDispFormat, pValue );
		break;

	case eITEMTYPE_INUM:
// Modify ... ( CHANGE )
		if( strlen( pValue ) == 0 ){
			sValue = pValue;
		}else{
			int nValue;
			sscanf( pValue, "%d", &nValue );
			sValue.Format( M_aColumnActFlag[nCol].sDispFormat, nValue );
		}
// By Y.Itabashi (xxxxx) 2007.02.12
		break;

	case eITEMTYPE_RNUM:
// Modify ... ( CHANGE )
		if( strlen( pValue ) == 0 ){
			sValue = pValue;
		}else{
			double fValue;
			sscanf( pValue, "%lf", &fValue );
			sValue.Format( M_aColumnActFlag[nCol].sDispFormat, fValue );
		}
// By Y.Itabashi (xxxxx) 2007.02.12
		break;
	}
	LVITEM	item;
	item.mask		= LVIF_TEXT;
	item.iItem		= nRow;
	item.iSubItem	= nCol;
	item.pszText	= sValue.GetBuffer(strlen(sValue));
	item.cchTextMax	= strlen(sValue);

	return SetItem(&item);
}

// コンボボックスデータセット
int CLixxxtrlEx::SetComboList(int nRow, int nCol, CStringList &lstItems, int nIndex)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nInputType != eINPUTTYPE_COMBOBOX ) return -2;

	// コンボボックスの作成
	(CComboBox*)this->ShowInPlaceList( nRow, nCol, lstItems, nIndex );

	return -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::GetValue
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイテム取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		int			nCol			[I] 列
//		CSting&		sValue			[O]	文字列
//	{	int&		nValue			[O] 整数	}
//	{	double&		fValue			[O] 実数	}
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int							[O] ステータス
//										> 0 : 正常終了、挿入した行番号
//										 -1 : 取得に失敗
//										 -2 : 取得する箇所のデータタイプの型が異なる
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) 新規作成
//*****************************************************************************************************
// 文字列取得
int CLixxxtrlEx::GetValue(int nRow, int nCol, CString &sValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_STRING ) return -2;

	sValue = GetItemText(nRow, nCol);

	return 0;
}

// 整数取得
int CLixxxtrlEx::GetValue(int nRow, int nCol, int &nValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_INUM ) return -2;

	CString sValue = GetItemText(nRow, nCol);
// Modify ... ( CHANGE )
	if( sValue.GetLength() > 0 )
		sscanf(sValue, "%d", &nValue);
	else
		nValue = 0;
//	sscanf(sValue, "%d", &nValue);
// By Y.Itabashi (xxxxx) 2007.02.01

	return 0;
}

int CLixxxtrlEx::GetValue(int nRow, int nCol, double &fValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_RNUM ) return -2;


	CString sValue = GetItemText(nRow, nCol);
// Modify ... ( CHANGE )
	if( sValue.GetLength() > 0 )
		sscanf(sValue, "%lf", &fValue);
	else
		fValue = 0;
//	sscanf(sValue, "%lf", &fValue);
// By Y.Itabashi (xxxxx) 2007.02.01

	return 0;
}

// ＯＮ／ＯＦＦ取得
int CLixxxtrlEx::GetValue(int nRow, int nCol, bool &bValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_ONOFFCHECK ) return -2;

	CString	sValue = GetItemText(nRow, nCol);
	if ( sValue == "") 
		bValue = false;
	else if ( sValue == "*" )
		bValue = true;
	else
		return -1;

	return 0;
}

/////////////////////////////////////////////////////////////////////////////
//
// CLixxxtrlEx メッセージ ハンドラ
//
/////////////////////////////////////////////////////////////////////////////


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnBeginDrag
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイテムのドラッグ開始時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		要修正：非連続な複数選択アイテム対応
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnBeginDrag(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iItem < 0 || pNMListView->iSubItem < 0 ) return;

	GetParent()->SendMessage(WM_BEGIN_DRAG, (WPARAM)m_hWnd, (LPARAM)0);

	if ( m_bPermissionDragDrop ) {		// ドラッグ＆ドロップ可か？

		CPoint          ptItem, ptAction, ptImage;

		int count = GetSelectedCount();
		POSITION pos = GetFirstSelectedItemPosition();

		m_bDragging = TRUE;
		int  i_index;
// Modify ... ( CHANGE )
		for( int i = 0; pos; i++ ){
//		for( int i = 0; i < count; i++ ){
// By Y.Itabshi (xxxxx) 2007.02.07

			// 要修正：連続していなくとも選択可　→　ドロップ時に連続して挿入

			i_index = GetNextSelectedItem( pos );
			/*
			if( i > 0 ) {	//ドラッグできるのは連続している選択部分だけとする
				if( (i_index-1) != m_iItemDrag[i-1] ) {	//連続していない部分は移動対象としない
					break;
				}
			}
			*/
			m_iItemDrag[i] = i_index;	//選択されているインデックスを取得
			m_nMoveCount++;

			if( i <= 0 ) {
			ptAction = pNMListView->ptAction;
			GetItemPosition(m_iItemDrag[i], &ptItem);  // ptItem is relative to (0,0) and not the view origin
			GetOrigin(&m_ptOrigin);

			//ドラッグ中のイメージリストを作成する
			m_sizeDelta[i] = ptAction - ptImage;   // difference between cursor pos and image pos
			m_ptHotSpot = ptAction - ptItem + m_ptOrigin;  // calculate hotspot for the cursor
			m_pimageListDrag.BeginDrag(i, CPoint(8, 8));
			ptAction -= m_sizeDelta[i];

// Modify ... ( CHANGE )
			m_pDropWnd = this;

			m_pimageListDrag.DragEnter(GetParent(), ptAction);
//			m_pimageListDrag.DragEnter(this, ptAction);
// By Y.Itabashi (xxxxx) 2007.02.08
			m_pimageListDrag.DragShowNolock(TRUE);  // lock updates and show drag image
			m_pimageListDrag.SetDragCursorImage(i, m_ptHotSpot);  // define the hot spot for the new cursor image
			m_pimageListDrag.DragMove(ptAction);  // move image to overlap original icon

			SetCapture();
			}
		}
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		ドラッグ＆ドロップ処理
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnMouseMove(UINT nFlags, CPoint point) 
{
    if( m_bDragging )
    {
        m_ptDropPoint = point;
        ClientToScreen(&m_ptDropPoint);
    
        //MOVE THE DRAG IMAGE
// Modify ... ( CHANGE )
		CPoint pointParent(m_ptDropPoint);
		CRect rect;
		GetParent()->GetWindowRect(rect);
		pointParent -= rect.TopLeft();
        m_pimageListDrag.DragMove(pointParent);
//        m_pimageListDrag.DragMove(point);
// By Y.Itabashi (xxxxx) 2007.02.07
    
        //TEMPORARILY UNLOCK WINDOW UPDATES
        m_pimageListDrag.DragShowNolock(FALSE);                
    
        //CONVERT THE DROP POINT TO CLIENT CO-ORDIANTES
        m_pDropWnd = WindowFromPoint(m_ptDropPoint);
        m_pDropWnd->ScreenToClient(&m_ptDropPoint);

        //SCROLL VIEW IF xxxESSARY
		if (m_pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl))) {
			int iOverItem = HitTest(point);
			int iTopItem = GetTopIndex();
			int iBottomItem = iTopItem + GetCountPerPage() - 1;
			if (iOverItem == iTopItem && iTopItem != 0)
			{
				EnsureVisible(iOverItem - 1, false);
				::UpdateWindow(this->m_hWnd);
			}
			else if (iOverItem == iBottomItem && iBottomItem != (GetItemCount() - 1))
			{
				EnsureVisible(iOverItem + 1, false);
			   ::UpdateWindow(this->m_hWnd);
			}

// Modify ... ( ADD )
			UINT uFlags;
			CLixxxtrl* pList = (CLixxxtrl*)m_pDropWnd;

			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}

			m_nDropIndex = ((CLixxxtrl*)m_pDropWnd)->HitTest(m_ptDropPoint, &uFlags);
			if( ((CLixxxtrl*)m_pDropWnd)->GetItemCount() == 0 )
				m_nDropIndex = -1;

			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, LVIS_DROPHILITED, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}
// By Y.Itabashi (xxxxx) 2007.02.07
		}

        //LOCK WINDOW UPDATES
        m_pimageListDrag.DragShowNolock(TRUE);
    }	
//	CLixxxtrl::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnButtonUp
//		CLixxxtrlEx::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス左ボタンを離した時の処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		ドラッグ＆ドロップ処理
//		要修正：挿入位置の上に挿入すべし
//		要修正：非連続な複数選択アイテムのドロップ時処理　→　連続して挿入
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnLButtonUp(UINT nFlags, CPoint point) 
{
	OnButtonUp(point);
	CLixxxtrl::OnLButtonUp(nFlags, point);
}

void CLixxxtrlEx::OnButtonUp(CPoint point) 
{
	if (m_bDragging)  // end of the drag operation
	{
        //RELEASE THE MOUSE CAPTURE AND END THE DRAGGING
        ::ReleaseCapture();
// Modify ... ( CHANGE )
        m_pimageListDrag.DragLeave(GetParent());
//        m_pimageListDrag.DragLeave(GetDesktopWindow());
// By Y.Itabashi (xxxxx) 2007.02.07
        m_pimageListDrag.EndDrag();

		// リスト外でドロップされた場合は処理取り消し
// Modify ... ( DELETE )
/*		CRect	listRect;
		GetClientRect(&listRect);
		if ( !(listRect.PtInRect( point )) ) {
			m_nMoveCount = 0;
			return;
		}*/
// By Y.Itabashi (xxxxx) 2007.02.07

        //GET THE WINDOW UNDER THE DROP POINT
        CPoint pt(point);
        ClientToScreen(&pt);
        m_pDropWnd = WindowFromPoint(pt);

// Modify ... ( ADD )
		CWnd *pWnd = m_pDropWnd->GetParent();
		if ( m_pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl)) )
			DropItemOnList((CLixxxtrlEx*)m_pDropWnd);
		else if ( m_pDropWnd->IsKindOf(RUNTIME_CLASS(CHeaderCtrl)) ){
			m_pDropWnd = pWnd;
			m_nDropIndex = -2;		// HEADER用コード
			DropItemOnList((CLixxxtrlEx*)m_pDropWnd);
		}
// By Y.Itabashi (xxxxx) 2007.02.07

		m_nMoveCount = 0;

		GetParent()->SendMessage(WM_DRAG_DROP, (WPARAM)m_hWnd, (LPARAM)m_pDropWnd->m_hWnd);

        m_bDragging = FALSE;
	}	
//	CLixxxtrl::OnLButtonUp(nFlags, point);
}

void CLixxxtrlEx::DropItemOnList(CLixxxtrlEx* pDropList)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();

	CString     cstr;
	int			j;
	int i = 0;
	// The drop target's sub-item text is replaced by the dragged item's
	// main text

// Modify ... ( CHANGE )
//	if( pDropList->GetItemCount() == 0 || m_nDropIndex == -1 )
//		return;

	pDropList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
/*	m_ptDropPoint.y += 10;
	m_ptDropPoint.x = 0;//allows dropping to right of last column
	m_nDropIndex = HitTest(m_ptDropPoint);*/
// By Y.Itabashi (xxxxx) 2007.02.07

// Modify ... ( CHANGE )
	if( m_nDropIndex != m_iItemDrag[i] || pDropList != this ) {
//	if( m_nDropIndex != m_iItemDrag[i] ) {

		// 選択位置の後ろに入れる
		if( m_nDropIndex != -2 && m_nDropIndex < 0 )		//移動先が最終行の場合、最終行番号をセットしなおす
			m_nDropIndex = pDropList->GetItemCount();
		else if( pDropList->GetItemCount() < m_nDropIndex )	//移動先が最終行を超えるの場合、最終行番号をセットしなおす
			m_nDropIndex = pDropList->GetItemCount();
		else if( m_nDropIndex < 0 )							//移動先が先頭の場合、先頭行番号をセットしなおす
			m_nDropIndex = 0;
		else
			m_nDropIndex = m_nDropIndex + 1;

		for( i = 0; i < m_nMoveCount; i++ ){
			cstr = GetItemText(m_iItemDrag[i], 0);
			PITEMSTATE pItemState = (PITEMSTATE)GetItemData(m_iItemDrag[i]);
			COLORREF textcolor = pItemState[0].textColor;
			COLORREF bkgdcolor = pItemState[0].bkgdColor;
			DWORD dwData = pItemState->dwData;
			pDropList->SetValue( m_nDropIndex, 0, cstr );
			pDropList->SetItemColor( m_nDropIndex, 0, textcolor, bkgdcolor );
			pDropList->SetItemRowData( m_nDropIndex, dwData );
			pDropList->SetItemState( m_nDropIndex, LVIS_SELECTED, LVIS_SELECTED );
			for( j = 1; j < nColumnCount; j++ ){
				cstr = GetItemText(m_iItemDrag[i], j);
				textcolor = pItemState[j].textColor;
				bkgdcolor = pItemState[j].bkgdColor;
				pDropList->SetValue( m_nDropIndex, j, cstr );
				pDropList->SetItemColor( m_nDropIndex, j, textcolor, bkgdcolor );
			}
			m_nDropIndex++;
		}
		if( pDropList != this || ( (m_nDropIndex-1) > m_iItemDrag[i] && (m_nDropIndex-1) > m_iItemDrag[m_nMoveCount-1]  ) )
		{	//移動先が、選択されているインデックスより後ろの場合

			for( i = m_nMoveCount-1; i >= 0; i-- ){
				DeleteItem( m_iItemDrag[i] );
			}
		}
		else if( pDropList == this && (m_nDropIndex-1) < m_iItemDrag[i] && (m_nDropIndex-1) < m_iItemDrag[m_nMoveCount-1] )
		{	//移動先が、選択されているインデックスより前の場合

			for( i = m_nMoveCount-1; i >= 0; i-- ){
				DeleteItem( (m_iItemDrag[i]+m_nMoveCount) );
			}
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.07
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnVScroll
//		CLixxxtrlEx::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スクロール時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		ドラッグ＆ドロップ処理
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	if( GetFocus() != this ) SetFocus();

	CLixxxtrl::OnVScroll(nSBCode, nPos, pScrollBar);
}

void CLixxxtrlEx::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	if( GetFocus() != this ) SetFocus();
	
	CLixxxtrl::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnBeginlabeledit
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		入力処理開始時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		アイテム入力処理
//		コンボボックス選択処理
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;
	// TODO: この位置にコントロール通知ハンドラ用のコードを追加してください
	
//	*pResult = 0;
	*pResult = -1;
}


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnEndlabeledit
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		入力処理終了時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		アイテム入力処理
//		コンボボックス選択処理
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;

	LV_ITEM		 *plvItem = &pDispInfo->item;

	if (plvItem->pszText != NULL)
	{
//		SetItemText(plvItem->iItem, plvItem->iSubItem, plvItem->pszText);
		ModifyValue( plvItem->iItem, plvItem->iSubItem, plvItem->pszText );
	}

	m_nEditRow = -99;
	m_nEditCol = -99;
	*pResult = 0;

	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_ENDLABELEDIT, reinterpret_cast<WPARAM> (pNMHDR));
// Modify ... ( ADD )
	m_bEditShow	 = 0;
// By Y.Itabashi (xxxxx) 2007.03.05
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnClick
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左ボタンクリック時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		アイテム入力処理
//		ＯＮ／ＯＦＦ処理
//		コンボボックス選択処理
//		要修正：カラム処理制御フラグから処理の可／不可を取得
//		要修正：コンボボックスの初期化処理（選択アイテムの設定）
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnClick(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

//	char b_ln[3];
	int index = pNMListView->iItem;
	int colnum = pNMListView->iSubItem;
	UINT flag = LVIS_FOCUSED;
	CString	sItem;
	PITEMSTATE	pItemState = NULL;

	if (index < 0 || colnum < 0) return;

	switch (M_aColumnActFlag[colnum].nInputType) {

	case eINPUTTYPE_ONOFFCHECK:

		// ＯＮ／ＯＦＦ切り替え
		sItem = GetItemText(index, colnum);
		if (sItem == "*") {
//			ModifyValue( index, colnum, "" );
			SetItemText(index, colnum, "");
		} else {
//			ModifyValue( index, colnum, "*" );
			SetItemText(index, colnum, "*");
		}
		break;

	case eINPUTTYPE_COMBOBOX:

		// コンボボックス選択
		if( (this->GetItemState( index, flag ) & flag) == flag )
		{
			// 編集可能なリストであるかチェックする（ LVS_EDITLABELS ）
			if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			{

				// コンボボックス作成イベント発生
				GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_COMBOCREATE, reinterpret_cast<WPARAM> (pNMHDR));
			}
		}
		break;

#ifndef START_INPLACE_IS_DBLCLICK
	case eINPUTTYPE_EDITBOX:
		// エディットボックスによるキーイン入力
		if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			EditSubLabel( index, colnum, GetDlgCtrlID() );
		break;
#endif

	}

	// テスト用にイベント発生
	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_CLICK, reinterpret_cast<WPARAM> (pNMHDR));

	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::ShowInPlaceList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンボボックス選択可アイテムの左ボタンクリック時処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nItem		[I] 行番号
//		int			nCol		[I] 列番号
//		CStringList	&IstItems	[I]	リスト文字列
//		int			nSel		[I] コンボボックス初期インデックス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CComboBox*				[O] コンボボックスのポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		コンボボックス選択処理
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
CComboBox* CLixxxtrlEx::ShowInPlaceList(int nItem,
										int nCol,
										CStringList &lstItems,
										int nSel)
{

//::AfxSetResourceHandle(hInst);
	// The returned pointer should not be saved

	// Make sure that the item is visible
	if( !EnsureVisible( nItem, TRUE ) ) return NULL;

	// Make sure that nCol is valid 
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if( nCol >= nColumnCount || GetColumnWidth(nCol) < 10 ) {
//::AfxSetResourceHandle(hInstResourceClient);
		return NULL;
	}

	// Get the column offset
	int offset = 0;
	for( int i = 0; i < nCol; i++ )
		offset += GetColumnWidth( i );

	CRect rect;
	GetItemRect( nItem, &rect, LVIR_BOUNDS );

	// Now scroll if we need to expose the column
	CRect rcClient;
	GetClientRect( &rcClient );
	if( offset + rect.left < 0 || offset + rect.left > rcClient.right )
	{
		CSize size;
		size.cx = offset + rect.left;
		size.cy = 0;
		Scroll( size );
		rect.left -= size.cx;
	}

	rect.left += offset+4;
	rect.right = rect.left + GetColumnWidth( nCol ) - 3 ;
	int height = rect.bottom-rect.top;
	rect.bottom += 5*height;
	if( rect.right > rcClient.right) rect.right = rcClient.right;

	DWORD dwStyle = WS_BORDER|WS_CHILD|WS_VISIBLE|WS_VSCROLL
					|CBS_DROPDOWNLIST|CBS_DISABLENOSCROLL;
	CComboBox *pList = new CInPlaceList(nItem, nCol, &lstItems, nSel);
	pList->Create( dwStyle, rect, this, IDC_COMBOEX );
	pList->SetItemHeight( -1, height);
	pList->SetHorizontalExtent( GetColumnWidth( nCol ));
//::AfxSetResourceHandle(hInstResourceClient);

	return pList;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnColumnclick
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ヘッダー左ボタンクリック時処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nItem		[I] 行番号
//		int			nCol		[I] 列番号
//		CStringList	&IstItems	[I]	リスト文字列
//		int			nSel		[I] コンボボックス初期インデックス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CComboBox*				[O] コンボボックスのポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		ソート処理
//		要修正：ソート可／不可フラグにて処理制御
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
void CLixxxtrlEx::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult) 
{

	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iSubItem < 0 ) return;

	int	iSubItem = pNMListView->iSubItem;

	if ( M_aColumnActFlag[iSubItem].bSort ) {	// ソート可か？

		M_nColumnSort = pNMListView->iSubItem;

		if ( M_aColumnActFlag[M_nColumnSort].nSortDir == 1 ) {
			M_aColumnActFlag[M_nColumnSort].nSortDir = 0;
		}
		else {
			M_aColumnActFlag[M_nColumnSort].nSortDir =1;
		}
		switch ( M_aColumnActFlag[M_nColumnSort].nItemType ) {
		case eITEMTYPE_STRING:		// 文字列ソート
			SortItems( CompareString, reinterpret_cast <DWORD> (this) );
			break;
		case eITEMTYPE_INUM:
		case eITEMTYPE_RNUM:// 数値ソート
			SortItems( CompareNumeric, reinterpret_cast <DWORD> (this) );
			break;
		case eITEMTYPE_ONOFFCHECK:
			break;
		}
	}
	*pResult = 0;
}
//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::CompareString
//		CLixxxtrlEx::CompareNumeric
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ソート比較関数
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		CLixxxtrl::SortItemsにて使用
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
int CALLBACK CLixxxtrlEx::CompareString(
			LPARAM lParam1,
			LPARAM lParam2,
			LPARAM lParamSort)
{
	CLixxxtrlEx* pLixxxtrl = reinterpret_cast <CLixxxtrlEx*> (lParamSort);

	int nColumn = pLixxxtrl->M_nColumnSort;

	//	比較項目のアイテムインデックスを取得する
	LV_FINDINFO	sFindInfo;
	sFindInfo.flags  = LVFI_PARAM;
	sFindInfo.lParam = lParam1;
	int nItem1 = pLixxxtrl->FindItem( &sFindInfo );
	sFindInfo.lParam = lParam2;
	int nItem2 = pLixxxtrl->FindItem( &sFindInfo );

	//	比較対象文字列を取得する
	CString cStr1 = pLixxxtrl->GetItemText( nItem1, nColumn );
	CString cStr2 = pLixxxtrl->GetItemText( nItem2, nColumn );

	//	文字列を比較する
	switch ( pLixxxtrl->M_aColumnActFlag[nColumn].nSortDir ) {
	case 0:		//昇順
		if ( cStr1 < cStr2 ) {
			return( -1 );
		} else if ( cStr1 > cStr2 ) {
			return( 1 );
		}
		break;
	case 1:		//降順
		if ( cStr1 < cStr2 ) {
			return( 1 );
		} else if ( cStr1 > cStr2 ) {
			return( -1 );
		}
		break;
	}

	return( 0 );

}

int CALLBACK CLixxxtrlEx::CompareNumeric(
			LPARAM lParam1,
			LPARAM lParam2,
			LPARAM lParamSort)
{

	CLixxxtrlEx* pLixxxtrl = reinterpret_cast <CLixxxtrlEx*> (lParamSort);

	int nColumn = pLixxxtrl->M_nColumnSort;

	//	比較項目のアイテムインデックスを取得する
	LV_FINDINFO	sFindInfo;
	sFindInfo.flags  = LVFI_PARAM;
	sFindInfo.lParam = lParam1;
	int nItem1 = pLixxxtrl->FindItem( &sFindInfo );
	sFindInfo.lParam = lParam2;
	int nItem2 = pLixxxtrl->FindItem( &sFindInfo );

	//	比較対象文字列を取得する
	CString cStr1 = pLixxxtrl->GetItemText( nItem1, nColumn );
	CString cStr2 = pLixxxtrl->GetItemText( nItem2, nColumn );

	BOOL nullcheck1 = cStr1.IsEmpty();
	BOOL nullcheck2 = cStr2.IsEmpty();
	if ( nullcheck1 || nullcheck2 ) {	// どちらかが値未入力の場合

		// ソート方向関係なし

		if ( nullcheck1 ) {
			if ( !nullcheck2 )
				return( 1 );
			else
				return( 0 );
		} else {
			if ( nullcheck2 )
				return( -1 );
		}
	}

	double rVal1 = atof( (LPCTSTR) cStr1 );
	double rVal2 = atof( (LPCTSTR) cStr2 );

	//	数値を比較する
	switch ( pLixxxtrl->M_aColumnActFlag[nColumn].nSortDir ) {
	case 0:		//昇順
		if ( rVal1 < rVal2 ) {
			return( -1 );
		} else if ( rVal1 > rVal2 ) {
			return( 1 );
		}
		break;
	case 1:		//降順
		if ( rVal1 < rVal2 ) {
			return( 1 );
		} else if ( rVal1 > rVal2 ) {
			return( -1 );
		}
		break;
	}

	return( 0 );

}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::InsertColumn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		列の挿入
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nItem		[I] 行番号
//		int			nCol		[I] 列番号
//		CStringList	&IstItems	[I]	リスト文字列
//		int			nSel		[I] コンボボックス初期インデックス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CComboBox*				[O] コンボボックスのポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		列の処理制御フラグ初期化のためのオーバーライド
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx) ALPHAのソース流用
//*****************************************************************************************************
int CLixxxtrlEx::InsertColumn(
			int nCol,
			const LV_COLUMN* pColumn)
{
	ST_CLMACT sAct = {	eITEMTYPE_STRING,	// 文字列
						eINPUTTYPE_NOTEDIT,	// ユーザー入力不可
						FALSE,				// ソート不可
						FALSE,				// 検索不可
						1,					// ソート方向降順
						"%s",				// 書式
						0,					// 最小値
						0,					// 最大値
						-1};				// 入力テキスト制限値（システムデフォルト）
	M_aColumnActFlag.InsertAt( nCol, sAct );

	return CLixxxtrl::InsertColumn( nCol, pColumn);
}

int CLixxxtrlEx::InsertColumn(
			int nCol,
			LPCTSTR lpszColumnHeading,
			int nFormat,
			int nWidth,
			int nSubItem)
{
	ST_CLMACT sAct = {	eITEMTYPE_STRING,	// 文字列
						eINPUTTYPE_NOTEDIT,	// ユーザー入力不可
						FALSE,				// ソート不可
						FALSE,				// 検索不可
						1,					// ソート方向降順
						"%s",				// 書式
						0,
						0,
						-1};				// 入力テキスト制限値（システムデフォルト）
	M_aColumnActFlag.InsertAt( nCol, sAct );

	return CLixxxtrl::InsertColumn( nCol, lpszColumnHeading, nFormat, nWidth, nSubItem);
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::HitTestEx
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		座標値の位置するカラム番号取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint		&point		[I] 座標値
//		int			*col		[O] 列番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int						[O] 行番号
//									< 0 : リスト上になし
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
int CLixxxtrlEx::HitTestEx(CPoint &point, int *col) const
{
	int colnum = 0;
	int row = HitTest( point, NULL );

	if( col ) *col = 0;

	// Make sure that the ListView is in LVS_REPORT
	if( (GetWindowLong(m_hWnd, GWL_STYLE) & LVS_TYPEMASK) != LVS_REPORT )
		return row;

	// Get the top and bottom row visible
	row = GetTopIndex();
	int bottom = row + GetCountPerPage();
	if( bottom > GetItemCount() )
		bottom = GetItemCount();

	// Get the number of columns
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();

	// Loop through the visible rows
	for( ;row <=bottom;row++)
	{
		// Get bounding rect of item and check whether point falls in it.
		CRect rect;
		GetItemRect( row, &rect, LVIR_BOUNDS );
		if( rect.PtInRect(point) )
		{
			// Now find the column
			for( colnum = 0; colnum < nColumnCount; colnum++ )
			{
				int colwidth = GetColumnWidth(colnum);
				if( point.x >= rect.left
					&& point.x <= (rect.left + colwidth ) )
				{
					if( col ) *col = colnum;
					return row;
				}
				rect.left += colwidth;
			}
		}
	}
	return -1;
}


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::EditSubLabel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		エディットボックスによるキーイン入力
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nItem		[I] 行番号
//		int			nCol		[I] 列番号
//		UINT		nID			[I] エディットコントロールID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CEdit*					[O] エディットコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
CEdit* CLixxxtrlEx::EditSubLabel( int nItem, int nCol, UINT nID )
{
	// The returned pointer should not be saved

	// Make sure that the item is visible
	if( !EnsureVisible( nItem, TRUE ) ) return NULL;

	// Make sure that nCol is valid
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if( nCol >= nColumnCount || GetColumnWidth(nCol) < 5 )
		return NULL;

	// Get the column offset
	int offset = 0;
	for( int i = 0; i < nCol; i++ )
		offset += GetColumnWidth( i );

	CRect rect;
	GetItemRect( nItem, &rect, LVIR_BOUNDS );

	// Now scroll if we need to expose the column
	CRect rcClient;
	GetClientRect( &rcClient );
	if( offset + rect.left < 0 || offset + rect.left > rcClient.right )
	{
		CSize size;
		size.cx = offset + rect.left;
		size.cy = 0;
		Scroll( size );
		rect.left -= size.cx;
	}

	// Get Column alignment
	LV_COLUMN lvcol;
	lvcol.mask = LVCF_FMT;
	GetColumn( nCol, &lvcol );
	DWORD dwStyle ;
	if((lvcol.fmt&LVCFMT_JUSTIFYMASK) == LVCFMT_LEFT)
		dwStyle = ES_LEFT;
	else if((lvcol.fmt&LVCFMT_JUSTIFYMASK) == LVCFMT_RIGHT)
		dwStyle = ES_RIGHT;
	else dwStyle = ES_CENTER;

	rect.left += offset+4;
	rect.right = rect.left + GetColumnWidth( nCol ) - 3 ;
	if( rect.right > rcClient.right) rect.right = rcClient.right;

	m_nEditRow	= nItem;
	m_nEditCol	= nCol;

	dwStyle |= WS_BORDER|WS_CHILD|WS_VISIBLE|ES_AUTOHSCROLL;
	CEdit *pEdit = new CInPlaceEdit(nItem, nCol, GetItemText( nItem, nCol ));
	pEdit->Create( dwStyle, rect, this, IDC_EDITEX );
//	pEdit->Create( dwStyle, rect, this, nID );

	if ( M_aColumnActFlag[nCol].nLimitEdit > 0 )
		pEdit->SetLimitText( UINT(M_aColumnActFlag[nCol].nLimitEdit) );

// Modify ... ( ADD )
	m_bEditShow	 = 1;
// By Y.Itabashi (xxxxx) 2007.03.05

	return pEdit;
}

#ifdef START_INPLACE_IS_DBLCLICK
// 要修正：シングルクリックによる入力に変更時は不要となる
//
//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnDblclk
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダブルクリック時の処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnDblclk(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iItem < 0 || pNMListView->iSubItem < 0 ) return;

	int iRow = pNMListView->iItem;
	int iCol = pNMListView->iSubItem;
	UINT flag = LVIS_FOCUSED;

	if ( M_aColumnActFlag[iCol].nInputType == eINPUTTYPE_EDITBOX ) {

		// Add check for LVS_EDITLABELS
		if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			EditSubLabel( iRow, iCol, GetDlgCtrlID() );
	}
//	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_DBLCLICK, reinterpret_cast<WPARAM> (pNMHDR));

	*pResult = 0;
}
#endif

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SelectChange
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		選択の変更
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		選択する行を指定、単一選択のときのみ機能する
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SelectChange(int nRow) 
{
// Modify ... ( CHANGE )
//	if( m_nSelectType != eSELECTTYPE_SINGLE )	return;

	SetFocus();
	POSITION pos = GetFirstSelectedItemPosition();
	int nBeforeRow = GetNextSelectedItem( pos );
	if( m_nSelectType == eSELECTTYPE_SINGLE )
		SetItemState( nBeforeRow, 0, LVIS_SELECTED );
// By Y.Itabashi (xxxxx) 2007.02.07

	SetItemState( nRow, LVIS_SELECTED, LVIS_SELECTED );

	this->EnsureVisible( nRow, true );
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnCustomDraw
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カスタムドロー処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnCustomDraw(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NMLVCUSTOMDRAW* pLVCD = reinterpret_cast<NMLVCUSTOMDRAW*>( pNMHDR );

	PITEMSTATE	pItemState = NULL;

	*pResult = CDRF_DODEFAULT;
	if ( CDDS_PREPAINT == pLVCD->nmcd.dwDrawStage )
	{
		*pResult = CDRF_NOTIFYITEMDRAW;
	}
	else if ( CDDS_ITEMPREPAINT == pLVCD->nmcd.dwDrawStage )
	{
		*pResult = CDRF_NOTIFYSUBITEMDRAW;
	}
	else if ( (CDDS_ITEMPREPAINT | CDDS_SUBITEM) == pLVCD->nmcd.dwDrawStage )
	{
		COLORREF crText, crBkgnd;

		pItemState = (PITEMSTATE)GetItemData( pLVCD->nmcd.dwItemSpec );
		if ( pItemState != NULL) {
			crText	= pItemState[pLVCD->iSubItem].textColor;
			crBkgnd	= pItemState[pLVCD->iSubItem].bkgdColor;
		}
		pLVCD->clrText = crText;
		pLVCD->clrTextBk = crBkgnd;

		*pResult = CDRF_DODEFAULT;
	}
}


//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::GetItemColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示位置のテキスト描画色、背景色を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		int			nCol			[I] 列
//		COLORREF&	textColor		[O]	文字色
//		COLORREF&	bkgdColor		[O] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemColor( int nRow, int nCol, COLORREF &textColor, COLORREF &bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		textColor = pItemState[nCol].textColor;
		bkgdColor = pItemState[nCol].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetItemColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示位置のテキスト描画色、背景色をセット
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		int			nCol			[I] 列
//		COLORREF	textColor		[I]	文字色
//		COLORREF	bkgdColor		[I] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemColor( int nRow, int nCol, COLORREF textColor, COLORREF bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		pItemState[nCol].textColor = textColor;
		pItemState[nCol].bkgdColor = bkgdColor;
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::GetItemRowColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示行のテキスト描画色、背景色を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		COLORREF&	textColor		[O]	文字色
//		COLORREF&	bkgdColor		[O] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		1列目のカラーを取得
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemRowColor( int nRow, COLORREF &textColor, COLORREF &bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		textColor = pItemState[0].textColor;
		bkgdColor = pItemState[0].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetItemRowColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示行のテキスト描画色、背景色をセット
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		COLORREF	textColor		[I]	文字色
//		COLORREF	bkgdColor		[I] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemRowColor( int nRow, COLORREF textColor, COLORREF bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		int nMax = this->GetHeaderCtrl()->GetItemCount();
		for( int nCol = 0; nCol < nMax; nCol++ ){
			pItemState[nCol].textColor = textColor;
			pItemState[nCol].bkgdColor = bkgdColor;
		}
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::GetItemColColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示列のテキスト描画色、背景色を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 列
//		COLORREF&	textColor		[O]	文字色
//		COLORREF&	bkgdColor		[O] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		1行目のカラーを取得
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemColColor( int nCol, COLORREF &textColor, COLORREF &bkgdColor) 
{
	if( nCol < 0 || nCol > GetHeaderCtrl()->GetItemCount() ){
		textColor = RGB( 0xff, 0xff, 0xff );
		bkgdColor = RGB( 0x00, 0x00, 0x00 );
		return;
	}

	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( 0 );
	if ( pItemState != NULL ) {
		textColor = pItemState[nCol].textColor;
		bkgdColor = pItemState[nCol].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetItemColColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示列のテキスト描画色、背景色をセット
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nCol			[I] 列
//		COLORREF	textColor		[I]	文字色
//		COLORREF	bkgdColor		[I] 背景色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemColColor( int nCol, COLORREF textColor, COLORREF bkgdColor) 
{
	if( nCol < 0 || nCol > GetHeaderCtrl()->GetItemCount() )	return;

	int nMax = this->GetItemCount();
	for( int nRow = 0; nRow < nMax; nRow++ ){
		PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
		if ( pItemState != NULL ) {
			pItemState[nCol].textColor = textColor;
			pItemState[nCol].bkgdColor = bkgdColor;
		}
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::GetItemRowData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示位置のテキスト描画色、背景色以外のデータを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		DWORD		dwData			[O]	アイテムに関連付けられる32 ビットの値
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
DWORD CLixxxtrlEx::GetItemRowData( int nRow ) 
{
	if( nRow < 0 || nRow >= GetItemCount() )	return 0;

	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		return( pItemState[0].dwData );
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetItemRowData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指示位置のテキスト描画色、背景色以外のデータをセット
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nRow			[I] 行
//		DWORD		dwData			[I]	アイテムに関連付けられる32 ビットの値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemRowData( int nRow, DWORD dwData) 
{
	if( nRow < 0 || nRow >= GetItemCount() )	return;

	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		pItemState[0].dwData = dwData;
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::OnDeleteitem
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイテム破棄時処理
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//		アイテム情報列の削除
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		DeleteItem、ダイアログ破棄時に呼び出される
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnDeleteitem( NMHDR* pNMHDR, LRESULT* pResult ) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int	nRow = pNMListView->iItem;
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData(nRow);
	if (pItemState != NULL) {
		delete [] pItemState;
		pItemState = NULL;
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CLixxxtrlEx::SetColumnFormat
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カラム毎の書式設定
//----------------------------------------------------------------------------------------------------
//  3. 処理概要
//----------------------------------------------------------------------------------------------------
//  4. 備考
//		与える文字列はprintf等で使用する"%d"、"文字列=%s"、"実数値=%2.3lf"をそのまま
//		但し、数値カラムに"整数値=%d"等と文字列を含めた書式にした場合、ソートとの併用はできない
//		（できるが動作保証なし）
//		初期値は"%s"、"%d"、"%lf"、"%c"
//----------------------------------------------------------------------------------------------------
//  5. 履歴
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnFormat( int nCol, LPCTSTR format )
{
	strcpy(M_aColumnActFlag[nCol].sDispFormat, format);
}

void CLixxxtrlEx::DoDataExchange(CDataExchange* pDX) 
{
	CLixxxtrl::DoDataExchange(pDX);

	if ( m_nEditRow >= 0 && m_nEditCol >= 0 ) {

		int	maxchar	= 0;
		int	minnum	= 0;
		int	maxnum	= 0;
		double	minval	= 0.0;
		double	maxval	= 0.0;
		switch ( M_aColumnActFlag[m_nEditCol].nItemType ) {

		case eITEMTYPE_STRING:

			maxchar = int( M_aColumnActFlag[m_nEditCol].fMaxValue );
			if ( maxchar > 0 ) {
				DDX_Text( pDX, IDC_EDITEX, m_EditText );
				DDV_MaxChars( pDX, m_EditText, int( M_aColumnActFlag[m_nEditCol].fMaxValue ) );
			}
			break;

		case eITEMTYPE_INUM:

			minnum = int( M_aColumnActFlag[m_nEditCol].fMinValue );
			maxnum = int( M_aColumnActFlag[m_nEditCol].fMaxValue );
			if ( minnum == 0 && maxnum == 0 || minnum == maxnum || minnum > maxnum ) {
				break;
			}
			DDX_Text( pDX, IDC_EDITEX, m_EditInt );
			DDV_MinMaxInt( pDX, m_EditInt, minnum, maxnum );
			break;

		case eITEMTYPE_RNUM:

			minval = M_aColumnActFlag[m_nEditCol].fMinValue;
			maxval = M_aColumnActFlag[m_nEditCol].fMaxValue; 
			if ( minval == 0.0 && maxval == 0.0 || minval == maxval || minval > maxval ) {
				break;
			}
			DDX_Text( pDX, IDC_EDITEX, m_EditDouble );
			DDV_MinMaxDouble( pDX, m_EditDouble, minval, maxval );
			break;

		}
	}
}

void CLixxxtrlEx::SetColumnMinMax( int nCol, double minvalue, double maxvalue )
{
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	
	if ( nCol > nColumnCount ) return;

	if ( M_aColumnActFlag[nCol].nInputType == eINPUTTYPE_EDITBOX ) {

		switch ( M_aColumnActFlag[nCol].nItemType ) {

		case eITEMTYPE_STRING:

			M_aColumnActFlag[nCol].fMaxValue = (int)maxvalue;
			break;

		case eITEMTYPE_INUM:

			M_aColumnActFlag[nCol].fMinValue = (int)minvalue;
			M_aColumnActFlag[nCol].fMaxValue = (int)maxvalue;
			break;

		case eITEMTYPE_RNUM:

			M_aColumnActFlag[nCol].fMinValue = minvalue;
			M_aColumnActFlag[nCol].fMaxValue = maxvalue;
			break;

		}
	}
}

void CLixxxtrlEx::SetLimitEdit( int nCol, int nLimit )
{
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	
	if ( nCol > nColumnCount ) return;

	M_aColumnActFlag[nCol].nLimitEdit = nLimit;
}

void CLixxxtrlEx::SetMultilineHeader(int nLine)
{
	HDITEM	hdItem;
	CHeaderCtrl* pHeader = NULL;
	pHeader = GetHeaderCtrl();

//	m_NewHeaderFont.CreatePointFont(190,"MS Serif");
	m_NewHeaderFont.CreatePointFont(100*nLine,"SYSTEM_FONT");

	if(pHeader==NULL)
		return;

	VERIFY(m_HeaderCtrl.SubclassWindow(pHeader->m_hWnd));	

	m_HeaderCtrl.SetFont(&(m_NewHeaderFont));

	hdItem.mask = HDI_FORMAT;

	for(int i=0; i <m_HeaderCtrl.GetItemCount(); i++)
	{
		m_HeaderCtrl.GetItem(i,&hdItem);

		hdItem.fmt|= HDF_OWNERDRAW;
			
		m_HeaderCtrl.SetItem(i,&hdItem);
	}
}

void CLixxxtrlEx::SetColumnText(int nColumn, LPCTSTR pText)
{
	LVCOLUMN lvc;

	memset(&lvc, 0, sizeof(lvc));
	lvc.mask = LVCF_TEXT;
	lvc.pszText = (LPTSTR)pText;
	SetColumn(nColumn, &lvc);
}

void CLixxxtrlEx::OnSetFocus(CWnd* pOldWnd) 
{
	if( m_bEditShow ){
		pOldWnd->SetFocus();
		return;
	}

	CLixxxtrl::OnSetFocus(pOldWnd);
	
}
