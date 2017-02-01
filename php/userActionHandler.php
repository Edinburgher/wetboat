<?php
/**
 * Created by PhpStorm.
 * User: Mario
 * Date: 01.02.2017
 * Time: 09:33
 */
require_once 'UserAction.php';
$action = $_POST['action'];

$userAction = new UserAction();

if (method_exists($userAction, $action)) {
    return $userAction->$action();
}
