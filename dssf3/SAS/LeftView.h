#pragma once

#include "MyCtrl.h"

class CSasDoc;

class CLeftView : public CTreeView
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CLeftView();
	DECLARE_DYNCREATE(CLeftView)

	CSasDoc* GetDocument();

	void SetTreeItem();
	void SelectItem(HTREEITEM hItem, DWORD dwItemData);
	void DeleteItem(long nItemID);
	BOOL DeleteItemSub(long nItemID, HTREEITEM hTreeItem);
	BOOL ChangeTitle(long nItemID, HTREEITEM hTreeItem, LPCTSTR pTitle);

protected:
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	CMyEdit m_cEditTitle;

	DECLARE_MESSAGE_MAP()
	virtual void OnDraw(CDC* pDC);  // ���̃r���[��`�悷��ۂɃI�[�o�[���C�h����܂��B
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	protected:
	virtual void OnInitialUpdate(); // �\�z��̍ŏ��̂P�x�����Ăяo����܂��B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	afx_msg void OnSelchanged(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnFileOpen();
	afx_msg void OnUpdateFileOpen(CCmdUI* pCmdUI);
	afx_msg void OnFileDelete();
	afx_msg void OnUpdateFileDelete(CCmdUI *pCmdUI);
	afx_msg void OnFileChangeTitle();
	afx_msg void OnUpdateFileChangeTitle(CCmdUI *pCmdUI);
	afx_msg void OnTvnBeginlabeledit(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnTvnEndlabeledit(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnNMRclick(NMHDR *pNMHDR, LRESULT *pResult);
};

#ifndef _DEBUG  // LeftView.cpp �t�@�C�����f�o�b�O���̎��g�p����܂��B
inline CSasDoc* CLeftView::GetDocument()
   { return (CSasDoc*)m_pDocument; }
#endif
