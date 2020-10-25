#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		DialogEx.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���_�C�A���O�N���X��`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************


// CDialogEx �_�C�A���O

class CDialogEx : public CDialog
{
	DECLARE_DYNAMIC(CDialogEx)

public:
	CDialogEx(UINT nID, CWnd* pParent);   // �W���R���X�g���N�^

	BOOL DoModeless(CWnd *pParent = NULL);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void OnCancel();
	virtual void OnOK();

	DECLARE_MESSAGE_MAP()
	virtual void PostNcDestroy();

protected:
	UINT m_nID;					// �_�C�A���O���\�[�XID
	BOOL m_bModeless;			// ���[�h���X�t���O
};
