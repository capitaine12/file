<?php

const DEFAULT_VALIDATION_ERRORS = [

    'required' => 'Le %s est obligatoire',
    'email' => 'Le %s n\'est pas une adresse email valide',
    'min' => 'Le %s doit avoir au moins %s caractères',
    'max' => 'Le %s doit avoir au moins %s caractères',
    'between' => 'Le %s doit avoir entre %d et %d caractères',
    'same' => 'Le % doit être a %s',
    'option' => 'Doit être obligatoirement être choisie',
    'varchar' => 'Le %s et le %s sont aubligatoires',
    'secure' => 'Le %s doit comporter entre 8 et 64 caractères et une lettre majuscule,une lettre minuscule et un caractère spécial',
    'unique' => 'Le %s existe déjà',

];

/**
 * VALIDATION
 * @param array $data
 *  @param array $fields
 *  @param array $messages
 * @return array
 */


function validate(array $data, array $fields, array $messages = []): array
{
    //? Diviser les tableau par un séparateur, couper chaque element
    //? return les tableaau

    $split = fn($str, $seperator) => array_map('trim', explode($seperator, $str));

    //? Obtenire les régle du message

    $rule_messages = array_filter($messages, fn($message) => is_string($message));

    // ? Destruction du messages par defaut

    $validation_errors = array_merge(DEFAULT_VALIDATION_ERRORS, $rule_messages);

    $errors = [];

    foreach ($fields as $field => $option) {

        $rules = $split($option, '|');

        foreach ($rules as $rule) {

            $params = [];

            //* Si la régle a des paramètres, par exemple min : 1

            if (strpos($rule, ':')) {

                [$rule_name, $param_str] = $split($rule, ':');
                $params = $split($param_str, ',');
            } else {

                $rule_name = trim($rule);
            }

            //* Par convation le callback  doit être is_<rule> e.g. , is_required

            $fn = 'is_' . $rule_name;

            if (is_callable($fn)) {

                $pass = $fn($data, $field, ...$params);

                if (!$pass) {

                    //* obtinir le message d'erreur et pour un champ specifique et le régle s'il existe
                    //* sinon obtenir le msg d'erreur pour le $validation_orrors

                    $errors[$field] = sprintf(

                        $messages[$field][$rule_name] ?? $validation_errors[$rule_name],
                        $field,
                        ...$params
                    );
                }
            }
        }
    }


    return $errors;
}

/**
 *Retourne vrai si une chaine n'est pas vide 
 *@param array $date
 *@param string $field
 *@return bool
 */

function is_required(array $data, string $field): bool
{

    return isset($data[$field]) && trim($data[$field]) !== '';
}

/**
 * Renvoie vrai si la valeur de l'email est valide
 * @param array $data
 * @param string $field
 * @return bool
 */

function is_email(array $data, string $field): bool
{

    if (empty($data[$field])) {

        return true;
    }

    return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
}

/**
 * RENVOIE VRAI SI UNE CHAINE A AU MOINS LA LONGUEURE MINIMALE
 *@param array $data
 *@param string $field
 *@param int $min
 *@return bool
 */

function is_min(array $data, string $field, int $min): bool
{

    if (!isset($data[$field])) {

        return true;
    }

    return mb_strlen($data[$field]) >= $min;
}

/**
 * RENVOIE VRAI SI UNE CHAINE NE PEUT PAS DEPASSE LA LONGUEURE MAXIMALE
 * @param array $data
 *@param string $field 
 * @param int $max
 * @return bool
 */

function is_max(array $data, string $field, int $max): bool
{

    if (!isset($data[$field])) {

        return true;
    }

    return mb_strlen($data[$field]) <= $max;
}

/**
 * @param array $data
 * @param string $field
 * @param int $min
 * @param int $max
 * @return bool
 */

function is_between(array $data, string $field, int $min, int $max): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    $len = mb_strlen($data[$field]);
    return $len >= $min && $len <= $max;
}

/**
 * Renvoie vrai si la chaine est egale a l'autre
 * @param array $data
 * @param string $field
 * @param string $other
 * @return bool
 */

/* function is_same(array $data, string $field, string $other): bool
{
    if (isset($data[$field], $data[$other])) {
        return $data[$field] === $data[$other];
    }

    if (!isset($data[$field]) && !isset($data[$other])) {
        return true;
    }

    return false;
} */

/**
 * Renvoie vrai si la chaine est un chaine
 * @param array $data
 * @param string $field
 * @return bool
 */
function is_alphanumeric(array $data, string $field): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    return ctype_alnum($data[$field]);
}

/**
 * Renvoie vrai si le MP est sécurisé
 * @param array $data
 * @param string $field
 * @return bool
 */
function is_secure(array $data, string $field): bool
{
    if (!isset($data[$field])) {
        return false;
    }

    $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
    return preg_match($pattern, $data[$field]);
}

/**
 * Renvoie vrai si le  $value est unique dans le colunne d'une'table 
 * @param array $data
 * @param string $field
 * @param string $table
 * @param string $column
 * @return bool
 */
function is_unique(array $data, string $field, string $table, string $column): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    $sql = "SELECT $column FROM $table WHERE $column = :value";

    $stmt = db()->prepare($sql);
    $stmt->bindValue(":value", $data[$field]);

    $stmt->execute();

    return $stmt->fetchColumn() === false;
}
