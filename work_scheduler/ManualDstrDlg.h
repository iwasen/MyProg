#if !defined(AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_)
#define AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualDstrDlg.h : �w�b�_�[ �t�@�C��
//

#include "DialogEx.h"
#include "ManualEditDocument.h"

/////////////////////////////////////////////////////////////////////////////
// CManualDstrDlg �_�C�A���O

class CManualDstrDlg : public CDialogEx
{
	typedef CArrayEx <SDSTRSanshutsu, SDSTRSanshutsu&> SDSTRDataArray;

// �R���X�g���N�V����
public:
	CManualDstrDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualDstrDlg)
	enum { IDD = IDD_MANUAL_DSTR };
	CComboBox	m_cComboKishu;
	double	m_fKosu;
	double	m_fHyojunJikan;
	double	m_fTeikiTenkenJikan;
	double	m_fHinshitsuLoss;
	double	m_fSetsubiFuguaiLoss;
	double	m_fShujukuLoss;
	double	m_fLineTeishiLoss;
	double	m_fHenseiLoss;
	double	m_fHukugouNoritsuBun;
	double	m_fKanriTeishiJikan;
	double	m_fDST;
	double	m_fDSTR;
	//}}AFX_DATA

	CManualEditDocument *m_pDoc;	// �h�L�������g�N���X�ւ̃|�C���^
	int m_nKishuId;					// �@��ID
	int m_bChangeEdit;				// ���͗̈�̕ύX�̎��


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualDstrDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	SDSTRDataArray m_aDstr;	// DSTR�Z�o�f�[�^

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualDstrDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboKishu();
	afx_msg void OnButtonCalc();
	virtual void OnOK();
	virtual void OnCancel();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetKishuList( int nKishu );
	void InitDstrInfo();
// Modify ... ( CHANGE )
	BOOL GetDstrInfo();
//	void GetDstrInfo();
// By Y.Itabashi (xxxxx) 2007.02.19
	void SetDstrInfo( int mode );
	double CalcKosu( SDSTRSanshutsu &pDstr );
	double CalcHyojunjikan( SDSTRSanshutsu &pDstr, int nKishuId );
	double CalcHenseiLoss( SDSTRSanshutsu &pDstr, int nKishuId );
	void OnChangeEdit( int nID ); 
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_)
