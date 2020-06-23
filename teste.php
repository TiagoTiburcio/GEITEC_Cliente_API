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
        'tipo_input' => "text",
        'descricao' => "Codigo Servidor",
        'table' => true
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
    
    
    $(function() {
        carregaLista();        
    });
</script>

</html>