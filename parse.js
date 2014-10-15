var csv = require('csv')
var fs = require('fs')
var content = fs.readFileSync('./clery.csv') + ''

csv.parse(content, function(err, csvData){
  var data = []
  var headers = csvData[0]
  csvData = csvData.slice(1)
  csvData.forEach(function(line){
    for (var i = 0; i < headers.length; i++){
      obj[headers[i]] = line[i]
    }
    data.push(obj)
  })

  fs.writeFileSync('clery.json', JSON.stringify(data, null, '  '))
  console.log('Wrote clery.json')
})
