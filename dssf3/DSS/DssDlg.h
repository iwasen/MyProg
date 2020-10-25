// DssDlg.h : �w�b�_�[ �t�@�C��
//

#pragma once

#include "Folder.h"
#include "Initialize.h"
#include "Mmlib.h"

// CDssDlg �_�C�A���O
class CDssDlg : public CPropertySheet
{
// �R���X�g���N�V����
public:
	CDssDlg(CWnd* pParent = NULL);	// �W���R���X�g���N�^

// ����
protected:
	HICON m_hIcon;
	CFolder m_cFolder;
	CInitialize m_cInitialize;
	CMmlib m_cMmlib;

	// �������ꂽ�A���b�Z�[�W���蓖�Ċ֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()
};
