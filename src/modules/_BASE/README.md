# DotJS Base Module

The Base-Module must be the first module loaded and will be loaded by default. It offers
low level Bridges to the Filesystem and Host process


## Server-Side

| Service            | Description                                           |
|--------------------|-------------------------------------------------------|
| DOT.include()      | Includes another JavaScript file                      |
| DOT.includeActions() | Extract and include JavaScript from a HTML Template file |
| DOT.print()        | Output text                                           |
| DOT.dump()         | Print some Structure data                             |
| DOT.fileGetContents() | Load data from the filesystem                      |
| DOT.extension()    | Load a low-level Extension                            |
| DOT.ENV.*          | Server environment (path, servername, etc)            |
| DOT.REQUEST.*      | Access POST/GET/COOKIE Data                           |
| DOT.printBrowserLibraryCode () | Print initialisation of browser-side      |


