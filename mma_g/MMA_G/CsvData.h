#pragma once

#include "datafile.h"
#include "datadatetime.h"


// CCsvData �_�C�A���O

class CCsvData : public CDialog
{
	DECLARE_DYNAMIC(CCsvData)

public:
	CCsvData(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CCsvData();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_CONV_CSV };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
protected:
	// �J�n����
	CEdit m_Edit_Start;
	// �I������
	CEdit m_Edit_Endtime;
	// CSV�t�@�C�����w��
	CEdit m_Edit_FileName;
private:
	CDataFile m_cDataFile;
public:
	void DspModal(LPCSTR pFilePath, int nDataType);
private:
	CDataDateTime m_startTime;
	CDataDateTime m_endTime;
	CDataDateTime m_In_startTime;
	CDataDateTime m_In_endTime;
	CString m_sCsvPath;
	CToolTipCtrl m_tooltip;

protected:
	CEdit m_Edit_s_yy;
	CEdit m_Edit_s_mm;
	CEdit m_Edit_s_dd;
	CEdit m_Edit_s_h;
	CEdit m_Edit_s_m;
	CEdit m_Edit_s_s;
	CEdit m_Edit_e_yy;
	CEdit m_Edit_e_mm;
	CEdit m_Edit_e_dd;
	CEdit m_Edit_e_h;
	CEdit m_Edit_e_m;
	CEdit m_Edit_e_s;
	CComboBox m_cComboSampleRate;
	CComboBox m_cComboTargetData;
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonRef();
	afx_msg void OnBnClickedButtonConv();
	virtual BOOL PreTranslateMessage(MSG* pMsg);

private:
	// ���͍��ڂ��擾����
	int GetInputData(void);
};
