<?php
require_once("class/principal.php");
$nomeRecurso = 'usuario';
$input = [
    [
        'nome' => 'usuario',
        'tipo_input' => "text",
        'descricao' => "Usuario",
        'table' => true
    ],
    [
        'nome' => 'descricao',
        'tipo_input' => "text",
        'descricao' => "Descricao",
        'table' => true
    ],
    [
        'nome' => 'tipo_usuario',
        'tipo_input' => "text",
        'descricao' => "Tipo Usuario",
        'table' => true
    ],
    [
        'nome' => 'codigo_servico',
        'tipo_input' => "select",
        'descricao' => "Codigo Serviço",
        'table' => true,
        'recurso' => 'servico',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ]
];
//botoes opçoes 
/**
 * id = id do botão chamado no javascript
 * nome = nome que irá aparecer
 * cor = 
 *      primary - Azul e branco
 *      secondary - Cinza e branco 
 *      success - Verde e Branco
 *      info - Azul Claro e Branco
 *      warning - Amarelo e Preto
 *      danger - Vermelho e Branco
 *      dark - Preto e Branco
 *      light - Cinza Claro e Preto
 *      link - transparente e Azul
 */
$btns_opc = [[
    'id' => 'adicionar',
    'nome' => 'Listar Todos',
    'cor' => 'primary'
]];
$btns_form = [
    [
        'id' => 'salvar_' . $nomeRecurso,
        'nome' => 'Salvar',
        'cor' => 'success'
    ],
    [
        'id' => 'delete_' . $nomeRecurso,
        'nome' => 'Deletar',
        'cor' => 'danger'
    ],
    [
        'id' => 'senhas',
        'nome' => 'Carregar Senhas',
        'cor' => 'primary'
    ]
];

$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes($btns_opc); ?>
    <div id="usuario-editar" style="display: none;">
        <div class="form-group">
            <label for="codigo">Codigo:</label>
            <input type="text" class="form-control" readonly id="codigo">
        </div>
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" id="usuario">
        </div>
        <div class="form-group">
            <label for="descricao">Descricao:</label>
            <input type="text" class="form-control" id="descricao">
        </div>
        <div class="form-group">
            <label for="tipo_usuario">Tipo Usuario:</label>
            <input type="text" class="form-control" id="tipo_usuario">
        </div>
        <div class="form-group">
            <label for="codigo_servico">Codigo Serviço:</label>
            <select class="form-control" id="codigo_servico" name="codigo_servico" form="form">
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
        <button id="salvar_usuario" class="btn btn-success">Salvar</button>
        <button id="delete_usuario" class="btn btn-danger">Deletar</button>
        <button id="btn_senhas" class="btn btn-secondary">Listar Senhas</button>
        <button id="btn_nova_senha" class="btn btn-primary">Nova Senha</button>
        <div id="senhas-listar" class="col-xs-12">
            <div class="alert alert-success" id="alert_senha" style="display: none;">
                <p>Data Criacao: <span id="inp_data_criacao"></span></p>
                <p>Situacao: <span id="inp_situacao"></span></p>
                <p>Codigo Usuário: <span id="inp_usuario"></span></p>
                <p>Ultima Atualização: <span id="inp_updated"></span></p>
                <div class="input-group">
                    <input type="text" class="form-control" id="inp_cp_senha" name="inp_cp_senha" readonly value="hjagdsaksjhgdkjhgdskj">
                    <div class="input-group-btn">
                        <button class="btn btn-secondary" id="btn_cp_senha">Copiar Senha</button>
                    </div>
                </div>
            </div>
            <table id="senhas" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Situacao</th>
                        <th>Criação</th>
                        <th>Expiração</th>
                        <th>Manut</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <?= $principal->criaLista($input); ?>
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>

    var btn_senhas = $("#btn_senhas");
    btn_senhas.on("click", function() {
        carregaSenhas($("#codigo").val());
    });

    function carregaSenhas(codigo) {
        var dcs = getTodos("senha");
        var tabela = $("#senhas").find("tbody");
        tabela.empty();
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                if (element.codigo_usuario == codigo) {
                    var linha = $("<tr>");
                    var situacao = $("<td>").text(element.situacao);
                    var data_criacao = $("<td>").text(element.data_criacao);
                    var data_expiracao = $("<td>").text(element.data_expiracao);
                    var link = $("<button>").text("Mostrar").attr("class", "btn btn-secondary edit-dc").attr("id", element.codigo).attr("onClick", "mostraSenha(" + element.codigo + ");");
                    var edit = $("<td>").append(link);
                    if (element.situacao == "Ativo") {
                        var expira = $("<button>").text("Expirar").attr("class", "btn btn-danger edit-dc").attr("id", element.codigo).attr("onClick", "expriraSenha(" + element.codigo + ");");
                        edit.append(expira);
                    }
                    linha.append(situacao).append(data_criacao).append(data_expiracao).append(edit);
                    tabela.append(linha);
                }

            }
        });
    }

    function mostraSenha(codigo) {
        var dcs = getTodos("senha/" + codigo);
        dcs.done(function(data) {
            $("#alert_senha").attr("style", "display: block;");
            $("#inp_cp_senha").val(data.senha);
            $("#inp_situacao").text(data.situacao);
            $("#inp_updated").text(data.updated_at);
            $("#inp_data_criacao").text(data.data_criacao);
            $("#inp_usuario").text(data.codigo_usuario);
        });

    }
    var btn_cp_senha = $("#btn_cp_senha");
    btn_cp_senha.on("click", function() {
        copiarTexto();
        $("#alert_senha").attr("style", "display: none;");
    });

    var btn_nova_senha = $("#btn_nova_senha");
    btn_nova_senha.on("click", function() {
        var codigo_usuario = $("#codigo").val();
        var nova_senha = novaSenha(codigo_usuario);
        nova_senha.done(function(data) {
            carregaSenhas(codigo_usuario);
        });
    });

    function novaSenha(codigo_usuario) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/senha/",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "codigo_usuario": codigo_usuario
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function expriraSenha(codigo) {
        var edit = editSenha(codigo, "Expirado");
        edit.done(function (data) {
            carregaSenhas($("#codigo").val());    
        })
    }

    function editSenha(codigo, situacao) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/senha/" + codigo + "/edit",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "situacao": situacao
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    function copiarTexto() {
        var textoCopiado = document.getElementById("inp_cp_senha");
        textoCopiado.select();
        document.execCommand("Copy");
        alert("Texto Copiado: " + textoCopiado.value);

    }

    function Temporizador(initiate) {
        if (initiate !== true) {
            var inp_codigo = $("#codigo").val();
            if (inp_codigo != $("#inp_usuario").text()) {
                carregaSenhas(inp_codigo);
                $("#alert_senha").attr("style", "display: none;");
                $("#inp_usuario").text(inp_codigo);
            }
        }
        setTimeout(Temporizador, 3000);
    }

    $(function() {
        Temporizador(true);
    });
</script>

</html>