#if !defined(AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_)
#define AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SubMenuDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CSubMenuDlg �_�C�A���O

class CSubMenuDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CSubMenuDlg(UINT nID, CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSubMenuDlg)
	enum { IDD = IDD_MANUAL_BAR };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSubMenuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSubMenuDlg)
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	//}}AFX_MSG
	virtual void OnOK();
	virtual void OnCancel();
	DECLARE_MESSAGE_MAP()

public:
	virtual void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_)
