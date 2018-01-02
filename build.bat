@ECHO OFF
::--------------------------------------------------------------------
:: Usage: "build [/clean | /rebuild | /release] [/NoPause] [/?]"
::
::                 /clean    - Cleans the project
::                 /rebuild  - Cleans and builds the project (default)
::                 /release  - Rebuilds the project and creates the packages
::
::                 /NoPause  - Does not pause after completion
::                 /?        - Gets the usage for this script
::--------------------------------------------------------------------



COLOR 07

:: Reset ERRORLEVEL
VERIFY OTHER 2>nul
SETLOCAL ENABLEEXTENSIONS ENABLEDELAYEDEXPANSION
IF ERRORLEVEL 1 GOTO ERROR_EXT

SET NO_PAUSE=0
SET PROJECT=build.xml
SET TARGET=rebuild
GOTO ARGS



:: -------------------------------------------------------------------
:: Builds the project
:: -------------------------------------------------------------------
:BUILD

CALL .\bin\composer.bat install -q -n
IF ERRORLEVEL 1 GOTO END_ERROR
ECHO.

CALL .\vendor\bin\phing.bat -f %PROJECT% %TARGET%
IF ERRORLEVEL 1 GOTO END_ERROR
GOTO END



:: -------------------------------------------------------------------
:: Parse command line argument values
:: Note: Currently, last one on the command line wins (ex: /rebuild /clean == /clean)
:: -------------------------------------------------------------------
:ARGS
IF "%PROCESSOR_ARCHITECTURE%"=="x86" (
    "C:\Windows\Sysnative\cmd.exe" /C "%0 %*"

    IF ERRORLEVEL 1 EXIT /B 1
    EXIT /B 0
)
::IF NOT "x%~5"=="x" GOTO ERROR_USAGE

:ARGS_PARSE
IF /I "%~1"=="/clean"      SET TARGET=clean& SHIFT & GOTO ARGS_PARSE
IF /I "%~1"=="/rebuild"    SET TARGET=rebuild& SHIFT & GOTO ARGS_PARSE
IF /I "%~1"=="/release"    SET TARGET=prepare.version release& SHIFT & GOTO ARGS_PARSE
::IF /I "%~1"=="/log"        SET VERBOSITY=diagnostic& SHIFT & GOTO ARGS_PARSE
IF /I "%~1"=="/NoPause"    SET NO_PAUSE=1& SHIFT & GOTO ARGS_PARSE
IF /I "%~1"=="/?"          GOTO ERROR_USAGE
IF    "%~1" EQU ""         GOTO ARGS_DONE
ECHO Unknown command-line switch: %~1
GOTO ERROR_USAGE

:ARGS_DONE
IF "%DEV_BUILD%"=="True" GOTO SETENV_DEV ELSE GOTO SETENV



:: -------------------------------------------------------------------
:: Set environment variables
:: -------------------------------------------------------------------
:SETENV
CALL bin\SetEnv.bat
ECHO.
GOTO BUILD



:: -------------------------------------------------------------------
:: Errors
:: -------------------------------------------------------------------
:ERROR_EXT
ECHO [31mCould not activate command extensions[0m
GOTO END_ERROR

:ERROR_USAGE
ECHO Usage: "build [/clean | /rebuild | /release] [/NoPause] [/?]"
ECHO.
ECHO                 /clean    - Cleans the project
ECHO                 /rebuild  - Cleans and builds the project (default)
ECHO                 /release  - Rebuilds the project and creates the packages
ECHO.
ECHO                 /NoPause  - Does not pause after completion
ECHO                 /?        - Gets the usage for this script
GOTO END



:: -------------------------------------------------------------------
:: End
:: -------------------------------------------------------------------
:END_ERROR
COLOR 4E

:END
@IF NOT "%NO_PAUSE%"=="1" PAUSE
ENDLOCAL
