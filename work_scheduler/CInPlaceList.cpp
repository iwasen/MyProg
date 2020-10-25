// CInPlaceList.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "CInPlaceList.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define WM_USEREVENT_LISTEX_COMBOCHANGE	(WM_USER + 0x0503)
/////////////////////////////////////////////////////////////////////////////
// CInPlaceList

CInPlaceList::CInPlaceList(int iItem, int iSubItem, CStringList *plstItems, int nSel)
{
	m_iItem = iItem;
	m_iSubItem = iSubItem;

	m_lstItems.AddTail( plstItems );
	m_nSel = nSel;
	m_bESC = FALSE;
}

CInPlaceList::~CInPlaceList()
{
}


BEGIN_MESSAGE_MAP(CInPlaceList, CComboBox)
	//{{AFX_MSG_MAP(CInPlaceList)
	ON_WM_CREATE()
	ON_WM_CHAR()
	ON_CONTROL_REFLECT(CBN_CLOSEUP, OnCloseup)
	ON_WM_NCDESTROY()
	ON_WM_KILLFOCUS()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CInPlaceList メッセージ ハンドラ

int CInPlaceList::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CComboBox::OnCreate(lpCreateStruct) == -1)
		return -1;
	
	// リストコントロールが「LVN_ENDLABELEDIT」通知を送るとき、コンボボックスを初期化します。
	// Set the proper font
	CFont* font = GetParent()->GetFont();
	SetFont(font);

	for( POSITION pos = m_lstItems.GetHeadPosition(); pos != NULL; )
	{
		AddString( (LPCTSTR) (m_lstItems.GetNext( pos )) );
	}
	SetCurSel( m_nSel );
	SetFocus();
	
	return 0;
}

void CInPlaceList::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags) 
{
	// エデットが完了したときに、コンボボックスを破棄します。
	if( nChar == VK_ESCAPE || nChar == VK_RETURN)
	{
		if( nChar == VK_ESCAPE )
			m_bESC = TRUE;
		GetParent()->SetFocus();
		return;
	}
	
	CComboBox::OnChar(nChar, nRepCnt, nFlags);
}

BOOL CInPlaceList::PreTranslateMessage(MSG* pMsg) 
{
	// エスケープキーもしくはリターンキーを押すと、エディットへメッセージを送ります。
	if( pMsg->message == WM_KEYDOWN )
	{
		if(pMsg->wParam == VK_RETURN 
				|| pMsg->wParam == VK_ESCAPE
				)
		{
			::TranslateMessage(pMsg);
			::DispatchMessage(pMsg);
			return TRUE;				// DO NOT process further
		}
	}
	
	return CComboBox::PreTranslateMessage(pMsg);
}

void CInPlaceList::OnKillFocus(CWnd* pNewWnd) 
{
	CComboBox::OnKillFocus(pNewWnd);
	
	// ユーザーがコンボリスト内の項目を選び終わる時、親へLVN_ENDLABELEDITメッセージを送ります。
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

// Modify ... ( CHANGE )
//	GetParent()->GetParent()->SendMessage( WM_NOTIFY, GetParent()->GetDlgCtrlID(), (LPARAM)&dispinfo );
	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_COMBOCHANGE, reinterpret_cast<WPARAM> (&dispinfo));
// By Y.itabashi (xxxxx) 2007.01.26

	PostMessage( WM_CLOSE );
	
}

void CInPlaceList::OnCloseup() 
{
	// TODO: この位置にコントロール通知ハンドラ用のコードを追加してください
	GetParent()->SetFocus();	
}

void CInPlaceList::OnNcDestroy() 
{
	CComboBox::OnNcDestroy();
	
	// TODO: この位置にメッセージ ハンドラ用のコードを追加してください
	delete this;
}
