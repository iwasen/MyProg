// SeisanJoken1Dlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "SetSeisanJoken1Dlg001.h"
#include "SetSeisanJoken1Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
#define COL_SUBLINE			0
#define COL_KADO_JIKAN		1
#define COL_KADO_RITSU		2
#define COL_TACT			3
#define COL_JOGEN			4
#define COL_KAGEN			5
#define COL_KYOSEI_KUDO		6

#define COLOR_TEXT	RGB( 0x00, 0x00, 0x00 )
#define COLOR_EDITDISABLE	RGB( 0xcc, 0xcc, 0xcc )
// By Y.Itabashi (xxxxx) 2007.01.26

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJoken1Dlg �_�C�A���O

//*****************************************************************************************************
//  1. �֐���
//		CSetSeisanJoken1Dlg::CSetSeisanJoken1Dlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent			[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���X�ϐ�������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.12.26 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CSetSeisanJoken1Dlg::CSetSeisanJoken1Dlg(CWnd* pParent /*=NULL*/)
	: CSetSeisanJokenDlg(CSetSeisanJoken1Dlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetSeisanJoken1Dlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_INIT
}


void CSetSeisanJoken1Dlg::DoDataExchange(CDataExchange* pDX)
{
	CSetSeisanJokenDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetSeisanJoken1Dlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetSeisanJoken1Dlg, CSetSeisanJokenDlg)
	//{{AFX_MSG_MAP(CSetSeisanJoken1Dlg)
	ON_CBN_SELCHANGE(IDC_COMBO_YEAR, OnSelchangeComboYear)
	ON_CBN_SELCHANGE(IDC_COMBO_MONTH, OnSelchangeComboMonth)
	ON_BN_CLICKED(IDC_BUTTON_SETTEI, OnButtonSettei)
	ON_EN_CHANGE(IDC_EDIT_DAISU1, OnChangeEditDaisu)
	ON_BN_CLICKED(IDC_BUTTON_IKO_JOGEN, OnButtonIkoJogen)
	ON_BN_CLICKED(IDC_BUTTON_IKO_KAGEN, OnButtonIkoKagen)
	ON_CBN_EDITCHANGE(IDC_COMBO_YEAR, OnEditchangeComboYear)
	ON_CBN_EDITCHANGE(IDC_COMBO_MONTH, OnEditchangeComboMonth)
	ON_EN_CHANGE(IDC_EDIT_DAISU2, OnChangeEditDaisu)
	ON_EN_CHANGE(IDC_EDIT_DAISU3, OnChangeEditDaisu)
	ON_EN_CHANGE(IDC_EDIT_DAISU4, OnChangeEditDaisu)
	ON_EN_CHANGE(IDC_EDIT_DAISU5, OnChangeEditDaisu)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER + 0x0500, OnChangeList)
	ON_MESSAGE(WM_USER + 0x0504, OnChangeEdit)
END_MESSAGE_MAP()

BEGIN_EVENTSINK_MAP(CSetSeisanJoken1Dlg, CSetSeisanJokenDlg)
    //{{AFX_EVENTSINK_MAP(CSetSeisanJoken1Dlg)
	//}}AFX_EVENTSINK_MAP
END_EVENTSINK_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJoken1Dlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CSetSeisanJoken1Dlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N�A���A�@�햼�A���Y�䐔�A���X�g�r���[�̊e�R���g���[��������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.12.26 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CSetSeisanJoken1Dlg::OnInitDialog()
{
	CSetSeisanJokenDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetSeisanJoken1Dlg );
// By Y.Itabashi (xxxxx) 2007.01.24

// Modify ... ( DELETE )
// Lixxxtrl�ɕύX
//	SetSpreadColumn(m_cSpread);
// By Y.Itabashi (xxxxx) 2007.01.26

	// �N�̃R���{�{�b�N�X�\��
	SetYearCombo();

	// ���̃R���{�{�b�N�X�\��
	SetMonthCombo();

	// �@�햼�\��
	SetKishuName();

	// ���Y�䐔�\��
	SetSeisanDaisu();

	// ���X�g�r���[�\��
	SetListView();

	return TRUE;
}

void CSetSeisanJoken1Dlg::OnSelchangeComboYear()
{
	int nSel = m_cComboYear.GetCurSel();
	if (nSel != -1) {
		m_cComboYear.GetLBText(nSel, m_sYear);
// Modify ... ( CHANGE )
		SetMonthCombo();
		SetSeisanDaisu();
//		SetSeisanDaisu();
//		SetMonthCombo();
// By Y.itabashi (xxxxx) 2007.02.05
	}
}

void CSetSeisanJoken1Dlg::OnSelchangeComboMonth()
{
	int nSel = m_cComboMonth.GetCurSel();
	if (nSel != -1) {
		m_cComboMonth.GetLBText(nSel, m_sMonth);
		SetSeisanDaisu();
	}
}

void CSetSeisanJoken1Dlg::OnEditchangeComboYear() 
{
	m_cComboYear.GetWindowText(m_sYear);
// Modify ... ( ADD )
	SetMonthCombo();
// By Y.itabashi (xxxxx) 2007.02.05
	SetSeisanDaisu();
}

void CSetSeisanJoken1Dlg::OnEditchangeComboMonth() 
{
	m_cComboMonth.GetWindowText(m_sMonth);
	SetSeisanDaisu();
}

void CSetSeisanJoken1Dlg::OnChangeEditDaisu()
{
	AutoCalcAll();
}

void CSetSeisanJoken1Dlg::OnButtonIkoJogen()
{
	SetIkoHanei(COL_JOGEN);
	AutoCalcAll();
}

void CSetSeisanJoken1Dlg::OnButtonIkoKagen()
{
	SetIkoHanei(COL_KAGEN);
	AutoCalcAll();
}

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
/*void CSetSeisanJoken1Dlg::OnChangeSpread(long Col, long Row)
{
	AutoCalc(Row);
}*/

LRESULT CSetSeisanJoken1Dlg::OnChangeList(WPARAM wp, LPARAM lp)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)wp;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	AutoCalc(Row);

	UpdateWindow();

	return TRUE;
}

LRESULT CSetSeisanJoken1Dlg::OnChangeEdit(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)wp;
	LV_ITEM		 *plvItem = &pDispInfo->item;

	int Row = plvItem->iItem;

	AutoCalc(Row);

	UpdateWindow();

// Modify ... ( ADD )
	m_bCloseLock = 0;
// By Y.Itabashi (xxxxx) 2007.03.05

	return TRUE;
}
// By Y.Itabashi (xxxxx) 2007.01.26

void CSetSeisanJoken1Dlg::OnButtonSettei()
{
	if (CheckDataInput()) {
		if (g_pSystem->DispMessage("Q005002") == IDYES) {
			SaveData();
			EndDialog(IDOK);
		}
	}
}

void CSetSeisanJoken1Dlg::SetListView()
{
	int nSublineNum = g_pDataManager->GetSublineNum();

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	SetLixxxolumn( m_cList, Menu_SetSeisanJoken1Dlg, List_SetSeisanJoken1Dlg, eSELECTTYPE_SINGLE );
	m_cList.SetMultilineHeader( 2 );

	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		m_cList.SetValue( nSublineId, COL_SUBLINE, pSubline->sSublineName );
		m_cList.SetValue( nSublineId, COL_KADO_JIKAN, pSubline->cSeisanJoken.fKadoJikan );
		m_cList.SetValue( nSublineId, COL_KADO_RITSU, pSubline->cSeisanJoken.fKadoRitsu );
		bool bKyoseiKudo = ( pSubline->cSeisanJoken.bKyoseiKudo ) ? true : false;
		m_cList.SetValue( nSublineId, COL_KYOSEI_KUDO, bKyoseiKudo );
		if (pSubline->cSeisanJoken.bSaved) {
			m_cList.SetValue( nSublineId, COL_JOGEN, pSubline->cSeisanJoken.fJogen );
			m_cList.SetValue( nSublineId, COL_KAGEN, pSubline->cSeisanJoken.fKagen );
		}

		AutoCalc(nSublineId);

	}

	m_cList.SetItemColColor( COL_SUBLINE, COLOR_TEXT, COLOR_EDITDISABLE );
	m_cList.SetItemColColor( COL_TACT, COLOR_TEXT, COLOR_EDITDISABLE );

/*	m_cSpread.SetMaxRows(nSublineNum);

	int nRow = 1;
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		m_cSpread.SetRowItemData(nRow, nSublineId);

		m_cSpread.SetValue(nRow, COL_SUBLINE, pSubline->sSublineName);
		m_cSpread.SetValue(nRow, COL_KADO_JIKAN, pSubline->cSeisanJoken.fKadoJikan);
		m_cSpread.SetValue(nRow, COL_KADO_RITSU, pSubline->cSeisanJoken.fKadoRitsu);
		m_cSpread.SetValue(nRow, COL_KYOSEI_KUDO, pSubline->cSeisanJoken.bKyoseiKudo);
		if (pSubline->cSeisanJoken.bSaved) {
			m_cSpread.SetValue(nRow, COL_JOGEN, pSubline->cSeisanJoken.fJogen);
			m_cSpread.SetValue(nRow, COL_KAGEN, pSubline->cSeisanJoken.fKagen);
		}

		AutoCalc(nRow);

		nRow++;
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

double CSetSeisanJoken1Dlg::CalcTact(int nRow)
{
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	int nSublineId = nRow;
	int nSeisanDaisu = GetSeisanDaisu(nSublineId);

	if (nSeisanDaisu > 0) {
		double fKadoJikan, fKadoRitsu;
		m_cList.GetValue(nRow, COL_KADO_JIKAN, fKadoJikan);
		m_cList.GetValue(nRow, COL_KADO_RITSU, fKadoRitsu);

// Modify ... ( CHANGE )	�ڕW�^�N�g�͉ғ������|����
		return (fKadoJikan * 60) * (fKadoRitsu / 100) / nSeisanDaisu;
// By Y.Itabashi (xxxxx) 2006.02.09
	} else
		return 0;

/*	int nSublineId = m_cSpread.GetRowItemData(nRow);
	int nSeisanDaisu = GetSeisanDaisu(nSublineId);

	if (nSeisanDaisu > 0) {
		double fKadoJikan, fKadoRitsu;
		m_cSpread.GetValue(nRow, COL_KADO_JIKAN, fKadoJikan);
		m_cSpread.GetValue(nRow, COL_KADO_RITSU, fKadoRitsu);

		return (fKadoJikan * 60) * (fKadoRitsu / 100) / nSeisanDaisu;
	} else
		return 0;*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetSeisanJoken1Dlg::AutoCalc(int nRow)
{
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	m_cList.SetValue(nRow, COL_TACT, CalcTact(nRow));

//	m_cSpread.SetValue(nRow, COL_TACT, CalcTact(nRow));
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetSeisanJoken1Dlg::AutoCalcAll()
{
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	int nMaxRows = m_cList.GetItemCount();
	for (int nRow = 0; nRow < nMaxRows; nRow++)
		AutoCalc(nRow);

/*	int nMaxRows = m_cSpread.GetMaxRows();
	for (int nRow = 1; nRow <= nMaxRows; nRow++)
		AutoCalc(nRow);*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

BOOL CSetSeisanJoken1Dlg::CheckDataInput(void)
{
	static SSpreadDataCheck aDataCheck[] = {
		{COL_KADO_JIKAN, eDataTypeNumber, 6, 2, 0, 0, NULL},
		{COL_KADO_RITSU, eDataTypeNumber, 5, 1, 0, 0, NULL},
		{COL_JOGEN, eDataTypeNumber, 4, 1, 0, 99.9, NULL},
		{COL_KAGEN, eDataTypeNumber, 4, 1, 0, 99.9, NULL}
	};

	return CheckDataInputSub(aDataCheck, sizeof(aDataCheck) / sizeof(SSpreadDataCheck));
}

void CSetSeisanJoken1Dlg::SaveData()
{
	SaveYMDaisu();

// Modify ... ( ADD )
// Lixxxtrl�ɕύX
	int nMaxRows = m_cList.GetItemCount();
	for (int nRow = 0; nRow < nMaxRows; nRow++) {
		int nSublineId = nRow;
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		m_cList.GetValue(nRow, COL_KADO_JIKAN, pSubline->cSeisanJoken.fKadoJikan);
		m_cList.GetValue(nRow, COL_KADO_RITSU, pSubline->cSeisanJoken.fKadoRitsu);
		m_cList.GetValue(nRow, COL_TACT, pSubline->cSeisanJoken.fTact);
		m_cList.GetValue(nRow, COL_JOGEN, pSubline->cSeisanJoken.fJogen);
		m_cList.GetValue(nRow, COL_KAGEN, pSubline->cSeisanJoken.fKagen);

		bool bKyoseiKudo;
		m_cList.GetValue(nRow, COL_KYOSEI_KUDO, bKyoseiKudo);
		pSubline->cSeisanJoken.bKyoseiKudo = bKyoseiKudo;
		pSubline->cSeisanJoken.bSaved = TRUE;
	}

/*	int nMaxRows = m_cSpread.GetMaxRows();
	for (int nRow = 1; nRow <= nMaxRows; nRow++) {
		int nSublineId = m_cSpread.GetRowItemData(nRow);
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		m_cSpread.GetValue(nRow, COL_KADO_JIKAN, pSubline->cSeisanJoken.fKadoJikan);
		m_cSpread.GetValue(nRow, COL_KADO_RITSU, pSubline->cSeisanJoken.fKadoRitsu);
		m_cSpread.GetValue(nRow, COL_TACT, pSubline->cSeisanJoken.fTact);
		m_cSpread.GetValue(nRow, COL_JOGEN, pSubline->cSeisanJoken.fJogen);
		m_cSpread.GetValue(nRow, COL_KAGEN, pSubline->cSeisanJoken.fKagen);
		m_cSpread.GetValue(nRow, COL_KYOSEI_KUDO, pSubline->cSeisanJoken.bKyoseiKudo);
		pSubline->cSeisanJoken.bSaved = TRUE;
	}*/
// By Y.Itabashi (xxxxx) 2007.01.27

	g_pDataManager->m_nStatus = STATUS_SEISAN_JOKEN;

	// �f�[�^�X�V�t���O�ݒ�
	g_pDataManager->SetUpdate();
}

void CSetSeisanJoken1Dlg::OnEndlabeleditList001(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;
	// TODO: ���̈ʒu�ɃR���g���[���ʒm�n���h���p�̃R�[�h��ǉ����Ă�������
	
	*pResult = 0;
}

// Modify ... ( ADD )
void CSetSeisanJoken1Dlg::OnCancel() 
{
	if( m_bCloseLock )
		return;

	CDialogEx::OnCancel();	
}
// By Y.Itabashi (xxxxx) 2007.03.05
