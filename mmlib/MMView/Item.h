// Item.h: CItem クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_)
#define AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CItem  
{
public:
	CItem();
	virtual ~CItem();

	BOOL m_bRead;
	CString	m_CreateDate;		// 撮影年月日時分
	CString	m_UpdateDate;		// 更新年月日時分
	CString	m_Creator;			// 作成者
	CString	m_Code;				// コード
	CString	m_Title;			// 画像タイトル
	CString	m_Remark1;			// 備考１
	CString	m_Remark2;			// 備考２
	HDIB m_hDIB1;
	HDIB m_hDIB2;
	CString m_Text;				// テキストデータ
	CRect m_IconRect;

	void ReadItem(CMmlRead *pMmlRead, int nPage);
};

#endif // !defined(AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_)
