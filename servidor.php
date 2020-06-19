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
        'tipo_input' => "text",
        'descricao' => "Codigo SO",
        'table' => true
    ],
    [
        'nome' => 'codigo_pool',
        'tipo_input' => "text",
        'descricao' => "Codigo Pool",
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
<script src="js/api/funcoes_ajax.js"></script>
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