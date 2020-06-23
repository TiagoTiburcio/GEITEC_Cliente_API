<?php
require_once("class/principal.php");
$nomeRecurso = 'pool';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'codigo_datacenter',
        'tipo_input' => "select",
        'descricao' => "codigo_datacenter",
        'table' => true,
        'recurso' => 'datacenter',
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