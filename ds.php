<?php
require_once("class/principal.php");
$nomeRecurso = 'ds';
$input = [
    [   'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true 
],
[   'nome' => 'tipo_disco',
        'tipo_input' => "text",
        'descricao' => "Tipo Disco",
        'table' => true 
    ]
];

$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes(); ?>    
    <?= $principal->formEdit(true,true,$input); ?>    
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