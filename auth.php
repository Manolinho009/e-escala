<?php
session_start();
try {
    $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
  
  if(isset($_POST['login'])){
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $user = $conn->query("SELECT * from tb_login WHERE usr_login = '$login' and usr_pass = '$pass'")->fetch();
    $conn = false;
    echo var_dump($user);
    
    if(isset($user['usr_nome'])){
        
        $_SESSION["usr_name"] = $user['usr_nome'];
        $_SESSION["usr_login"] = $user['usr_login'];
        $_SESSION["usr_pass"] = $user['usr_pass'];
        $_SESSION["usr_codfunc"] = $user['usr_codfunc'];

        header('Location: index.php');
        // echo var_dump($_SESSION);
      }
      else{
        header('Location: sign-in.php');
      }



}



function get_name(){
  return $_SESSION('usr_name');
}