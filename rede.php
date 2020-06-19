<?php
require_once("class/principal.php");
$nomeRecurso = 'rede';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'ip_rede',
        'tipo_input' => "text",
        'descricao' => "Ip Rede",
        'table' => true
    ],
    [
        'nome' => 'subnet',
        'tipo_input' => "text",
        'descricao' => "Subnet",
        'table' => true
    ],
    [
        'nome' => 'gateway',
        'tipo_input' => "text",
        'descricao' => "Gateway",
        'table' => true
    ],
    [
        'nome' => 'dns_principal',
        'tipo_input' => "text",
        'descricao' => "DNS Principal",
        'table' => false    
    ],
    [
        'nome' => 'dns_secundario',
        'tipo_input' => "text",
        'descricao' => "DNS Secundario",
        'table' => false
    ],
    [
        'nome' => 'tipo_rede',
        'tipo_input' => "text",
        'descricao' => "Tipo Rede",
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