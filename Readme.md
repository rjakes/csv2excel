## CSV 2 Excel
### Simple example file that shows how to create excel documents from csv files.

### Requirements
* PHP running under Apache (tested under PHP 5.4, probably works under 5.3, but no less than that.)


### Installation
* Put entire code base into a directory on your web server named 'csv2excel'
* All required components are committed to the repo for ease of installation, no need to run composer.

### Usage
* Currently setup to call from a url
* Send in query parameters to see example for totaling a column
* The group_columns parameters let you choose any columns to group by. Pass in zero based indexes to identify columns
* The total_column is a single parameter to total (currently only supports totaling one column.) Pass a zero based index

[http://localhost/csv2excel/index.php?group_columns[0]=2&group_columns[1]=3&total_column=0]

## License
* MIT License - http://en.wikipedia.org/wiki/MIT_License