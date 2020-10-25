#pragma once

#include "MyCtrl.h"
#include "datadatetime.h"

// CAnalyzeRangeDlg �_�C�A���O

class CAnalyzeRangeDlg : public CDialog
{
	DECLARE_DYNAMIC(CAnalyzeRangeDlg)

public:
	CAnalyzeRangeDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CAnalyzeRangeDlg();

	CDataDateTime m_dDataBegin;		// �f�[�^�J�n����
	CDataDateTime m_dDataEnd;		// �f�[�^�I������
	CDataDateTime m_dSelectBegin;	// �I��͈͊J�n����
	CDataDateTime m_dSelectEnd;		// �I��͈͏I������

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ANALYZE_RANGE };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	BOOL CheckInputData();

	DECLARE_MESSAGE_MAP()

	CEditEx m_cEditDataBegin;
	CEditEx m_cEditDataEnd;
	CEditEx m_Edit_s_yy;
	CEditEx m_Edit_s_mm;
	CEditEx m_Edit_s_dd;
	CEditEx m_Edit_s_h;
	CEditEx m_Edit_s_m;
	CEditEx m_Edit_s_s;
	CEditEx m_Edit_e_yy;
	CEditEx m_Edit_e_mm;
	CEditEx m_Edit_e_dd;
	CEditEx m_Edit_e_h;
	CEditEx m_Edit_e_m;
	CEditEx m_Edit_e_s;

protected:
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
