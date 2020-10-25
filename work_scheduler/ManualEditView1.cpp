//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView1.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView1 �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditWnd002.h"
#include "ManualEditView1.h"
#include "DataManager.h"
#include "ManualMoveDlg.h"
#include "ManualBunkatsuDlg.h"
#include "ManualEditDlg.h"
#include "ManualDstrDlg.h"
#include "SplitterEx.h"
#include "ManualEditChildWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define JIDO_SIZE	6		// �����H���̎O�p�\���̃T�C�Y

// �}�E�X�ړ����[�h
#define MOUSE_NONE		0
#define MOUSE_SELECT	1
#define MOUSE_DRAG		2

// Modify ... ( ADD )
#define MOVE_IVENT_NONE			0
#define MOVE_IVENT_MOVE			1
#define MOVE_IVENT_KITCANCEL	2
// By Y.Itabashi (xxxxx) 2007.03.05

IMPLEMENT_DYNCREATE(CManualEditView1, CManualEditView)

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::CManualEditView1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
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
CManualEditView1::CManualEditView1()
{
	// �N���X�ϐ��̏�����
	m_pRightClickZahyo = NULL;
	m_nMouseMoveMode = MOUSE_NONE;
	m_nSelectRemoveIndex = -1;
// Modify ... ( CHANGE )
	m_rectNewMove.SetRectEmpty();
//	m_rectNewMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.11

	// �X�P�[���p�����[�^�ݒ�
	SetScaleParamaeter();

	// �h���b�O��̎O�p��`�悷��u���V���쐬
	m_brushMoveDrag.CreateSolidBrush(RGB(0, 0xff, 0xff));

	m_penSelected.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbSelectProcessColor);
	m_penNewMove.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbNewMoveProcessColor);
	m_penKittingCancel.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbKittingCancelProcessColor);
// Modify ... ( ADD )
	m_penReference.CreatePen(PS_DOT, 2, g_pSystem->m_cIniUser.m_rgbReferenceProcessColor);
// By Y.Itabashi (xxxxx) 2007.02.27

	// ���ʍH���̃r�b�g�}�b�v
	BITMAP bmp;
	m_bmpKyotsuKotei.LoadBitmap(IDB_BITMAP_KYOTSU_KOTEI);
	m_bmpKyotsuKotei.GetBitmap(&bmp);
	m_sizeKyotsuKotei.cx = bmp.bmWidth;
	m_sizeKyotsuKotei.cy = bmp.bmHeight;
}


BEGIN_MESSAGE_MAP(CManualEditView1, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView1)
	ON_WM_RBUTTONDOWN()
	ON_COMMAND(IDM_MANUAL_MOVE, OnManualMove)
	ON_COMMAND(IDM_MANUAL_BUNKATSU, OnManualBunkatsu)
	ON_COMMAND(IDM_MANUAL_EDIT, OnManualEdit)
	ON_WM_LBUTTONDOWN()
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	ON_WM_CHAR()
	ON_WM_LBUTTONDBLCLK()
	ON_WM_SIZE()
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_MAE, OnManualSagyoshaMae)
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_USHIRO, OnManualSagyoshaUshiro)
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_SAKUJO, OnManualSagyoshaSakujo)
	ON_COMMAND(IDM_MANUAL_COPY, OnManualCopy)
	ON_COMMAND(IDM_MANUAL_PASTE, OnManualPaste)
	ON_COMMAND(IDM_MANUAL_DSTR, OnManualDstr)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER_DRAG, OnUserDrag)
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnInitialUpdate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r���[����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�X�N���[���o�[��\��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	SetWindowPos(&wndBottom, 0, 0, 0, 0, SWP_NOMOVE | SWP_NOSIZE);

	// �^�C�g���ݒ�
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[1].MenuNo, Menu_ManualWindowTitle[1].ItemNo );
	GetParent()->GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("�ҏW���");
// By Y.Itabashi (xxxxx) 2007.01.26

	// ������Ԃň�ԉ��ɃX�N���[��������
	CRect rect;
	GetClientRect(rect);
	ScrollToPosition(CPoint(0, m_rectView.Height() - rect.Height()));

	DispSublineInfo();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r���[����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC			[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̊e�p�[�c��`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnDraw(CDC* pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// �`�惂�[�h��TRANSPARENT�ɐݒ�
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// ��ƃO���t�`��
	DrawGraph(pDC, TRUE);

	// ���ʍH���g�`��
	DrawKyotsuKoteiFrame(pDC);

	// �ŐV�ړ��g�`��
	if (pDoc->m_bDispNewMove)
		DrawNewMoveFrame(pDC);

	// �L�b�g�����������g�`��
	DrawKittingCancelFrame(pDC);

// Modify ... ( ADD )
	// �Q�ƍH���`��
	DrawReferenceFrame(pDC);
// By Y.Itabashi (xxxxx) 2007.02.27

	// �c���`��
	DrawVerticalAxisLine(pDC);

	// �����`��
	DrawHorizontalAxisLine(pDC);

	// �c���e�L�X�g�`��
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[0].MenuNo, Menu_ManualWindowText[0].ItemNo );
	DrawVerticalAxisText(pDC, str);
//	DrawVerticalAxisText(pDC, "�y��Ǝ��ԁz");
// By Y.Itabashi (xxxxx) 2007.01.26

	// �����e�L�X�g�`��
	DrawHorizontalAxisText(pDC);

	// ���v���ԕ`��
	DrawTotalTime(pDC);

	// �ڕW�^�N�g�`��
	DrawTactLine(pDC);

	// �T�u���C���ԋ�؂���`��
	DrawSublineBoundLine(pDC);

	// �L�b�g���T�u���C���ԋ�؂���`��
	DrawKittingSubline(pDC);

	// �`�惂�[�h��߂�
	pDC->SetBkMode(nBkMode);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnUpdate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r���[�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CView		*pSender		[I] �h�L�������g���C�����ꂽ�r���[�ւ̃|�C���^
//		LPARAM		lHint			[I] �ύX�ɂ��Ă̏��
//		CObject		*pHint			[I] �ύX�ɂ��Ă̏�񂪊i�[���ꂽ�I�u�W�F�N�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�r���[���ĕ`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	CManualEditDocument *pDoc = GetDocument();
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	CFrameWnd *pActiveFrame;

// Modify ... ( CHANGE )
	// �@��ύX�őΏۉ�ʈȊO�̓A�b�v�f�[�g���Ȃ�
	if( lHint == UPDATE_GRAPH_KISHU && pHint != GetParentFrame() ){
		Invalidate();
		return;
	}
// By Y.Itabashi (xxxxx) 2007.02.27

	switch (lHint) {
	case UPDATE_SCROLL:
		if (pWnd->IsActivePane(this)) {
			SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
			DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
		}
		return;
// Modify ... ( ADD )
	case UPDATE_SCROLL_ACTIVE:
		pActiveFrame = ((CFrameWnd *)(AfxGetMainWnd()->GetActiveWindow()))->GetActiveFrame();
		if( pActiveFrame == GetParentFrame() ){
			if (pWnd->IsActivePane(this)) {
				SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
				DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
			}
		}
		return;
// By Y.Itabashi (xxxxx) 2007.03.04
	case UPDATE_CHANGE_SLIM:
		break;
	case UPDATE_CHANGE_SUBLINE:
		InitializeZahyoData();
		break;
	case UPDATE_EDIT_VIEW:
		Invalidate(FALSE);
		return;
	case UPDATE_SELECT_BLINK:
		BlinkSelectedBox((BOOL)pHint);
		return;
	case UPDATE_ERASE_MOVE_TRIANGLE:
		DrawMoveTriangle(0);
		return;
	}

	// �X�P�[���p�����[�^�ݒ�
	SetScaleParamaeter();
	SetSlimMode();

	// ���W���v�Z
	MakeGraph();

	// �r���[�̈�𖳌���
	Invalidate();

	// �T�u���C�����\��
	DispSublineInfo();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�T�C�Y�ύX
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nType			[I] �T�C�Y�ύX�̃^�C�v
//		int		cx				[I] �E�B���h�E�̕�
//		int		cy				[I] �E�B���h�E�̍���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�E�B���h�E�T�C�Y�ɍ��킹�ăO���t�̍��W���Čv�Z���čĕ`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnSize(UINT nType, int cx, int cy)
{
	CManualEditView::OnSize(nType, cx, cy);

	if (m_bInitialize) {
		// �O���t�̍��W���Čv�Z
		MakeGraph();

		// �T�u���C�����\���ʒu�ݒ�
		SetSublineInfoPos();

		// �T�u���C������\������ 20070213 xxxxx kuwa
		DispSublineInfo();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::MakeGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���f�[�^�쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�`��ɕK�v�ȃf�[�^���쐬����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();

	// �r���[�T�C�Y��ݒ�
	SetViewSize();

	// ���W�f�[�^���폜
	ClearZahyoData();
	m_aZahyoDrag.RemoveAll();
	m_aZahyoDrop.RemoveAll();
	m_aKittingCancel.RemoveAll();

	// �T�u���C���ʂɃO���t�̍��W���v�Z
	int nSublineBound = m_rectGraph.left;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1) {
			nLeft += m_nBarSpacing;
			nSublineBound += m_nBarSpacing / 2;
			m_aZahyoSubline[nSublineIndex - 1].nRight = nSublineBound;
		}

		MakeGraphSub(cGraphSubline, nLeft);

		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];
		cZahyoSubline.nSublineId = cGraphSubline.nSublineId;
		cZahyoSubline.nLeft = nSublineBound;
		nSublineBound = nLeft - m_nBarSpacing / 2;
		cZahyoSubline.nRight = nSublineBound;
	}

	// �ŐV�ړ�
// Modify ... ( CHANGE )
	m_rectNewMove.SetRectEmpty();
//	m_rectNewMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.11
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrag &cSZahyoDrag = m_aZahyoDrag[i];
		if (cSZahyoDrag.pGraphBlock->bNewMove)
			m_rectNewMove.UnionRect(m_rectNewMove, cSZahyoDrag.rect);
	}

// Modify ... ( CHANGE )
	// �I�����Z�b�g
	if( GetParentFrame() == GetDocument()->m_pSelectWnd )
		ResetSelectedBox();
//	ResetSelectedBox();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::MakeGraphSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���f�[�^�쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] �O���t��ƎҔz��
//		int					&nLeft				[I/O] �E�B���h�E�̕�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�`��ɕK�v�ȃf�[�^���쐬����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( CHANGE )
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( cGraphSubline.nSublineId );
//	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	CString sKyotsuKotei;
	int nKittingSublineId = -1;
	CRect rectSagyosha;
	SGraphBlock *pLastGraphBlock = NULL;

	CDC *pDC = GetDC();
	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// ��ƎҕʂɃO���t�̍��W���v�Z
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;

		// �O���t�̍��W
		MakeZahyoGraph(cGraphSubline.nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, nLeft, m_rectGraph.bottom, nKittingSublineId, pLastGraphBlock);

		// ��ƎҖ��̍��W
		CSize size = pDC->GetTextExtent(cGraphSagyosha.sSagyoshaName);
		rectSagyosha.left = nLeft + (m_nBarWidth - size.cx) / 2;
		rectSagyosha.top = m_rectGraph.bottom + 8;
		rectSagyosha.right = rectSagyosha.left + size.cx;
		rectSagyosha.bottom = rectSagyosha.top + size.cy;
		AddZahyoSagyosha(rectSagyosha, cGraphSubline.nSublineId, cGraphSagyosha.nSagyoshaId);


		if (!cGraphSagyosha.bKitting) {
			// ���ʍH���}�[�N�̍��W
			if (GetSagyoshaKyotsuKotei(cGraphSagyosha, sKyotsuKotei)) {
				CRect rect;
				rect.left = rectSagyosha.right + 2;
				rect.top = rectSagyosha.top;
				rect.right = rect.left + m_sizeKyotsuKotei.cx;
				rect.bottom = rect.top + m_sizeKyotsuKotei.cy;
				AddZahyoGraph(rect, sKyotsuKotei, COORDINATE_KIND_KYOTSU_KOTEI);
			}
		}

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
	ReleaseDC(pDC);
}

BOOL CManualEditView1::GetSagyoshaKyotsuKotei(SGraphSagyosha &cGraphSagyosha, CString &sKyotsuKotei)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 207.02.26
	CStringArrayEx aKyotsuKotei;

// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1) {
//	if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 207.02.26
		int nKishuNum = cGraphSagyosha.aKishu.GetSize();
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
			GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, nKishuId, aKyotsuKotei);
	} else
// Modify ... ( CHANGE )
		GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, pWnd->m_nGraphKishu, aKyotsuKotei);
//		GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, pDoc->m_nGraphKishu, aKyotsuKotei);
// By Y.Itabashi (xxxxx) 2007.02.26

	aKyotsuKotei.Unique();
	sKyotsuKotei = aKyotsuKotei.Join("\n");

	return !sKyotsuKotei.IsEmpty();
}

void CManualEditView1::GetSagyoshaKyotsuKoteiSub(SGraphSagyosha &cGraphSagyosha, int nKishuId, CStringArrayEx &aKyotsuKotei)
{
	CManualEditDocument *pDoc = GetDocument();
	CString sKyotsuKotei;

	CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
	int nSize = aGraphBlock.GetSize();
	for (int i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

		if (nKishuId == -1 || nKishuId == cGraphBlock.nKishuId) {
			if (cGraphBlock.bKyotsuKotei) {
				sKyotsuKotei = "�E";

// Modify ... ( CHANGE )
				if (cGraphBlock.sJido == g_pConst->m_sJido){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[8].MenuNo, Menu_ManualWindowText[8].ItemNo );
					sKyotsuKotei += str;
				}
				else if (cGraphBlock.sJido == g_pConst->m_sHanJido){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[9].MenuNo, Menu_ManualWindowText[9].ItemNo );
					sKyotsuKotei += str;
				}
/*				if (cGraphBlock.sJido == g_pConst->m_sJido)
					sKyotsuKotei += "��)";
				else if (cGraphBlock.sJido == g_pConst->m_sHanJido)
					sKyotsuKotei += "��)";*/
// By Y.Itabashi (xxxxx) 2007.01.26

				sKyotsuKotei += pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphBlock.sYosoSagyogunName : cGraphBlock.sKoteiName;
				aKyotsuKotei.Add(sKyotsuKotei);
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::MakeZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t���W�f�[�^�쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphBlockArray	&aGraphBlock	[I] �O���t�u���b�N�z��
//		int					nSagyoshaId		[I] ��Ǝ�ID
//		int					nKishuId		[I] �@��ID
//		int					nLeft			[I] X���W
//		int					nTop			[I] Y���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�l�p�`�̉��ӂ̍��W
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���܂��͗v�f��ƌQ�̎l�p�`�̍��W���v�Z����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::MakeZahyoGraph(int nSublineId, CGraphBlockArray &aGraphBlock, int nSagyoshaId, int nLeft, int nTop, int &nKittingSublineId, SGraphBlock *&pLastGraphBlock)
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();
	int i;
	double fBottom, fTop;
	CRect rect;
	CString sText;
	BOOL bJidoKyotsuKotei = FALSE;
	CRect rectJido;
	CStringArrayEx aJidoKoteiName;
	BOOL bHanJidoKyotsuKotei = FALSE;
	CRect rectHanJido;
	CStringArrayEx aHanJidoKoteiName;
	double fRatio;
	int nGroupNo = 0;
	int nDropTop;
	int nDropBottom = m_rectGraph.bottom;
	int nKyotsuKoteiIndex;
	CPoint pointLastJido = 0;
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );

	// �O���t�̃u���b�N�ɂ�郋�[�v
	fBottom = nTop;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];
		CString &sName = pDoc->m_nGraphKind == GRAPH_KOTEI ? cGraphBlock.sKoteiName : cGraphBlock.sYosoSagyogunName;

// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1 || cGraphBlock.nKishuId == pWnd->m_nGraphKishu) {
//		if (pDoc->m_nGraphKishu == -1 || cGraphBlock.nKishuId == pDoc->m_nGraphKishu) {
// By Y.Itabashi (xxxxx) 2007.02.26
			pLastGraphBlock = &cGraphBlock;

			// �L�b�g���̃T�u���C����؂�
			if (cGraphBlock.bKitting) {
				if (nKittingSublineId != cGraphBlock.nSublineId) {
					if (nKittingSublineId != -1) {
						SZahyoKittingSubline *pKittingSubline = m_aZahyoKittingSubline.AddElement();
						pKittingSubline->pointLine.x = nLeft;
						pKittingSubline->pointLine.y = (int)fBottom;
						pKittingSubline->sSiblineName1 = g_pDataManager->GetSublineData(cGraphBlock.nSublineId)->sSublineName;

						if (i == 0) {
							pKittingSubline = m_aZahyoKittingSubline.AddElement();
							SZahyoDrag &cZahyoDrag = m_aZahyoDrag[m_aZahyoDrag.GetSize() - 1];
							pKittingSubline->pointLine.x = cZahyoDrag.rect.left;
							pKittingSubline->pointLine.y = cZahyoDrag.rect.top;
						}

						pKittingSubline->sSiblineName2 = g_pDataManager->GetSublineData(nKittingSublineId)->sSublineName;
					}
					nKittingSublineId = cGraphBlock.nSublineId;
				}
			}

			// �O���t�̎l�p���e�̈�̍��W���v�Z
// Modify ... ( CHANGE )
			if (pWnd->m_nGraphKishu == -1) {
//			if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
				SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
				fRatio = (double)pKishu->nSeisanDaisu / nTotalSeisanDaisu;
			} else
				fRatio = 1;

			fTop = fBottom - cGraphBlock.fTime * m_fScaleFactor * fRatio;
			rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
			fBottom = fTop;
			sText.Format("%s\n%g", sName, cGraphBlock.fTime);
			AddZahyoGraph(rect, sText, COORDINATE_KIND_SAGYO, cGraphBlock.nKishuId);
			AddZahyoDrag(rect, &cGraphBlock, nSagyoshaId);
			nDropTop = (rect.bottom + rect.top) / 2;
			AddZahyoDrop(CRect(nLeft, nDropTop, rect.right, nDropBottom), &cGraphBlock, nSagyoshaId, CPoint(nLeft, rect.bottom), FALSE);
			nDropBottom = nDropTop;

			// �L�b�g�������ɂ�镜���g
			if (cGraphBlock.bKittingCancel)
				m_aKittingCancel.Add(rect);

			// ���ʍH���̘g�̍��W
			if (cGraphBlock.bKyotsuKotei) {
				if (nGroupNo != cGraphBlock.nGroupNo) {
					nKyotsuKoteiIndex = AddKyotsuKoteiFrame(rect);
					nGroupNo = cGraphBlock.nGroupNo;
				} else
					m_aZahyoKyotsuKoteiFrame[nKyotsuKoteiIndex].rect.UnionRect(m_aZahyoKyotsuKoteiFrame[nKyotsuKoteiIndex].rect, rect);
			}

			// �����H���̎O�p�̍��W���v�Z
			if (cGraphBlock.sJido == g_pConst->m_sJido) {
				sText = sName;
				if (cGraphBlock.bKyotsuKotei) {
// Modify ... ( CHANGE )
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[7].MenuNo, Menu_ManualWindowText[7].ItemNo );
					sText += str;
//					sText += "�i���ʁj";
// By Y.Itabashi (xxxxx) 2007.01.26
					bJidoKyotsuKotei = TRUE;
				}
				if (aJidoKoteiName.Find(sText) == -1) {
					if (aJidoKoteiName.GetSize() == 0) {
						rectJido.SetRect(nLeft + m_nBarWidth + 1, (int)(fBottom - JIDO_SIZE), nLeft + m_nBarWidth + JIDO_SIZE + 2, (int)(fBottom + JIDO_SIZE));

						if (pointLastJido == rectJido.TopLeft())
							rectJido.OffsetRect(JIDO_SIZE + 1, 0);

						pointLastJido = rectJido.TopLeft();
					}

					aJidoKoteiName.InsertAt(0, sText);
				}
			}
			if (cGraphBlock.sJido != g_pConst->m_sJido || cGraphBlock.fTime != 0) {
				if (aJidoKoteiName.GetSize() != 0)
					AddZahyoGraph(rectJido, aJidoKoteiName.Join("\n��\n"), bJidoKyotsuKotei ? COORDINATE_KIND_JIDO_KYOTSU : COORDINATE_KIND_JIDO);

				bJidoKyotsuKotei = FALSE;
				aJidoKoteiName.RemoveAll();
			}

			// �������H���̎O�p�̍��W���v�Z
			if (cGraphBlock.sJido == g_pConst->m_sHanJido) {
				sText = sName;
				if (cGraphBlock.bKyotsuKotei) {
// Modify ... ( CHANGE )
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[7].MenuNo, Menu_ManualWindowText[7].ItemNo );
					sText += str;
//					sText += "�i���ʁj";
// By Y.Itabashi (xxxxx) 2007.01.26
					bHanJidoKyotsuKotei = TRUE;
				}
				if (aHanJidoKoteiName.Find(sText) == -1) {
					if (aHanJidoKoteiName.GetSize() == 0) {
						rectHanJido.SetRect(nLeft + m_nBarWidth + 1, (int)(fBottom - JIDO_SIZE), nLeft + m_nBarWidth + JIDO_SIZE + 2, (int)(fBottom + JIDO_SIZE));

						if (pointLastJido == rectHanJido.TopLeft())
							rectHanJido.OffsetRect(JIDO_SIZE + 1, 0);

						pointLastJido = rectHanJido.TopLeft();
					}

					aHanJidoKoteiName.InsertAt(0, sText);
				}
			}
			if (cGraphBlock.sJido != g_pConst->m_sHanJido || cGraphBlock.fTime != 0) {
				if (aHanJidoKoteiName.GetSize() != 0)
					AddZahyoGraph(rectHanJido, aHanJidoKoteiName.Join("\n��\n"), bHanJidoKyotsuKotei ? COORDINATE_KIND_HANJIDO_KYOTSU : COORDINATE_KIND_HANJIDO);

				bHanJidoKyotsuKotei = FALSE;
				aHanJidoKoteiName.RemoveAll();
			}
		}
	}

	if (aJidoKoteiName.GetSize() != 0)
		AddZahyoGraph(rectJido, aJidoKoteiName.Join("\n��\n"), bJidoKyotsuKotei ? COORDINATE_KIND_JIDO_KYOTSU : COORDINATE_KIND_JIDO);

	if (aHanJidoKoteiName.GetSize() != 0)
		AddZahyoGraph(rectHanJido, aHanJidoKoteiName.Join("\n��\n"), bHanJidoKyotsuKotei ? COORDINATE_KIND_HANJIDO_KYOTSU : COORDINATE_KIND_HANJIDO);

	BOOL bLastBlock = TRUE;
	SGraphBlock *pLastGraphBlock2 = pLastGraphBlock;
	if (pLastGraphBlock2 == NULL) {
		pLastGraphBlock2 = pDoc->GetFirstGraphBlock(nSublineId);
		bLastBlock = FALSE;
	}
	AddZahyoDrop(CRect(nLeft, (int)fBottom - 50, nLeft + m_nBarWidth, nDropBottom), pLastGraphBlock2, nSagyoshaId, CPoint(nLeft, (int)fBottom), bLastBlock);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::SetViewSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̑傫���ɍ��킹�ăr���[�T�C�Y��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�E�B���h�E�T�C�Y�ɍ��킹�ăr���[�̃T�C�Y���v�Z����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::SetViewSize()
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	// �r���[�̃T�C�Y���v�Z
	int nSagyoshaNum = GetTotalSagyoshaNum();
	GetClientRect(m_rectView);
	m_rectView.right = m_nLeftMargin + m_nLeftSpacing + nSagyoshaNum * (m_nBarWidth + m_nBarSpacing) + m_nRightMargin;
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		m_rectView.right += m_nBarSpacing + nKittingSagyoshaNum * (m_nBarWidth + m_nBarSpacing);

	// �O���t�̈���v�Z
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	SetScaleFactor(FALSE, 0);

	// ��ԍ����o�[�ɍ��킹�ăr���[�̈���g��
// Modify ... ( CHANGE )
	int nOverHeight = GetScaleHeight(GetMaxTime(pWnd->m_nGraphKishu)) - m_rectGraph.Height();
//	int nOverHeight = GetScaleHeight(GetMaxTime(pDoc->m_nGraphKishu)) - m_rectGraph.Height();
// By Y.Itabashi (xxxxx) 2007.02.26
	if (nOverHeight > 0) {
		m_rectView.bottom += nOverHeight;
		m_rectGraph.bottom += nOverHeight;
	}

// Modify ... ( CHANGE )
	SCROLLINFO scrollInfo;
	CScrollBar *pBar;

	// �����X�N���[���o�[�̐ݒ�
	pBar = GetScrollBarCtrl( SB_HORZ );
	memset( &scrollInfo, '\0', sizeof(SCROLLINFO) );
	scrollInfo.fMask = SIF_RANGE;
	scrollInfo.nMin = 0;
	scrollInfo.nMax = m_rectView.Width();
	pBar->SetScrollInfo( &scrollInfo, TRUE );

	// �����X�N���[���o�[�̐ݒ�
	pBar = GetScrollBarCtrl( SB_VERT );
	memset( &scrollInfo, '\0', sizeof(SCROLLINFO) );
	scrollInfo.fMask = SIF_RANGE;
	scrollInfo.nMin = 0;
	scrollInfo.nMax = m_rectView.Height();
	pBar->SetScrollInfo( &scrollInfo, TRUE );
	scrollInfo.fMask = SIF_PAGE;
	if (nOverHeight > 0)
		scrollInfo.nPage = m_rectView.Height() / 10;
	else
		scrollInfo.nPage = m_rectView.Height();
	pBar->SetScrollInfo( &scrollInfo, TRUE );
	if( nOverHeight<= 0 )
		pBar->EnableWindow( FALSE );

	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));
// By Y.Itabashi (xxxxx) 2007.03.04
}

void CManualEditView1::DrawNewMoveFrame(CDC *pDC)
{
	if (!m_rectNewMove.IsRectNull()) {
		CPen *pPenOrg = pDC->SelectObject(&m_penNewMove);
		CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

		CRect rect(m_rectNewMove.left, m_rectNewMove.top, m_rectNewMove.right + 1, m_rectNewMove.bottom + 1);
		pDC->Rectangle(rect);

		pDC->SelectObject(pPenOrg);
		pDC->SelectObject(pBrushOrg);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::DrawHorizontalAxisText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����e�L�X�g�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC			[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����̃e�L�X�g�i��ƎҖ��j��`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawHorizontalAxisTextSub(pDC, pDoc->m_aGraphSubline[nSublineIndex], nLeft);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::DrawHorizontalAxisTextSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����e�L�X�g�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC					*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] �O���t��ƎҔz��
//		int					&nLeft				[I/O] X���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�����̃e�L�X�g�i��ƎҖ��j��`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft)
{
	CManualEditDocument *pDoc = GetDocument();

	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// ��Ǝ҂ɂ�郋�[�v
	CRect rect;
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		CSize size = pDC->GetTextExtent(cGraphSagyosha.sSagyoshaName);
		int x = nLeft + (m_nBarWidth - size.cx) / 2;
		rect.SetRect(x, m_rectGraph.bottom + 8, x + size.cx, m_rectView.bottom);
		if (pDC->RectVisible(rect)) {
//			double fTime = (pDoc->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pDoc->m_nGraphKishu].fTime;

			// ��ƎҖ���\��
			int color;
			if (pDoc->m_cSelectSagyosha.bSelect && pDoc->m_cSelectSagyosha.nSubnlineId == cGraphSubline.nSublineId && pDoc->m_cSelectSagyosha.nSagyoshaId == cGraphSagyosha.nSagyoshaId)
				color = pDC->SetTextColor(g_pSystem->m_cIniUser.m_rgbSelectWorkerColor);
			else
				color = pDC->SetTextColor(RGB(0, 0, 0));
			pDC->TextOut(rect.left, rect.top, cGraphSagyosha.sSagyoshaName);
			pDC->SetTextColor(color);
		}

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::DrawTotalTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���ԕ`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC			[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̃o�[�̏�ɍ��v���Ԃ�`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::DrawTotalTime(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawTotalTimeSub(pDC, pDoc->m_aGraphSubline[nSublineIndex], nLeft);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::DrawTotalTimeSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���ԕ`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC					*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] �O���t��ƎҔz��
//		int					&nLeft				[I/O] X���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̃o�[�̏�ɍ��v���Ԃ�`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::DrawTotalTimeSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	CFont *pFontOrg = pDC->SelectObject(&m_fontTime);

	CString sTime;
	CRect rectText;

	// ��Ƃɂ�郋�[�v
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
// Modify ... ( CHANGE )
		double fTime = (pWnd->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pWnd->m_nGraphKishu].fTime;
//		double fTime = (pDoc->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pDoc->m_nGraphKishu].fTime;
// By Y.Itabashi (xxxxx) 2007.02.26
		DrawTime(pDC, cGraphSubline, nLeft, fTime);

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
}

// �L�b�g�������ɂ�镜���g�`��
void CManualEditView1::DrawKittingCancelFrame(CDC *pDC)
{
// Modify ... ( ADD )
	// �ŐV�̈ړ��̕\��
	if( m_rectNewMove.IsRectNull() ){
		CIntArrayEx aCheckArea;
		aCheckArea.RemoveAll();

		while( 1 ){

			CRect newRect;
			newRect.SetRectEmpty();

			for( int i = 0; i < m_aKittingCancel.GetSize(); i++ ){
				if( aCheckArea.Find( i ) != -1 )
					continue;

				if( newRect.IsRectNull() ){
					newRect = m_aKittingCancel[i];
					aCheckArea.Add( i );
					continue;
				}

				if( newRect.left != m_aKittingCancel[i].left )
					continue;

				if( newRect.top == m_aKittingCancel[i].bottom ||
					newRect.bottom == m_aKittingCancel[i].top )
					newRect.UnionRect( newRect, m_aKittingCancel[i] );
				else
					continue;

				aCheckArea.Add( i );
			}

			if( !newRect.IsRectNull() ){
				CPen *pPenOrg = pDC->SelectObject(&m_penNewMove);
				CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

				CRect rect(newRect.left, newRect.top, newRect.right + 1, newRect.bottom + 1);
				pDC->Rectangle(rect);

				pDC->SelectObject(pPenOrg);
				pDC->SelectObject(pBrushOrg);
			}else
				break;
		}
		aCheckArea.RemoveAll();
	}
// By Y.Itabashi (xxxxx) 2007.03.06

	CPen *pPenOrg = pDC->SelectObject(&m_penKittingCancel);

	int nSize = m_aKittingCancel.GetSize();
	for (int i = 0; i < nSize; i++) {
		CRect rect = m_aKittingCancel[i];

		pDC->MoveTo(rect.left, rect.top);
		pDC->LineTo(rect.right, rect.top);
		pDC->MoveTo(rect.left, rect.top - 1);
		pDC->LineTo(rect.right, rect.top - 1);

		pDC->MoveTo(rect.right, rect.top);
		pDC->LineTo(rect.right, rect.bottom);
		pDC->MoveTo(rect.right - 1, rect.top);
		pDC->LineTo(rect.right - 1, rect.bottom);

		pDC->MoveTo(rect.left, rect.bottom);
		pDC->LineTo(rect.right, rect.bottom);
		pDC->MoveTo(rect.left, rect.bottom - 1);
		pDC->LineTo(rect.right, rect.bottom - 1);

		pDC->MoveTo(rect.left, rect.top);
		pDC->LineTo(rect.left, rect.bottom);
		pDC->MoveTo(rect.left - 1, rect.top);
		pDC->LineTo(rect.left - 1, rect.bottom);
	}

	pDC->SelectObject(pPenOrg);
}

// �Q�ƍH���g�`��
void CManualEditView1::DrawReferenceFrame(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nReferenceBox = pDoc->m_aRefYosoSagyogun.GetSize();
	if (nReferenceBox == 0)
		return;

	int i, j;
	CArray <CRect, CRect&> aBlinkRect;

	for (i = 0; i < nReferenceBox; i++) {

		if( nGraphKishu != pDoc->m_aRefYosoSagyogun[i].nKishuId )
			continue;

		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aRefYosoSagyogun[i].nIndex : pDoc->m_aRefYosoSagyogun[i].nIndexKishu;
		CRect rectRefBox = m_aZahyoDrag[nIndex].rect;

		int nBlinkRect = aBlinkRect.GetSize();
		for (j = 0; j < nBlinkRect; j++) {
			CRect &rectBlink = aBlinkRect[j];

			if (rectRefBox.left == rectBlink.left) {
				if (rectRefBox.bottom == rectBlink.top) {
					rectBlink.top = rectRefBox.top;
					break;
				}

				if (rectRefBox.top == rectBlink.bottom) {
					 rectBlink.bottom = rectRefBox.bottom;
					break;
				}
			}
		}

		if (j == nBlinkRect)
			aBlinkRect.Add(rectRefBox);
	}

	CPen *pPenOrg = pDC->SelectObject(&m_penReference);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

	for (j = 0; j < aBlinkRect.GetSize(); j++) {
		CRect rect(aBlinkRect[j]);
		rect.right++;
		rect.bottom++;
		pDC->Rectangle(rect);
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnRButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ���z�L�[�̏��
//		CPoint		point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�E�N���b�N���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnRButtonDown(UINT nFlags, CPoint point)
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();

	// �N���b�N���ꂽ�O���t�̍��W�����߂�
	CRect rect;
	m_pRightClickZahyo = CheckZahyoDrag(point, rect);

	// ���\�[�X���烁�j���[�����[�h
	CMenu cMenu;
	cMenu.LoadMenu(IDR_MANUAL_POPUPMENU);
	CMenu* pPopup = cMenu.GetSubMenu(0);

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( (CWnd *)pPopup, Menu_ManualWindowPopup );
// By Y.Itabashi (xxxxx) 2007.01.26

	if (m_pRightClickZahyo == NULL) {
// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1) {
//		if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
			// �O���t�̃o�[�̏�łȂ���������d���ϕ\���ł̈ړ��A�����폜�͕s��
// Modify ... ( CHANGE )
//			pPopup->EnableMenuItem(IDM_MANUAL_MOVE, MF_GRAYED);
// By Y.Itabashi (xxxxx)
			pPopup->EnableMenuItem(IDM_MANUAL_BUNKATSU, MF_GRAYED);
		}
	}

	// ���d���ϕ\�����͕ҏW�s��
// Modify ... ( CHANGE )
	// UP4�o�׃��W���[���p
// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1)
//	if (pDoc->m_nGraphKishu == -1)
// By Y.Itabashi (xxxxx) 2007.02.26
		pPopup->EnableMenuItem(IDM_MANUAL_EDIT, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.22
// Modify ... ( ADD )
// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1)
//	if (pDoc->m_nGraphKishu == -1)
// By Y.Itabashi (xxxxx) 2007.02.26
		pPopup->EnableMenuItem(IDM_MANUAL_PASTE, MF_GRAYED);
// By Y.Itabashi (xxxxx)

	// �����I������Ă�����R�s�[�͕s��
// Modify ... ( CHANGE )
	// UP4�o�׃��W���[���p
//	if (pDoc->m_aSelectedBox.GetSize() > 1 || pDoc->m_aSelectedBox.GetSize() == 0 || pDoc->m_bSelectKitting)
	if (pDoc->m_aSelectYosoSagyogun.GetSize() > 1 || pDoc->m_aSelectYosoSagyogun.GetSize() == 0 || pDoc->m_bSelectKitting)
		pPopup->EnableMenuItem(IDM_MANUAL_COPY, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.27

// Modify ... ( ADD )
	// UP4�o�׃��W���[���p
//	pPopup->EnableMenuItem(IDM_MANUAL_EDIT, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_MOVE, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_COPY, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_PASTE, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.22

	// �N���b�N���ꂽ�ʒu�Ƀ|�b�v�A�b�v���j���[��\��
	::GetCursorPos(&point);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, point.x, point.y, this);

	CManualEditView::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnManualMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���E�v�f��ƌQ�̈ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���E�v�f��ƌQ�̈ړ��_�C�A���O���J���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnManualMove()
{
	// ���C���K���t�@�C�����J���Ă�����G���[
	if (g_pDataManager->m_bOpenLineKiseiMatrix) {
		g_pSystem->DispMessage("E006002");
		return;
	}

	// �^���K���t�@�C�����J���Ă�����G���[
	if (g_pDataManager->m_bOpenKatashikiKiseiMatrix) {
		g_pSystem->DispMessage("E006102");
		return;
	}

	CManualMoveDlg dlg(this);

	CManualEditDocument *pDoc = GetDocument();
	if (m_pRightClickZahyo != NULL) {
		// �O���t�̃o�[��Ȃ炻�̃f�[�^���_�C�A���O�ɓn��
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
		dlg.m_sSagyosha = g_pDataManager->GetSagyoshaName(m_pRightClickZahyo->pGraphBlock->nSublineId, m_pRightClickZahyo->nSagyoshaId);
	} else {
		// �O���t�̃o�[��łȂ��Ȃ�@����_�C�A���O�ɓn��
// Modify ... ( CHANGE )
		dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
		dlg.m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_nMode = pDoc->m_nGraphKind;
	dlg.m_pDoc = pDoc;

	// �H���E�v�f��ƌQ�̈ړ��_�C�A���O�\��
	if (dlg.DoModal() == IDOK) {
		// OK�{�^���Ȃ�O���t���X�V
		GetDocument()->UpdateGraph(FALSE);

		// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancel�{�^���Ȃ�O���t�����ɖ߂�
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnManualBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���E�v�f��ƌQ�̕����폜����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���E�v�f��ƌQ�̕����폜�_�C�A���O���J���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnManualBunkatsu()
{
	CManualBunkatsuDlg dlg(this);

	if (m_pRightClickZahyo != NULL) {
		// �O���t�̃o�[��Ȃ炻�̃f�[�^���_�C�A���O�ɓn��
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
/*
		dlg.m_nSagyoshaId = m_pRightClickZahyo->nSagyoshaId;
		dlg.m_sKoteiName = m_pRightClickZahyo->pGraphBlock->sKoteiName;
		dlg.m_sYosoSagyogunName = m_pRightClickZahyo->pGraphBlock->sYosoSagyogunName;
*/
	} else {
		// �O���t�̃o�[��łȂ��Ȃ�@����_�C�A���O�ɓn��
// Modify ... ( CHANGE )
		CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
		dlg.m_nKishuId = pWnd->m_nGraphKishu;
//		CManualEditDocument *pDoc = GetDocument();
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_pDoc = GetDocument();

	// �H���E�v�f��ƌQ�̕����폜�_�C�A���O�\��
	if (dlg.DoModal() == IDOK) {
		// OK�{�^���Ȃ�O���t���X�V
		GetDocument()->UpdateGraph(TRUE);

		// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancel�{�^���Ȃ�O���t�����ɖ߂�
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnManualEdit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H���E�v�f��ƌQ�̏C������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�H���E�v�f��ƌQ�̏C���_�C�A���O���J���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnManualEdit()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditDlg dlg(this);

	if (m_pRightClickZahyo != NULL && !m_pRightClickZahyo->pGraphBlock->bKitting) {
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
		dlg.m_nSagyoshaId = m_pRightClickZahyo->nSagyoshaId;
	} else {
// Modify ... ( CHANGE )
		dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_pDoc = pDoc;

	if (dlg.DoModal() == IDOK) {
		// OK�{�^���Ȃ�O���t���X�V
		GetDocument()->UpdateGraph(TRUE);

		// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancel�{�^���Ȃ�O���t�����ɖ߂�
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���_�E������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ���z�L�[�̏��
//		CPoint		point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�h���b�O�J�n�̃}�E�X�J�[�\���̈ʒu��ۑ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnLButtonDown(UINT nFlags, CPoint point)
{

// Modify ... ( ADD )
	// �O�ɍ�Ƃ�I��������ʂƓ����ʂłȂ���΁A�I���N���A
	if( GetParentFrame() != GetDocument()->m_pSelectWnd )
		ResetSelectedBox();

	GetDocument()->m_pSelectWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27

	// �N���b�N���ꂽ���W���`�F�b�N
	SZahyoDrag *pZahyoDrag = CheckZahyoDrag(point, m_rectDrag);
	if (pZahyoDrag != NULL) {
// Modify ... ( CHANGE )
		if (!pZahyoDrag->pGraphBlock->bKitting)
			ClickSublineArea(point, pZahyoDrag, nFlags);
//			ClickSublineArea(point, pZahyoDrag);
		else
			ClickKittingArea(point, pZahyoDrag, nFlags);
//			ClickKittingArea(point, pZahyoDrag);
// By Y.Itabashi (xxxxx) 2007.02.20
	} else {
		// �V�t�g�L�[��������ăL�b�g���G���A�Ȃ�I�����������Ȃ�
		SZahyoSubline *pZahyoSubline = GetZahyoSubline(point);
// Modify ... ( CHANGE )
		if ( pZahyoSubline == NULL || pZahyoSubline->nSublineId != -1 ||
			 ( nFlags != 0x0005 && pZahyoSubline->nSublineId == -1 ) )
//		if (!(GetKeyState(VK_SHIFT) & 0x8000) || pZahyoSubline == NULL || pZahyoSubline->nSublineId != -1)
// By Y.Itabashi (xxxxx) 2007.03.06
			ResetSelectedBox();

		SetCapture();
		m_rectSelect.SetRect(point, point);
		DrawFocusRect(m_rectSelect);
		m_nMouseMoveMode = MOUSE_SELECT;
	}

	SelectSagyosha(point);

	CManualEditView::OnLButtonDown(nFlags, point);
}

// Modify ... ( CHANGE )
void CManualEditView1::ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags)
//void CManualEditView1::ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag)
// By Y.Itabashi (xxxxx) 2007.02.21
{
	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27

	// �V�t�g�L�[���`�F�b�N
// Modify ... ( CHANGE )
	if (nFlags == 0x0005) {
//	if (GetKeyState(VK_SHIFT) & 0x8000) {
// By Y.Itabashi (xxxxx) 2007.02.21
		// �V�t�g�L�[��������Ă���ꍇ�̏���
		if (nSelectedBox == 0) {
			// �ЂƂ��I������Ă��Ȃ��ꍇ�̓N���b�N�����ꏊ��I������
			AddSelect(pZahyoDrag->nIndex);
		} else {
			// ���ɑI������Ă���ꍇ�́A�N���b�N�����ꏊ�Ƃ̊Ԃ�S�đI������
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[nIndex];
			if (cSelectedZahyoDrag.rect.left == pZahyoDrag->rect.left) {
//			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[0]];
//			if (m_aZahyoDrag[pDoc->m_aSelectedBox[0]].rect.left == pZahyoDrag->rect.left) {
// By Y.Itabashi (xxxxx) 2007.02.27
				CRect rect = GetSelectRect();
				rect.UnionRect(rect, pZahyoDrag->rect);
	
				ResetSelectedBox();
				int nSize = m_aZahyoDrag.GetSize();
				for (i = 0; i < nSize; i++) {
					SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
					if (rect.PtInRect(cZahyoDrag.rect.TopLeft()))
						AddSelect(cZahyoDrag.nIndex);
				}
			} else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		}
	} else {
		// �V�t�g�L�[��������Ă��Ȃ��ꍇ�̏���
		if (nSelectedBox == 0) {
			// �ЂƂ��I������Ă��Ȃ��ꍇ�̓N���b�N�����ꏊ��I������
			AddSelect(pZahyoDrag->nIndex);
		} else if (nSelectedBox == 1) {
			// �ЂƂI������Ă���ꍇ�́A������������ăN���b�N�����ꏊ��I������
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[nIndex];
			if (pZahyoDrag->nIndex == cSelectedZahyoDrag.nIndex)
//			if (pZahyoDrag->nIndex == m_aZahyoDrag[pDoc->m_aSelectedBox[0]].nIndex)
// By Y.Itabashi (xxxxx) 2007.02.27
				m_nSelectRemoveIndex = pZahyoDrag->nIndex;
			else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		} else {
			// �����I������Ă���ꍇ�́A�㉺�[�Ȃ�I������������
// Modify ... ( CHANGE )
			int m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
			int nLastIndex = nSelectedBox-1;
// Modify ... ( CHANGE )
			for( i = nSelectedBox-1; i >= 0 && m_nGraphKishu != -1; i-- ){
//			for( i = nSelectedBox-1; i >= 0; i-- ){
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId == m_nGraphKishu ){
					nLastIndex = i;
					break;
				}
// By Y.Itabashi (xxxxx) 2007.03.06

			}
			for (i = 0; i < nSelectedBox; i++) {
// Modify ... ( CHANGE )
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu &&
					m_nGraphKishu != -1 )
//				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu )
// By Y.Itabashi (xxxxx) 2007.03.05
					continue;

				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				if (m_aZahyoDrag[nIndex].nIndex == pZahyoDrag->nIndex)
//				if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex == pZahyoDrag->nIndex)
					break;
			}
			if (i == 0 || i == nLastIndex) {
//			if (i == 0 || i == nSelectedBox - 1) {
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				m_nSelectRemoveIndex = m_aZahyoDrag[nIndex].nIndex;
//				m_nSelectRemoveIndex = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex;
			} else if (i == nSelectedBox) {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
// By Y.Itabashi (xxxxx) 2007.02.27
		}

		m_pointDrag = point;
		m_nMouseMoveMode = MOUSE_DRAG;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// Modify ... ( CHANGE )
void CManualEditView1::ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags)
//void CManualEditView1::ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag)
// By Y.Itabashi (xxxxx) 2007.02.21
{
// Modify ... ( ADD )
	// UP4�p���W���[��
//	return;
// By Y.Itabashi (xxxxx) 2007.02.14

	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27

	// �V�t�g�L�[���`�F�b�N
// Modify ... ( CHANGE )
	if (nFlags == 0x0005) {
//	if (GetKeyState(VK_SHIFT) & 0x8000) {
// By Y.Itabashi (xxxxx) 2007.02.21
		// �V�t�g�L�[��������Ă���ꍇ�̏���
		AddSelect(pZahyoDrag->nIndex);
	} else {
		// �V�t�g�L�[��������Ă��Ȃ��ꍇ�̏���
		if (nSelectedBox == 0) {
			// �ЂƂ��I������Ă��Ȃ��ꍇ�̓N���b�N�����ꏊ��I������
			AddSelect(pZahyoDrag->nIndex);
		} else if (nSelectedBox == 1) {
			// �ЂƂI������Ă���ꍇ�́A������������ăN���b�N�����ꏊ��I������
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			if (pZahyoDrag->nIndex == m_aZahyoDrag[nIndex].nIndex)
//			if (pZahyoDrag->nIndex == m_aZahyoDrag[pDoc->m_aSelectedBox[0]].nIndex)
// By Y.Itabashi (xxxxx) 2007.02.27
				m_nSelectRemoveIndex = pZahyoDrag->nIndex;
			else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		} else {
			// �����I������Ă���ꍇ�̓N���b�N�����ꏊ�̑I������������
// Modify ... ( CHANGE )
			for (i = 0; i < nSelectedBox; i++) {
// Modify ... ( CHANGE )
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
					nGraphKishu != -1 )
//				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu )
// By Y.Itabashi (xxxxx) 2007.03.06
					continue;

				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				if (m_aZahyoDrag[nIndex].nIndex == pZahyoDrag->nIndex)
//				if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex == pZahyoDrag->nIndex)
					break;
			}
// By Y.Itabashi (xxxxx) 2007.02.27
			if (i == nSelectedBox) {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
// Modify ... ( CHANGE )
			} else{
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				m_nSelectRemoveIndex = m_aZahyoDrag[nIndex].nIndex;
			}
//				m_nSelectRemoveIndex = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex;
// By Y.Itabashi (xxxxx) 2007.02.27
		}

		m_pointDrag = point;
		m_nMouseMoveMode = MOUSE_DRAG;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���A�b�v����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ���z�L�[�̏��
//		CPoint		point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���b�N���ꂽ���W���L�����Z������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnLButtonUp(UINT nFlags, CPoint point)
{
	switch (m_nMouseMoveMode) {
	case MOUSE_SELECT:
		DrawFocusRect(m_rectSelect);
		SelectRect();
		ReleaseCapture();
		break;
	}

	if (m_nSelectRemoveIndex != -1) {
		RemoveSelect(m_nSelectRemoveIndex);
		m_nSelectRemoveIndex = -1;
	}

	m_nMouseMoveMode = MOUSE_NONE;

// Modify ... ( ADD )
	GetDocument()->m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.28

	CManualEditView::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ���z�L�[�̏��
//		CPoint		point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���b�N����Ă���ꍇ�̓h���b�O���J�n���A�h���b�v���ꂽ��ړ��������s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnMouseMove(UINT nFlags, CPoint point)
{
	CManualEditDocument *pDoc = GetDocument();

	switch (m_nMouseMoveMode) {
	case MOUSE_SELECT:
		DrawFocusRect(m_rectSelect);
		m_rectSelect.right = point.x;
		m_rectSelect.bottom = point.y;
		DrawFocusRect(m_rectSelect);
		break;
	case MOUSE_DRAG:
		// �h���b�O���ɓ_�ł�����ƃh���b�O�g��������̂œ_�ł��~�߂�
		pDoc->StopBlink(TRUE);
// Modify ... ( ADD )
		if( pDoc->m_pDragWnd && pDoc->m_pDragWnd != GetParentFrame() )
			break;
// By Y.Itabashi (xxxxx) 2007.02.28

		// �N���b�N����Ă���Ȃ�h���b�O����l�p��\��
		CRectTrackerEx cRectTracker(GetSelectRect() - GetScrollPosition(), CRectTracker::hatchedBorder);
		pDoc->m_pDrop = NULL;
		m_pointDragOld = 0;
		if (cRectTracker.Track(AfxGetMainWnd(), m_pointDrag, FALSE, NULL)) {
			if (!pDoc->m_bSelectKitting) {
				// �O���t�̃o�[��Ńh���b�v���ꂽ��O���t�̎�ނɂ��H���܂��͗v�f��ƌQ�̈ړ�����
				if (pDoc->m_pDrop != NULL)
					MoveDrag();
			} else {
				// �L�b�g����Ƃ��I������Ă��ăT�u���C���G���A�Ńh���b�v���ꂽ��L�b�g������
				if (pDoc->m_nDropSublineId != -1)
					CancelKitting();
			}
		}
		pDoc->StopBlink(FALSE);
		pDoc->UpdateAllViews(NULL, UPDATE_ERASE_MOVE_TRIANGLE);
		m_nMouseMoveMode = MOUSE_NONE;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.28
		m_nSelectRemoveIndex = -1;
		break;
	}

	CManualEditView::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnUserDrag
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�h���b�O���̃}�E�X�ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM	wParam			[I] �E�B���h�E���b�Z�[�W�p�����[�^�P
//		LPARAM	lParam			[I] �E�B���h�E���b�Z�[�W�p�����[�^�Q
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�}�E�X�J�[�\�����ړ��\�Ȉʒu�ɂ���ꍇ�́A�ړ���̎O�p�`��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
LRESULT CManualEditView1::OnUserDrag(WPARAM wParam, LPARAM lParam)
{
	CManualEditDocument *pDoc = GetDocument();

	CPoint point;
	::GetCursorPos(&point);
	ScreenToClient(&point);

// Modify ... ( ADD )
	if( pDoc->m_pDragWnd != GetParentFrame() )
		return 0;

	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.28

	// �L�b�g����Ƃ��I������Ă���ꍇ�͉������Ȃ�
	if (!pDoc->m_bSelectKitting) {
		if (wParam == USER_DRAG_ENTER || wParam == USER_DRAG_OVER) {
// Modify ... ( CHANGE )
			if (pDoc->m_aSelectYosoSagyogun.GetSize() != 0) {
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
				SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//			if (pDoc->m_aSelectedBox.GetSize() != 0) {
//				SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[0]];
// By Y.Itabashi (xxxxx) 2007.02.27

				CRect rect;
				pDoc->m_pDrop = CheckZahyoDrop(point, rect);
				if (pDoc->m_pDrop != NULL
						&& pDoc->m_pDrop->pGraphBlock->bKitting == cZahyoDrag.pGraphBlock->bKitting
						&& (cZahyoDrag.pGraphBlock->nSublineId != pDoc->m_pDrop->pGraphBlock->nSublineId
						|| cZahyoDrag.nSagyoshaId != pDoc->m_pDrop->nSagyoshaId
						|| !CheckSelect(pDoc->m_pDrop->pGraphBlock))) {
					int nIndex1 = pDoc->m_pDrop->nIndex;
					int nIndex2 = nIndex1 - 1;

					if (nIndex2 == -1
							|| m_aZahyoDrop[nIndex1].bLastBlock
							|| m_aZahyoDrop[nIndex1].nSagyoshaId != m_aZahyoDrop[nIndex2].nSagyoshaId
							|| !m_aZahyoDrop[nIndex1].pGraphBlock->bKyotsuKotei
							|| !m_aZahyoDrop[nIndex2].pGraphBlock->bKyotsuKotei
							|| m_aZahyoDrop[nIndex1].pGraphBlock->nGroupNo != m_aZahyoDrop[nIndex2].pGraphBlock->nGroupNo) {
						if (wParam == USER_DRAG_ENTER)
							m_pointDragOld = CPoint(0, 0);

						// �O���t�̃o�[��Ȃ�ړ���̎O�p��\��
						DrawMoveTriangle(pDoc->m_pDrop->pointTriangle - GetScrollPosition());

						return 0;
					}
				}
			}
		}

		// �O���t�̃o�[��łȂ��Ȃ�ړ���̎O�p������
		DrawMoveTriangle(0);
		pDoc->m_pDrop = NULL;
	} else {
		SZahyoSubline *pZahyoSubline = GetZahyoSubline(point);
		if (pZahyoSubline != NULL)
			pDoc->m_nDropSublineId = pZahyoSubline->nSublineId;
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::DrawMoveTriangle
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ړ���̎O�p�`�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint		point			[I] �O�p�`��`�悷����W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�ʒu�ɎO�p�`��`�悷��B�ȑO�̈ʒu����͏�������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::DrawMoveTriangle(CPoint point)
{
	if (point != m_pointDragOld) {
		// �`�惂�[�h�ݒ�
		CDC *pDC = GetDC();
		int nDrawMode = pDC->SetROP2(R2_XORPEN);
		CBrush *pBrush = pDC->SelectObject(&m_brushMoveDrag);
		CPen *pPen = pDC->SelectObject((CPen *)pDC->SelectStockObject(NULL_PEN));

		// �ȑO�̎O�p�`������
		if (m_pointDragOld != CPoint(0, 0)) {
			CRect rect(m_pointDragOld.x - 8, m_pointDragOld.y - 6, m_pointDragOld.x - 1, m_pointDragOld.y + 6);
			DrawRightTriangle(pDC, rect);
			m_pointDragOld = 0;
		}

		// �V�����ʒu�ɎO�p�`��`��
		if (point != CPoint(0, 0)) {
			CRect rect(point.x - 8, point.y - 6, point.x - 1, point.y + 6);
			DrawRightTriangle(pDC, rect);
		}
		m_pointDragOld = point;

		pDC->SelectObject(pPen);
		pDC->SelectObject(pBrush);
		pDC->SetROP2(nDrawMode);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::MoveKotei
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�h���b�O�ɂ��H���ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�h���b�O�ɂ��H���ړ����s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::MoveDrag()
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( ADD )
	int nGraphKishu;
	int nKishu;
	int nSize;
	BOOL bSetGraph;
	int nSetIndex;
	CGraphBlockPtrArray aGraphBlockFrom;
	CSelectYosoSagyogunArray m_aSelectYosoSagyogunBack;
	CReferenceYosoSagyogunArray m_aRefYosoSagyogunBack;

	m_aSelectYosoSagyogunBack.RemoveAll();
	for( i = 0; i < pDoc->m_aSelectYosoSagyogun.GetSize(); i++ )
		m_aSelectYosoSagyogunBack.Add( pDoc->m_aSelectYosoSagyogun[i] );

	m_aRefYosoSagyogunBack.RemoveAll();
	for( i = 0; i < pDoc->m_aRefYosoSagyogun.GetSize(); i++ )
		m_aRefYosoSagyogunBack.Add( pDoc->m_aRefYosoSagyogun[i] );
	// ���オ�����H���Ȃ炻����I������
	pDoc->AddSelectAutoKotei();
// By Y.Itabashi (xxxxx) 2007.03.07

	SGraphBlock *pGraphBlockTo = pDoc->m_pDrop->pGraphBlock;
// Modify ... ( CHANGE )
	int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
	SGraphBlock *pGraphBlockFrom = m_aZahyoDrag[nIndex].pGraphBlock;
//	SGraphBlock *pGraphBlockFrom = m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock;
// By Y.Itabashi (xxxxx) 2007.02.27
	int nSublineIdFrom = pGraphBlockFrom->nSublineId;
	int nSublineIdTo = pGraphBlockTo->nSublineId;

	// ���C���K���t�@�C�����J���Ă�����G���[
	if (g_pDataManager->m_bOpenLineKiseiMatrix) {
		g_pSystem->DispMessage("E006002");
		return;
	}

	// �^���K���t�@�C�����J���Ă�����G���[
	if (g_pDataManager->m_bOpenKatashikiKiseiMatrix) {
		g_pSystem->DispMessage("E006102");
		return;
	}

	// �@��ʕ\���ŋ��ʍH�����܂܂�Ă�����G���[
// Modify ... ( CHANGE )
	int nSelectSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	if (pWnd->m_nGraphKishu != -1) {
//	if (pDoc->m_nGraphKishu != -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
		for (int i = 0; i < nSelectSize; i++) {
// Modify ... ( CHANGE )
			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
// By Y.Itabashi (xxxxx) 2007.02.27
			if (cZahyoDrag.pGraphBlock->bKyotsuKotei) {
				g_pSystem->DispMessage("E013001");
				goto ERR;	// Modify... ( CHANGE )
			}
		}
	}

	// ����H���`�F�b�N
// Modify ... ( CHANGE )		����H���`�F�b�N���֐���
	for (i = 0; i < nSelectSize; i++) {
// Modify ... ( CHANGE )
		int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		if( !pDoc->CheckSameKoteiMove( m_aZahyoDrag[nIndex].pGraphBlock, pGraphBlockTo, pDoc->m_pDrop->bLastBlock ) ){
//		if( !pDoc->CheckSameKoteiMove( m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock, pGraphBlockTo, pDoc->m_pDrop->bLastBlock ) ){
// By Y.Itabashi (xxxxx) 2007.02.27
				goto ERR;	// Modify... ( CHANGE )
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.10

	// �ŐV�ړ��t���O�N���A
	g_pDataManager->ClearNewMoveFlag();

// Modify ... ( CHANGE )
	nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	bSetGraph = TRUE;
	nSetIndex = -1;
	while( bSetGraph ){

		bSetGraph = FALSE;

		int nIndexMin = m_aZahyoDrag.GetSize();
		for (i = 0; i < nSize; i++){

			if( pWnd->m_nGraphKishu != pDoc->m_aSelectYosoSagyogun[i].nKishuId &&
				pWnd->m_nGraphKishu != -1 )
				continue;

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
			if( nSetIndex < nIndex ){
				nIndexMin = ( nIndex < nIndexMin ) ? nIndex : nIndexMin;
				bSetGraph = TRUE;
			}
		}
		if( bSetGraph ){
			aGraphBlockFrom.Add(m_aZahyoDrag[nIndexMin].pGraphBlock);
			nSetIndex = nIndexMin;
		}
	}
//	int nSize = pDoc->m_aSelectedBox.GetSize();
//	for (i = 0; i < nSize; i++)
//		aGraphBlockFrom.Add(m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock);
// By Y.Itabashi (xxxxx) 2007.03.07

// Modify ... ( ADD )
	// �ړ��O�̈ʒu��ݒ�
	for( nKishu = 0; nKishu < g_pDataManager->GetKishuNum(); nKishu++ ){
		CString sCheckKoteiName;
		CString sCheckYosoSagyogunName;
		int nCheckYosoSagyogunId = -1;

		for( i = 0; i < aGraphBlockFrom.GetSize(); i++ ){
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

			if( pGraphBlockFrom->nKishuId != nKishu )
				continue;

			if( sCheckKoteiName.IsEmpty() ){
				sCheckKoteiName = pGraphBlockFrom->sKoteiName;
				sCheckYosoSagyogunName = pGraphBlockFrom->sYosoSagyogunName;
				nCheckYosoSagyogunId = pGraphBlockFrom->aYosoSagyogunId[0];
			}

			g_pDataManager->SetMoveBeforeSagyojun(nSublineIdFrom, nSublineIdTo,
				pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
				sCheckKoteiName, sCheckYosoSagyogunName, nCheckYosoSagyogunId,
				pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
				pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
		}
	}
/*	for( i = 0; i < aGraphBlockFrom.GetSize(); i++ ){
		SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];
		g_pDataManager->SetMoveBeforeSagyojun(nSublineIdFrom,
			pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
			pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
			pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
	}*/
// By Y.Itabashi (xxxxx) 2007.02.28

	pDoc->MoveItem(nSublineIdFrom, aGraphBlockFrom, nSublineIdTo, pDoc->m_pDrop->pGraphBlock, pDoc->m_pDrop->nSagyoshaId, pDoc->m_pDrop->bLastBlock);

	// �H���K���`�F�b�N
// Modify ... ( CHANGE )
	if (!g_pDataManager->CheckKojunKisei(nSublineIdFrom, nSublineIdTo, pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun)) {
//	if (!g_pDataManager->CheckKojunKisei(nSublineIdTo, pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun)) {
// By Y.Itabashi (xxxxx) 2007.02.07
		g_pDataManager->RestoreUndoData();
		goto ERR;	// Modify... ( CHANGE )
	}

// Modify ... ( ADD )
	g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.06

	// �ڕW�^�N�g����`�F�b�N
// Modify ... ( CHANGE )
	nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	for (i = 0; i < nSelectSize; i++) {
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
			nGraphKishu != -1 )
//		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu )
			continue;

		int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
		if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineIdTo, pDoc->m_pDrop->nSagyoshaId, cZahyoDrag.pGraphBlock->nKishuId, pWnd->m_nGraphKishu)) {
//		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
//		if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineIdTo, pDoc->m_pDrop->nSagyoshaId, cZahyoDrag.pGraphBlock->nKishuId)) {
// By Y.Itabashi (xxxxx) 2007.03.07
			if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
				g_pDataManager->RestoreUndoData();
				goto ERR;	// Modify... ( CHANGE )
			} else
				break;
		}
	}

// --Add 2007/03/02 aizawa �s��D-147
	// �T�u���C���Ԉړ��̏ꍇ�A���C���K���}�g���N�X�̃T�u���C�����X�V����
	if (nSublineIdFrom != nSublineIdTo)
		g_pDataManager->UpdateLineKiseiSubline(nSublineIdFrom, nSublineIdTo);
// --Add

	// �f�[�^�X�V�t���O�ݒ�
	g_pDataManager->SetUpdate();

	// �I�����Z�b�g
	ResetSelectedBox();

	// ��ʍX�V
	GetDocument()->UpdateGraph(FALSE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();

// Modify ... ( ADD )
	return;

ERR:
	pDoc->m_aSelectYosoSagyogun.RemoveAll();
	for( i = 0; i < m_aSelectYosoSagyogunBack.GetSize(); i++ )
		pDoc->m_aSelectYosoSagyogun.Add( m_aSelectYosoSagyogunBack[i] );

	pDoc->m_aRefYosoSagyogun.RemoveAll();
	for( i = 0; i < m_aRefYosoSagyogunBack.GetSize(); i++ )
		pDoc->m_aRefYosoSagyogun.Add( m_aRefYosoSagyogunBack[i] );
// By Y.Itabashi (xxxxx) 2007.03.07
}

// �L�b�g����������
void CManualEditView1::CancelKitting()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// --Add 2007/03/05 aizawa �s��D-199
	int nSublineId;
// --Add

	// �L�b�g�������t���O�N���A
	g_pDataManager->ClearKittingCancelFlag();

	// �L�b�g���f�[�^�擾
	g_pDataManager->GetKittingData();

	// �I������Ă��镔�i�̃L�b�g��������
// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	for (int i = 0; i < nSize; i++) {
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
			nGraphKishu != -1 )
			continue;

		int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SGraphBlock *pGraphBlock = m_aZahyoDrag[nIndex].pGraphBlock;
		g_pDataManager->CancelKitting(pGraphBlock->nSublineId, pGraphBlock->sKoteiName);
	}
/*	int nSize = pDoc->m_aSelectedBox.GetSize();
	for (int i = 0; i < nSize; i++) {
		SGraphBlock *pGraphBlock = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock;
		g_pDataManager->CancelKitting(pGraphBlock->nSublineId, pGraphBlock->sKoteiName);
	}*/
// By Y.Itabashi (xxxxx) 2007.03.06

// --Add 2007/03/05 aizawa �s��D-199
	// �^�N�g�I�[�o���`�F�b�N����
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
			// �L�b�g�������ɂ���Ƃ����������v�f��ƌQ���`�F�b�N
			if (cYosoSagyogun.bKittingCancel) {
				if (pWnd->m_nGraphKishu == -1) {
					// ���d���ϕ\��
					int nKishuSagyoNum = cYosoSagyogun.aKishuSagyo.GetSize();
					for (int nKishuId = 0; nKishuId < nKishuSagyoNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
						if (cKishuSagyo.nSagyoshaId != -1) {
							// �ڕW�^�N�g����`�F�b�N
							if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, cKishuSagyo.nSagyoshaId, nKishuId, pWnd->m_nGraphKishu)) {
								if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
									g_pDataManager->RestoreUndoData();
									return;
								} else
									goto tact_check_ok;
							}
						}
					}
				} else {
					// �@��ʕ\��
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[pWnd->m_nGraphKishu];
					if (cKishuSagyo.nSagyoshaId != -1) {
						// �ڕW�^�N�g����`�F�b�N
						if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, cKishuSagyo.nSagyoshaId, pWnd->m_nGraphKishu, pWnd->m_nGraphKishu)) {
							if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
								g_pDataManager->RestoreUndoData();
								return;
							} else
								goto tact_check_ok;
						}
					}
				}
			}
		}
	}
tact_check_ok:
// --Add

	// �L�b�g�����i���ۑ�
	g_pDataManager->SetBuhinKitting();

	// �L�b�g����Ǝ����Ґ�
	CIntArrayEx aSublineId;
// --Delete 2007/03/05 aizawa �s��D-199
//	int nSublineNum = g_pDataManager->GetSublineNum();
// --Delete
// --Change 2007/03/05 aizawa �s��D-199
//	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
// --Change
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		if (pSubline->bSagyoshaWaritsuke)
			aSublineId.Add(nSublineId);
	}
	g_pDataManager->JidoHensei1Kitting(aSublineId);

// Modify ... ( ADD )
	// �ړ��t���O�N���A�[
	g_pDataManager->ClearNewMoveFlag();
// By Y.Itabashi (xxxxx) 2007.03.05

	// �I�����Z�b�g
	ResetSelectedBox();

	// �f�[�^�X�V�t���O�ݒ�
	g_pDataManager->SetUpdate();

	// ��ʍX�V
	GetDocument()->UpdateGraph(TRUE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();
}

// �X�P�[���p�����[�^�ݒ�
void CManualEditView1::SetScaleParamaeter()
{
	m_nLeftMargin = 40;
	m_nTopMargin = 80;
	m_nRightMargin = 20;
	m_nBottomMargin = 40;
	m_nBarWidth = 80;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 30;
}

void CManualEditView1::DrawFocusRect(CRect rect)
{
	if (rect.TopLeft() != rect.BottomRight()) {
		CDC *pDC = GetDC();

		rect.NormalizeRect();
		pDC->DrawFocusRect(rect);

		ReleaseDC(pDC);
	}
}

void CManualEditView1::BlinkSelectedBox(BOOL bBlink)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27
	if (nSelectedBox == 0)
		return;

	int i, j;
	CArray <CRect, CRect&> aBlinkRect;
	CPoint point = GetScrollPosition();

// Modify ... ( ADD )
	if( nGraphKishu == -1 ){
		int nCheckIndex = -1;
		while( 1 ){
			int nMinIndex = -1;
			for (i = 0; i < nSelectedBox; i++) {

				int nIndex = pDoc->m_aSelectYosoSagyogun[i].nIndex;

				if( nCheckIndex < nIndex && nMinIndex == -1 )
					nMinIndex = nIndex;

				if( nCheckIndex >= nIndex )
					continue;

				nMinIndex = ( nMinIndex > nIndex ) ? nIndex : nMinIndex;
			}

			if( nCheckIndex == nMinIndex || nMinIndex == -1 )
				break;

			nCheckIndex = nMinIndex;
			CRect rectSelectedBox = m_aZahyoDrag[nMinIndex].rect - point;

			int nBlinkRect = aBlinkRect.GetSize();
			for (j = 0; j < nBlinkRect; j++) {
				CRect &rectBlink = aBlinkRect[j];

				if (rectSelectedBox.left == rectBlink.left) {
					if (rectSelectedBox.bottom == rectBlink.top) {
						rectBlink.top = rectSelectedBox.top;
						break;
					}

					if (rectSelectedBox.top == rectBlink.bottom) {
						 rectBlink.bottom = rectSelectedBox.bottom;
						break;
					}
				}
			}

			if (j == nBlinkRect)
				aBlinkRect.Add(rectSelectedBox);
		}
	}else{
// By Y.Itabashi (xxxxx) 2007.02.28
	for (i = 0; i < nSelectedBox; i++) {

// Modify ... ( CHANGE )
		if( nGraphKishu != pDoc->m_aSelectYosoSagyogun[i].nKishuId )
			continue;

		int nIndex = pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		CRect rectSelectedBox = m_aZahyoDrag[nIndex].rect - point;
//		CRect rectSelectedBox = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].rect - point;
// By Y.Itabashi (xxxxx) 2007.02.20

		int nBlinkRect = aBlinkRect.GetSize();
		for (j = 0; j < nBlinkRect; j++) {
			CRect &rectBlink = aBlinkRect[j];

			if (rectSelectedBox.left == rectBlink.left) {
				if (rectSelectedBox.bottom == rectBlink.top) {
					rectBlink.top = rectSelectedBox.top;
					break;
				}

				if (rectSelectedBox.top == rectBlink.bottom) {
					 rectBlink.bottom = rectSelectedBox.bottom;
					break;
				}
			}
		}

		if (j == nBlinkRect)
			aBlinkRect.Add(rectSelectedBox);
	}
// Modify ... ( ADD )
	}
// By Y.Itabashi (xxxxx) 2007.02.28

	if (bBlink) {
		CDC *pDC = GetDC();
		CPen *pPenOrg = pDC->SelectObject(&m_penSelected);
		CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

		for (j = 0; j < aBlinkRect.GetSize(); j++) {
			CRect rect(aBlinkRect[j]);
			rect.right++;
			rect.bottom++;
			pDC->Rectangle(rect);
		}

		pDC->SelectObject(pPenOrg);
		pDC->SelectObject(pBrushOrg);

		ReleaseDC(pDC);
	} else {
		for (j = 0; j < aBlinkRect.GetSize(); j++) {
			CRect rect(aBlinkRect[j]);
			rect.InflateRect(1, 1);
			InvalidateRect(rect, FALSE);
		}
	}
}

void CManualEditView1::EraseSelectedBox()
{
	CManualEditDocument *pDoc = GetDocument();
	pDoc->UpdateAllViews(NULL, UPDATE_SELECT_BLINK, (CObject *)FALSE);
}

void CManualEditView1::ResetSelectedBox()
{
	CManualEditDocument *pDoc = GetDocument();

	EraseSelectedBox();
// Modify ... ( CHANGE )
	pDoc->m_aSelectYosoSagyogun.RemoveAll();
	pDoc->InitReferenceYosoSagyogun();
	this->Invalidate();
//	pDoc->m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04
}

void CManualEditView1::SelectRect()
{
	CManualEditDocument *pDoc = GetDocument();
	CRect rectSelect = m_rectSelect;
	rectSelect.NormalizeRect();
	rectSelect += GetScrollPosition();
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

	// ���W�z��̒�����w�肳�ꂽ�ʒu���܂ގl�p�`������
	int nLeft = 0;
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		CRect &rect = m_aZahyoDrag[i].rect;
		if (rectSelect.PtInRect(rect.TopLeft()) && rectSelect.PtInRect(rect.BottomRight())) {
			if (nLeft == 0 || nLeft == rect.left) {
				AddSelect(i);

// Modify ... ( CHANGE )
//				if( pDoc->m_aSelectedBox.GetSize() == 0 )
				if( pDoc->m_aSelectYosoSagyogun.GetSize() == 0 )
					continue;

//				if (!m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock->bKitting)
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
				if (!m_aZahyoDrag[nIndex].pGraphBlock->bKitting)
					nLeft = rect.left;
// By Y.Itabashi (xxxxx) 2007.02.22
			}
		}
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// �H���^�v�f��ƌQ�I��ǉ�
void CManualEditView1::AddSelect(int nIndex)
{
	CManualEditDocument *pDoc = GetDocument();
	SZahyoDrag &cAddZahyoDrag = m_aZahyoDrag[nIndex];

// Modify ... ( ADD )
	// UP4�p���W���[��
//	if( cAddZahyoDrag.pGraphBlock->bKitting )
//		return;
// By Y.Itabashi (xxxxx) 2007.02.22

	if (cAddZahyoDrag.pGraphBlock->bKitting != pDoc->m_bSelectKitting) {
		ResetSelectedBox();
		pDoc->m_bSelectKitting = cAddZahyoDrag.pGraphBlock->bKitting;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	pDoc->AddSelectKotei( cAddZahyoDrag, nGraphKishu );
//	pDoc->m_aSelectedBox.Add(nIndex);
// By Y.Itabashi (xxxxx) 2007.02.27

	// ���ʍH���Ȃ瓯���O���[�v�ԍ��̍H���^�v�f��ƌQ���I������
	if (cAddZahyoDrag.pGraphBlock->bKyotsuKotei) {
		int nSize = m_aZahyoDrag.GetSize();
		for (int i = 0; i < nSize; i++) {
			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
			
// Modify ... ( CHANGE )
			if (cZahyoDrag.rect.left == cAddZahyoDrag.rect.left && cZahyoDrag.pGraphBlock->nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo){
				pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
//				pDoc->m_aSelectedBox.Add(i);
// Modify ... ( DELETE )
				// ���オ�����H���Ȃ炻����I������
/*				for (int j = i + 1; j < nSize; j++) {
					SZahyoDrag &cZahyoDrag = m_aZahyoDrag[j];
					if (cZahyoDrag.nSagyoshaId == cAddZahyoDrag.nSagyoshaId && cZahyoDrag.pGraphBlock->sJido == g_pConst->m_sJido){
						pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
					}
					else
						break;
				}*/
// By Y.Itabashi (xxxxx) 2007.03.07
			}
// By Y.Itabashi (xxxxx) 2007.02.27
		}
	}

// Modify ... ( DELETE )
	// ���オ�����H���Ȃ炻����I������
/*	int nSize = m_aZahyoDrag.GetSize();
	for (int i = nIndex + 1; i < nSize; i++) {
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
// Modify ... ( CHANGE )
		if (cZahyoDrag.nSagyoshaId == cAddZahyoDrag.nSagyoshaId && cZahyoDrag.pGraphBlock->sJido == g_pConst->m_sJido){
			pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
//			pDoc->m_aSelectedBox.Add(i);
		}
// By Y.Itabashi (xxxxx) 2007.02.27
		else
			break;
	}*/
// By Y.Itabashi (xxxxx) 2007.03.07

// Modify ... ( DELETE )
//	pDoc->m_aSelectedBox.Unique();
//	pDoc->m_aSelectedBox.Sort();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// �H���^�v�f��ƌQ�I������
void CManualEditView1::RemoveSelect(int nIndex)
{
	CManualEditDocument *pDoc = GetDocument();
	SZahyoDrag &cAddZahyoDrag = m_aZahyoDrag[nIndex];
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

	EraseSelectedBox();

	// ���ʍH���Ȃ瓯���O���[�v�ԍ��̍H���^�v�f��ƌQ����������
// Modify ... ( CHANGE )
	for (int i = 0; i < pDoc->m_aSelectYosoSagyogun.GetSize(); ) {
//	for (int i = 0; i < pDoc->m_aSelectedBox.GetSize(); ) {
		int nCheckIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nCheckIndex];
//		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
		
		if ( ( ( nGraphKishu == pDoc->m_aSelectYosoSagyogun[i].nKishuId || nGraphKishu == -1 ) &&
			   cZahyoDrag.nIndex == nIndex ) ||
			 ( pDoc->m_aSelectYosoSagyogun[i].bKyotsuKotei &&
			 pDoc->m_aSelectYosoSagyogun[i].nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo) ){
//		if (cZahyoDrag.nIndex == nIndex || (cZahyoDrag.pGraphBlock->bKyotsuKotei && cZahyoDrag.rect.left == cAddZahyoDrag.rect.left && cZahyoDrag.pGraphBlock->nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo)){
			pDoc->m_aSelectYosoSagyogun.RemoveAt(i);
			pDoc->CancelReferenceYosoSagyogun( cAddZahyoDrag.pGraphBlock->sKoteiName,
				cAddZahyoDrag.pGraphBlock->sYosoSagyogunName );
//			pDoc->m_aSelectedBox.RemoveAt(i);
		}else if( cAddZahyoDrag.pGraphBlock->bKitting &&
			pDoc->m_aSelectYosoSagyogun[i].sKoteiName == cAddZahyoDrag.pGraphBlock->sKoteiName &&
			pDoc->m_aSelectYosoSagyogun[i].sYosoSagyogunName == cAddZahyoDrag.pGraphBlock->sYosoSagyogunName ){
			pDoc->m_aSelectYosoSagyogun.RemoveAt(i);
		}else
			i++;
	}

	pDoc->UpdateAllViews(NULL, UPDATE_EDIT_VIEW);
// By Y.Itabashi (xxxxx) 2007.03.06
}

// �I������Ă���S�Ă̔����͂ޘg�����߂�
CRect CManualEditView1::GetSelectRect()
{
	CManualEditDocument *pDoc = GetDocument();
	CRect rect(0);

// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	rect.SetRectEmpty();
// By Y.Itabashi (xxxxx) 2007.02.21

// Modify ... ( CHANGE )
	int m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	for (int i = 0; i < nSize; i++){
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu &&
			m_nGraphKishu != -1 )
			continue;

		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		rect.UnionRect(rect, m_aZahyoDrag[nIndex].rect);
	}
//	int nSize = pDoc->m_aSelectedBox.GetSize();
//	for (int i = 0; i < nSize; i++)
//		rect.UnionRect(rect, m_aZahyoDrag[pDoc->m_aSelectedBox[i]].rect);
// By Y.Itabashi (xxxxx) 2007.02.27

	return rect;
}

// �w�肵���ʒu�̍H���^�v�f��ƌQ���I������Ă��邩�`�F�b�N
BOOL CManualEditView1::CheckSelect(SGraphBlock *pGraphBlock)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

// Modify ... ( CHANGE )
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSize = pDoc->m_aSelectedBox.GetSize();
	for (int i = 0; i < nSize; i++) {
		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		if (m_aZahyoDrag[nIndex].pGraphBlock == pGraphBlock)
//		if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock == pGraphBlock)
			return TRUE;
	}
// By Y.Itabashi (xxxxx) 2007.02.27

	return FALSE;
}

void CManualEditView1::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags)
{
	if (nChar == VK_ESCAPE) {
		ResetSelectedBox();
		SelectSagyosha(0);
	}

	CManualEditView::OnChar(nChar, nRepCnt, nFlags);
}

void CManualEditView1::OnLButtonDblClk(UINT nFlags, CPoint point)
{
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	pWnd->ChangeSplit(point);

//	CManualEditView::OnLButtonDblClk(nFlags, point);
}

void CManualEditView1::OnActivateView(BOOL bActivate, CView* pActivateView, CView* pDeactiveView)
{
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	pWnd->RecalcLayout();

	CManualEditView::OnActivateView(bActivate, pActivateView, pDeactiveView);
}

// ��Ǝ҂�I��
void CManualEditView1::SelectSagyosha(CPoint point)
{
	CManualEditDocument *pDoc = GetDocument();
	CPoint pointScroll = GetScrollPosition();
	point += pointScroll;
	SSelectSagyosha cSelectSagyosha = pDoc->m_cSelectSagyosha;

	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha &cZahyoSagyosha = m_aZahyoSagyosha[i];
		if (cZahyoSagyosha.nSublineId != -1 && cZahyoSagyosha.rect.PtInRect(point)) {
			if (cSelectSagyosha.bSelect == TRUE && cSelectSagyosha.nSubnlineId == cZahyoSagyosha.nSublineId && cSelectSagyosha.nSagyoshaId == cZahyoSagyosha.nSagyoshaId)
				pDoc->m_cSelectSagyosha.bSelect = FALSE;
			else {
				pDoc->m_cSelectSagyosha.bSelect = TRUE;
				pDoc->m_cSelectSagyosha.nSubnlineId = cZahyoSagyosha.nSublineId;
				pDoc->m_cSelectSagyosha.nSagyoshaId = cZahyoSagyosha.nSagyoshaId;
			}
			break;
		}
	}

	if (i == nSize)
		pDoc->m_cSelectSagyosha.bSelect = FALSE;

	if (pDoc->m_cSelectSagyosha.bSelect != cSelectSagyosha.bSelect || pDoc->m_cSelectSagyosha.nSubnlineId != cSelectSagyosha.nSubnlineId || pDoc->m_cSelectSagyosha.nSagyoshaId != cSelectSagyosha.nSagyoshaId)
		pDoc->UpdateAllViews(NULL, UPDATE_EDIT_VIEW);
}

// ��Ǝ҂̐V�K�}���i�O�ɑ}���j
void CManualEditView1::OnManualSagyoshaMae() 
{
	CManualEditDocument *pDoc = GetDocument();

	// ��Ǝ҂��I������Ă��邩�`�F�b�N
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E022001");
		return;
	}

	// ��Ǝґ}������
	g_pDataManager->InsertSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId);

	// �ĕ\��
	GetDocument()->UpdateGraph(FALSE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();
}

// ��Ǝ҂̐V�K�}���i���ɑ}���j
void CManualEditView1::OnManualSagyoshaUshiro() 
{
	CManualEditDocument *pDoc = GetDocument();

	// ��Ǝ҂��I������Ă��邩�`�F�b�N
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E022001");
		return;
	}

	// ��Ǝґ}������
	g_pDataManager->InsertSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId + 1);

	// �ĕ\��
	GetDocument()->UpdateGraph(FALSE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();
}

// ��Ǝ҂̍폜
void CManualEditView1::OnManualSagyoshaSakujo() 
{
	CManualEditDocument *pDoc = GetDocument();

	// ��Ǝ҂��I������Ă��邩�`�F�b�N
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E023001");
		return;
	}

	if (g_pDataManager->CheckSagyoshaSagyo(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId)) {
		g_pSystem->DispMessage("E023002");
		return;
	}

	// ��Ǝҍ폜����
	g_pDataManager->DeleteSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId);

	// �ĕ\��
	GetDocument()->UpdateGraph(FALSE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();
}

// �H���^�v�f��ƌQ�̃R�s�[
void CManualEditView1::OnManualCopy() 
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	if (pDoc->m_aSelectYosoSagyogun.GetSize() == 0) {
//	if (pDoc->m_aSelectedBox.GetSize() == 0) {
// By Y.Itabashi (xxxxx) 2007.02.27
		g_pSystem->DispMessage("E019001");
		return;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
	SGraphBlock *pGraphBlock = m_aZahyoDrag[nIndex].pGraphBlock;
//	SGraphBlock *pGraphBlock = m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock;
// By Y.Itabashi (xxxxx) 2007.02.27
	g_pDataManager->CopyClipboard(pGraphBlock->nSublineId, pGraphBlock->aYosoSagyogunId, pDoc->m_nGraphKind == GRAPH_KOTEI ? "1" : "2");
}

// �H���^�v�f��ƌQ�̓\��t��
void CManualEditView1::OnManualPaste() 
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSize = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27
	if (nSize == 0 || pDoc->m_bSelectKitting) {
		g_pSystem->DispMessage("E020001");
		return;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nIndex = 0;

	if( nGraphKishu == -1 )
		return;

	for( int nSelect = pDoc->m_aSelectYosoSagyogun.GetSize()-1; nSelect >= 0; nSelect-- ){

		if( nGraphKishu == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){
			if( nIndex < pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu ){
				nIndex = pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
				break;
			}
		}

	}
	SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//	SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[nSize - 1]];
// By Y.Itabashi (xxxxx) 2007.03.04
	SGraphBlock *pGraphBlock = cZahyoDrag.pGraphBlock;
	if (!g_pDataManager->PasteClipboard(pGraphBlock->nSublineId, pGraphBlock->aYosoSagyogunId[pGraphBlock->aYosoSagyogunId.GetSize() - 1], pGraphBlock->nKishuId, cZahyoDrag.nSagyoshaId)) {
		g_pSystem->DispMessage("E020002");
		return;
	}

	// �O���t���X�V
	GetDocument()->UpdateGraph(FALSE);

	// ���݂̃f�[�^��Undo�o�b�t�@�ɕۑ�
	g_pDataManager->SaveUndoBuf();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView1::OnManualDstr
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		DSTR�Z�o
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		DSTR�Z�o�_�C�A���O���J���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.02.09 Y.Itabashi �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::OnManualDstr() 
{
	CManualEditDocument *pDoc = GetDocument();
	CManualDstrDlg dlg(this);

// Modify ... ( CHANGE )
	dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//	dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26

	dlg.m_pDoc = pDoc;

	dlg.DoModal();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::AddZahyoDrag
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��ƍ��W�ǉ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CRect		&rect				[I] �l�p�`�̍��W
//		SGraphBlock	*pGraphBlock		[I] �O���t�u���b�N�f�[�^
//		CString		&sDispText			[I] �l�p���ɕ\������e�L�X�g
//		int			nKishuId			[I] �@��ID
//		int			nSagyoshaId			[I] ��Ǝ�ID
//		int			nKind				[I] �O���t��ށi�H�� or �v�f��ƌQ�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		m_aZahyoDrag�Ɏw�肳�ꂽ���W�f�[�^��ǉ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView1::AddZahyoDrag(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId)
{
	int nIndex;
	SZahyoDrag *pZahyoDrag = m_aZahyoDrag.AddElement(nIndex);

	pZahyoDrag->rect = rect;
	pZahyoDrag->pGraphBlock = pGraphBlock;
	pZahyoDrag->nSagyoshaId = nSagyoshaId;
	pZahyoDrag->nIndex = nIndex;
}

void CManualEditView1::AddZahyoDrop(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId, CPoint pointTriangle, BOOL bLastBlock)
{
// Modify ... ( ADD )
	if( !pGraphBlock )
		return;
// By Y.Itabashi (xxxxx) 2007.02.14

	if (!pGraphBlock->bKitting) {
		int nIndex;
		SZahyoDrop *pZahyoDrop = m_aZahyoDrop.AddElement(nIndex);

		pZahyoDrop->rect = rect;
		pZahyoDrop->pGraphBlock = pGraphBlock;
		pZahyoDrop->nSagyoshaId = nSagyoshaId;
		pZahyoDrop->nIndex = nIndex;
		pZahyoDrop->pointTriangle = pointTriangle;
		pZahyoDrop->bLastBlock = bLastBlock;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::CheckZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ���W�ʒu�̃|�C���^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint		point			[I] ���W
//		CRect		&rect			[O] �l�p�`�̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		SZahyoGraph*		���W�z��̃|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		m_aZahyoGraph�̒��Ɏw�肳�ꂽ���W���܂ގl�p�`�����邩�������A����΂��̃|�C���^��Ԃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
SZahyoDrag *CManualEditView1::CheckZahyoDrag(CPoint point, CRect &rect)
{
	CPoint pointScroll = GetScrollPosition();

	// ���W�z��̒�����w�肳�ꂽ�ʒu���܂ގl�p�`������
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrag *pZahyoDrag = &m_aZahyoDrag[i];
		rect = pZahyoDrag->rect - pointScroll;
		if (rect.PtInRect(point))
			return pZahyoDrag;
	}

	return NULL;
}

SZahyoDrop *CManualEditView1::CheckZahyoDrop(CPoint point, CRect &rect)
{
	CPoint pointScroll = GetScrollPosition();

	// ���W�z��̒�����w�肳�ꂽ�ʒu���܂ގl�p�`������
	int nSize = m_aZahyoDrop.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrop *pZahyoDrop = &m_aZahyoDrop[i];
		rect = pZahyoDrop->rect - pointScroll;
		if (rect.PtInRect(point))
			return pZahyoDrop;
	}

	return NULL;
}

// ���ʍH���̑I��ǉ��i��A�N�e�B�u��ʁj
void CManualEditView1::AddSelectKyotsuKotei()
{
	CManualEditDocument *pDoc = GetDocument();

	if( GetParentFrame() == pDoc->m_pSelectWnd )
		return;

	if( pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( !pDoc->m_aSelectYosoSagyogun[nSelect].bKyotsuKotei )
			continue;

		int nGroupNo = pDoc->m_aSelectYosoSagyogun[nSelect].nGroupNo;
		int nSubline = pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId;

// Modify ... ( CHANGE )
		for( int nSelectSubline = 0; nSelectSubline < pDoc->m_aGraphSubline.GetSize(); nSelectSubline++ ){
			if( pDoc->m_aGraphSubline[nSelectSubline].nSublineId == pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId )
				break;
		}
		if( nSelectSubline == pDoc->m_aGraphSubline.GetSize() )
			continue;
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSelectSubline];
//		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId];
// By Y.Itabashi (xxxxx) 2007.03.06
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[pDoc->m_aSelectYosoSagyogun[nSelect].nSagyoshaId];

		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
		for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

			if( aGraphBlock[nBlock].nGroupNo == nGroupNo ){

				for( int nZahyoDrag = 0; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

					if( m_aZahyoDrag[nZahyoDrag].pGraphBlock == &aGraphBlock[nBlock] ){
						pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );

						if( !bDispSagyosha ){
							DispCenterSagyosha( m_aZahyoDrag[nZahyoDrag].pGraphBlock->nSublineId, m_aZahyoDrag[nZahyoDrag].nSagyoshaId );
							bDispSagyosha = TRUE;
						}
					}
				}

			}
		}
	}
}

// �Q�ƍH���̑I��ǉ��i��A�N�e�B�u��ʁj
void CManualEditView1::AddReferenceKotei()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( GetParentFrame() == pDoc->m_pSelectWnd )
		return;

	if( pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId == pWnd->m_nGraphKishu )
			continue;

		CString sKoteiName = g_pDataManager->RemoveUnderbarDigit( pDoc->m_aSelectYosoSagyogun[nSelect].sKoteiName );
		CString sYosoSagyogunName = g_pDataManager->RemoveUnderbarDigit( pDoc->m_aSelectYosoSagyogun[nSelect].sYosoSagyogunName );

		for( int nBlock = 0; nBlock < m_aZahyoDrag.GetSize(); nBlock++ ){

			if( m_aZahyoDrag[nBlock].pGraphBlock->nKishuId == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId )
				continue;

			if( m_aZahyoDrag[nBlock].pGraphBlock->nSublineId != pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId )
				continue;

			CString sCheckKoteiName = g_pDataManager->RemoveUnderbarDigit( m_aZahyoDrag[nBlock].pGraphBlock->sKoteiName );
			CString sCheckYosoSagyogunName = g_pDataManager->RemoveUnderbarDigit( m_aZahyoDrag[nBlock].pGraphBlock->sYosoSagyogunName );

			if( sKoteiName == sCheckKoteiName && sYosoSagyogunName == sCheckYosoSagyogunName )
				pDoc->AddReferenceKotei( m_aZahyoDrag[nBlock], pWnd->m_nGraphKishu );
		}
	}

	Invalidate();
	if( pDoc->m_aRefYosoSagyogun.GetSize() > 0 ){
		DispCenterSagyosha( pDoc->m_aRefYosoSagyogun[0].nSublineId, pDoc->m_aRefYosoSagyogun[0].nSagyoshaId );
		((CManualEditChildWnd *)GetParentFrame())->m_nRefIndex = 0;
	}
}

// �L�b�g���H���̑I��ǉ��i��A�N�e�B�u��ʁj
void CManualEditView1::AddSelectKittingKotei()
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( GetParentFrame() == pDoc->m_pSelectWnd && pWnd->m_nGraphKishu != -1 )
//	if( GetParentFrame() == pDoc->m_pSelectWnd )
// By Y.Itabashi (xxxxx) 2007.03.06
		return;

	if( !pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( !pDoc->m_aSelectYosoSagyogun[nSelect].bKitting )
			continue;

		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[pDoc->m_aGraphSubline.GetSize()-1];
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[pDoc->m_aSelectYosoSagyogun[nSelect].nSagyoshaId];

		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
		for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

			if( aGraphBlock[nBlock].sKoteiName == pDoc->m_aSelectYosoSagyogun[nSelect].sKoteiName &&
				aGraphBlock[nBlock].sYosoSagyogunName == pDoc->m_aSelectYosoSagyogun[nSelect].sYosoSagyogunName ){

				for( int nZahyoDrag = 0; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

					if( m_aZahyoDrag[nZahyoDrag].pGraphBlock == &aGraphBlock[nBlock] ){
						pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );

						if( !bDispSagyosha ){
							DispCenterSagyosha( -1, m_aZahyoDrag[nZahyoDrag].nSagyoshaId );
							bDispSagyosha = TRUE;
						}
					}
				}

			}
		}
	}
}

// �����H���̑I��ǉ��i�S��ʁj
void CManualEditView1::AddSelectAutiKotei()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( pDoc->m_bSelectKitting )
		return;

	int nCheckKishu = -1;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( pWnd->m_nGraphKishu == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[nSelect].nIndex : pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
			for( int nZahyoDrag = nIndex+1; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

				if( m_aZahyoDrag[nZahyoDrag].pGraphBlock->sJido == g_pConst->m_sJido ){
					pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );
				}else
					return;
			}
		}else if( pWnd->m_nGraphKishu == -1 && nCheckKishu != pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[nSelect].nIndex : pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
			for( int nZahyoDrag = nIndex+1; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

				if( m_aZahyoDrag[nZahyoDrag].pGraphBlock->sJido == g_pConst->m_sJido ){
					pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );
				}else
					break;
			}

			nCheckKishu = pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId;
		}
	}
}
