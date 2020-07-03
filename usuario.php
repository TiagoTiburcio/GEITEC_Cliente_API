<?php
require_once("class/principal.php");
$nomeRecurso = 'usuario';
$input = [
    [
        'nome' => 'usuario',
        'tipo_input' => "text",
        'descricao' => "Usuario",
        'table' => true
    ],
    [
        'nome' => 'descricao',
        'tipo_input' => "text",
        'descricao' => "Descricao",
        'table' => true
    ],
    [
        'nome' => 'tipo_usuario',
        'tipo_input' => "text",
        'descricao' => "Tipo Usuario",
        'table' => true
    ],
    [
        'nome' => 'codigo_servico',
        'tipo_input' => "text",
        'descricao' => "Codigo Serviço",
        'table' => true
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