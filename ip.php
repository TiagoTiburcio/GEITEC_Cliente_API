<?php
require_once("class/principal.php");
$nomeRecurso = 'ip';
$input = [
    [
        'nome' => 'ip',
        'tipo_input' => "text",
        'descricao' => "Ip",
        'table' => true
    ],
    [
        'nome' => 'situacao',
        'tipo_input' => "text",
        'descricao' => "Situacao",
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
        'nome' => 'codigo_rede',
        'tipo_input' => "select",
        'descricao' => "Codigo Rede",
        'table' => true,        
        'recurso' => 'rede',
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