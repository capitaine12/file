<?php

const FILTER = [

    'string' => FILTER_SANITIZE_STRING,
    'string[]' => [

        'filter' => FILTER_SANITIZE_STRING,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    'email' => FILTER_SANITIZE_EMAIL,
    'int' => [

        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_SCALAR
    ],

    'int[]' => [

        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    'float' => [

        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_FLAG_ALLOW_FRACTION
    ],

    'float[]' => [

        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    'url' => FILTER_SANITIZE_URL,


];

/**
 * COUPER RECURSIVEMENT LES CHI+AINE DE CARACTER DANS UN TABLEAU
 * @param array $item
 * @return array
 */

function array_trim(array $items): array
{

    return array_map(function ($item) {

        if (is_string($item)) {

            return trim($item);
        } elseif (is_array($item)) {

            return array_trim($item);
        } else

            return $item;
    }, $items);
}

/**
 * Désinfectation des entrés en fonction des regles et couper éventuellement les chaine
 * @param array $inputs
 * @param array $fields
 * @param int $default_filter FILTER_SANIRIZE_STRING
 * @param array $filters FILTERS
 * @param bool $trinm
 * @return array
 */

 function sanitize(array $inputs, array $fields = [], int $default_filter = FILTER_SANITIZE_STRING, array $filters = FILTER, bool $trim = true): array
 {

    if ($fields) {
        
        $options = array_map(fn($field) => $fields[$field], $fields);
        $data = $filter_var_array($inputs, $options);

    } else {
        
        $data = $filter_var_array($inputs, $default_filter);
    }

    return $trim ? array_trim($data) : $data;
    
 }