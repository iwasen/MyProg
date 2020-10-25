// ExcelExportGraphTemplate.h: CExcelExportGraphTemplate クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELEXPORTGRAPHTEMPLATE_H__22BE9FF7_63D4_4F9F_B932_4AFD5D9FB2A3__INCLUDED_)
#define AFX_EXCELEXPORTGRAPHTEMPLATE_H__22BE9FF7_63D4_4F9F_B932_4AFD5D9FB2A3__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelExportGraphTemplate : public CExcelFile  
{
public:

	enum ColumnNo {
		eColSublineName = 1,
			eColSagyosha = 2,
			eColSagyoNaiyo = 3,
			eColJidoShudoKotei = 4,
			eColShisei = 5,
			eColKousu = 6,
			eColBikou = 7,
			eColSublineName2 = 8,
			eColSagyosha2 = 9,
			eColSagyoNaiyo2 = 10,
			eColJidoShudoKotei2 = 11,
			eColShisei2 = 12
	};

	CString	m_sTemplateName;

	CExcelExportGraphTemplate();
	virtual ~CExcelExportGraphTemplate();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL AccessCheck2(LPCTSTR pFolderName);
	CString GetNewFileName();
	CString GetNewFileName2();
	bool IsCheckedSubline(int iSubline) { return m_aCheckedSubline[iSubline]; };
	bool IsCheckedKishu(int iKishu) { return m_aCheckedKishu[iKishu]; };
	bool IsCheckedZenkishu() { return m_bCheckedZenkishu; }
	bool IsCheckedKajuHeikin() { return m_bCheckedKajuHeikin; }
	bool IsCheckedWaritsuke() { return m_bCheckedWaritsuke; }

// Modify ... ( ADD )
	void SetExportCondition(bool* aSubline, bool* aKishu, bool bWaritsuke, bool bZenkishu, bool bKajuHeikin);

protected:
	bool m_aCheckedSubline[MAX_SUBLINE];
	bool m_aCheckedKishu[MAX_KISHU];
	bool m_bCheckedWaritsuke;
	bool m_bCheckedZenkishu;
	bool m_bCheckedKajuHeikin;
// By K.Kimura (xxxxx) 2007.06.16

};

#endif // !defined(AFX_EXCELEXPORTGRAPHTEMPLATE_H__22BE9FF7_63D4_4F9F_B932_4AFD5D9FB2A3__INCLUDED_)
