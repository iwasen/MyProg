// OptProperty.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "OptProperty.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// COptProperty

IMPLEMENT_DYNAMIC(COptProperty, CPropertySheet)

COptProperty::COptProperty(UINT nIDCaption, CWnd* pParentWnd, UINT iSelectPage)
	:CPropertySheet(nIDCaption, pParentWnd, iSelectPage)
{
	AddPropertyPage();
}

COptProperty::COptProperty(LPCTSTR pszCaption, CWnd* pParentWnd, UINT iSelectPage)
	:CPropertySheet(pszCaption, pParentWnd, iSelectPage)
{
	AddPropertyPage();
}

COptProperty::~COptProperty()
{
}


BEGIN_MESSAGE_MAP(COptProperty, CPropertySheet)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptProperty メッセージ ハンドラ

void COptProperty::AddPropertyPage()
{
	AddPage(&m_OptFolder);
	AddPage(&m_OptFont);
	AddPage(&m_OptOrder);
	AddPage(&m_OptTwain);
	AddPage(&m_OptImageEditor);

	m_psh.dwFlags |= PSH_NOAPPLYNOW;
}
