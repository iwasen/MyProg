#include "StdAfx.h"
#include "GpsData.h"
#include "shlwapi.h"

#import "msxml3.dll" 

struct STk2Header {
	char pHeader[16];//="WintecLogTk2";   //16
	float f32LogVersion;                  //20
	float f32SWVersion;                   //24
	float f32HWVersion;                   //28
	unsigned short u16FlashID;            //30
	char pDeviceName1[20];                //50
	char pDeviceName2[20];                //70
	char pDeviceName3[40];                //110
	char pLocalTimeOfReading[20];         //130
	//------ Tk2 New --------------------------------
	char TrackDescription[300];           //430
	char ZoneIndex;                       //431  // 1 or -1
	unsigned char ZoneHour;               //432
	unsigned char ZoneMinite;             //433
	char pTimeToFirstPoint[27];           //460
	unsigned int TotalPoint;              //464
	unsigned int TotalTime_s;             //468
	unsigned int TotalDistance_m;         //472
	unsigned int NumberOfPushToLog;       //476
	//------------------------------------------------
	char pResever1[548];                  //1024
};

void CGpsData::ReadGpsData(BYTE *pData, int nPointNum)
{
	SGpsPoint *pGpsPoint = (SGpsPoint *)pData;

	m_oArrayGpsData.SetSize(nPointNum);
	for (int i = 0; i < nPointNum; i++) {
		SGpsPoint &oGpsPoint = pGpsPoint[i];
		SGpsData &oGpsData = m_oArrayGpsData[i];

		oGpsData.oTime = CTime(oGpsPoint.nYear + 2000, oGpsPoint.nMonth, oGpsPoint.nDay, oGpsPoint.nHour, oGpsPoint.nMinute, oGpsPoint.nSecond);
		oGpsData.oTime += CTimeSpan(0, 9, 0, 0);
		oGpsData.fLat = oGpsPoint.nLat / 10000000.0;
		oGpsData.fLon = oGpsPoint.nLon / 10000000.0;
		oGpsData.nAlt = oGpsPoint.nAlt;
	}
}

void CGpsData::OutputKml(LPCTSTR pFilePath)
{
	MSXML2::IXMLDOMDocumentPtr pDoc;

	pDoc.CreateInstance(CLSID_DOMDocument);

	pDoc->loadXML(
L"<kml xmlns=\"http://earth.google.com/kml/2.1\">"
L"<Document>"
L"<name></name>"
L"<visibility>1</visibility>"
L"<Folder>"
L"<name>Track</name>"
L"<Placemark>"
L"<name>Track information</name>"
L"<Style>"
L"<Icon>"
L"<href>root://icons/palette-2.png</href>"
L"<x>0</x><y>128</y><w>32</w><h>32</h>"
L"</Icon>"
L"<LineStyle>"
L"<color>aaAA00FF</color>"
L"<width>5</width>"
L"</LineStyle>"
L"</Style>"
L"<LineString>"
L"<tessellate>1</tessellate>"
L"<coordinates>"
L"</coordinates>"
L"</LineString>"
L"</Placemark>"
L"</Folder>"
L"</Document>"
L"</kml>"
);

	MSXML2::IXMLDOMNodePtr pNameNode = pDoc->getElementsByTagName(L"name")->Getitem(0);
	MSXML2::IXMLDOMNodePtr pCoordinatesNode = pDoc->getElementsByTagName(L"coordinates")->Getitem(0);

	CString sFileName = ::PathFindFileName(pFilePath);
	sFileName = sFileName.Left(sFileName.Find('.'));
	pNameNode->appendChild(pDoc->createTextNode(_bstr_t(sFileName)));

	CString sText;
	int nSize = m_oArrayGpsData.GetSize();
	for (int i = 0; i < nSize; i++) {
		sText.Format("\n%.7f,%.7f,%d", m_oArrayGpsData[i].fLon, m_oArrayGpsData[i].fLat, m_oArrayGpsData[i].nAlt);
		pCoordinatesNode->appendChild(pDoc->createTextNode(_bstr_t(sText)));
	}

	IStreamPtr str;
	::SHCreateStreamOnFile(
		pFilePath,
		STGM_READWRITE | STGM_SHARE_DENY_WRITE | STGM_CREATE,
		&str
	);

	MSXML2::IMXWriterPtr wrt;
	wrt.CreateInstance("MSXML2.MXXMLWriter");
	wrt->put_version(L"1.0");
	wrt->put_encoding(L"UTF-8");
	wrt->put_indent(VARIANT_TRUE);
	wrt->put_output(_variant_t((IUnknown*)(IUnknownPtr)str));

	MSXML2::ISAXXMLReaderPtr rdr;
	rdr.CreateInstance("MSXML2.SAXXMLReader");
	rdr->putContentHandler((MSXML2::ISAXContentHandlerPtr)wrt);
	rdr->parse(_variant_t((IUnknown*)(IUnknownPtr)pDoc));

	//pDoc->save(_variant_t(pFilePath));
}

BOOL CGpsData::FindGpsPoint(CTime oTime, double &fLat, double &fLon)
{
	int nSize = m_oArrayGpsData.GetSize();
	if (nSize == 0)
		return FALSE;

	if (oTime < m_oArrayGpsData[0].oTime)
		return FALSE;

	for (int i = 1; i < nSize; i++) {
		SGpsData &oGpsData1 = m_oArrayGpsData[i - 1];
		SGpsData &oGpsData2 = m_oArrayGpsData[i];

		if (oTime < oGpsData2.oTime) {
			double fRatio = (double)(oTime - oGpsData1.oTime).GetTimeSpan() / (double)(oGpsData2.oTime - oGpsData1.oTime).GetTimeSpan();
			fLat = oGpsData1.fLat + (oGpsData2.fLat - oGpsData1.fLat) * fRatio;
			fLon = oGpsData1.fLon + (oGpsData2.fLon - oGpsData1.fLon) * fRatio;
			return TRUE;
		}
	}

	return FALSE;
}

BYTE *CGpsData::ReadGpsFile(CString &sFilePath, int &nDataSize, int &nPointNum, CString &sLoggerName, CTime &oStartTime, CTime &oEndTime)
{
	SGpsPoint *pGpsPoint;

	if (sFilePath.Right(3).CompareNoCase("tk2") == 0)
		pGpsPoint = ReadTk2File(sFilePath, nPointNum, sLoggerName);
	else if (sFilePath.Right(3).CompareNoCase("gpx") == 0)
		pGpsPoint = ReadGpxFile(sFilePath, nPointNum, sLoggerName);
	else
		return NULL;

	// 重複データを削除
	SGpsPoint *pGpsPoint2 = new SGpsPoint[nPointNum];
	int i, j = 0;
	for (i = 0, j = 0; i < nPointNum; i++) {
		if (i != 0 && pGpsPoint[i].nLat != pGpsPoint[i - 1].nLat || pGpsPoint[i].nLon != pGpsPoint[i - 1].nLon || pGpsPoint[i].nAlt != pGpsPoint[i - 1].nAlt)
			pGpsPoint2[j++] = pGpsPoint[i];
	}
	delete [] pGpsPoint;
	pGpsPoint = pGpsPoint2;
	nPointNum = j;

	nDataSize = nPointNum * sizeof(SGpsPoint);

	oStartTime = CTime(pGpsPoint->nYear + 2000, pGpsPoint->nMonth, pGpsPoint->nDay, pGpsPoint->nHour, pGpsPoint->nMinute, pGpsPoint->nSecond);
	oStartTime += CTimeSpan(0, 9, 0, 0);

	pGpsPoint2 = &pGpsPoint[nPointNum - 1];
	oEndTime = CTime(pGpsPoint2->nYear + 2000, pGpsPoint2->nMonth, pGpsPoint2->nDay, pGpsPoint2->nHour, pGpsPoint2->nMinute, pGpsPoint2->nSecond);
	oEndTime += CTimeSpan(0, 9, 0, 0);

	return (BYTE *)pGpsPoint;
}

SGpsPoint *CGpsData::ReadTk2File(CString &sFilePath, int &nPointNum, CString &sLoggerName)
{
	CFile oFile;
	STk2Header oTk2Header;

	oFile.Open(sFilePath, CFile::modeRead | CFile::shareDenyNone);
	oFile.Read(&oTk2Header, sizeof(STk2Header));

	nPointNum = oTk2Header.TotalPoint;
	int nDataSize = nPointNum * sizeof(SGpsPoint);
	SGpsPoint *pGpsPoint = new SGpsPoint[nPointNum];
	oFile.Read(pGpsPoint, nPointNum * sizeof(SGpsPoint));

	sLoggerName = "WBT-201";

	return pGpsPoint;
}

SGpsPoint *CGpsData::ReadGpxFile(CString &sFilePath, int &nPointNum, CString &sLoggerName)
{
	MSXML2::IXMLDOMDocumentPtr pDoc;
	pDoc.CreateInstance(CLSID_DOMDocument);
	pDoc->load(_bstr_t(sFilePath));

	MSXML2::IXMLDOMNodePtr pGpxNode = pDoc->selectSingleNode(L"gpx");
	MSXML2::IXMLDOMNodePtr pCreatorNode = pGpxNode->Getattributes()->getNamedItem(L"creator");
	if (pCreatorNode->text == (_bstr_t)L"TimeMachineX - http://www.wintec.com.tw")
		sLoggerName = "WBT-201";
	else if (pCreatorNode->text == (_bstr_t)L"Mobile Action http://www.mobileaction.com/")
		sLoggerName = "GT-120";
	else
		sLoggerName = "unknown";

	MSXML2::IXMLDOMNodeListPtr pTrkptNodeList = pDoc->getElementsByTagName(L"trkpt");
	nPointNum = pTrkptNodeList->Getlength();

	int nYear, nMonth, nDay, nHour, nMinute, nSecond;
	MSXML2::IXMLDOMNodePtr pTrkptNode;
	MSXML2::IXMLDOMNamedNodeMapPtr pTrkptNodeMap;
	MSXML2::IXMLDOMNodeListPtr pNodeList;
	CString sDateTime;
	SGpsPoint *pGpsPoint = new SGpsPoint[nPointNum];
	SGpsPoint *pGpsPoint2 = pGpsPoint;
	for (int i = 0; i < nPointNum; i++, pGpsPoint2++) {
		pTrkptNode = pTrkptNodeList->Getitem(i);
		pTrkptNodeMap = pTrkptNode->Getattributes();

		pGpsPoint2->nIndex = i + 1;
		pGpsPoint2->nLat = (int)((double)pTrkptNodeMap->getNamedItem(L"lat")->GetnodeValue() * 10000000.0);
		pGpsPoint2->nLon = (int)((double)pTrkptNodeMap->getNamedItem(L"lon")->GetnodeValue() * 10000000.0);
		sscanf_s((LPCTSTR)pTrkptNode->selectSingleNode(L"time")->text, "%4d-%2d-%2dT%2d:%2d:%2dZ", &nYear, &nMonth, &nDay, &nHour, &nMinute, &nSecond);
		pGpsPoint2->nYear = nYear - 2000;
		pGpsPoint2->nMonth = nMonth;
		pGpsPoint2->nDay = nDay;
		pGpsPoint2->nHour = nHour;
		pGpsPoint2->nMinute = nMinute;
		pGpsPoint2->nSecond = nSecond;
		pGpsPoint2->nAlt = atoi(pTrkptNode->selectSingleNode(L"ele")->text);
	}

	return pGpsPoint;
}
