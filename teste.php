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
        'tipo_input' => "text",
        'descricao' => "Codigo Datacenter",
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

    <div class="form-group">
        <label for="codigo_so_pk">Sistema Operacional</label>
        <select class="form-control" id="codigo_so_pk" name="codigo_so_pk" form="form">
        </select>
    </div>

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

    function carregaSO(codigoSO) {
        var sos = getTodos('so');
        var select = $("#codigo_so_pk");
        sos.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<option>");
                linha.val(element.codigo);
                linha.text(element.nome);
                if (codigoSO == element.codigo) {
                    linha.attr('selected', 'true');
                }                
                select.append(linha);
            }
        });

    }
    $(function() {
        carregaLista();
        carregaSO('1');
    });
</script>

</html>