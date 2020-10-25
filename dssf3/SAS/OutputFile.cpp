// OutputFile.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "Sas.h"
#include "OutputFile.h"
#include "Calc.h"
#include "CalcAcf.h"

void OutputCsvFileIR(long nFolderID, const CString &pathName)
{
	CDbFolder dbFolder;
	DbFolderRec dbFolderRec;
	CDbImpulse dbImpulse;
	DbImpulseBuf dbImpulseBuf;
	DbImpulseRec dbImpulseRec;
	CDbAcParam dbAcParam;
	DbAcParamRec dbAcParamRec;
	DbAcParamData *pDbAcParamData = dbAcParamRec.dbAcParamData;
	int i;
	BOOL eof;
	long nRec;
	CString str, str2;
	CString strFreq;
	CString strSpl, strSplL, strSplR;
	CString strA, strAL, strAR;
	CString strTsub, strTsubL, strTsubR;
	CString strIACC, strTIACC, strWIACC;
	CString strG, strGL, strGR;
	CString strT20, strT20L, strT20R;
	CString strT30, strT30L, strT30R;
	CString strTCustom, strTCustomL, strTCustomR;
	CString strEdt, strEdtL, strEdtR;
	CString strC50, strC50L, strC50R;
	CString strC80, strC80L, strC80R;
	CString strCCustom, strCCustomL, strCCustomR;
	CString strD50, strD50L, strD50R;
	CString strTs, strTsL, strTsR;
	CString strIACCE;
	CString strIACCL;
	int n;
	double adjustSPL;
	double s0;
	HWAVEDATA hWaveData;
	PWAVEDATA pWaveData;
	int nData;

	if (!dbFolder.Open())
		return;

	if (!dbImpulse.Open())
		return;

	if (!dbAcParam.Open())
		return;

	if (!dbFolder.ReadRecID(nFolderID, &dbFolderRec))
		return;

	try {
		CStdioFile file(pathName, CFile::modeCreate | CFile::modeWrite);

		file.WriteString("[Folder]\n");
		str.Format("Title,\"%s\"\n", (LPCTSTR)dbFolderRec.sTitle);
		file.WriteString(str);
		str.Format("Name,\"%s\"\n", (LPCTSTR)dbFolderRec.sName);
		file.WriteString(str);
		str.Format("Place,\"%s\"\n", (LPCTSTR)dbFolderRec.sPlace);
		file.WriteString(str);
		str.Format("Comment,\"%s\"\n", (LPCTSTR)dbFolderRec.sComment);
		file.WriteString(str);
		str.Format("Date,\"%s\"\n", (LPCTSTR)FormatTime(dbFolderRec.sDate));
		file.WriteString(str);
		str.Format("Scale,%g\n\n", 1 / dbFolderRec.fScale);
		file.WriteString(str);

		dbImpulse.DBChgIdx(dbImpulse.m_nIdxImpulseID);
		str.Format("#%d=%ld", dbImpulse.m_nIdxFolderID, nFolderID);
		dbImpulse.DBSelect(str, &nRec);
		for (i = 0; ; i++, dbImpulse.DBSkip(1)) {
			if (dbImpulse.DBEof(&eof) != 0 || eof)
				break;

			if (dbImpulse.DBRead(&dbImpulseBuf) != 0)
				break;

			if (MMWaveReadDB(&hWaveData, dbImpulse.GetFCB(), "WAVEDATA") == 0) {
				pWaveData = (PWAVEDATA)::GlobalLock(hWaveData);
				nData = pWaveData->dataSize / pWaveData->nChannels / (pWaveData->nBitsPerSample / 8);
				::GlobalUnlock(hWaveData);
				::GlobalFree(hWaveData);
			} else
				nData = (1 << dbImpulseRec.nMeasTime);

			dbImpulse.DBGetFieldBuf(&dbImpulseBuf, &dbImpulseRec);

			str.Format("[Data%d]\n", i + 1);
			file.WriteString(str);
			str.Format("Title,\"%s\"\n", (LPCTSTR)dbImpulseRec.sTitle);
			file.WriteString(str);
			str.Format("Comment,\"%s\"\n", (LPCTSTR)dbImpulseRec.sComment);
			file.WriteString(str);
			str.Format("Time,\"%s\"\n", (LPCTSTR)FormatTime(dbImpulseRec.sTime));
			file.WriteString(str);
			str.Format("Sampling Rate,%ld\n", dbImpulseRec.nSampling);
			file.WriteString(str);
			str.Format("Measurement Time [s],%.3f\n", nData / (double)dbImpulseRec.nSampling);
			file.WriteString(str);
			str.Format("Summation,%ld\n", dbImpulseRec.nMeasNum);
			file.WriteString(str);
			str.Format("Channel,%s\n", dbImpulseRec.nChannel == 1 ? "Mono" : "Stereo");
			file.WriteString(str);
			str.Format("Bit (bit),%ld\n\n", dbImpulseRec.nBit & 0xfc);
			file.WriteString(str);

			if (!dbAcParam.ReadRecID(dbImpulseRec.nImpulseID, &dbAcParamRec))
				continue;

			adjustSPL = GetAdjustSPL(nFolderID, dbAcParamRec.dbAcParamCond.nSplRefData, dbAcParamRec.dbAcParamCond.fSplRefLevel);

			str.Format("[Parameters%d]\n", i + 1);
			file.WriteString(str);

			strFreq.Empty();
			strSpl.Empty();
			strA.Empty();
			strTsub.Empty();
			strG.Empty();
			strT20.Empty();
			strT30.Empty();
			strTCustom.Empty();
			strEdt.Empty();
			strC50.Empty();
			strC80.Empty();
			strCCustom.Empty();
			strD50.Empty();
			strTs.Empty();

			if (dbImpulseRec.nChannel == 2) {
				strSplL.Empty();
				strSplR.Empty();
				strAL.Empty();
				strAR.Empty();
				strTsubL.Empty();
				strTsubR.Empty();
				strIACC.Empty();
				strTIACC.Empty();
				strWIACC.Empty();
				strGL.Empty();
				strGR.Empty();
				strT20L.Empty();
				strT20R.Empty();
				strT30L.Empty();
				strT30R.Empty();
				strTCustomL.Empty();
				strTCustomR.Empty();
				strEdtL.Empty();
				strEdtR.Empty();
				strC50L.Empty();
				strC50R.Empty();
				strC80L.Empty();
				strC80R.Empty();
				strCCustomL.Empty();
				strCCustomR.Empty();
				strD50L.Empty();
				strD50R.Empty();
				strTsL.Empty();
				strTsR.Empty();
				strIACCE.Empty();
				strIACCL.Empty();
			}
			for (n = 0; n < dbAcParamRec.nDataNum; n++) {
				strFreq += ",";
				strSpl += ",";
				strA += ",";
				strTsub += ",";
				strG += ",";
				strT20 += ",";
				strT30 += ",";
				strTCustom += ",";
				strEdt += ",";
				strC50 += ",";
				strC80 += ",";
				strCCustom += ",";
				strD50 += ",";
				strTs += ",";
				if (dbImpulseRec.nChannel == 2) {
					strSplL += ",";
					strSplR += ",";
					strAL += ",";
					strAR += ",";
					strTsubL += ",";
					strTsubR += ",";
					strIACC += ",";
					strTIACC += ",";
					strWIACC += ",";
					strGL += ",";
					strGR += ",";
					strT20L += ",";
					strT20R += ",";
					strT30L += ",";
					strT30R += ",";
					strTCustomL += ",";
					strTCustomR += ",";
					strEdtL += ",";
					strEdtR += ",";
					strC50L += ",";
					strC50R += ",";
					strC80L += ",";
					strC80R += ",";
					strCCustomL += ",";
					strCCustomR += ",";
					strD50L += ",";
					strD50R += ",";
					strTsL += ",";
					strTsR += ",";
					strIACCE += ",";
					strIACCL += ",";
				}

				GetDispFreq(pDbAcParamData[n].nFreq, dbAcParamRec.dbAcParamCond.nFreqBand, str);
				strFreq += str;

				if (dbImpulseRec.nChannel == 1) {
					str.Format("%.2lf", pDbAcParamData[n].fSplL + adjustSPL);
					strSpl += str;

					str.Format("%.2lf", pDbAcParamData[n].fAL);
					strA += str;

					str.Format("%.3lf", pDbAcParamData[n].tSubL);
					strTsub += str;

					str.Format("%.2lf", pDbAcParamData[n].fG_L + adjustSPL);
					strG += str;

					str.Format("%.3lf", pDbAcParamData[n].fT20_L);
					strT20 += str;

					str.Format("%.3lf", pDbAcParamData[n].fT30_L);
					strT30 += str;

					str.Format("%.3lf", pDbAcParamData[n].fTCustom_L);
					strTCustom += str;

					str.Format("%.3lf", pDbAcParamData[n].fEDT_L);
					strEdt += str;

					str.Format("%.2lf", pDbAcParamData[n].fC50_L);
					strC50 += str;

					str.Format("%.2lf", pDbAcParamData[n].fC80_L);
					strC80 += str;

					str.Format("%.2lf", pDbAcParamData[n].fCCustom_L);
					strCCustom += str;

					str.Format("%.3lf", pDbAcParamData[n].fD50_L);
					strD50 += str;

					str.Format("%.3lf", pDbAcParamData[n].fTs_L);
					strTs += str;
				} else {
					str.Format("%.2lf", MeanSPL(pDbAcParamData[n].fSplL, pDbAcParamData[n].fSplR) + adjustSPL);
					strSpl += str;

					str.Format("%.2lf", pDbAcParamData[n].fSplL + adjustSPL);
					strSplL += str;

					str.Format("%.2lf", pDbAcParamData[n].fSplR + adjustSPL);
					strSplR += str;
					str.Format("%.2lf", MeanA(pDbAcParamData[n].fAL, pDbAcParamData[n].fAR));
					strA += str;

					str.Format("%.2lf", pDbAcParamData[n].fAL);
					strAL += str;

					str.Format("%.2lf", pDbAcParamData[n].fAR);
					strAR += str;

					str.Format("%.3lf", MeanTsub(pDbAcParamData[n].tSubL, pDbAcParamData[n].tSubR));
					strTsub += str;

					str.Format("%.3lf", pDbAcParamData[n].tSubL);
					strTsubL += str;

					str.Format("%.3lf", pDbAcParamData[n].tSubR);
					strTsubR += str;

					str.Format("%.2lf", pDbAcParamData[n].fIACC);
					strIACC += str;

					str.Format("%.2lf", pDbAcParamData[n].tIACC);
					strTIACC += str;

					str.Format("%.2lf", pDbAcParamData[n].wIACC);
					strWIACC += str;

					str.Format("%.2lf", MeanG(pDbAcParamData[n].fG_L, pDbAcParamData[n].fG_R) + adjustSPL);
					strG += str;

					str.Format("%.2lf", pDbAcParamData[n].fG_L + adjustSPL);
					strGL += str;

					str.Format("%.2lf", pDbAcParamData[n].fG_R + adjustSPL);
					strGR += str;

					str.Format("%.3lf", MeanT(pDbAcParamData[n].fT20_L, pDbAcParamData[n].fT20_R));
					strT20 += str;

					str.Format("%.3lf", pDbAcParamData[n].fT20_L);
					strT20L += str;

					str.Format("%.3lf", pDbAcParamData[n].fT20_R);
					strT20R += str;

					str.Format("%.3lf", MeanT(pDbAcParamData[n].fT30_L, pDbAcParamData[n].fT30_R));
					strT30 += str;

					str.Format("%.3lf", pDbAcParamData[n].fT30_L);
					strT30L += str;

					str.Format("%.3lf", pDbAcParamData[n].fT30_R);
					strT30R += str;

					str.Format("%.3lf", MeanT(pDbAcParamData[n].fTCustom_L, pDbAcParamData[n].fTCustom_R));
					strTCustom += str;

					str.Format("%.3lf", pDbAcParamData[n].fTCustom_L);
					strTCustomL += str;

					str.Format("%.3lf", pDbAcParamData[n].fTCustom_R);
					strTCustomR += str;

					str.Format("%.3lf", MeanT(pDbAcParamData[n].fEDT_L, pDbAcParamData[n].fEDT_R));
					strEdt += str;

					str.Format("%.3lf", pDbAcParamData[n].fEDT_L);
					strEdtL += str;

					str.Format("%.3lf", pDbAcParamData[n].fEDT_R);
					strEdtR += str;

					str.Format("%.2lf", MeanC(pDbAcParamData[n].fC50_L, pDbAcParamData[n].fC50_R));
					strC50 += str;

					str.Format("%.2lf", pDbAcParamData[n].fC50_L);
					strC50L += str;

					str.Format("%.2lf", pDbAcParamData[n].fC50_R);
					strC50R += str;

					str.Format("%.2lf", MeanC(pDbAcParamData[n].fC80_L, pDbAcParamData[n].fC80_R));
					strC80 += str;

					str.Format("%.2lf", pDbAcParamData[n].fC80_L);
					strC80L += str;

					str.Format("%.2lf", pDbAcParamData[n].fC80_R);
					strC80R += str;

					str.Format("%.2lf", MeanC(pDbAcParamData[n].fCCustom_L, pDbAcParamData[n].fCCustom_R));
					strCCustom += str;

					str.Format("%.2lf", pDbAcParamData[n].fCCustom_L);
					strCCustomL += str;

					str.Format("%.2lf", pDbAcParamData[n].fCCustom_R);
					strCCustomR += str;

					str.Format("%.2lf", MeanD(pDbAcParamData[n].fD50_L, pDbAcParamData[n].fD50_R));
					strD50 += str;

					str.Format("%.2lf", pDbAcParamData[n].fD50_L);
					strD50L += str;

					str.Format("%.2lf", pDbAcParamData[n].fD50_R);
					strD50R += str;

					str.Format("%.3lf", MeanTs(pDbAcParamData[n].fTs_L, pDbAcParamData[n].fTs_R));
					strTs += str;

					str.Format("%.3lf", pDbAcParamData[n].fTs_L);
					strTsL += str;

					str.Format("%.3lf", pDbAcParamData[n].fTs_R);
					strTsR += str;

					str.Format("%.2lf", pDbAcParamData[n].fIACCE);
					strIACCE += str;

					str.Format("%.2lf", pDbAcParamData[n].fIACCL);
					strIACCL += str;
				}
			}

			str.Format("Frequency band [Hz]%s\n", (LPCTSTR)strFreq);
			file.WriteString(str);

			if (dbImpulseRec.nChannel == 1) {
				str.Format("SPL [dB]%s\n", (LPCTSTR)strSpl);
				file.WriteString(str);

				str.Format("dT1 [ms],%.2lf\n", dbAcParamRec.dbAcParamResult.fDeltaT1L - dbAcParamRec.dbAcParamResult.fDeltaT0L);
				file.WriteString(str);

				str.Format("A-value%s\n", (LPCTSTR)strA);
				file.WriteString(str);

				str.Format("Tsub [s]%s\n", (LPCTSTR)strTsub);
				file.WriteString(str);

				str.Format("G [dB]%s\n", (LPCTSTR)strG);
				file.WriteString(str);

				str.Format("T20 [s]%s\n", (LPCTSTR)strT20);
				file.WriteString(str);

				str.Format("T30 [s]%s\n", (LPCTSTR)strT30);
				file.WriteString(str);

				str.Format("T_custom [s]%s\n", (LPCTSTR)strTCustom);
				file.WriteString(str);

				str.Format("EDT [s]%s\n", (LPCTSTR)strEdt);
				file.WriteString(str);

				str.Format("C50 [dB]%s\n", (LPCTSTR)strC50);
				file.WriteString(str);

				str.Format("C80 [dB]%s\n", (LPCTSTR)strC80);
				file.WriteString(str);

				str.Format("C_custom [dB]%s\n", (LPCTSTR)strCCustom);
				file.WriteString(str);

				str.Format("D50 [%%]%s\n", (LPCTSTR)strD50);
				file.WriteString(str);

				str.Format("Ts [s]%s\n", (LPCTSTR)strTs);
				file.WriteString(str);
			} else {
				str.Format("SPL-LR [dB]%s\n", (LPCTSTR)strSpl);
				file.WriteString(str);

				str.Format("SPL-L [dB]%s\n", (LPCTSTR)strSplL);
				file.WriteString(str);

				str.Format("SPL-R [dB]%s\n", (LPCTSTR)strSplR);
				file.WriteString(str);

				str.Format("dT1-LR [ms],%.2lf\n", MeanDT1(dbAcParamRec.dbAcParamResult.fDeltaT1L - dbAcParamRec.dbAcParamResult.fDeltaT0L, dbAcParamRec.dbAcParamResult.fDeltaT1R - dbAcParamRec.dbAcParamResult.fDeltaT0R));
				file.WriteString(str);

				str.Format("dT1-L [ms],%.2lf\n", dbAcParamRec.dbAcParamResult.fDeltaT1L - dbAcParamRec.dbAcParamResult.fDeltaT0L);
				file.WriteString(str);

				str.Format("dT1-R [ms],%.2lf\n", dbAcParamRec.dbAcParamResult.fDeltaT1R - dbAcParamRec.dbAcParamResult.fDeltaT0R);
				file.WriteString(str);

				str.Format("A-value-LR%s\n", (LPCTSTR)strA);
				file.WriteString(str);

				str.Format("A-value-L%s\n", (LPCTSTR)strAL);
				file.WriteString(str);

				str.Format("A-value-R%s\n", (LPCTSTR)strAR);
				file.WriteString(str);

				str.Format("Tsub-LR [s]%s\n", (LPCTSTR)strTsub);
				file.WriteString(str);

				str.Format("Tsub-L [s]%s\n", (LPCTSTR)strTsubL);
				file.WriteString(str);

				str.Format("Tsub-R [s]%s\n", (LPCTSTR)strTsubR);
				file.WriteString(str);

				str.Format("IACC%s\n", (LPCTSTR)strIACC);
				file.WriteString(str);

				str.Format("TIACC [ms]%s\n", (LPCTSTR)strTIACC);
				file.WriteString(str);

				str.Format("WIACC [ms]%s\n", (LPCTSTR)strWIACC);
				file.WriteString(str);

				if (CalcS0(&dbAcParamRec, 0, adjustSPL, &s0))
					str2.Format("%.2lf", s0);
				else
					str2 = "----";
				if (dbAcParamRec.dbAcParamCond.bAFilter) {
					if (CalcS0(&dbAcParamRec, 1, adjustSPL, &s0))
						str.Format("%.2lf", s0);
					else
						str = "----";
					str2 += "," + str;
				}
				str.Format("Preference,%s\n", (LPCTSTR)str2);
				file.WriteString(str);

				str.Format("G-LR [dB]%s\n", (LPCTSTR)strG);
				file.WriteString(str);

				str.Format("G-L [dB]%s\n", (LPCTSTR)strGL);
				file.WriteString(str);

				str.Format("G-R [dB]%s\n", (LPCTSTR)strGR);
				file.WriteString(str);

				str.Format("T20-LR [s]%s\n", (LPCTSTR)strT20);
				file.WriteString(str);

				str.Format("T20-L [s]%s\n", (LPCTSTR)strT20L);
				file.WriteString(str);

				str.Format("T20-R [s]%s\n", (LPCTSTR)strT20R);
				file.WriteString(str);

				str.Format("T30-LR [s]%s\n", (LPCTSTR)strT30);
				file.WriteString(str);

				str.Format("T30-L [s]%s\n", (LPCTSTR)strT30L);
				file.WriteString(str);

				str.Format("T30-R [s]%s\n", (LPCTSTR)strT30R);
				file.WriteString(str);

				str.Format("T_custom-LR [s]%s\n", (LPCTSTR)strTCustom);
				file.WriteString(str);

				str.Format("T_custom-L [s]%s\n", (LPCTSTR)strTCustomL);
				file.WriteString(str);

				str.Format("T_custom-R [s]%s\n", (LPCTSTR)strTCustomR);
				file.WriteString(str);

				str.Format("EDT-LR [s]%s\n", (LPCTSTR)strEdt);
				file.WriteString(str);

				str.Format("EDT-L [s]%s\n", (LPCTSTR)strEdtL);
				file.WriteString(str);

				str.Format("EDT-R [s]%s\n", (LPCTSTR)strEdtR);
				file.WriteString(str);

				str.Format("C50-LR [dB]%s\n", (LPCTSTR)strC50);
				file.WriteString(str);

				str.Format("C50-L [dB]%s\n", (LPCTSTR)strC50L);
				file.WriteString(str);

				str.Format("C50-R [dB]%s\n", (LPCTSTR)strC50R);
				file.WriteString(str);

				str.Format("C80-LR [dB]%s\n", (LPCTSTR)strC80);
				file.WriteString(str);

				str.Format("C80-L [dB]%s\n", (LPCTSTR)strC80L);
				file.WriteString(str);

				str.Format("C80-R [dB]%s\n", (LPCTSTR)strC80R);
				file.WriteString(str);

				str.Format("C_custom-LR [dB]%s\n", (LPCTSTR)strCCustom);
				file.WriteString(str);

				str.Format("C_custom-L [dB]%s\n", (LPCTSTR)strCCustomL);
				file.WriteString(str);

				str.Format("C_custom-R [dB]%s\n", (LPCTSTR)strCCustomR);
				file.WriteString(str);

				str.Format("D50-LR [%%]%s\n", (LPCTSTR)strD50);
				file.WriteString(str);

				str.Format("D50-L [%%]%s\n", (LPCTSTR)strD50L);
				file.WriteString(str);

				str.Format("D50-R [%%]%s\n", (LPCTSTR)strD50R);
				file.WriteString(str);

				str.Format("Ts-LR [s]%s\n", (LPCTSTR)strTs);
				file.WriteString(str);

				str.Format("Ts-L [s]%s\n", (LPCTSTR)strTsL);
				file.WriteString(str);

				str.Format("Ts-R [s]%s\n", (LPCTSTR)strTsR);
				file.WriteString(str);

				str.Format("IACCE%s\n", (LPCTSTR)strIACCE);
				file.WriteString(str);

				str.Format("IACCL%s\n", (LPCTSTR)strIACCL);
				file.WriteString(str);
			}
			file.WriteString("\n");
		}
	} catch (CFileException *e) {
		e->ReportError();
	}
}

void OutputCsvFileAcf(long nFolderID, const CString &pathName)
{
	CDbFolder dbFolder;
	DbFolderRec dbFolderRec;
	CDbAcf dbAcf;
	DbAcfBuf dbAcfBuf;
	DbAcfRec dbAcfRec;
	CDbAcfFactor dbAcfFactor;
	DbAcfFactorRec dbAcfFactorRec;
	AcfConditionData acfConditionData;
	AcfCondition *pAcfCondition;
	AcfFactorData *pAcfFactorData;
	int i;
	BOOL eof;
	long nRec;
	CString str, str2;
	CString strTime, strPhi0, strTauE, strTau1, strPhi1;
	CString strSPL, strIACC, strTIACC, strWIACC;
	int n;
	double adjustPhi0;
	double fTime;

	if (!dbFolder.Open())
		return;

	if (!dbAcf.Open())
		return;

	if (!dbAcfFactor.Open())
		return;

	if (!dbFolder.ReadRecID(nFolderID, &dbFolderRec))
		return;

	try {
		CStdioFile file(pathName, CFile::modeCreate | CFile::modeWrite);

		file.WriteString("[Folder]\n");
		str.Format("Title,\"%s\"\n", (LPCTSTR)dbFolderRec.sTitle);
		file.WriteString(str);
		str.Format("Name,\"%s\"\n", (LPCTSTR)dbFolderRec.sName);
		file.WriteString(str);
		str.Format("Place,\"%s\"\n", (LPCTSTR)dbFolderRec.sPlace);
		file.WriteString(str);
		str.Format("Comment,\"%s\"\n", (LPCTSTR)dbFolderRec.sComment);
		file.WriteString(str);
		str.Format("Date,\"%s\"\n", (LPCTSTR)FormatTime(dbFolderRec.sDate));
		file.WriteString(str);
		str.Format("Scale,%g\n\n", 1 / dbFolderRec.fScale);
		file.WriteString(str);

		dbAcf.DBChgIdx(dbAcf.m_nIdxAcfID);
		str.Format("#%d=%ld", dbAcf.m_nIdxFolderID, nFolderID);
		dbAcf.DBSelect(str, &nRec);
		for (i = 0; ; i++, dbAcf.DBSkip(1)) {
			if (dbAcf.DBEof(&eof) != 0 || eof)
				break;

			if (dbAcf.DBRead(&dbAcfBuf) != 0)
				break;

			dbAcf.DBGetFieldBuf(&dbAcfBuf, &dbAcfRec);

			str.Format("[Data%d]\n", i + 1);
			file.WriteString(str);
			str.Format("Title,\"%s\"\n", (LPCTSTR)dbAcfRec.sTitle);
			file.WriteString(str);
			str.Format("Comment,\"%s\"\n", (LPCTSTR)dbAcfRec.sComment);
			file.WriteString(str);
			str.Format("Time,\"%s\"\n", (LPCTSTR)FormatTime(dbAcfRec.sTime));
			file.WriteString(str);
			str.Format("Sampling Rate,%ld\n", dbAcfRec.nSampling);
			file.WriteString(str);
			str.Format("Sampling Data Number,%ld\n", dbAcfRec.nSampleNum);
			file.WriteString(str);
			str.Format("Channel,%s\n", dbAcfRec.nChannel == 1 ? "Mono" : "Stereo");
			file.WriteString(str);
			str.Format("Sampling Time,%.2lf\n\n", dbAcfRec.fDataTime);
			file.WriteString(str);

			if (!dbAcfFactor.ReadRecID(dbAcfRec.nAcfID, &dbAcfFactorRec, &acfConditionData, &pAcfFactorData))
				continue;

			pAcfCondition = &acfConditionData.AcfCondition;
			adjustPhi0 = GetAdjustPhi0(nFolderID, pAcfCondition->nPhi0RefData, pAcfCondition->fPhi0RefLevel);

			str.Format("[Factor%d]\n", i + 1);
			file.WriteString(str);

			fTime = 0;

			strTime.Empty();
			strPhi0.Empty();
			strTauE.Empty();
			strTau1.Empty();
			strPhi1.Empty();
			strSPL.Empty();
			strIACC.Empty();
			strTIACC.Empty();
			strWIACC.Empty();

			for (n = 0; n < dbAcfFactorRec.nAcfFactor; n++) {
				str.Format(",%.2f", fTime);
				fTime += pAcfCondition->fRunningStep;
				strTime += str;

				str.Format(",%.2f", pAcfFactorData[n].Acf.fPhi0 + adjustPhi0);
				strPhi0 += str;

				str.Format(",%.2f", pAcfFactorData[n].Acf.fTauE * 1000);
				strTauE += str;

				str.Format(",%.2f", pAcfFactorData[n].Acf.fTau1 * 1000);
				strTau1 += str;

				str.Format(",%.2f", pAcfFactorData[n].Acf.fPhi1);
				strPhi1 += str;

				str.Format(",%.2f", pAcfFactorData[n].IAcf.fSPL);
				strSPL += str;

				str.Format(",%.2f", pAcfFactorData[n].IAcf.fIACC);
				strIACC += str;

				str.Format(",%.2f", pAcfFactorData[n].IAcf.fTauIACC * 1000);
				strTIACC += str;

				str.Format(",%.2f", pAcfFactorData[n].IAcf.fWIACC * 1000);
				strWIACC += str;
			}

			delete [] pAcfFactorData;

			str.Format("Time [s]%s\n", (LPCTSTR)strTime);
			file.WriteString(str);

			str.Format("Phi-0 [dB]%s\n", (LPCTSTR)strPhi0);
			file.WriteString(str);

			str.Format("Tau-e [ms]%s\n", (LPCTSTR)strTauE);
			file.WriteString(str);

			str.Format("Tau-1 [ms]%s\n", (LPCTSTR)strTau1);
			file.WriteString(str);

			str.Format("Phi-1%s\n", (LPCTSTR)strPhi1);
			file.WriteString(str);

			if (dbAcfRec.nChannel == 2) {
	//			str.Format("SPL [dB]%s\n", (LPCTSTR)strSPL);
	//			file.WriteString(str);

				str.Format("IACC%s\n", (LPCTSTR)strIACC);
				file.WriteString(str);

				str.Format("TauIACC [ms]%s\n", (LPCTSTR)strTIACC);
				file.WriteString(str);

				str.Format("WIACC [ms]%s\n", (LPCTSTR)strWIACC);
				file.WriteString(str);
			}

			file.WriteString("\n");
		}
	} catch (CFileException *e) {
		e->ReportError();
	}
}

void OutputCsvFileNms(long nFolderID, const CString &pathName)
{
	CDbFolder dbFolder;
	DbFolderRec dbFolderRec;
	CDbNms dbNms;
	DbNmsRec dbNmsRec;
	NmsConditionData nmsConditionData;
	AcfCondition *pAcfCondition;
	NmsFactorData *pNmsFactorData;
	int i;
	BOOL eof;
	long nRec;
	CString str, str2;
	CString strTime, strPhi0, strTauE, strTau1, strPhi1;
	CString strSPL, strIACC, strTIACC, strWIACC;
	int n;
	double fTime;
	NoiseSrcData noiseSrcData;
	long nBytes;

	if (!dbFolder.Open())
		return;

	if (!dbNms.Open())
		return;

	if (!dbFolder.ReadRecID(nFolderID, &dbFolderRec))
		return;

	try {
		CStdioFile file(pathName, CFile::modeCreate | CFile::modeWrite);

		file.WriteString("[Folder]\n");
		str.Format("Title,\"%s\"\n", (LPCTSTR)dbFolderRec.sTitle);
		file.WriteString(str);
		str.Format("Name,\"%s\"\n", (LPCTSTR)dbFolderRec.sName);
		file.WriteString(str);
		str.Format("Place,\"%s\"\n", (LPCTSTR)dbFolderRec.sPlace);
		file.WriteString(str);
		str.Format("Comment,\"%s\"\n", (LPCTSTR)dbFolderRec.sComment);
		file.WriteString(str);
		str.Format("Date,\"%s\"\n", (LPCTSTR)FormatTime(dbFolderRec.sDate));
		file.WriteString(str);
		str.Format("Scale,%g\n\n", 1 / dbFolderRec.fScale);
		file.WriteString(str);

		dbNms.DBChgIdx(dbNms.m_nIdxNmsID);
		str.Format("#%d=%ld", dbNms.m_nIdxFolderID, nFolderID);
		dbNms.DBSelect(str, &nRec);
		for (i = 0; ; i++, dbNms.DBSkip(1)) {
			if (dbNms.DBEof(&eof) != 0 || eof)
				break;

			if (!dbNms.ReadRecID(-1, &dbNmsRec, &nmsConditionData, &pNmsFactorData, NULL))
				break;

			dbNms.DBReadBinary("NOISESRC", &noiseSrcData, sizeof(NoiseSrcData), &nBytes);

			str.Format("[Data%d]\n", dbNmsRec.nNumber + 1);
			file.WriteString(str);
			str.Format("Time,\"%s\"\n", (LPCTSTR)FormatTime(dbNmsRec.sTime));
			file.WriteString(str);
			str.Format("Sampling Rate,%ld\n", dbNmsRec.nSampling);
			file.WriteString(str);
			str.Format("Sampling Data Number,%ld\n", dbNmsRec.nSampleNum);
			file.WriteString(str);
			str.Format("Sampling Time,%.2lf\n", dbNmsRec.fDataTime);
			file.WriteString(str);

			str.Format("Noise Source,\"%s\"\n\n", noiseSrcData.aName[0] == '=' ? noiseSrcData.aName + 1 : noiseSrcData.aName);
			file.WriteString(str);

			pAcfCondition = &nmsConditionData.AcfCondition;

			str.Format("[Factor%d]\n", dbNmsRec.nNumber + 1);
			file.WriteString(str);

			fTime = 0;

			strTime.Empty();
			strPhi0.Empty();
			strTauE.Empty();
			strTau1.Empty();
			strPhi1.Empty();
			strSPL.Empty();
			strIACC.Empty();
			strTIACC.Empty();
			strWIACC.Empty();

			for (n = 0; n < dbNmsRec.nNmsFactor; n++) {
				str.Format(",%.2f", fTime);
				fTime += pAcfCondition->fRunningStep;
				strTime += str;

				str.Format(",%.2f", pNmsFactorData[n].Acf.fPhi0);
				strPhi0 += str;

				str.Format(",%.2f", pNmsFactorData[n].Acf.fTauE * 1000);
				strTauE += str;

				str.Format(",%.2f", pNmsFactorData[n].Acf.fTau1 * 1000);
				strTau1 += str;

				str.Format(",%.2f", pNmsFactorData[n].Acf.fPhi1);
				strPhi1 += str;

				str.Format(",%.2f", pNmsFactorData[n].IAcf.fSPL);
				strSPL += str;

				str.Format(",%.2f", pNmsFactorData[n].IAcf.fIACC);
				strIACC += str;

				str.Format(",%.2f", pNmsFactorData[n].IAcf.fTauIACC * 1000);
				strTIACC += str;

				str.Format(",%.2f", pNmsFactorData[n].IAcf.fWIACC * 1000);
				strWIACC += str;
			}

			delete [] pNmsFactorData;

			str.Format("Time [s]%s\n", (LPCTSTR)strTime);
			file.WriteString(str);

			str.Format("Phi-0 [dB]%s\n", (LPCTSTR)strPhi0);
			file.WriteString(str);

			str.Format("Tau-e [ms]%s\n", (LPCTSTR)strTauE);
			file.WriteString(str);

			str.Format("Tau-1 [ms]%s\n", (LPCTSTR)strTau1);
			file.WriteString(str);

			str.Format("Phi-1%s\n", (LPCTSTR)strPhi1);
			file.WriteString(str);

			str.Format("SPL [dB]%s\n", (LPCTSTR)strSPL);
			file.WriteString(str);

			str.Format("IACC%s\n", (LPCTSTR)strIACC);
			file.WriteString(str);

			str.Format("TauIACC [ms]%s\n", (LPCTSTR)strTIACC);
			file.WriteString(str);

			str.Format("WIACC [ms]%s\n", (LPCTSTR)strWIACC);
			file.WriteString(str);

			file.WriteString("\n");
		}
	} catch (CFileException *e) {
		e->ReportError();
	}
}

void OutputListToCsv(CLixxxtrl &lc, const CString &pathName)
{
	CHeaderCtrl &hc = *lc.GetHeaderCtrl();
	int nCol = hc.GetItemCount();
	int nRow = lc.GetItemCount();
	int i, j;
	HDITEM hdi;
	TCHAR  buf[256];

	try {
		CStdioFile file(pathName, CFile::modeCreate | CFile::modeWrite);

		// ヘッダ出力
		hdi.mask = HDI_TEXT;
		hdi.pszText = buf;
		hdi.cchTextMax = sizeof(buf);
		for (i = 0; i < nCol; i++) {
			hc.GetItem(i, &hdi);
			if (i != 0)
				file.WriteString(",");
			file.WriteString(buf);
		}
		file.WriteString("\n");

		// データ出力
		for (i = 0; i < nRow; i++) {
			for (j = 0; j < nCol; j++) {
				if (j != 0)
					file.WriteString(",");
				file.WriteString(lc.GetItemText(i, j));
			}
			file.WriteString("\n");
		}
	} catch (CFileException *e) {
		e->ReportError();
	}
}
