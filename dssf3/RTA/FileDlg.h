#pragma once

#include "FileIO.h"
#include "MyCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CFolderList �E�B���h�E

class CFolderList : public CListBox
{
public:
	CFolderList();
	virtual ~CFolderList();

	void SetFolderList(int nFolderType);
	DbFolderRec *GetSelFolderRec();

protected:
	DbFolderRec *m_pFolder;

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
// CIRDataList �E�B���h�E

class CIRDataList : public CListBox
{
public:
	CIRDataList();
	virtual ~CIRDataList();

	void SetDataList(long impulseID);
	DbImpulseRec *GetSelImpulseRec();

protected:
	DbImpulseRec *m_pData;

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
// CAcfDataList �E�B���h�E

class CAcfDataList : public CListBox
{
public:
	CAcfDataList();
	virtual ~CAcfDataList();

	void SetDataList(long impulseID);
	DbAcfRec *GetSelAcfRec();

protected:
	DbAcfRec *m_pData;

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
// CFolderDlg �_�C�A���O

class CFolderDlg : public CDialog
{
public:
	CFolderDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	int m_nFolderType;

protected:
	enum { IDD = IDD_FOLDER };

	CFolderList	m_cFolderList;
	CMyEdit m_cTitle;
	CMyEdit m_cName;
	CMyEdit m_cPlace;
	CMyEdit m_cDate;
	CMyEdit m_cComment;
	CMyEdit m_cScale;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	afx_msg void OnRegist();
	afx_msg void OnDelete();
	afx_msg void OnChange();
	afx_msg void OnSelchangeFolderList();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};

/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////
// CSaveIR �_�C�A���O

class CSaveIR : public CDialog
{
public:
	CSaveIR(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	CString m_Time;
	HWAVEDATA m_hWaveData;
	int m_iSampling;
	int m_nStage;
	int m_iMeasureNum;
	int m_nChannel;
	int m_iBit;
	BOOL m_bUseFilter;
	long m_nIFilterID;
	int m_nMultiplier;
	BOOL m_bImpulseData;
	int m_nMethod;

protected:
	enum { IDD = IDD_SAVE_IR };

	CIRDataList	m_cDataList;
	CFolderList	m_cFolderList;
	CMyEdit	m_cComment;
	CMyEdit	m_cTitle;
	CString	m_sChannel;
	CString	m_sMeasureTime;
	CString	m_sTime;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnFolder();
	afx_msg void OnSelchangeFolderList();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};

/////////////////////////////////////////////////////////////////////////////
// CLoadIR �_�C�A���O

class CLoadIR : public CDialog
{
public:
	CLoadIR(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	long m_nImpulseID;
	CString m_sWaveFile;

protected:
	enum { IDD = IDD_LOAD_DATA };

	CIRDataList	m_cDataList;
	CMyEdit	m_cFolderTitle;
	CMyEdit	m_cFolderPlace;
	CMyEdit	m_cFolderName;
	CMyEdit	m_cFolderDate;
	CMyEdit	m_cFolderComment;
	CMyEdit	m_cDataTitle;
	CMyEdit	m_cDataTime;
	CMyEdit	m_cDataComment;
	CFolderList	m_cFolderList;
	long m_nFolderID;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeFolderList();
	afx_msg void OnSelchangeDataList();
	afx_msg void OnDblclkDataList();
	afx_msg void OnDelete();
	afx_msg void OnFolder();
	afx_msg void OnWaveFile();
	afx_msg void OnImportData();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};

/////////////////////////////////////////////////////////////////////////////
// CLoadACF �_�C�A���O

class CLoadACF : public CDialog
{
public:
	CLoadACF(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	long m_nAcfID;
	CString m_sWaveFile;

protected:
	enum { IDD = IDD_LOAD_DATA };

	CAcfDataList	m_cDataList;
	CMyEdit	m_cFolderTitle;
	CMyEdit	m_cFolderPlace;
	CMyEdit	m_cFolderName;
	CMyEdit	m_cFolderDate;
	CMyEdit	m_cFolderComment;
	CMyEdit	m_cDataTitle;
	CMyEdit	m_cDataTime;
	CMyEdit	m_cDataComment;
	CFolderList	m_cFolderList;
	long m_nFolderID;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	afx_msg void OnSelchangeFolderList();
	afx_msg void OnSelchangeDataList();
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	afx_msg void OnDblclkDataList();
	afx_msg void OnDelete();
	afx_msg void OnFolder();
	afx_msg void OnImportData();
	afx_msg void OnWaveFile();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};

/////////////////////////////////////////////////////////////////////////////
// CSaveACF �_�C�A���O

class CSaveACF : public CDialog
{
public:
	CSaveACF(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	int m_iSampling;
	int m_nChannel;
	int m_nSample;
	int m_nBitsPerSample;
	double m_fDataTime;
	CString m_Time;
	HWAVEDATA m_hWaveData;

protected:
	enum { IDD = IDD_SAVE_ACF };

	CAcfDataList	m_cDataList;
	CFolderList	m_cFolderList;
	CMyEdit	m_cTitle;
	CMyEdit	m_cComment;
	CString	m_sTime;
	CString	m_sDataTime;
	CString	m_sChannel;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnFolder();
	afx_msg void OnWaveFile();
	afx_msg void OnSelchangeFolderList();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
