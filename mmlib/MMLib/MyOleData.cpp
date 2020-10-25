// MyOleData.cpp: CMyOleDataSource クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMLib.h"
#include "MyOleData.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMyOleDataSource::CMyOleDataSource()
{

}

CMyOleDataSource::~CMyOleDataSource()
{

}

BOOL CMyOleDataSource::OnRenderGlobalData(LPFORMATETC lpFormatEtc, HGLOBAL* phGlobal)
{
	DROPFILES *pDropFiles;
	int nLength;
	int i;
	LPSTR pFileName;

	switch (lpFormatEtc->cfFormat) {
	case CF_HDROP:
		if (*phGlobal == NULL) {
			if (m_TempFileName.IsEmpty()) {
				if (!::MakeTempFile(m_pItem, m_TempFileName, PICTFILE_ALL, FALSE))
					return FALSE;
			}

			if (m_TempFileName.IsEmpty())
				return FALSE;

			nLength = m_TempFileName.GetLength();
			if ((*phGlobal = ::GlobalAlloc(GMEM_MOVEABLE | GMEM_DDESHARE,
						sizeof(DROPFILES) + nLength + 1)) != NULL) {
				pDropFiles = (DROPFILES *)::GlobalLock(*phGlobal);
				pDropFiles->pFiles = sizeof(DROPFILES);
				pDropFiles->fWide = FALSE;
				pFileName = (char *)pDropFiles + sizeof(DROPFILES);
				strcpy(pFileName, m_TempFileName);
				for (i = 0; i < nLength; i++) {
					if (pFileName[i] == ';')
						pFileName[i] = '\0';
				}
				pFileName[nLength] = '\0';

				::GlobalUnlock(*phGlobal);
			}
		}
		return TRUE;
	}

	return FALSE;
}
