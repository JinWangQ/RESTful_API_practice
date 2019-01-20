 <?php 
date_default_timezone_set('EST');
require __DIR__.'/lib/User.php';
require __DIR__.'/lib/Article.php';
$pdo = require __DIR__.'/lib/db.php';

//if(!$pdo){echo 'null';}else{echo 'not null';}
//
//
//$user = new User($pdo);
//
//
//if(!$user){echo 'null';}else{echo 'not null';}
// $res = ;
// if(!$res){echo 'null';}else{echo 'not null';}
// 
// 
//print_r($user->login('jin4','admin3')); 
//
//

$article = new Article($pdo);
// print_r($article->create('canada goose','it is so warm, is there a pants from goose?',3));
//print_r($article->edit(2,'how to train your dragon','Feb 22',3));
//
print_r($article->delete(1,1));
//
//
//
//
// $dbms='mysql';     //数据库类型
// $host='localhost:8889'; //数据库主机名
// $dbName='mydb';    //使用的数据库
// $user='root';      //数据库连接用户名
// $pass='root';          //对应的密码
// $dsn="$dbms:host=$host;dbname=$dbName";


// try {
//     $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
//     echo "连接成功<br/>";
//     $user = new User($dbh);
//     print_r($user->signup('admin','admin')); 
    
//     $dbh = null;
// } catch (PDOException $e) {
//     die ("Error!: " . $e->getMessage() . "<br/>");
// }
// //默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
// $db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));


// phpinfo();