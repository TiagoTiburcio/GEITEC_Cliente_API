<?php
require_once("class/principal.php");
$nomeRecurso = 'disco';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'tamanho',
        'tipo_input' => "text",
        'descricao' => "Tamanho",
        'table' => true
    ],
    [
        'nome' => 'codigo_servidor',
        'tipo_input' => "select",
        'descricao' => "Servidor",
        'table' => true,
        'recurso' => 'servidor',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ],
    [
        'nome' => 'codigo_ds',
        'tipo_input' => "select",
        'descricao' => "Data Store",
        'table' => true,
        'recurso' => 'ds',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ]
];
//botoes opçoes 
/**
 * id = id do botão chamado no javascript
 * nome = nome que irá aparecer
 * cor = 
 *      primary - Azul e branco
 *      secondary - Cinza e branco 
 *      success - Verde e Branco
 *      info - Azul Claro e Branco
 *      warning - Amarelo e Preto
 *      danger - Vermelho e Branco
 *      dark - Preto e Branco
 *      light - Cinza Claro e Preto
 *      link - transparente e Azul
 */
$btns_opc = [[
    'id' => 'adicionar',
    'nome' => 'Listar Todos',
    'cor' => 'primary'
]];
$btns_form = [
    [
        'id' => 'salvar_' . $nomeRecurso,
        'nome' => 'Salvar',
        'cor' => 'success'
    ],
    [
        'id' => 'delete_' . $nomeRecurso,
        'nome' => 'Deletar',
        'cor' => 'danger'
    ]
];

$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes($btns_opc); ?>
    <?= $principal->formEdit($input, $btns_form); ?>
    <?= $principal->criaLista($input); ?>  
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>
    
</script>

</html>