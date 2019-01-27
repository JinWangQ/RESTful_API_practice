# RESTful_API_practice
### Dev environment
*[MAMP](https://www.mamp.info/en/)*
*[MySQLWorkBench](https://www.mysql.com/cn/products/workbench/)*

### virtual host (localhost -> api.com)
See post [Add a virtual host in MAMP](https://jinwangq.github.io/2018/11/15/Add-a-virtual-host-in-MAMP/)

### MySQL
*Create Database on MAMP*
1. Enter MAMP MySQL in terminal:
`$/Applications/MAMP/Library/bin/mysql -uroot -p`
password : 'root'

2. excute .sql file
`$source '.sql file directory'`

### PHP
*php connect to database in MAMP*
change MySQL port to 8889
```php
// /lib/db.php
$pdo = new PDO('mysql:host=localhost:8889;dbname=mydb','root','root');
return $pdo;
```


### Test tool
*[Postman](https://www.getpostman.com/)*
*[Restlet Client](https://chrome.google.com/webstore/detail/restlet-client-rest-api-t/aejoelaoggembcahagimdiliamlcdmfm)*
