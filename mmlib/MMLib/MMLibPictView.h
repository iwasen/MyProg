#pragma once

// MMLibPictView.h : �w�b�_�[ �t�@�C��
//

#include "Item.h"

/////////////////////////////////////////////////////////////////////////////
// CMMLibPictView �r���[

class CMMLibPictView : public CScrollView
{
protected:
	CMMLibPictView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CMMLibPictView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual DROPEFFECT OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	virtual BOOL OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual void OnInitialUpdate();     // �\�z��A�ŏ��̏����B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnPrint(CDC* pDC, CPrintInfo* pInfo);

// �C���v�������e�[�V����
protected:
	virtual ~CMMLibPictView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg BOOL OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message);
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
	afx_msg void OnRButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnEditCopy();
	afx_msg void OnUpdateEditCopy(CCmdUI* pCmdUI);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnRButtonDown(UINT nFlags, CPoint point);
	afx_msg LRESULT OnGetSelectedItem(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUpdatePict(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	HDIB m_hDIB;
	CBrush m_hBrushBkgnd;
	CBrush m_hBrushSelect;
	CFont m_Font;
	SIZE m_sizePict;
	SIZE m_sizeDraw;
	BOOL m_bScrollPicture;
	CPoint m_posPicture;
	int m_nDispMode;
	CItemData *m_pSelectItem;
	CItem *m_pCurrentItem;
	double m_fPictRatio;
	int m_nTextMode;
	int m_nSmallSize;
	BOOL m_bIgnoreUpdate;
	COleDropTarget m_DropTarget;
	BOOL m_bModify;

	void DrawItems();
	BOOL ReadPicture(CItemData *pItem);
	void ReadPictureInfo();
	void DrawList(CDC *pDC, CItem *pItem);
	void DrawPicture(CDC *pDC, CItemData *pItem);
	CItemData *GetItemByPoint(CPoint point);
	void DrawSelect(CDC* pDC, BOOL bSelect = TRUE);
	void SetViewFont();
	void OpenPictFrame();
	void ChangePicture(HDIB hDIB, BOOL bUpdateView = TRUE);
	void SaveData(CItem *pItem);
	void EditPicture();
	static UINT EditPictureThread(LPVOID pParam);
	void EditPictureSub();
public:
	afx_msg void OnUpdateFilePrint(CCmdUI *pCmdUI);
	afx_msg void OnUpdateFilePrintPreview(CCmdUI *pCmdUI);
};
