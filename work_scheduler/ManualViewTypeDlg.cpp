// ManualViewTypeDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualViewTypeDlg001.h"
#include "ManualViewTypeDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CManualViewTypeDlg ダイアログ


CManualViewTypeDlg::CManualViewTypeDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualViewTypeDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualViewTypeDlg)
	m_nRadioViewType = -1;
	//}}AFX_DATA_INIT
}


void CManualViewTypeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualViewTypeDlg)
	DDX_Control(pDX, IDC_COMBO_VIEW3, m_cComboView3);
	DDX_Control(pDX, IDC_COMBO_VIEW2, m_cComboView2);
	DDX_Control(pDX, IDC_COMBO_VIEW1, m_cComboView1);
	DDX_Radio(pDX, IDC_RADIO_VIEW_TYPE1, m_nRadioViewType);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualViewTypeDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualViewTypeDlg)
	ON_BN_CLICKED(IDC_RADIO_VIEW_TYPE1, OnRadioViewType1)
	ON_BN_CLICKED(IDC_RADIO_VIEW_TYPE2, OnRadioViewType2)
	ON_BN_CLICKED(IDC_RADIO_VIEW_TYPE3, OnRadioViewType3)
	ON_BN_CLICKED(IDC_RADIO_VIEW_TYPE4, OnRadioViewType4)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualViewTypeDlg メッセージ ハンドラ

BOOL CManualViewTypeDlg::OnInitDialog()
{
	m_nRadioViewType = g_pSystem->m_cIniUser.m_nWindowType - 1;

	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualViewTypeDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

// Modify ... ( DELET )
/*	CStringArray aViewKind;
	aViewKind.Add("編集画面");
	aViewKind.Add("作業者一覧表示画面");
	aViewKind.Add("全情報表示画面");
	CGlobal::SetComboBoxFromStringArray(m_cComboView1, aViewKind);
	CGlobal::SetComboBoxFromStringArray(m_cComboView2, aViewKind);
	CGlobal::SetComboBoxFromStringArray(m_cComboView3, aViewKind);*/
// By Y.Itabashi (xxxxx) 2007.01.26
	m_cComboView1.SetCurSel(g_pSystem->m_cIniUser.m_nWindowNo1DisplayType - 1);
	m_cComboView2.SetCurSel(g_pSystem->m_cIniUser.m_nWindowNo2DisplayType - 1);
	m_cComboView3.SetCurSel(g_pSystem->m_cIniUser.m_nWindowNo3DisplayType - 1);

	EnableViewType();

	return TRUE;
}

void CManualViewTypeDlg::OnOK()
{
	UpdateData(TRUE);

	g_pSystem->m_cIniUser.m_nWindowType = m_nRadioViewType + 1;
	g_pSystem->m_cIniUser.m_nWindowNo1DisplayType = m_cComboView1.GetCurSel() + 1;
	g_pSystem->m_cIniUser.m_nWindowNo2DisplayType = m_cComboView2.GetCurSel() + 1;
	g_pSystem->m_cIniUser.m_nWindowNo3DisplayType = m_cComboView3.GetCurSel() + 1;

	EndDialog(IDOK);
}

void CManualViewTypeDlg::OnRadioViewType1() 
{
	EnableViewType();
}

void CManualViewTypeDlg::OnRadioViewType2() 
{
	EnableViewType();
}

void CManualViewTypeDlg::OnRadioViewType3() 
{
	EnableViewType();
}

void CManualViewTypeDlg::OnRadioViewType4() 
{
	EnableViewType();
}

void CManualViewTypeDlg::EnableViewType()
{
	UpdateData(TRUE);

	switch (m_nRadioViewType) {
	case 0:
		m_cComboView2.EnableWindow(FALSE);
		m_cComboView3.EnableWindow(FALSE);
		break;
	case 1:
		m_cComboView2.EnableWindow(TRUE);
		m_cComboView3.EnableWindow(FALSE);
		break;
	case 2:
		m_cComboView2.EnableWindow(TRUE);
		m_cComboView3.EnableWindow(TRUE);
		break;
	case 3:
		m_cComboView2.EnableWindow(TRUE);
		m_cComboView3.EnableWindow(TRUE);
		break;
	}
}
