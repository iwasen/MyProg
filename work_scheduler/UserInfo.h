// UserInfo.h: CUserInfo �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_USERINFO_H__E6A186EE_BE24_4B65_B811_502544576678__INCLUDED_)
#define AFX_USERINFO_H__E6A186EE_BE24_4B65_B811_502544576678__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CUserInfo
{
public:
	CUserInfo();

	CString m_sUserName;			// ����
	CString m_sShokuban;			// �E��
	CString m_sBusho;				// ����
	int	m_nJokenSetteiSosa;			// �����ݒ葀��
	int m_nKojunKiseiIhan;			// �H���K���ᔽ
};

#endif // !defined(AFX_USERINFO_H__E6A186EE_BE24_4B65_B811_502544576678__INCLUDED_)
