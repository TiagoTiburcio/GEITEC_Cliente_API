<?php
require_once("class/principal.php");
$nomeRecurso = 'servico';
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
        'descricao' => "Descrição",
        'table' => true
    ],
    [
        'nome' => 'tipo_servico',
        'tipo_input' => "text",
        'descricao' => "Tipo Servico",
        'table' => true
    ],
    [
        'nome' => 'situacao',
        'tipo_input' => "text",
        'descricao' => "Situação",
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