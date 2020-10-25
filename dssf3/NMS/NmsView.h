#pragma once

#include "listvwex.h"
#include "NmsItem.h"

class CNmsView : public CListViewEx
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CNmsView();
	DECLARE_DYNCREATE(CNmsView)

	CNmsDoc* GetDocument();

	virtual ~CNmsView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	void SetListHeader();
	void AddItem(const CNmsItem *pNmsItem);
	void UpdateItem(const CNmsItem *pNmsItem);
	void SetNmsItem(const CNmsItem *pNmsItem);
	CNmsItem *GetSelectedItem();

	DECLARE_MESSAGE_MAP()
	virtual void OnInitialUpdate(); // �\�z��̍ŏ��̂P�x�����Ăяo����܂��B
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnBeginPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnEndPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	virtual void OnDraw(CDC* pDC);  // ���̃r���[��`�悷��ۂɃI�[�o�[���C�h����܂��B
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	afx_msg void OnViewFactor();
	afx_msg void OnUpdateViewFactor(CCmdUI* pCmdUI);
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
	afx_msg void OnChangeNoiseSrc();
	afx_msg void OnUpdateChangeNoiseSrc(CCmdUI* pCmdUI);
	afx_msg void OnRclick(NMHDR* pNMHDR, LRESULT* pResult);
};

#ifndef _DEBUG  // NmsView.cpp �t�@�C�����f�o�b�O���̎��g�p����܂��B
inline CNmsDoc* CNmsView::GetDocument()
   { return (CNmsDoc*)m_pDocument; }
#endif
