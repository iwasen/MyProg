//*****************************************************************************************************
//  1. �t�@�C����
//		AutoSchedulingDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CAutoSchedulingDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"

#include "MenuDispCtrl.h"

#include "WorkSchedulerDlg.h"
#include "AutoSchedulingDlg001.h"
#include "AutoSchedulingDlg.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. �֐���
//		CAutoSchedulingDlg::CAutoSchedulingDlg
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
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CAutoSchedulingDlg::CAutoSchedulingDlg(CWnd* pParent /*=NULL*/)
	: CSubMenuDlg(CAutoSchedulingDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CAutoSchedulingDlg)
	m_nRadioKojun = 0;
	m_nRadioTactKeisan = 1;
	//}}AFX_DATA_INIT
}


void CAutoSchedulingDlg::DoDataExchange(CDataExchange* pDX)
{
	CSubMenuDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAutoSchedulingDlg)
	DDX_Control(pDX, IDC_COMBO_KIJUN_KISHU, m_cComboKijunKishu);
	DDX_Control(pDX, IDOK, m_cButtonExec);
	DDX_Radio(pDX, IDC_RADIO_KOJUN1, m_nRadioKojun);
	DDX_Radio(pDX, IDC_RADIO_MOKUHYO_TACT_YUSEN, m_nRadioTactKeisan);
	//}}AFX_DATA_MAP
	DDX_Control(pDX, IDC_CHECK_SUBLINE_1, m_aCheckSubline[0]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_2, m_aCheckSubline[1]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_3, m_aCheckSubline[2]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_4, m_aCheckSubline[3]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_5, m_aCheckSubline[4]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_6, m_aCheckSubline[5]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_7, m_aCheckSubline[6]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_8, m_aCheckSubline[7]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_9, m_aCheckSubline[8]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_10, m_aCheckSubline[9]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_11, m_aCheckSubline[10]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_12, m_aCheckSubline[11]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_13, m_aCheckSubline[12]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_14, m_aCheckSubline[13]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_15, m_aCheckSubline[14]);
}


BEGIN_MESSAGE_MAP(CAutoSchedulingDlg, CSubMenuDlg)
	//{{AFX_MSG_MAP(CAutoSchedulingDlg)
	ON_WM_SHOWWINDOW()
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CAutoSchedulingDlg::OnInitDialog
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
//		���j���[�{�^���̐F��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CAutoSchedulingDlg::OnInitDialog()
{
	CSubMenuDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_AutoSchedulingDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// ���j���[�{�^���̐F��ݒ�
	m_cButtonExec.SetBackGroundColor(COLOR_SUBMENU_BUTTON);

// Modify ... ( ADD )
	// UP4�p���W���[��
//	GetDlgItem( IDC_RADIO_KOJUN2 )->EnableWindow( FALSE );
// By Y.Itabashi (xxxxx) 2007.02.22

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAutoSchedulingDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����Ґ����s�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����Ґ������s����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CAutoSchedulingDlg::OnOK()
{
	if (!ComboDataCheck(m_cComboKijunKishu, "��@��I��"))
		return;

	UpdateData(TRUE);

	// �ΏۃT�u���C���擾
	CIntArrayEx aSublineId;
	int nSubline = g_pDataManager->GetSublineNum();
	for (int nSunlineId = 0; nSunlineId < nSubline; nSunlineId++) {
		if ((int)m_aCheckSubline[nSunlineId] != 0)
			aSublineId.Add(nSunlineId);
	}

	// �T�u���C���I���`�F�b�N
	if (aSublineId.GetSize() == 0) {
		g_pSystem->DispMessage("E009001");
		return;
	}

	// ��@��I���`�F�b�N
	int nSel = m_cComboKijunKishu.GetCurSel();
	if (nSel == -1) {
		g_pSystem->DispMessage("E009002");
		m_cComboKijunKishu.SetFocus();
		return;
	}

	// ��@��擾
	int nKijunKishuId = m_cComboKijunKishu.GetItemData(nSel);

// Modify ... ( ADD )
	// UG1�p���W���[��
//	m_nRadioKojun = 0;
// By Y.Itabashi (xxxxx) 2007.02.07

	// �����Ґ����s
	g_pDataManager->JidoHensei(aSublineId, m_nRadioKojun, m_nRadioTactKeisan, nKijunKishuId);

	// ���j���[���ڂ̗L����
	g_pSystem->EnableMenu();
}

void CAutoSchedulingDlg::OnShowWindow(BOOL bShow, UINT nStatus)
{
	// �T�u���C���̃`�F�b�N�{�b�N�X��\��
	if (bShow) {
		int nSublineId;
		int nSublineNum = g_pDataManager->GetSublineNum();

		for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
			m_aCheckSubline[nSublineId] = 1;
			m_aCheckSubline[nSublineId].SetWindowText(pSubline->sSublineName);
			m_aCheckSubline[nSublineId].ShowWindow(SW_SHOW);
		}

		for (; nSublineId < MAX_SUBLINE; nSublineId++)
			m_aCheckSubline[nSublineId].ShowWindow(SW_HIDE);

		int nMaxSeisanDaisu = 0;
		int nMaxKishuId = 0;
		m_cComboKijunKishu.ResetContent();
		int nKishuNum = g_pDataManager->GetKishuNum();
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
			m_cComboKijunKishu.SetItemData(m_cComboKijunKishu.AddString(pKishu->sKishuName), nKishuId);

			if (pKishu->nSeisanDaisu > nMaxSeisanDaisu) {
				nMaxSeisanDaisu = pKishu->nSeisanDaisu;
				nMaxKishuId = nKishuId;
			}
		}
		CGlobal::SelectComboBox(m_cComboKijunKishu, nMaxKishuId);
	}

	CSubMenuDlg::OnShowWindow(bShow, nStatus);
}

void CAutoSchedulingDlg::OnButtonZensentaku() 
{
	SetAllSublixxxheck(TRUE);
}

void CAutoSchedulingDlg::OnButtonZenkaijo() 
{
	SetAllSublixxxheck(FALSE);
}

void CAutoSchedulingDlg::SetAllSublixxxheck(BOOL bCheck)
{
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		if (m_aCheckSubline[nSublineId].IsWindowEnabled())
			m_aCheckSubline[nSublineId] = bCheck;
	}
}
