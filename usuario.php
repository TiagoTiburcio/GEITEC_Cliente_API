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