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
    <title>Pool Adm</title>
</head>

<body>
    <div class="col-xs-12">
        <button id="adicionar" class="btn btn-primary">Add Pool</button>
    </div>
    <div id="pool-editar" style="display: none;">
        <div class="form-group">
            <label for="codigo">Codigo:</label>
            <input type="text" class="form-control" readonly id="codigo">
        </div>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome">
        </div>
        <div class="form-group">
            <label for="codigo_datacenter">Datacenter:</label>
            <select class="form-control" id="codigo_datacenter" name="codigo_datacenter" form="form">
            </select>
        </div>
        <div class="form-group">
            <label for="dataCriacao">Data Criação:</label>
            <input type="text" class="form-control" readonly id="dataCriacao">
        </div>
        <div class="form-group">
            <label for="dataAtu">Data Atualização:</label>
            <input type="text" class="form-control" readonly id="dataAtu">
        </div>
        <button id="salvar_pool" class="btn btn-success">Salvar</button>
        <button id="delete_pool" class="btn btn-danger">Deletar</button>
    </div>
    <div id="pool-listar" class="col-xs-12">
        <table id="lista" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>Datacenter</th>
                    <th>Pool</th>
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
    var btn_save = $("#salvar_pool");
    var btn_del = $("#delete_pool");
    var btn_add = $("#adicionar");

    btn_save.on("click", function() {
        var divEditar = $("#pool-editar");
        var divListar = $("#pool-listar");
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_codigo_dc = divEditar.find("#codigo_datacenter");
        console.log(inp_codigo_dc);
        
        if (inp_nome.val() == "") {
            alert("Campo Nome não pode ser vazio!!!");
            return;
        }
        if (inp_codigo.val() == "") {
            storePool(inp_nome.val(),inp_codigo_dc.val());
        } else {
            updatePool(inp_codigo.val(), inp_nome.val(),inp_codigo_dc.val());
        }
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    btn_del.on("click", function() {
        var divEditar = $("#pool-editar");
        var divListar = $("#pool-listar");
        var inp_codigo = divEditar.find("#codigo");
        if (inp_codigo.val() != "") {
            deletePool(inp_codigo.val());
        }
        divEditar.toggle();
        divListar.toggle();

        carregaLista();
    });

    btn_add.on("click", function() {
        var divEditar = $("#pool-editar");
        var divListar = $("#pool-listar");
        divEditar.toggle();
        divListar.toggle();        
        if (divEditar.attr("style") == 'display: none;') {
            carregaLista();
        } else {
            montaEdit();
        }
    });

    function carregaLista() {
        btn_add.text("Add Pool");
        var dcs = getTodos('pool');
        var tabela = $("#lista").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var nome_dc = $("<td>").text(element.dc.nome);
                var nome = $("<td>").text(element.nome);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome_dc).append(nome).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar Pool");
        var divEditar = $("#pool-editar");
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#codigo_datacenter").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
        carregaDC();
    }

    function storePool(nome, codigo_datacenter) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/pool/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "codigo_datacenter": codigo_datacenter
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function carregaEdit(codigo) {
        btn_add.text("Listar Pool");
        var divEditar = $("#pool-editar");
        var divListar = $("#pool-listar");
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos('pool/' + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_dc = divEditar.find("#codigo_datacenter");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_dc.val(data.codigo_datacenter);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);
        });
        carregaDC(inp_dc.val());        

    }

    function updatePool(codigo, nome, codigoDC) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/pool/" + codigo + "/edit",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "codigo_datacenter": codigoDC
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function deletePool(codigo) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/pool/" + codigo + "/delete",
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

    function carregaDC(codigoDC) {
        var dcs = getTodos('datacenter');
        $("#codigo_datacenter").empty();
        dcs.done(function(data) {
            $(data).each(function() {
                var linha = $("<option>");
                linha.val(this.codigo_datacenter);
                linha.text(this.nome);
                if (codigoDC == this.codigo_datacenter) {
                    linha.attr('selected', 'true');
                }
                $("#codigo_datacenter").append(linha);
            });
        });
    }
</script>

</html>