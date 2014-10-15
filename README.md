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