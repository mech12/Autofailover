connect "SYS"/"&&sysPassword" as SYSDBA
set echo on
spool C:\oraclexe\app\oracle\product\11.2.0\server\config\log\postScripts.log
@C:\oraclexe\app\oracle\product\11.2.0\server\rdbms\admin\dbmssml.sql;
@C:\oraclexe\app\oracle\product\11.2.0\server\rdbms\admin\dbmsclr.plb;
execute dbms_datapump_utl.replace_default_dir;
create or replace directory XMLDIR as 'C:\oraclexe\app\oracle\product\11.2.0\server\rdbms\xml';
EXECUTE IMMEDIATE 'DROP DIRECTORY ORACLE_OCM_CONFIG_DIR';
commit;
execute dbms_swrf_internal.cleanup_database(cleanup_local => FALSE);
commit;
spool off
