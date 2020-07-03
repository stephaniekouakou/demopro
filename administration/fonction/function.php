<?php
function verifyInput($var){
    $var = htmlspecialchars($var);
    $var = stripcslashes($var);
    $var = trim($var);
    return $var ;
}
function fullname($fullname){
    $fullname =preg_match("/^[a-zA-Z ]*$/",$fullname);
    return $fullname;
}
function isUsername ($username){
    $username = preg_match('/^[a-zA-Z ]*$/',$username);
    return $username;
}
function isEmail ($email){
    $email = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $email;
}
function isLetter ($letter){
    $letter = preg_match('/^[a-zA-Z]*$/',$letter);
    return $letter ;
}
function isLetter1 ($letter){
    $letter = preg_match('/^[a-zA-Z- ]*$/',$letter);
    return $letter ;
}
function hashPass($pass){
    $pass = sha1($pass);
    return $pass ;
}
function telDefind($phone){
    $phone = preg_match('/^[0-9-]*$/',$phone);
    return $phone ;
}
function contact($c)
{
    $c=preg_match('#(0|\+225)[1-9]( *[0-9]{2}){4}#', $c);
    return $c;
}
function lengthPass($pwd){
    $pwd = strlen($pwd) ;
    return $pwd ;
}

function isNumeric($price){
    $price = preg_match('/^[0-9]*$/',$price);
    return $price ;
}

function limitDesc($msg){
    $msg = strlen($msg) ;
    return $msg ;
}

function verifyPass($pa){
    if (strlen($pa)<=8) {
      return $pa;
    }
  }
?>
