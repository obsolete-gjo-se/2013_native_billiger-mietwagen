<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gregory
 * Date: 22.04.13
 * Time: 19:31
 * To change this template use File | Settings | File Templates.
 */

namespace entities;

class Blog extends \entities\Base{

    protected $loggedIn = false;
    protected $username = NULL;
    protected $password = NULL;

    const TABELLE = 'user';

    public function __construct(){
        parent::__construct();
        $this->setUsername();
        $this->setPassword();
    }

    private function setUsername(){

        if (isset($_POST['username']) && $_GET['username'] != '') {
            $this->username = htmlspecialchars($_GET['username']);
            return true;
        }
        return false;
    }

    private function setPassword(){

        if (isset($_POST['password']) && $_GET['password'] != '') {
            $this->username = md5(htmlspecialchars($_GET['password']));
            return true;
        }
        return false;
    }



    public function login()
    {
        $sql = "SELECT * FROM " . self::TABELLE . " WHERE username = " . $this->username . " AND password = " . $this->password;

        $prep_state = $this->dbh->prepare($sql);

        $prep_state->execute();

        if($prep_state->errorCode() !== "00000") {

            echo "MySQL-Errorcode: " . $prep_state->errorCode() . "<br />";
            throw new \error\myException("", 1);
        }

        return $prep_state;


        // Rest würde folgen:


        while($row = mysql_fetch_row($result)) {
            $this->logged_in_users[] = $_REQUEST['user'];
            $_SESSION['loggedin'] = 'yes';
            $_SESSION['permission'] = $row[3];
            $_SESSION['readonly'] = $row[4];
            $_SESSION['user_id'] = $row[0];
        }
    }

}