<?php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU';
$nomeRecurso = 'servidor';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title><?= strtoupper($nomeRecurso); ?> Adm</title>
</head>

<body>
    <div class="col-xs-12">
        <button id="adicionar" class="btn btn-primary">Add <?= strtoupper($nomeRecurso); ?></button>
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
            <label for="descricao">Descrição:</label>
            <input type="text" class="form-control" id="descricao">
        </div>
        <div class="form-group">
            <label for="memoria">Memoria:</label>
            <input type="text" class="form-control" id="memoria">
        </div>        
        <div class="form-group">
            <label for="cpu">CPU:</label>
            <input type="text" class="form-control" id="cpu">
        </div>        
        <div class="form-group">
            <label for="situacao">Situacao:</label>
            <input type="text" class="form-control" id="situacao">
        </div>
        <div class="form-group">
            <label for="codigo_so">codigo_so:</label>
            <input type="text" class="form-control" id="codigo_so">
        </div>
        <div class="form-group">
            <label for="codigo_pool">Codigo Pool:</label>
            <input type="text" class="form-control" id="codigo_pool">
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
                    <th>Pool</th>
                    <th>Nome</th>
                    <th>Situacao</th>
                    <th>SO</th>
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
    var btn_save = $("#salvar_" + recurso);
    var btn_del = $("#delete_" + recurso);
    var btn_add = $("#adicionar");

    btn_save.on("click", function() {
        var divEditar = $("#" + recurso + "-editar");
        var divListar = $("#" + recurso + "-listar");
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_descricao = divEditar.find("#descricao");
        var inp_memoria = divEditar.find("#memoria");
        var inp_cpu = divEditar.find("#cpu");
        var inp_situacao = divEditar.find("#situacao");
        var inp_codigo_so = divEditar.find("#codigo_so");
        var inp_codigo_pool = divEditar.find("#codigo_pool");
        var teste = (
            (inp_nome.val() == "") ||
            (inp_descricao.val() == "") ||
            (inp_memoria.val() == "") ||
            (inp_cpu.val() == "") ||
            (inp_situacao.val() == "") ||
            (inp_codigo_so.val() == "") ||
            (inp_codigo_pool.val() == "")
        );
        if (teste) {
            alert("Campo Obrigatório não preenchido!!!");
            return;
        }
        if (inp_codigo.val() == "") {
            store(
                inp_nome.val(),
                inp_descricao.val(),
                inp_memoria.val(),
                inp_cpu.val(),
                inp_situacao.val(),
                inp_codigo_so.val(),
                inp_codigo_pool.val()
            );
        } else {
            update(
                inp_codigo.val(),
                inp_nome.val(),
                inp_descricao.val(),
                inp_memoria.val(),
                inp_cpu.val(),
                inp_situacao.val(),
                inp_codigo_so.val(),
                inp_codigo_pool.val()
            );
        }
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    btn_del.on("click", function() {
        var divEditar = $("#" + recurso + "-editar");
        var divListar = $("#" + recurso + "-listar");
        var inp_codigo = divEditar.find("#codigo");
        if (inp_codigo.val() != "") {
            deletar(inp_codigo.val());
        }
        divEditar.toggle();
        divListar.toggle();

        carregaLista();
    });

    btn_add.on("click", function() {
        var divEditar = $("#" + recurso + "-editar");
        var divListar = $("#" + recurso + "-listar");
        divEditar.toggle();
        divListar.toggle();
        if (divEditar.attr("style") == 'display: none;') {
            carregaLista();
        } else {
            montaEdit();
        }
    });

    function carregaLista() {
        btn_add.text("Add " + recurso.toUpperCase() + "");
        var dcs = getTodos(recurso);
        var tabela = $("#lista").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var nome = $("<td>").text(element.nome);
                var pool = $("<td>").text(element.pool.nome);
                var situacao = $("<td>").text(element.situacao);
                var so = $("<td>").text(element.so.nome);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(pool).append(nome).append(situacao).append(so).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar " + recurso.toUpperCase());
        var divEditar = $("#" + recurso + "-editar");
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#descricao").val("");
        divEditar.find("#memoria").val("");
        divEditar.find("#cpu").val("");
        divEditar.find("#situacao").val("");
        divEditar.find("#codigo_so").val("");
        divEditar.find("#codigo_pool").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
    }

    function store(
        nome,
        descricao,
        memoria,
        cpu,
        situacao,
        codigo_so,
        codigo_pool) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/" + recurso + "/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "nome": nome,
                "descricao": descricao,
                "memoria": memoria,
                "cpu": cpu,
                "situacao": situacao,
                "codigo_so": codigo_so,
                "codigo_pool": codigo_pool
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function carregaEdit(codigo) {
        btn_add.text("Listar " + recurso.toUpperCase());
        var divEditar = $("#" + recurso + "-editar");
        var divListar = $("#" + recurso + "-listar");
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos(recurso + "/" + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_nome = divEditar.find("#nome");
        var inp_descricao = divEditar.find("#descricao");
        var inp_memoria = divEditar.find("#memoria");
        var inp_cpu = divEditar.find("#cpu");
        var inp_situacao = divEditar.find("#situacao");
        var inp_codigo_so = divEditar.find("#codigo_so");
        var inp_codigo_pool = divEditar.find("#codigo_pool");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_descricao.val(data.descricao);
            inp_memoria.val(data.memoria);
            inp_cpu.val(data.cpu);
            inp_situacao.val(data.situacao);
            inp_codigo_so.val(data.codigo_so);
            inp_codigo_pool.val(data.codigo_pool);
            inp_dataCre.val(data.created_at);
            inp_dataAtu.val(data.updated_at);
        });
    }

    function update(
        codigo,
        nome,
        descricao,
        memoria,
        cpu,
        situacao,
        codigo_so,
        codigo_pool) {
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
                "descricao": descricao,
                "memoria": memoria,
                "cpu": cpu,
                "situacao": situacao,
                "codigo_so": codigo_so,
                "codigo_pool": codigo_pool
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