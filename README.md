## TodoPHP

The todo app for my Summer of Tech 2013 Intro to PHP bootcamp.

### Requirements

* PHP 5.3+
	* PDO (MySQL or SQLite)
* MySQL 5.2+

### Schema

#### MySQL

```mysql
CREATE TABLE `todos` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(50) DEFAULT NULL,
	`completed` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#### SQLite
```sqlite
CREATE TABLE todos (
	id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	title varchar(50),
	completed integer NOT NULL DEFAULT(0)
);
```

### Install

1. Create a MySQL or SQLite database and execute the appropriate SQL query above.
2. Edit the config values in `app/config.php`. If you do not have mod_rewrite enabled, uncomment the `index` value.
3. Navigate your browser to your TodoPHP install.

**Note:** For SQLite to work properly, the database directory needs to be writable by the server.

### License
Copyright (C) 2013 Martin Hipp

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
