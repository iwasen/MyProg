/**
 * @file	InPlaceEdit.cpp
 * @brief	CInPlaceEditクラスのインプリメンテーション
 *
 * @author	K.kimura (xxxxx, Inc.)
 * @date	2005-08-18
 * @version	InPlaceEdit.cpp, v 1.1 2005/08/18 $
 *			Copyright(C) 2005 xxxxx, Inc. All rights reserved.
 */

#include "stdafx.h"
#include "InPlaceEdit.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define WM_USEREVENT_LISTEX_ERRLABELEDIT	(WM_USER + 0x0505)
/////////////////////////////////////////////////////////////////////////////
// CInPlaceEdit
/** コンストラクタ
 */
CInPlaceEdit::CInPlaceEdit()
{
}

/** コンストラクタ
 *
 * @param iItem		(i/ )	リスト行番号	int
 * @param iSubItem	(i/ )	リスト列番号	int
 * @param sInitText	(i/ )	初期化文字列	CString
 */
CInPlaceEdit::CInPlaceEdit(int iItem, int iSubItem, CString sInitText)
:m_sInitText( sInitText )
{
	m_iItem = iItem;
	m_iSubItem = iSubItem;
	m_bESC = FALSE;
}

/** デストラクタ
 */
CInPlaceEdit::~CInPlaceEdit()
{
}


BEGIN_MESSAGE_MAP(CInPlaceEdit, CEdit)
	//{{AFX_MSG_MAP(CInPlaceEdit)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	ON_WM_KILLFOCUS()
	ON_WM_NCDESTROY()
	ON_WM_CHAR()
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CInPlaceEdit メッセージ ハンドラ

/** メッセージ変換前処理
 * @param pMsg	(i/ ) 処理メッセージ	CMSG*
 * @retval TRUE		正常終了
 * @retval FALSE	エラー
 */
BOOL CInPlaceEdit::PreTranslateMessage(MSG* pMsg)
{
	if( pMsg->message == WM_KEYDOWN )
	{
		if(pMsg->wParam == VK_RETURN
				|| pMsg->wParam == VK_DELETE
				|| pMsg->wParam == VK_ESCAPE
				|| GetKeyState( VK_CONTROL)
				)
		{
			::TranslateMessage(pMsg);
			::DispatchMessage(pMsg);
			return TRUE;		    	// DO NOT process further
		}
	}

	return CEdit::PreTranslateMessage(pMsg);
}


/** 入力フォーカス消失前処理
 * @param pNewWnd	(i/ ) フォーカス受け取りウィンドウ	CWnd*
 */
void CInPlaceEdit::OnKillFocus(CWnd* pNewWnd)
{
	CEdit::OnKillFocus(pNewWnd);

// Modify ... ( CHANGE )
	if( m_bESC == TRUE ) {
		LV_DISPINFO dispinfo;
		dispinfo.hdr.hwndFrom = GetParent()->m_hWnd;
		dispinfo.hdr.idFrom = GetDlgCtrlID();
		dispinfo.hdr.code = LVN_ENDLABELEDIT;

		dispinfo.item.mask = LVIF_TEXT;
		dispinfo.item.iItem = m_iItem;
		dispinfo.item.iSubItem = m_iSubItem;
		dispinfo.item.pszText = NULL;
		dispinfo.item.cchTextMax = 0;

		GetParent()->GetParent()->SendMessage( WM_NOTIFY, GetParent()->GetDlgCtrlID(),
						(LPARAM)&dispinfo );

		DestroyWindow();
	} else
// By Y.Itabashi (xxxxx) 2007.02.05
	if ( GetParent()->UpdateData(TRUE) ) {
		CString str;
		GetWindowText(str);

		// Send Notification to parent of ListView ctrl
		LV_DISPINFO dispinfo;
		dispinfo.hdr.hwndFrom = GetParent()->m_hWnd;
		dispinfo.hdr.idFrom = GetDlgCtrlID();
		dispinfo.hdr.code = LVN_ENDLABELEDIT;

		dispinfo.item.mask = LVIF_TEXT;
		dispinfo.item.iItem = m_iItem;
		dispinfo.item.iSubItem = m_iSubItem;
		dispinfo.item.pszText = m_bESC ? NULL : LPTSTR((LPCTSTR)str);
		dispinfo.item.cchTextMax = str.GetLength();

		GetParent()->GetParent()->SendMessage( WM_NOTIFY, GetParent()->GetDlgCtrlID(),
						(LPARAM)&dispinfo );

		DestroyWindow();
	} else {
		GetParent()->GetParent()->RedrawWindow();
	}

}

/** クライアント領域破棄処理
 */
void CInPlaceEdit::OnNcDestroy()
{
	CEdit::OnNcDestroy();

	delete this;
}


/** キー入力変換処理
 *
 * @param nChar		(i/ ) 文字コード		UINT
 * @param nRepCnt	(i/ ) リピートカウント	UINT
 * @param nFlags	(i/ ) スキャンコード等	UINT
 */
void CInPlaceEdit::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags)
{
	if( nChar == VK_ESCAPE || nChar == VK_RETURN)
	{
		if( nChar == VK_ESCAPE )
			m_bESC = TRUE;
		GetParent()->SetFocus();
		return;
	}


	CEdit::OnChar(nChar, nRepCnt, nFlags);

	// Resize edit control if needed

	// Get text extent
	CString str;

	GetWindowText( str );
	CWindowDC dc(this);
	CFont *pFont = GetParent()->GetFont();
	CFont *pFontDC = dc.SelectObject( pFont );
	CSize size = dc.GetTextExtent( str );
	dc.SelectObject( pFontDC );
	size.cx += 5;			   	// add some extra buffer

	// Get client rect
	CRect rect, parentrect;
	GetClientRect( &rect );
	GetParent()->GetClientRect( &parentrect );

	// Transform rect to parent coordinates
	ClientToScreen( &rect );
	GetParent()->ScreenToClient( &rect );

	// Check whether control needs to be resized
	// and whether there is space to grow
	if( size.cx > rect.Width() )
	{
		if( size.cx + rect.left < parentrect.right )
			rect.right = rect.left + size.cx;
		else
			rect.right = parentrect.right;
		MoveWindow( &rect );
	}
}

/** オブジェクト作成時処理
 * @param lpCreateStruct	(i/ )	オブジェクト情報	LPCREATESTRUCT
 * @retval 0	正常終了
 * @retcal -1	オブジェクト破棄
 */
int CInPlaceEdit::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CEdit::OnCreate(lpCreateStruct) == -1)
		return -1;

	// Set the proper font
	CFont* font = GetParent()->GetFont();
	SetFont(font);

	SetWindowText( m_sInitText );
	SetFocus();
	SetSel( 0, -1 );
// Modify ... ( ADD )
	GetParent()->GetParent()->SendMessage( WM_USEREVENT_LISTEX_ERRLABELEDIT, GetParent()->GetDlgCtrlID(),
					NULL );
// By Y.Itabashi (xxxxx) 2007.03.05
	return 0;
}
