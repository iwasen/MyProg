#if !defined(AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_)
#define AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// KatashikiKiseiMatrixDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CKatashikiKiseiMatrixDlg �_�C�A���O

class CKatashikiKiseiMatrixDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CKatashikiKiseiMatrixDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CKatashikiKiseiMatrixDlg)
	enum { IDD = IDD_KTASHIKI_KISEI_MATRIX };
	CMyButton	m_cButtonOK;
	//}}AFX_DATA
	CMyButton	m_aCheckKatashiki[MAX_KISHU];


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CKatashikiKiseiMatrixDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CKatashikiKiseiMatrixDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_)
