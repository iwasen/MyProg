DIR	= PCSWIN
OBJ	= $(DIR)\pcswin.obj $(DIR)\cps.obj $(DIR)\cdb.obj $(DIR)\crf.obj\
	$(DIR)\ccf.obj $(DIR)\csn.obj $(DIR)\ccl.obj $(DIR)\cad.obj\
	$(DIR)\cgw.obj $(DIR)\cms.obj $(DIR)\ioc.obj $(DIR)\rs232c.obj\
	$(DIR)\db.obj $(DIR)\dbf.obj $(DIR)\idx.obj $(DIR)\sort.obj\
	$(DIR)\filter.obj $(DIR)\bin.obj $(DIR)\dbsub.obj $(DIR)\seqno.obj\
	$(DIR)\calndr.obj $(DIR)\pcscom.obj
OPT	= -c -ALw -Gs -J -Zp -W3 -Ox -GD -G3 -DOS_WINDOWS -DUSE_VB -DMULTI_OPEN -Fo$*.obj

pcswin.dll : $(OBJ) pcswin.def pcswin.lnk
	link /NOD /NOE /M @pcswin.lnk
	implib $*.lib $*.def

$(DIR)\pcswin.obj : pcswin.c
	cl $(OPT) pcswin.c

$(DIR)\cps.obj : cps.c
	cl $(OPT) cps.c

$(DIR)\cdb.obj : cdb.c
	cl $(OPT) cdb.c

$(DIR)\crf.obj : crf.c
	cl $(OPT) crf.c

$(DIR)\ccf.obj : ccf.c
	cl $(OPT) ccf.c

$(DIR)\csn.obj : csn.c
	cl $(OPT) csn.c

$(DIR)\ccl.obj : ccl.c
	cl $(OPT) ccl.c

$(DIR)\cad.obj : cad.c
	cl $(OPT) cad.c

$(DIR)\cgw.obj : cgw.c
	cl $(OPT) cgw.c

$(DIR)\cms.obj : cms.c
	cl $(OPT) cms.c

$(DIR)\ioc.obj : ioc.c
	cl $(OPT) ioc.c

$(DIR)\rs232c.obj : rs232c.c
	cl $(OPT) rs232c.c

$(DIR)\db.obj : db.c
	cl $(OPT) db.c

$(DIR)\dbf.obj : dbf.c
	cl $(OPT) dbf.c

$(DIR)\idx.obj : idx.c
	cl $(OPT) idx.c

$(DIR)\sort.obj : sort.c
	cl $(OPT) sort.c

$(DIR)\filter.obj : filter.c
	cl $(OPT) filter.c

$(DIR)\bin.obj : bin.c
	cl $(OPT) bin.c

$(DIR)\dbsub.obj : dbsub.c
	cl $(OPT) dbsub.c

$(DIR)\seqno.obj : seqno.c
	cl $(OPT) seqno.c

$(DIR)\calndr.obj : calndr.c
	cl $(OPT) calndr.c

$(DIR)\pcscom.obj : pcscom.c
	cl $(OPT) pcscom.c
