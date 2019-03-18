## Requirements
 - PHP >= 7.1
 - composer
 - mySQL
## Instalation
 1. git clone git@github.com:ssi94/test-console.git
 2. cd test-console
 3. composer install
 4. mysql -u `user` -p < make_database.sql
 5. mysql -u `user` -p < addColumns.sql
 6. set up mysql connection in importer
## Usage
./importer list  
./importer import help  
./importer import file.csv  
./importer import file.csv --test  
