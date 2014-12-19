<?php
$database = mysql_connect('localhost', 'user') or die('no connection');
mysql_select_db('test', $database);
session_start();

class Blog
{
    public $logged_in_users = array();

    function login()
    {
        global $database;

        $_SESSION['loggedin'] = 'no';

        $sql = "select * from user where username = '" .
            $_REQUEST['user'] . "' and password  = '" . $_REQUEST['password'] . "'";

        $result = mysql_query($sql, $database);

        if (!$result) {
            $log = fopen('Log/error.log', 'a+');
            fwrite($log, 'error while reading/writing log file');
            fclose($log);
            echo 'error while reading/writing log file';
            return;
        }

        while($row = mysql_fetch_row($result)) {
            $this->logged_in_users[] = $_REQUEST['user'];
            $_SESSION['loggedin'] = 'yes';
            $_SESSION['permission'] = $row[3];
            $_SESSION['readonly'] = $row[4];
            $_SESSION['user_id'] = $row[0];
        }
    }

    function insertBlog()
    {
        global $database;

        if ($_SESSION['loggedin'] == 'yes' && ($_SESSION['permission'] == 'admin' ||
            $_SESSION['permission'] == 'superuser' ||
            ( $_SESSION['permission'] == 'normal' && $_SESSION['readonly'] == 'no'))) {
            $sql = "insert into blog (text,userid) values('".$_REQUEST['text']."','" . $_SESSION['user_id'] . "')";

            $result = mysql_query($sql, $database);

            if (!$result) {
                $log = fopen('Log/error.log', 'a+');
                fwrite($log, 'error while writing new blog');
                fclose($log);
                echo 'error while writing new blog';
                return;
            }
        }
    }

    function loadBlog()
    {
        global $database;

        $sql = 'select * from blog';

        $result = mysql_query($sql, $database);

        if (!$result) {
            $log = fopen('Log/error.log', 'a+');
            fwrite($log, 'error while reading');
            fclose($log);
            echo 'error while reading';
            return;
        }

        echo "<br><table>";

        while($row =  mysql_fetch_array($result, MYSQL_NUM)) {

            $sql = "select username from user where userid = " . $row[2];

            echo "<tr>";

            $result2 = mysql_query($sql, $database);

            if (!$result2) {
                $log = fopen('Log/error.log', 'a+');
                fwrite($log, 'error while reading');
                fclose($log);
                echo 'error while reading';
                return;
            }

            $row2 = mysql_fetch_row($result2);
            echo "<td>Autor : " . $row2[0]."</td>";
            echo "</tr><tr>";
            echo "<td>" . $row[1] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    function deleteBlog()
    {
        global $database;

        if ($_SESSION['loggedin'] == 'yes' && $_SESSION['permission'] == 'admin') {

            $sql = 'delete from blog where id = ' . $_REQUEST['id'];

            $result = mysql_query($sql, $database);

            if (!$result) {
                $log = fopen('Log/error.log', 'a+');
                fwrite($log, 'error while deleting');
                fclose($log);
                echo 'error while deleting';
                return;
            }
        }
    }

    function newUser()
    {
        global $database;

        if ($_SESSION['loggedin'] == 'yes' && $_SESSION['permission'] == 'admin') {

            $sql = "insert into user(username, password, permission, readonly) values("
            . "'{$_REQUEST['username']}', '{$_REQUEST['password']}', '{$_REQUEST['permission']}', '{$_REQUEST['readonly']}')";

            $result = mysql_query($sql, $database);
            if (!$result) {
                $log = fopen('Log/error.log', 'a+');
                fwrite($log, 'error while writing new user');
                fclose($log);
                echo 'error while writing new user';
                return;
            }
        }
    }


    function countBlogs()
    {
        global $database;

        $result = mysql_query('select count(*) as anzahl from blog', $database);

        if (!$result) {
            $log = fopen('Log/error.log', 'a+');
            fwrite($log, 'error while writing new user');
            fclose($log);
            echo 'error while counting';
            return;
        }

        $row = mysql_fetch_row($result);

        echo "anzahl blogs : " . $row[0];
    }
}



$blog = new Blog();


if ($_REQUEST['action'] == 'list') {
    $blog->countBlogs();
    $blog->loadBlog();
} elseif($_REQUEST['action'] == 'new') {
    $blog->insertBlog();
    $blog->countBlogs();
    $blog->loadBlog();
} elseif($_REQUEST['action'] == 'delete') {
    $blog->deleteBlog();
    $blog->countBlogs();
    $blog->loadBlog();
} elseif($_REQUEST['action'] == 'new_user') {
    $blog->newUser();
    $blog->countBlogs();
    $blog->loadBlog();
} elseif($_REQUEST['action'] == 'login') {
    $blog->login();
    $blog->countBlogs();
    $blog->loadBlog();
} else {
    $blog->countBlogs();
    $blog->loadBlog();
}



/*
 CREATE TABLE `user` (
    `userid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `username` VARCHAR( 45 ) NOT NULL ,
    `password` VARCHAR( 45 ) NOT NULL ,
    `permission` VARCHAR( 45 ) NOT NULL ,
    `readonly` VARCHAR( 45 ) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE `blog` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `text` TEXT NOT NULL ,
    `userid` INT NOT NULL
) ENGINE = InnoDB;

--liste
http://domain/blog.php

--einloggen
http://domain/blog.php?action=login&user=admin&password=admin

--neuer eintrag
http://domain/blog.php?action=new&text=bla

--neuer user
http://domain/blog.php?action=new_user&username=test&password=test&permission=superuser&readonly=yes

--eintrag löschen
http://domain/blog.php?action=delete&id=1

 */






