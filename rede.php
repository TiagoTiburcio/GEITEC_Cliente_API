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
        'nome' => 'Libera Todos',
        'cor' => 'warning'
    ]
];
$principal = new Principal($nomeRecurso, $input);
$token = $principal->token();

echo $principal->cabecalho();
?>

<body>
    <?= $principal->opcoes($btns_opc); ?>
    <?= $principal->formEdit($input,$btns_form); ?>
    <?= $principal->criaLista($input); ?>  
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>
    
</script>

</html>