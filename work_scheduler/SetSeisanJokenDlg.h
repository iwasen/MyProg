#if !defined(AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_)
#define AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SetSeisanJokenDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJokenDlg �_�C�A���O

class CSetSeisanJokenDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CSetSeisanJokenDlg(UINT nID, CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetSeisanJokenDlg)
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA
	CComboBox	m_cComboMonth;
	CComboBox	m_cComboYear;
	CSpreadEx	m_cSpread;
	CLixxxtrlEx	m_cList;
	CMyEdit	m_aEditKishu[MAX_KISHU];
	CMyEdit	m_aEditDaisu[MAX_KISHU];


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetSeisanJokenDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetSeisanJokenDlg)
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	virtual void OnOK();
	DECLARE_MESSAGE_MAP()

public:
	struct SSpreadDataCheck {
		int nCol;
		CDataCheck cDataCheck;
	};

protected:
	CString m_sYear;				// �Ώ۔N
	CString m_sMonth;				// �Ώی�

	void SetYearCombo();
	void SetMonthCombo();
	void SetKishuName();
	void SetSeisanDaisu();
	BOOL CheckDataInputSub(SSpreadDataCheck aDataCheck[], int nDataCheck);
	int GetSeisanDaisu(int nSublineId);
	void SetIkoHanei(int nCol);
	void SaveYMDaisu();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_)
