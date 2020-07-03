<?php
require_once("class/principal.php");
$nomeRecurso = 'ip';
$input = [
    [
        'nome' => 'ip',
        'tipo_input' => "text",
        'descricao' => "Ip",
        'table' => true
    ],
    [
        'nome' => 'situacao',
        'tipo_input' => "text",
        'descricao' => "Situacao",
        'table' => true
    ],
    [
        'nome' => 'codigo_servidor',
        'tipo_input' => "select",
        'descricao' => "Servidor",
        'table' => false,
        'recurso' => 'servidor',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ],
    [
        'nome' => 'codigo_rede',
        'tipo_input' => "select",
        'descricao' => "Codigo Rede",
        'table' => false,
        'recurso' => 'rede',
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
    'id' => 'listar',
    'nome' => 'Listar Todos IPs',
    'cor' => 'primary'
]];
$btns_form = [
    [
        'id' => 'salvar_' . $nomeRecurso,
        'nome' => 'Salvar',
        'cor' => 'success'
    ],
    [
        'id' => 'liberar',
        'nome' => 'Liberar',
        'cor' => 'info'
    ],
    [
        'id' => 'bloquear',
        'nome' => 'Bloquear',
        'cor' => 'warning'
    ]
];

$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes($btns_opc); ?>
    <div id="ip-editar" style="display: none;">
        <div class="form-group">
            <label for="codigo">Codigo:</label>
            <input type="text" class="form-control" readonly id="codigo">
        </div>
        <div class="form-group">
            <label for="ip">Ip:</label>
            <input type="text" class="form-control" readonly id="ip">
        </div>
        <div class="form-group">
            <label for="situacao">Situacao:</label>
            <input type="text" class="form-control" readonly id="situacao">
        </div>
        <div class="form-group">
            <label for="codigo_servidor">Servidor:</label>
            <select class="form-control" id="codigo_servidor" name="codigo_servidor" form="form">
            </select>
        </div>
        <div class="form-group">
            <label for="codigo_rede">Rede:</label>
            <input type="text" class="form-control" readonly id="codigo_rede">
        </div>
        <div class="form-group">
            <label for="dataCriacao">Data Criação:</label>
            <input type="text" class="form-control" readonly id="dataCriacao">
        </div>
        <div class="form-group">
            <label for="dataAtu">Data Atualização:</label>
            <input type="text" class="form-control" readonly id="dataAtu">
        </div>
        <button id="salvar_ip" class="btn btn-success">Salvar</button>
    </div>
    <div id="ip-listar" class="col-xs-12">
        <table id="lista" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Situacao</th>
                    <th>Rede</th>
                    <th>Manut</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    function getTodos(categoria) {
        var settings = {
            "async": true,
            "url": "/api/index.php/infraestrutura/" + categoria,
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            }
        }
        var dados = $.get(settings, function(data) {
            return data;
        });
        return dados;
    }

    function carregaLista() {
        console.log("Puxa Lista");
        var dcs = getTodos("ip");
        var tabela = $("#lista").find("tbody");

        tabela.empty();
        console.log("Carregand Lista");
        dcs.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<tr>");
                var ip = $("<td>").text(element.ip);
                var situacao = $("<td>").text(element.situacao);
                var rede = $("<td>").text(element.rede.nome);
                var vinc = $("<button>").text("Vincular").attr("class", "btn btn-primary edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");
                if (element.situacao == "Livre") {
                    var lib_bloq = $("<button>").text("Bloquear").attr("class", "btn btn-secondary edit-dc").attr("id", element.codigo).attr("onClick", "bloquear(" + element.codigo + ");");
                } else {
                    var lib_bloq = $("<button>").text("Liberar").attr("class", "btn btn-success edit-dc").attr("id", element.codigo).attr("onClick", "liberar(" + element.codigo + ");");
                }

                var edit = $("<td>").append(vinc).append(lib_bloq);
                linha.append(ip).append(situacao).append(rede).append(edit);
                tabela.append(linha);
            }
        });
        console.log("Fim Função");
    }


    function carregaSERVIDOR(codigoSERVIDOR) {
        var recurso = getTodos("servidor");
        var select = $("#codigo_servidor");
        $("#codigo_servidor option").remove();
        recurso.done(function(data) {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var linha = $("<option>");
                linha.val(element.codigo);
                linha.text(element.nome);
                if (codigoSERVIDOR == element.codigo) {
                    linha.attr("selected", "true");
                }
                select.append(linha);
            }
        });
    }

    function inputSelecionadoSERVIDOR() {
        var optionSelected = $("#codigo_servidor option:selected");
        return optionSelected.val();
    };

    function carregaEdit(codigo) {
        var divEditar = $("#ip-editar");
        var divListar = $("#ip-listar");
        divEditar.toggle();
        divListar.toggle();
        var dc = getTodos("ip/" + codigo);
        var inp_codigo = divEditar.find("#codigo");
        var inp_ip = divEditar.find("#ip");
        var inp_situacao = divEditar.find("#situacao");
        var inp_codigo_servidor = divEditar.find("#codigo_servidor");
        var inp_codigo_rede = divEditar.find("#codigo_rede");
        var inp_dataCre = divEditar.find("#dataCriacao");
        var inp_dataAtu = divEditar.find("#dataAtu");
        dc.done(function(data) {
            inp_codigo.val(data.codigo);
            inp_ip.val(data.ip);
            inp_situacao.val(data.situacao);
            carregaSERVIDOR(data.codigo_servidor);
            inp_codigo_rede.val(data.rede.nome);
            inp_dataCre.val(data.created_at)
            inp_dataAtu.val(data.updated_at);
        });
    }

    function liberar(codigo) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/ip/" + codigo + "/libera",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {}
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        setTimeout(function() {
            carregaLista();
        }, 3000);


    }

    function bloquear(codigo) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/ip/" + codigo + "/bloqueia",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {}
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        setTimeout(function() {
            carregaLista();
        }, 3000);
    }

    var btn_liberar = $("#liberar");
    btn_liberar.on("click", function() {
        var input_codigo = $("#codigo");
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/ip/" + input_codigo + "/libera",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {}
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
    });

    var btn_save = $("#salvar_ip");
    btn_save.on("click", function() {
        var divEditar = $("#ip-editar");
        var divListar = $("#ip-listar");
        var inp_codigo = divEditar.find("#codigo");
        var inp_codigo_servidor = divEditar.find("#codigo_servidor");
        atualizar(inp_codigo.val(), inputSelecionadoSERVIDOR());
        divEditar.toggle();
        divListar.toggle();
        carregaLista();
    });

    function atualizar(codigo, codigo_servidor) {
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/ip/" + codigo + "/vincula",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                "Authorization": "Bearer " + token
            },
            "data": {
                "codigo_servidor": codigo_servidor
            }
        }
        var dados = $.ajax(settings, function(data) {
            return data;
        });
        return dados;
    }

    var btn_listar = $("#listar");
    btn_listar.on("click", function() {
        var divEditar = $("#ip-editar");
        var divListar = $("#ip-listar");
        divEditar.toggle();
        divListar.toggle();
        if (divEditar.attr("style") == "display: none;") {
            carregaLista();
        }
    });

    $(function() {
        carregaLista();
    });
</script>

</html>