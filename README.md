Hearrye Clerydata
=================

These are some Node.js scripts to work with the clery data from [clerypl.us](http://clerypl.us/).

* `parse.js` - reads `clery.csv`, parses it and converts it to `clery.json`.
* `import_to_db.js` - reads `clery.json`, and imports to a mongodb named `hearrye` on the local machine.

## Mongodb

### events

The events collection (although this is misnamed) is a collection containing documents with the following fields. 

* year
* on_or_off_campus
* institute
* branch
* address
* city
* state
* zip
* sector_cd
* sector_desc
* men_total
* women_total
* total
* forcible
* non_forcible
* forcible_or_non_forcible
* campus_id

### Using the mongodb shell to query data

First, fire up the mongo shell

```
mongo hearrye
```

Below are some queries you can run against it.

#### total cases reported by year

```
db.events.aggregate([{
  $group: {
    _id: '$year', 
    total: {
      $sum: '$forcible_or_non_forcible'
    }
  }
}])
```

## Postgresql

After last night I realized that most of the members were more comfortable with SQL than Mongo, and even I think sql is easier to work with for data exploration, so I imported the data into postgres.

To do this for yourself, assuming you have postgresql installed. Create a database named hearrye and create the summary table using `schema.sql`. Then, import the data from using this command inside of psql

```
copy summary from '/Users/airportyh/Home/Code/hearrye/clean.csv' DELIMITERS ',' CSV;
```

Now you can start querying and playing with the data. For example, to get the summary of all counts of reported cases by institute and year:

```
select sum(forcib_or_nonfor), year, instnm 
from summary 
where
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;
```

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
