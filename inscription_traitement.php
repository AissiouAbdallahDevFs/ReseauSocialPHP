<?php
include 'lib/database.php';
if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_retype'])) {
    $email = htmlspecialchars($_POST('email'));
    $pseudo = htmlspecialchars($_POST('pseudo'));
    $password = htmlspecialchars($_POST('passeword'));
    $password_retype = htmlspecialchars($_POST('password_retype'));
    $check = connectToDatabase()->prepare('SELECT pseudo , email , password FROM user WHERE email = ?');
    $check->execute(array($email));
    $date = $check->fetch();
    $row = $check->rowCount();
    if ($row == 0) 
    {
        if(strlen($pseudo) <= 100 )
        {
            if(strlen($email) <= 100 )
            {
                if(filter_var($email ,FILTER_VALIDATE_EMAIL))
                {
                    if($password == $password_retype)
                    {
                        $password = hash('sha256' , $password);
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $insert = connectToDatabase()->prepare('INSERT INTO user(pseudo, email , password , ip) VALUES (:pseudo ,:email , :password , :ip)');
                        $insert->execute(array(
                            'pseudo'=> $pseudo,
                            'email'=> $email,
                            'password'=> $password,
                            'ip'=> $ip
                        ));
                        header('Location:index.php?reg_err=success');
                    }else header('Location:index.php?reg_err=password');
                }else header('Location:index.php?reg_err=email');
            }else header('Location:index.php?reg_err=mail_lenght');
        }else header('Location:index.php?reg_err=pseudo_lenght');
    }else header('Location:index.php?reg_err=already');
} else header('Location:index.php');
