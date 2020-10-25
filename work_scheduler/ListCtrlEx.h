#if !defined(AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_)
#define AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LixxxtrlEx.h : ヘッダー ファイル
//
#include "afxtempl.h"

#define LVEXS_SELECTATCULOMN	0x0001	//カラム選択スタイル
#define LVEXS_SELECTATROW		0x0002	//行選択スタイル
#define LVEXS_SELECTIONROW		0x0004	//行選択表示

#define START_INPLACE_IS_DBLCLICK	1	// エディット入力のタイミング

// ユーザー定義イベント
#define WM_DRAG_DROP	(WM_USER + 200)
#define WM_BEGIN_DRAG	(WM_USER + 201)

#define WM_USEREVENT_LISTEX_CLICK			(WM_USER + 0x0500)
#define WM_USEREVENT_LISTEX_DBLCLICK		(WM_USER + 0x0501)
#define WM_USEREVENT_LISTEX_COMBOCREATE		(WM_USER + 0x0502)
#define WM_USEREVENT_LISTEX_COMBOCHANGE		(WM_USER + 0x0503)		// CInPlaceList.cppにも記述
#define WM_USEREVENT_LISTEX_ENDLABELEDIT	(WM_USER + 0x0504)
#define WM_USEREVENT_LISTEX_ERRLABELEDIT	(WM_USER + 0x0505)		// InPlaceEdit.cppにも記述

#include "LixxxtrlEx.h"
#include "HeaderCtrlEx.h"

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx ウィンドウ

// 要確認↓
#define		low_max		300		// ドラッグ＆ドロップの選択アイテムMAX数
//

//=======================================================
// 構造体定義
//=======================================================
// リスト制御定義
struct LIxxxTRLDATA {
	int		nCol;					// 列番号
	int		nHeaderNo;				// ヘッダーに記述する文字取得のMENUCTRLDATAのNo
	int		nWidth;					// 表示文字列数
	int		tFormat;				// 文字表示フォーマット
	int		tSort;					// ソートタイプ
	int		tSearch;				// 検索タイプ
	int		tDataType;				// 表示文字タイプ
	int		tEditType;				// 入力モード
	char	sDispFormat[255];		// 書式
	double	fMinValue;				// 入力最小値
	double	fMaxValue;				// 入力最大値（文字の場合は文字数）
	int		nLimitEdit;				// エディットボックスのテキスト制限値（バイト数）
};

//=======================================================
// 列挙型定義
//=======================================================
// データタイプ
enum EItemType {
	eITEMTYPE_STRING		= 0,	// 文字列
	eITEMTYPE_INUM			= 1,	// 整数
	eITEMTYPE_RNUM			= 2,	// 実数
	eITEMTYPE_ONOFFCHECK	= 3		// ＯＮ／ＯＦＦチェック
};

// ユーザー入力タイプ
enum EInputType {
	eINPUTTYPE_NOTEDIT		= 0,	// ユーザー入力不可
	eINPUTTYPE_EDITBOX		= 1,	// エディットボックス入力
	eINPUTTYPE_ONOFFCHECK	= 2,	// ＯＮ／ＯＦＦチェック
	eINPUTTYPE_COMBOBOX		= 3,	// コンボボックス選択
};

// カラム処理制御フラグのタイプ
enum EColumnActionType {
	eCOLUMNACTTYPE_SORT		= 1,	// ソート
	eCOLUMNACTTYPE_SEARCH	= 2		// 検索
};

// 行選択タイプ
enum ESelectType {
	eSELECTTYPE_SINGLE		= 0,	// 単一行選択
	eSELECTTYPE_MULTI		= 1		// 複数行選択
};
//=======================================================
// クラス定義
//=======================================================
class CLixxxtrlEx : public CLixxxtrl
{
	//カラム処理制御フラグ
	typedef struct _tagCLMACTSTRUCT {

		int		nItemType;			// データタイプ
		int		nInputType;			// ユーザー入力タイプ

		BOOL	bSort;				// ソート可／不可フラグ
									//		TRUE	: ソート可
									//		FALSE	: ソート不可

		BOOL	bSearch;			// 検索可／不可フラグ
									//		TRUE	: 検索可
									//		FALSE	: 検索不可

		int		nSortDir;			// ソート方向
									//		0 : 昇順
									//		1 : 降順

		char	sDispFormat[255];	// 書式

		double	fMinValue;			// 入力最小値
		double	fMaxValue;			// 入力最大値（文字の場合は文字数）

		int		nLimitEdit;			// エディットボックスのテキスト制限値（バイト数）


	} ST_CLMACT;

	typedef struct _tagITEMSTATE {

		COLORREF	textColor;		// テキスト描画色
		COLORREF	bkgdColor;		// 背景色
		DWORD		dwData;			// その他の情報

		_tagITEMSTATE() { textColor = RGB(0,0,0); bkgdColor = RGB(255,255,255); dwData = 0; };	// 初期化

	} ITEMSTATE;

	typedef ITEMSTATE* PITEMSTATE;	// ITEMSTATEへのポインタ型

// コンストラクション
public:
	CLixxxtrlEx();

// アトリビュート
public:

protected:
	//-------------------------------------------------------
	// ドラッグ＆ドロップ
	//--------------------------------------------------------
	BOOL			m_bPermissionDragDrop;	// Drag&Drop可／不可フラグ
	BOOL            m_bDragging;			// Drag状態
	int             m_iItemDrag[low_max];	// Dragアイテム
	int             m_iItemDrop[low_max];	// Dropアイテム
	CPoint          m_ptHotSpot;
	CPoint          m_ptOrigin;
	CSize           m_sizeDelta[low_max];

	CImageList      m_pimageListDrag;
	CPoint			m_ptDropPoint;
	int				m_nDropIndex;
    CWnd*			m_pDropWnd;
	int				m_nMoveCount;

	int				m_nSelectType;			// 行の単一／複数行選択タイプ
											//	0 : 単一行選択
											//	1 : 複数行選択

protected:

	//-------------------------------------------------------
	// キー入力
	//--------------------------------------------------------
	int				m_nEditRow;
	int				m_nEditCol;
	double			m_EditDouble;
	int				m_EditInt;
	CString			m_EditText;
// Modify ... ( ADD )
	BOOL			m_bEditShow;
// By Y.Itabashi (xxxxx) 2007.03.05

	//-------------------------------------------------------
	// ソート
	//--------------------------------------------------------
	CArray< ST_CLMACT, ST_CLMACT >		M_aColumnActFlag;		// 列毎の処理フラグ
	int									M_nColumnSort;			// ソートするカラム番号

	//--------------------------------------------------------
	// 複数行ヘッダ
	//--------------------------------------------------------
	CFont			m_NewHeaderFont;
	CHeaderCtrlEx	m_HeaderCtrl;

// オペレーション
public:
	//--------------------------------------------------------
	// 操作
	//--------------------------------------------------------
	int		InsertColumn( int nCol, const LV_COLUMN* pColumn );
	int		InsertColumn( int nCol, LPCTSTR lpszColumnHeading, int nFormat = LVCFMT_LEFT, int nWidth = -1, int nSubItem = -1 );

	//--------------------------------------------------------
	// 属性
	//--------------------------------------------------------
	// 基本的に公開するものは属性設定のみ（取得は内部で直接操作）
	void	SetPermissionDragDrop( BOOL bFlg ) { m_bPermissionDragDrop = bFlg; };	// ドラッグ＆ドロップ可／不可
	int		GetDraggingMode() { return m_bDragging; };								// ドラッグ状態を取得
	void	SetSelectType( int nSelectType );										// 単一／複数行選択の設定
	void	SetColumnAction( int nCol, int nAct, BOOL bFlg );						// 列毎の処理可／不可フラグ設定
	void	SetColumnType( int nCol, int nDataType, int nInputType );				// 列毎のデータタイプ、ユーザー入力タイプ設定
	void	SetColumnFormat( int nCol, LPCTSTR format);
	int		SetValue( int nRow, int nCol, LPCTSTR pValue );							// 値セット（文字列）
	int		SetValue( int nRow, int nCol, double fValue );							// 値セット（実数）
	int		SetValue( int nRow, int nCol, int nValue );								// 値セット（整数）
	int		SetValue( int nRow, int nCol, bool bValue );							// 値セット（ＯＮ／ＯＦＦ）
	int		SetComboList(int nRow, int nCol, CStringList &lstItems, int nIndex);		// コンボボックスリストセット
	int		GetValue( int nRow, int nCol, CString &sValue );
	int		GetValue( int nRow, int nCol, double &fValue );
	int		GetValue( int nRow, int nCol, int &nValue );
	int		GetValue( int nRow, int nCol, bool &bValue );
	int		SetValue2( int nRow, int nCol, LPCTSTR pValue );						// 書式なし
	int		ModifyValue( int nRow, int nCol, LPCTSTR pValue );
	void	GetItemColor( int nRow, int nCol, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemColor( int nRow, int nCol, COLORREF textColor, COLORREF bkgdColor );
	void	GetItemColColor( int nCol, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemColColor( int nCol, COLORREF textColor, COLORREF bkgdColor );
	void	GetItemRowColor( int nRow, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemRowColor( int nRow, COLORREF textColor, COLORREF bkgdColor );
	DWORD	GetItemRowData( int nRow );
	void	SetItemRowData( int nRow, DWORD dwData );
	void	SetColumnMinMax( int nCol, double minvalue, double maxvalue );
	void	SetLimitEdit( int nCol, int nLimit );
	void	SetMultilineHeader( int nLine );
	BOOL	IsEmpty( int nRow, int nCol );
	void	SetColumnText(int nColumn, LPCTSTR pText);

protected:
	//--------------------------------------------------------
	// ソート比較関数
	//--------------------------------------------------------
	static int CALLBACK CompareString(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	static int CALLBACK CompareNumeric(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);

	int		HitTestEx(CPoint &point, int *col) const;				// カーソル位置の列番号取得
	CEdit*	EditSubLabel( int nItem, int nCol, UINT nID ) ;			// ラベル編集処理

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CLixxxtrlEx)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CLixxxtrlEx();
	void    OnButtonUp(CPoint point);
	void CLixxxtrlEx::DropItemOnList(CLixxxtrlEx* pDropList);

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CLixxxtrlEx)
	afx_msg void OnBeginDrag(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDblclk(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnCustomDraw(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDeleteitem(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnSetFocus(CWnd* pNewWnd);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
public:
	CComboBox*	ShowInPlaceList( int nItem, int nCol, CStringList &lstItems, int nSel );
	void	SelectChange(int nRow) ;								// 選択行の変更
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_)
