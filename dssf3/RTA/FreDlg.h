#pragma once

#include "BaseDlg.h"
#include "FreSweep.h"
#include "FreSpot.h"
#include "FreNoise.h"
#include "WaveNotify.h"
#include "MyCtrl.h"
#include "ScreenShot.h"
#include "FileIO.h"

class CFreDlg : public CBaseDlg, public IWaveNotify
{
public:
	CFreDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CFreDlg();

	void Restart();

	DbMicCalRec m_oMicCalDataL;
	DbMicCalRec m_oMicCalDataR;

protected:
	enum { IDD = IDD_FRE };

	BOOL Start();
	void Stop();
	void RedrawGraph();
	int WaveOutData(double *pData);
	BOOL WaveInData(const double *pData);
	void EnableOutputFile();
	BOOL CheckDataExist();
	BOOL CheckDataHold();
	void GetInputDevice(int *pInputDevice, int *pInputVolume);
	void SetInputDevice(int nInputDevice, int nInputVolume);
	void SetMicCalData();
	void EnableDataHoldButton();

	CComboBox m_cSamplingRate;
	BOOL m_bInitialized;
	CMyTab m_cFreTab;
	CButton m_cBtnStart;
	CMyEdit m_cElapseTime;
	CMyEdit m_cMaxLevel;
	CMyEdit m_cMinLevel;
	CButton m_cCsvOutput;
	CSpinButtonCtrl m_cMaxLevelSpin;
	CSpinButtonCtrl m_cMinLevelSpin;
	CFreSweep m_oFreSweep;
	CFreSpot m_oFreSpot;
	CFreNoise m_oFreNoise;
	class CWaveInEx *m_pWaveIn;
	class CWaveOutEx *m_pWaveOut;
	int m_iChannel;
	double m_fTimeCount;
	BOOL m_bRegist;
	BOOL m_bNoRegist;
	int m_nSamplingRate;
	int m_nChannel;
	int m_nBufSize;
	CScreenShot m_oScreenShot;
	CMyEdit m_cCalMessage;
	CButton m_cAddDataHold;
	CButton m_cDelDataHold;
	CButton m_cScreenShot;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void ChangeWaveDevice(int nInOut);
	virtual int OnWaveNotify(int nCode, LPWAVENOTIFY pWaveNotify);
	virtual BOOL OnInitDialog();
	afx_msg void OnCbnSelchangeSamplingRate();
	afx_msg void OnBnClickedChannelMono();
	afx_msg void OnBnClickedChannelStereo();
	afx_msg void OnEnChangeMaxLevel();
	afx_msg void OnEnChangeMinLevel();
	afx_msg void OnTcnSelchangeFreTab(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnBnClickedStart();
	afx_msg void OnDestroy();
	afx_msg void OnBnClickedCsvOutput();
	afx_msg void OnBnClickedScreenShot();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg void OnMove(int x, int y);
	afx_msg void OnBnClickedSetCalibration();
	afx_msg void OnBnClickedAddDataHold();
	afx_msg void OnBnClickedDelDataHold();
};
