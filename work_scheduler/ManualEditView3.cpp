//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView3.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView3 �N���X�̃C���v�������e�[�V����
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
#include "ManualEditView3.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView3, CManualEditView)

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::CManualEditView3
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
CManualEditView3::CManualEditView3()
{
	// �X�P�[���p�����[�^�ݒ�
	SetScaleParamaeter();
}


BEGIN_MESSAGE_MAP(CManualEditView3, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView3)
	ON_WM_SIZE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

void CManualEditView3::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	// �^�C�g���ݒ�
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[3].MenuNo, Menu_ManualWindowTitle[3].ItemNo );
	GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("�S���\�����");
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::OnDraw
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
void CManualEditView3::OnDraw(CDC* pDC)
{
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// �`�惂�[�h��TRANSPARENT�ɐݒ�
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// ��ƃO���t�`��
	DrawGraph(pDC, TRUE);

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

	// �}��`��
	DrawLegends(pDC);

	// �`�惂�[�h��߂�
	pDC->SetBkMode(nBkMode);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::OnUpdate
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
void CManualEditView3::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	switch (lHint) {
	case UPDATE_GRAPH_KISHU:
	case UPDATE_EDIT_VIEW:
	case UPDATE_SELECT_BLINK:
	case UPDATE_ERASE_MOVE_TRIANGLE:
		return;
	case UPDATE_SCROLL:
		{
			SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
			DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
		}
		return;
	case UPDATE_CHANGE_SLIM:
		// �X�P�[���p�����[�^�ݒ�
		SetScaleParamaeter();
		SetSlimMode();
		break;
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
//		CManualEditView3::OnSize
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
void CManualEditView3::OnSize(UINT nType, int cx, int cy)
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
//		CManualEditView3::MakeGraph
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
void CManualEditView3::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();
	int nKittingSublineId = -1;
	int i, j;

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
		int nSublineId = cGraphSubline.nSublineId;

		if (nSublineId == -1) {
			nLeft += m_nBarSpacing;
			nSublineBound += m_nBarSpacing / 2;
			m_aZahyoSubline[nSublineIndex - 1].nRight = nSublineBound;
		}

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
			CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;

			int nLeftSagyosha = nLeft;

			// ���d���σO���t�̍��W
			MakeZahyoSagyo(nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, -1, nLeft, m_rectGraph.bottom, nKittingSublineId);
			nLeft += m_nBarWidth;

			// �@��ʂɃO���t�̍��W���v�Z
			if (nSublineId != -1) {
				int nKishuNum = cGraphSagyosha.aKishu.GetSize();
				for (j = 0; j < nKishuNum; j++) {
					MakeZahyoSagyo(nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, j, nLeft, m_rectGraph.bottom, nKittingSublineId);
					nLeft += m_nBarWidth;
				}
			}

			AddZahyoSagyosha(CRect(nLeftSagyosha, 0, nLeft, 0), nSublineId, cGraphSagyosha.nSagyoshaId);

			nLeft += m_nBarSpacing;
		}

		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];
		cZahyoSubline.nSublineId = nSublineId;
		cZahyoSubline.nLeft = nSublineBound;
		nSublineBound = nLeft - m_nBarSpacing / 2;
		cZahyoSubline.nRight = nSublineBound;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::MakeZahyoSagyo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t���W�f�[�^�쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphBlockArray	&aGraphBlock		[I] �O���t�u���b�N�z��
//		int					nSagyoshaId		[I] ��Ǝ�ID
//		int					nKishuId		[I] �@��ID
//		int					nLeft			[I] X���W
//		int					nTop			[I] Y���W
//		double				fRatio			[I] �S�@��̐��Y�䐔�ɑ΂��邱�̋@��̐��Y�䐔�̊���
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
int CManualEditView3::MakeZahyoSagyo(int nSublineId, CGraphBlockArray &aGraphBlock, int nSagyoshaId, int nKishuId, int nLeft, int nTop, int &nKittingSublineId)
{
	CManualEditDocument *pDoc = GetDocument();
	int i;
	double fBottom, fTop;
	CRect rect;
	double fRatio;
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );

	int nSize = aGraphBlock.GetSize();

	// �O���t�̃u���b�N�ɂ�郋�[�v
	fBottom = nTop;
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

		if (nKishuId == -1 || cGraphBlock.nKishuId == nKishuId) {
			// �L�b�g���̃T�u���C����؂�
			if (cGraphBlock.bKitting) {
				if (nKittingSublineId != cGraphBlock.nSublineId) {
					if (nKittingSublineId != -1) {
						SZahyoKittingSubline *pZahyoKittingSubline = m_aZahyoKittingSubline.AddElement();
						pZahyoKittingSubline->pointLine.x = nLeft;
						pZahyoKittingSubline->pointLine.y = (int)fBottom;
						pZahyoKittingSubline->sSiblineName1 = g_pDataManager->GetSublineData(cGraphBlock.nSublineId)->sSublineName;

						if (i == 0) {
							pZahyoKittingSubline = m_aZahyoKittingSubline.AddElement();
							SZahyoGraph &cZahyoGraph = m_aZahyoGraph[m_aZahyoGraph.GetSize() - 1];
							pZahyoKittingSubline->pointLine.x = cZahyoGraph.rect.left;
							pZahyoKittingSubline->pointLine.y = cZahyoGraph.rect.top;
						}

						pZahyoKittingSubline->sSiblineName2 = g_pDataManager->GetSublineData(nKittingSublineId)->sSublineName;
					}
					nKittingSublineId = cGraphBlock.nSublineId;
				}
			}

			// �O���t�̎l�p���e�̈�̍��W���v�Z
			if (cGraphBlock.fTime != 0) {
				if (nKishuId == -1) {
					SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
					fRatio = (double)pKishu->nSeisanDaisu / nTotalSeisanDaisu;
				} else
					fRatio = 1;

				fTop = fBottom - cGraphBlock.fTime * m_fScaleFactor * fRatio;
				rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
				fBottom = fTop;
				AddZahyoGraph(rect, pDoc->m_nGraphKind == GRAPH_KOTEI ? cGraphBlock.sKoteiName : cGraphBlock.sYosoSagyogunName, COORDINATE_KIND_SAGYO, cGraphBlock.nKishuId);
			}
		}
	}

	return (int)fBottom;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::SetViewSize
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
void CManualEditView3::SetViewSize()
{
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nKishuNum = g_pDataManager->GetKishuNum();

	// �r���[�̃T�C�Y���v�Z
	GetClientRect(m_rectView);
	if (GetTotalSize().cx < m_rectView.Width())
		m_rectView.bottom -= ::GetSystemMetrics(SM_CYHSCROLL);

	m_rectView.right = m_nLeftMargin + m_nLeftSpacing + (nKishuNum + 1) * nSagyoshaNum * m_nBarWidth + nSagyoshaNum * m_nBarSpacing + m_nRightMargin;
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		m_rectView.right += (nKittingSagyoshaNum + 1) * (m_nBarWidth + m_nBarSpacing);

	// �O���t�̈���v�Z
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	// �ڕW�^�N�g�̍���
	SetScaleFactor(TRUE, -2);

	// �X�N���[���G���A�̃T�C�Y��ݒ�
	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));

	// �����X�N���[���o�[���\��
	ShowScrollBar(SB_VERT, FALSE);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::DrawHorizontalAxisText
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
//		�����̃e�L�X�g�i�@�햼�A��ƎҖ��j��`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView3::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	int i, j;

	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// ��Ǝ҂ɂ�郋�[�v
	CRect rectText;
	int nBaseHeight = m_rectGraph.bottom;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
			int nKishuNum = cGraphSagyosha.aKishu.GetSize();
			int nLeft2 = nLeft;

			if (cGraphSubline.nSublineId != -1) {
				// �u�S�v��\��
				rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
// Modify ... ( CHANGE )
				if (pDC->RectVisible(rectText)){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[6].MenuNo, Menu_ManualWindowText[6].ItemNo );
					pDC->DrawText(str, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);
				}
//				if (pDC->RectVisible(rectText))
//					pDC->DrawText("�S", rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);
// By Y.Itabashi (xxxxx) 2007.01.26
				nLeft += m_nBarWidth;

				// �@��ɂ�郋�[�v
				for (j = 0; j < nKishuNum; j++) {
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];

					// �@�햼��\��
					rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
					if (pDC->RectVisible(rectText))
						pDC->DrawText(cGraphKishu.sKishuName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

					nLeft += m_nBarWidth;
				}
			} else
				nLeft += m_nBarWidth;

			// ��ƎҖ���\��
			rectText.SetRect(nLeft2, nBaseHeight + (cGraphSubline.nSublineId != -1 ? 28 : 8), nLeft, m_rectView.bottom);
			if (pDC->RectVisible(rectText))
				pDC->DrawText(cGraphSagyosha.sSagyoshaName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

			nLeft += m_nBarSpacing;
		}
	}

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView3::DrawTotalTime
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
void CManualEditView3::DrawTotalTime(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	CFont *pFontOrg = pDC->SelectObject(&m_fontTime);

	int i, j;
	CString sTime;
	CRect rectText;

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];

			DrawTime(pDC, cGraphSubline, nLeft, cGraphSagyosha.fTime);

			nLeft += m_nBarWidth;

			if (cGraphSubline.nSublineId != -1) {
				// �@��ʂɃO���t�̍��W���v�Z
				int nKishuNum = cGraphSagyosha.aKishu.GetSize();
				for (j = 0; j < nKishuNum; j++) {
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];

					DrawTime(pDC, cGraphSubline, nLeft, cGraphKishu.fTime);

					nLeft += m_nBarWidth;
				}
			}

			nLeft += m_nBarSpacing;
		}
	}

	pDC->SelectObject(pFontOrg);
}

// �X�P�[���p�����[�^�ݒ�
void CManualEditView3::SetScaleParamaeter()
{
	m_nLeftMargin = 40;
	m_nTopMargin = 70;
	m_nRightMargin = 100;
	m_nBottomMargin = 50;
	m_nBarWidth = 80;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 30;
}
