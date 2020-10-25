[Setup]
AppName=マルチメディアライブラリビューワ
AppVerName=マルチメディアライブラリビューワ Version 1.10
AppVersion=1.10
AppCopyright=Copyright (C) 2000-2011 Yoshimasa Electronic Inc.
DefaultDirName={pf}\YMEC\MMLibJ
DefaultGroupName=マルチメディアライブラリ
OutputBaseFilename=MMViewJ64
LicenseFile=J:\ymec\MyDev\RTF\LicenseAgreementJ.rtf
UninstallDisplayIcon={app}\MMView.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
ArchitecturesAllowed=x64
ArchitecturesInstallIn64BitMode=x64
ChangesAssociations=yes
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: japanese; MessagesFile: compiler:Languages\Japanese.isl

[Files]
Source: "..\x64\JpnRel\MMView.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\x64\JpnRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\x64\JpnRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\x64\JpnRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\x64\JpnRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\MMLibJ\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "J:\YMEC\MyDev\INI\YmUpdate.ini"; DestDir: "{app}"; Flags: ignoreversion

[Icons]
Name: "{group}\MMLIBビューワ"; Filename: "{app}\MMView.exe"; WorkingDir: "{app}"

[Registry]
Root: HKCR; Subkey: ".mml"; ValueType: string; ValueName: ""; ValueData: "MMLIB.File"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File"; ValueType: string; ValueName: ""; ValueData: "Multimedia Library File"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File\DefaultIcon"; ValueType: string; ValueName: ""; ValueData: "{app}\MMView.exe,0"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "MMLIB.File\shell\open\command"; ValueType: string; ValueName: ""; ValueData: """{app}\MMView.exe"" ""%1"""; Flags: uninsdeletevalue deletevalue

