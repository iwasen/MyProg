#pragma once

#include "MyCtrl.h"

class CSaveDlg : public CDialog
{
public:
	CSaveDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	CObList *m_pItemList;

protected:
	enum { IDD = IDD_SAVE };

	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	BOOL MakeFolder(long &nFolderID);
	BOOL SaveData(long nFolderID);

	CLixxxtrl	m_cFolderList;
	CMyEdit	m_cTitle;
	CMyEdit	m_cName;
	CMyEdit	m_cPlace;
	CMyEdit	m_cComment;
	CMyEdit	m_cDate;
	BOOL	m_bWaveNoSave;
	long m_nFolderID;
	int m_nSortItem;
	int m_nSortDir;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
