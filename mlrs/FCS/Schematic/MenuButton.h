#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuButton.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�̃{�^���R���g���[���N���X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************


// CMenuButton

class CMenuButton : public CButton
{
	DECLARE_DYNAMIC(CMenuButton)

public:
	BOOL operator=(BOOL param);
	operator BOOL();
	LPCTSTR operator=(LPCTSTR pText);

	void SetEnable(BOOL bEnable);
	void SetCheck(BOOL bCheck);
	void SetEnableAndCheck(BOOL bEnable, BOOL bCheck = FALSE);

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg LRESULT OnSetState(WPARAM wParam, LPARAM lParam);
};
