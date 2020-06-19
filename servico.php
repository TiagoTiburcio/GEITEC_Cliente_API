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
        'descricao' => "Descricao",
        'table' => true
    ],
    [
        'nome' => 'tipo_servico',
        'tipo_input' => "text",
        'descricao' => "Memoria",
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
        'tipo_input' => "text",
        'descricao' => "Codigo Servidor",
        'table' => true
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

    <?= $principal->btnAddJS(); ?>
    <?= $principal->btnSaveJS(); ?>
    <?= $principal->btnDelJS(); ?>
    <?= $principal->carregaListaJS(); ?>
    <?= $principal->montaEditJS(); ?>
    <?= $principal->carregaEditJS(); ?>
    <?= $principal->addAjaxJS(); ?>


    $(function() {
        carregaLista();
    });
</script>

</html>