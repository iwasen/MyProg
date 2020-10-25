//*****************************************************************************************************
//  1. �t�@�C����
//		ManualMoveDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualMoveDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"

#include "MenuDispCtrl.h"

#include "ManualMoveDlg001.h"
#include "ManualMoveDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// �\�����[�h
#define MODE_KOTEI			0		// �H�����\������Ă���
#define MODE_YOSO_SAGYOGUN	1		// �v�f��ƌQ���\������Ă���

// ���X�g�̗�ԍ�
#define	COL_LIST_KOTEI		0		// �H��
#define COL_LIST_TIME		1		// ��Ǝ���
#define	COL_LIST_KISHU		2		// �@�햼

// ���ʍH���̐F�ݒ�
#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_KYOTSU	RGB(0xff, 0xff, 0)

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::CManualMoveDlg
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
CManualMoveDlg::CManualMoveDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualMoveDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualMoveDlg)
	m_nRadioKotei = -1;
	//}}AFX_DATA_INIT

	// �N���X�ϐ��̏�����
	m_bEdit = FALSE;
// Modify ... ( ADD )
	m_nSublineIdFrom = 0;
	m_nSublineIdTo = 0;
	m_bSelectIvent = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
// Modify ... ( ADD )
	m_bMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.12
}


void CManualMoveDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualMoveDlg)
	DDX_Control(pDX, IDC_BUTTON_PREVIEW, m_cButtonPreview);
	DDX_Control(pDX, IDC_BUTTON_SETTEI, m_cButtonSettei);
	DDX_Control(pDX, IDC_STATIC_TOTAL_TIME2, m_cStaticTotalTime2);
	DDX_Control(pDX, IDC_STATIC_TOTAL_TIME1, m_cStaticTotalTime1);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA2, m_cComboSagyosha2);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA1, m_cComboSagyosha1);
	DDX_Control(pDX, IDC_LIST2, m_cList2);
	DDX_Control(pDX, IDC_LIST1, m_cList1);
	DDX_Radio(pDX, IDC_RADIO_KOTEI, m_nRadioKotei);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualMoveDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualMoveDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA1, OnSelchangeComboSagyosha1)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA2, OnSelchangeComboSagyosha2)
	ON_BN_CLICKED(IDC_BUTTON_SETTEI, OnButtonSettei)
	ON_BN_CLICKED(IDC_BUTTON_PREVIEW, OnButtonPreview)
	ON_BN_CLICKED(IDC_RADIO_KOTEI, OnRadioKotei)
	ON_BN_CLICKED(IDC_RADIO_YOSO_SAGYOGUN, OnRadioYosoSagyogun)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST1, OnItemchangedList1)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST2, OnItemchangedList2)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_DRAG_DROP, OnDragDrop)
	ON_MESSAGE(WM_BEGIN_DRAG, OnBeginDrag)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnInitDialog
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
//		�e�R���{�{�b�N�X������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CManualMoveDlg::OnInitDialog()
{
	m_nRadioKotei = m_nMode;

	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	// ���j���[�Ƀ��j���[�t�@�C���̕�������Z�b�g
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualMoveDlg );

	SetLixxxolumn( m_cList1, Menu_ManualMoveDlg, List_ManualMoveDlgList1, eSELECTTYPE_MULTI );
	SetLixxxolumn( m_cList2, Menu_ManualMoveDlg, List_ManualMoveDlgList2, eSELECTTYPE_MULTI );
	m_cList1.SetMultilineHeader( 1 );
	m_cList2.SetMultilineHeader( 1 );
//	SetLixxxolumn(m_cList1);
//	SetLixxxolumn(m_cList2);

//	m_cList1.SetExtendedStyle(LVS_EX_FULLROWSELECT);
//	m_cList2.SetExtendedStyle(LVS_EX_FULLROWSELECT);
// By Y.Itabashi (xxxxx) 2007.02.07

	// ��Ǝ҃R���{�{�b�N�X��ݒ�
	SetSagyoshaList();

/* �d�l�ɂ͖����̂ō폜
	// ��ƎҁA�H���A�v�f��ƌQ���w�肳��Ă����炻���\��
	if (!m_sSagyosha.IsEmpty()) {
		if (CGlobal::SelectComboBox(m_cComboSagyosha1, m_sSagyosha)) {
			SetListAll();
		}
	}
*/

	// �ŐV�ړ��t���O�N���A
	g_pDataManager->ClearNewMoveFlag();
	m_pDoc->m_bDispNewMove = FALSE;

	// �{�^����L����
	EnableButtons();

	return TRUE;
}

void CManualMoveDlg::OnRadioKotei()
{
	m_nMode = MODE_KOTEI;

	SetListAll();
}

void CManualMoveDlg::OnRadioYosoSagyogun()
{
	m_nMode = MODE_YOSO_SAGYOGUN;

	SetListAll();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnSelchangeComboSagyosha1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		������Ǝ҃R���{�{�b�N�X�I��ύX
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����H���R���{�{�b�N�X�A���X�g�{�b�N�X��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::OnSelchangeComboSagyosha1()
{
	// �H���\�����[�h�ɐݒ�
	SetListAll();

	// �{�^����L����
	EnableButtons();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnSelchangeComboSagyosha2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E����Ǝ҃R���{�{�b�N�X�I��ύX
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�E���H���R���{�{�b�N�X�A���X�g�{�b�N�X��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::OnSelchangeComboSagyosha2()
{
	// �H���\�����[�h�ɐݒ�
	SetListAll();

	// �{�^����L����
	EnableButtons();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnDragDrop
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�h���b�O���h���b�v���s
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���v���Ԃ�\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
LRESULT CManualMoveDlg::OnDragDrop(WPARAM wParam, LPARAM lParam)
{
	// �ړ�
	MoveItem((HWND)wParam, (HWND)lParam);

	// ���v���Ԃ�\��
	DispTotalTimeAll();

	// �ҏW�t���O�Z�b�g
	m_bEdit = TRUE;

	// �{�^����L����
// Modify ... ( CHANGE )
	EnableButtons();
//	m_cList1.SetPermissionDragDrop( FALSE );
//	m_cList2.SetPermissionDragDrop( FALSE );
//	m_cList1.EnableDrag( FALSE );
//	m_cList2.EnableDrag( FALSE );
// By Y.Itabashi (xxxxx) 2007.02.07

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnButtonSettei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ݒ�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�ړ����������s����B�G���[�̏ꍇ�̓f�[�^�����ɖ߂��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::OnButtonSettei()
{
	// �ړ������s
	if (MoveCheck(TRUE)) {
		// �f�[�^�X�V�t���O�ݒ�
		g_pDataManager->SetUpdate();

		// �ŐV�ړ��t���O�N���A
// Modify ... ( DELETE )
//		g_pDataManager->ClearNewMoveFlag();
// By Y.Itabashi (xxxxx) 2007.03.06
		m_pDoc->m_bDispNewMove = TRUE;

		// ����Ȃ�_�C�A���O���I��
		EndDialog(IDOK);
	}

// Modify ... ( ADD )
	g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.07
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnButtonPreview
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v���r���[�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���݂̃f�[�^���o�b�N�A�b�v������A�ړ������s���ăO���t�\�����X�V����B���̌�o�b�N�A�b�v
//		�����f�[�^��߂��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::OnButtonPreview()
{
	// �ړ������s
	if (MoveCheck(FALSE)) {
		// �O���t�\�����X�V
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[m_cComboSagyosha1.GetItemData(m_cComboSagyosha1.GetCurSel())];
		m_pDoc->DispCenterSagyosha(cSagyoshaList.nSublineId, cSagyoshaList.nSagyoshaId);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�_�C�A���O�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::OnCancel()
{
	if (m_bEdit) {
		// �f�[�^�����ɖ߂�
		g_pDataManager->RestoreUndoData();

// Modify ... ( ADD )
		g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.07
	}

	// �v���r���[���Ă�����߂����Ƃ��ɉ�ʂ��X�V����
	EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetSagyoshaList
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
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::SetSagyoshaList()
{
	int nIndex;

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// �R���{�{�b�N�X�ɍ�Ǝ҂�ݒ�
			int nSagyoshNum = g_pDataManager->GetSagyoshaNum(nSublineId);
			for (int nSagyoshId = 0; nSagyoshId < nSagyoshNum; nSagyoshId++) {
				SSagyoshaList *pSagyoshaList = m_aSagyoshaList.AddElement(nIndex);
				pSagyoshaList->nSublineId = nSublineId;
				pSagyoshaList->nSagyoshaId = nSagyoshId;

				CString &sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshId);
				m_cComboSagyosha1.SetItemData(m_cComboSagyosha1.AddString(sSagyoshaName), nIndex);
				m_cComboSagyosha2.SetItemData(m_cComboSagyosha2.AddString(sSagyoshaName), nIndex);
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetListAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S���X�g�{�b�N�X�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�S�Ẵ��X�g�{�b�N�X��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::SetListAll()
{
	// �����̃R���{�{�b�N�X�A���X�g�{�b�N�X��ݒ�
	SetList(m_aListData1, m_cComboSagyosha1, m_cList1, 1);

	// �E���̃R���{�{�b�N�X�A���X�g�{�b�N�X��ݒ�
	SetList(m_aListData2, m_cComboSagyosha2, m_cList2, 2);

	// ���v���Ԃ�\��
	DispTotalTimeAll();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�A���X�g�{�b�N�X�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int				nMode					[I] �\�����[�h
//		CListDataArray	&aListData				[I] ���X�g�f�[�^
//		CComboBox		&cComboSagyosha			[O] ��Ǝ҃R���{�{�b�N�X
//		CComboBox		&cComboKotei			[O] �H���R���{�{�b�N�X
//		CComboBox		&cComboYosoSagyogun		[O] �v�f��ƌQ�R���{�{�b�N�X
//		CLixxxtrlEx1	&cList					[O] ���X�g�{�b�N�X
//		int				nOrg					[I] 1:�ړ����A2:�ړ���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�R���{�{�b�N�X�A���X�g�{�b�N�X�Ƀf�[�^���Z�b�g����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	// �\�����[�h�ɂ�胓�{�{�b�N�X�A���X�g�{�b�N�X��ݒ�
	switch (m_nMode) {
	case MODE_KOTEI:
		// �H�����X�g�ݒ�
		SetKoteiList(aListData, cComboSagyosha, cList, nOrg);
		break;
	case MODE_YOSO_SAGYOGUN:
		// �v�f��ƌQ���X�g�ݒ�
		SetYosoSagyogunList(aListData, cComboSagyosha, cList, nOrg);
		break;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetKoteiList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�����X�g�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CListDataArray	&aListData				[I] ���X�g�f�[�^
//		CComboBox		&cComboSagyosha			[I] ��Ǝ҃R���{�{�b�N�X
//		CLixxxtrlEx1	&cList					[O] ���X�g�{�b�N�X
//		int				nOrg					[I] 1:�ړ����A2:�ړ���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���X�g�{�b�N�X�ɍH����\�����A�H���R���{�{�b�N�X��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	int i;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[16].MenuNo, Menu_ManualMoveDlg[16].ItemNo );
	cList.SetColumnText(0, str);
//	cList.SetColumnText(0, "�H��");
// By Y.Itabashi (xxxxx) 2007.01.26

	// �R���{�{�b�N�X�����Ǝ҂��擾
	int nIndex = cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[cComboSagyosha.GetItemData(nIndex)];

	// ���X�g�{�b�N�X���N���A
	aListData.RemoveAll();
	cList.DeleteAllItems();

	SGraphSubline *pGraphSubline = m_pDoc->GetGraphSublineData(cSagyoshaList.nSublineId);
	CGraphBlockArray &aGraphBlock = pGraphSubline->aGraphSagyosha[cSagyoshaList.nSagyoshaId].aKotei;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

// ���d���ώ����\��
// Modify ... ( CHANGE )
		if ( (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) || m_nGraphKishu == -1 ) {
//		if (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) {
// By Y.Itabashi (xxxxx) 2007.02.26
			CListData *pListData = aListData.AddElement();
			pListData->sName = cGraphBlock.sKoteiName;
			pListData->fTime = cGraphBlock.fTime;
// Modify ... ( ADD )
			SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
			pListData->sKishuName = pKishu->sKishuName;
			if( cGraphBlock.bKyotsuKotei )
				pListData->nKyotsuKoteiNo = cGraphBlock.nGroupNo;
			else
				pListData->nKyotsuKoteiNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
			pListData->nOrg = nOrg;
			pListData->pGraphBlock = &cGraphBlock;
		}
	}

	// ���X�g�{�b�N�X�ɃZ�b�g
	SetListBox(aListData, cList);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetYosoSagyogunList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�f��ƌQ���X�g�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CListDataArray	&aListData				[I] ���X�g�f�[�^
//		CComboBox		&cComboSagyosha			[I] ��Ǝ҃R���{�{�b�N�X
//		CLixxxtrlEx1	&cList					[O] ���X�g�{�b�N�X
//		int				nOrg					[I] 1:�ړ����A2:�ړ���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���X�g�{�b�N�X�ɗv�f��ƌQ��\�����A�v�f��ƌQ�R���{�{�b�N�X��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	int i;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[17].MenuNo, Menu_ManualMoveDlg[17].ItemNo );
	cList.SetColumnText(0, str);
//	cList.SetColumnText(0, "�v�f��ƌQ");
// By Y.Itabashi (xxxxx) 2007.01.26

	// �R���{�{�b�N�X�����Ǝ҂��擾
	int nIndex = cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[cComboSagyosha.GetItemData(nIndex)];

	// ���X�g�{�b�N�X���N���A
	aListData.RemoveAll();
	cList.DeleteAllItems();

	SGraphSubline *pGraphSubline = m_pDoc->GetGraphSublineData(cSagyoshaList.nSublineId);
	CGraphBlockArray &aGraphBlock = pGraphSubline->aGraphSagyosha[cSagyoshaList.nSagyoshaId].aYosoSagyogun;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

// Modify ... ( CHANGE )
		if ( (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) || m_pDoc->m_nGraphKind == -1 ){
//		if (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) {
// By Y.Itabashi (xxxxx) 2007.02.07
			CListData *pListData = aListData.AddElement();
			pListData->sName = cGraphBlock.sYosoSagyogunName;
			pListData->fTime = cGraphBlock.fTime;
// Modify ... ( ADD )
			SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
			pListData->sKishuName = pKishu->sKishuName;
			if( cGraphBlock.bKyotsuKotei )
				pListData->nKyotsuKoteiNo = cGraphBlock.nGroupNo;
			else
				pListData->nKyotsuKoteiNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
			pListData->nOrg = nOrg;
			pListData->pGraphBlock = &cGraphBlock;
		}
	}

	// ���X�g�{�b�N�X�ɃZ�b�g
	SetListBox(aListData, cList);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::SetListBox
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�{�b�N�X�ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CListDataArray	&aListData			[I] ���X�g�f�[�^
//		CLixxxtrlEx1	&cList				[O] ���X�g�{�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�R���{�{�b�N�X�Ƀ��X�g�f�[�^��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetListBox(CListDataArray &aListData, CLixxxtrlEx &cList)
//void CManualMoveDlg::SetListBox(CListDataArray &aListData, CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	CString sText;
	int nSize = aListData.GetSize();
	for (int i = 0; i < nSize; i++) {
		CListData &cListData = aListData[i];

// Modify ... ( CHANGE )
		cList.SetValue( i, COL_LIST_KOTEI, cListData.sName);
		cList.SetValue(i, COL_LIST_TIME, cListData.fTime);
		cList.SetValue( i, COL_LIST_KISHU, cListData.sKishuName );

		if( cListData.nKyotsuKoteiNo )
			cList.SetItemRowColor( i, COLOR_TEXT, COLOR_KYOTSU );
		cList.SetItemRowData(i, (DWORD)&cListData);
/*		sText.Format("%.6f", cListData.fTime);
		cList.InsertItem(i, cListData.sName);
		cList.SetItemText(i, 1, sText);
		cList.SetItemData(i, (DWORD)&cListData);*/
// By Y.Itabashi (xxxxx) 2007.02.07
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::DispTotalTimeAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���ԕ\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���E�̃��X�g�{�b�N�X�̍��v���Ԃ�\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::DispTotalTimeAll()
{
	// �����̃��X�g�{�b�N�X�̍��v���Ԃ�\��
	DispTotalTime(CalcTotalTime(m_cList1), m_cStaticTotalTime1);

	// �E���̃��X�g�{�b�N�X�̍��v���Ԃ�\��
	DispTotalTime(CalcTotalTime(m_cList2), m_cStaticTotalTime2);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::DispTotalTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���ԕ\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTime			[I] ���v����
//		CStatic		&cStatic		[O] �X�^�e�B�b�N�R���g���[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�X�^�e�B�b�N�R���g���[���Ɏw�肳�ꂽ���v���Ԃ�\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::DispTotalTime(double fTime, CStatic &cStatic)
{
	CString sText;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[7].MenuNo, Menu_ManualMoveDlg[7].ItemNo );
	sText.Format("%s%.3f", str, fTime);
//	sText.Format("���v���ԁF%.3f", fTime);
// By Y.Itabashi (xxxxx) 2007.01.26
	cStatic.SetWindowText(sText);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::CalcTotalTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���Ԍv�Z
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CLixxxtrlEx1	&cList			[I] ���X�g�{�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double		���v����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ���X�g�{�b�N�X���̍��v���Ԃ��v�Z����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
double CManualMoveDlg::CalcTotalTime(CLixxxtrlEx &cList)
//double CManualMoveDlg::CalcTotalTime(CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	double fTime = 0;

	// ���X�g�{�b�N�X���̍�Ǝ��Ԃ����v
	int nSize = cList.GetItemCount();
	for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cList.GetItemRowData(i);
//		CListData *pListData = (CListData *)cList.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		fTime += pListData->fTime;
	}

	return fTime;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::EnableButtons
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		OK,�v���r���[�{�^���̗L���^������ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualMoveDlg::EnableButtons()
{
	int nIndex1 = m_cComboSagyosha1.GetCurSel();
	int nIndex2 = m_cComboSagyosha2.GetCurSel();
// Modify ... ( ADD )
	if( m_bMove == 1 ){
		m_cList1.SetPermissionDragDrop(FALSE);
		m_cList2.SetPermissionDragDrop(FALSE);
	}else
// By Y.Itabashi (xxxxx) 2007.02.12
	if (nIndex1 != nIndex2) {
// Modify ... ( CHANGE )
		m_cList1.SetPermissionDragDrop(TRUE);
		m_cList2.SetPermissionDragDrop(TRUE);
//		m_cList1.EnableDrag(TRUE);
//		m_cList2.EnableDrag(TRUE);
// By Y.Itabashi (xxxxx) 2007.02.07
	} else {
// Modify ... ( CHANGE )
		m_cList1.SetPermissionDragDrop(FALSE);
		m_cList2.SetPermissionDragDrop(FALSE);
//		m_cList1.EnableDrag(FALSE);
//		m_cList2.EnableDrag(FALSE);
// By Y.Itabashi (xxxxx) 2007.02.07
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::MoveCheck
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ړ����s����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL		bLimitCheck			[I] ����`�F�b�N�̗L��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����CFALSE�F�G���[�L��
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�ړ��������s���A�H���K���`�F�b�N�A�ڕW�^�N�g����`�F�b�N���s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CManualMoveDlg::MoveCheck(BOOL bLimitCheck)
{
	CIntArrayEx aSagyoshaId;

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// �H���K���`�F�b�N
// Modify ... ( CAHNGE )			�ړ����T�u���C����ID�����������`�F�b�N���K�v
			if (!g_pDataManager->CheckKojunKisei(m_nSublineIdFrom, nSublineId, m_nMode == MODE_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun))
//			if (!g_pDataManager->CheckKojunKisei(nSublineId, m_nMode == MODE_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun))
// By Y.Itabashi (xxxxx) 2007.02.07
				return FALSE;

			// �ڕW�^�N�g����`�F�b�N
			if (bLimitCheck) {
				// �ڕW�^�N�g����`�F�b�N�Ώۂ̍�Ǝ҂��擾
				aSagyoshaId.RemoveAll();
				int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
				for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
					SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
					int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
						if (cKishuSagyo.bNewMove)
							aSagyoshaId.AddUnique(cKishuSagyo.nSagyoshaId);
					}
				}

				int nSize = aSagyoshaId.GetSize();
				for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
					if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, aSagyoshaId[i], m_nKishuId, m_nGraphKishu)) {
//					if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, aSagyoshaId[i], m_nKishuId)) {
// By Y.Itabashi (xxxxx) 2007.02.26
						if(g_pSystem->DispMessage("W013002") == IDCANCEL)
							return FALSE;
					}
				}
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualMoveDlg::GetMoveData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ړ��f�[�^�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CLixxxtrlEx1		&cList				[I] �ړ�����Ǝ�ID
//		CMoveDataArray	&aMoveData			[O] �ړ����Ǝ�ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ���X�g�{�b�N�X����A�ړ����鍀�ڂ��擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::GetMoveData(CLixxxtrlEx &cList, CMoveDataArray &aMoveData)
//void CManualMoveDlg::GetMoveData(CLixxxtrlEx1 &cList, CMoveDataArray &aMoveData)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	// ���X�g�{�b�N�X���̍��ڂ��擾
	int nCount = cList.GetItemCount();
	for (int i = 0; i < nCount; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cList.GetItemRowData(i);
//		CListData *pListData = (CListData *)cList.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		SMoveData *pMoveData = aMoveData.AddElement();
		pMoveData->sName = pListData->sName;
		pMoveData->nOrg = pListData->nOrg;
	}
}

// Modify ... ( CHANGE )
/*void CManualMoveDlg::SetLixxxolumn(CLixxxtrlEx &cList)
//void CManualMoveDlg::SetLixxxolumn(CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	cList.EnableScrollBarCtrl(SB_VERT, TRUE);

	CRect rect;
	cList.GetClientRect(rect);
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[4].MenuNo, Menu_ManualMoveDlg[4].ItemNo );
	cList.InsertColumn(0, str, LVCFMT_LEFT, rect.Width() * 4 / 5);
//	cList.InsertColumn(0, "�H��", LVCFMT_LEFT, rect.Width() * 4 / 5);

	str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[5].MenuNo, Menu_ManualMoveDlg[5].ItemNo );
	cList.InsertColumn(1, str, LVCFMT_RIGHT, rect.Width() / 5);
//	cList.InsertColumn(1, "��Ǝ���", LVCFMT_RIGHT, rect.Width() / 5);
// By Y.Itabashi (xxxxx) 2007.01.26
/*
	HD_ITEM hdi;
	memset(&hdi, 0, sizeof(hdi));
	hdi.mask = HDI_FORMAT;
	hdi.fmt = HDF_CENTER;
	cList.GetHeaderCtrl()->SetItem(0, &hdi);
*/
//}

void CManualMoveDlg::MoveItem(HWND hWndDrag, HWND hWndDrop)
{
	int nIndex1 = m_cComboSagyosha1.GetCurSel();
	int nIndex2 = m_cComboSagyosha2.GetCurSel();

// Modify ... ( CHANGE )
	CLixxxtrlEx &cListDrag = (m_cList1.m_hWnd == hWndDrag) ? m_cList1 : m_cList2;
	CLixxxtrlEx &cListDrop = (m_cList1.m_hWnd == hWndDrop) ? m_cList1 : m_cList2;

	if( cListDrag != cListDrop && ( nIndex1 == -1 || nIndex2 == -1 ) ){
		if( nIndex1 == -1 ){
			m_cList1.DeleteAllItems();
			m_aListData1.RemoveAll();
		}
		if( nIndex2 == -1 ){
			m_cList2.DeleteAllItems();
			m_aListData2.RemoveAll();
		}
		SetListAll();
		return;
	}

	if( !CheckMoveIndex( cListDrop ) ){
		SetListAll();
		return;
	}
// By Y.Itabashi (xxxxx) 2007.02.07

	SSagyoshaList &cSagyoshaListDrag = (m_cList1.m_hWnd == hWndDrag) ? m_aSagyoshaList[m_cComboSagyosha1.GetItemData(nIndex1)] : m_aSagyoshaList[m_cComboSagyosha2.GetItemData(nIndex2)];
	SSagyoshaList &cSagyoshaListDrop = (m_cList1.m_hWnd == hWndDrop) ? m_aSagyoshaList[m_cComboSagyosha1.GetItemData(nIndex1)] : m_aSagyoshaList[m_cComboSagyosha2.GetItemData(nIndex2)];

// Modify ... ( CHANGE )
	m_nSublineIdFrom = cSagyoshaListDrag.nSublineId;
	m_nSublineIdTo = cSagyoshaListDrop.nSublineId;
// By Y.Itabashi (xxxxx) 2007.02.07

	BOOL bLastBlock = FALSE;
	BOOL bSelect = FALSE;
	SGraphBlock *pGraphBlockTo = NULL;
	SGraphBlock *pGraphBlockLast = NULL;
	CGraphBlockPtrArray aGraphBlockPtr;
	int nSize = cListDrop.GetItemCount();
	for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cListDrop.GetItemRowData(i);
//		CListData *pListData = (CListData *)cListDrop.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		if (cListDrop.GetItemState(i, LVIS_SELECTED)) {
			aGraphBlockPtr.Add(pListData->pGraphBlock);
			bSelect = TRUE;
		} else {
			if (bSelect && pGraphBlockTo == NULL)
				pGraphBlockTo = pListData->pGraphBlock;
			pGraphBlockLast = pListData->pGraphBlock;
		}
	}

	if (pGraphBlockTo == NULL) {
		if (pGraphBlockLast != NULL) {
			pGraphBlockTo = pGraphBlockLast;
			bLastBlock = TRUE;
		} else {
			pGraphBlockTo = m_pDoc->GetNextGraphBlock(cSagyoshaListDrop.nSublineId, cSagyoshaListDrop.nSagyoshaId);
			bLastBlock = FALSE;
		}
			
	}

// Modify ... ( ADD )
	// �@��ʕ\���ŋ��ʍH�����܂܂�Ă�����G���[
	int nSelectSize = aGraphBlockPtr.GetSize();
// Modify ... ( ADD )
	if (m_nGraphKishu != -1) {
//	if (m_pDoc->m_nGraphKishu != -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
		for (int i = 0; i < nSelectSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];
			if (pGraphBlockFrom->bKyotsuKotei) {
				g_pSystem->DispMessage("E013001");
				aGraphBlockPtr.RemoveAll();
				pGraphBlockTo = NULL;
				SetListAll();
				return;
			}
		}
	}

	// ����H���`�F�b�N
// Modify ... ( CHANGE )		����H���`�F�b�N���֐���
	for (i = 0; i < nSelectSize; i++) {
		if( !m_pDoc->CheckSameKoteiMove( aGraphBlockPtr[i], pGraphBlockTo, bLastBlock ) ){
			aGraphBlockPtr.RemoveAll();
			pGraphBlockTo = NULL;
			SetListAll();
			return;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.10

	// �ړ��O�̈ʒu��ݒ�
	if (pGraphBlockTo != NULL){

// Modify ... ( CHANGE )
		for( int nKishu = 0; nKishu < g_pDataManager->GetKishuNum(); nKishu++ ){

			CString sCheckKoteiName;
			CString sCheckYosoSagyogunName;
			int nCheckYosoSagyogunId = -1;

			for( i = 0; i < aGraphBlockPtr.GetSize(); i++ ){
				SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];

				if( pGraphBlockFrom->nKishuId != nKishu )
					continue;

				if( sCheckKoteiName.IsEmpty() ){
					sCheckKoteiName = pGraphBlockFrom->sKoteiName;
					sCheckYosoSagyogunName = pGraphBlockFrom->sYosoSagyogunName;
					nCheckYosoSagyogunId = pGraphBlockFrom->aYosoSagyogunId[0];
				}

				g_pDataManager->SetMoveBeforeSagyojun(m_nSublineIdFrom, m_nSublineIdTo,
					pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
					sCheckKoteiName, sCheckYosoSagyogunName, nCheckYosoSagyogunId,
					pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
					m_pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
			}
		}
/*		for( i = 0; i < aGraphBlockPtr.GetSize(); i++ ){
			SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];
			g_pDataManager->SetMoveBeforeSagyojun(m_nSublineIdFrom,
				pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
				pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
				m_pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
		}*/
// By Y.Itabashi (xxxxx) 2007.02.28

		m_pDoc->MoveItem(m_nSublineIdFrom, aGraphBlockPtr, m_nSublineIdTo, pGraphBlockTo, cSagyoshaListDrop.nSagyoshaId, bLastBlock);
	}
// By Y.Itabashi (xxxxx) 2007.02.06

	m_pDoc->MakeGraphData();
	SetListAll();
// Modify ... ( ADD )
	m_bMove = 1;
// By Y.Itabashi (xxxxx) 2007.02.12
}

void CManualMoveDlg::OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	
	if( m_cList1.GetDraggingMode() || m_cList2.GetDraggingMode() )
		return;

	if( m_bSelectIvent )
		return;
	
	int Row = pNMListView->iItem;

	AutoKyotsuKoteiSelect( m_cList1, Row );

	*pResult = 0;
}

void CManualMoveDlg::OnItemchangedList2(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	
	if( m_cList1.GetDraggingMode() || m_cList2.GetDraggingMode() )
		return;
	
	if( m_bSelectIvent )
		return;
	
	int Row = pNMListView->iItem;

	AutoKyotsuKoteiSelect( m_cList1, Row );

	*pResult = 0;
}

void CManualMoveDlg::AutoKyotsuKoteiSelect( CLixxxtrlEx &cList, int Row )
{
	// �I�𑀍�����b�N
	m_bSelectIvent = 1;

	SHORT sKeyCheck1, sKeyCheck2;
	sKeyCheck1 = GetKeyState(VK_SHIFT);
	sKeyCheck2 = GetKeyState(VK_CONTROL);

	if( Row < 0 )
		return;

	UINT nState = cList.GetItemState( Row, LVIS_SELECTED );
	CListData *pListData = (CListData *)cList.GetItemRowData( Row );
	if( pListData == NULL )
		return;

	int nSelectGroupNo = pListData->nKyotsuKoteiNo;

	for( int i = 0; i < cList.GetItemCount(); i++ ){

		pListData = (CListData *)cList.GetItemRowData( i );
		if( !pListData )	continue;
		int nGroupNo = pListData->nKyotsuKoteiNo;
		if( !nGroupNo )		continue;

		if( nGroupNo && pListData->nKyotsuKoteiNo == nSelectGroupNo )
			cList.SelectChange( i );
		else if( !(sKeyCheck1 & 0x8000) && !(sKeyCheck2 & 0x8000) )
			cList.SetItemState( i, 0, LVIS_SELECTED );
	}
	
	// �I�𑀍샍�b�N����
	m_bSelectIvent = 0;
}

void CManualMoveDlg::AutoAutoKoteiSelect( CLixxxtrlEx &cList )
{
	// �I�𑀍�����b�N
	m_bSelectIvent = 1;

	CListData *pListData = NULL;
	for( int i = 0; i < cList.GetItemCount()-1; i++ ){
		if( !cList.GetItemState( i, LVIS_SELECTED ) )
			continue;

		pListData = (CListData *)cList.GetItemRowData( i+1 );
		if( !pListData )	continue;

		if( pListData->pGraphBlock->sJido == g_pConst->m_sJido )
			cList.SelectChange( i+1 );
	}
	
	// �I�𑀍샍�b�N����
	m_bSelectIvent = 0;

	cList.UpdateWindow();
}

BOOL CManualMoveDlg::CheckMoveIndex( CLixxxtrlEx &cList )
{
	int nCount = cList.GetItemCount();
	int nBeforeItem = -1;
	int nAfterItem = -1;

	int bBeforeSelect = 0;
	for( int i = 0; i < nCount; i++ ){
		int bSelect = cList.GetItemState( i, LVIS_SELECTED );
		if( bSelect && !bBeforeSelect )
			nBeforeItem = i - 1;
		else if( !bSelect && bBeforeSelect )
			nAfterItem = i;

		if( nBeforeItem != -1 && nAfterItem != -1 )
			break;

		bBeforeSelect = bSelect;
	}

	if( nBeforeItem == -1 || nAfterItem == -1 )
		return TRUE;

	CListData *pListData1 = (CListData *)cList.GetItemRowData( nBeforeItem );
	CListData *pListData2 = (CListData *)cList.GetItemRowData( nAfterItem );
	if( pListData1 == NULL || pListData2 == NULL )
		return TRUE;

	if( pListData1->nKyotsuKoteiNo == 0 || pListData2->nKyotsuKoteiNo == 0 )
		return TRUE;
	else if( pListData1->nKyotsuKoteiNo == pListData2->nKyotsuKoteiNo )
		return FALSE;

	return TRUE;
}

void CManualMoveDlg::OnBeginDrag(WPARAM wParam, LPARAM lParam) 
{
	CLixxxtrlEx &cListDrag = (m_cList1.m_hWnd == (HWND)wParam) ? m_cList1 : m_cList2;

	AutoAutoKoteiSelect( cListDrag );
}
