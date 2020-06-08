<?php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU';
$nomeRecurso = 'ds';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title><?= $nomeRecurso; ?> Adm</title>
</head>

<body>
    <div class="col-xs-12">
        <button id="adicionar" class="btn btn-primary">Add <?= $nomeRecurso; ?></button>
    </div>
    <div id="<?= $nomeRecurso; ?>-editar" style="display: none;">
        <div class="form-group">
            <label for="codigo">Codigo:</label>
            <input type="text" class="form-control" readonly id="codigo">
        </div>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome">
        </div>
        <div class="form-group">
            <label for="tipo_disco">tipo_disco:</label>
            <input type="text" class="form-control" id="tipo_disco">
        </div>
        <div class="form-group">
            <label for="dataCriacao">Data Criação:</label>
            <input type="text" class="form-control" readonly id="dataCriacao">
        </div>
        <div class="form-group">
            <label for="dataAtu">Data Atualização:</label>
            <input type="text" class="form-control" readonly id="dataAtu">
        </div>
        <button id="salvar_<?= $nomeRecurso; ?>" class="btn btn-success">Salvar</button>
        <button id="delete_<?= $nomeRecurso; ?>" class="btn btn-danger">Deletar</button>
    </div>
    <div id="<?= $nomeRecurso; ?>-listar" class="col-xs-12">
        <table id="lista" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descricao</th>
                    <th>Manut</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>
<script src="js/jquery.js"></script>
<script src="js/api/funcoes_ajax.js"></script>
<script>
    var token = '<?= $token; ?>';
    var recurso = '<?= $nomeRecurso; ?>';
    var btn_save = $("#salvar_"+recurso);
    var btn_del = $("#delete_"+recurso);
    var btn_add = $("#adicionar");

    btn_save.on("click", function() {
        var divEditar = $("#"+recurso+"-editar");
        var divListar = $("#"+recurso+"-listar");
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_tipo_disco = divEditar.find("#tipo_disco");
        if (inp_nome.val() == "") {
            alert("Campo Nome não pode ser vazio!!!");
            return;
        }
        if (inp_codigo.val() == "") {            
            store(inp_nome.val(),inp_tipo_disco.val());
        } else {
            update(inp_codigo.val(), inp_nome.val(),inp_tipo_disco.val());
        }
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    btn_del.on("click", function() {
        var divEditar = $("#"+recurso+"-editar");
        var divListar = $("#"+recurso+"-listar");
        var inp_codigo = divEditar.find("#codigo");
        if (inp_codigo.val() != "") {
            deletar(inp_codigo.val());
        }
        divEditar.toggle();
        divListar.toggle();

        carregaLista();
    });

    btn_add.on("click", function() {
        var divEditar = $("#"+recurso+"-editar");
        var divListar = $("#"+recurso+"-listar");
        divEditar.toggle();
        divListar.toggle();
        if (divEditar.attr("style") == 'display: none;') {
            carregaLista();
        } else {
            montaEdit();
        }
    });

    function carregaLista() {
        btn_add.text("Add "+recurso+"");
        var dcs = getTodos(recurso);
        var tabela = $("#lista").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var nome = $("<td>").text(element.nome);
                var desc = $("<td>").text(element.tipo_disco);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome).append(desc).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar "+recurso);
        var divEditar = $("#"+recurso+"-editar");
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#tipo_disco").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");        
    }

    function store(nome, tipo_disco) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/"+recurso+"/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "tipo_disco": tipo_disco
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function carregaEdit(codigo) {
        btn_add.text("Listar "+recurso);
        var divEditar = $("#"+recurso+"-editar");
        var divListar = $("#"+recurso+"-listar");
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos(recurso + "/" + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_desc = divEditar.find("#tipo_disco");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_desc.val(data.tipo_disco);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);
        });
    }

    function update(codigo, nome, tipo_disco) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/" + recurso + "/" + codigo + "/edit",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "tipo_disco": tipo_disco
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function deletar(codigo) {        
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/" + recurso + "/" + codigo + "/delete",
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