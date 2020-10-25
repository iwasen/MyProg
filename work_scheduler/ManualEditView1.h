#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditView1.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditView1 �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "ManualEditView.h"

#define WM_USER_DRAG	(WM_USER + 100)
#define USER_DRAG_ENTER	0
#define USER_DRAG_OVER	1
#define USER_DRAG_LEAVE	2

// �h���b�O�g���b�J�[
class CRectTrackerEx : public CRectTracker
{
public:
	HWND m_hWnd;

	// �R���X�g���N�^
	CRectTrackerEx(LPCRECT lpSrcRect, UINT nStyle)
		: CRectTracker(lpSrcRect, nStyle)
	{
		m_hWnd = NULL;
	}

	// �l�p�`�̈ړ�
	void OnChangedRect(const CRect& rectOld)
	{
		int nFlag;
		CPoint point;
		::GetCursorPos(&point);
		HWND hWnd = ::WindowFromPoint(point);

		if (m_hWnd != NULL && m_hWnd != hWnd)
			::SendMessage(m_hWnd, WM_USER_DRAG, USER_DRAG_LEAVE, 0);

		if (hWnd != NULL) {
			if (hWnd != m_hWnd)
				nFlag = USER_DRAG_ENTER;
			else
				nFlag = USER_DRAG_OVER;
			::SendMessage(hWnd, WM_USER_DRAG, nFlag, 0);
		}

		m_hWnd = hWnd;
	}
};

// �蓮�Ґ���ʂ̍���r���[�i�ҏW�j
class CManualEditView1 : public CManualEditView
{
protected:
	CManualEditView1();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CManualEditView1)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:
// Modify ... ( ADD )
	void AddSelectKyotsuKotei();
	void AddReferenceKotei();
	void AddSelectKittingKotei();
// By Y.Itabashi (xxxxx) 2007.02.27
// Modify ... ( ADD )
	void AddSelectAutiKotei();
// By Y.Itabashi (xxxxx) 2007.02.27

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditView1)
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual void OnInitialUpdate();     // �\�z��A�ŏ��̏����B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	virtual void OnActivateView(BOOL bActivate, CView* pActivateView, CView* pDeactiveView);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditView1)
	afx_msg void OnRButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnManualMove();
	afx_msg void OnManualBunkatsu();
	afx_msg void OnManualEdit();
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnManualSagyoshaMae();
	afx_msg void OnManualSagyoshaUshiro();
	afx_msg void OnManualSagyoshaSakujo();
	afx_msg void OnManualCopy();
	afx_msg void OnManualPaste();
	afx_msg void OnManualDstr();
	//}}AFX_MSG
	afx_msg LRESULT OnUserDrag(WPARAM wParam, LPARAM lParam);
	afx_msg long OnEnterSizeMove(WPARAM wParam, LPARAM lParam);
	afx_msg long OnExitSizeMove(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	SZahyoDrag *m_pRightClickZahyo;		// �N���b�N�������W�f�[�^
	CPoint m_pointDrag;					// �h���b�O���}�E�X���W
	CRect m_rectDrag;					// �h���b�O���l�p�`���W
	CPoint m_pointDragOld;				// �h���b�O���O��}�E�X���W
	CBrush m_brushMoveDrag;				// �h���b�O��̎O�p�`�`��p�u���V
	CPen m_penSelected;
	CPen m_penNewMove;
	CPen m_penKittingCancel;			// �L�b�g�������`��p�̃y��
// Modify ... ( ADD )
	CPen m_penReference;				// �Q�ƍH���\��
// By Y.Itabashi (xxxxx) 2007.02.27
	CRect m_rectSelect;
	int m_nMouseMoveMode;
	int m_nSelectRemoveIndex;
	CZahyoDragArray m_aZahyoDrag;
	CZahyoDropArray m_aZahyoDrop;
	CRect m_rectNewMove;
	CArray <CRect, CRect&> m_aKittingCancel;

	void MakeGraph();
	void MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft);
	BOOL GetSagyoshaKyotsuKotei(SGraphSagyosha &cGraphSagyosha, CString &sKyotsuKotei);
	void GetSagyoshaKyotsuKoteiSub(SGraphSagyosha &cGraphSagyosha, int nKishuId, CStringArrayEx &aKyotsuKotei);
	void MakeZahyoGraph(int nSublineId, CGraphBlockArray &aGraphBlock, int nSagyoshaId, int nLeft, int nTop, int &nKittingSublineId, SGraphBlock *&pLastGraphBlock);
	void SetViewSize();
	void DrawNewMoveFrame(CDC *pDC);
	void DrawHorizontalAxisText(CDC *pDC);
	void DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft);
	void DrawTotalTime(CDC *pDC);
	void DrawTotalTimeSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft);
	void DrawKittingCancelFrame(CDC *pDC);
// Modify ... ( ADD )
	void DrawReferenceFrame(CDC *pDC);
// By Y.Itabashi (xxxxx) 2007.02.27
	void DrawMoveTriangle(CPoint point);
// Modify ... ( CHANGE )
	void ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags);
	void ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags);
//	void ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag);
//	void ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag);
// By Y.Itabashi (xxxxx) 2007.02.21
	void MoveDrag();
	void CancelKitting();
	void SetScaleParamaeter();
	void DrawFocusRect(CRect rect);
	void BlinkSelectedBox(BOOL bBlink);
	void EraseSelectedBox();
	void ResetSelectedBox();
	void SelectRect();
	void AddSelect(int nIndex);
	void RemoveSelect(int nIndex);
	CRect GetSelectRect();
	BOOL CheckSelect(SGraphBlock *pGraphBlock);
	void SelectSagyosha(CPoint point);
	SZahyoDrag *CheckZahyoDrag(CPoint point, CRect &rect);
	SZahyoDrop *CheckZahyoDrop(CPoint point, CRect &rect);
	void AddZahyoDrag(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId);
	void AddZahyoDrop(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId, CPoint pointTriangle, BOOL bLastBlock);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
