'-- OutProc.vbs --
IIsObjectPath = "IIS://LocalHost/W3SVC"
Set IIsObject = GetObject (IIsObjectPath)
IIsObject.Put "AspAllowOutOfProcComponents", True
IIsObject.SetInfo
'-- OutProc.vbs --
