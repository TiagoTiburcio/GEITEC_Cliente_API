<?php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title>DataCenter Manutenção</title>
</head>

<body>
    <div class="col-xs-12">
        <button id="adicionar" class="btn btn-primary">Add DC</button>        
    </div>
    <div id="data-center-editar" style="display: none;">
        <div class="form-group">
            <label for="codigo">Codigo:</label>
            <input type="text" class="form-control" readonly id="codigo">
        </div>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome">
        </div>
        <div class="form-group">
            <label for="dataCriacao">Data Criação:</label>
            <input type="text" class="form-control" readonly id="dataCriacao">
        </div>
        <div class="form-group">
            <label for="dataAtu">Data Atualização:</label>
            <input type="text" class="form-control" readonly id="dataAtu">
        </div>
        <button id="salvar_datacenter" class="btn btn-success">Salvar</button>
        <button id="delete_datacenter" class="btn btn-danger">Deletar</button>
    </div>
    <div id="data-center-listar" class="col-xs-12">
        <table id="lista" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>nome</th>
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
    var btn_save = $("#salvar_datacenter");
    var btn_del = $("#delete_datacenter");
    var btn_add = $("#adicionar");

    btn_save.on("click", function() {
        var divEditar = $("#data-center-editar");
        var divListar = $("#data-center-listar");
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        if (inp_nome.val() == "") {
            alert("Campo Nome não pode ser vazio!!!");
            return;            
        }
        if (inp_codigo.val() == "") {
            storeDC(inp_nome.val());
        } else {
            updateDC(inp_codigo.val(), inp_nome.val());
        }
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    btn_del.on("click", function() {
        var divEditar = $("#data-center-editar");
        var divListar = $("#data-center-listar");
        var inp_codigo = divEditar.find("#codigo");
        if (inp_codigo.val() != "") {
            deleteDC(inp_codigo.val());
        }
        divEditar.toggle();
        divListar.toggle();
        
        carregaLista();
    });

    btn_add.on("click", function() {
        var divEditar = $("#data-center-editar");
        var divListar = $("#data-center-listar");
        divEditar.toggle();
        divListar.toggle();
        if (divEditar.attr("style") == 'display: none;') {
            carregaLista();
        } else {
            montaEdit();
        }
    });

    function carregaLista() {
        btn_add.text("Add DC");
        var dcs = getTodos('datacenter');
        var tabela = $("#lista").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var nome = $("<td>").text(element.nome);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar DC");
        var divEditar = $("#data-center-editar");                
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
    }

    function storeDC(nome){
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/datacenter/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }
    function carregaEdit(codigo) {
        btn_add.text("Listar DC");
        var divEditar = $("#data-center-editar");
        var divListar = $("#data-center-listar");
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos('datacenter/' + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);

        });
    }

    function updateDC(codigo, nome) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/datacenter/" + codigo + "/edit",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function deleteDC(codigo) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/datacenter/" + codigo + "/delete",
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
    })
</script>

</html>