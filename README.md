# Wreck_the_todos
 A todo list management app

When use it, make sure to add a "config.php" file under the "util" folder. The format should be as below

```
<?php
    function connect(){
        return new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    }
    session_start();
    define("DBHOST", "YOUR HOST");
    define("DBUSER", "YOUR USERNAME");
    define("DBPASS", "YOUR DATABASE");
    define("DBNAME", "YOUR PASSWORD");
?>
```

If you want to create your own database, you should try the scripts below
```
CREATE TABLE `users` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `pass` VARCHAR(256) NOT NULL,
  `email` VARCHAR(45) NULL,
  `username` VARCHAR(45) NULL,
  PRIMARY KEY (`iduser`)
);

create table .`categories` (
	`idcategory` INT NOT NULL AUTO_INCREMENT,
  `iduser` INT NOT NULL,
  `category` VARCHAR(45) NOT NULL,
	PRIMARY KEY (`idcategory`),
  FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`)
);

CREATE TABLE `tasks` (
  `idtask` INT NOT NULL AUTO_INCREMENT,
  `iduser` INT NOT NULL,
  `idcategory` INT NOT NULL,
  `duedate` DATETIME NULL,
  `description` VARCHAR(256) NULL,
  `title` VARCHAR(45) NOT NULL,
  `complete` INT NOT NULL,
  PRIMARY KEY (`idtask`),
  FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`)
);
```

  Just so you know, because this app utilizes some packages through CDN and JQUERY, if you locate in China, you might need to use a VPN(proxy) to correct use it.
