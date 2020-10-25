#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		DigitalDataDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�W�^�����l�\���_�C�A���O�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "MyCtrl.h"
#include "DataFile.h"

// CDigitalDataDlg �_�C�A���O

class CDigitalDataDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CDigitalDataDlg)

public:
	CDigitalDataDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^

	enum { IDD = IDD_DIGITAL_DATA };

	void DispList(LPCTSTR pFilePath, int nDataType);

protected:
	void SetListHeader();
	void DispData(int nPageNo);
	void PrintData(CDC &dc, CRect rectView, LPCTSTR pTitle, CLixxxtrl &cLixxxtrl);
	void PrintInfo(CDC &dc, CRect rectView, LPCTSTR pTitle, LPCTSTR pTitle2);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnBnClickedButtonPrevPage();
	afx_msg void OnBnClickedButtonNextPage();
	afx_msg void OnCbnSelchangeComboSampleRate();
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedButtonSelectAll();

protected:
	CString m_sFilePath;
	int m_nBeginTime;
	int m_nEndTime;
	CDataFile m_cDataFile;
	int m_nPageNum;
	int m_nPageNo;
	CToolTipCtrl m_tooltip;

	CLixxxtrl m_cListData;
	CEditEx m_cEditPageNo;
	CButton m_cButtonPrevPage;
	CButton m_cButtonNextPage;
	CComboBox m_cComboSampleRate;
};
