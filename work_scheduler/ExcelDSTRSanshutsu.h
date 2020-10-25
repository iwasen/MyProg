// ExcelDSTRSanshutsu.h: CExcelDSTRSanshutsu クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELDSTRSANSHUTSU_H__7E1AF70B_65D8_4BD6_9F2F_1B464E947C69__INCLUDED_)
#define AFX_EXCELDSTRSANSHUTSU_H__7E1AF70B_65D8_4BD6_9F2F_1B464E947C69__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelDSTRSanshutsu : public CExcelFile
{
public:
	enum ColumnNo {
		eColKishuName = 1,				// 機種名
		eColHyojunJikan = 2,			// 標準時間
		eColTeikiTenkenJikan = 4,		// 定期点検時間
		eColHinshitsuLoss = 5,			// 品質ﾛｽ
		eColSetsubiFuguaiLoss = 7,		// 設備不具合ﾛｽ
		eColShujukuLoss = 	8,			// 習熟ﾛｽ
		eColLineTeishiLoss = 10,		// ﾗｲﾝ停止ﾛｽ
		eColHenseiLoss = 11,			// 編成ﾛｽ
		eColHukugouNoritsuBun = 13,		// 複合能率分
		eColKanriTeishiJikan = 14,		// 管理停止時間
		eColDST = 15					// DST
	};

	CExcelDSTRSanshutsu();

	BOOL AccessCheck(LPCTSTR pFolderName);
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELDSTRSANSHUTSU_H__7E1AF70B_65D8_4BD6_9F2F_1B464E947C69__INCLUDED_)
