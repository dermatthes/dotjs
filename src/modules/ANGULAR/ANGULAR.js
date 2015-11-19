
DOT.include(__DIR__ + "/server/angular-server.js");



var initCode = "";
initCode += DOT.fileGetContents(__DIR__ + "/browser/class/CTRLProxy.js");
initCode += DOT.fileGetContents(__DIR__ + "/browser/class/DotRemote.js");
initCode += DOT.fileGetContents(__DIR__ + "/browser/angular-init.js");
initCode = initCode.replace("$$$ENV$$$", JSON.stringify(DOT.ENV));

DOT.addBrowserLibraryCode(initCode)