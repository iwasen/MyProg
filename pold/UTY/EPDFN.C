#include  "epdfn.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";
     DSET    *olad;			/* pointer to LA-data_set for out */
     int      labn;			/* target label no.		  */
     char     axinm[128];
     char     atrnm[128];
     int      trgtno;
     int      rcrdno;

dfint03()
{
     int     act=act_qfst;

     if (doopen(0)==0) {
        if (epbach==F) { trgtno=-1; rcrdno=0; }
        sprintf(doutm,"%s",dioutm);
        dd05pg03[1]=-1;
        dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
        dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
        dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
        dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
        dq13pg03[0]|=ntbl;
     }
     else {
        act=act_fini;
     }

     return(act);
}
dflst03()
{
     PD_ELM  aptr;
     int     atrb,item;
     int     act=act_nodt;

     d3rdcm(olad,"Des",&atrb,&item,&aptr);
     switch (item) {
					/* data not found mode		*/
     case 0:
        sprintf(doutn,"%s",dioutn);
        act=act_noml;
        break;
					/* data exist mode		*/
     default:
        if (listno<item) {
           sprintf(doutn,"%-20s",aptr.ptr_d[listno].name);
           if (doutn[0]=='\0') 
              sprintf(doutn,"%s",dioutn);
           act=act_noml;
        }
        else
           act=act_nodt;
        break;
     }

     return(act);
}
dfque03()
{
     PD_ELM  aptr;
     char   *doutw;
     int     atrb,item,recn,err,i;
     int     act=act_qnxt;

     if (dstnum!=1) {			/* only return mode		*/
        switch (baseqn) {
        case 0:				/* configuration modify request	*/
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           for (i=0; i<item; ++i)
              if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
           if (i>=item) {
              if ((listqn>=item) || (item<=0))
                 d3incm(olad,"Des",doutn,item);
              else {
                 sprintf(atrnm,"Des$%s",aptr.ptr_d[listqn].name);
                 d3upnm(olad,atrnm,doutn);
              }
              trgtno=-1;rcrdno=0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qnxt;
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;

        case 3:
           if (trgtno>=0) {
              d3rdcm(olad,"Des",&atrb,&item,&aptr);
              if (trgtno<item) {
                 sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
                 if ((aptr.ptr_d[trgtno].mode==DS_N) ||
                     (dd05pg03[1]!=aptr.ptr_d[trgtno].mode)) {
                    while (d3dlel(olad,atrnm,1,1)==0) {}
                    switch (dd05pg03[1]) {
                    case DS_N:sprintf(doutm,"N");break;
                    case DS_L:sprintf(doutm,"L");break;
                    case DS_I:sprintf(doutm,"I");break;
                    case DS_R:sprintf(doutm,"R");break;
                    case DS_C:sprintf(doutm,"C");break;
                    }
                 }
                 ddspel(atrnm,dd05pg03[1]);
                 rcrdno=0;
                 format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qnxt;
              }
              else { act=act_eror; erno=ER_Pxxx; }
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;

        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
           if ((trgtno>=0) && (dd05pg03[1]!=DS_N)) {
              recn=rcrdno+baseqn-3;
              d3rdcm(olad,"Des",&atrb,&item,&aptr);
              if (trgtno<item) {
                 sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
                 doutw=ddoutf[baseqn-4];
                 if (dchkel(doutw,dd05pg03[1])==0) {
                    if (recn<=aptr.ptr_d[trgtno].length)
                       if (dd05pg03[1]!=DS_C)
                          err=d3upel(olad,atrnm,dd05pg03[1],1, doutw,recn);
                       else
                          err=d3upel(olad,atrnm,dd05pg03[1],1,&doutw,recn);
                    else
                       if (dd05pg03[1]!=DS_C)
                          err=d3inel(olad,atrnm,dd05pg03[1],1, doutw,aptr.ptr_d[trgtno].length);
                       else
                          err=d3inel(olad,atrnm,dd05pg03[1],1,&doutw,aptr.ptr_d[trgtno].length);
                    if (err==0) {
                       ddspel(atrnm,dd05pg03[1]);
                       format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qnxt;
                    }
                 }
                 else { act=act_eror; erno=ER_Pxxx; }
              }
              else { act=act_eror; erno=ER_Pxxx; }
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;
        case 10:
           if (rdladsd() == 0) {
              trgtno=-1;rcrdno=0;
              listqn = 0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              baseqn=0;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           } else
              act=act_eror;
           break;
        case 11:
           if (rdfiled() == 0) {
              trgtno=-1;rcrdno=0;
              listqn = 0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              baseqn=0;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           } else
              act=act_eror;
           break;
        }
     } else {
        switch (baseqn) {
        case 10:
        case 11:
           act=act_eror;
           break;
        }
     }
     return(act);
}
dftrm03()
{
     doclos();
     return(act_fini);
}
dfk0003()
{
     PD_ELM  aptr;
     int     atrb,item,recn;
     int     act=act_cont;

     switch (baseqn) {
     case 0:				/* line delete : 成分削除	*/
        if (epokno()==0) {
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           if (listqn<item) {
              sprintf(atrnm,"Des$%s",aptr.ptr_d[listqn].name);
              d3dlcm(olad,atrnm);
              trgtno=-1;rcrdno=0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           }
           else
              act=act_eror;
        }
        break;
     case 3:
        switch (slblno) {
        case 0:
           if (trgtno>=0) {
              if (epokno()==0) {
                 dd05pg03[1]=DS_L;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        case 1:
           if (trgtno>=0) {
              if (epokno()==0) {
                 dd05pg03[1]=DS_N;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 4:				/* line delete : 要素削除	*/
     case 5:
     case 6:
     case 7:
     case 8:
        if (epokno()==0) {
           if ((trgtno>=0) && (dd05pg03[1]!=DS_N)) {
              recn=rcrdno+baseqn-3;
              d3rdcm(olad,"Des",&atrb,&item,&aptr);
              if (trgtno<item) {
                 if (recn<=aptr.ptr_d[trgtno].length) {
                    sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
                    d3dlel(olad,atrnm,recn,1);
                    ddspel(atrnm,dd05pg03[1]);
                    format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
                 }
                 else
                    act=act_eror;
              }
              else
                 act=act_eror;
           }
           else
              act=act_eror;
        }
        break;
     case 9:
	if (epokno()==0) {
	   if (rdsidx() == 0) {
              trgtno=-1;rcrdno=0;
              listqn = 0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              baseqn=0;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
	   } else
	      act=act_eror;
	}
	break;
     }

     return(act);
}
dfk0103()
{
     PD_ELM  aptr;
     int     atrb,item,i;
     char   *doutw;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:
           sprintf(doutn,"-");
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           for (i=0; i<item; ++i)
              if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
           if (i>=item) {
              d3incm(olad,"Des",doutn,listqn);
              trgtno=-1;rcrdno=0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 3:
        switch (slblno) {
        case 0:
           if (trgtno>=0) {
              if (epokno()==0) {
                 dd05pg03[1]=DS_I;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 4:
     case 5:
     case 6:
     case 7:
     case 8:
        switch (slblno) {
        case 0:
           if ((trgtno>=0) && (dd05pg03[1]!=DS_N)) {
              d3rdcm(olad,"Des",&atrb,&item,&aptr);
              sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
              doutw=ddoutf[baseqn-4];
              dintel(doutw,dd05pg03[1]);
              if (dd05pg03[1]!=DS_C)
                 d3inel(olad,atrnm,dd05pg03[1],1, doutw,rcrdno+baseqn-4);
              else
                 d3inel(olad,atrnm,dd05pg03[1],1,&doutw,rcrdno+baseqn-4);
              ddspel(atrnm,dd05pg03[1]);
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 9:
        dq11pg03[0]|=ntbl;
        dq12pg03[0]&=~ntbl;
        baseqn=10;
        act=act_disp|act_qslc;
	break;
     }

     return(act);
}
dfk0203()
{
     PD_ELM  aptr;
     int     atrb,item,recn,i;
     char   *doutw;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           recn=(item>0)?listqn+1:0;
           sprintf(doutn,"-");
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           for (i=0; i<item; ++i)
              if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
           if (i>=item) {
              d3incm(olad,"Des",doutn,recn);
              trgtno=-1;rcrdno=0;
              dd05pg03[1]=-1;
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qnxt;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 3:
        switch (slblno) {
        case 0:
           if (trgtno>=0) {
              if (epokno()==0) {
                 dd05pg03[1]=DS_R;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 4:
     case 5:
     case 6:
     case 7:
     case 8:
        switch (slblno) {
        case 0:
           if ((trgtno>=0) && (dd05pg03[1]!=DS_N)) {
              d3rdcm(olad,"Des",&atrb,&item,&aptr);
              sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
              doutw=ddoutf[baseqn-4];
              dintel(doutw,dd05pg03[1]);
              if (dd05pg03[1]!=DS_C)
                 d3inel(olad,atrnm,dd05pg03[1],1, doutw,rcrdno+baseqn-3);
              else
                 d3inel(olad,atrnm,dd05pg03[1],1,&doutw,rcrdno+baseqn-3);
              ddspel(atrnm,dd05pg03[1]);
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qnxt;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 9:
        dq11pg03[0]|=ntbl;
        dq13pg03[0]&=~ntbl;
        baseqn=11;
        act=act_disp|act_qslc;
	break;
     }

     return(act);
}
dfk0303()
{
     int   act=act_cont;

     switch (baseqn) {
     case 0:
	switch (slblno) {
	case 0:
           dq11pg03[0]&=~ntbl;
           baseqn=9;
           act=act_disp|act_qslc;
	   break;
	}
	break;
     case 3:
        switch (slblno) {
        case 0:
           if (trgtno>=0) {
              if (epokno()==0) {
                 dd05pg03[1]=DS_C;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     default:
        break;
     }

     return(act);
}
dfk0403()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:				/* 前頁			*/
           epdf05();
           break;
        }
        break;
    case 4:
    case 5:
    case 6:
    case 7:
    case 8:
        switch (slblno) {
        case 0:				/* 前頁			*/
           if (trgtno>=0) {
              if (dd05pg03[1]!=DS_N) {
                 rcrdno=(rcrdno<5)?0:rcrdno-5;
                 d3rdcm(olad,"Des",&atrb,&item,&aptr);
                 sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
                 ddspel(atrnm,dd05pg03[1]);
                 baseqn=4;
                 format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     }
     return(act);
}
dfk0503()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:				/* 後頁			*/
           epdf04();
           break;
        }
        break;
     case 4:
     case 5:
     case 6:
     case 7:
     case 8:
        switch (slblno) {
        case 0:				/* 後頁			*/
           if (trgtno>=0) {
              if (dd05pg03[1]!=DS_N) {
                 d3rdcm(olad,"Des",&atrb,&item,&aptr);
                 sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
                 rcrdno=((rcrdno+4)<aptr.ptr_d[trgtno].length)?rcrdno+5:rcrdno;
                 ddspel(atrnm,dd05pg03[1]);
                 baseqn=4;
                 format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     }
     return(act);
}
dfk0603()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:				/* 属性選択		*/
           trgtno=listqn;
           d3rdcm(olad,"Des",&atrb,&item,&aptr);
           if (trgtno<item) {
              dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
              dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
              dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
              dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
              dq13pg03[0]|=ntbl;
              dq05pg03[0]^=ntbl; dq06pg03[0]^=ntbl;
              dq07pg03[0]^=ntbl; dq08pg03[0]^=ntbl;
              dq09pg03[0]^=ntbl; dq10pg03[0]^=ntbl;
              sprintf(dslcn,"%s",aptr.ptr_d[trgtno].name);
              switch ((dd05pg03[1]=aptr.ptr_d[trgtno].mode)) {
              case DS_N:sprintf(doutm,"N");break;
              case DS_L:sprintf(doutm,"L");break;
              case DS_I:sprintf(doutm,"I");break;
              case DS_R:sprintf(doutm,"R");break;
              case DS_C:sprintf(doutm,"C");break;
              }
              sprintf(atrnm,"Des$%s",aptr.ptr_d[trgtno].name);
              ddspel(atrnm,dd05pg03[1]);
              baseqn=3;
              format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           }
           break;
        }
        break;
     case 3:
     case 4:
     case 5:
     case 6:
     case 7:
     case 8:
        switch (slblno) {
        case 0:				/* 更新終了		*/
        case 1:				/* 更新終了		*/
           trgtno=-1;
           rcrdno=0;
           dd05pg03[1]=-1;
           dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
           dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
           dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
           dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
           dq13pg03[0]|=ntbl;
           baseqn=0;/*listqn=0;*/
           format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
           break;
        }
        break;
     }
     return(act);
}

/*
 * Entry point	: dfquit03
 * Comment	: Func_key f19 processes
 *			( CANCEL )
 */
dfquit03()
{
	int	act;

	switch (baseqn) {
	case 9:
	case 10:
	case 11:
		trgtno=-1;rcrdno=0;
		dd05pg03[1]=-1;
		dq05pg03[0]|=ntbl; dq06pg03[0]|=ntbl;
		dq07pg03[0]|=ntbl; dq08pg03[0]|=ntbl;
		dq09pg03[0]|=ntbl; dq10pg03[0]|=ntbl;
		dq11pg03[0]|=ntbl; dq12pg03[0]|=ntbl;
		dq13pg03[0]|=ntbl;
		format=dfmspg03;act=act_cler|act_disp|act_vfrm|act_qslc;
		break;
	default:
		act=act_term;
		break;
	}
	return( act );
}

/*
 * Entry point	: dfexit03
 * Comment	: Func_key f20 processes
 *			( CLOSE )
 */
dfexit03()
{
	dl_write = 1;
	return( act_term );
}

dfint04()
{
     PD_ELM  nptr;
     int     atrb,item,err,i;
     char   *doutw;
     int     act=act_qfst;

     if (doopen(1)==0) {
        if (epbach==F) labn=0;
        d3rdcm(olad,"Lab",&atrb,&item,&nptr);
        if (labn<item) {
           sprintf(daxis,"%s",nptr.ptr_d[labn].name);
           if (epbach==F) { trgtno=-1; rcrdno=0; }
           sprintf(doutm,"%s",dioutm);
        }
        else {
           if (epbach==F) { labn=trgtno=-1; rcrdno=0; }
           sprintf(daxis,"%s",diaxis);
           sprintf(doutm,"%s",dioutm);
        }
        dd05pg04[1]=-1;
        dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
        dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
        dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
        dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
        dq13pg04[0]|=ntbl;
     }
     else {
        act=act_fini;
     }

     return(act);
}
dflst04()
{
     PD_ELM  aptr;
     int     atrb,item;
     int     act=act_nodt;

     switch (labn) {
					/* data not found mode		*/
     case -1:
        sprintf(doutn,"%s",dioutn);
        act=act_noml;
        break;
					/* data exist mode		*/
     default:
        sprintf(axinm,"Lab$%s",daxis);
        d3rdcm(olad,axinm,&atrb,&item,&aptr);
        if (item<=0) {
           sprintf(doutn,"%s",dioutn);
           act=act_noml;
        }
        else {
           if (listno<item) {
              sprintf(doutn,"%-20s",aptr.ptr_d[listno].name);
              if (doutn[0]=='\0') 
                 sprintf(doutn,"%s",dioutn);
              act=act_noml;
           }
           else
              act=act_nodt;
        }
        break;
     }

     return(act);
}
dfque04()
{
     PD_ELM  nptr,aptr;
     char   *doutw;
     int     atrb,item,recn,err,i;
     int     act=act_qnxt;

     if (dstnum!=1) {			/* only return mode		*/
        switch (baseqn) {
        case 0:				/* configuration modify request	*/
           sprintf(daxis,"Axi%d",dd01pg04[1]);
           d3rdcm(olad,"Lab",&atrb,&item,&nptr);
           if (labn>=0) {
              for (labn=0; labn<item; ++labn)
                 if (strcmp(nptr.ptr_d[labn].name,daxis)==0) break;
           }
           else
              labn=0;
           if (labn>=item)
              d3incm(olad,"Lab",daxis,item);
           trgtno=-1;rcrdno=0;
           dd05pg04[1]=-1;
           dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
           dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
           dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
           dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
           dq13pg04[0]|=ntbl;
           format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
           break;

        case 1:				/* configuration modify request	*/
           if (labn>=0) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              for (i=0; i<item; ++i)
                 if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
              if (i>=item) {
                 if ((listqn>=item) || (item<=0))
                    d3incm(olad,axinm,doutn,item);
                 else {
                    sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[listqn].name);
                    d3upnm(olad,atrnm,doutn);
                 }
                 trgtno=-1;rcrdno=0;
                 dd05pg04[1]=-1;
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
              }
              else { act=act_eror; erno=ER_Pxxx; }
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;

        case 4:
           if ((labn>=0) && (trgtno>=0)) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              if (trgtno<item) {
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                 if ((aptr.ptr_d[trgtno].mode==DS_N) ||
                     (dd05pg04[1]!=aptr.ptr_d[trgtno].mode)) {
                    while (d3dlel(olad,atrnm,1,1)==0) {}
                    switch (dd05pg04[1]) {
                    case DS_N:sprintf(doutm,"N");break;
                    case DS_L:sprintf(doutm,"L");break;
                    case DS_I:sprintf(doutm,"I");break;
                    case DS_R:sprintf(doutm,"R");break;
                    case DS_C:sprintf(doutm,"C");break;
                    }
                 }
                 rcrdno=0;
                 ddspel(atrnm,dd05pg04[1]);
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
              }
              else { act=act_eror; erno=ER_Pxxx; }
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;

        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
           if ((labn>=0) && (trgtno>=0) && (dd05pg04[1]!=DS_N)) {
              recn=rcrdno+baseqn-4;
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              if (trgtno<item) {
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                 doutw=ddoutf[baseqn-5];
                 if (dchkel(doutw,dd05pg04[1])==0) {
                    if (recn<=aptr.ptr_d[trgtno].length)
                       if (dd05pg04[1]!=DS_C)
                          err=d3upel(olad,atrnm,dd05pg04[1],1, doutw,recn);
                       else
                          err=d3upel(olad,atrnm,dd05pg04[1],1,&doutw,recn);
                    else
                       if (dd05pg04[1]!=DS_C)
                          err=d3inel(olad,atrnm,dd05pg04[1],1, doutw,aptr.ptr_d[trgtno].length);
                       else
                          err=d3inel(olad,atrnm,dd05pg04[1],1,&doutw,aptr.ptr_d[trgtno].length);
                    if (err==0) {
                       ddspel(atrnm,dd05pg04[1]);
                       format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
                    }
                 }
                 else { act=act_eror; erno=ER_Pxxx; }
              }
              else { act=act_eror; erno=ER_Pxxx; }
           }
           else { act=act_eror; erno=ER_Pxxx; }
           break;
        case 11:
           if (rdladsl() == 0) {
              trgtno=-1;rcrdno=0;
              listqn = 0;
              trgtno=-1;rcrdno=0;
              dd05pg04[1]=-1;
              dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
              dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
              dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
              dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
              dq13pg04[0]|=ntbl;
              baseqn=0;
              format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
           } else
              act=act_eror;
           break;
        case 12:
           if (rdfilel() == 0) {
              trgtno=-1;rcrdno=0;
              listqn = 0;
              trgtno=-1;rcrdno=0;
              dd05pg04[1]=-1;
              dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
              dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
              dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
              dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
              dq13pg04[0]|=ntbl;
              baseqn=0;
              format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
           } else
              act=act_eror;
           break;
        }
     } else {
        switch (baseqn) {
        case 11:
        case 12:
           act=act_eror;
           break;
        }
     }
     return(act);
}
dftrm04()
{
     doclos();
     return(act_fini);
}
dfk0004()
{
     PD_ELM  nptr,aptr;
     int     atrb,item,recn;
     int     act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:
           dd01pg04[1]=1;act=act_ques;
           break;
        case 1:				/* line delete : 軸名削除	*/
           if (epokno()==0) {
              if (labn>=0) {
                 sprintf(axinm,"Lab$%s",daxis);
                 d3dlcm(olad,axinm);
                 d3rdcm(olad,"Lab",&atrb,&item,&nptr);
                 if (item>0) {
                    sprintf(daxis,"%s",nptr.ptr_d[(labn=0)].name);
                 }
                 else {
                    sprintf(daxis,"%s",diaxis);labn=-1;
                 }
                 trgtno=-1;rcrdno=0;
                 dd05pg04[1]=-1;
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
              else
                 act=act_eror;
           }
           break;
        }
        break;
     case 1:				/* line delete : 成分削除	*/
        if (epokno()==0) {
           if (labn>=0) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              if (listqn<item) {
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[listqn].name);
                 d3dlcm(olad,atrnm);
                 trgtno=-1;rcrdno=0;
                 dd05pg04[1]=-1;
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
              else
                 act=act_eror;
           }
           else
              act=act_eror;
        }
        break;
     case 4:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0)) {
              if (epokno()==0) {
                 dd05pg04[1]=DS_L;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        case 1:
           if ((labn>=0) && (trgtno>=0)) {
              if (epokno()==0) {
                 dd05pg04[1]=DS_N;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 5:				/* line delete : 要素削除	*/
     case 6:
     case 7:
     case 8:
     case 9:
        if (epokno()==0) {
           if ((labn>=0) && (trgtno>=0) && (dd05pg04[1]!=DS_N)) {
              recn=rcrdno+baseqn-4;
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              if (trgtno<item) {
                 if (recn<=aptr.ptr_d[trgtno].length) {
                    sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                    d3dlel(olad,atrnm,recn,1);
                    ddspel(atrnm,dd05pg04[1]);
                    format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
                 }
                 else
                    act=act_eror;
              }
              else
                 act=act_eror;
           }
           else
              act=act_eror;
        }
        break;
     }

     return(act);
}
dfk0104()
{
     PD_ELM  aptr;
     int     atrb,item,i;
     char   *doutw;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
        switch (slblno) {
        case 0:
           dd01pg04[1]=2;act=act_ques;
           break;
        }
        break;
     case 1:
        switch (slblno) {
        case 0:
           if (labn>=0) {
              sprintf(axinm,"Lab$%s",daxis);
              sprintf(doutn,"-");
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              for (i=0; i<item; ++i)
                 if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
              if (i>=item) {
                 d3incm(olad,axinm,doutn,listqn);
                 trgtno=-1;rcrdno=0;
                 dd05pg04[1]=-1;
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
              else
                 act=act_eror;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 4:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0)) {
              if (epokno()==0) {
                 dd05pg04[1]=DS_I;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 5:
     case 6:
     case 7:
     case 8:
     case 9:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0) && (dd05pg04[1]!=DS_N)) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
              doutw=ddoutf[baseqn-5];
              dintel(doutw,dd05pg04[1]);
              if (dd05pg04[1]!=DS_C)
                 d3inel(olad,atrnm,dd05pg04[1],1, doutw,rcrdno+baseqn-5);
              else
                 d3inel(olad,atrnm,dd05pg04[1],1,&doutw,rcrdno+baseqn-5);
              ddspel(atrnm,dd05pg04[1]);
              format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 10:
        dq11pg04[0]|=ntbl;
        dq12pg04[0]&=~ntbl;
        baseqn=11;
        act=act_disp|act_qslc;
	break;
     }

     return(act);
}
dfk0204()
{
     PD_ELM  aptr;
     int     atrb,item,recn,i;
     char   *doutw;
     int   act=act_cont;

     switch (baseqn) {
     case 0:
	switch (slblno) {
        case 0:
           dd01pg04[1]=3;act=act_ques;
           break;
        }
        break;
     case 1:
        switch (slblno) {
        case 0:
           if (labn>=0) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              recn=(item>0)?listqn+1:0;
              sprintf(doutn,"-");
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              for (i=0; i<item; ++i)
                 if (strcmp(aptr.ptr_d[i].name,doutn)==0) break;
              if (i>=item) {
                 d3incm(olad,axinm,doutn,recn);
                 trgtno=-1;rcrdno=0;
                 dd05pg04[1]=-1;
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
              }
              else
                 act=act_eror;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 4:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0)) {
              if (epokno()==0) {
                 dd05pg04[1]=DS_R;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 5:
     case 6:
     case 7:
     case 8:
     case 9:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0) && (dd05pg04[1]!=DS_N)) {
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
              doutw=ddoutf[baseqn-5];
              dintel(doutw,dd05pg04[1]);
              if (dd05pg04[1]!=DS_C)
                 d3inel(olad,atrnm,dd05pg04[1],1, doutw,rcrdno+baseqn-4);
              else
                 d3inel(olad,atrnm,dd05pg04[1],1,&doutw,rcrdno+baseqn-4);
              ddspel(atrnm,dd05pg04[1]);
              format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
           }
           else
              act=act_eror;
           break;
        }
        break;
     case 10:
        dq11pg04[0]|=ntbl;
        dq13pg04[0]&=~ntbl;
        baseqn=12;
        act=act_disp|act_qslc;
	break;
     }

     return(act);
}
dfk0304()
{
     int   act=act_cont;

     switch (baseqn) {
     case 0:
	switch (slblno) {
	case 1:
           dq11pg04[0]&=~ntbl;
           baseqn=10;
           act=act_disp|act_qslc;
	   break;
	}
	break;
     case 4:
        switch (slblno) {
        case 0:
           if ((labn>=0) && (trgtno>=0)) {
              if (epokno()==0) {
                 dd05pg04[1]=DS_C;act=act_ques;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     default:
        break;
     }

     return(act);
}
dfk0404()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 1:
        switch (slblno) {
        case 0:				/* 前頁			*/
           epdf05();
           break;
        }
        break;
    case 5:
    case 6:
    case 7:
    case 8:
    case 9:
        switch (slblno) {
        case 0:				/* 前頁			*/
           if ((labn>=0) && (trgtno>=0)) {
              if (dd05pg04[1]!=DS_N) {
                 rcrdno=(rcrdno<5)?0:rcrdno-5;
                 sprintf(axinm,"Lab$%s",daxis);
                 d3rdcm(olad,axinm,&atrb,&item,&aptr);
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                 ddspel(atrnm,dd05pg04[1]);
                 baseqn=5;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     }
     return(act);
}
dfk0504()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 1:
        switch (slblno) {
        case 0:				/* 後頁			*/
           epdf04();
           break;
        }
        break;
     case 5:
     case 6:
     case 7:
     case 8:
     case 9:
        switch (slblno) {
        case 0:				/* 後頁			*/
           if ((labn>=0) && (trgtno>=0)) {
              if (dd05pg04[1]!=DS_N) {
                 sprintf(axinm,"Lab$%s",daxis);
                 d3rdcm(olad,axinm,&atrb,&item,&aptr);
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                 rcrdno=((rcrdno+4)<aptr.ptr_d[trgtno].length)?rcrdno+5:rcrdno;
                 ddspel(atrnm,dd05pg04[1]);
                 baseqn=5;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
           }
           else
              act=act_eror;
           break;
        }
        break;
     }
     return(act);
}
dfk0604()
{
     PD_ELM  aptr;
     int     atrb,item;
     int   act=act_cont;

     switch (baseqn) {
     case 1:
        switch (slblno) {
        case 0:				/* 属性選択		*/
           if (labn>=0) {
              trgtno=listqn;
              sprintf(axinm,"Lab$%s",daxis);
              d3rdcm(olad,axinm,&atrb,&item,&aptr);
              if (trgtno<item) {
                 dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
                 dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
                 dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
                 dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
                 dq13pg04[0]|=ntbl;
                 dq05pg04[0]^=ntbl; dq06pg04[0]^=ntbl;
                 dq07pg04[0]^=ntbl; dq08pg04[0]^=ntbl;
                 dq09pg04[0]^=ntbl; dq10pg04[0]^=ntbl;
                 sprintf(dslcn,"%s",aptr.ptr_d[trgtno].name);
                 switch ((dd05pg04[1]=aptr.ptr_d[trgtno].mode)) {
                 case DS_N:sprintf(doutm,"N");break;
                 case DS_L:sprintf(doutm,"L");break;
                 case DS_I:sprintf(doutm,"I");break;
                 case DS_R:sprintf(doutm,"R");break;
                 case DS_C:sprintf(doutm,"C");break;
                 }
                 sprintf(atrnm,"%s$%s",axinm,aptr.ptr_d[trgtno].name);
                 ddspel(atrnm,dd05pg04[1]);
                 baseqn=4;
                 format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
              }
              else
                 act=act_eror;
           }
           break;
        }
        break;
     case 4:
     case 5:
     case 6:
     case 7:
     case 8:
     case 9:
        switch (slblno) {
        case 0:				/* 更新終了		*/
        case 1:				/* 更新終了		*/
           trgtno=-1;
           rcrdno=0;
           dd05pg04[1]=-1;
           dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
           dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
           dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
           dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
           dq13pg04[0]|=ntbl;
           baseqn=1;/*listqn=0;*/
           format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qslc;
           break;
        }
        break;
     }
     return(act);
}

/*
 * Entry point	: dfquit04
 * Comment	: Func_key f19 processes
 *			( CANCEL )
 */
dfquit04()
{
	int	act;

	switch (baseqn) {
	case 10:
	case 11:
	case 12:
		trgtno=-1;rcrdno=0;
		dd05pg04[1]=-1;
		dq05pg04[0]|=ntbl; dq06pg04[0]|=ntbl;
		dq07pg04[0]|=ntbl; dq08pg04[0]|=ntbl;
		dq09pg04[0]|=ntbl; dq10pg04[0]|=ntbl;
		dq11pg04[0]|=ntbl; dq12pg04[0]|=ntbl;
		dq13pg04[0]|=ntbl;
		format=dfmspg04;act=act_cler|act_disp|act_vfrm|act_qnxt;
		break;
	default:
		act=act_term;
		break;
	}
	return( act );
}

/*
 * Entry point	: dfexit04
 * Comment	: Func_key f20 processes
 *			( CLOSE )
 */
dfexit04()
{
	dl_write = 1;
	return( act_term );
}

dckreg(r,s,n)

     char *r,*s;
     int   n;
{
     char *ptrs,*ptre;
     int   err=-1;

     if ((ptre=(char *)regex((ptrs=(char *)regcmp(r,(char *)0)),s))!=NULL)
        if ((int)(ptre-s)>=n) err=0;
     free(ptrs);
     return(err);
}
doopen(flag)
int	flag;		/* =0 説明部, =1 ラベル部 */
{
	extern	DSET	*d3lbgn();
	char	lads_path[128];

	d3path(ep_ladir, ep_lads, lads_path);
	olad=d3lbgn(lads_path,"u+" ,0,&dl_rinf);
	if (olad != 0 && flag == 1 && d3istb(olad) == 0) /* 表データセット ? */
		dl_rinf = 1;

	return(dl_rinf);
}
doclos()
{
	if (olad!=NULL)
		dl_rinf=d3lend(olad,dl_write ? "wq" : "q",0);
	return(dl_rinf);
}
ddspel(name,atrb)

     char *name;
     int   atrb;
{
     int    i;
     PD_ELM eptr;

     for (i=0; i<5; ++i) {
        if (d3rdel(olad,name,atrb,1,&eptr,rcrdno+i+1)==0) {
           sprintf(doutf[i],"%s",dioutf);
           switch (atrb) {
           case DS_L:
              if (eptr.ptr_i[0]!=PD_NA)
                 if (eptr.ptr_i[0]==0)
                    sprintf(doutf[i],".FALSE.");
                 else
                    sprintf(doutf[i],".TRUE.");
              break;
           case DS_I:
              if (eptr.ptr_i[0]!=PD_NA)
                 sprintf(doutf[i],"%d",eptr.ptr_i[0]);
              break;
           case DS_R:
              if (eptr.ptr_i[0]!=PD_NA)
                 sprintf(doutf[i],"%g",eptr.ptr_r[0]);
              break;
           case DS_C:
              if (strcmp(eptr.ptr_c[0],"\0")!=0)
                 sprintf(doutf[i],"%s",eptr.ptr_c[0]);
              break;
           }
        }
        else
           sprintf(doutf[i],"%s",dioutf);
        elemn[i]=rcrdno+i;
    }
}
dchkel(elem,atrb)

    float *elem;
    int    atrb;
{
    int   *ielm;
    float *felm;
    char  *celm;
    int    err=0;

    celm=(char  *)elem;
    ielm=(int   *)elem;
    felm=(float *)elem;
    switch (atrb) {
    case DS_L:
       *ielm=(*celm=='F')?0:1;
       break;
    case DS_I:
       if (sscanf(elem,"%d",ielm)!=1) err=-1;
       break;
    case DS_R:
       if (sscanf(elem,"%f",felm)!=1) err=-1;
       break;
    case DS_C:
       if (sscanf(elem,"%s",celm)!=1) err=-1;
       break;
    }
    return(err);
}
dintel(elem,atrb)

    int   *elem;
    int    atrb;
{
    int    err=0;

    switch (atrb) {
    case DS_L:
       *elem=PD_NA;
       break;
    case DS_I:
       *elem=PD_NA;
       break;
    case DS_R:
       *elem=PD_NA;
       break;
    case DS_C:
       sprintf(elem,"\0");
       break;
    }
    return(err);
}

rdsidx()
{
	static	int	d_na = PD_NA;
	static	char	*d_null = "";
	DSET	*dsp;
	int	i, err, n, mode, attrib, nitems, n_elm;
	PD_ELM	*ptr;
	char	**kwp;
	char	cnm[64];
	char	sidx_path[128];
	char	templete[sizeof(TEMPLETE) * 100];
	T_TEMPLETE	*tp = (T_TEMPLETE *)templete;

	d3path(ep_ladir, ep_sidx, sidx_path);
	if ((dsp = d3lbgn(sidx_path, "r", 0, &err)) == 0)
		return(err);

	if ((err = d3tkey(dsp, &n, &kwp)) != 0) {
		d3lend(dsp, "q", 0);
		return(err);
	}

	tp->tp_cnum = n;
	for (i = 0; i < n; i++)
		tp->tp_templete[i].tp_col = i + 1;

	if ((err = d3mktp(dsp, tp)) != 0) {
		d3lend(dsp, "q", 0);
		return(err);
	}

	d3rdcm(olad, "Des", &attrib, &n_elm, &ptr);

	for (i = 1; i < n; i++) {
		sprintf(cnm, "Des$%s", kwp[i]);
		if (d3rdcm(olad, cnm, &attrib, &nitems, &ptr) != 0) {
			d3incm(olad, "Des", kwp[i], n_elm++);
			if ((mode = tp->tp_templete[i].tp_mode) == DS_C)
				d3inel(olad, cnm, mode, 1, &d_null, 0);
			else
				d3inel(olad, cnm, mode, 1, &d_na, 0);
		}
	}

	d3lend(dsp, "q", 0);

	return(0);
}

rdladsd()
{
	extern	DSET	*d3lbgn();
	extern	DMEM	*d3gcmp();
	DSET	*dsp;
	DMEM	*desp1, *desp2, *desp3;
	int	err;
	char	lads_path[128];

	/* 読み込み元のＬＡデータセット使用開始 */
	d3path(ep_ladir, dfile, lads_path);
	if ((dsp = d3lbgn(lads_path, "r1", 0, &err)) == 0)
		return(err);

	/* 読み込み元のＬＡデータセットの説明部サーチ */
	if ((desp1 = d3gcmp(dsp->ds_entry, "Des")) == 0) {
		d3lend(dsp, "q", 0);
		return(1);
	}

	/* 読み込み先のＬＡデータセットの説明部サーチ */
	if ((desp2 = d3gcmp(olad->ds_entry, "Des")) == 0) {
		d3lend(dsp, "q", 0);
		return(1);
	}

	/* 説明部コピー */
	if ((err = d3lcpy(olad->ds_memno, desp1, &desp3)) != 0) {
		d3lend(dsp, "q", 0);
		return(err);
	}

	*desp2 = *desp3;

	d3lend(dsp, "q", 0);

	return(0);
}

rdfiled()
{
	return(0);
}

rdladsl()
{
	extern	DSET	*d3lbgn();
	extern	DMEM	*d3gcmp();
	DSET	*dsp;
	DMEM	*labp1, *labp2, *labp3;
	int	err;
	char	lads_path[128];

	/* 読み込み元のＬＡデータセット使用開始 */
	d3path(ep_ladir, dfile, lads_path);
	if ((dsp = d3lbgn(lads_path, "r2", 0, &err)) == 0)
		return(err);

	/* 読み込み元のＬＡデータセットのラベル部サーチ */
	if ((labp1 = d3gcmp(dsp->ds_entry, "Lab")) == 0) {
		d3lend(dsp, "q", 0);
		return(1);
	}

	/* 読み込み先のＬＡデータセットのラベル部サーチ */
	if ((labp2 = d3gcmp(olad->ds_entry, "Lab")) == 0) {
		d3lend(dsp, "q", 0);
		return(1);
	}

	/* ラベル部コピー */
	if ((err = d3lcpy(olad->ds_memno, labp1, &labp3)) != 0) {
		d3lend(dsp, "q", 0);
		return(err);
	}

	*labp2 = *labp3;

	d3lend(dsp, "q", 0);

	return(0);
}

rdfilel()
{
	return(0);
}
