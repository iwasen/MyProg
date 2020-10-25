#pragma once

struct SGpsData {
	CTime oTime;
	double fLat;
	double fLon;
	int nAlt;
};
typedef CArray <SGpsData, SGpsData&> CArrayGpsData;

#pragma pack(2)
struct SGpsPoint {
	short nIndex;
	unsigned nSecond: 6;
	unsigned nMinute: 6;
	unsigned nHour: 5;
	unsigned nDay: 5;
	unsigned nMonth: 4;
	unsigned nYear: 6;
	int nLat;
	int nLon;
	short nAlt;
};
#pragma pack()

class CGpsData
{
public:
	void ReadGpsData(BYTE *pData, int nPointNum);
	void OutputKml(LPCTSTR pFilePath);
	BOOL FindGpsPoint(CTime oTime, double &fLat, double &fLon);
	static BYTE *ReadGpsFile(CString &sFilePath, int &nDataSize, int &nPointNum, CString &sLoggerName, CTime &oStartTime, CTime &oEndTime);

	CArrayGpsData m_oArrayGpsData;

protected:
	static SGpsPoint *ReadTk2File(CString &sFilePath, int &nPointNum, CString &sLoggerName);
	static SGpsPoint *ReadGpxFile(CString &sFilePath, int &nPointNum, CString &sLoggerName);
};
