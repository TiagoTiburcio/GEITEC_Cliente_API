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
        'tipo_input' => "text",
        'descricao' => "Codigo Servidor",
        'table' => true
    ],
    [
        'nome' => 'codigo_rede',
        'tipo_input' => "text",
        'descricao' => "Codigo Rede",
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