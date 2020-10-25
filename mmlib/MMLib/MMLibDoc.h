// MMLibDoc.h : CMMLibDoc �N���X�̐錾����уC���^�[�t�F�C�X�̒�`�����܂��B
//
/////////////////////////////////////////////////////////////////////////////

#pragma once

#include "Item.h"
#include "FindDB.h"
#include "Mml.h"

class CMMLibDoc : public CDocument
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CMMLibDoc();
	DECLARE_DYNCREATE(CMMLibDoc)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

//�I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual BOOL OnNewDocument();
	virtual void Serialize(CArchive& ar);
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);
	virtual void OnCloseDocument();
	virtual BOOL OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo);
	protected:
	virtual BOOL SaveModified();

// �C���v�������e�[�V����
public:
	virtual ~CMMLibDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg void OnFileNew();
	afx_msg void OnFileOpen();
	afx_msg void OnFileClose();
	afx_msg void OnViewPict();
	afx_msg void OnViewList();
	afx_msg void OnViewDetail();
	afx_msg void OnUpdateFileNew(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileOpen(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileClose(CCmdUI* pCmdUI);
	afx_msg void OnUpdateViewPict(CCmdUI* pCmdUI);
	afx_msg void OnUpdateViewList(CCmdUI* pCmdUI);
	afx_msg void OnUpdateViewDetail(CCmdUI* pCmdUI);
	afx_msg void OnFileUpdate();
	afx_msg void OnFileDelete();
	afx_msg void OnPictOpen();
	afx_msg void OnViewReload();
	afx_msg void OnViewPrev();
	afx_msg void OnUpdateViewPrev(CCmdUI* pCmdUI);
	afx_msg void OnViewNext();
	afx_msg void OnUpdateViewNext(CCmdUI* pCmdUI);
	afx_msg void OnPictEnlarge();
	afx_msg void OnUpdatePictEnlarge(CCmdUI* pCmdUI);
	afx_msg void OnPictReduce();
	afx_msg void OnUpdatePictReduce(CCmdUI* pCmdUI);
	afx_msg void OnPictRightRotate();
	afx_msg void OnUpdatePictRightRotate(CCmdUI* pCmdUI);
	afx_msg void OnPictLeftRotate();
	afx_msg void OnUpdatePictLeftRotate(CCmdUI* pCmdUI);
	afx_msg void OnPictAutosize();
	afx_msg void OnUpdatePictAutosize(CCmdUI* pCmdUI);
	afx_msg void OnDetailName();
	afx_msg void OnUpdateDetailName(CCmdUI* pCmdUI);
	afx_msg void OnDetailCode();
	afx_msg void OnUpdateDetailCode(CCmdUI* pCmdUI);
	afx_msg void OnDetailCdate();
	afx_msg void OnUpdateDetailCdate(CCmdUI* pCmdUI);
	afx_msg void OnDetailUdate();
	afx_msg void OnUpdateDetailUdate(CCmdUI* pCmdUI);
	afx_msg void OnDetailCreator();
	afx_msg void OnUpdateDetailCreator(CCmdUI* pCmdUI);
	afx_msg void OnDetailRemark();
	afx_msg void OnUpdateDetailRemark(CCmdUI* pCmdUI);
	afx_msg void OnDetailKeyword();
	afx_msg void OnUpdateDetailKeyword(CCmdUI* pCmdUI);
	afx_msg void OnDetailServer();
	afx_msg void OnUpdateDetailServer(CCmdUI* pCmdUI);
	afx_msg void OnDetailDirectory();
	afx_msg void OnUpdateDetailDirectory(CCmdUI* pCmdUI);
	afx_msg void OnViewUpfolder();
	afx_msg void OnUpdateViewUpfolder(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileUpdate(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileDelete(CCmdUI* pCmdUI);
	afx_msg void OnFind();
	afx_msg void OnCacheRead();
	afx_msg void OnUpdateCacheRead(CCmdUI* pCmdUI);
	afx_msg void OnCacheClear();
	afx_msg void OnUpdateCacheClear(CCmdUI* pCmdUI);
	afx_msg void OnOption();
	afx_msg void OnFileSendMail();
	afx_msg void OnUpdateFileSendMail(CCmdUI* pCmdUI);
	afx_msg void OnEditSelectAll();
	afx_msg void OnFileSave();
	afx_msg void OnUpdateFileSave(CCmdUI* pCmdUI);
	afx_msg void OnPictAutosize2();
	afx_msg void OnUpdatePictAutosize2(CCmdUI* pCmdUI);
	afx_msg void OnSelchangeCombo();
	afx_msg void OnEditchangeCombo();
	afx_msg void OnUpdateCombo(CCmdUI* pCmdUI);
	afx_msg void OnRefind();
	afx_msg void OnUpdateRefind(CCmdUI* pCmdUI);
	afx_msg void OnFilePack();
	afx_msg void OnUpdateFilePack(CCmdUI* pCmdUI);
	afx_msg void OnFileExport();
	afx_msg void OnUpdateFileExport(CCmdUI* pCmdUI);
	afx_msg void OnBackup();
	afx_msg void OnRestore();
	afx_msg void OnTransReg();
	afx_msg void OnImportBmp();
	afx_msg void OnUpdateImportBmp(CCmdUI* pCmdUI);
	afx_msg void OnImportMml();
	afx_msg void OnUpdateImportMml(CCmdUI* pCmdUI);
	afx_msg void OnImportPpm();
	afx_msg void OnUpdateImportPpm(CCmdUI* pCmdUI);
	afx_msg void OnPictAllRotate();
	afx_msg void OnUpdatePictAllRotate(CCmdUI* pCmdUI);
	afx_msg void OnPictWindow();
	afx_msg void OnUpdatePictWindow(CCmdUI* pCmdUI);
	afx_msg void OnFilePassword();
	afx_msg void OnFilePermission();
	afx_msg void OnEditCopy();
	afx_msg void OnUpdateEditCopy(CCmdUI* pCmdUI);
	afx_msg void OnEditCut();
	afx_msg void OnUpdateEditCut(CCmdUI* pCmdUI);
	afx_msg void OnEditPaste();
	afx_msg void OnUpdateEditPaste(CCmdUI* pCmdUI);
	afx_msg void OnNewData();
	afx_msg void OnViewMax();
	afx_msg void OnUpdateViewMax(CCmdUI* pCmdUI);
	afx_msg void OnKeyword();
	afx_msg void OnEditImage();
	afx_msg void OnUpdateEditImage(CCmdUI* pCmdUI);
	afx_msg void OnDetailFirstdata();
	afx_msg void OnUpdateDetailFirstdata(CCmdUI* pCmdUI);
	DECLARE_MESSAGE_MAP()

public:
	int m_nViewMode;
	int m_nAutoSize;
	BOOL m_bPictView;
	BOOL m_bDetailFlag[3][DETAIL_NUM];
	BOOL m_nDetailWidth[3][DETAIL_NUM];
	FOLDERREC m_FolderRec;
	DATAREC m_DataRec;
	CString m_sExecFile;
	CString m_sTempFile;

	void OpenCurrentLibrary();
	void OpenFind(CItemFolder *pItem);
	void CreateLibrary();
	void CreateFolder(CItemFolder *pItemFolder);
	void CreateData(CItemFolder *pItemFolder);
	void OpenLibrary();
	void NameChange(CItem *pItem, LPCTSTR pName);
	void CopyItem(CItem *pItemSrc, CItem *pItemDst);
	void MoveItem(CItem *pItemSrc, CItem *pItemDst);
	CItem *GetSelectedItem();
	void ImportMmlFile(LPCTSTR pFileName, CItemFolder *pItemFolder);
	void ImportPictFile(CString *pFileName, int nFileName, CItemFolder *pItemFolder);
	void ImportPpmFile(LPCTSTR pFileName, CItemFolder *pItemFolder);
	void InsertData(LPCTSTR pFolderTitle, LPCTSTR pFolderComment, LPCTSTR pDataTitle, LPCTSTR pDataComment, HDIB hDIB, LPCTSTR pText);

private:
	BOOL m_bEditCut;
	BOOL m_bMaxPict;

	void NewFindSimple();
	void NewFindExpert();
	void UpdateFolder(CItemFolder *pItemFolder);
	void UpdateData(CItemData *pItemData);
	void UpdateFind(CItemFind *pItemFind);
	void UpdateFindSimple(CItemFind *pItemFind);
	void UpdateFindExpert(CItemFind *pItemFind);
	void DeleteFolder(CItemFolder *pItemFolder);
	void DeleteData(CItemData *pItemData);
	void CopyFolder(CItemFolder *pItemSrc, CItemFolder *pItemDst);
	void CopyData(CItemData *pItemSrc, CItemFolder *pItemDst);
	void MoveFolder(CItemFolder *pItemSrc, CItemFolder *pItemDst);
	void MoveData(CItemData *pItemSrc, CItemFolder *pItemDst);
	BOOL CheckParent(CItem *pItemSrc, CItem *pItemDst);
	int GetSelectedItemType();
	BOOL CheckSelectedItemType(int nItemType);
	void InverseDetailDisp(int nDetailDisp);
	BOOL CheckDetailDisp(int nDetailDisp);
	BOOL Offlixxxheck(CItem *pItem);
	BOOL ExportData(CItemData *pItemData);
	BOOL ExportMmlFile(CItem *pItem);
	void ImportFile(LPCTSTR pFilter);
	CString CreateDFolder(CItemFolder *pItemFolder, LPCTSTR pName, LPCTSTR pCreator, LPCTSTR pDate);
	BOOL ReadMmlHeader(CMmlRead *pMmlRead, FOLDERREC *pFolderRec, CString *pText);
	BOOL ReadMmlData(int nPage, CMmlRead *pMmlRead, DATAREC *pDataRec, HDIB *phDIB, CString *pText);
	void OnTransApp(int nIndex);
	static UINT ExecApp(LPVOID pParam);
	BOOL CopyToClipboard();
};
