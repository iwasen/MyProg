#if !defined(AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_)
#define AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualViewTypeDlg.h : �w�b�_�[ �t�@�C��
//

#include "DialogEx.h"

/////////////////////////////////////////////////////////////////////////////
// CManualViewTypeDlg �_�C�A���O

class CManualViewTypeDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CManualViewTypeDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualViewTypeDlg)
	enum { IDD = IDD_MANUAL_VIEW_TYPE };
	CComboBox	m_cComboView3;
	CComboBox	m_cComboView2;
	CComboBox	m_cComboView1;
	int		m_nRadioViewType;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualViewTypeDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualViewTypeDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnRadioViewType1();
	afx_msg void OnRadioViewType2();
	afx_msg void OnRadioViewType3();
	afx_msg void OnRadioViewType4();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void EnableViewType();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_)
