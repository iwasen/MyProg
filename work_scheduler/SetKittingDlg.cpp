//*****************************************************************************************************
//  1. �t�@�C����
//		SetKittingDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSetKittingDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "SetKittingDlg001.h"
#include "SetKittingDlg.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// Modify ... ( CHANGE )
// Lixxxtrl�ɕύX
// ���i�X�v���b�h�J�����ʒu
#define	COL_BUHIN_CHECK		0
#define COL_BUHIN_BLOCK		1
#define	COL_BUHIN_BUHINNO	2
#define	COL_BUHIN_BUHINNAME	3
#define	COL_BUHIN_PC		4

/*enum EBuhinList {
	eBuhinLixxxheck = 1,
	eBuhinListBlock = 2,
	eBuhinListBuhinNo = 3,
	eBuhinListBuhinName = 4,
	eBuhinListPC = 5
};*/

// �H���X�v���b�h�J�����ʒu
#define	COL_KOTEI_LISTNO		0
#define	COL_KOTEI_KOTEINAME		1
#define	COL_KOTEI_FUYOSAGYO		2

/*enum EKoteiList {
	eKoteiListNo = 1,
	eKoteiListKoteiName = 2,
	eKoteiListFuyoSagyo = 3
};*/

// �v�f��ƌQ�X�v���b�h�J�����ʒu
#define	COL_YOSOSAGYOGUN_LISTNO		0
#define	COL_YOSOSAGYOGUN_KOTEINAME	1
#define	COL_YOSOSAGYOGUN_FUYOSAGYO	2

/*enum EYosoSagyogunList {
	eYosoSagyogunListNo = 1,
	eYosoSagyogunListKoteiName = 2,
	eYosoSagyogunListFuyoSagyo = 3
};*/

// �v�f��ƃX�v���b�h�J�����ʒu
#define	COL_YOSOSAGYO_SAGYONO	0
#define	COL_YOSOSAGYO_YOSOSAGYO	1

/*enum EYosoSagyoList {
	eYosoSagyoListSagyoNo = 1,
	eYosoSagyoListYosoSagyo = 2
};*/

#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_CHECKOK	RGB(0xcc, 0xff, 0xff)
#define	COLOR_CHECKNON	RGB(0xff, 0xff, 0xff)
#define	COLOR_FUYOYES	RGB(0xff, 0xff, 0)
#define	COLOR_FUYONO	RGB(0xff, 0xff, 0xff)
// By Y.Itabashi (xxxxx) 2007.01.26

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::CSetKittingDlg
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
//		����
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CSetKittingDlg::CSetKittingDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CSetKittingDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetKittingDlg)
	//}}AFX_DATA_INIT

	m_nSublineId = -1;
	m_nSelectOff = 0;
}


void CSetKittingDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetKittingDlg)
	DDX_Control(pDX, IDC_LIST_BUHIN, m_cListBuhin);
	DDX_Control(pDX, IDC_LIST_KOTEI, m_cListKotei);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYOGUN, m_cListYosoSagyogun);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYO, m_cListYosoSagyo);
	DDX_Control(pDX, IDOK, m_cButtonOK);
	DDX_Control(pDX, IDC_EDIT_KIT_SA_JIKAN, m_cEditKitSaJikan);
	DDX_Control(pDX, IDC_EDIT_KIT_NASHI_GOKEI_JIKAN, m_cEditKitNashiGokeiJikan);
	DDX_Control(pDX, IDC_EDIT_KIT_ARI_GOKEI_JIKAN, m_cEditKitAriGokeiJikan);
	DDX_Control(pDX, IDC_COMBO_SUBLINE, m_cComboSubline);
//	DDX_Control(pDX, IDC_SPREAD_BUHIN, m_cSpreadBuhin);
//	DDX_Control(pDX, IDC_SPREAD_KOTEI, m_cSpreadKotei);
//	DDX_Control(pDX, IDC_SPREAD_YOSO_SAGYO, m_cSpreadYosoSagyo);
//	DDX_Control(pDX, IDC_SPREAD_YOSO_SAGYOGUN, m_cSpreadYosoSagyogun);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetKittingDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSetKittingDlg)
	ON_WM_SIZE()
	ON_CBN_SELCHANGE(IDC_COMBO_SUBLINE, OnSelchangeComboSubline)
	ON_BN_CLICKED(IDC_BUTTON_NEXT, OnButtonNext)
	ON_BN_CLICKED(IDC_BUTTON_BACK, OnButtonBack)
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	ON_BN_CLICKED(IDC_BUTTON_KITTING_TAISHO_BUHINHYO, OnButtonKittingTaishoBuhinhyo)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_BUHIN, OnItemchangedListBuhin)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_KOTEI, OnItemchangedListKotei)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYOGUN, OnItemchangedListYosoSagyogun)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYO, OnItemchangedListYosoSagyo)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USEREVENT_LISTEX_CLICK, OnListExClick)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnInitDialog
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
//		�T�u���C����\�����A�X�v���b�h�ɃL�b�g�����i��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CSetKittingDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	int i;
	CString sText;

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetKittingDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// �L�b�g���f�[�^�擾
	g_pDataManager->GetKittingData();

	// �T�u���C���\��
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		m_cComboSubline.SetItemData(m_cComboSubline.AddString(pSubline->sSublineName), nSublineId);
	}

// Modify ... ( DELETE )
// Lixxxtrl�ɕύX
//	m_cSpreadBuhin.SetMaxRows(0);
//	m_cSpreadKotei.SetMaxRows(0);
//	m_cSpreadYosoSagyogun.SetMaxRows(0);
//	m_cSpreadYosoSagyo.SetMaxRows(0);
// By Y.Itabashi (xxxxx) 2007.01.26

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	// �X�v���b�h�ɋ@�킲�Ƃ̗��ǉ�
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
		sprintf( Menu_SetKittingDlg[List_SetKittingBuhin[i+COL_BUHIN_PC].nHeaderNo].str1, "%s", pKishu->sKishuName );
	}

	SetLixxxolumn( m_cListBuhin, Menu_SetKittingDlg, List_SetKittingBuhin, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListKotei, Menu_SetKittingDlg, List_SetKittingKotei, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyogun, Menu_SetKittingDlg, List_SetKittingYosoSagyogun, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyo, Menu_SetKittingDlg, List_SetKittingYosoSagyo, eSELECTTYPE_SINGLE );

	m_cListBuhin.DeleteAllItems();
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
/*	// �X�v���b�h�ɋ@�킲�Ƃ̗��ǉ�
	int nKishuNum = g_pDataManager->GetKishuNum();
	m_cSpreadBuhin.SetMaxCols(eBuhinListPC - 1 + nKishuNum);
	for (i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);

		m_cSpreadBuhin.SetValue(0, eBuhinListPC + i, pKishu->sKishuName);

		m_cSpreadBuhin.SetRow(-1);
		m_cSpreadBuhin.SetCol(eBuhinListPC + i);
		m_cSpreadBuhin.SetTypeHAlign(1);
	}*/

	// �R���g���[���̈ʒu�𒲐�
	SetCtlPosition(IDC_BUTTON_KITTING_TAISHO_BUHINHYO, 1, 0, 1, 0);
	SetCtlPosition(IDC_LIST_BUHIN, 0, 0, 1, 0.5);
//	SetCtlPosition(IDC_SPREAD_BUHIN, 0, 0, 1, 0.5);
	SetCtlPosition(IDC_BUTTON_NEXT, 0.5, 0.5, 0.5, 0.5);
	SetCtlPosition(IDC_BUTTON_BACK, 0.5, 0.5, 0.5, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_NASHI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_NASHI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_ARI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_ARI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_SA_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_SA_JIKAN, 1, 0.5, 1, 0.5);
//	SetCtlPosition(IDC_SPREAD_KOTEI, 0, 0.5, 0.5, 0.75);
//	SetCtlPosition(IDC_SPREAD_YOSO_SAGYOGUN, 0, 0.75, 0.5, 1);
//	SetCtlPosition(IDC_SPREAD_YOSO_SAGYO, 0.5, 0.5, 1, 1);
	SetCtlPosition(IDC_LIST_KOTEI, 0, 0.5, 0.5, 0.75);
	SetCtlPosition(IDC_LIST_YOSO_SAGYOGUN, 0, 0.75, 0.5, 1);
	SetCtlPosition(IDC_LIST_YOSO_SAGYO, 0.5, 0.5, 1, 1);
	SetCtlPosition(IDOK, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);
// By Y.Itabashi (xxxxx) 2007.01.26

	m_cButtonOK.EnableWindow(FALSE);

	return TRUE;
}

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnClickSpreadBuhin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�v���b�h�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		long		Col			[I] �N���b�N������
//		long		Row			[I] �N���b�N�����s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�P��ڂ��N���b�N�����ꍇ�́A�X�v���b�h�̃`�F�b�N�{�b�N�X��ON/OFF����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadBuhin(long Col, long Row)
{
	if (Row > 0) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		// �P��I�����[�h�ɐݒ�
		m_cSpreadBuhin.SetOperationMode(3);

		// �`�F�b�N�{�b�N�X��ON/OFF��؂�ւ���
		if (Col == eBuhinLixxxheck) {
			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[m_cSpreadBuhin.GetRowItemData(Row)];

			if (cKittingBuhin.bCheck) {
				cKittingBuhin.bCheck = FALSE;
				m_cSpreadBuhin.SetValue(Row, Col, "0");
			} else {
				cKittingBuhin.bCheck = TRUE;
				m_cSpreadBuhin.SetValue(Row, Col, "1");
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnDblClickSpreadBuhin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�v���b�h�_�u���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		long		Col			[I] �N���b�N������
//		long		Row			[I] �N���b�N�����s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		Kitting��ƑI���_�C�A���O���J���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::OnDblClickSpreadBuhin(long Col, long Row)
{
	// Kitting��ƑI���_�C�A���O�\��
	if (Row > 0)
		SelectBuhin(Row);
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

void CSetKittingDlg::SelectBuhin(int nRow)
{
	if( m_nSelectOff == 1 )
		return;

	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	int nIndex = m_cListBuhin.GetItemRowData( nRow );
	m_pKittingBuhin = &pSubline->aKittingBuhin[nIndex];
//	m_pKittingBuhin = &pSubline->aKittingBuhin[m_cSpreadBuhin.GetRowItemData(nRow)];

	// �`�F�b�N�ς�
	pSubline->aCheckBuhin.Add(m_pKittingBuhin->sBuhinNo);
	m_cListBuhin.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);
//	m_cSpreadBuhin.SetBkColor(nRow, -1, RGB(0xcc, 0xcc, 0xcc));

	SetKoteiList();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
//	m_cSpreadYosoSagyogun.SetMaxRows(0);
//	m_cSpreadYosoSagyo.SetMaxRows(0);
// By Y.Itabashi (xxxxx) 2007.02.01
}

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		OK�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�L�b�g���ɂ��s�v��Ƃ��폜���A�L�b�g����ƃe���v���[�g�ǉ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::OnOK()
{
	// �L�b�g�����i���݃`�F�b�N
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	BOOL bKitting = FALSE;
	int nBuhinNum = pSubline->aKittingBuhin.GetSize();
	for (int i = 0; i < nBuhinNum; i++) {
		if (pSubline->aKittingBuhin[i].bCheck) {
			bKitting = TRUE;
			break;
		}
	}
	if (bKitting) {
		if (g_pSystem->DispMessage("Q008002") == IDNO)
			return;
	} else {
		if (g_pSystem->DispMessage("Q008001") == IDNO)
			return;
	}

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (m_aKittingSublineId.Find(nSublineId) != -1) {
			// �L�b�g������
			g_pDataManager->CancelKitting(nSublineId);

			// �L�b�g�����i���݃`�F�b�N
			pSubline->bSetKitting = FALSE;
			int nBuhinNum = pSubline->aKittingBuhin.GetSize();
			for (int i = 0; i < nBuhinNum; i++) {
				if (pSubline->aKittingBuhin[i].bCheck) {
					pSubline->bSetKitting = TRUE;
					break;
				}
			}

			// �L�b�g���ɂ��s�v��ƍ폜
			g_pDataManager->DeleteKittingSagyo(nSublineId);

			if (pSubline->bSetKitting) {
				// �L�b�g����ƃe���v���[�g�ǉ�
				g_pDataManager->AddKittingTemplate(nSublineId);
			}
		}
	}
	
	// ���i�L�b�g�����t�@�C���ݒ�
	g_pDataManager->SetBuhinKitting();

	// �f�[�^�X�V�t���O�ݒ�
	g_pDataManager->SetUpdate();

	EndDialog(IDOK);
}

// WM_SIZE���b�Z�[�W�n���h��
void CSetKittingDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	CWnd *pParentWnd = GetParent();
	if (pParentWnd != NULL) {
		switch (nType) {
		case SIZE_MINIMIZED:
			// �e�E�B���h�E���ŏ���
			pParentWnd->ShowWindow(SW_MINIMIZE);
			pParentWnd->EnableWindow(TRUE);
			break;
		case SIZE_RESTORED:
			// �e�E�B���h�E�𖳌���
			pParentWnd->EnableWindow(FALSE);
			EnableWindow(TRUE);
			break;
		}
	}
}

// �T�u���C���I��
void CSetKittingDlg::OnSelchangeComboSubline() 
{
	int nIndex = m_cComboSubline.GetCurSel();
	if (nIndex != -1) {
		m_nSublineId = m_cComboSubline.GetItemData(nIndex);

		// �X�v���b�h�ɕ��i��\��
		SetBuhinList();

		// �L�b�g�����s�Ώېݒ�
		m_aKittingSublineId.AddUnique(m_nSublineId);

		m_cButtonOK.EnableWindow(TRUE);
	}
}

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnClickSpreadKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���X�v���b�h�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		long		Col			[I] �N���b�N������
//		long		Row			[I] �N���b�N�����s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƌQ�X�v���b�h��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadKotei(long Col, long Row)
{
	if (Row > 0) {
		// �P��I�����[�h�ɐݒ�
		m_cSpreadKotei.SetOperationMode(3);

		// �v�f��ƌQ�X�v���b�h��\��
		m_cSpreadKotei.GetValue(Row, eKoteiListKoteiName, m_sKoteiName);
		SetYosoSagyogunList();
	}
}*/

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnClickSpreadYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ�X�v���b�h�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		long		Col			[I] �N���b�N������
//		long		Row			[I] �N���b�N�����s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƃX�v���b�h��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadYosoSagyogun(long Col, long Row)
{
	if (Row > 0) {
		// �P��I�����[�h�ɐݒ�
		m_cSpreadYosoSagyogun.SetOperationMode(3);

		// �v�f��ƃX�v���b�h��\��
		m_nKittingYosoSagyogunIndex = m_cSpreadYosoSagyogun.GetRowItemData(Row);
		SetYosoSagyoList();
	}
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::OnClickSpreadYosoSagyo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƃX�v���b�h�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		long		Col			[I] �N���b�N������
//		long		Row			[I] �N���b�N�����s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�폜�t���O��ON/OFF��؂�ւ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::OnClickSpreadYosoSagyo(long Col, long Row)
{
	// �폜�t���O��ON/OFF��؂�ւ���
	if (Row > 0) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cListYosoSagyo.GetItemRowData(Row)];
//		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cSpreadYosoSagyo.GetItemData(Row)];
// By Y.Itabashi (xxxxx) 2007.01.26

		if (cKittingYosoSagyo.bSakujoFlag) {
			cKittingYosoSagyo.bSakujoFlag = FALSE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_CHECKNON);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0xff));
		} else {
			cKittingYosoSagyo.bSakujoFlag = TRUE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_CHECKOK);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0));
		}

		SetKoteiList(FALSE);
		SetYosoSagyogunList(FALSE);

		// ���v���Ԃ��v�Z
		double fKitNashiJikan = 0;
		double fKitAriJikan = 0;
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (int i = 0; i < nYosoSagyoNum; i++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];

			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}

		// ���v���ԕ\��
		DispTotalTime(fKitNashiJikan, fKitAriJikan);
	}
}

void CSetKittingDlg::OnButtonKittingTaishoBuhinhyo() 
{
	CString sPathName = CGlobal::FileSaveDlg(g_pSystem->m_cIniUser.m_sDataPath, "Excel Files (*.xls)|*.xls|All Files (*.*)|*.*||", "", "Title", ".xls");
	if (!sPathName.IsEmpty())
		g_pDataManager->SaveKittingTaishoBuhin(sPathName);
}

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingDlg::SetBuhinList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���i�X�v���b�h�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�X�v���b�h�̊e��ɕ��i�f�[�^���Z�b�g����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::SetBuhinList()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;

	int nKishuNum = g_pDataManager->GetKishuNum();

	int nKittingBuhinNum = pSubline->aKittingBuhin.GetSize();
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	m_cListBuhin.DeleteAllItems();
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
	for (i = 0; i < nKittingBuhinNum; i++) {
		SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[i];
		int nRow = i;

		// �`�F�b�N�{�b�N�X
		m_cListBuhin.SetValue(nRow, COL_BUHIN_CHECK, cKittingBuhin.bCheck ? true : false);

		// BLOCK�ԍ�
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BLOCK, cKittingBuhin.sBlockNo);

		// ���i�ԍ�
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BUHINNO, cKittingBuhin.sBuhinNo);

		// ���i����
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BUHINNAME, cKittingBuhin.sBuhinName);

		// PC
		for  (j = 0; j < nKishuNum; j++)
			m_cListBuhin.SetValue(nRow, COL_BUHIN_PC + j, cKittingBuhin.aPC[j]);

		// �ݒ�ς݂͐F��ς���
		m_cListBuhin.SetItemRowColor(nRow, COLOR_TEXT,
			pSubline->aCheckBuhin.Find(cKittingBuhin.sBuhinNo) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);

		m_cListBuhin.SetItemRowData(nRow, i);
	}

	UpdateWindow();

/*	m_cSpreadBuhin.SetMaxRows(nKittingBuhinNum);
	m_cSpreadBuhin.SetTopRow(0);
	for (i = 0; i < nKittingBuhinNum; i++) {
		SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[i];
		int nRow = i + 1;

		// �ݒ�ς݂͐F��ς���
		m_cSpreadBuhin.SetBkColor(nRow, -1, pSubline->aCheckBuhin.Find(cKittingBuhin.sBuhinNo) != -1 ? RGB(0xcc, 0xcc, 0xcc) : RGB(0xff, 0xff, 0xff));

		// �`�F�b�N�{�b�N�X
		m_cSpreadBuhin.SetValue(nRow, eBuhinLixxxheck, cKittingBuhin.bCheck ? "1" : "0");

		// BLOCK�ԍ�
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBlock, cKittingBuhin.sBlockNo);

		// ���i�ԍ�
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBuhinNo, cKittingBuhin.sBuhinNo);

		// ���i����
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBuhinName, cKittingBuhin.sBuhinName);

		// PC
		for  (j = 0; j < nKishuNum; j++)
			m_cSpreadBuhin.SetValue(nRow, eBuhinListPC + j, cKittingBuhin.aPC[j]);

		m_cSpreadBuhin.SetRowItemData(nRow, i);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	// ���v���ԕ\��
	DispTotalTime(0, 0);
}

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingSagyoDlg::SetKoteiList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���X�v���b�h�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���X�v���b�h�ɍH������ݒ肵�A�v�f��ƌQ�X�v���b�h�A�v�f��ƃX�v���b�h���N���A����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::SetKoteiList(BOOL bInitFlag)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// �H�������擾
	CKoteiListArray aKoteiList;
	int nYosoSagyogun = m_pKittingBuhin->aKittingYosoSagyogunId.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_pKittingBuhin->aKittingYosoSagyogunId[i]];
		SKoteiList *pKoteiList = aKoteiList.LookupElement(cKittingYosoSagyogun.sKoteiName);
		if (pKoteiList == NULL) {
			pKoteiList = aKoteiList.AddElement(cKittingYosoSagyogun.sKoteiName);
			pKoteiList->sKoteiName = cKittingYosoSagyogun.sKoteiName;
			pKoteiList->bFuyoSagyo = FALSE;
		}

		// �s�v��ƃt���O�ݒ�
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyoNum; j++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
			if (cKittingYosoSagyo.bSakujoFlag) {
				pKoteiList->bFuyoSagyo = TRUE;
				break;
			}
		}

		// ���v���Ԍv�Z
		for (j = 0; j < nYosoSagyoNum; j++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}
	}

	int nKoteiNum = aKoteiList.GetSize();

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	if (bInitFlag) {
		// �X�v���b�h�̍s����������
		m_cListYosoSagyogun.DeleteAllItems();
		m_cListYosoSagyo.DeleteAllItems();
/*		m_cSpreadKotei.SetMaxRows(nKoteiNum);
		m_cSpreadKotei.SetTopRow(0);
		m_cSpreadYosoSagyogun.SetMaxRows(0);
		m_cSpreadYosoSagyo.SetMaxRows(0);

		// �X�v���b�h��ǂݏo����p���[�h�ɐݒ�
		m_cSpreadKotei.SetOperationMode(1);
		m_cSpreadYosoSagyogun.SetOperationMode(1);
		m_cSpreadYosoSagyo.SetOperationMode(1);*/

		// ���v���ԕ\��
		DispTotalTime(fKitNashiJikan, fKitAriJikan);

		// �H���X�v���b�h�ɍH������ݒ�
		m_cListKotei.DeleteAllItems();
		for (i = 0; i < nKoteiNum; i++) {
			SKoteiList &cKoteiList = aKoteiList[i];
			int nRow = i;

			m_cListKotei.SetValue(nRow, COL_KOTEI_LISTNO, nRow+1);
			m_cListKotei.SetValue(nRow, COL_KOTEI_KOTEINAME, cKoteiList.sKoteiName);
			m_cListKotei.SetValue(nRow, COL_KOTEI_FUYOSAGYO, cKoteiList.bFuyoSagyo ? "*" : "");

			// �ݒ�ς݂͐F��ς���
			m_cListKotei.SetItemRowColor(nRow, COLOR_TEXT,
				pSubline->aCheckKotei.Find(cKoteiList.sKoteiName) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);
		}
	} else {
		for (i = 0; i < nKoteiNum; i++) {
			SKoteiList &cKoteiList = aKoteiList[i];
			int nRow = i;

			m_cListKotei.SetValue(nRow, COL_KOTEI_FUYOSAGYO, cKoteiList.bFuyoSagyo ? "*" : "");
		}
	}
}

// Modify ... ( CHANGE )
void CSetKittingDlg::SelectKotei(int nRow)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

	m_cListKotei.GetValue(nRow, COL_KOTEI_KOTEINAME, m_sKoteiName);

	// �`�F�b�N�ς�
	pSubline->aCheckKotei.Add(m_sKoteiName);
	m_cListKotei.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);

	SetYosoSagyogunList();
	m_cListYosoSagyo.DeleteAllItems();
}

void CSetKittingDlg::SelectYosoSagyogun(int nRow)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

	CString	sYosoSagyogunName;
	m_cListYosoSagyogun.GetValue(nRow, COL_YOSOSAGYOGUN_KOTEINAME, sYosoSagyogunName);

	// �`�F�b�N�ς�
	pSubline->aCheckYosoSagyogun.Add(sYosoSagyogunName);
	m_cListYosoSagyogun.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);

	m_nKittingYosoSagyogunIndex = m_cListYosoSagyogun.GetItemRowData(nRow);
	SetYosoSagyoList();
}
// By Y.Itabashi (xxxxx) 2007.01.29

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingSagyoDlg::SetYosoSagyogunList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ�X�v���b�h�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƌQ�X�v���b�h�ɗv�f��ƌQ����ݒ肵�A�v�f��ƃX�v���b�h���N���A����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::SetYosoSagyogunList(BOOL bInitFlag)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;
	int nIndex;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// �v�f��ƌQ���擾
	CYosoSagyogunListArray aYosoSagyogunList;
	int nYosoSagyogun = m_pKittingBuhin->aKittingYosoSagyogunId.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		nIndex = m_pKittingBuhin->aKittingYosoSagyogunId[i];
		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[nIndex];
		if (cKittingYosoSagyogun.sKoteiName == m_sKoteiName) {
			SYosoSagyogunList *pYosoSagyogunList = aYosoSagyogunList.AddElement();
			pYosoSagyogunList->sYosoSagyogunName = cKittingYosoSagyogun.sYosoSagyogunName;
			pYosoSagyogunList->nIndex = nIndex;
			pYosoSagyogunList->bFuyoSagyo = FALSE;

			// �s�v��ƃt���O�ݒ�
			int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
			for (j = 0; j < nYosoSagyoNum; j++) {
				SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
				if (cKittingYosoSagyo.bSakujoFlag) {
					pYosoSagyogunList->bFuyoSagyo = TRUE;
					break;
				}
			}

			// ���v���Ԍv�Z
			for (j = 0; j < nYosoSagyoNum; j++) {
				SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
				fKitNashiJikan += cKittingYosoSagyo.fTime;
				if (!cKittingYosoSagyo.bSakujoFlag)
					fKitAriJikan += cKittingYosoSagyo.fTime;
			}
		}
	}

	nYosoSagyogun = aYosoSagyogunList.GetSize();

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	if (bInitFlag) {
		// �X�v���b�h�̍s����������
		m_cListYosoSagyo.DeleteAllItems();
/*		m_cSpreadYosoSagyogun.SetMaxRows(nYosoSagyogun);
		m_cSpreadYosoSagyogun.SetTopRow(0);
		m_cSpreadYosoSagyo.SetMaxRows(0);

		// �X�v���b�h��ǂݏo����p���[�h�ɐݒ�
		m_cSpreadYosoSagyogun.SetOperationMode(1);
		m_cSpreadYosoSagyo.SetOperationMode(1);*/

		// ���v���ԕ\��
		DispTotalTime(fKitNashiJikan, fKitAriJikan);

		// �v�f��ƌQ�X�v���b�h�ɗv�f��ƌQ��ݒ�
		m_cListYosoSagyogun.DeleteAllItems();
		for (i = 0; i < nYosoSagyogun; i++) {
			SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
			int nRow = i;

			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_LISTNO, nRow+1);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_KOTEINAME, cYosoSagyogunList.sYosoSagyogunName);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_FUYOSAGYO, cYosoSagyogunList.bFuyoSagyo ? "*" : "");

			m_cListYosoSagyogun.SetItemRowData(nRow, cYosoSagyogunList.nIndex);

			// �ݒ�ς݂͐F��ς���
			m_cListYosoSagyogun.SetItemRowColor(nRow, COLOR_TEXT,
				pSubline->aCheckYosoSagyogun.Find(cYosoSagyogunList.sYosoSagyogunName) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);
		}
	} else {
		for (i = 0; i < nYosoSagyogun; i++) {
			SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
			int nRow = i;

			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_FUYOSAGYO, cYosoSagyogunList.bFuyoSagyo ? "*" : "");
		}
	}

/*	// �v�f��ƌQ�X�v���b�h�ɗv�f��ƌQ��ݒ�
	for (i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
		int nRow = i + 1;

		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListNo, nRow);
		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListKoteiName, cYosoSagyogunList.sYosoSagyogunName);
		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListFuyoSagyo, cYosoSagyogunList.bFuyoSagyo ? "*" : "");

		m_cSpreadYosoSagyogun.SetRowItemData(nRow, cYosoSagyogunList.nIndex);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. �֐���
//		CSetKittingSagyoDlg::SetYosoSagyoList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƃX�v���b�h�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƃX�v���b�h�ɗv�f��Ɩ���ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetKittingDlg::SetYosoSagyoList()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// �v�f��Ƃ��擾
	SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	// �X�v���b�h�̍s����������
	int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
//	m_cSpreadYosoSagyo.SetMaxRows(nYosoSagyoNum);
//	m_cSpreadYosoSagyo.SetTopRow(0);

	// �v�f��ƃX�v���b�h�ɗv�f��Ƃ�ݒ�
	m_cListYosoSagyo.DeleteAllItems();
	for (i = 0; i < nYosoSagyoNum; i++) {
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];
		int nRow = i;

		m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, cKittingYosoSagyo.nSagyoNo);
		m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYO, cKittingYosoSagyo.sYosoSagyoName);

		m_cListYosoSagyo.SetItemRowColor(nRow, COLOR_TEXT,
			cKittingYosoSagyo.bSakujoFlag ? COLOR_FUYOYES : COLOR_FUYONO);
		m_cListYosoSagyo.SetItemRowData(nRow, i);

		fKitNashiJikan += cKittingYosoSagyo.fTime;
		if (!cKittingYosoSagyo.bSakujoFlag)
			fKitAriJikan += cKittingYosoSagyo.fTime;
	}

/*	for (i = 0; i < nYosoSagyoNum; i++) {
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];
		int nRow = i + 1;

		m_cSpreadYosoSagyo.SetValue(nRow, eYosoSagyoListSagyoNo, cKittingYosoSagyo.nSagyoNo);
		m_cSpreadYosoSagyo.SetValue(nRow, eYosoSagyoListYosoSagyo, cKittingYosoSagyo.sYosoSagyoName);

		m_cSpreadYosoSagyo.SetBkColor(nRow, -1, cKittingYosoSagyo.bSakujoFlag ? RGB(0xff, 0xff, 0) : RGB(0xff, 0xff, 0xff));
		m_cSpreadYosoSagyo.SetRowItemData(nRow, i);

		fKitNashiJikan += cKittingYosoSagyo.fTime;
		if (!cKittingYosoSagyo.bSakujoFlag)
			fKitAriJikan += cKittingYosoSagyo.fTime;
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	// ���v���ԕ\��
	DispTotalTime(fKitNashiJikan, fKitAriJikan);
}

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
void CSetKittingDlg::OnButtonNext() 
{
	POSITION pos = m_cListBuhin.GetFirstSelectedItemPosition();
	int nRow = m_cListBuhin.GetNextSelectedItem( pos );
	if (nRow >= 0 && nRow < m_cListBuhin.GetItemCount()-1) {
		nRow++;

		bool bCheck;
		m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		while( !bCheck ){
			if( nRow == m_cListBuhin.GetItemCount()-1 )
				return;

			nRow++;
			m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		}

		m_cListBuhin.SelectChange(nRow);
		SelectBuhin(nRow);
	}

/*	int nRow = m_cSpreadBuhin.GetActiveRow();
	if (nRow > 0 && nRow < m_cSpreadBuhin.GetMaxRows()) {
		nRow++;
		m_cSpreadBuhin.SetActiveCell(0, nRow);
		SelectBuhin(nRow);
	}*/
}

void CSetKittingDlg::OnButtonBack() 
{
	POSITION pos = m_cListBuhin.GetFirstSelectedItemPosition();
	int nRow = m_cListBuhin.GetNextSelectedItem( pos );
	if (nRow > 0 && nRow < m_cListBuhin.GetItemCount()) {
		nRow--;

		bool bCheck;
		m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		while( !bCheck ){
			if( nRow == 0 )
				return;

			nRow--;
			m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		}

		m_cListBuhin.SelectChange(nRow);
		SelectBuhin(nRow);
	}

/*	int nRow = m_cSpreadBuhin.GetActiveRow();
	if (nRow > 1 && nRow <= m_cSpreadBuhin.GetMaxRows()) {
		nRow--;
		m_cSpreadBuhin.SetActiveCell(0, nRow);
		SelectBuhin(nRow);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetKittingDlg::OnButtonZensentaku() 
{
// Modify ... ( CAHNGE )
	m_nSelectOff = 1;

	SetCheckAll(TRUE);

	m_nSelectOff = 0;
// By Y.Itabashi (xxxxx) 2007.01.30
}

void CSetKittingDlg::OnButtonZenkaijo() 
{
// Modify ... ( CAHNGE )
	m_nSelectOff = 1;

	SetCheckAll(FALSE);

	m_nSelectOff = 0;
// By Y.Itabashi (xxxxx) 2007.01.30
}

void CSetKittingDlg::SetCheckAll(BOOL bCheck)
{
// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
	if (m_nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
		int nSize = pSubline->aKittingBuhin.GetSize();
		for (int i = 0; i < nSize; i++)
			pSubline->aKittingBuhin[i].bCheck = bCheck;

		int nMaxRows = m_cListBuhin.GetItemCount();
		LVITEM	item;
		for (int nRow = 0; nRow < nMaxRows; nRow++){
			CString	sValue = ( bCheck ) ? "*" : "";

			item.mask		= LVIF_TEXT;
			item.iItem		= nRow;
			item.iSubItem	= COL_BUHIN_CHECK;
			item.pszText	= sValue.GetBuffer(strlen(sValue));
			item.cchTextMax	= strlen(sValue);

			m_cListBuhin.SetItem(&item);
		}
	}

/*	if (m_nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
		int nSize = pSubline->aKittingBuhin.GetSize();
		for (int i = 0; i < nSize; i++)
			pSubline->aKittingBuhin[i].bCheck = bCheck;

		int nMaxRows = m_cSpreadBuhin.GetMaxRows();
		for (int nRow = 1; nRow <= nMaxRows; nRow++)
			m_cSpreadBuhin.SetValue(nRow, eBuhinLixxxheck, bCheck ? "1" : "0");
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetKittingDlg::DispTotalTime(double fKitNashiJikan, double fKitAriJikan)
{
	if (fKitNashiJikan == 0) {
		m_cEditKitNashiGokeiJikan.Blank();
		m_cEditKitAriGokeiJikan.Blank();
		m_cEditKitSaJikan.Blank();
	} else {
		m_cEditKitNashiGokeiJikan.Format("%.6f", fKitNashiJikan);
		m_cEditKitAriGokeiJikan.Format("%.6f", fKitAriJikan);
		m_cEditKitSaJikan.Format("%.6f", fKitNashiJikan - fKitAriJikan);
	}
}

void CSetKittingDlg::OnCancel() 
{
	// Undo�o�b�t�@���畜��
	g_pDataManager->RestoreUndoData();
	
	CDialogEx::OnCancel();
}

// Modify ... ( CAHNGE )
// Lixxxtrl�ɕύX
void CSetKittingDlg::OnItemchangedListBuhin(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	// Kitting��ƑI���_�C�A���O�\��
// --Add 2007/03/06 aizawa �s��(84)
//	if (Row >= 0){
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED)) {
// --Add
		if( Col == 0 ){
			SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

			bool bCheck;
			m_cListBuhin.GetValue( Row, Col, bCheck );
// Modify ... ( CHANGE )
			int nIndex = m_cListBuhin.GetItemRowData( Row );
			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[nIndex];
//			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[Row];
// By Y.Itabashi (xxxxx) 
			cKittingBuhin.bCheck = bCheck;
		} 
		SelectBuhin(Row);
	}
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

// Modify ... ( CHANGE )
// --Add 2007/03/06 aizawa �s��(84)
//	if (Row >= 0)
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED))
// --Add
		SelectKotei(Row);
/*	if (Row >= 0) {

		// �v�f��ƌQ�X�v���b�h��\��
		m_cListKotei.GetValue(Row, COL_KOTEI_KOTEINAME, m_sKoteiName);
		SetYosoSagyogunList();
	}*/
// By Y.Itabashi (xxxxx) 2007.01.29
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

// Modify ... ( CHANGE )
// --Add 2007/03/06 aizawa �s��(84)
//	if (Row >= 0)
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED))
// --Add
		SelectYosoSagyogun(Row);
/*	if (Row >= 0) {
		// �v�f��ƃX�v���b�h��\��
		m_nKittingYosoSagyogunIndex = m_cListYosoSagyogun.GetItemRowData(Row);
		SetYosoSagyoList();
	}*/
// By Y.Itabashi (xxxxx) 2007.01.29
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListYosoSagyo(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	// �폜�t���O��ON/OFF��؂�ւ���
// --Add 2007/03/06 aizawa �s��(84)
//	if (Row >= 0) {
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED)) {
// --Add
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cListYosoSagyo.GetItemRowData(Row)];

		if (cKittingYosoSagyo.bSakujoFlag) {
			cKittingYosoSagyo.bSakujoFlag = FALSE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_FUYONO);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0xff));
		} else {
			cKittingYosoSagyo.bSakujoFlag = TRUE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_FUYOYES);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0));
		}

		SetKoteiList(FALSE);
		SetYosoSagyogunList(FALSE);

		// ���v���Ԃ��v�Z
		double fKitNashiJikan = 0;
		double fKitAriJikan = 0;
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (int i = 0; i < nYosoSagyoNum; i++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];

			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}

		// ���v���ԕ\��
		DispTotalTime(fKitNashiJikan, fKitAriJikan);
	}
	
	*pResult = 0;
}
// By Y.Itabashi (xxxxx) 2007.01.26

LRESULT CSetKittingDlg::OnListExClick(WPARAM wParam, LPARAM lParam)
{
	NMHDR* pNMHdr = (NMHDR*)wParam;
	LRESULT result;

	switch (pNMHdr->idFrom) {
	case IDC_LIST_BUHIN:
		OnItemchangedListBuhin(pNMHdr, &result);
		break;
	case IDC_LIST_KOTEI:
		OnItemchangedListKotei(pNMHdr, &result);
		break;
	}

	return 0;
}
