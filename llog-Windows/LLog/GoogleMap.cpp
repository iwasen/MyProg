// GoogleMap.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "GoogleMap.h"
#include "afxinet.h"


// CGoogleMap

IMPLEMENT_DYNAMIC(CGoogleMap, CStatic)


BEGIN_MESSAGE_MAP(CGoogleMap, CStatic)
END_MESSAGE_MAP()



// CGoogleMap メッセージ ハンドラ


void CGoogleMap::DispMap(double fLat, double fLon, CArrayGpsData *pArrayGpsData)
{
	Clear();

	CRect rectClinet;
	GetClientRect(rectClinet);
	CSize sizeMap = rectClinet.Size();

    CString sFileName;
	sFileName.Format("/maps/api/staticmap?size=%dx%d&sensor=false&language=ja&key=AIzaSyDOQuKNtuoma_R8LqSg7hM5EK70lLJXGG0", sizeMap.cx, sizeMap.cy);

	CString sTemp;
	if (fLat != 0 && fLon != 0) {
		sTemp.Format("&center=%f,%f&markers=color:red|%f,%f", fLat, fLon, fLat, fLon);
		sFileName += sTemp;
	}

	if (pArrayGpsData != NULL) {
		int nSize = pArrayGpsData->GetSize();
		double fStep;
		int nCount;
		if (nSize <= 50) {
			fStep = 1;
			nCount = nSize;
		} else {
			fStep = (double)(nSize - 1) / 49;
			nCount = 50;
		}

		sFileName += "&path=color:0xff00aaaa|weight:4";
		for (int i = 0; i < nCount; i++) {
			int nIndex = (int)(fStep * i);
			sTemp.Format("|%f,%f", pArrayGpsData->ElementAt(nIndex).fLat, pArrayGpsData->ElementAt(nIndex).fLon);
			sFileName += sTemp;
		}
	}

    try {
        CInternetSession session("HttpGet - simple http client");
        CHttpConxxxtion* conn = session.GetHttpConxxxtion(
                                    "maps.googleapis.com",
                                    CHttpConxxxtion::HTTP_VERB_GET,
                                    80, NULL, NULL);
        CHttpFile* file = conn->OpenRequest(
                              CHttpConxxxtion::HTTP_VERB_GET, sFileName,
                              NULL, 1, NULL, NULL,
                              INTERNET_FLAG_RELOAD | INTERNET_FLAG_DONT_CACHE);
        file->SendRequest();

        DWORD statusCode;
        file->QueryInfoStatusCode(statusCode);
        if (statusCode >= 300)
            return;

#define BUF_SIZE 500000
        TCHAR buf[BUF_SIZE];
        int nRead = file->Read(buf, BUF_SIZE);

        file->Close();
        delete file;
        conn->Close();
        delete conn;
        session.Close();

		HGLOBAL hGlobal;
		hGlobal = ::GlobalAlloc(GMEM_MOVEABLE, nRead);
		void *p = ::GlobalLock(hGlobal);
		memcpy(p, buf, nRead);
		::GlobalUnlock(hGlobal);

		IStream *pStream;
		::CreateStreamOnHGlobal(hGlobal, TRUE, &pStream);
		m_oImage.Load(pStream);
		pStream->Release();

    } catch (CInternetException* ex) {
        TCHAR szMsg[1024];
        ex->GetErrorMessage(szMsg, 1024);
        ::AfxMessageBox(szMsg, MB_OK | MB_ICONEXCLAMATION);
        ex->Delete();
    }
}

void CGoogleMap::Clear()
{
	if (!m_oImage.IsNull())
		::DeleteObject(m_oImage.Detach());

	Invalidate();
}

void CGoogleMap::DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct)
{
	if (!m_oImage.IsNull())
		m_oImage.Draw(lpDrawItemStruct->hDC, lpDrawItemStruct->rcItem);
	else
		::FillRect(lpDrawItemStruct->hDC, &lpDrawItemStruct->rcItem, (HBRUSH)::GetStockObject(GRAY_BRUSH));
}
