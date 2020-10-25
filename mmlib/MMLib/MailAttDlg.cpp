// MailAttDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "mmlib.h"
#include "MailAttDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMailAttDlg ダイアログ


CMailAttDlg::CMailAttDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CMailAttDlg::IDD, pParent)
{
	m_iAttActual = -1;
}


void CMailAttDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_ATT_VPIXEL, m_cAttVPixel);
	DDX_Control(pDX, IDC_ATT_VLIMIT, m_cAttVLimit);
	DDX_Control(pDX, IDC_ATT_SCALE, m_cAttScale);
	DDX_Control(pDX, IDC_ATT_HPIXEL, m_cAttHPixel);
	DDX_Control(pDX, IDC_ATT_HLIMIT, m_cAttHLimit);
	DDX_Radio(pDX, IDC_ATT_ACTUAL, m_iAttActual);
}


BEGIN_MESSAGE_MAP(CMailAttDlg, CDialog)
	ON_BN_CLICKED(IDC_ATT_ACTUAL, OnAttActual)
	ON_BN_CLICKED(IDC_ATT_REDUCTION, OnAttReduction)
	ON_BN_CLICKED(IDC_ATT_HLIMIT, OnAttHlimit)
	ON_BN_CLICKED(IDC_ATT_VLIMIT, OnAttVlimit)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMailAttDlg メッセージ ハンドラ

BOOL CMailAttDlg::OnInitDialog() 
{
	CWinApp *pWinApp = AfxGetApp();

	m_iAttActual = pWinApp->GetProfileInt(g_SectionMailAtt, "Actual", 0);
	
	CDialog::OnInitDialog();

	m_cAttScale = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "Scale", 50);
	m_cAttHLimit = pWinApp->GetProfileInt(g_SectionMailAtt, "HLimit", 0);
	m_cAttHPixel = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "HPixel", 800);
	m_cAttVLimit = pWinApp->GetProfileInt(g_SectionMailAtt, "VLimit", 0);
	m_cAttVPixel = (int)pWinApp->GetProfileInt(g_SectionMailAtt, "VPixel", 800);

	EnableControls();

	return TRUE;
}

void CMailAttDlg::OnOK() 
{
	CWinApp *pWinApp = AfxGetApp();

	UpdateData(TRUE);

	if (m_iAttActual != 0 && ((int)m_cAttScale < 5 || (int)m_cAttScale > 100)) {
		AfxMessageBox(IDS_REDUCTION_RATIO);
		return;
	}

	pWinApp->WriteProfileInt(g_SectionMailAtt, "Actual", m_iAttActual);
	pWinApp->WriteProfileInt(g_SectionMailAtt, "Scale", m_cAttScale);
	pWinApp->WriteProfileInt(g_SectionMailAtt, "HLimit", m_cAttHLimit);
	pWinApp->WriteProfileInt(g_SectionMailAtt, "HPixel", m_cAttHPixel);
	pWinApp->WriteProfileInt(g_SectionMailAtt, "VLimit", m_cAttVLimit);
	pWinApp->WriteProfileInt(g_SectionMailAtt, "VPixel", m_cAttVPixel);

	
	EndDialog(IDOK);
}

void CMailAttDlg::EnableControls()
{
	UpdateData(TRUE);

	if (m_iAttActual == 0) {
		m_cAttScale.EnableWindow(FALSE);
		m_cAttHLimit.EnableWindow(FALSE);
		m_cAttHPixel.EnableWindow(FALSE);
		m_cAttVLimit.EnableWindow(FALSE);
		m_cAttVPixel.EnableWindow(FALSE);
	} else {
		m_cAttScale.EnableWindow(TRUE);
		m_cAttHLimit.EnableWindow(TRUE);
		m_cAttHPixel.EnableWindow(m_cAttHLimit);
		m_cAttVLimit.EnableWindow(TRUE);
		m_cAttVPixel.EnableWindow(m_cAttVLimit);
	}
}

void CMailAttDlg::OnAttActual() 
{
	EnableControls();
}

void CMailAttDlg::OnAttReduction() 
{
	EnableControls();
}

void CMailAttDlg::OnAttHlimit() 
{
	EnableControls();
}

void CMailAttDlg::OnAttVlimit() 
{
	EnableControls();
}
