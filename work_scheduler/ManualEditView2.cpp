//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView2.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView2 �N���X�̃C���v�������e�[�V����
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
#include "ManualEditView2.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView2, CManualEditView)

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::CManualEditView2
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
CManualEditView2::CManualEditView2()
{
	// �X�P�[���p�����[�^�ݒ�
	SetScaleParamaeter();
}


BEGIN_MESSAGE_MAP(CManualEditView2, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView2)
	ON_WM_SIZE()
	ON_WM_LBUTTONDOWN()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

void CManualEditView2::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	// �^�C�g���ݒ�
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[2].MenuNo, Menu_ManualWindowTitle[2].ItemNo );
	GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("��Ǝ҈ꗗ�\�����");
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r���[�`�揈��
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
void CManualEditView2::OnDraw(CDC* pDC)
{
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// �`�惂�[�h��TRANSPARENT�ɐݒ�
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// ��ƃO���t�`��
	DrawGraph(pDC, FALSE);

	// �����`��
	DrawHorizontalAxisLine(pDC);

	// �����e�L�X�g�`��
	DrawHorizontalAxisText(pDC);

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
//		CManualEditView2::OnUpdate
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
void CManualEditView2::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	switch (lHint) {
	case UPDATE_GRAPH_KIND:
	case UPDATE_CHANGE_SLIM:
	case UPDATE_SCROLL:
	case UPDATE_EDIT_VIEW:
	case UPDATE_SELECT_BLINK:
	case UPDATE_ERASE_MOVE_TRIANGLE:
		return;
	case UPDATE_CHANGE_SUBLINE:
		InitializeZahyoData();
		break;
	}

	// ���W���v�Z
	MakeGraph();

	// �r���[�̈�𖳌���
	Invalidate();

	// �T�u���C�����\��
	DispSublineInfo();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::OnSize
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
void CManualEditView2::OnSize(UINT nType, int cx, int cy)
{
	CManualEditView::OnSize(nType, cx, cy);

	if (m_bInitialize) {
		// �O���t�̍��W���Čv�Z
		MakeGraph();

		// �T�u���C�����\���ʒu�ݒ�
		SetSublineInfoPos();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::MakeGraph
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
void CManualEditView2::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();

	// �r���[�T�C�Y��ݒ�
	SetViewSize();

	// ���W�f�[�^���폜
	ClearZahyoData();

	// ��ƎҕʂɃO���t�̍��W���v�Z
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
		cZahyoSubline.nRight = (cGraphSubline.nSublineId != -1) ? nSublineBound : m_rectGraph.right;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::MakeGraphSubline
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
void CManualEditView2::MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26
	int i;
	double fBottom, fTop;
	CRect rect;
// Modify ... ( CHANGE )
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( cGraphSubline.nSublineId );
//	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11

	// ��ƎҕʂɃO���t�̍��W���v�Z
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		fBottom = m_rectGraph.bottom;
// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1) {
//		if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
			// ���d���ς̃O���t
			int nKishuNum = cGraphSagyosha.aKishu.GetSize();
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
				SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[nKishuId];

				fTop = fBottom - GetScaleHeight(cGraphKishu.fTime) * pKishu->nSeisanDaisu / nTotalSeisanDaisu;
				rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
				AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, nKishuId);
				fBottom = fTop;
			}
		} else {
			// �@��ʂ̃O���t
// Modify ... ( CHANGE )
			SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[pWnd->m_nGraphKishu];
//			SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[pDoc->m_nGraphKishu];
// By Y.Itabashi (xxxxx) 2007.02.26
			fTop = fBottom - GetScaleHeight(cGraphKishu.fTime);
			rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
// Modify ... ( CHANGE )
			AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, pWnd->m_nGraphKishu);
//			AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, pDoc->m_nGraphKishu);
// By Y.Itabashi (xxxxx) 2007.02.26
		}

		rect.bottom = m_rectGraph.bottom;
		AddZahyoSagyosha(rect, cGraphSubline.nSublineId, cGraphSagyosha.nSagyoshaId);

		nLeft += m_nBarWidth + m_nBarSpacing;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::SetViewSize
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
void CManualEditView2::SetViewSize()
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	// �r���[�̃T�C�Y���擾
	GetClientRect(m_rectView);

	// �O���t�̈���v�Z
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	// �o�[���d�Ȃ�Ȃ��悤�ɕ��𒲐�
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		nSagyoshaNum += nKittingSagyoshaNum + 1;
	m_nBarWidth = nSagyoshaNum ? (m_rectGraph.Width() - m_nLeftSpacing) / nSagyoshaNum / 2 : 0;
	if (m_nBarWidth > 10)
		m_nBarWidth = 10;
	m_nBarSpacing = nSagyoshaNum ? (m_rectGraph.Width() - m_nLeftSpacing - m_nBarWidth * nSagyoshaNum) / nSagyoshaNum : 0;

	// �ڕW�^�N�g�̍���
// Modify ... ( CHANGE )
	SetScaleFactor(TRUE, pWnd->m_nGraphKishu);
//	SetScaleFactor(TRUE, pDoc->m_nGraphKishu);
// By Y.Itabashi (xxxxx) 2007.02.26

	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::DrawHorizontalAxisText
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
void CManualEditView2::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	BOOL bAlternate = FALSE;
	int nSublineIndex;

	// ��Ǝ҂̕\�����d�Ȃ��Ă��܂����`�F�b�N
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		nSagyoshaNum += cGraphSubline.aGraphSagyosha.GetSize();
	}
	nSagyoshaNum += GetKittingSagyoshaNum();
	CSize sizeText = pDC->GetTextExtent("D99");
	if ((sizeText.cx + 2) * nSagyoshaNum > m_rectGraph.Width() - m_nLeftSpacing)
		bAlternate = TRUE;

	int nCounter = 0;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	for (nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawHorizontalAxisTextSub(pDC, cGraphSubline, nLeft, bAlternate, nCounter);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::DrawHorizontalAxisText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����e�L�X�g�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC					*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] �O���t��ƎҔz��
//		int					&nLeft				[I/O] X���W
//		BOOL				bAlternate			[I] TRUE�F��Ǝ҂��Q�i�ɕ\������
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
void CManualEditView2::DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft, BOOL bAlternate, int &nCounter)
{
	CSize sizeText = pDC->GetTextExtent("D99");

	// ��Ǝ҂ɂ�郋�[�v
	CRect rectText;
	int nBaseHeight = m_rectGraph.bottom;
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];

		// ��Ǝ҂̕\�����d�Ȃ��Ă��܂��ꍇ�͂Q�i�ɕ\��
		if (!bAlternate || nCounter++ % 2 == 0)
			rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
		else
			rectText.SetRect(nLeft, nBaseHeight + 8 + sizeText.cy, nLeft + m_nBarWidth, m_rectView.bottom);

		// ��ƎҖ���\��
		if (pDC->RectVisible(rectText))
			pDC->DrawText(cGraphSagyosha.sSagyoshaDispName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

		nLeft += m_nBarWidth;
		nLeft += m_nBarSpacing;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView2::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ���z�L�[�̏��
//		CPoint		point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���b�N���ꂽ��Ǝ҂𑼂̃r���[�̒����ɕ\��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView2::OnLButtonDown(UINT nFlags, CPoint point)
{
	SZahyoSagyosha *pClickZahyoSagyosha = CheckZahyoSagyosha(point);
	if (pClickZahyoSagyosha != NULL)
		GetDocument()->UpdateAllViews(this, UPDATE_SCROLL, (CObject *)pClickZahyoSagyosha);

	CManualEditView::OnLButtonDown(nFlags, point);
}

// �X�P�[���p�����[�^�ݒ�
void CManualEditView2::SetScaleParamaeter()
{
	m_nLeftMargin = 0;
	m_nTopMargin = 60;
	m_nRightMargin = 0;
	m_nBottomMargin = 35;
	m_nBarWidth = 10;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 10;
}
