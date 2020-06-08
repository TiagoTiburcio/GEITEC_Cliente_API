<?php
require_once("class/principal.php");
$nomeRecurso = 'rede';
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
            <label for="ip_rede">IP Rede:</label>
            <input type="text" class="form-control" id="ip_rede">
        </div>
        <div class="form-group">
            <label for="subnet">Mascara:</label>
            <input type="text" class="form-control" id="subnet">
        </div>
        <div class="form-group">
            <label for="gateway">Gateway:</label>
            <input type="text" class="form-control" id="gateway">
        </div>
        <div class="form-group">
            <label for="dns_principal">DNS Principal:</label>
            <input type="text" class="form-control" id="dns_principal">
        </div>
        <div class="form-group">
            <label for="dns_secundario">DNS Secundario:</label>
            <input type="text" class="form-control" id="dns_secundario">
        </div>
        <div class="form-group">
            <label for="tipo_rede">Tipo Rede:</label>
            <input type="text" class="form-control" id="tipo_rede">
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
                    <th>IP Rede</th>
                    <th>Subnet</th>
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
        var inp_ip_rede = divEditar.find("#ip_rede");
        var inp_subnet = divEditar.find("#subnet");
        var inp_gateway = divEditar.find("#gateway");
        var inp_dns_principal = divEditar.find("#dns_principal");
        var inp_dns_secundario = divEditar.find("#dns_secundario");
        var inp_tipo_rede = divEditar.find("#tipo_rede");
        var teste = (
            (inp_nome.val() == "") ||
            (inp_ip_rede.val() == "") ||
            (inp_subnet.val() == "") ||
            (inp_gateway.val() == "") ||
            (inp_dns_principal.val() == "") ||
            (inp_dns_secundario.val() == "") ||
            (inp_tipo_rede.val() == "")
        );
        if (teste) {
            alert("Campo Obrigatório não preenchido!!!");
            return;
        }
        if (inp_codigo.val() == "") {
            store(
                inp_nome.val(),
                inp_ip_rede.val(),
                inp_subnet.val(),
                inp_gateway.val(),
                inp_dns_principal.val(),
                inp_dns_secundario.val(),
                inp_tipo_rede.val()
            );
        } else {
            update(
                inp_codigo.val(),
                inp_nome.val(),
                inp_ip_rede.val(),
                inp_subnet.val(),
                inp_gateway.val(),
                inp_dns_principal.val(),
                inp_dns_secundario.val(),
                inp_tipo_rede.val()
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
                var ip = $("<td>").text(element.ip_rede);
                var subnet = $("<td>").text(element.subnet);
                var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                var edit = $("<td>").append(link);

                linha.append(nome).append(ip).append(subnet).append(edit);
                tabela.append(linha);
            }
        });
    }

    function montaEdit() {
        btn_add.text("Listar " + recurso.toUpperCase());
        var divEditar = $("#" + recurso + "-editar");
        divEditar.find("#codigo").val("");
        divEditar.find("#nome").val("");
        divEditar.find("#ip_rede").val("");
        divEditar.find("#subnet").val("");
        divEditar.find("#gateway").val("");
        divEditar.find("#dns_principal").val("");
        divEditar.find("#dns_secundario").val("");
        divEditar.find("#tipo_rede").val("");
        divEditar.find("#dataCriacao").val("");
        divEditar.find("#dataAtu").val("");
    }

    function store(
        nome,
        ip_rede,
        subnet,
        gateway,
        dns_principal,
        dns_secundario,
        tipo_rede) {
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
                "ip_rede": ip_rede,
                "subnet": subnet,
                "gateway": gateway,
                "dns_principal": dns_principal,
                "dns_secundario": dns_secundario,
                "tipo_rede": tipo_rede
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
        var inp_ip_rede = divEditar.find("#ip_rede");
        var inp_subnet = divEditar.find("#subnet");
        var inp_gateway = divEditar.find("#gateway");
        var inp_dns_principal = divEditar.find("#dns_principal");
        var inp_dns_secundario = divEditar.find("#dns_secundario");
        var inp_tipo_rede = divEditar.find("#tipo_rede");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_nome.val(data.nome);
            inp_ip_rede.val(data.ip_rede);
            inp_subnet.val(data.subnet);
            inp_gateway.val(data.gateway);
            inp_dns_principal.val(data.dns_principal);
            inp_dns_secundario.val(data.dns_secundario);
            inp_tipo_rede.val(data.tipo_rede);
            inp_dataCre.val(data.created_at);
            inp_dataAtu.val(data.updated_at);
        });
    }

    function update(
        codigo,
        nome,
        ip_rede,
        subnet,
        gateway,
        dns_principal,
        dns_secundario,
        tipo_rede) {
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
                "ip_rede": ip_rede,
                "subnet": subnet,
                "gateway": gateway,
                "dns_principal": dns_principal,
                "dns_secundario": dns_secundario,
                "tipo_rede": tipo_rede
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