<?php

$input = [
    [   'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome:"
    ],
    [   'nome' => 'descricao',
        'tipo_input' => "text",
        'descricao' => "Descrição:"
    ]
];

//var_dump($input);

foreach ($input as $value) {
    echo $value['nome'] . '<br>';
    echo $value['tipo_input']. '<br>';
    echo $value['descricao'].'<br>';
}