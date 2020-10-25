#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		Dialog.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDialogEx �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.12.04 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "SpreadEx.h"
#include "MyCtrl.h"
#include "DataCheck.h"
#include "LixxxtrlEx.h"
#include "MenuDispCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CDialogEx �_�C�A���O

class CDialogEx : public CDialog
{
	struct SDlgCtlPosition {
		UINT nID;
		CRect rectCtl;
		double fLeft;
		double fTop;
		double fRight;
		double fBottom;
	};
	typedef CArray <SDlgCtlPosition, SDlgCtlPosition&> CDlgCtlPositionArray;

	struct SEditDataCheck {
		LPCTSTR pItemName;
		UINT nID;
		CDataCheck cDataCheck;
	};

// �R���X�g���N�V����
public:
	CDialogEx(UINT nID, CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CDialogEx)
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CDialogEx)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CDialogEx)
	virtual BOOL OnInitDialog();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnGetMinMaxInfo(MINMAXINFO FAR* lpMMI);
	afx_msg void OnSetFocus(CWnd* pNewWnd);
	//}}AFX_MSG
	afx_msg long OnExitSizeMove(WPARAM wParam, LPARAM lParam);
// Modify ... ( ADD )
	afx_msg LRESULT OnEditError(WPARAM wp, LPARAM lp);
// By Y.Itabashi (xxxxx) 2007.03.05
	DECLARE_MESSAGE_MAP()

public:
	void SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom);
	void SetMinSize(int nWidth, int nHeight);

protected:
	CDlgCtlPositionArray m_aDlgCtlPosition;
	CSize m_sizeDlg;
	CSize m_sizeMin;
	BOOL m_bSizeChange;
// Modify ... ( ADD )
	BOOL m_bCloseLock;
// By Y.Itabashi (xxxxx) 2007.03.05

	void SetSpreadColumn(CSpreadSheet &cSpread);
	void SetLixxxolumn( CLixxxtrlEx &cList, MENUCTRLDATA *MenuCtrlData, LIxxxTRLDATA *LixxxtrlData, int SelectMode );
	BOOL EditDataCheck(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName);
	BOOL EditDataCheckSub(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName);
	BOOL ComboDataCheck(CComboBox &cComboBox, LPCTSTR pItemName);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
