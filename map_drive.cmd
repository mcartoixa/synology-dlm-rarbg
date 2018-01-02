@ECHO OFF
::--------------------------------------------------------------------
:: Usage: "map_drive <drive_name>"
::
::                 <drive_name> - Optional. W: by default.
::--------------------------------------------------------------------

SETLOCAL
SET MAPPED_DRIVE_NAME=%~1
IF "x%MAPPED_DRIVE_NAME%x"=="xx" SET MAPPED_DRIVE_NAME=W:

NET USE %MAPPED_DRIVE_NAME% /DELETE
SUBST %MAPPED_DRIVE_NAME% /D >NUL
SUBST %MAPPED_DRIVE_NAME% "%~dp0."

ENDLOCAL
