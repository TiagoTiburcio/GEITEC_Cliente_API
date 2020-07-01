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
        'table' => true,
        'recurso' => 'servidor',
        'codigo_recurso' => 'codigo',
        'nome_recurso' => 'nome'
    ],
    [
        'nome' => 'codigo_rede',
        'tipo_input' => "select",
        'descricao' => "Codigo Rede",
        'table' => true,        
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
$btns_opc = [
  
];
$btns_form = [
  [
      'id' => 'salvar_' . $nomeRecurso ,
      'nome' => 'Salvar',
      'cor' => 'success'
  ], 
  [
      'id' => 'liberar' ,
      'nome' => 'Liberar',
      'cor' => 'info'
  ], 
  [
      'id' => 'bloquear' ,
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
    <?= $principal->formEdit($input, $btns_form); ?>
    <?= $principal->criaLista($input); ?>  
</body>
<script src="js/jquery.js"></script>
<script>
    var token = '<?= $token; ?>';

    <?= $principal->saidaJS(); ?>
    
</script>

</html>