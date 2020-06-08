<?php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU';
$nomeRecurso = 'disco';
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
            <label for="tamanho">Tamanho:</label>
            <input type="text" class="form-control" id="tamanho">
        </div>
        <div class="form-group">
            <label for="codigo_servidor">Codigo Servidor:</label>
            <input type="text" class="form-control" id="codigo_servidor">
        </div>
        <div class="form-group">
            <label for="codigo_ds">codigo DS:</label>
            <input type="text" class="form-control" id="codigo_ds">
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
                    <th>Tamanho</th>
                    <th>Servidor</th>
                    <th>DS</th>
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
        var inp_tamanho = divEditar.find("#tamanho");
        var inp_codigo_servidor = divEditar.find("#codigo_servidor");
        var inp_codigo_ds = divEditar.find("#codigo_ds");
        if (inp_nome.val() == "") {
            alert("Campo Nome não pode ser vazio!!!");
            return;
        }
        if (inp_codigo.val() == "") {            
            store(
                inp_nome.val(),
                inp_tamanho.val(),
                inp_codigo_servidor.val(),
                inp_codigo_ds.val()
            );
        } else {
            update(
                inp_codigo.val(),
                inp_nome.val(),
                inp_tamanho.val(),
                inp_codigo_servidor.val(),
                inp_codigo_ds.val()
            );
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
                var tam = $("<td>").text(element.tamanho);
                var srv = $("<td>").text(element.codigo_servidor + " - " + element.servidor.nome);
                var ds = $("<td>").text(element.codigo_ds + " - " + element.ds.nome);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome).append(tam).append(srv).append(ds).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar "+recurso);
        var divEditar = $("#"+recurso+"-editar");
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#tamanho").val("");
        divEditar.find("#codigo_servidor").val("");
        divEditar.find("#codigo_ds").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");        
    }

    function store(nome, tamanho, codigo_servidor, codigo_ds) {
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
                "tamanho": tamanho,
                "codigo_servidor": codigo_servidor,
                "codigo_ds": codigo_ds
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
        var inp_tam = divEditar.find("#tamanho");
        var inp_cod_srv = divEditar.find("#codigo_servidor");
        var inp_cod_ds = divEditar.find("#codigo_ds");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_tam.val(data.tamanho);
            inp_cod_srv.val(data.codigo_servidor);
            inp_cod_ds.val(data.codigo_ds);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);
        });
    }

    function update(codigo, nome, tamanho, codigo_servidor, codigo_ds) {
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
                "tamanho": tamanho,
                "codigo_servidor": codigo_servidor,
                "codigo_ds": codigo_ds,
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