#ifndef __MIXER_H__
#define __MIXER_H__

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

#include <mmsystem.h>

extern DWORD dwMixerOutMasterLineID;
extern DWORD dwMixerInMasterLineID;

extern BOOL MixerInitialize(DWORD nWaveInDevice, DWORD nWaveOutDevice);
extern void MixerSetOutput();
extern void MixerRestoreOutput();
extern void MixerSetInput();
extern void MixerRestoreInput();
extern DWORD MixerGetControlID(DWORD nWaveDevice, DWORD dwLineID, DWORD dwControlType);
extern UINT MixerGetControlsByType(DWORD nWaveDevice, DWORD dwLineID, DWORD dwControlType, MIXERCONTROL &mxc);
extern LONG MixerGetBoolControl(DWORD nWaveDevice, DWORD dwControlID);
extern LONG MixerGetUnsignedControl(DWORD nWaveDevice, DWORD dwControlID);
extern void MixerSetBoolControl(DWORD nWaveDevice, DWORD dwControlID, LONG value);
extern void MixerSetUnsignedControl(DWORD nWaveDevice, DWORD dwControlID, DWORD value);
extern UINT MixerGetControlValue(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails);
extern UINT MixerGetControlList(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails);
extern UINT MixerSetControlValue(DWORD nWaveDevice, DWORD dwControlID, DWORD cMultipleItems, LPVOID paDetails, DWORD cbDetails);

#endif	// __MIXER_H__
