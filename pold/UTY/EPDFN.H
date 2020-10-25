
 /* ----- %Z%%M% %I% %E% %U% -----*/
#include  <stdio.h>
#include  "la_ws/include/d3libc.h"
#include  "la_ws/include/d3lmac.h"
#include  "la_ws/include/epsub.h"
/*#include  "la_ws/include/epdsm.h"*/
extern	int	epbach;
extern	char	*ep_ladir;
extern	char	*ep_lads;
extern	char	*ep_sidx;

     extern  int  dd05pg03[];
     extern  int  dq05pg03[],dq06pg03[],dq07pg03[],dq08pg03[],dq09pg03[],
                  dq10pg03[],dq11pg03[],dq12pg03[],dq13pg03[];
     extern  int  dd01pg04[],dd05pg04[];
     extern  int  dq05pg04[],dq06pg04[],dq07pg04[],dq08pg04[],dq09pg04[],
                  dq10pg04[],dq11pg04[],dq12pg04[],dq13pg04[];
     extern  char daxis[] ,doutn[] ,doutf[][61],doutm[];
     extern  char diaxis[],dioutn[],dioutf[]   ,dioutm[];
     extern  char          dslcn[] ,ddoutf[][61];
     extern  int  elemn[5];
     extern  int  dfmspg03[],dfmspg04[];
	extern	int	dl_rinf;
	extern	int	dl_write;
	extern	char	dfile[];
