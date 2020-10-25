#if !defined(AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_)
#define AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditSublineDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineDlg �_�C�A���O

class CManualEditSublineDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CManualEditSublineDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualEditSublineDlg)
	enum { IDD = IDD_MANUAL_SUBLINE };
	CButton	m_cButtonOK;
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditSublineDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditSublineDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	//}}AFX_MSG
	afx_msg void OnCheckSubline();
	DECLARE_MESSAGE_MAP()

	void EnableExecButton();
	void SetAllSublixxxheck(BOOL bCheck);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_)
