<?php
/**
* Register a user
*
* @param string $email
* @param string $username
* @param string $password
* @param bool $is_admin
* @return bool
*/
function register_user(string $firstName, string $lastName, string $email, string $studyPath, string $level, string $password, bool $isAdmin = false):bool
{

    $sql = 'INSERT INTO users(firtName, lastName, email, studyPath, level, password, is_admin ) 
            VALUES(:firstName, :lastName, :email, :studyPath, :studyPath, :level, :password, :level, :is_amdin)';

    $statement = db() -> prepare($sql);

    $statement->bindValue(':firstName',$firstName, PDO::PARAM_STR);
    $statement->bindValue(':lastName',$lastName, PDO::PARAM_STR);
    $statement->bindValue(':email',$email, PDO::PARAM_STR);
    $statement->bindValue(':studyPath',$studyPath, PDO::PARAM_STR);
    $statement->bindValue(':level',$level, PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $statement->bindValue(':is_admin',(int)$isAdmin, PDO::PARAM_INT);
    

    return $statement->execute();

}