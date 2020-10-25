/**
 * @file	InPlaceEdit.cpp
 * @brief	CInPlaceEdit�N���X�̃C���v�������e�[�V����
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
/** �R���X�g���N�^
 */
CInPlaceEdit::CInPlaceEdit()
{
}

/** �R���X�g���N�^
 *
 * @param iItem		(i/ )	���X�g�s�ԍ�	int
 * @param iSubItem	(i/ )	���X�g��ԍ�	int
 * @param sInitText	(i/ )	������������	CString
 */
CInPlaceEdit::CInPlaceEdit(int iItem, int iSubItem, CString sInitText)
:m_sInitText( sInitText )
{
	m_iItem = iItem;
	m_iSubItem = iSubItem;
	m_bESC = FALSE;
}

/** �f�X�g���N�^
 */
CInPlaceEdit::~CInPlaceEdit()
{
}


BEGIN_MESSAGE_MAP(CInPlaceEdit, CEdit)
	//{{AFX_MSG_MAP(CInPlaceEdit)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	ON_WM_KILLFOCUS()
	ON_WM_NCDESTROY()
	ON_WM_CHAR()
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CInPlaceEdit ���b�Z�[�W �n���h��

/** ���b�Z�[�W�ϊ��O����
 * @param pMsg	(i/ ) �������b�Z�[�W	CMSG*
 * @retval TRUE		����I��
 * @retval FALSE	�G���[
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


/** ���̓t�H�[�J�X�����O����
 * @param pNewWnd	(i/ ) �t�H�[�J�X�󂯎��E�B���h�E	CWnd*
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

/** �N���C�A���g�̈�j������
 */
void CInPlaceEdit::OnNcDestroy()
{
	CEdit::OnNcDestroy();

	delete this;
}


/** �L�[���͕ϊ�����
 *
 * @param nChar		(i/ ) �����R�[�h		UINT
 * @param nRepCnt	(i/ ) ���s�[�g�J�E���g	UINT
 * @param nFlags	(i/ ) �X�L�����R�[�h��	UINT
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

/** �I�u�W�F�N�g�쐬������
 * @param lpCreateStruct	(i/ )	�I�u�W�F�N�g���	LPCREATESTRUCT
 * @retval 0	����I��
 * @retcal -1	�I�u�W�F�N�g�j��
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
