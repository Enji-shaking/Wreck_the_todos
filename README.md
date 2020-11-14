# Wreck_the_todos
 A todo list management app

When use it, make sure to add a "config.php" file under the "util" folder. The format should be as below

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

  Just so you know, because this app utilizes some packages through CDN and JQUERY, if you locate in China, you might need to use a VPN(proxy) to correct use it.