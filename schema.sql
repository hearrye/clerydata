CREATE TABLE summary (
  year                integer NOT NULL,
  on_or_off_campus    varchar(40) NOT NULL,
  INSTNM              varchar(100) NOT NULL,
  BRANCH              varchar(100) NOT NULL,
  Address             varchar(200) NOT NULL,
  City                varchar(100) NOT NULL,
  State               varchar(100) NOT NULL,
  Zip                 varchar(25) NOT NULL,
  sector_cd           integer NOT NULL,
  sector_desc         varchar(100) NOT NULL,
  men_total           integer,
  women_total         integer,
  Total               integer,
  FORCIB              integer,
  NONFOR              integer,
  forcib_or_nonfor    integer,
  campus_id           integer NOT NULL
);