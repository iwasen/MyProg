//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "ManualEditWnd002.h"
#include "ManualEditView.h"
#include "DataManager.h"
#include "ManualEditFrameWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView, CScrollView)

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::CManualEditView
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
//		�N���X�ϐ��̏������A�`��I�u�W�F�N�g�̍쐬�A�@�킲�Ƃ̃O���t�̐F�̍쐬���s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CManualEditView::CManualEditView()
{
	// �N���X�ϐ��̏�����
	m_bInitialize = FALSE;

	// �`��I�u�W�F�N�g���쐬
	m_penGraph.CreatePen(PS_SOLID, 2, RGB(0x00, 0x00, 0x00));
	m_penAxis.CreatePen(PS_SOLID, 2, RGB(0, 0, 0));
	m_penTact.CreatePen(PS_SOLID, 2, RGB(0, 0, 0xff));
	m_penTactLimit.CreatePen(PS_DASH, 1, RGB(0xff, 0x77, 0x33));
	m_penSublineBar.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbSublineBarColor);
	m_penKyotsuKotei.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbCommonProcessColor);
	m_penKittingLine.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbKittinglineBarColor);
	m_penKittingSubline.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbKitSublineBarColor);
	m_fontGraph.CreatePointFont(80, g_pConst->m_sDefaultFontName);
	m_fontH.CreateFont(14, 0, 0, 0, FW_BOLD, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_fontV.CreateFont(14, 0, 900, 0, FW_BOLD, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_fontTime.CreateFont(14, 0, 0, 0, FW_MEDIUM, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_brushJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbAutoProcessColor);
	m_brushHanJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbSemiAutoProcessColor);
	m_brushKyotsuJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbCommonAutoProcessColor);
	m_brushKyotsuHanJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbCommonSemiAutoProcessColor);

	// �@�킲�Ƃ̃u���V���쐬
	for (int i = 0; i < MAX_KISHU; i++)
		m_brushGraph[i].CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbProcessGraphColor[i]);
}


BEGIN_MESSAGE_MAP(CManualEditView, CScrollView)
	//{{AFX_MSG_MAP(CManualEditView)
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	//}}AFX_MSG_MAP
	ON_NOTIFY(TTN_NEEDTEXT, 0, OnGetToolTipText)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::OnInitialUpdate
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
//		�c�[���`�b�v�\���L�������s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::OnInitialUpdate()
{
	ModifyStyle(0, WS_CLIPSIBLINGS | WS_CLIPCHILDREN);

	CDC *pDC = GetDC();
	m_dcCompatible.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	InitializeZahyoData();

	CScrollView::OnInitialUpdate();

	// �c�[���`�b�v�\���L����
	EnableToolTips(TRUE);

	// �c�[���`�b�v����"&"��\���ł���悤�ɂ���
	_AFX_THREAD_STATE* pThreadState = AfxGetThreadState();
	CToolTipCtrl* pToolTip = pThreadState->m_pToolTip;
	if (pThreadState->m_pToolTip == NULL) {
		pThreadState->m_pToolTip = new CToolTipCtrl;
		pThreadState->m_pToolTip->Create(GetParentOwner(), TTS_ALWAYSTIP | TTS_NOPREFIX);
	}

	m_bInitialize = TRUE;
}

void CManualEditView::InitializeZahyoData()
{
	CManualEditDocument *pDoc = GetDocument();

	m_aZahyoSubline.RemoveAll();

	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	m_aZahyoSubline.SetSize(nGraphSublineNum);
	for (int i = 0; i < nGraphSublineNum; i++)
		m_aZahyoSubline[i].cSublineInfo.Create(this);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC			[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�`��͔h���N���X�ōs�����߂����ł͉������Ȃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::OnDraw(CDC* pDC)
{
}

void CManualEditView::ClearZahyoData()
{
	m_aZahyoGraph.RemoveAll();
	m_aZahyoKittingSubline.RemoveAll();
	m_aZahyoKyotsuKoteiFrame.RemoveAll();
	m_aZahyoSagyosha.RemoveAll();
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::AddZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t���W�ǉ�����
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
//		m_aZahyoGraph�Ɏw�肳�ꂽ���W�f�[�^��ǉ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::AddZahyoGraph(const CRect &rect, CString &sText, int nKind, int nKishuId)
{
	if (!rect.IsRectEmpty()) {
		SZahyoGraph *pZahyoGraph = m_aZahyoGraph.AddElement();
		pZahyoGraph->rect = rect;
		pZahyoGraph->sText = sText;
		pZahyoGraph->nKind = nKind;
		pZahyoGraph->nKishuId = nKishuId;
	}
}

// ���ʍH���g���W�ǉ�����
int CManualEditView::AddKyotsuKoteiFrame(const CRect &rect)
{
	int nIndex;
	SZahyoKyotsuKoteiFrame *pZahyoKyotsuKoteiFrame = m_aZahyoKyotsuKoteiFrame.AddElement(nIndex);
	pZahyoKyotsuKoteiFrame->rect = rect;
	return nIndex;
}

void CManualEditView::AddZahyoSagyosha(const CRect &rect, int nSublineId, int nSagyoshaId)
{
	SZahyoSagyosha *pZahyoSagyosha = m_aZahyoSagyosha.AddElement();
	pZahyoSagyosha->rect = rect;
	pZahyoSagyosha->nSublineId = nSublineId;
	pZahyoSagyosha->nSagyoshaId = nSagyoshaId;
}

SZahyoSagyosha *CManualEditView::CheckZahyoSagyosha(CPoint point)
{
	point += GetScrollPosition();

	// ���W�z��̒�����w�肳�ꂽ�ʒu���܂ގl�p�`������
	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha *pZahyoSagyosha = &m_aZahyoSagyosha[i];
		if (pZahyoSagyosha->rect.PtInRect(point))
			return pZahyoSagyosha;
	}

	return NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//		BOOL	bDrawText			[I] �l�p���Ƀe�L�X�g��TRUE�F�\������AFALSE�F�\�����Ȃ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		m_aZahyoGraph�ɐݒ肳�ꂽ���W�f�[�^�ɏ]���Ďl�p�`�y�т��̒��̃e�L�X�g��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawGraph(CDC *pDC, BOOL bDrawText)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penGraph);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);
	CFont *pFontOrg = pDC->SelectObject(&m_fontGraph);

	// ���W�z��̑S�Ă�\��
	int nSize = m_aZahyoGraph.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoGraph &cZahyoGraph = m_aZahyoGraph[i];
		CRect &rect = cZahyoGraph.rect;

		// �l�p�`���\���̈�����`�F�b�N
		CRect rect2(rect.left - 1, rect.top - 1, rect.right + 1, rect.bottom + 1);
		if (pDC->RectVisible(rect2)) {
			switch (cZahyoGraph.nKind) {
			case COORDINATE_KIND_SAGYO:
				if (cZahyoGraph.rect.Height() != 0) {
					// �O���t�̐F��I��
					pDC->SelectObject(&m_brushGraph[cZahyoGraph.nKishuId]);

					// �e�H���E�v�f��ƌQ�̎l�p�`��\��
					CRect rect2(rect.left, rect.top, rect.right + 1, rect.bottom + 1);
					pDC->Rectangle(rect2);

					// �l�p�`�̒��ɍH�����E�v�f��ƌQ����\��
					if (bDrawText)
						DrawTextCenter(pDC, cZahyoGraph.sText, rect2);
				}
				break;
			case COORDINATE_KIND_JIDO:
				// �����H���̐��O�p��\��
				pDC->SelectObject(&m_brushJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_HANJIDO:
				// �������H���̉��F���O�p��\��
				pDC->SelectObject(&m_brushHanJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_JIDO_KYOTSU:
				// ���ʎ����H���̐��O�p��\��
				pDC->SelectObject(&m_brushKyotsuJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_HANJIDO_KYOTSU:
				// ���ʔ������H���̉��F���O�p��\��
				pDC->SelectObject(&m_brushKyotsuHanJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_KYOTSU_KOTEI:
				CBitmap *pBitmapOrg = m_dcCompatible.SelectObject(&m_bmpKyotsuKotei);
				pDC->BitBlt(rect.left, rect.top, rect.Width(), rect.Height(), &m_dcCompatible, 0, 0, SRCCOPY);
				m_dcCompatible.SelectObject(pBitmapOrg);
				break;
			}
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
	pDC->SelectObject(pFontOrg);
}

// ���ʍH���g�`��
void CManualEditView::DrawKyotsuKoteiFrame(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penKyotsuKotei);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

	int nSize = m_aZahyoKyotsuKoteiFrame.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoKyotsuKoteiFrame &cKyotsuKotei = m_aZahyoKyotsuKoteiFrame[i];

		CRect rect2(cKyotsuKotei.rect.left - 1, cKyotsuKotei.rect.top - 1, cKyotsuKotei.rect.right + 1, cKyotsuKotei.rect.bottom + 1);
		if (pDC->RectVisible(rect2)) {
			CRect rect2(cKyotsuKotei.rect.left, cKyotsuKotei.rect.top, cKyotsuKotei.rect.right + 1, cKyotsuKotei.rect.bottom + 1);
			pDC->Rectangle(rect2);
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawLeftTriangle
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����������O�p�`��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CRect	&rect				[I] �O�p�`��\��������W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ���W�ɍ����������O�p�`��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawLeftTriangle(CDC *pDC, const CRect &rect)
{
	CPoint point[3];

	point[0].x = rect.left;
	point[0].y = rect.top + rect.Height() / 2;
	point[1].x = rect.right;
	point[1].y = rect.top;
	point[2].x = rect.right;
	point[2].y = rect.bottom;
	CPen *pPen = (CPen *)pDC->SelectStockObject(BLACK_PEN);
	pDC->Polygon(point, 3);
	pDC->SelectObject(pPen);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawRightTriangle
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E���������O�p�`��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CRect	&rect				[I] �O�p�`��\��������W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ���W�ɉE���������O�p�`��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawRightTriangle(CDC *pDC, const CRect &rect)
{
	CPoint point[3];

	point[0].x = rect.right;
	point[0].y = rect.top + rect.Height() / 2;
	point[1].x = rect.left;
	point[1].y = rect.top;
	point[2].x = rect.left;
	point[2].y = rect.bottom;
	CPen *pPen = (CPen *)pDC->SelectStockObject(BLACK_PEN);
	pDC->Polygon(point, 3);
	pDC->SelectObject(pPen);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawTextCenter
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�e�L�X�g���w�肳�ꂽ�g�̒����ɕ`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//		CString	&sText				[I] �\������e�L�X�g
//		CRect	&rect				[I] �g�̎l�p�`�̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�l�p�`�̍��W�̒��Ƀe�L�X�g��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawTextCenter(CDC *pDC, const CString &sText, CRect rect)
{
	// �g���̃}�[�W�����m��
	rect.left += 2;
	rect.top += 1;
	rect.right -= 2;
	rect.bottom -= 1;

	CRect rectText(rect);
	CRect rectText2(rectText);

	if (rect.Height() > 20) {
		// �`���̃T�C�Y���擾
		pDC->DrawText(sText, rectText, DT_CENTER | DT_WORDBREAK | DT_CALCRECT | DT_EDITCONTROL | DT_NOPREFIX);

		// �㉺�̃Z���^�����O�ʒu�����߂�
		CSize sizeOffset((rect.Width() - rectText.Width()) / 2, (rect.Height() - rectText.Height()) / 2);

		if (sizeOffset.cy > 0) {
			// �g���ɓ���ꍇ�͂��̂܂ܕ\��
			rectText.OffsetRect(sizeOffset);
			pDC->DrawText(sText, rectText, DT_CENTER | DT_WORDBREAK | DT_EDITCONTROL | DT_NOPREFIX);
			return;
		}
	}

	// �g���ɓ���Ȃ��ꍇ�͂P�s�ŏȗ��L����t���ĕ\��
	CString sText2(sText);
	sText2.Replace('\n', ' ');
	pDC->DrawText(sText2, rectText2, DT_CENTER | DT_VCENTER | DT_SINGLELINE | DT_END_ELLIPSIS | DT_NOPREFIX);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawVerticalAxisLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̏c����`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̏c����`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawVerticalAxisLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penAxis);

	pDC->MoveTo(m_rectGraph.left, m_rectGraph.top);
	pDC->LineTo(m_rectGraph.left, m_rectGraph.bottom);

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawHorizontalAxisLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̉�����`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̉�����`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawHorizontalAxisLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penAxis);

	pDC->MoveTo(m_rectGraph.left, m_rectGraph.bottom);
	pDC->LineTo(m_rectGraph.right, m_rectGraph.bottom);

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawTactLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڕW�^�N�g�Ə���A�����̐���`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC				[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�ڕW�^�N�g�������A����A�����̐���j���ŕ`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawTactLine(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	CPen *pPenOrg = pDC->SelectObject(&m_penTact);

	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		int nLeft = cZahyoSubline.nLeft;
		int nRight = cZahyoSubline.nRight;
		if (nSublineIndex == nGraphSublineNum - 1)
			nRight = m_rectGraph.right;

		// �ڕW�^�N�g�̐�
		int nTactPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fMokugyoTact);
		pDC->SelectObject(&m_penTact);
		pDC->MoveTo(nLeft, nTactPos);
		pDC->LineTo(nRight, nTactPos);

		// ����̐�
		int nJogenPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fJogen);
		pDC->SelectObject(&m_penTactLimit);
		pDC->MoveTo(nLeft, nJogenPos);
		pDC->LineTo(nRight, nJogenPos);
		pDC->MoveTo(nLeft, nJogenPos - 1);
		pDC->LineTo(nRight, nJogenPos - 1);

		// �����̐�
		int nKagenPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fKagen);
		pDC->MoveTo(nLeft, nKagenPos);
		pDC->LineTo(nRight, nKagenPos);
		pDC->MoveTo(nLeft, nKagenPos - 1);
		pDC->LineTo(nRight, nKagenPos - 1);
	}

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawVerticalAxisText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̕�����`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*pDC				[I] �f�o�C�X�R���e�L�X�g
//		LPCTSTR		pText				[I] �\�����镶��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�c���̍����̕�����`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawVerticalAxisText(CDC *pDC, LPCTSTR pText)
{
	CFont *pFontOrg = pDC->SelectObject(&m_fontV);

	// �\���T�C�Y���擾
	CSize sizeText = pDC->GetTextExtent(pText);

	// �c���̒����ɕ\��
	pDC->TextOut(m_rectGraph.left - sizeText.cy - 8, m_rectGraph.top + (m_rectGraph.Height() + sizeText.cx) / 2, pText);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawLegends
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}���`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*pDC				[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t�̖}���`�悷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawLegends(CDC *pDC)
{
	CRect rect;
	int nLeft = m_rectGraph.right + 10;
	int nTop = m_rectGraph.top;
	CPen *pPenOrg = pDC->SelectObject(&m_penGraph);

	// �@��Ń��[�v
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

		rect.SetRect(nLeft, nTop, nLeft + 70, nTop + 20);
		CBrush *pBrushOrg = pDC->SelectObject(&m_brushGraph[nKishuId]);

		// �l�p�̒��ɋ@�햼��\��
		if (pDC->RectVisible(rect)) {
			pDC->Rectangle(rect);
			pDC->DrawText(pKishu->sKishuName, rect, DT_CENTER | DT_VCENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);
		}
		pDC->SelectObject(&pBrushOrg);

		nTop += 30;
	}

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::DrawTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���v���ԕ`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*pDC			[I] �f�o�C�X�R���e�L�X�g
//		int			nLeft			[I] �\���ʒu
//		double		fTime			[I] ���v����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���v���ԂƏ[���x��`�悷��B�ڕW�^�N�g�̏���𒴂��Ă���ꍇ�͐Ԃŕ\���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::DrawTime(CDC *pDC, SGraphSubline &cGraphSubline, int nLeft, double fTime)
{
	CString sTime;

	int nTextHeight = m_rectGraph.bottom - GetScaleHeight(max(cGraphSubline.fJogen, fTime));
	CRect rectText(nLeft, 0, nLeft + m_nBarWidth, nTextHeight - 8);
	if (pDC->RectVisible(rectText)) {
		// ����𒴂��Ă���ꍇ�͐Ԃŕ\��
		int color = pDC->SetTextColor(fTime > cGraphSubline.fJogen ? RGB(255, 0, 0) : RGB(0, 0, 0));

		// �[���x
// Modify ... ( CHANGE )
		CString str1 = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[2].MenuNo, Menu_ManualWindowText[2].ItemNo );
		sTime.Format(str1, fTime / cGraphSubline.fMokugyoTact * 100);
//		sTime.Format("%.1f%%", fTime / cGraphSubline.fMokugyoTact * 100);
// By Y.Itabashi (xxxxx) 2007.01.26
		pDC->DrawText(sTime, rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);

		// ��Ǝ���
// Modify ... ( CHANGE )
		CString str2 = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[1].MenuNo, Menu_ManualWindowText[1].ItemNo );
		sTime.Format(str2, fTime);
//		sTime.Format("%.3f��", fTime);
// By Y.Itabashi (xxxxx) 2007.01.26
		rectText.bottom -= 16;
		pDC->DrawText(sTime, rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);
//		pDC->DrawText("���v����", rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP);

		pDC->SetTextColor(color);
	}
}

// �T�u���C���ԋ�؂���`��
void CManualEditView::DrawSublineBoundLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penSublineBar);

	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 1; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		if (cZahyoSubline.nSublineId != -1) {
			pDC->MoveTo(cZahyoSubline.nLeft, 0);
			pDC->LineTo(cZahyoSubline.nLeft, m_rectGraph.bottom);
			pDC->MoveTo(cZahyoSubline.nLeft - 1, 0);
			pDC->LineTo(cZahyoSubline.nLeft - 1, m_rectGraph.bottom);
		} else {
			pDC->SelectObject(&m_penKittingLine);
			pDC->MoveTo(cZahyoSubline.nLeft, 0);
			pDC->LineTo(cZahyoSubline.nLeft, m_rectGraph.bottom);
		}
	}

	pDC->SelectObject(pPenOrg);
}

// �L�b�g���T�u���C���ԋ�؂���`��
void CManualEditView::DrawKittingSubline(CDC *pDC)
{
	CRect rect;
	CPen *pPenOrg = pDC->SelectObject(&m_penKittingSubline);
	CFont *pFontOrg = pDC->SelectObject(&m_fontGraph);

	int nSize = m_aZahyoKittingSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoKittingSubline &cKittingSubline = m_aZahyoKittingSubline[i];
		int x = cKittingSubline.pointLine.x;
		int y = cKittingSubline.pointLine.y;

		rect.SetRect(x - m_nBarSpacing / 2, y - 14, x + m_nBarWidth + m_nBarSpacing, y + 14);
		if (pDC->RectVisible(rect)) {
			pDC->MoveTo(x - m_nBarSpacing / 2, y - 1);
			pDC->LineTo(x + m_nBarWidth + m_nBarSpacing / 2, y - 1);
			pDC->MoveTo(x - m_nBarSpacing / 2, y);
			pDC->LineTo(x + m_nBarWidth + m_nBarSpacing / 2, y);

			if (!cKittingSubline.sSiblineName1.IsEmpty())
				pDC->TextOut(x + m_nBarWidth + 2, y - 14, cKittingSubline.sSiblineName1);

			if (!cKittingSubline.sSiblineName2.IsEmpty())
				pDC->TextOut(x + m_nBarWidth + 2, y + 3, cKittingSubline.sSiblineName2);
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::OnToolHitTest
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c�[���`�b�v�̍��W���`�F�b�N
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint		point			[I] �}�E�X�J�[�\���̈ʒu
//		TOOLINFO	*pTI			[I] �c�[���`�b�v���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		���W�z��̃C���f�b�N�X
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�ʒu�Ɏl�p�`�����݂���ꍇ�̓c�[���`�b�v�ɕ\������f�[�^���Z�b�g����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CManualEditView::OnToolHitTest(CPoint point, TOOLINFO* pTI) const
{
	SZahyoGraph *pZahyoGraph;
	CRect rect;

	CPoint pointScroll = GetScrollPosition();

	// ���W�z��̒�����w�肳�ꂽ�ʒu���܂ގl�p�`������
	int nSize = m_aZahyoGraph.GetSize();
	for (int i = 0; i < nSize; i++) {
		pZahyoGraph = &m_aZahyoGraph[i];
		rect = pZahyoGraph->rect - pointScroll;
		if (rect.PtInRect(point))
			break;
	}
	if (i < nSize) {
		pTI->hwnd = m_hWnd;
		pTI->uFlags &= ~TTF_IDISHWND;
		pTI->rect = rect;
		pTI->uId = i;
		pTI->lpszText = LPSTR_TEXTCALLBACK;
		return i;
	}

	return -1;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::OnGetToolTipText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c�[���`�b�v��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		NMHDR		*pNMH			[I] NMHDR�|�C���^
//		LRESULT		*pRes			[I] LRESULT�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�c�[���`�b�v�ɕ\������e�L�X�g��p�ӂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::OnGetToolTipText(NMHDR* pNMH, LRESULT* pRes)
{
	TOOLTIPTEXT *pTTT = (TOOLTIPTEXT*)pNMH;

	// �c�[���`�b�v��\n�ŉ��s����悤�ɂ���
	_AFX_THREAD_STATE* pThreadState = AfxGetThreadState();
	CToolTipCtrl* pToolTip = pThreadState->m_pToolTip;
	if (pToolTip != NULL)
		pToolTip->SendMessage(TTM_SETMAXTIPWIDTH, 0, 300);

	// �c�[���`�b�v�ɕ\�����镶����ݒ�
	if ((int)pNMH->idFrom < m_aZahyoGraph.GetSize())
		pTTT->lpszText = (LPTSTR)(LPCTSTR)m_aZahyoGraph[pNMH->idFrom].sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::SetTactHeight
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڕW�^�N�g�̈ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bAdjust				[I] �ڕW�^�N�g�ʒu�␳�t���O
//		int		nKishuId			[I] �@��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�ڕW�^�N�g�̕\���ʒu��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditView::SetScaleFactor(BOOL bAdjust, int nKishuId)
{
	CManualEditDocument *pDoc = GetDocument();

	// �ő�̖ڕW�^�N�g���擾
	double fMaxTime = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.fMokugyoTact > fMaxTime)
			fMaxTime = cGraphSubline.fMokugyoTact;
	}

	// �O���t�̃o�[����ɂ͂ݏo���ꍇ�́A�ڕW�^�N�g�̈ʒu��������
	if (bAdjust) {
		double fTime = GetMaxTime(nKishuId);
		if (fTime > fMaxTime)
			fMaxTime = fTime;
	}

	m_fScaleFactor = m_rectGraph.Height() / fMaxTime * 0.9;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditView::GetMaxTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ł�������Ǝ��Ԃ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nKishuId			[I] �@��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�@��̒��ōł�������Ǝ��Ԃ��擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
double CManualEditView::GetMaxTime(int nKishuId)
{
	CManualEditDocument *pDoc = GetDocument();

	double fMaxTime = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId != -1) {
			int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
			for (int i = 0; i < nSagyoshaNum; i++) {
				SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
				if (nKishuId < 0) {
					// ���d���ς̏ꍇ
					if (cGraphSagyosha.fTime > fMaxTime)
						fMaxTime = cGraphSagyosha.fTime;

					if (nKishuId == -2) {
						int nKishuNum = cGraphSagyosha.aKishu.GetSize();
						for (int j = 0; j < nKishuNum; j++) {
							SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];
							if (cGraphKishu.fTime > fMaxTime)
								fMaxTime = cGraphKishu.fTime;
						}
					}
				} else {
					// �@��ʂ̏ꍇ
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[nKishuId];
					if (cGraphKishu.fTime > fMaxTime)
						fMaxTime = cGraphKishu.fTime;
				}
			}
		}
	}

	return fMaxTime;
}

// �X�����\���ݒ�
void CManualEditView::SetSlimMode()
{
	CManualEditDocument *pDoc = GetDocument();

	if (pDoc->m_bSlimMode) {
		SetSlim(m_nBarWidth);
		SetSlim(m_nLeftSpacing);
		SetSlim(m_nBarSpacing);
	}
}

void CManualEditView::SetSlim(int &nScale)
{
	nScale = nScale * 3 / 5;
}

int CManualEditView::GetTotalSagyoshaNum()
{
	CManualEditDocument *pDoc = GetDocument();

	int nTotalSagyoshaNum = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		if (cGraphSubline.nSublineId != -1)
			nTotalSagyoshaNum += cGraphSubline.aGraphSagyosha.GetSize();
	}

	return nTotalSagyoshaNum;
}

int CManualEditView::GetKittingSagyoshaNum()
{
	CManualEditDocument *pDoc = GetDocument();

	return pDoc->m_nKittingIndex != -1 ? pDoc->m_aGraphSubline[pDoc->m_nKittingIndex].aGraphSagyosha.GetSize() : 0;
}

void CManualEditView::DispSublineInfo()
{
	CManualEditDocument *pDoc = GetDocument();

	CString sText1, sText2, sText3;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

// Modify ... ( CHANGE )
		CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[3].MenuNo, Menu_ManualWindowText[3].ItemNo );
		sText1.Format(str, cGraphSubline.sSublineName);
		str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[4].MenuNo, Menu_ManualWindowText[4].ItemNo );
		sText2.Format(str, cGraphSubline.fMokugyoTact, cGraphSubline.aGraphSagyosha.GetSize());
		str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[5].MenuNo, Menu_ManualWindowText[5].ItemNo );
		sText3.Format(str, cGraphSubline.fKadoRitsu, g_pDataManager->GetSeisanDaisu(cGraphSubline.nSublineId));
//		sText1.Format("�T�u���C���F%s", cGraphSubline.sSublineName);
//		sText2.Format("�ڕW�^�N�g�F%.2f�@/�@����ƎҁF%d�l", cGraphSubline.fMokugyoTact, cGraphSubline.aGraphSagyosha.GetSize());
//		sText3.Format("�ғ����F%.0f%%�@/�@���Y�䐔�F%d��", cGraphSubline.fKadoRitsu, g_pDataManager->GetSeisanDaisu(cGraphSubline.nSublineId));
// By Y.Itabashi (xxxxx) 2007.01.26
		cZahyoSubline.cSublineInfo.SetText(sText1, sText2, sText3);
	}

    m_moveHight = 0;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

void CManualEditView::SetSublineInfoPos()
{
	CManualEditDocument *pDoc = GetDocument();
	CPoint point = GetScrollPosition();

	CRect rectClient;
	GetClientRect(rectClient);
	rectClient.OffsetRect(point.x, 0);

	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		int nLeft = max(rectClient.left, cZahyoSubline.nLeft);
		int nRight = min(rectClient.right, cZahyoSubline.nRight);
		int nWidth = nRight - nLeft;

		CRect rectSublineIndo;
		cZahyoSubline.cSublineInfo.GetClientRect(rectSublineIndo);
		if (nWidth > rectSublineIndo.Width()) {
			cZahyoSubline.cSublineInfo.SetWindowPos(NULL, (nLeft + nRight - rectSublineIndo.Width()) / 2 - point.x, 5, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
			cZahyoSubline.cSublineInfo.ShowWindow(SW_SHOW);
		} else
			cZahyoSubline.cSublineInfo.ShowWindow(SW_HIDE);
	}

	// ��ԉ��ɃX�N���[�������� 20070213 xxxxx kuwa
	if ( m_moveHight == 0 ) {	// �c�ʒu���ӂɎ����Ă��܂�
	   ScrollToPosition( CPoint(point.x, m_rectView.Height() - rectClient.Height()));
	} else {					// �c�ʒu�͎w�肵���Ƃ���
	   ScrollToPosition( CPoint(point.x,  point.y));
	}
}

void CManualEditView::HideSublineInfo()
{
	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++)
		m_aZahyoSubline[nSublineIndex].cSublineInfo.ShowWindow(SW_HIDE);
}

void CManualEditView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	HideSublineInfo();

	CScrollView::OnHScroll(nSBCode, nPos, pScrollBar);

	m_moveHight = 1;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

void CManualEditView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	HideSublineInfo();

	CScrollView::OnVScroll(nSBCode, nPos, pScrollBar);

	m_moveHight = 1;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

int CManualEditView::GetScaleHeight(double fMinute)
{
	return (int)(fMinute * m_fScaleFactor + 0.5);
}

// �w�肵����Ǝ҂𒆉��ɕ\������
void CManualEditView::DispCenterSagyosha(int nSublineId, int nSagyoshaId)
{
	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha &cZahyoSagyosha = m_aZahyoSagyosha[i];

		if (cZahyoSagyosha.nSublineId == nSublineId && cZahyoSagyosha.nSagyoshaId == nSagyoshaId) {
			CRect rect;
			GetClientRect(rect);
			ScrollToPosition(CPoint(cZahyoSagyosha.rect.CenterPoint().x - rect.Width() / 2, m_rectView.Height() - rect.Height()));
			break;
		}
	}
}

// �w�肵���ʒu���ǂ̃T�u���C���G���A�����ׂ�
SZahyoSubline *CManualEditView::GetZahyoSubline(CPoint point)
{
	point += GetScrollPosition();

	int nSize = m_aZahyoSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSubline &cZahyoSubline  = m_aZahyoSubline[i];
		if (point.x > cZahyoSubline.nLeft && point.x < cZahyoSubline.nRight)
			return &cZahyoSubline;
	}

	return NULL;
}
