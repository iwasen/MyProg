// MainTreeView.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "MainTreeView.h"
#include "MMA_GDoc.h"
#include "TreeDir.h"
#include "GlobalData.h"

// CMainTreeView

IMPLEMENT_DYNCREATE(CMainTreeView, CTreeView)

CMainTreeView::CMainTreeView()
{
}

CMainTreeView::~CMainTreeView()
{
}

BEGIN_MESSAGE_MAP(CMainTreeView, CTreeView)
	ON_NOTIFY_REFLECT(TVN_SELCHANGED, &CMainTreeView::OnTvnSelchanged)
END_MESSAGE_MAP()


// CMainTreeView �f�f

#ifdef _DEBUG
void CMainTreeView::AssertValid() const
{
	CTreeView::AssertValid();
}

#ifndef _WIN32_WCE
void CMainTreeView::Dump(CDumpContext& dc) const
{
	CTreeView::Dump(dc);
}
#endif
#endif //_DEBUG


// CMainTreeView ���b�Z�[�W �n���h��

// �E�B���h�E�쐬�O����
BOOL CMainTreeView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style |= TVS_HASLINES | TVS_HASBUTTONS | TVS_SHOWSELALWAYS;

	return CTreeView::PreCreateWindow(cs);
}


// �A�C�e���I������
void CMainTreeView::OnTvnSelchanged(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMTREEVIEW pNMTreeView = reinterpret_cast<LPNMTREEVIEW>(pNMHDR);

	HTREEITEM hItem = pNMTreeView->itemNew.hItem;
	CTreeCtrl &tc = GetTreeCtrl();
	DWORD nItemData = (DWORD)tc.GetItemData(hItem);
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();

//	switch (nItemData & ID_KIND) {
//	case ID_FOLDER:
		// ���X�g�r���[�ɑI�������t�H���_�̃f�[�^��\��
		pDoc->m_nSelectedItem = nItemData;
		pDoc->UpdateAllViews(this);
//		break;
//	}

	*pResult = 0;
}

void CMainTreeView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	CTreeCtrl &treeCtrl = (CTreeCtrl &)GetTreeCtrl();
	CImageList &imageList = ((CMMA_GApp *)AfxGetApp())->m_imageList;
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();

	treeCtrl.DeleteAllItems();

	// �c���[�r���[�ɃC���[�W���X�g��ݒ�
	treeCtrl.SetImageList(&imageList, TVSIL_NORMAL);

	// �ŏ�ʂ̃A�C�e����ݒ�
	HTREEITEM hTreeRoot = treeCtrl.InsertItem(TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT,
			"�f�[�^�x�[�X", IMAGE_SYSTEM, IMAGE_SYSTEM, TVIS_EXPANDED, TVIS_EXPANDED, ID_SYSTEM, TVI_ROOT, TVI_LAST);

	// �f�[�^���X�g��ǂݍ��݁A�\������
	pDoc->m_TreeDir.ReadDir(gRootDataList, treeCtrl, hTreeRoot);

	// �I�𒆂̃A�C�e�����Z�b�g
	pDoc->m_nSelectedItem = SelectItem(hTreeRoot, (LPCTSTR)lHint);
}

int CMainTreeView::SelectItem(HTREEITEM hTree, LPCTSTR pPath)
{
	CTreeCtrl &treeCtrl = (CTreeCtrl &)GetTreeCtrl();
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();

	if (pPath == NULL)
		return 0;

	int idx = (int)treeCtrl.GetItemData(hTree);
	if (idx != 0) {
		CString sPath;
		if (pDoc->m_TreeDir.SearchIdx(idx, sPath)) {
			if (sPath == pPath) {
				treeCtrl.SelectItem(hTree);
				return idx;
			}
		}
	}

	if (treeCtrl.ItemHasChildren(hTree)) {
		HTREEITEM hChildItem = treeCtrl.GetChildItem(hTree);

		while (hChildItem != NULL) {
			idx = SelectItem(hChildItem, pPath);
			if (idx != 0)
				return idx;

			hChildItem = treeCtrl.GetNextItem(hChildItem, TVGN_NEXT);
		}
	}

	return 0;
}
