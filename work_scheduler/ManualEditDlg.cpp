// ManualEditDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"
#include "EVal.h"

#include "MenuDispCtrl.h"

#include "ManualEditDlg001.h"
#include "ManualEditDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// �H�����X�g�J�����ʒu
#define	COL_KOTEI_SUBLINE		0
#define	COL_KOTEI_KIGOU			1
#define	COL_KOTEI_BANGO			2
#define	COL_KOTEI_NAME			3
#define	COL_KOTEI_JIDOU			4

// �v�f��ƌQ���X�g�J�����ʒu
#define	COL_YOSOSAGYOGUN_LISTNO		0
#define	COL_YOSOSAGYOGUN_NAME		1

// �v�f��ƃ��X�g�J�����ʒu
#define	COL_YOSOSAGYO_SAGYONO		0
#define	COL_YOSOSAGYO_YOSOSAGYO		1
#define	COL_YOSOSAGYO_YOSOSAGYONO	2
#define	COL_YOSOSAGYO_M				3
#define	COL_YOSOSAGYO_N				4
#define	COL_YOSOSAGYO_HINDO			5
#define	COL_YOSOSAGYO_KURIKAESI		6
#define	COL_YOSOSAGYO_KOTEI			7
#define	COL_YOSOSAGYO_KIJUN		8

// �\���F
#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_KYOTSU	RGB(0xff, 0xff, 0x00)
#define	COLOR_ERROR		RGB(0xff, 0x00, 0x00)

// �ҏW�t���O
#define EDIT_NONE		0
#define EDIT_CHANGE		1
#define EDIT_DELETE		2
#define EDIT_INSERT		3


/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg �_�C�A���O


CManualEditDlg::CManualEditDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualEditDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualEditDlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_INIT

	m_nSublineId = -1;
	m_nSagyoshaId = -1;
}


void CManualEditDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualEditDlg)
	DDX_Control(pDX, IDC_BUTTON_YOSO_SAGYO_TSUIKA, m_cButtonYosoSagyoTsuika);
	DDX_Control(pDX, IDC_BUTTON_YOSO_SAGYO_SAKUJO, m_cButtonYosoSagyoSakujo);
	DDX_Control(pDX, IDC_EDIT_KISHU_NAME, m_cEditKishuName);
	DDX_Control(pDX, IDC_LIST_KOTEI, m_cListKotei);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYOGUN, m_cListYosoSagyogun);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYO, m_cListYosoSagyo);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA, m_cComboSagyosha);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualEditDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualEditDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA, OnSelchangeComboSagyosha)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_KOTEI, OnItemchangedListKotei)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYOGUN, OnItemchangedListYosoSagyogun)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYO_SAKUJO, OnButtonYosoSagyoSakujo)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYOGUN_SAKUJO, OnButtonYosoSagyogunSakujo)
	ON_BN_CLICKED(IDC_BUTTON_KOTEI_SAKUJO, OnButtonKoteiSakujo)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYO_TSUIKA, OnButtonYosoSagyoTsuika)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYOGUN_TSUIKA, OnButtonYosoSagyogunTsuika)
	ON_BN_CLICKED(IDC_BUTTON_KOTEI_TSUIKA, OnButtonKoteiTsuika)
	ON_BN_CLICKED(IDC_BUTTON_PREVIEW, OnButtonPreview)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER + 0x0502, CreateComboList)
	ON_MESSAGE(WM_USER + 0x0503, OnChangeCombo)
	ON_MESSAGE(WM_USER + 0x0504, OnChangeEdit)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg ���b�Z�[�W �n���h��

BOOL CManualEditDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();
	
// Modify ... ( CHANGE )
	// �v�f��ƃe���v���[�g��ǂݍ���
	if (g_pDataManager->GetYosoSagyoTemplateNum() == 0) {
//		if (!g_pDataManager->ReadYosoSagyoTemplate()) {
			EndDialog(IDCANCEL);
			return TRUE;
//		}
	}
// By Y.Itabashi (xxxxx) 2007.02.16

	// ���j���[�Ƀ��j���[�t�@�C���̕�������Z�b�g
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualEditDlg );

	// ���X�g�̃J���������Z�b�g
	SetLixxxolumn( m_cListKotei, Menu_ManualEditDlg, List_ManualEditKotei, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyogun, Menu_ManualEditDlg, List_ManualEditYosoSagyogun, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyo, Menu_ManualEditDlg, List_ManualEditYosoSagyo, eSELECTTYPE_SINGLE );

	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// ���X�g�f�[�^�쐬
	MakeSagyoshaList();

	// ��Ǝ҃R���{�{�b�N�X��ݒ�
	SetSagyoshaList();

	// �f�t�H���g�̍�Ǝ҂�I��
	if (m_nSagyoshaId != -1) {
		int nCount = m_cComboSagyosha.GetCount();
		for (int i = 0; i < nCount; i++) {
			int nIndex = m_cComboSagyosha.GetItemData(i);
			if (m_aSagyoshaList[nIndex].nSagyoshaId == m_nSagyoshaId) {
				m_cComboSagyosha.SetCurSel(i);
				OnSelchangeComboSagyosha();
				break;
			}
		}
	}

	// �@�햼��\��
	if (m_nKishuId != -1)
		m_cEditKishuName = g_pDataManager->GetKishuData(m_nKishuId)->sKishuName;

	// �R���g���[���̈ʒu�𒲐�
	SetCtlPosition(IDC_STATIC005, 0, 0, 0, 0.5);
	SetCtlPosition(IDC_LIST_KOTEI, 0, 0, 0, 0.5);
	SetCtlPosition(IDC_STATIC013, 0, 0.5, 0, 1);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYOGUN_TSUIKA, 0, 0.5, 0, 0.5);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYOGUN_SAKUJO, 0, 0.5, 0, 0.5);
	SetCtlPosition(IDC_LIST_YOSO_SAGYOGUN, 0, 0.5, 0, 1);
	SetCtlPosition(IDC_STATIC018, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYO_TSUIKA, 1, 0, 1, 0);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYO_SAKUJO, 1, 0, 1, 0);
	SetCtlPosition(IDC_LIST_YOSO_SAGYO, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PREVIEW, 1, 1, 1, 1);
	SetCtlPosition(IDOK, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	return TRUE;
}

// ��Ǝ҃R���{�{�b�N�X�I��ύX
void CManualEditDlg::OnSelchangeComboSagyosha() 
{
	// �H�����X�g�\��
	SetKoteiList();
}

// �H�����X�g�I��ύX
void CManualEditDlg::OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// �v�f��ƌQ���X�g�\��
	SetYosoSagyogunList();

	// �v�f��Ƃ̒ǉ��E�폜�{�^���L�����ݒ�
	EnableYosoSagyoTsuikaSakujo();

	*pResult = 0;
}

// �v�f��ƌQ���X�g�I��ύX
void CManualEditDlg::OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// �v�f��ƃ��X�g�\��
	SetYosoSagyoList();

	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditDlg::SetSagyoshaList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Ǝ҃��X�g�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���E�̍�Ǝ҃R���{�{�b�N�X�ɍ�Ǝ҂�ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//	
//*****************************************************************************************************
void CManualEditDlg::SetSagyoshaList()
{
	int nSize = m_aSagyoshaList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[i];

		// �R���{�{�b�N�X�ɍ�ƎҖ���ݒ�
		m_cComboSagyosha.SetItemData(m_cComboSagyosha.AddString(cSagyoshaList.sSagyoshaName), i);
	}
}

// �H�����X�g�ݒ�
void CManualEditDlg::SetKoteiList(int nSelectIndex) 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// ���X�g���N���A
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// �H�����X�g�Ƀf�[�^���Z�b�g
	int nRow = 0;
	int nSize = cSagyoshaList.aKoteiList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[i];

		if (cKoteiList.nEditFlag != EDIT_DELETE) {
			// �e�J�����Ƀf�[�^���Z�b�g
			m_cListKotei.SetValue(nRow, COL_KOTEI_SUBLINE, cKoteiList.sSubline);
			m_cListKotei.SetValue(nRow, COL_KOTEI_KIGOU, cKoteiList.sKoteiKigo);
			m_cListKotei.SetValue(nRow, COL_KOTEI_BANGO, cKoteiList.sKoteiNo);
			m_cListKotei.SetValue(nRow, COL_KOTEI_NAME, cKoteiList.sKoteiName);
			m_cListKotei.SetValue(nRow, COL_KOTEI_JIDOU, cKoteiList.sJidoShudo);

			// ���ʍH�������F�ɂ���
			if (cKoteiList.bKyotsuKotei)
				m_cListKotei.SetItemRowColor( nRow, COLOR_TEXT, COLOR_KYOTSU );

			// �G���[�s��ԐF�ɂ���
			if (cKoteiList.bErrorFlag)
				m_cListKotei.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// �sID���Z�b�g
			m_cListKotei.SetItemRowData(nRow, i);

			// �w�肳�ꂽ�s��I����Ԃɂ���
			if (i == nSelectIndex)
				m_cListKotei.SelectChange(nRow);

			nRow++;
		}
	}
}

// �v�f��ƌQ���X�g�ݒ�
void CManualEditDlg::SetYosoSagyogunList(int nSelectIndex)
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// ���X�g���N���A
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// �v�f��ƌQ���X�g�ɗv�f��ƌQ��ݒ�
	int nRow = 0;
	int nSize = cKoteiList.aYosoSagyogunList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[i];

		if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
			// �e�J�����Ƀf�[�^���Z�b�g
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_LISTNO, nRow + 1);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_NAME, cYosoSagyogunList.sYosoSagyogunName);

			// ���ʍH�������F�ɂ���
			if (cYosoSagyogunList.bKyotsuKotei)
				m_cListYosoSagyogun.SetItemRowColor( nRow, COLOR_TEXT, COLOR_KYOTSU );

			// �G���[�s��ԐF�ɂ���
			if (cYosoSagyogunList.bErrorFlag)
				m_cListYosoSagyogun.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// �sID���Z�b�g
			m_cListYosoSagyogun.SetItemRowData(nRow, i);

			// �w�肳�ꂽ�s��I����Ԃɂ���
			if (i == nSelectIndex)
				m_cListYosoSagyogun.SelectChange(nRow);

			nRow++;
		}
	}
}

// �v�f��ƃ��X�g�ݒ�
void CManualEditDlg::SetYosoSagyoList(int nSelectIndex)
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// ���X�g���N���A
	m_cListYosoSagyo.DeleteAllItems();

	// �����H���͗v�f��Ƃ�\�����Ȃ�
	if (cKoteiList.sJidoShudo == g_pConst->m_sJido)
		return;

	// �v�f��ƃ��X�g�ɗv�f��Ƃ�ݒ�
// --Add 2007/02/26 aizawa �s��D-187
	int nAdjust = 0;
// --Add
	int nRow = 0;
	int nSize = cYosoSagyogunList.aYosoSagyoList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[i];

// --Add 2007/02/26 aizawa �s��D-187
		// �ǉ��폜�ɂ����No��␳
		if (cYosoSagyoList.nEditFlag == EDIT_INSERT)
			nAdjust++;
		else if (cYosoSagyoList.nEditFlag == EDIT_DELETE)
			nAdjust--;
// --Add

		if (cYosoSagyoList.nEditFlag != EDIT_DELETE) {
			// �e�J�����Ƀf�[�^���Z�b�g
// --Change 2007/02/26 aizawa �s��D-187
//			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, nRow + 1);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, cYosoSagyoList.nSagyoNo + nAdjust);
// --Change
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYO, cYosoSagyoList.sYosoSagyoName);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYONO, cYosoSagyoList.sYosoSagyoNo);
			if (cYosoSagyoList.bM)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_M, cYosoSagyoList.fM);
			if (cYosoSagyoList.bN)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_N, cYosoSagyoList.fN);
			if (cYosoSagyoList.bHassexxxndo)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_HINDO, cYosoSagyoList.nHassexxxndo);
			if (cYosoSagyoList.bKurikaeshiKaisu)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KURIKAESI, cYosoSagyoList.nKurikaeshiKaisu);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KOTEI, cYosoSagyoList.sKoteibuHendobu);
			if (cYosoSagyoList.bKijunKotei)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KIJUN, GetKijunKoteiString(cYosoSagyoList.nKijunKotei));

			// �G���[�s��ԐF�ɂ���
			if (cYosoSagyoList.bErrorFlag)
				m_cListYosoSagyo.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// �sID���Z�b�g
			m_cListYosoSagyo.SetItemRowData(nRow, i);

			// �w�肳�ꂽ�s��I����Ԃɂ���
			if (i == nSelectIndex)
				m_cListYosoSagyo.SelectChange(nRow);

			nRow++;
		}
	}
}

// �H�����X�g�̍폜
void CManualEditDlg::OnButtonKoteiSakujo() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// ���s�m�F���b�Z�[�W
	if (cKoteiList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014003") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014006") == IDCANCEL)
			return;
	}

	// �폜��Ԃɐݒ�
	cKoteiList.nEditFlag = EDIT_DELETE;

	// �H�����X�g�\��
	SetKoteiList();
}

// �v�f��ƌQ���X�g�̍폜
void CManualEditDlg::OnButtonYosoSagyogunSakujo() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// ���s�m�F���b�Z�[�W
	if (cYosoSagyogunList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014004") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014007") == IDCANCEL)
			return;
	}

	// �폜��Ԃɐݒ�
	cYosoSagyogunList.nEditFlag = EDIT_DELETE;

	// �v�f��ƌQ���X�g�\��
	SetYosoSagyogunList();
}

// �v�f��ƃ��X�g�̍폜
void CManualEditDlg::OnButtonYosoSagyoSakujo() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	// �I������Ă���v�f��Ǝ擾
	int nSelectYosoSagyo = GetSelectYosoSagyo();
	if (nSelectYosoSagyo == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];
	SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nSelectYosoSagyo];

	// ���s�m�F���b�Z�[�W
	if (cYosoSagyogunList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014005") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014008") == IDCANCEL)
			return;
	}

	// �폜��Ԃɐݒ�
//	cYosoSagyoList.nEditFlag = EDIT_DELETE;
	if (cYosoSagyoList.nYosoSagyoId == -1)
		cYosoSagyogunList.aYosoSagyoList.RemoveAt(nSelectYosoSagyo);
	else
		cYosoSagyoList.nEditFlag = EDIT_DELETE;

	// �v�f��ƃ��X�g�\��
	SetYosoSagyoList();
}

// �H�����X�g�̒ǉ�
void CManualEditDlg::OnButtonKoteiTsuika() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// �I������Ă���H���擾
	int nSelectKotei = nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		nSelectKotei = cSagyoshaList.aKoteiList.GetSize();

	// �V�K���R�[�h�ǉ�
	SKoteiList cKoteiList;
	cKoteiList.nIndex = -1;
	cKoteiList.nEditFlag = EDIT_INSERT;
	cKoteiList.bErrorFlag = FALSE;
	cKoteiList.sSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId)->sSublineName;
	cKoteiList.sKoteiKigo = cSagyoshaList.sKoteiKigo;
	cKoteiList.bKyotsuKotei = FALSE;
	cSagyoshaList.aKoteiList.InsertAt(nSelectKotei, cKoteiList);

	// �H�����X�g�\��
	SetKoteiList(nSelectKotei);
}

// �v�f��ƌQ���X�g�̒ǉ�
void CManualEditDlg::OnButtonYosoSagyogunTsuika() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		nSelectYosoSagyogun = cKoteiList.aYosoSagyogunList.GetSize();

	// �V�K���R�[�h�ǉ�
	SYosoSagyogunList cYosoSagyogunList;
	cYosoSagyogunList.nIndex = -1;
	cYosoSagyogunList.nEditFlag = EDIT_INSERT;
	cYosoSagyogunList.bErrorFlag = FALSE;
	cYosoSagyogunList.nYosoSagyogunId = -1;
	cYosoSagyogunList.bKyotsuKotei = FALSE;
	cKoteiList.aYosoSagyogunList.InsertAt(nSelectYosoSagyogun, cYosoSagyogunList);

	// �v�f��ƌQ���X�g�\��
	SetYosoSagyogunList(nSelectYosoSagyogun);
}

// �v�f��ƃ��X�g�̒ǉ�
void CManualEditDlg::OnButtonYosoSagyoTsuika() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// �I������Ă���v�f��Ǝ擾
	int nSelectYosoSagyo = GetSelectYosoSagyo();
	if (nSelectYosoSagyo == -1)
		nSelectYosoSagyo = cYosoSagyogunList.aYosoSagyoList.GetSize();

	// �V�K���R�[�h�ǉ�
	SYosoSagyoList cYosoSagyoList;
	cYosoSagyoList.nIndex = -1;
	cYosoSagyoList.nEditFlag = EDIT_INSERT;
	cYosoSagyoList.bErrorFlag = FALSE;
// --Add 2007/02/26 aizawa �s��D-187
	cYosoSagyoList.nSagyoNo = (nSelectYosoSagyo == 0) ? 0 : cYosoSagyogunList.aYosoSagyoList[nSelectYosoSagyo - 1].nSagyoNo;
// --Add
	cYosoSagyoList.nYosoSagyoId = -1;
	cYosoSagyoList.bM = FALSE;
	cYosoSagyoList.bN = FALSE;
	cYosoSagyoList.bHassexxxndo = FALSE;
	cYosoSagyoList.bKurikaeshiKaisu = FALSE;
	cYosoSagyoList.bKijunKotei = FALSE;
	cYosoSagyogunList.aYosoSagyoList.InsertAt(nSelectYosoSagyo, cYosoSagyoList);

	// �v�f��ƃ��X�g�\��
	SetYosoSagyoList(nSelectYosoSagyo);
}

// ���X�g�R���g���[���ύX
LRESULT CManualEditDlg::OnChangeEdit(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)wp;
	LV_ITEM		 *plvItem = &pDispInfo->item;

	int nRow = plvItem->iItem;
	int nCol = plvItem->iSubItem;

	if (pDispInfo->hdr.hwndFrom == m_cListKotei.m_hWnd)
		EditKotei(nRow, nCol);
	else if (pDispInfo->hdr.hwndFrom == m_cListYosoSagyogun.m_hWnd)
		EditYosoSagyogun(nRow, nCol);
	else if (pDispInfo->hdr.hwndFrom == m_cListYosoSagyo.m_hWnd)
		EditYosoSagyo(nRow, nCol);

// Modify ... ( ADD )
	m_bCloseLock = 0;
// By Y.Itabashi (xxxxx) 2007.03.05

	return TRUE;
}

// �H�����X�g�ύX
void CManualEditDlg::EditKotei(int nRow, int nCol)
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[m_cListKotei.GetItemRowData(nRow)];

	// �ύX���ꂽ�f�[�^���擾
	switch (nCol) {
	case COL_KOTEI_BANGO:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sKoteiNo);
		break;
	case COL_KOTEI_NAME:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sKoteiName);
		break;
	case COL_KOTEI_JIDOU:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sJidoShudo);
		SetYosoSagyoList();
		EnableYosoSagyoTsuikaSakujo();
		break;
	}
}

// �v�f��ƌQ���X�g�ύX
void CManualEditDlg::EditYosoSagyogun(int nRow, int nCol)
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[m_cListYosoSagyogun.GetItemRowData(nRow)];

	// �ύX���ꂽ�f�[�^���擾
	switch (nCol) {
	case COL_YOSOSAGYOGUN_NAME:
		m_cListYosoSagyogun.GetValue(nRow, nCol, cYosoSagyogunList.sYosoSagyogunName);
		break;
	}
}

// �v�f��ƃ��X�g�ύX
void CManualEditDlg::EditYosoSagyo(int nRow, int nCol)
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// �I������Ă���v�f��ƌQ�擾
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];
	SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[m_cListYosoSagyo.GetItemRowData(nRow)];
	CString sKijunKotei;

	// �ύX���ꂽ�f�[�^���擾
	switch (nCol) {
	case COL_YOSOSAGYO_YOSOSAGYO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sYosoSagyoName);
		break;
	case COL_YOSOSAGYO_YOSOSAGYONO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sYosoSagyoNo);
		SetYosoSagyoTemplate(cYosoSagyoList);
		break;
	case COL_YOSOSAGYO_M:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.fM);
		cYosoSagyoList.bM = TRUE;
		break;
	case COL_YOSOSAGYO_N:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.fN);
		cYosoSagyoList.bN = TRUE;
		break;
	case COL_YOSOSAGYO_HINDO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.nHassexxxndo);
		cYosoSagyoList.bHassexxxndo = TRUE;
		break;
	case COL_YOSOSAGYO_KURIKAESI:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.nKurikaeshiKaisu);
		cYosoSagyoList.bKurikaeshiKaisu = TRUE;
		break;
	case COL_YOSOSAGYO_KOTEI:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sKoteibuHendobu);
		break;
	case COL_YOSOSAGYO_KIJUN:
		m_cListYosoSagyo.GetValue(nRow, nCol, sKijunKotei);
		cYosoSagyoList.nKijunKotei = (sKijunKotei == g_pSystem->m_cMenuDisp.GetMenu(Menu_ManualEditDlg[37].MenuNo, Menu_ManualEditDlg[37].ItemNo)) ? 1 : 2;
		cYosoSagyoList.bKijunKotei = TRUE;
		break;
	}
}

// �v�f��ƃe���v���[�g����f�[�^��ݒ�
void CManualEditDlg::SetYosoSagyoTemplate(SYosoSagyoList &cYosoSagyoList)
{
	SYosoSagyoTemplate *pYosoSagyoTemplate = g_pDataManager->GetYosoSagyoTemplateData(cYosoSagyoList.sYosoSagyoNo);
	if (pYosoSagyoTemplate != NULL) {
		// �v�f��ƃe���v���[�g����f�[�^��ݒ�
// Modify ... ( ADD )
		cYosoSagyoList.sYosoSagyoName = pYosoSagyoTemplate->sSagyoNaiyo;
// By Y.Itabashi (xxxxx) 2007.02.16
		cYosoSagyoList.sEnzanShiki = pYosoSagyoTemplate->sEnzanShiki;
		cYosoSagyoList.fS1 = pYosoSagyoTemplate->fS1;
		cYosoSagyoList.fS2 = pYosoSagyoTemplate->fS2;
		cYosoSagyoList.fM = pYosoSagyoTemplate->fM;
		cYosoSagyoList.bM = TRUE;
		cYosoSagyoList.fN = pYosoSagyoTemplate->fN;
		cYosoSagyoList.bN = TRUE;
//		cYosoSagyoList.nHassexxxndo = pYosoSagyoTemplate->nHassexxxndo;
//		cYosoSagyoList.bHassexxxndo = TRUE;
//		cYosoSagyoList.nKurikaeshiKaisu = pYosoSagyoTemplate->nKurikaeshiKaisu;
//		cYosoSagyoList.bKurikaeshiKaisu = TRUE;
		cYosoSagyoList.sSagyoFlag = pYosoSagyoTemplate->sSagyoFlag;

		// �v�f��ƃ��X�g�\��
		SetYosoSagyoList();
	}
}

// ���X�g�r���[�̃R���{�{�b�N�X���쐬
LRESULT CManualEditDlg::CreateComboList(WPARAM wp, LPARAM lp)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)wp;

	int nRow = pNMListView->iItem;
	int nCol = pNMListView->iSubItem;

	CStringList	lstGroupNo;
	CString sValue;
	int i;
	int nIndex = 0;
	int nSize;

	switch( pNMListView->hdr.idFrom ){
	case IDC_LIST_KOTEI:
		m_cListKotei.GetValue( nRow, nCol, sValue );
		for (i = 0; i < 3; i++) {
			CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[32+i].MenuNo, Menu_ManualEditDlg[32+i].ItemNo );
			lstGroupNo.AddTail( str );
			if( str.Compare( sValue ) == 0 )
				nIndex = i;
		}
		m_cListKotei.SetComboList( nRow, nCol, lstGroupNo, nIndex );
		break;

	case IDC_LIST_YOSO_SAGYO:
		switch( nCol ){
		case COL_YOSOSAGYO_YOSOSAGYONO:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			nSize = g_pDataManager->GetYosoSagyoTemplateNum();
			for (i = 0; i < nSize; i++) {
				SYosoSagyoTemplate *pYosoSagyoTemplate = g_pDataManager->GetYosoSagyoTemplateData(i);
				lstGroupNo.AddTail( pYosoSagyoTemplate->sYosoSagyoNo );
				if(sValue == pYosoSagyoTemplate->sYosoSagyoNo )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		case COL_YOSOSAGYO_KOTEI:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			for (i = 0; i < 2; i++) {
				CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[35+i].MenuNo, Menu_ManualEditDlg[35+i].ItemNo );
				lstGroupNo.AddTail( str );
				if( str.Compare( sValue ) == 0 )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		case COL_YOSOSAGYO_KIJUN:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			for (i = 0; i < 2; i++) {
				CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[37+i].MenuNo, Menu_ManualEditDlg[37+i].ItemNo );
				lstGroupNo.AddTail( str );
				if( str.Compare( sValue ) == 0 )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		}
		break;

	default:
		break;
	}

	return TRUE;
}

// ���X�g�r���[�̃R���{�{�b�N�X�̑I��ύX
LRESULT CManualEditDlg::OnChangeCombo(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO *dispinfo = (LV_DISPINFO*)wp;

	int nRow = dispinfo->item.iItem;
	int nCol = dispinfo->item.iSubItem;
	
	CString sValue;
	if (dispinfo->hdr.hwndFrom == m_cListKotei.m_hWnd) {
		m_cListKotei.SetValue(nRow, nCol, dispinfo->item.pszText);
		EditKotei(nRow, nCol);
	} else if (dispinfo->hdr.hwndFrom == m_cListYosoSagyo.m_hWnd) {
		m_cListYosoSagyo.SetValue(nRow, nCol, dispinfo->item.pszText);
		EditYosoSagyo(nRow, nCol);
	}

	return TRUE;
}

// ��E�H���R�[�h�𕶎��ɕϊ�
CString CManualEditDlg::GetKijunKoteiString(int nKijunKotei)
{
	switch (nKijunKotei) {
	case 1:
		return g_pConst->m_sKijun;
	case 2:
		return g_pConst->m_sKotei;
	}
	return "";
}

// ��Ǝ҃��X�g���쐬
void CManualEditDlg::MakeSagyoshaList()
{
	// ��Ǝ҂ɂ�郋�[�v
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// ��Ǝ҃f�[�^��ݒ�
			int nSagyoshNum = g_pDataManager->GetSagyoshaNum(nSublineId);
			for (int nSagyoshId = 0; nSagyoshId < nSagyoshNum; nSagyoshId++) {
				SSagyoshaList *pSagyoshaList = m_aSagyoshaList.AddElement();
				pSagyoshaList->nSublineId = nSublineId;
				pSagyoshaList->nSagyoshaId = nSagyoshId;
				pSagyoshaList->sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshId);
				pSagyoshaList->sKoteiKigo = pSubline->aYosoSagyogun[0].sKoteiKigo;

				// �H�����X�g���쐬
				MakeKoteiList(nSublineId, nSagyoshId, pSagyoshaList->aKoteiList);
			}
		}
	}

	// �ύX�ӏ����`�F�b�N���邽�߁A���f�[�^��ۑ����Ă���
	m_aSagyoshaList2 = m_aSagyoshaList;
}

// �H�����X�g���쐬
void CManualEditDlg::MakeKoteiList(int nSublineId, int nSagyoshId, CKoteiListArray &aKoteiList)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	int nIndex;

	// ��Ǝ҂̗v�f��ƌQ���擾
	CIntArrayEx aYosoSagyogunId;
	g_pDataManager->FindSagyoWariate(nSublineId, nSagyoshId, m_nKishuId, aYosoSagyogunId);

	// �v�f��ƌQ����H�������擾
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++) {
		int nYosoSagyogunId = aYosoSagyogunId[i];
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];

		SKoteiList *pKoteiList = aKoteiList.LookupElement(cYosoSagyogun.sKoteiName);
		if (pKoteiList == NULL) {
			pKoteiList = aKoteiList.AddElement(cYosoSagyogun.sKoteiName, nIndex);
			pKoteiList->nIndex = nIndex;
			pKoteiList->nEditFlag = EDIT_NONE;
			pKoteiList->bErrorFlag = FALSE;
			pKoteiList->sSubline = pSubline->sSublineName;
			pKoteiList->sKoteiKigo = cYosoSagyogun.sKoteiKigo;
			pKoteiList->sKoteiNo = cYosoSagyogun.sKoteiNo;
			pKoteiList->sKoteiName = cYosoSagyogun.sKoteiName;
			pKoteiList->sJidoShudo = cYosoSagyogun.sJidoKotei;
			pKoteiList->bKyotsuKotei = FALSE;
		}
		pKoteiList->bKyotsuKotei |= cYosoSagyogun.aKishuSagyo[m_nKishuId].bKyotsuKotei;

		// �v�f��ƌQ���X�g���쐬
		MakeYosoSagyogunList(nYosoSagyogunId, cYosoSagyogun, pKoteiList->aYosoSagyogunList);
	}
}

// �v�f��ƌQ���X�g���쐬
void CManualEditDlg::MakeYosoSagyogunList(int nYosoSagyogunId, SYosoSagyogun &cYosoSagyogun, CYosoSagyogunListArray &aYosoSagyogunList)
{
	int nIndex;

	SYosoSagyogunList *pYosoSagyogunList = aYosoSagyogunList.AddElement(nIndex);
	pYosoSagyogunList->nIndex = nIndex;
	pYosoSagyogunList->nEditFlag = EDIT_NONE;
	pYosoSagyogunList->bErrorFlag = FALSE;
	pYosoSagyogunList->nYosoSagyogunId = nYosoSagyogunId;
	pYosoSagyogunList->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
	pYosoSagyogunList->bKyotsuKotei = cYosoSagyogun.aKishuSagyo[m_nKishuId].bKyotsuKotei;

	// �v�f��ƃ��X�g���쐬
	MakeYosoSagyoList(cYosoSagyogun, pYosoSagyogunList->aYosoSagyoList);
}

// �v�f��ƃ��X�g���쐬
void CManualEditDlg::MakeYosoSagyoList(SYosoSagyogun &cYosoSagyogun, CYosoSagyoListArray &aYosoSagyoList)
{
	int nIndex;

	int nSize = cYosoSagyogun.aYosoSagyo.GetSize();
	for (int nYosoSagyoId = 0; nYosoSagyoId < nSize; nYosoSagyoId++) {
		SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[nYosoSagyoId];

		if (cYosoSagyo.sDeleteFlag.IsEmpty()) {
			SYosoSagyoList *pYosoSagyoList = aYosoSagyoList.AddElement(nIndex);
			pYosoSagyoList->nIndex = nIndex;
			pYosoSagyoList->nEditFlag = EDIT_NONE;
			pYosoSagyoList->bErrorFlag = FALSE;
			pYosoSagyoList->nYosoSagyoId = nYosoSagyoId;
			pYosoSagyoList->sYosoSagyoName = cYosoSagyo.sYosoSagyoName;
			pYosoSagyoList->sYosoSagyoNo = cYosoSagyo.sYosoSagyoNo;
// --Add 2007/02/26 aizawa �s��D-187
			pYosoSagyoList->nSagyoNo = cYosoSagyo.nSagyoNo;
// --Add
			pYosoSagyoList->sEnzanShiki = cYosoSagyo.sEnzanShiki;
			pYosoSagyoList->fS1 = cYosoSagyo.fS1;
			pYosoSagyoList->fS2 = cYosoSagyo.fS2;
			pYosoSagyoList->fM = cYosoSagyo.fM;
			pYosoSagyoList->bM = TRUE;
			pYosoSagyoList->fN = cYosoSagyo.fN;
			pYosoSagyoList->bN = TRUE;
			pYosoSagyoList->nHassexxxndo = cYosoSagyo.nHassexxxndo;
			pYosoSagyoList->bHassexxxndo = TRUE;
			pYosoSagyoList->nKurikaeshiKaisu = cYosoSagyo.nKurikaeshiKaisu;
			pYosoSagyoList->bKurikaeshiKaisu = TRUE;
			pYosoSagyoList->sKoteibuHendobu = cYosoSagyo.sKoteibuHendobu;
			pYosoSagyoList->sSagyoFlag = cYosoSagyo.sSagyoFlag;
			pYosoSagyoList->nKijunKotei = cYosoSagyo.nKijunKotei;
			pYosoSagyoList->bKijunKotei = TRUE;
		}
	}
}

// �I������Ă����Ǝ҂��擾
int CManualEditDlg::GetSelectSagyosha()
{
	int nIndex = m_cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return -1;

	return m_cComboSagyosha.GetItemData(nIndex);
}

// �I������Ă���H�����擾
int CManualEditDlg::GetSelectKotei()
{
	POSITION pos = m_cListKotei.GetFirstSelectedItemPosition();
	int nRow = m_cListKotei.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListKotei.GetItemRowData(nRow);
}

// �I������Ă���v�f��ƌQ���擾
int CManualEditDlg::GetSelectYosoSagyogun()
{
	POSITION pos = m_cListYosoSagyogun.GetFirstSelectedItemPosition();
	int nRow = m_cListYosoSagyogun.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListYosoSagyogun.GetItemRowData(nRow);
}

// �I������Ă���v�f��Ƃ��擾
int CManualEditDlg::GetSelectYosoSagyo()
{
	POSITION pos = m_cListYosoSagyo.GetFirstSelectedItemPosition();
	int nRow = m_cListYosoSagyo.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListYosoSagyo.GetItemRowData(nRow);
}

// �v�f��Ƃ̒ǉ��E�폜�{�^���̗L���E������ݒ�
void CManualEditDlg::EnableYosoSagyoTsuikaSakujo()
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// �I������Ă���H���擾
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	if (cKoteiList.sJidoShudo == g_pConst->m_sJido) {
		// �ǉ��E�폜�{�^���𖳌�
		m_cButtonYosoSagyoSakujo.EnableWindow(FALSE);
		m_cButtonYosoSagyoTsuika.EnableWindow(FALSE);
		return;
	} else {
		// �ǉ��E�폜�{�^����L��
		m_cButtonYosoSagyoSakujo.EnableWindow(TRUE);
		m_cButtonYosoSagyoTsuika.EnableWindow(TRUE);
	}
}

// OK�{�^���N���b�N
void CManualEditDlg::OnOK() 
{
	// ���̓f�[�^�`�F�b�N
	if (!CheckData())
		return;

	CWaitCursor cWaitCursor;

	// �ύX����
	ChangeData();

	// �폜����
	DeleteData();

	// �ǉ�����
	InsertData();

// --Add 2007/02/26 aizawa �s��D-187
	// ���No���i���o
	g_pDataManager->RenumberSagyoNo();
// --Add
	
	CDialogEx::OnOK();
}

// �L�����Z���{�^���N���b�N
void CManualEditDlg::OnCancel() 
{
// Modify ... ( ADD )
	if( m_bCloseLock )
		return;
// By Y.Itabashi (xxxxx) 2007.03.05

	CDialogEx::OnCancel();
}

// �v���r���[�{�^���N���b�N
void CManualEditDlg::OnButtonPreview() 
{
	// �I������Ă����ƎҎ擾
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;
	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// ���̓f�[�^�`�F�b�N
	if (!CheckData())
		return;

	CWaitCursor cWaitCursor;

	// ���X�g�f�[�^��ۑ�
	CSagyoshaListArray aSagyoshaList;
	aSagyoshaList = m_aSagyoshaList;

	// �ύX����
	ChangeData();

	// �폜����
	DeleteData();

	// �ǉ�����
	InsertData();

// --Add 2007/02/26 aizawa �s��D-187
	// ���No���i���o
	g_pDataManager->RenumberSagyoNo();
// --Add

	// �蓮�Ґ���ʂ��X�V
	m_pDoc->DispCenterSagyosha(cSagyoshaList.nSublineId, cSagyoshaList.nSagyoshaId);

	// ���X�g�f�[�^�𕜌�
	g_pDataManager->RestoreUndoData();
	m_aSagyoshaList = aSagyoshaList;
}

// �w�肵�����X�g��\��
void CManualEditDlg::DispList(int nSagyoshaIndex, int nKoteiIndex, int nYosoSagyogunIndex)
{
	int i;
	int nCount;

	// ��Ǝ҂�\��
	nCount = m_cComboSagyosha.GetCount();
	for (i = 0; i < nCount; i++) {
		if ((int)m_cComboSagyosha.GetItemData(i) == nSagyoshaIndex) {
			m_cComboSagyosha.SetCurSel(i);
			SetKoteiList();
			break;
		}
	}

	// �H����\��
	if (nKoteiIndex != -1) {
		nCount = m_cListKotei.GetItemCount();
		for (i = 0; i < nCount; i++) {
			if ((int)m_cListKotei.GetItemRowData(i) == nKoteiIndex) {
				m_cListKotei.SelectChange(i);
				break;
			}
		}
	}

	// �v�f��ƌQ��\��
	if (nYosoSagyogunIndex != -1) {
		nCount = m_cListYosoSagyogun.GetItemCount();
		for (i = 0; i < nCount; i++) {
			if ((int)m_cListYosoSagyogun.GetItemRowData(i) == nYosoSagyogunIndex) {
				m_cListYosoSagyogun.SelectChange(i);
				break;
			}
		}
	}
}

// ���̓f�[�^�`�F�b�N
BOOL CManualEditDlg::CheckData()
{
	// �G���[�t���O�N���A
	ClearErrorFlag();

	// �ύX�t���O�Z�b�g
	SetChangeFlag();

	// �����̓`�F�b�N
	if (!CheckMinyuryoku())
		return FALSE;

	// ���̓f�[�^�L���`�F�b�N
	if (!CheckInputData())
		return FALSE;

	// ����H�����݃`�F�b�N
	if (!CheckSameKotei())
		return FALSE;

	// ����v�f��ƌQ���݃`�F�b�N
	if (!CheckSameYosoSagyogun())
		return FALSE;

	// �v�f��ƌQ���݃`�F�b�N
	if (!CheckYosoSagyogun())
		return FALSE;

	// �v�f��Ƒ��݃`�F�b�N
	if (!CheckYosoSagyo())
		return FALSE;

	// ���ʍH���ҏW�`�F�b�N
	if (!CheckKyotsuKotei())
		return FALSE;

	return TRUE;
}

// �G���[�t���O�N���A
void CManualEditDlg::ClearErrorFlag()
{
	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			// �H���G���[�t���O�N���A
			cKoteiList.bErrorFlag = FALSE;

			// �v�f��ƌQ���X�g�ɂ�郋�[�v
			int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
			for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
				SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

				// �v�f��ƌQ�G���[�t���O�N���A
				cYosoSagyogunList.bErrorFlag = FALSE;

				// �v�f��ƃ��X�g�ɂ�郋�[�v
				int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
				for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
					SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

					// �v�f��ƃG���[�t���O�N���A
					cYosoSagyoList.bErrorFlag = FALSE;
				}
			}
		}
	}
}

// �ύX�t���O�Z�b�g
void CManualEditDlg::SetChangeFlag()
{
	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSagyoshaList &cSagyoshaList2 = m_aSagyoshaList2[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];
			if (cKoteiList.nIndex != -1 && cKoteiList.nEditFlag != EDIT_DELETE) {
				SKoteiList &cKoteiList2 = cSagyoshaList2.aKoteiList[cKoteiList.nIndex];

				// �H���̌��f�[�^�Ɠ��̓f�[�^���r
				if (cKoteiList.sKoteiNo != cKoteiList2.sKoteiNo ||
						cKoteiList.sKoteiName != cKoteiList2.sKoteiName ||
						cKoteiList.sJidoShudo != cKoteiList2.sJidoShudo)
					cKoteiList.nEditFlag = EDIT_CHANGE;
				else
					cKoteiList.nEditFlag = EDIT_NONE;

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];
					if (cYosoSagyogunList.nIndex != -1 && cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						SYosoSagyogunList &cYosoSagyogunList2 = cKoteiList2.aYosoSagyogunList[cYosoSagyogunList.nIndex];

						// �v�f��ƌQ�̌��f�[�^�Ɠ��̓f�[�^���r
						if (cYosoSagyogunList.sYosoSagyogunName != cYosoSagyogunList2.sYosoSagyogunName)
							cYosoSagyogunList.nEditFlag = EDIT_CHANGE;
						else
							cYosoSagyogunList.nEditFlag = EDIT_NONE;

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];
							if (cYosoSagyoList.nIndex != -1 && cYosoSagyoList.nEditFlag != EDIT_DELETE) {
								SYosoSagyoList &cYosoSagyoList2 = cYosoSagyogunList2.aYosoSagyoList[cYosoSagyoList.nIndex];

								// �v�f��Ƃ̌��f�[�^�Ɠ��̓f�[�^���r
								if (cYosoSagyoList.sYosoSagyoName != cYosoSagyoList2.sYosoSagyoName ||
										cYosoSagyoList.sYosoSagyoNo !=  cYosoSagyoList2.sYosoSagyoNo ||
										cYosoSagyoList.fM != cYosoSagyoList2.fM ||
										cYosoSagyoList.fN != cYosoSagyoList2.fN ||
										cYosoSagyoList.nHassexxxndo != cYosoSagyoList2.nHassexxxndo ||
										cYosoSagyoList.nKurikaeshiKaisu != cYosoSagyoList2.nKurikaeshiKaisu ||
										cYosoSagyoList.sKoteibuHendobu != cYosoSagyoList2.sKoteibuHendobu ||
										cYosoSagyoList.nKijunKotei != cYosoSagyoList2.nKijunKotei)
									cYosoSagyoList.nEditFlag = EDIT_CHANGE;
								else
									cYosoSagyoList.nEditFlag = EDIT_NONE;
							}
						}
					}
				}
			}
		}
	}
}

// �����̓`�F�b�N
BOOL CManualEditDlg::CheckMinyuryoku()
{
	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag != EDIT_NONE) {
					if (cKoteiList.sKoteiNo.IsEmpty() || cKoteiList.sKoteiName.IsEmpty() || cKoteiList.sJidoShudo.IsEmpty()) {
						cKoteiList.bErrorFlag = TRUE;
						DispList(nSagyoshaIndex);
						g_pSystem->DispMessage("E014001");
						return FALSE;
					}
				}

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag != EDIT_NONE) {
							if (cYosoSagyogunList.sYosoSagyogunName.IsEmpty()) {
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014001");
								return FALSE;
							}
						}

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						if (cKoteiList.sJidoShudo != g_pConst->m_sJido) {
							int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
							for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
								SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

								if (cYosoSagyoList.nEditFlag != EDIT_DELETE && cYosoSagyoList.nEditFlag != EDIT_NONE) {
									if (cYosoSagyoList.sYosoSagyoName.IsEmpty() || cYosoSagyoList.sYosoSagyoNo.IsEmpty() ||
											!cYosoSagyoList.bM || !cYosoSagyoList.bN || !cYosoSagyoList.bHassexxxndo || !cYosoSagyoList.bKurikaeshiKaisu ||
											cYosoSagyoList.sKoteibuHendobu.IsEmpty() || !cYosoSagyoList.bKijunKotei) {
										cYosoSagyoList.bErrorFlag = TRUE;
										DispList(nSagyoshaIndex, nKoteiIndex, nYosoSagyogunIndex);
										g_pSystem->DispMessage("E014001");
										return FALSE;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// ���̓f�[�^�L���`�F�b�N
BOOL CManualEditDlg::CheckInputData()
{
	static CDataCheck aKoteiDataCheck[] = {
		{eDataTypeNumber, 32, 0, 0, 0, NULL},
		{eDataTypeKoteiName, 128, 0, 0, 0, NULL},
		{eDataTypeAll, 6, 0, 0, 0, NULL}
	};
	static CDataCheck aYosoSagyogunDataCheck[] = {
		{eDataTypeKoteiName, 128, 0, 0, 0, NULL}
	};
	static CDataCheck aYosoSagyoDataCheck[] = {
		{eDataTypeAll, 128, 0, 0, 0, NULL},
		{eDataTypeNumber, 8, 0, 0, 0, NULL},
		{eDataTypeNumber, 10, 4, -9999.9999, 9999.9999, NULL},
		{eDataTypeNumber, 10, 4, -9999.9999, 9999.9999, NULL},
		{eDataTypeNumber, 4, 0, 1, 9999, NULL},
		{eDataTypeNumber, 4, 0, 0, 9999, NULL},
		{eDataTypeAll, 6, 0, 0, 0, NULL},
		{eDataTypeNumber, 1, 0, 1, 2, NULL}
	};

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag != EDIT_NONE) {
					// �H���̃f�[�^�`�F�b�N
					if (!CheckInputDataSub(aKoteiDataCheck[0], cKoteiList.sKoteiNo) ||
							!CheckInputDataSub(aKoteiDataCheck[1], cKoteiList.sKoteiName) ||
							!CheckInputDataSub(aKoteiDataCheck[2], cKoteiList.sJidoShudo)) {
						cKoteiList.bErrorFlag = TRUE;
						DispList(nSagyoshaIndex);
						g_pSystem->DispMessage("E014002");
						return FALSE;
					}
				}

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag != EDIT_NONE) {
							// �v�f��ƌQ�̃f�[�^�`�F�b�N
							if (!CheckInputDataSub(aYosoSagyogunDataCheck[0], cYosoSagyogunList.sYosoSagyogunName)) {
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014002");
								return FALSE;
							}
						}

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_DELETE && cYosoSagyoList.nEditFlag != EDIT_NONE) {
								// �v�f��Ƃ̃f�[�^�`�F�b�N
								if (!CheckInputDataSub(aYosoSagyoDataCheck[0], cYosoSagyoList.sYosoSagyoName) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[1], cYosoSagyoList.sYosoSagyoNo) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[2], cYosoSagyoList.fM) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[3], cYosoSagyoList.fN) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[4], (double)cYosoSagyoList.nHassexxxndo) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[5], (double)cYosoSagyoList.nKurikaeshiKaisu) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[6], cYosoSagyoList.sKoteibuHendobu) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[7], (double)cYosoSagyoList.nKijunKotei)) {
									cYosoSagyoList.bErrorFlag = TRUE;
									DispList(nSagyoshaIndex, nKoteiIndex, nYosoSagyogunIndex);
									g_pSystem->DispMessage("E014002");
									return FALSE;
								}
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// ������f�[�^�̃`�F�b�N
BOOL CManualEditDlg::CheckInputDataSub(CDataCheck &cDataCheck, LPCTSTR pData)
{
	return cDataCheck.ValidCharCheck(pData) && cDataCheck.LengthCheck(pData);
}

// ���l�f�[�^�̃`�F�b�N
BOOL CManualEditDlg::CheckInputDataSub(CDataCheck &cDataCheck, double fData)
{
	return cDataCheck.RangeCheck(fData);
}

// �v�f��ƌQ���݃`�F�b�N
BOOL CManualEditDlg::CheckYosoSagyogun()
{
	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					if (cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex].nEditFlag != EDIT_DELETE)
						break;
				}
				if (nYosoSagyogunIndex == nYosoSagyogunNum) {
					// �v�f��ƌQ��������΃G���[
					cKoteiList.bErrorFlag = TRUE;
					DispList(nSagyoshaIndex);
					g_pSystem->DispMessage("E014012");
					return FALSE;			
				}
			}
		}
	}

	return TRUE;
}

// �v�f��Ƒ��݃`�F�b�N
BOOL CManualEditDlg::CheckYosoSagyo()
{
	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						// �v�f��ƃ��X�g�ɂ�郋�[�v
						if (cKoteiList.sJidoShudo != g_pConst->m_sJido) {
							int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
							for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
								if (cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex].nEditFlag != EDIT_DELETE)
									break;
							}
							if (nYosoSagyoIndex == nYosoSagyoNum) {
								// �v�f��Ƃ�������΃G���[
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014013");
								return FALSE;			
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// ����H�����݃`�F�b�N
BOOL CManualEditDlg::CheckSameKotei()
{
	CString sKoteiKey;
	CStringArrayEx aKoteiKey;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		aKoteiKey.RemoveAll();

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				sKoteiKey = cKoteiList.sKoteiNo + cKoteiList.sKoteiName;
				if (aKoteiKey.Find(sKoteiKey) != -1) {
					// ����H��������΃G���[
					cKoteiList.bErrorFlag = TRUE;
					DispList(nSagyoshaIndex);
					g_pSystem->DispMessage("E014010");
					return FALSE;			
				} else
					aKoteiKey.Add(sKoteiKey);
			}
		}
	}

	return TRUE;
}

// ����v�f��ƌQ���݃`�F�b�N
BOOL CManualEditDlg::CheckSameYosoSagyogun()
{
	CStringArrayEx aYosoSagyogunName;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				aYosoSagyogunName.RemoveAll();

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (aYosoSagyogunName.Find(cYosoSagyogunList.sYosoSagyogunName) != -1) {
							// ����v�f��ƌQ������΃G���[
							cYosoSagyogunList.bErrorFlag = TRUE;
							DispList(nSagyoshaIndex, nKoteiIndex);
							g_pSystem->DispMessage("E014011");
							return FALSE;			
						} else
							aYosoSagyogunName.Add(cYosoSagyogunList.sYosoSagyogunName);
					}
				}
			}
		}
	}

	return TRUE;
}

// ���ʍH���ύX�`�F�b�N
BOOL CManualEditDlg::CheckKyotsuKotei()
{
	CStringArrayEx aKyotsuKoteiEdit;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList2.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag == EDIT_CHANGE && cKoteiList.bKyotsuKotei)
					aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag == EDIT_CHANGE && cYosoSagyogunList.bKyotsuKotei)
							aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_DELETE) {
								if (cYosoSagyoList.nEditFlag == EDIT_CHANGE && cYosoSagyogunList.bKyotsuKotei)
									aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);
							}
						}
					}
				}
			}
		}
	}

	// ���ʍH���̕ύX�m�F���b�Z�[�W�\��
	CStringArrayEx aSagyoshaKotei;
	int nSize = aKyotsuKoteiEdit.GetSize();
	for (int i = 0; i < nSize; i++) {
		aSagyoshaKotei.Split("\t", aKyotsuKoteiEdit[i]);
		if (g_pSystem->DispMessage("W014009", aSagyoshaKotei[0], aSagyoshaKotei[1]) == IDCANCEL)
			return FALSE;
	}

	return TRUE;
}

// �f�[�^�ύX����
void CManualEditDlg::ChangeData()
{
	CString sChangeKotei;
	CString sChangeYosoSagyogun;
	CString sChangeYosoSagyo;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE && cKoteiList.nEditFlag != EDIT_INSERT) {
				sChangeKotei = (cKoteiList.nEditFlag == EDIT_CHANGE) ? "1" : "";

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE && cYosoSagyogunList.nEditFlag != EDIT_INSERT) {
						sChangeYosoSagyogun = (cYosoSagyogunList.nEditFlag == EDIT_CHANGE) ? "2" : sChangeKotei;

						SYosoSagyogun *pYosoSagyogun;
						if (!sChangeYosoSagyogun.IsEmpty()){
							SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];

							// �v�f��ƌQ��V���ɒǉ�
							pYosoSagyogun = pSubline->aYosoSagyogun.AddElement(cKoteiList.sKoteiName + cYosoSagyogunList.sYosoSagyogunName, cYosoSagyogunList.nYosoSagyogunId);
							*pYosoSagyogun = cYosoSagyogun;
							pYosoSagyogun->sKoteiKigo = cKoteiList.sKoteiKigo;
							pYosoSagyogun->sKoteiNo = cKoteiList.sKoteiNo;
							pYosoSagyogun->sKoteiName = ConvertHankaku(cKoteiList.sKoteiName);
							pYosoSagyogun->sYosoSagyogunName = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							pYosoSagyogun->sJidoYosoSagyogun = cKoteiList.sJidoShudo;
							pYosoSagyogun->sJidoKotei = cKoteiList.sJidoShudo;

							int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
							for (int i = 0; i < nYosoSagyoNum; i++) {
								// ���̗v�f��ƌQ�ɍ폜�t���O��ݒ�
								cYosoSagyogun.aYosoSagyo[i].sDeleteFlag = sChangeYosoSagyogun;

								// �ǉ������v�f��ƌQ�ɒǉ��t���O��ݒ�
								pYosoSagyogun->aYosoSagyo[i].sInsertFlag = sChangeYosoSagyogun;
							}
						} else
							pYosoSagyogun = &pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag == EDIT_CHANGE) {
								SYosoSagyo &cYosoSagyo = pYosoSagyogun->aYosoSagyo[cYosoSagyoList.nYosoSagyoId];
								SetYosoSagyoData(cYosoSagyo, cYosoSagyoList);
							}
						}
					}
				}
			}
		}
	}
}

// �f�[�^�폜����
void CManualEditDlg::DeleteData()
{
	CString sDeleteKoteiFlag;
	CString sDeleteYosoSagyogunFlag;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_INSERT) {
				sDeleteKoteiFlag = (cKoteiList.nEditFlag == EDIT_DELETE) ? "1" : "";

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_INSERT && cYosoSagyogunList.nYosoSagyogunId != -1) {
						SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];
						sDeleteYosoSagyogunFlag = (sDeleteKoteiFlag.IsEmpty() && cYosoSagyogunList.nEditFlag == EDIT_DELETE) ? "2" : sDeleteKoteiFlag;

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nOffset = 0;
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_INSERT && cYosoSagyoList.nYosoSagyoId != -1) {
								// �v�f��Ƃ��폜
								if (cYosoSagyoList.nEditFlag == EDIT_DELETE) {
									cYosoSagyogun.aYosoSagyo.RemoveAt(cYosoSagyoList.nYosoSagyoId + nOffset);
									nOffset--;
								}

								// �H���܂��͗v�f��ƌQ���폜����Ă���ꍇ�͍폜�t���O���Z�b�g
								if (!sDeleteYosoSagyogunFlag.IsEmpty()) {
									int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
									for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++)
										cYosoSagyogun.aYosoSagyo[nYosoSagyoIndex].sDeleteFlag = sDeleteYosoSagyogunFlag;
								}
							}
						}
					}
				}
			}
		}
	}
}

// �f�[�^�ǉ�����
void CManualEditDlg::InsertData()
{
	CString sInsertKoteiFlag;
	CString sInsertYosoSagyogunFlag;
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nLastYosoSagyogunId = -1;

	// ��Ǝ҃��X�g�ɂ�郋�[�v
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// �H�����X�g�ɂ�郋�[�v
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				sInsertKoteiFlag = (cKoteiList.nEditFlag == EDIT_INSERT) ? "1" : "";

				// �v�f��ƌQ���X�g�ɂ�郋�[�v
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						sInsertYosoSagyogunFlag = (sInsertKoteiFlag.IsEmpty() && cYosoSagyogunList.nEditFlag == EDIT_INSERT) ? "2" : sInsertKoteiFlag;

						SYosoSagyogun *pYosoSagyogun;
						if (cYosoSagyogunList.nYosoSagyogunId != -1) {
							pYosoSagyogun = &pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];
							nLastYosoSagyogunId = cYosoSagyogunList.nYosoSagyogunId;
						} else {
							CSagyojunArray aSagyojun;
							g_pDataManager->GetSagyojun(cSagyoshaList.nSublineId, m_nKishuId, aSagyojun);

							int nSagyojun = 0;
							if (nLastYosoSagyogunId != -1) {
								int nSize = aSagyojun.GetSize();
								for (int i = 0; i < nSize; i++) {
									if (aSagyojun[i].nYosoSagyogunId == nLastYosoSagyogunId) {
										nSagyojun = i + 1;
										break;
									}
								}
							}

							// �v�f��ƌQ��ǉ�
							int nAddYosoSagyogunId;
							pYosoSagyogun = pSubline->aYosoSagyogun.AddElement(cKoteiList.sKoteiName + cYosoSagyogunList.sYosoSagyogunName, nAddYosoSagyogunId);
							pYosoSagyogun->sKoteiKigo = cKoteiList.sKoteiKigo;
							pYosoSagyogun->sKoteiNo = cKoteiList.sKoteiNo;
							pYosoSagyogun->sKoteiName = ConvertHankaku(cKoteiList.sKoteiName);
							pYosoSagyogun->sYosoSagyogunName = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							pYosoSagyogun->sJidoYosoSagyogun = cKoteiList.sJidoShudo;
							pYosoSagyogun->sJidoKotei = cKoteiList.sJidoShudo;
							pYosoSagyogun->aKishuSagyo.SetSize(nKishuNum);
// Modify ... ( ADD )
							// �H���K���ᔽ�t�@�C���o�͗p�f�[�^�쐬
							SKatashikiError cKatashikiError;
							SLineError cLineError;

							cKatashikiError.nKishuID = m_nKishuId;
							cKatashikiError.sSublineFrom = pSubline->sSublineName;
							cKatashikiError.sKoteiNoFrom = cKoteiList.sKoteiNo;
							cKatashikiError.sKoteiNameFrom = ConvertHankaku(cKoteiList.sKoteiName);
							cKatashikiError.sYosoSagyogunNameFrom = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							cKatashikiError.sRiyuNo = "E014014";

							cLineError.nKishuID = m_nKishuId;
							cLineError.sSublineFrom = pSubline->sSublineName;
							cLineError.sKoteiNoFrom = cKoteiList.sKoteiNo;
							cLineError.sKoteiNameFrom = ConvertHankaku(cKoteiList.sKoteiName);
							cLineError.sYosoSagyogunNameFrom = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							cLineError.sRiyuNo = "E014014";

							g_pDataManager->AddKojunKiseiError( cKatashikiError, cLineError );
// By Y.Itabashi (xxxxx) 2007.02.19

							// �@��ʃf�[�^��ݒ�
							SKishuSagyo &cKishuSagyo = pYosoSagyogun->aKishuSagyo[m_nKishuId];
							cKishuSagyo.nSagyojun = 1;
							cKishuSagyo.nSagyoshaId = cSagyoshaList.nSagyoshaId;
							cKishuSagyo.nGroupNo = g_pDataManager->GetNewGroupNo(cSagyoshaList.nSublineId);
							cKishuSagyo.bKyotsuKotei = FALSE;
							cKishuSagyo.bNewMove = FALSE;

							// ��Ə���ݒ�
							SSagyojun cSagyojun;
							cSagyojun.nYosoSagyogunId = nAddYosoSagyogunId;
							cSagyojun.nKishuId = m_nKishuId;
							aSagyojun.InsertAt(nSagyojun, cSagyojun);
							g_pDataManager->OrderBySagyojun(cSagyoshaList.nSublineId, aSagyojun);

							nLastYosoSagyogunId = nAddYosoSagyogunId;
						}

						// �v�f��ƃ��X�g�ɂ�郋�[�v
						int nOffset = 0;
						int nInsertYosoSagyoId = 0;
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag == EDIT_INSERT) {
								// �v�f��Ƃ�ǉ�
								SYosoSagyo cYosoSagyo;
								cYosoSagyo.sYosoSagyoNo = cYosoSagyoList.sYosoSagyoNo;
								cYosoSagyo.nSagyoNo = 0;
								if (!sInsertYosoSagyogunFlag.IsEmpty())
									cYosoSagyo.sInsertFlag = sInsertYosoSagyogunFlag;
								cYosoSagyo.nRow = g_pDataManager->AddYosoSagyoHyo();
								SetYosoSagyoData(cYosoSagyo, cYosoSagyoList);

								pYosoSagyogun->aYosoSagyo.InsertAt(nInsertYosoSagyoId++, cYosoSagyo);

								nOffset++;
							} else {
								if (cYosoSagyoList.nEditFlag == EDIT_DELETE)
									nOffset--;

								if (cYosoSagyoList.nYosoSagyoId != -1)
									nInsertYosoSagyoId = cYosoSagyoList.nYosoSagyoId + nOffset + 1;
							}
						}

						// �����H���ŗv�f��Ƃ������ꍇ�͂P���ǉ�
						if (cKoteiList.sJidoShudo == g_pConst->m_sJido && nYosoSagyoNum == 0) {
							SYosoSagyo *pYosoSagyo = pYosoSagyogun->aYosoSagyo.AddElement();
							pYosoSagyo->nSagyoNo = 1;
							if (!sInsertYosoSagyogunFlag.IsEmpty())
								pYosoSagyo->sInsertFlag = sInsertYosoSagyogunFlag;
							pYosoSagyo->nRow = g_pDataManager->AddYosoSagyoHyo();
						}
					}
				}
			}
		}
	}

// Modify ... ( ADD )
	// �H���K���ᔽ�t�@�C��
	g_pDataManager->SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.19
}

// �v�f��ƃf�[�^�ݒ�
void CManualEditDlg::SetYosoSagyoData(SYosoSagyo &cYosoSagyo, SYosoSagyoList &cYosoSagyoList)
{
	cYosoSagyo.sYosoSagyoName = cYosoSagyoList.sYosoSagyoName;
	cYosoSagyo.sYosoSagyoNo = cYosoSagyoList.sYosoSagyoNo;
	cYosoSagyo.sEnzanShiki = cYosoSagyoList.sEnzanShiki;
	cYosoSagyo.fS1 = cYosoSagyoList.fS1;
	cYosoSagyo.fS2 = cYosoSagyoList.fS2;
	cYosoSagyo.fM = cYosoSagyoList.fM;
	cYosoSagyo.fN = cYosoSagyoList.fN;
	cYosoSagyo.fEnzangoSagyoJikan = CalcEnzangoSagyoJikan(cYosoSagyoList);
	cYosoSagyo.nHassexxxndo = cYosoSagyoList.nHassexxxndo;
	cYosoSagyo.nKurikaeshiKaisu = cYosoSagyoList.nKurikaeshiKaisu;
	cYosoSagyo.fTime = CalcDaiatariSagyoJikan(cYosoSagyoList, cYosoSagyo.fEnzangoSagyoJikan);
	cYosoSagyo.sKoteibuHendobu = cYosoSagyoList.sKoteibuHendobu;
	cYosoSagyo.sSagyoFlag = cYosoSagyoList.sSagyoFlag;
	cYosoSagyo.nKijunKotei = cYosoSagyoList.nKijunKotei;
}

// ���Z���Ǝ��Ԃ��v�Z
double CManualEditDlg::CalcEnzangoSagyoJikan(SYosoSagyoList &cYosoSagyoList)
{
	CEVal cEVal;
	CString sVal;

	CString sEnzanShiki = cYosoSagyoList.sEnzanShiki;

	// ���Z����M�̒l����
	sVal.Format("%10.4f", cYosoSagyoList.fM);
	sEnzanShiki.Replace("M", sVal);

	// ���Z����N�̒l����
	sVal.Format("%10.4f", cYosoSagyoList.fN);
	sEnzanShiki.Replace("N", sVal);

	// ���Z����S1�̒l����
	sVal.Format("%10.4f", cYosoSagyoList.fS1);
	sEnzanShiki.Replace("S1", sVal);

	// ���Z����S2�̒l����
	sVal.Format("%10.4f", cYosoSagyoList.fS2);
	sEnzanShiki.Replace("S2", sVal);

	// ���Z�����v�Z
	return cEVal.GetValue(sEnzanShiki);
}

// �䓖���Ǝ��Ԃ��v�Z
double CManualEditDlg::CalcDaiatariSagyoJikan(SYosoSagyoList &cYosoSagyoList, double fEnzangoSagyoJikan)
{
	// ���Z��̍�Ǝ��ԁ������p�x�~�J��Ԃ���
	if (cYosoSagyoList.nHassexxxndo != 0)
		return fEnzangoSagyoJikan / cYosoSagyoList.nHassexxxndo * cYosoSagyoList.nKurikaeshiKaisu;
	else
		return 0;
}

// �S�p�̃A���_�[�o�[�A�����𔼊p�ɕϊ�
CString CManualEditDlg::ConvertHankaku(LPCTSTR pText)
{
	CString sText(pText);
	g_pDataManager->ConvertHankaku(sText);
	return sText;
}
