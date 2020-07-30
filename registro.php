<?php
$exemplo = '<h2>O que &eacute; Lorem Ipsum?</h2>
    <p><strong>Lorem Ipsum</strong> &eacute; simplesmente uma simula&ccedil;&atilde;o de texto da ind&uacute;stria
      tipogr&aacute;fica e de impressos, e vem sendo utilizado desde o s&eacute;culo XVI, quando um impressor
      desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum
      sobreviveu n&atilde;o s&oacute; a cinco s&eacute;culos, como tamb&eacute;m ao salto para a
      editora&ccedil;&atilde;o eletr&ocirc;nica, permanecendo essencialmente inalterado. Se popularizou na d&eacute;cada
      de 60, quando a Letraset lan&ccedil;ou decalques contendo passagens de Lorem Ipsum, e mais recentemente quando
      passou a ser integrado a softwares de editora&ccedil;&atilde;o eletr&ocirc;nica como Aldus PageMaker.</p>
    <h2>Porque n&oacute;s o usamos?</h2>
    <p>&Eacute; um fato conhecido de todos que um leitor se distrair&aacute; com o conte&uacute;do de texto
      leg&iacute;vel de uma p&aacute;gina quando estiver examinando sua diagrama&ccedil;&atilde;o. A vantagem de usar
      Lorem Ipsum &eacute; que ele tem uma distribui&ccedil;&atilde;o normal de letras, ao contr&aacute;rio de
      "Conte&uacute;do aqui, conte&uacute;do aqui", fazendo com que ele tenha uma apar&ecirc;ncia similar a de um texto
      leg&iacute;vel. Muitos softwares de publica&ccedil;&atilde;o e editores de p&aacute;ginas na internet agora usam
      Lorem Ipsum como texto-modelo padr&atilde;o, e uma r&aacute;pida busca por lorem ipsum mostra v&aacute;rios
      websites ainda em sua fase de constru&ccedil;&atilde;o. V&aacute;rias vers&otilde;es novas surgiram ao longo dos
      anos, eventualmente por acidente, e &agrave;s vezes de prop&oacute;sito (injetando humor, e coisas do
      g&ecirc;nero).</p>';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Registro Manutenção</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            position: relative;
        }

        .divs_registros {
            padding-top: 70px;
            padding-bottom: 20px;
            border-bottom: 2px solid black;
        }
    </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#section1">Coleta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section2">Processamento</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section3">Compartilhamento</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section4">Segurança</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section5">Descarte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section6">Outros</a>
            </li>
        </ul>
    </nav>

    <div id="section1" class="container-fluid divs_registros">
        <h1>Coleta</h1>            
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" class="custom-select">
                <option selected>Selecione Categoria</option>
                <option value="1">Processo</option>
                <option value="2">Sistemas</option>
                <option value="3">Outros</option>
            </select>
        </div>
        <div class="form-group">
            <label for="evento">Evento:</label>
            <select name="evento" class="custom-select">
                <option selected>Selecione Evento</option>
                <option value="1">Processo1</option>
                <option value="2">Processo2</option>
                <option value="3">Processo3</option>
                <option value="4">Outros</option>
            </select>
        </div>
        <div class="form-group">
            <label for="area_responsavel">Area Responsavel:</label>
            <select name="area_responsavel" class="custom-select">
                <option selected>Selecione Area Responsavel</option>
                <option value="1">Processo1</option>
                <option value="2">Processo2</option>
                <option value="3">Processo3</option>
                <option value="4">Outros</option>
            </select>
        </div> 
                                
        <div class="form-group custom-control custom-switch">        
            <label class="custom-control-label" for="consentimento">Existe Consentimento? </label>    
            <input type="checkbox" class="custom-control-input" id="consentimento">                  
        </div>            

        <button type="button" id="btn-coleta-save" class="btn btn-primary">Salvar Registro Parcial</button>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-coleta-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>
    <div id="section2" class="container-fluid divs_registros">
        <h1>Processamento</h1>
        <?= $exemplo ?>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-processamento-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>
    <div id="section3" class="container-fluid divs_registros">
        <h1>Compartilhamento</h1>
        <?= $exemplo ?>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-compartilhamento-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>
    <div id="section4" class="container-fluid divs_registros">
        <h1>Segurança</h1>
        <?= $exemplo ?>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-seguranca-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>
    <div id="section5" class="container-fluid divs_registros">
        <h1>Descarte</h1>
        <?= $exemplo ?>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-descarte-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>
    <div id="section6" class="container-fluid divs_registros">
        <h1>Outros</h1>
        <?= $exemplo ?>
        <div class="d-flex flex-row-reverse">
            <div class="p-2">
                <button type="button" id="btn-outros-ajuda" class="btn btn-info">Ajuda ?</button>
            </div>
        </div>
    </div>

</body>

</html>