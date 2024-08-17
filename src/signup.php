<?php
$errors = [];
$inputs = [];

if (is_post_request()) {


    $fields = [

        'firstName' => 'string | required | varchar | between : 3, 25 | users, 	firstName',
        'lastName' => 'string | required | varchar | between : 3, 25 | users, lastName',
        'email' => 'email | required | unique: users, email',
        'password' => 'string | required | secure',
        'cpassword' => 'string | required | same: password',
        'studyPath' => 'string | required | option | users, studyPath',
        'level' => 'string | required | option | users, level',
        'agree' => 'string | required'
    ];


    // MESSAGE SLASH

    $messages = [

        'cpassword' => [

            'required' => 'Confirme votre mot de passe',
            'same' => 'Le Mot de passe est incorrecte'
        ],

        'agree' => [

            'required' => 'Vous devez accepté les conditions d\'utilisation des services avant inscription'
        ]
    ];


    [$inputs, $errors] = filter($_POST, $fields, $messages);

    if ($errors) {
        
        redirect_with('signup.php', [

            'inputs' => $inputs,
            'errors' => $errors
        ]);
    }

    if (register_user($inputs['email'], $inputs['password'])) {

        redirect_with_message(
            'login.php',
            'Votre inscription est en sucesse. veillez vous connecter.'
        );
    } elseif (is_get_request()){

        [$inputs, $errors] = session_flash('inputs', 'errors');
    }
}
