# GloriousDB
A wrapper class for MySQL on PHP

###Some basic way to operating using the GloriousDB:

* Initialize

```php
require "glorious.php";
$db = new GloriousDB("host", "username", "password", "database");//connect your database
$db->setTable("tablename"); //Choose your table.
```
And you can always get the connection status using

```php
$db->state(); //if there is an error, return 0 otherwise 1
```

* Query
All the query method will return an array with key and value, and you can always use `print_r` to check the array.
You can query the database as you want using the function like

```php
$db->findall(); //This will return an array of all the data in that table
```
which is the same as the SQL query:

```sql
select * from table; //table is the table name which passes to the class using the setTable() method above.
```
So if you want to find one particular row using the exact primary key value, you can use the query like this:

```php
$db->findOne(value); //the value parameter is the primary key's value which you are willing to get.
```
And if you want to use the conditional search as the `where` stuff in the SQL language, you can use two import function `where()` method and the `orWhere()` method, for example, if you want to search the rows whose id equals to 1001 and school is SCU, you can use the query method like this:

```php
$db->where(["id" => "1001", "school" => "SCU"], "and"); //you can omit the second parameter if you want to use the and relation for the and relation is the default one, you can also try or here to present the or relationship.
$db->find("*"); // the parameter will be the columns which the user wants to get
```
And this is the same as the SQL sentence:

```sql
select * from table where (id='1001' and school='SCU');
```

and the query orWhere method is for some queries which is connected using `or` relation like:

```sql
select * from table where ((id='1001' and school='SCU') or (id='1002' and school='PKU'));
```

and if you use GloriousDB, the method will be like this:

```php
$db->where(["id" => "1001", "school" => "SCU"], "and");
$db->orWhere(["id" => "1002", "school" => "PKU"], "and");
$db->find(*);
```
see, it's easy to use the method to select some data because as we all know the original way of using PHP maybe first insert query and then using the `fetch_assocs` or other methods to get the row information and here things will be sort of easier just to use some method to get an array which contains the data in the database for you to work on.

And still the `insert`, `update` and `delete` method is pretty simple:

* insert

```php
$db->insert(["id" => "2015", "name" => "7t"]);
```
which is the same as:

```sql
insert into table set (id='2015', name='7t');
```

* update

```php
$db->update(["name" => "Michael Jack"], ["id" => "3001"]);
```
which is the same as:

```sql
update table set (name='Michael Jack') where (id='3001');
```

* delete

```php
$db->delete(["id" => "3001"]);
```
and the sql:

```sql
delete from table where id='3001'
```


Finally, when the database is of no use any longer, remember to use

```php
$db->destroy();
```