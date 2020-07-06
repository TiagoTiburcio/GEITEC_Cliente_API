<?php
require_once("class/principal.php");
$nomeRecurso = 'rede';
$input = [
    [
        'nome' => 'nome',
        'tipo_input' => "text",
        'descricao' => "Nome",
        'table' => true
    ],
    [
        'nome' => 'ip_rede',
        'tipo_input' => "text",
        'descricao' => "Ip Rede",
        'table' => true
    ],
    [
        'nome' => 'subnet',
        'tipo_input' => "text",
        'descricao' => "Subnet",
        'table' => true
    ],
    [
        'nome' => 'gateway',
        'tipo_input' => "text",
        'descricao' => "Gateway",
        'table' => true
    ],
    [
        'nome' => 'dns_principal',
        'tipo_input' => "text",
        'descricao' => "DNS Principal",
        'table' => false
    ],
    [
        'nome' => 'dns_secundario',
        'tipo_input' => "text",
        'descricao' => "DNS Secundario",
        'table' => false
    ],
    [
        'nome' => 'tipo_rede',
        'tipo_input' => "text",
        'descricao' => "Tipo Rede",
        'table' => true
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
        'id' => 'delete_' . $nomeRecurso,
        'nome' => 'Deletar',
        'cor' => 'danger'
    ],
    [
        'id' => 'cria_todos',
        'nome' => 'Cria Todos IPS',
        'cor' => 'primary'
    ],
    [
        'id' => 'libera_todos',
        'nome' => 'Libera Todos IPS',
        'cor' => 'warning'
    ],
    [
        'id' => 'exclui_todos',
        'nome' => 'Exclui Todos IPS',
        'cor' => 'danger'
    ]
];
$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes($btns_opc); ?>
    <?= $principal->formEdit($input, $btns_form); ?>
    <?= $principal->criaLista($input); ?>
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>

    var btn_criar_ip = $("#cria_todos");
    btn_criar_ip.on("click", function() {
        criaTodosIPs();
    });

    function criaTodosIPs() {
        var codigo = $("#codigo").val();
        var settings = {
            "async": true,
            "method": "POST",
            "url": "/api/index.php/infraestrutura/rede/" + codigo + "/ips",
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
            window.location.replace("ip.php");
        }, 3000);
    }

    function Temporizador(initiate) {
        if (initiate !== true) {
            var inp_codigo = $("#codigo").val();
            var div_listar = $("#rede-listar");
            var btn_del = $("#delete_rede");
            var btn_cria_ips = $("#cria_todos");
            var btn_libera_ips = $("#libera_todos");
            var btn_exclui_ips = $("#exclui_todos");
            if ((inp_codigo == 0) && (div_listar.attr("style") == "display: none;")) {
                btn_del.attr("style", "display: none;");
                btn_cria_ips.attr("style", "display: none;");
                btn_exclui_ips.attr("style", "display: none;");
                btn_libera_ips.attr("style", "display: none;");
            } else {
                if ((inp_codigo != $("#inp_usuario").text()) && (div_listar.attr("style") == "display: none;")) {
                    var cad_ip = getTodos("rede/" + inp_codigo);
                    cad_ip.done(function(data) {
                        if (data.qtd_ips != 0) {
                            btn_del.attr("style", "display: none;");
                            btn_cria_ips.attr("style", "display: none;");
                            btn_exclui_ips.attr("style","");
                            btn_libera_ips.attr("style","");
                            if (data.ips_vinculado == 0) {
                                btn_exclui_ips.attr("style", "");
                                btn_libera_ips.attr("style", "display: none;");
                            } else {
                                btn_exclui_ips.attr("style", "display: none;");
                                btn_libera_ips.attr("style", "");
                            }
                        } else {
                            btn_del.attr("style", "");
                            btn_cria_ips.attr("style", "");
                        }
                    });
                }
            }
        }
        setTimeout(Temporizador, 3000);
    }

    $(function() {
        Temporizador(true);
    });
</script>

</html>