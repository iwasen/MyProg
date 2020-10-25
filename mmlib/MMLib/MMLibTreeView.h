#pragma once

// MMLibTreeView.h : �w�b�_�[ �t�@�C��
//

#include "MMLibDB.h"
#include "Item.h"

/////////////////////////////////////////////////////////////////////////////
// CMMLibTreeView �r���[

class CMMLibTreeView : public CTreeView
{
protected:
	CMMLibTreeView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^
	DECLARE_DYNCREATE(CMMLibTreeView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual void OnInitialUpdate();
	virtual DROPEFFECT OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	virtual BOOL OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	virtual BOOL OnScroll(UINT nScrollCode, UINT nPos, BOOL bDoScroll = TRUE);
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnPrint(CDC* pDC, CPrintInfo* pInfo);

// �C���v�������e�[�V����
protected:
	virtual ~CMMLibTreeView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg void OnSelchanged(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDestroy();
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnItemexpanding(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnRclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnBegindrag(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg LRESULT OnGetSelectedItem(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	COleDropTarget m_DropTarget;
	HTREEITEM m_hTreeRoot;
	BOOL m_bInUpdate;
	BOOL m_bDragEntry;
	CFont m_Font;
	CString m_Order;
	int m_nTextMode;

	void AddLibrary(CMMPcsDB *pPcsDB);
	void AddPendingLibrary(CAddItemPending *pAddItemPending);
	CItem *OpenLibrary(CItemPending *pItemPending);
	CItemFolder *AddFolder(CItemFolder *pItemParent, FOLDERREC *pFolderRec);
	CItemFolder *AddFolder(CItemFind *pItemParent, FOLDERREC *pFolderRec);
	CItemFolder *AddFolder(HTREEITEM hItem, CMMPcsDB *pPcsDB, FOLDERREC *pFolderRec);
	CItemData *AddData(CItemFolder *pItemParent, DATAREC *pDataRec);
	CItemData *AddData(CItemFind *pItemParent, DATAREC *pDataRec);
	CItemData *AddDataSub(HTREEITEM hItem, CMMPcsDB *pPcsDB, DATAREC *pDataRec);
	void AddFind(CAddItemFind *pAddItemFind);
	void ReadChildren(CItemFolder *pItemFolder);
	void FindData(CItemFind *pItemFind);
	void ReFindItem(CItem *pItem);
	void SetChildren(HTREEITEM hItem, BOOL bChild);
	void AddItem(CAddItem *pAddItem);
	void RemoveItem(CItem *pItem);
	void RemoveItemSub(CItem *pItem);
	void UpdateItem(CItem *pItem);
	void UpdateItemSub(CItem *pItem);
	DROPEFFECT DragOverItem(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	DROPEFFECT DragOverFile(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	BOOL DropItem(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	BOOL DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	CItem *GetTargetItem(CPoint point);
	CItem *GetSourceItem(COleDataObject* pDataObject);
	int GetSourceFileType(COleDataObject* pDataObject);
	void ReloadItem(CItemFolder *pItemFolder, BOOL bSubitem);
	CItemFolder *FindChildFolder(CItem *pItem, LPCTSTR pFolderID);
	CItemData *FindChildData(CItem *pItem, LPCTSTR pDataID);
	void ReloadAllItems();
	void SortItem(CItem *pItem);
	static int CALLBACK CompareFunc(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	int CompareFolder(CItemFolder *pItemFolder1, CItemFolder *pItemFolder2);
	int CompareData(CItemData *pItemData1, CItemData *pItemData2);
	void SetViewFont();
	void SortAllItems();
	void SetTextDataItems();
	LPCTSTR DataItemText(CItemData *pItemData);
	void SelectData(CSelectItem *pSelectItem);
	BOOL SelectDataSub(CSelectItem *pSelectItem);
	HTREEITEM SelectFolder(HTREEITEM hItemLibrary, CMMPcsDB *pPcsDB, CString &folderID, int count);
public:
	afx_msg void OnUpdateFilePrint(CCmdUI *pCmdUI);
	afx_msg void OnUpdateFilePrintPreview(CCmdUI *pCmdUI);
};
