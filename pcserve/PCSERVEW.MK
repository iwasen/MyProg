DIR	= WINDOWS

OBJ	= $(DIR)\pcserve.obj $(DIR)\netwin.obj $(DIR)\cmd.obj $(DIR)\sdb.obj\
	$(DIR)\srfwin.obj $(DIR)\scfwin.obj $(DIR)\ssn.obj $(DIR)\scl.obj\
	$(DIR)\sad.obj $(DIR)\sgw.obj $(DIR)\seqno.obj $(DIR)\calndr.obj\
	$(DIR)\pcssub.obj $(DIR)\cs_win.obj $(DIR)\db.obj $(DIR)\dbf.obj\
	$(DIR)\idx.obj $(DIR)\sort.obj $(DIR)\filter.obj $(DIR)\dbsub.obj\
	$(DIR)\pcscom.obj $(DIR)\dpio.obj $(DIR)\sdp.obj $(DIR)\sendmail.obj

OBJ2	= $(DIR)\pcserve $(DIR)\netwin $(DIR)\cmd $(DIR)\sdb\
	$(DIR)\srfwin $(DIR)\scfwin $(DIR)\ssn $(DIR)\scl\
	$(DIR)\sad $(DIR)\sgw $(DIR)\seqno $(DIR)\calndr\
	$(DIR)\pcssub $(DIR)\cs_win $(DIR)\db $(DIR)\dbf\
	$(DIR)\idx $(DIR)\sort $(DIR)\filter $(DIR)\dbsub\
	$(DIR)\pcscom $(DIR)\dpip $(DIR)\sdp $(DIR)\sendmail

OPT	= -c -AL -Gsw -J -Zp -W3 -Ox -Fo$*.obj -DOS_WINDOWS

pcservew.exe : $(OBJ) pcservew.mk pcservew.res
	link /M @pcservew.lnk
	rc pcservew.res pcservew.exe

pcservew.res: pcservew.rc pcservew.ico
	rc -r pcservew.rc

$(DIR)\pcserve.obj : pcserve.c
	cl $(OPT) pcserve.c

$(DIR)\netwin.obj : netwin.c
	cl $(OPT) netwin.c

$(DIR)\cmd.obj : cmd.c
	cl $(OPT) cmd.c

$(DIR)\sdb.obj : sdb.c
	cl $(OPT) sdb.c

$(DIR)\srfwin.obj : srfwin.c
	cl $(OPT) srfwin.c

$(DIR)\scfwin.obj : scfwin.c
	cl $(OPT) scfwin.c

$(DIR)\ssn.obj : ssn.c
	cl $(OPT) ssn.c

$(DIR)\scl.obj : scl.c
	cl $(OPT) scl.c

$(DIR)\sad.obj : sad.c
	cl $(OPT) sad.c

$(DIR)\sgw.obj : sgw.c
	cl $(OPT) sgw.c

$(DIR)\seqno.obj : seqno.c
	cl $(OPT) seqno.c

$(DIR)\calndr.obj : calndr.c
	cl $(OPT) calndr.c

$(DIR)\pcssub.obj : pcssub.c
	cl $(OPT) pcssub.c

$(DIR)\cs_win.obj : cs_win.c
	cl $(OPT) cs_win.c

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

$(DIR)\dbsub.obj : dbsub.c
	cl $(OPT) dbsub.c

$(DIR)\pcscom.obj : pcscom.c
	cl $(OPT) pcscom.c

$(DIR)\dpio.obj : dpio.c
	cl $(OPT) dpio.c

$(DIR)\sdp.obj : sdp.c
	cl $(OPT) sdp.c

$(DIR)\sendmail.obj : sendmail.c
	cl $(OPT) sendmail.c
