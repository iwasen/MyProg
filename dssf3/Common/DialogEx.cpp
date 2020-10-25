// D:\aizawa\DSSF3_5.0.6\Common\DialogEx.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "DialogEx.h"


// CDialogEx �_�C�A���O

CDialogEx::CDialogEx(UINT nIDTemplate, CWnd* pParentWnd)
	: CDialog(nIDTemplate, pParentWnd)
{
}

void CDialogEx::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CDialogEx, CDialog)
	ON_NOTIFY_EX(TTN_NEEDTEXT, 0, OnToolTipText)
END_MESSAGE_MAP()


// CDialogEx ���b�Z�[�W �n���h��

BOOL CDialogEx::OnInitDialog()
{
	CDialog::OnInitDialog();

	EnableToolTips(TRUE);

	return TRUE;
}

BOOL CDialogEx::OnToolTipText(UINT, NMHDR* pNMHDR, LRESULT* /*pResult*/)
{
	TOOLTIPTEXT *pTTT = (TOOLTIPTEXT *)pNMHDR;
	UINT_PTR nID =pNMHDR->idFrom;

	if (pTTT->uFlags & TTF_IDISHWND)
		nID = ::GetDlgCtrlID((HWND)nID);

	if(nID) {
		pTTT->lpszText = MAKEINTRESOURCE(nID);
		pTTT->hinst = AfxGetResourceHandle();
		return(TRUE);
	}

	return(FALSE);
}
