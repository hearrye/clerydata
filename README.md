HEARRYE
=======

This is the web app for HEARRYE.

## Getting Started

To get setup developing this app, you'll need to have the following software installed:

* MySQL 5.5 or above
* Apache with PHP 5.2 or above

Once you have these installed, create a MySQL user `'hearrye'@'localhost'` with password `hearryehearrye` and a database `hearrye`.

```
mysql -u root
mysql> create user 'hearrye'@'localhost' identified by 'hearryehearrye';
mysql> create database hearrye;
mysql> grant all on hearrye.* to 'hearrye'@'localhost';
mysql> exit
```

Then, import the existing data:

```
mysql -u root hearrye < reported_data.sql
mysql -u root hearrye < schools.sql
```

Configure Apache to serve the project directory(need more setup detail). Now you should be able to open the web app in the browser.

## Playing With The Data

```sql
mysql> select sum(total_incidents), year
from reported_data
where
  on_or_off_campus = 'Total on or off campus'
group by year
order by year;
```

For that we got

```
 sum  | year 
------+------
 2520 | 2006
 2387 | 2007
 2389 | 2008
 2339 | 2009
 2610 | 2010
 3072 | 2011
 3710 | 2012
```

Summary of all counts of reported cases by institute and year:

```sql
mysql> select sum(total_incidents), year, instnm 
from reported_data 
where
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;
```

Note that I am filtering by on_or_off_campus = 'Total on or off campus', because that is the sum of both 'On-campus' and 'Off-campus'. If we didn't do this, all the numbers would be counted twice. If you wanted the numbers for only Georgia Tech:

```sql
mysql> select sum(total_incidents), year, instnm 
from reported_data 
where
  instnm like 'Georgia Institute of Technology%' and
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;
```
