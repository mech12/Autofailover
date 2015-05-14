@set ORACLE_HOME=%cd%\db\oracle\server
@set ORACLE_SCRIPT_PATH=%cd%\db\oracle
@cd %ORACLE_HOME%\bin
@sqlplus -S -L %1/%2@%3 @%ORACLE_SCRIPT_PATH%\check_server.sql 2>&1
