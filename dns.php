<?php
require_once("class/principal.php");
$nomeRecurso = 'dns';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'codigo_ip',
        'tipo_input' => "select",
        'descricao' => "Codigo Ip",
        'table' => true,
        'recurso' => 'ip',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'ip'
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