#pragma once

// MMLibInfoView.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMMLibInfoView �t�H�[�� �r���[

#ifndef __AFXEXT_H__
#include <afxext.h>
#endif

class CMMLibInfoView : public CFormView
{
protected:
	CMMLibInfoView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CMMLibInfoView)

// �t�H�[�� �f�[�^
public:
	enum { IDD = IDD_INFO_VIEW };
	CMyDate	m_cUpdateDate;
	CMyDate	m_cCreateDate;
	CMyEdit	m_cCode;
	CButton	m_cUpdateInfo;
	CMyEdit	m_cTitle;
	CMyEdit	m_cRemark;
	CMyEdit	m_cKeyword;
	CMyEdit	m_cCreator;

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// �C���v�������e�[�V����
protected:
	virtual ~CMMLibInfoView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnUpdateInfo();
	afx_msg void OnChangeCode();
	afx_msg void OnChangeTitle();
	afx_msg void OnChangeCreator();
	afx_msg void OnChangeRemark();
	afx_msg void OnChangeKeyword();
	afx_msg void OnEditCopy();
	afx_msg void OnEditCut();
	afx_msg void OnEditPaste();
	afx_msg void OnUpdateEditPaste(CCmdUI* pCmdUI);
	afx_msg void OnUpdateEditCopy(CCmdUI* pCmdUI);
	afx_msg void OnUpdateEditCut(CCmdUI* pCmdUI);
	afx_msg LRESULT OnGetSelectedItem(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	CItem *m_pCurrentItem;
	int m_nFormHeight;

	void DrawItem();
	void DrawRootInfo();
	void DrawFolderInfo();
	void DrawDataInfo();
	void DrawFindInfo();
	void DrawOtherInfo();
	void SaveData(CItem *pItem);
	void SaveFolderInfo(CItemFolder *pItemFolder);
	void SaveDataInfo(CItemData *pItemData);
	void SaveFindInfo(CItemFind *pItemFind);
	void ModifyData();
};
