DEFINE sysPassword = "&1"
connect "SYS"/"&&sysPassword" as SYSDBA
set echo on
spool C:\oraclexe\app\oracle\product\11.2.0\server\config\log\postDBCreation.log
connect "SYS"/"&&sysPassword" as SYSDBA
set echo on
//create or replace directory DB_BACKUPS as 'C:\oraclexe\app\oracle\fast_recovery_area';
begin
   dbms_xdb.sethttpport('8080');
   dbms_xdb.setftpport('0');
end;
/
create spfile='C:\oraclexe\app\oracle\product\11.2.0\server\dbs/spfileXE.ora' FROM pfile='C:\oraclexe\app\oracle\product\11.2.0\server\config\scripts\init.ora';
shutdown immediate;
connect "SYS"/"&&sysPassword" as SYSDBA
startup ;
select 'utl_recomp_begin: ' || to_char(sysdate, 'HH:MI:SS') from dual;
execute utl_recomp.recomp_serial();
select 'utl_recomp_end: ' || to_char(sysdate, 'HH:MI:SS') from dual;
alter user hr password expire account lock;
alter user ctxsys password expire account lock;
alter user outln password expire account lock;
alter user MDSYS password expire;
@C:\oraclexe\app\oracle\product\11.2.0\server\apex\apxxepwd.sql "&1"
spool off;
exit
