#pragma once

struct WAVEFILEHDR {
	DWORD	riffID;
	DWORD	riffSize;
	DWORD	formType;
	DWORD	fmtID;
	DWORD	fmtSize;
	PCMWAVEFORMAT waveFormat;
	DWORD	dataID;
	DWORD	dataSize;
};

class CWaveFile
{
public:
	CWaveFile();
	~CWaveFile();

	BOOL Create(LPCTSTR pFileName, const PCMWAVEFORMAT *pWaveFormat);
	void Write(const double *pData, int nSamplesNum);
	void Close();
	BOOL Open(LPCTSTR pFileName, PCMWAVEFORMAT *pWaveFormat, DWORD *pDataSize);
	void Read(double *pData, int nSamplesNum);

protected:
	BOOL ReadHeader(LPPCMWAVEFORMAT pWaveFormat, DWORD *pDataSize);
	BOOL ReadChunk(DWORD ckID, void *pData, DWORD nSize);
	BOOL SearchChunk(DWORD ckID, DWORD *ckSize);

	CFile m_oWaveFile;
	int m_nDataSize;
	WAVEFILEHDR m_oWaveFileHdr;
	PCMWAVEFORMAT m_oPcmWaveFormat;
	BOOL m_bWriteDataSize;
};
