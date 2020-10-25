#include "stdafx.h"
#include "Nms.h"
#include "mixer.h"
#include "SetData.h"
#include <mmsystem.h>

CSetData g_oSetData;
HICON g_hIcon;
CString g_sTempDir;
CSplWnd *g_pSplWnd;
CPeakLevelWnd *g_pPeakLevelWnd;
CNmsFactor *g_pNmsFactor;
CManualMode *g_pManualWnd;

#define MAX_CONTROL_ID	20
void SetInputDevice(int nInputDevice, int nInputSelector, int nInputVolume)
{
	MIXERLINE mxl;
	MIXERCONTROL mxc;
	MIXERCONTROLDETAILS_BOOLEAN mxcdb[MAX_CONTROL_ID];
	MIXERCONTROLDETAILS_LISTTEXT mxcdl[MAX_CONTROL_ID];
	DWORD i, dst, src;
	DWORD dwDxxxontrolID;
	DWORD dwMultipleItems;
	DWORD dwSrcItems;
	DWORD dwSrcRecVolID[MAX_CONTROL_ID];
	DWORD dwSrcRecMuteID;

	MixerInitialize(nInputDevice, 0);

	// �^���}�X�^�[�� LineID ���擾
	memset(&mxl, 0, sizeof(mxl));
	mxl.cbStruct = sizeof(mxl);
	mxl.dwComponentType	= MIXERLINE_COMPONENTTYPE_DST_WAVEIN;
	if (mixerGetLineInfo((HMIXEROBJ)dwMixerInDevice, &mxl, MIXER_GETLINEINFOF_COMPONENTTYPE) != MMSYSERR_NOERROR)
		return;

	// �^�����̓Z���N�^
	if (MixerGetControlsByType(dwMixerInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MUX, mxc) == MMSYSERR_NOERROR ||
			MixerGetControlsByType(dwMixerInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MIXER, mxc) == MMSYSERR_NOERROR) {
		dwDxxxontrolID = mxc.dwControlID;
		dwMultipleItems = mxc.cMultipleItems;
		if (dwMultipleItems > MAX_CONTROL_ID)
			dwMultipleItems = MAX_CONTROL_ID;

		// �Z���N�^���X�g�擾
		if (MixerGetControlList(dwMixerInDevice, dwDxxxontrolID, dwMultipleItems, mxcdl, sizeof(MIXERCONTROLDETAILS_LISTTEXT)) != MMSYSERR_NOERROR)
			return;

		// �Z���N�g��Ԏ擾
		if (MixerGetControlValue(dwMixerInDevice, dwDxxxontrolID, dwMultipleItems, mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN)) != MMSYSERR_NOERROR)
			return;

		// ���͋@��̃{�����[���� ControlID �擾
		for (i = 0; i < dwMultipleItems; i++) {
			dwSrcRecVolID[i] = MixerGetControlID(dwMixerInDevice, mxcdl[i].dwParam1, MIXERCONTROL_CONTROLTYPE_VOLUME);

			mxcdb[i].fValue = (i == (DWORD)nInputSelector) ? 1 : 0;
		}
		MixerSetControlValue(dwMixerInDevice, dwDxxxontrolID, dwMultipleItems, mxcdb, sizeof(MIXERCONTROLDETAILS_BOOLEAN));
	} else {
		dwSrcItems = mxl.cConxxxtions;
		if (dwSrcItems > MAX_CONTROL_ID)
			dwSrcItems = MAX_CONTROL_ID;
		dst = mxl.dwDestination;
		for (src = 0; src < dwSrcItems; src++) {
			// ���͋@��� LineID �擾
			memset(&mxl, 0, sizeof(mxl));
			mxl.cbStruct = sizeof(mxl);
			mxl.dwDestination = dst;
			mxl.dwSource = src;
			if (mixerGetLineInfo((HMIXEROBJ)dwMixerInDevice, &mxl, MIXER_GETLINEINFOF_SOURCE) != MMSYSERR_NOERROR)
				return;

			// ���͋@��̃{�����[���� ControlID �擾
			dwSrcRecVolID[src] = MixerGetControlID(dwMixerInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_VOLUME);
			dwSrcRecMuteID = MixerGetControlID(dwMixerInDevice, mxl.dwLineID, MIXERCONTROL_CONTROLTYPE_MUTE);

			// ���͋@��̃~���[�g��Ԑݒ�
			if (dwSrcRecMuteID != -1)
				MixerSetBoolControl(dwMixerInDevice, dwSrcRecMuteID, (src == (DWORD)nInputSelector) ? 0 : 1);
		}
	}

	MixerSetUnsignedControl(dwMixerInDevice, dwSrcRecVolID[nInputSelector], nInputVolume);
}
