<?php
require_once("class/principal.php");
$nomeRecurso = 'so';
$principal = new Principal($nomeRecurso);
$token = $principal->token();
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
echo $principal->cabecalho($nomeRecurso);
?>

<body>
    <?= Principal::opcoes($nomeRecurso); ?>    
    <?= Principal::formEdit($nomeRecurso,true,true,$input); ?>    
    <?= Principal::criaLista($nomeRecurso,$input); ?>
</body>
<script src="js/jquery.js"></script>
<script src="js/api/funcoes_ajax.js"></script>
<script>
    var token = '<?= $token; ?>';
    var btn_save = $("#salvar_<?= $nomeRecurso;?>");
    var btn_del = $("#delete_<?= $nomeRecurso;?>");
    var btn_add = $("#adicionar");
    function buscarDivPrincipais() {
        
    }
    btn_save.on("click", function() {
        buscarDivPrincipais();
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_descricao = divEditar.find("#descricao");
        if (inp_nome.val() == "") {
            alert("Campo Nome não pode ser vazio!!!");
            return;
        }
        if (inp_codigo.val() == "") {
            store(inp_nome.val(), inp_descricao.val());
        } else {
            updateSO(inp_codigo.val(), inp_nome.val(), inp_descricao.val());
        }
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    btn_del.on("click", function() {
        buscarDivPrincipais();
        var inp_codigo = divEditar.find("#codigo");
        if (inp_codigo.val() != "") {
            deleteSO(inp_codigo.val());
        }
        divEditar.toggle();
        divListar.toggle();

        carregaLista();
    });

    btn_add.on("click", function() {
        buscarDivPrincipais();
        divEditar.toggle();
        divListar.toggle();
        if (divEditar.attr("style") == 'display: none;') {
            carregaLista();
        } else {
            montaEdit();
        }
    });

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
        buscarDivPrincipais();
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#descricao").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
    }

    function storeSO(nome, descricao) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/so/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "descricao": descricao
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function carregaEdit(codigo) {
        btn_add.text("Listar SO");
        buscarDivPrincipais()
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

    function updateSO(codigo, nome, descricao) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/so/" + codigo + "/edit",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "descricao": descricao
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function deleteSO(codigo) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/so/" + codigo + "/delete",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    

    $(function() {
        carregaLista();
    });
</script>

</html>