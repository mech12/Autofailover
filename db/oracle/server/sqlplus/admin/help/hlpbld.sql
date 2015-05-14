--
-- Copyright (c) 1998, 2009, Oracle and/or its affiliates.All rights reserved. 
--
-- NAME
--   hlpbld.sql
--
-- DESCRIPTION
--   Builds the SQL*Plus HELP table and loads the HELP data from a
--   data file.  The data file must exist before this script is run.
--
-- USAGE
--   To run this script, connect as SYSTEM and pass the datafile to be
--   loaded as a parameter e.g.
--
--       sqlplus system/<system_password> @hlpbld.sql helpus.sql
--
--
DEFINE DATAFILE = &1

--
-- Drop the HELP table in a PL/SQL block so that the unnecessary error 
-- ORA-0942 can be suppressed when table does not exist. (7676775)
-- 

BEGIN
  EXECUTE IMMEDIATE 'DROP TABLE HELP';
  EXCEPTION WHEN OTHERS THEN
  IF (SQLCODE = -942) THEN
   NULL;
  ELSE
   RAISE;
  END IF;
END;
/

--
-- Create the HELP table
--
CREATE TABLE HELP
(
  TOPIC VARCHAR2 (50) NOT NULL,
  SEQ   NUMBER        NOT NULL,
  INFO  VARCHAR2 (80)
) PCTFREE 0 STORAGE (INITIAL 48K PCTINCREASE 0);

GRANT SELECT ON HELP TO PUBLIC;

--
-- Insert the data into HELP.
--

@@&DATAFILE

--
-- Create the index
--

ALTER TABLE HELP
  ADD CONSTRAINT HELP_TOPIC_SEQ
  PRIMARY KEY (TOPIC, SEQ)
  USING INDEX STORAGE (INITIAL 10K);

--
-- Add all topics to the HELP TOPICS entry
--

CREATE OR REPLACE VIEW HELP_TEMP_VIEW (TOPIC) AS
  SELECT DISTINCT UPPER(TOPIC)
  FROM HELP
  GROUP BY UPPER(TOPIC)
  ORDER BY UPPER(TOPIC);

-- Using ROWNUM+10 below allows the first few rows of TOPICS to be
-- stored in the data file help<lang>.sql.  Although there should only
-- be 3 lines there, we add 10 to allow future expansion or protect
-- from errors.  The value for the SEQ column is not important as long
-- as it is unique and increases monotonically.

INSERT INTO HELP
  SELECT 'TOPICS', ROWNUM + 10, TOPIC
  FROM HELP_TEMP_VIEW;

COMMIT;

--
-- Drop the temp HELP view in a PL/SQL block so that the unnecessary error 
-- ORA-0942 can be suppressed when view does not exist. (7676775)
-- 
BEGIN
  EXECUTE IMMEDIATE 'DROP VIEW HELP_TEMP_VIEW';
  EXCEPTION WHEN OTHERS THEN
  IF (SQLCODE = -942) THEN
   NULL;
  ELSE
   RAISE;
  END IF;
END;
/
