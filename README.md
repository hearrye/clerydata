Hearrye Clerydata
=================

* `clery-data.csv` - a cleaned up for UTF-8 encoding version of clery.csv for directly importing to postgresql. 
* `schema.sql` - a schema for the summary table for the data in the csv file.
* `summary.txt` - a output of the query of all counts of reported cases by institute and year

## Postgresql

After last night I realized that most of the members were more comfortable with SQL than Mongo, and even I think sql is easier to work with for data exploration, so I imported the data into postgres.

To do this for yourself, assuming you have postgresql installed. Create a database named hearrye and create the summary table using `schema.sql`. Then, import the data from using this command inside of psql

```
copy summary from '<path to file>/clery-data.csv' DELIMITERS ',' CSV;
```

Now you can start querying and playing with the data.

Summary of all counts of reported cases by year:

```
select sum(forcib_or_nonfor), year
from summary
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

```
select sum(forcib_or_nonfor), year, instnm 
from summary 
where
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;
```

For that you can see the output in `summary.txt`.
Note that I am filtering by on_or_off_campus = 'Total on or off campus', because that is the sum of both 'On-campus' and 'Off-campus'. If we didn't do this, all the numbers would be counted twice. If you wanted the numbers for only Georgia Tech:

```
select sum(forcib_or_nonfor), year, instnm 
from summary 
where
  instnm like 'Georgia Institute of Technology%' and
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;
```
