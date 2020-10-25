[Setup]
AppName=Multimedia Library Viewer
AppVerName=Multimedia Library Viewer Version 1.10
AppVersion=1.10
AppCopyright=Copyright (C) 2000-2011 Yoshimasa Electronic Inc.
DefaultDirName={pf}\YMEC\MMLIBE
DefaultGroupName=Multimedia Library Manager
OutputBaseFilename=MMViewE32
LicenseFile=J:\ymec\MyDev\RTF\LicenseAgreementE.rtf
UninstallDisplayIcon={app}\MMView.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
ChangesAssociations=yes
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: "en"; MessagesFile: "compiler:Default.isl"

[Files]
Source: "..\win32\EngRel\MMView.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\MMLibE\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "J:\YMEC\MyDev\INI\YmUpdate.ini"; DestDir: "{app}"; Flags: ignoreversion

[Icons]
Name: "{group}\MMLIB Viewer"; Filename: "{app}\MMView.exe"; WorkingDir: "{app}"

[Registry]
Root: HKCR; Subkey: ".mml"; ValueType: string; ValueName: ""; ValueData: "MMLIB.File"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File"; ValueType: string; ValueName: ""; ValueData: "Multimedia Library File"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File\DefaultIcon"; ValueType: string; ValueName: ""; ValueData: "{app}\MMView.exe,0"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File\shell\open\command"; ValueType: string; ValueName: ""; ValueData: """{app}\MMView.exe"" ""%1"""; Flags: uninsdeletevalue deletevalue

