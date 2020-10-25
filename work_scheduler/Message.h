// Message.h: CMessage クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_MESSAGE_H__830F967D_CD3C_48BF_B3F4_A9BFED75AAA2__INCLUDED_)
#define AFX_MESSAGE_H__830F967D_CD3C_48BF_B3F4_A9BFED75AAA2__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

// メッセージデータ
typedef CArrayEx2 <CString, CString&> CMessageArray;

class CMessage
{
public:
	BOOL ReadMessageFile(LPCTSTR pFolderName);
	CString GetMessage(LPCTSTR pMessageNo);

protected:
	CMessageArray m_aMessage;
};

#endif // !defined(AFX_MESSAGE_H__830F967D_CD3C_48BF_B3F4_A9BFED75AAA2__INCLUDED_)
