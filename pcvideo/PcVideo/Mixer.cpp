// Mixer.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "Mixer.h"

DWORD dwMixerInDevice;
DWORD dwMixerOutDevice;
DWORD dwMixerInMasterLineID;
DWORD dwMixerOutMasterLineID;

static LONG InMuteID = (DWORD)-1;
static DWORD InVolumeID = (DWORD)-1;
static LONG InMuteVal;
static DWORD InVolumeVal;

static DWORD OutMuteID = (DWORD)-1;
static DWORD OutVolumeID = (DWORD)-1;
static DWORD OutTrebleID = (DWORD)-1;
static DWORD OutBassID = (DWORD)-1;
static LONG OutMuteVal;
static DWORD OutVolumeVal;
static DWORD OutTrebleVal;
static DWORD OutBassVal;

BOOL MixerInitialize(DWORD nWaveInDevice, DWORD nWaveOutDevice)
{
	MIXERLINE mxl;

	dwMixerOutDevice = nWaveOutDevice;
	dwMixerInDevice = nWaveInDevice;

	// 出力マスターの LineID を取得
	memset(&mxl, 0, sizeof(mxl));
	mxl.cbStruct = sizeof(mxl);
	mxl.dwComponentType	= MIXERLINE_COMPONENTTYPE_DST_SPEAKERS;
	if (mixerGetLineInfo((HMIXEROBJ)dwMixerOutDevice, &mxl, MIXER_GETLINEINFOF_COMPONENTTYPE) != MMSYSERR_NOERROR)
		return FALSE;

	dwMixerOutMasterLineID = mxl.dwLineID;

	// ControlID 取得
	OutMuteID = MixerGetControlID(dwMixerOutDevice, dwMixerOutMasterLineID, MIXERCONTROL_CONTROLTYPE_MUTE);
	OutVolumeID = MixerGetControlID(dwMixerOutDevice, dwMixerOutMasterLineID, MIXERCONTROL_CONTROLTYPE_VOLUME);
	OutTrebleID = MixerGetControlID(dwMixerOutDevice, dwMixerOutMasterLineID, MIXERCONTROL_CONTROLTYPE_TREBLE);
	OutBassID = MixerGetControlID(dwMixerOutDevice, dwMixerOutMasterLineID, MIXERCONTROL_CONTROLTYPE_BASS);

	// 録音マスターの LineID を取得
	memset(&mxl, 0, sizeof(mxl));
	mxl.cbStruct = sizeof(mxl);
	mxl.dwComponentType	= MIXERLINE_COMPONENTTYPE_DST_WAVEIN;
	if (mixerGetLineInfo((HMIXEROBJ)dwMixerInDevice, &mxl, MIXER_GETLINEINFOF_COMPONENTTYPE) != MMSYSERR_NOERROR)
		return FALSE;

	dwMixerInMasterLineID = mxl.dwLineID;

	// ControlID 取得
	InMuteID = MixerGetControlID(dwMixerInDevice, dwMixerInMasterLineID, MIXERCONTROL_CONTROLTYPE_MUTE);
	InVolumeID = MixerGetControlID(dwMixerInDevice, dwMixerInMasterLineID, MIXERCONTROL_CONTROLTYPE_VOLUME);

	return TRUE;
}

void MixerSetOutput()
{
	if (OutMuteID != -1) {
		OutMuteVal = MixerGetBoolControl(dwMixerOutDevice, OutMuteID);
		MixerSetBoolControl(dwMixerOutDevice, OutMuteID, 0);
	}

	if (OutVolumeID != -1) {
		OutVolumeVal = MixerGetUnsignedControl(dwMixerOutDevice, OutVolumeID);
		MixerSetUnsignedControl(dwMixerOutDevice, OutVolumeID, 32768);
	}

	if (OutTrebleID != -1) {
		OutTrebleVal = MixerGetUnsignedControl(dwMixerOutDevice, OutTrebleID);
		MixerSetUnsignedControl(dwMixerOutDevice, OutTrebleID, 32768);
	}

	if (OutBassID != -1) {
		OutBassVal = MixerGetUnsignedControl(dwMixerOutDevice, OutBassID);
		MixerSetUnsignedControl(dwMixerOutDevice, OutBassID, 32768);
	}
}

void MixerRestoreOutput()
{
	if (OutMuteID != -1)
		MixerSetBoolControl(dwMixerOutDevice, OutMuteID, OutMuteVal);

	if (OutVolumeID != -1)
		MixerSetUnsignedControl(dwMixerOutDevice, OutVolumeID, OutVolumeVal);

	if (OutTrebleID != -1)
		MixerSetUnsignedControl(dwMixerOutDevice, OutTrebleID, OutTrebleVal);

	if (OutBassID != -1)
		MixerSetUnsignedControl(dwMixerOutDevice, OutBassID, OutBassVal);
}

void MixerSetInput()
{
	if (InMuteID != -1) {
		InMuteVal = MixerGetBoolControl(dwMixerInDevice, InMuteID);
		MixerSetBoolControl(dwMixerInDevice, InMuteID, 0);
	}

	if (InVolumeID != -1) {
		InVolumeVal = MixerGetUnsignedControl(dwMixerInDevice, InVolumeID);
		MixerSetUnsignedControl(dwMixerInDevice, InVolumeID, 32768);
	}
}

void MixerRestoreInput()
{
	if (InMuteID != -1)
		MixerSetBoolControl(dwMixerInDevice, InMuteID, InMuteVal);

	if (InVolumeID != -1)
		MixerSetUnsignedControl(dwMixerInDevice, InVolumeID, InVolumeVal);
}

DWORD MixerGetControlID(DWORD nWaveDevice, DWORD dwLineID, DWORD dwControlType)
{
	MIXERCONTROL mxc;

	if (MixerGetControlsByType(nWaveDevice, dwLineID, dwControlType, mxc) == MMSYSERR_NOERROR)
		return mxc.dwControlID;
	else
		return (DWORD)-1;
}

UINT MixerGetControlsByType(DWORD nWaveDevice, DWORD dwLineID, DWORD dwControlType, MIXERCONTROL &mxc)
{
	MIXERLIxxxONTROLS mxlc;

	memset(&mxc, 0, sizeof(&mxc));
	memset(&mxlc, 0, sizeof(mxlc));
	mxlc.cbStruct = sizeof(mxlc);
	mxlc.dwLineID = dwLineID;
	mxlc.dwControlType = dwControlType;
	mxlc.cbmxctrl = sizeof(MIXERCONTROL);
	mxlc.pamxctrl = &mxc;
	return mixerGetLixxxontrols((HMIXEROBJ)nWaveDevice, &mxlc, MIXER_GETLIxxxONTROLSF_ONEBYTYPE);
}

LONG MixerGetBoolControl(DWORD nWaveDevice, DWORD dwControlID)
{
	MIXERCONTROLDETAILS_BOOLEAN mxcdb;

	MixerGetControlValue(nWaveDevice, dwControlID, 0, &mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN));
	return mxcdb.fValue;
}

LONG MixerGetUnsignedControl(DWORD nWaveDevice, DWORD dwControlID)
{
	MIXERCONTROLDETAILS_UNSIGNED mxcdu;

	MixerGetControlValue(nWaveDevice, dwControlID, 0, &mxcdu, sizeof(MIXER_GETCONTROLDETAILSF_VALUE));
	return mxcdu.dwValue;
}

void MixerSetBoolControl(DWORD nWaveDevice, DWORD dwControlID, LONG value)
{
	MIXERCONTROLDETAILS_BOOLEAN mxcdb;

	mxcdb.fValue = value;
	MixerSetControlValue(nWaveDevice, dwControlID, 0, &mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN));
}

void MixerSetUnsignedControl(DWORD nWaveDevice, DWORD dwControlID, DWORD value)
{
	MIXERCONTROLDETAILS_UNSIGNED mxcdu;

	mxcdu.dwValue = value;
	MixerSetControlValue(nWaveDevice, dwControlID, 0, &mxcdu, sizeof(MIXERCONTROLDETAILS_UNSIGNED));
}

UINT MixerGetControlValue(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails)
{
	MIXERCONTROLDETAILS mxcd;

	memset(&mxcd, 0, sizeof(mxcd));
	mxcd.cbStruct = sizeof(mxcd);
	mxcd.dwControlID = dwControlID;
	mxcd.cChannels = 1;
	mxcd.cMultipleItems = cMultipleItems;
	mxcd.cbDetails = cbDetails;
	mxcd.paDetails = paDetails;
	return mixerGetControlDetails((HMIXEROBJ)nWaveDevice, &mxcd, MIXER_GETCONTROLDETAILSF_VALUE);
}

UINT MixerGetControlList(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails)
{
	MIXERCONTROLDETAILS mxcd;

	memset(&mxcd, 0, sizeof(mxcd));
	mxcd.cbStruct = sizeof(mxcd);
	mxcd.dwControlID = dwControlID;
	mxcd.cChannels = 1;
	mxcd.cMultipleItems = cMultipleItems;
	mxcd.cbDetails = cbDetails;
	mxcd.paDetails = paDetails;
	return mixerGetControlDetails((HMIXEROBJ)nWaveDevice, &mxcd, MIXER_GETCONTROLDETAILSF_LISTTEXT);
}

UINT MixerSetControlValue(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails)
{
	MIXERCONTROLDETAILS mxcd;

	memset(&mxcd, 0, sizeof(mxcd));
	mxcd.cbStruct = sizeof(mxcd);
	mxcd.dwControlID = dwControlID;
	mxcd.cChannels = 1;
	mxcd.cMultipleItems = cMultipleItems;
	mxcd.cbDetails = cbDetails;
	mxcd.paDetails = paDetails;
	return mixerSetControlDetails((HMIXEROBJ)nWaveDevice, &mxcd, MIXER_GETCONTROLDETAILSF_VALUE);
}
