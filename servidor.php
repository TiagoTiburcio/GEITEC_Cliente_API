<?php
require_once("class/principal.php");
$nomeRecurso = 'servidor';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'descricao',
        'tipo_input' => "text",
        'descricao' => "Descricao",
        'table' => true
    ],
    [
        'nome' => 'memoria',
        'tipo_input' => "text",
        'descricao' => "Memoria",
        'table' => true
    ],
    [
        'nome' => 'cpu',
        'tipo_input' => "text",
        'descricao' => "CPU",
        'table' => true
    ],
    [
        'nome' => 'situacao',
        'tipo_input' => "text",
        'descricao' => "Situacao",
        'table' => true
    ],
    [
        'nome' => 'codigo_so',
        'tipo_input' => "select",
        'descricao' => "Codigo SO",
        'table' => true,        
        'recurso' => 'so',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ],
    [
        'nome' => 'codigo_pool',
        'tipo_input' => "select",
        'descricao' => "Pool",
        'table' => true,        
        'recurso' => 'pool',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ]
];

$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes(); ?>
    <?= $principal->formEdit(true, true, $input); ?>
    <?= $principal->criaLista($input); ?>  
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>
    
</script>

</html>