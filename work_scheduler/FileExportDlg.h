#if !defined(AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_)
#define AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// FileExportDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CFileExportDlg �_�C�A���O

#include "DialogEx.h"

class CFileExportDlg : public CDialogEx
{

protected:
// �R���X�g���N�V����
public:
	CFileExportDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CFileExportDlg)
	enum { IDD = IDD_FILE_EXPORT_DIALOG };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];
	CMyButton	m_aCheckKishu[MAX_KISHU];
// Modify ... ( ADD )
	bool		m_aCheckedSubline[MAX_SUBLINE];	// �T�u���C���̑I���t���O
	bool		m_aCheckedKishu[MAX_KISHU];		// �@��̑I���t���O
// By K.Kimura (xxxxx) 2007.02.16
// Modify ... ( ADD )
	CMyButton	m_bCheckWaritsuke;
	CMyButton	m_bCheckZenkishu;
	CMyButton	m_bCheckKajuHeikin;
	bool		m_bCheckedZenkishu;				// �S�@��
	bool		m_bCheckedKajuHeikin;			// ���d����
	bool		m_bCheckedWaritsuke;			// ���t��ƕ\
// By Y.Itabashi (xxxxx) 2007.02.19

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CFileExportDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

	void SetAllSublixxxheck(BOOL bCheck);


// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CFileExportDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnButtonZensentaku();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_)
