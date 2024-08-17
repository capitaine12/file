<?php
//? :::::::::::::::::::::::::::: FONCTION PPOUR LE TITRES DES PAGES DYNAMIQUE :::::::::::::::::::::::::::
function view(string $filename, array $data = []) : void 
{

    foreach ($data as $key => $value){

        $$key = $value;
    }

    include_once __DIR__ . './' . $filename . '.php'; 
}

//? :::::::::::::::::::::::::::: FONCTION POUR VERIFIER LES REQUETTE HTTP POST :::::::::::::::::::::::::::


function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

//? :::::::::::::::::::::::::::: FONCTION POUR VERIFIER LES REQUETTE HTTP POST :::::::::::::::::::::::::::

function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

//? :::::::::::::::::::::::::::: FONCTION DE STYLISATION DES MESSAGES D'ERREUR :::::::::::::::::::::::::::

function error_class(array $errors, string $field): string
 {
    return isset($errors[$field]) ? 'error' : '';
}

//? :::::::::::::::::::::::::::: FONCTION DE REDIRECTIONS :::::::::::::::::::::::::::

function redirect_to(string $url): void
{
    header('location:' . $url);
    exit;
}

function redirect_with(string $url, array $items): void 
{

    foreach ($items as $key => $value) {
        
        $_SESSION[$key] = $value;
    }

    redirect_to($url);
}

function redirect_with_message(string $url, string $message, string $type=FLASH_SUCCESS)
{
    flash('flash_'. uniqid(), $message, $type);
    redirect_to($url);
}

/**
 * Flash data specified by $keys from the $_SESSION
 * @param ...$keys
 * @return array
 */

function session_flash(...$keys): array
{
    $data = [];
    foreach ($keys as $key) {

        if (isset($_SESSION[$key])) {

            $data[] = $_SESSION[$key];
            unset($_SESSION[$key]);

        } else {
            $data[] = [];
        }
    }
    return $data;
}
