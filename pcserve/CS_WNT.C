#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <memory.h>

#define	MAX_ID		227

typedef	struct	{
	char	id[MAX_ID][18];
	char	dummy1[4094 - MAX_ID * 18];
	long	vsn;
	short	checkSum;
} INST;

static	INST	inst;

/************************************************************************
*	インストールが正常かをチェックする
*************************************************************************/
BOOL	CheckSystem(LPSTR systemID, LPSTR subSystemID)
{
	BYTE	*p;
	int	i, j, fd, n;
	char	id[18];
	char	instFile[128];
	char	root[4];
	BOOL	rc, flag;
	long	vsn;

	rc = FALSE;

	GetWindowsDirectory(instFile, sizeof(instFile));
	lstrcat(instFile, "\\xxxx.reg");

	if ((fd = _lopen(instFile, OF_READ)) == -1)
		return FALSE;

	flag = FALSE;
	n = _lread(fd, &inst, sizeof(inst));
	if (n != sizeof(inst)) {
		_lclose(fd);
		return FALSE;
	}

	for (i = 0, p = (BYTE *)&inst; i < sizeof(inst); i++, p++)
		*p ^= 0xe5;

	for (i = 0, p = (BYTE *)&inst; i < sizeof(inst)-2; i++, p++)
		inst.checkSum -= *p;
	if (inst.checkSum == 0) {
		sprintf(root, "%c:\\", instFile[0]);
		GetVolumeInformation(root, NULL, 0, &vsn, NULL, NULL, NULL, 0);
		if (inst.vsn == vsn) {
			sprintf(id, "%s@%s", systemID, subSystemID);
			for (j = 0; j < MAX_ID; j++) {
				if (inst.id[j][0] == '\0')
					break;
				if (lstrcmp(inst.id[j], id) == 0) {
					rc = TRUE;
					break;
				}
			}
		}
	}

	_lclose(fd);

	return rc;
}
