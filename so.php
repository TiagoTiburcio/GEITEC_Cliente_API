<?php
require_once("class/principal.php");
$nomeRecurso = 'so';
$input = [
    [   'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true 
    ],
    [   'nome' => 'descricao',
        'tipo_input' => "text",
        'descricao' => "Descrição",
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

    function carregaLista() {
        btn_add.text("Add SO");
        var dcs = getTodos('so');
        var tabela = $("#lista").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var nome = $("<td>").text(element.nome);
                var desc = $("<td>").text(element.descricao);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome).append(desc).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar SO");
        <?= $principal->selectDivPricipaisJS(); ?>
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#descricao").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
    }
    
    function carregaEdit(codigo) {
        btn_add.text("Listar SO");
        <?= $principal->selectDivPricipaisJS();?>
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos('so/' + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_desc = divEditar.find("#descricao");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_desc.val(data.descricao);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);
        });
    }

    <?= $principal->addAjaxJS(); ?>
    

    $(function() {
        carregaLista();
    });
</script>

</html>