// OptTwain.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "OptTwain.h"
#include "LocalDll.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// COptTwain �v���p�e�B �y�[�W

IMPLEMENT_DYNCREATE(COptTwain, CPropertyPage)

COptTwain::COptTwain() : CPropertyPage(COptTwain::IDD)
{
	m_sTwainDriver = _T("");
}

COptTwain::~COptTwain()
{
}

void COptTwain::DoDataExchange(CDataExchange* pDX)
{
	CPropertyPage::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_TWAIN_DRIVER, m_sTwainDriver);
}


BEGIN_MESSAGE_MAP(COptTwain, CPropertyPage)
	ON_BN_CLICKED(IDC_SELECT_DRIVER, OnSelectDriver)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptTwain ���b�Z�[�W �n���h��

BOOL COptTwain::OnInitDialog() 
{
	TW_IDENTITY dsID;
	if (GetDefaultDriver(m_hWnd, &dsID))
		m_sTwainDriver = dsID.ProductName;

	CPropertyPage::OnInitDialog();
	
	return TRUE;
}

void COptTwain::OnSelectDriver() 
{
	SelectTwainDriver(m_hWnd);

	TW_IDENTITY dsID;
	if (GetDefaultDriver(m_hWnd, &dsID)) {
		m_sTwainDriver = dsID.ProductName;
		UpdateData(FALSE);
		CancelToClose();
	}
}
