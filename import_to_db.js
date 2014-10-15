var data = require('./clery.json')

var MongoClient = require('mongodb').MongoClient

var url = 'mongodb://localhost:27017/hearrye'
MongoClient.connect(url, function(err, db){
  if (err) console.error(err)
  var Events = db.collection('events')
  var batch = Events.initializeOrderedBulkOp()
  data.forEach(function(doc){
    batch.insert(transform(doc))
  })
  batch.execute(function(err, result){
    if (err) console.error(err.message)
    console.log('done')
    db.close()
  })
})

function transform(doc){
  return {
    year: Number(doc.year),
    on_or_off_campus: doc.on_or_off_campus,
    institute: doc.INSTNM,
    branch: doc.BRANCH,
    address: doc.Address,
    city: doc.City,
    state: doc.State,
    zip: doc.Zip,
    sector_cd: doc.sector_cd,
    sector_desc: doc.sector_desc,
    men_total: Number(doc.men_total),
    women_total: Number(doc.women_total),
    total: Number(doc.Total),
    forcible: Number(doc.FORCIB),
    non_forcible: Number(doc.NONFOR),
    forcible_or_non_forcible: Number(doc.forcib_or_nonfor),
    campus_id: Number(doc.campus_id)
  }
}