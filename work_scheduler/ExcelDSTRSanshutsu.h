// ExcelDSTRSanshutsu.h: CExcelDSTRSanshutsu �N���X�̃C���^�[�t�F�C�X
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
		eColKishuName = 1,				// �@�햼
		eColHyojunJikan = 2,			// �W������
		eColTeikiTenkenJikan = 4,		// ����_������
		eColHinshitsuLoss = 5,			// �i��۽
		eColSetsubiFuguaiLoss = 7,		// �ݔ��s�۽
		eColShujukuLoss = 	8,			// �K�n۽
		eColLineTeishiLoss = 10,		// ײݒ�~۽
		eColHenseiLoss = 11,			// �Ґ�۽
		eColHukugouNoritsuBun = 13,		// �����\����
		eColKanriTeishiJikan = 14,		// �Ǘ���~����
		eColDST = 15					// DST
	};

	CExcelDSTRSanshutsu();

	BOOL AccessCheck(LPCTSTR pFolderName);
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELDSTRSANSHUTSU_H__7E1AF70B_65D8_4BD6_9F2F_1B464E947C69__INCLUDED_)
