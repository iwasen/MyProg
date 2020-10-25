// UserInfo.h: CUserInfo クラスのインターフェイス
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

	CString m_sUserName;			// 氏名
	CString m_sShokuban;			// 職番
	CString m_sBusho;				// 部署
	int	m_nJokenSetteiSosa;			// 条件設定操作
	int m_nKojunKiseiIhan;			// 工順規制違反
};

#endif // !defined(AFX_USERINFO_H__E6A186EE_BE24_4B65_B811_502544576678__INCLUDED_)
