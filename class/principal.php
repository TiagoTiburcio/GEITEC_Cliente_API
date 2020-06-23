<?php
include_once 'GeraJS.php';
include_once 'GeraHml.php';

/**
 * Description of Principal
 * Classe contém fucionalidades genéricas do cliente API
 *
 * @author tiagoc
 */
class Principal
{
    private $nomeRecurso;
    private $inputs;
    private $javaScript;
    private $geraHtml;

    public function __construct($nomeRecurso, $inputs)
    {
        $this->nomeRecurso = $nomeRecurso;
        $this->inputs = $inputs;
    }

    public function concatJS($javaScript)
    {
        $this->javaScript = $this->javaScript . PHP_EOL . $javaScript;
    }
    public static function token($usuario = ""): string
    {
        // aqui deve ser implementada lógica buscar token no banco de dados do $usuario
        // casa
        // eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU
        // seduc
        // eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.CoshUvB4nV9VFw0HYyNddBqTORy1UpF21siLS6wWMM4
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU";

        return $token;
    }

    public function cabecalho()
    {
        return GeraHtml::cabecalho($this->nomeRecurso);
    }
    public function opcoes()
    {
        return GeraHtml::opcoes($this->nomeRecurso);
    }
    public function formEdit($codigo = true, $datas = true, $inputs)
    {
        return GeraHtml::formEdit($codigo, $datas, $inputs, $this->nomeRecurso);
    }

    public function criaLista($inputs)
    {
        return GeraHtml::criaLista($this->inputs, $this->nomeRecurso);
    }

    public function selectDivPricipaisJS()
    {
        $js = 'var divEditar = $("#' . $this->nomeRecurso . '-editar");'
            . 'var divListar = $("#' . $this->nomeRecurso . '-listar");';
        return $js;
    }

    public function btnAddJS()
    {
        $html = 'var btn_add = $("#adicionar");' . PHP_EOL
            . 'btn_add.on("click", function() {' . PHP_EOL
            . $this->selectDivPricipaisJS() . PHP_EOL
            . 'divEditar.toggle();' . PHP_EOL
            . 'divListar.toggle();' . PHP_EOL
            . 'if (divEditar.attr("style") == "display: none;") {' . PHP_EOL
            . 'carregaLista();' . PHP_EOL
            . '} else {' . PHP_EOL
            . 'montaEdit();' . PHP_EOL
            . '}' . PHP_EOL
            . '});' . PHP_EOL;
        return $html;
    }

    public function btnSaveJS()
    {
        $qtd = (count($this->inputs) - 1);
        $html = 'var btn_save = $("#salvar_' . $this->nomeRecurso . '");' . PHP_EOL
            . 'btn_save.on("click", function() {' . PHP_EOL
            . $this->selectDivPricipaisJS() . PHP_EOL
            . $this->importaInputsJS() . PHP_EOL
            . $this->validaInputsVaziosJS() . PHP_EOL
            . 'if (inp_codigo.val() == "") {' . PHP_EOL
            . 'salvar(' . $this->retornaValorInputsJS() . ');' . PHP_EOL
            . '} else {' . PHP_EOL
            . 'atualizar(inp_codigo.val(), ' . $this->retornaValorInputsJS() . ');' . PHP_EOL
            . '}' . PHP_EOL
            . 'divEditar.toggle();' . PHP_EOL
            . 'divListar.toggle();' . PHP_EOL
            . 'carregaLista();' . PHP_EOL
            . '}); ' . PHP_EOL;
        return $html;
    }

    private function retornaValorInputsJS()
    {
        $html = '';
        foreach ($this->inputs as $key => $value) {
            if ($value['tipo_input'] == 'text') {
                $html = $html . 'inp_' . $value['nome'] . '.val()';
            } elseif ($value['tipo_input'] == 'select') {
                $html = $html . 'inputSelecionado' . strtoupper($value['recurso']) . '()';
            }
            if (count($this->inputs) <> ($key + 1)) {
                $html = $html . ', ';
            }
        }
        return $html;
    }
    private function importaInputsJS()
    {
        $html = 'var inp_codigo = divEditar.find("#codigo");';
        foreach ($this->inputs as $value) {
            $html = $html . 'var inp_' . $value['nome'] . ' = divEditar.find("#' . $value['nome'] . '");';
        }
        return $html;
    }

    private function validaInputsVaziosJS()
    {
        $html = '';
        foreach ($this->inputs as $value) {
            if ($value['tipo_input'] == 'text') {
                $html = $html . 'if (inp_' . $value['nome'] . '.val() == "") {'
                    . 'alert("Campo ' . $value['nome'] . ' não pode ser vazio!!!");'
                    . 'return;'
                    . '}';
            }
        }
        return $html;
    }

    public function btnDelJS()
    {
        $html = 'var btn_del = $("#delete_' . $this->nomeRecurso . '");'
            . 'btn_del.on("click", function() {'
            . $this->selectDivPricipaisJS()
            . 'var inp_codigo = divEditar.find("#codigo");'
            . 'if (inp_codigo.val() != "") {'
            . 'deletar(inp_codigo.val());'
            . '}'
            . 'divEditar.toggle();'
            . 'divListar.toggle();'
            . 'carregaLista();'
            . '});';
        return $html;
    }

    public function addAjaxJS()
    {
        $html = 'function salvar(';
        foreach ($this->inputs as $key => $value) {
            $html = $html . $value['nome'];
            if (count($this->inputs) <> ($key + 1)) {
                $html = $html . ', ';
            }
        }
        $html = $html . ') {'
            . ' var settings = {
                "async": true,
                "method": "POST",
                "url": "/api/index.php/infraestrutura/' . $this->nomeRecurso . '/",
                "headers": {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": "Bearer " + token
                },
                "data": {';

        foreach ($this->inputs as $key => $value) {
            $html =  $html . '"' . $value['nome'] . '" : ' . $value['nome'];
            if (count($this->inputs) <> ($key + 1)) {
                $html = $html . ', ';
            }
        }

        $html =  $html . '} }' . PHP_EOL . ' var dados = $.ajax(settings, function(data) {
                return data;
            });
            return dados;
        }';
        $html =  $html . ' function atualizar(codigo, ';
        foreach ($this->inputs as $key => $value) {
            $html = $html . $value['nome'];
            if (count($this->inputs) <> ($key + 1)) {
                $html = $html . ', ';
            }
        }
        $html = $html . ') {'
            . 'var settings = {' . PHP_EOL
            . '"async": true,' . PHP_EOL
            . '"method": "POST",' . PHP_EOL
            . '"url": "/api/index.php/infraestrutura/' . $this->nomeRecurso . '/" + codigo + "/edit",' . PHP_EOL
            . '"headers": {' . PHP_EOL
            . '"Content-Type": "application/x-www-form-urlencoded",' . PHP_EOL
            . '"Authorization": "Bearer " + token ' . PHP_EOL
            . '},' . PHP_EOL
            . '"data": {';
        foreach ($this->inputs as $key => $value) {
            $html =  $html . '"' . $value['nome'] . '" : ' . $value['nome'];
            if (count($this->inputs) <> ($key + 1)) {
                $html = $html . ', ';
            }
        }
        $html =  $html . '} }' . PHP_EOL
            . 'var dados = $.ajax(settings, function(data) {' . PHP_EOL
            . 'return data;' . PHP_EOL
            . '});' . PHP_EOL
            . 'return dados;' . PHP_EOL
            . '}';

        $html =  $html . 'function deletar(codigo) {
            var settings = {
                "async": true,
                "method": "POST",
                "url": "/api/index.php/infraestrutura/' . $this->nomeRecurso . '/" + codigo + "/delete",
                "headers": {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": "Bearer " + token
                }
            }
            var dados = $.ajax(settings, function(data) {
                return data;
            });
            return dados;
        }' . PHP_EOL
            . 'function getTodos(categoria) {
            var settings = {
                "async": true,
                "url": "/api/index.php/infraestrutura/" + categoria,
                "headers": {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": "Bearer " + token
                }
            }
            
        
            var dados = $.get(settings, function (data) {
                return data;
            });
            return dados;
        }';
        return $html;
    }

    public function montaEditJS()
    {
        $html = ' function montaEdit() {
            btn_add.text("Listar ' . strtoupper($this->nomeRecurso) . '");'
            . $this->selectDivPricipaisJS()
            . 'divEditar.find("#codigo").val("");';
        foreach ($this->inputs as $value) {
            if ($value['tipo_input'] == 'text') {
                $html = $html . 'divEditar.find("#' . $value['nome'] . '").val("");' . PHP_EOL;
            } elseif ($value['tipo_input'] == 'select') {
                $html = $html . 'carrega' . strtoupper($value['recurso']) . '();' . PHP_EOL;
            }
        }
        $html = $html . 'divEditar.find("#dataCriacao").val("");
            divEditar.find("#dataAtu").val("");
        }';
        return $html;
    }

    public function carregaEditJS()
    {
        $html = 'function carregaEdit(codigo) {
            btn_add.text("Listar ' . strtoupper($this->nomeRecurso) . '");'
            . $this->selectDivPricipaisJS()
            . 'divEditar.toggle();' . PHP_EOL
            . 'divListar.toggle();' . PHP_EOL
            . 'var dc = getTodos("' . $this->nomeRecurso . '/" + codigo);' . PHP_EOL
            . 'var inp_codigo = divEditar.find("#codigo");' . PHP_EOL;

        foreach ($this->inputs as $value) {
            $html = $html . 'var inp_' . $value['nome'] . ' = divEditar.find("#' . $value['nome'] . '");' . PHP_EOL;
        }

        $html = $html . 'var inp_dataCre = divEditar.find("#dataCriacao");' . PHP_EOL
            . 'var inp_dataAtu = divEditar.find("#dataAtu"); ' . PHP_EOL
            . 'dc.done(function(data) {' . PHP_EOL
            . 'inp_codigo.val(data.codigo);' . PHP_EOL;

        foreach ($this->inputs as $value) {
            if ($value['tipo_input'] == 'text') {
                $html = $html . 'inp_' . $value['nome'] . '.val(data.' . $value['nome'] . ');' . PHP_EOL;
            } elseif ($value['tipo_input'] == 'select') {
                $html = $html . 'carrega' . strtoupper($value['recurso']) . '(data.' . $value['nome'] . ');' . PHP_EOL;
            }
        }
        $html = $html . 'inp_dataCre.val(data.created_at)' . PHP_EOL
            . 'inp_dataAtu.val(data.updated_at);' . PHP_EOL
            . '});     }' . PHP_EOL;
        return $html;
    }
    public function carregaListaJS()
    {
        $html = ' function carregaLista() { ' . PHP_EOL
            . ' btn_add.text("Add ' . strtoupper($this->nomeRecurso) . '");' . PHP_EOL
            . ' var dcs = getTodos("' . $this->nomeRecurso . '");' . PHP_EOL
            . ' var tabela = $("#lista").find("tbody");' . PHP_EOL
            . ' tabela.empty();' . PHP_EOL
            . ' dcs.done(function(data) {' . PHP_EOL
            . ' for (let index = 0; index < data.length; index++) {' . PHP_EOL
            . ' const element = data[index];' . PHP_EOL
            . ' var linha = $("<tr>");';

        foreach ($this->inputs as $value) {
            if ($value['table']) {
                $html =  $html . 'var ' . $value['nome'] . ' = $("<td>").text(element.' . $value['nome'] . ');' . PHP_EOL;
            }
        }

        $html = $html . 'var link = $("<button>").text("Editar").attr("class", "btn btn-warning edit-dc").attr("id", element.codigo).attr("onClick", "carregaEdit(" + element.codigo + ");");' . PHP_EOL
            . 'var edit = $("<td>").append(link);' . PHP_EOL
            . 'linha';
        foreach ($this->inputs as $key => $value) {
            if ($value['table']) {
                $html =  $html . '.append(' . $value['nome'] . ')';
            }
        }
        $html = $html . '.append(edit);'
            . 'tabela.append(linha); }'
            . '} ); }';

        return $html;
    }

    public function saidaJS()
    {
        $js =  new GeraJS();
        foreach ($this->inputs as $value) {
            if ($value['tipo_input'] == 'select') {
                $this->javaScript = $this->javaScript . $js->inputSelect($value['recurso'], $value['nome'], $value['codigo_recurso'], $value['nome_recurso']);
            }
        }
        $retorno =
            $this->btnAddJS()
            . $this->btnSaveJS()
            . $this->btnDelJS()
            . $this->carregaListaJS()
            . $this->montaEditJS()
            . $this->carregaEditJS()
            . $this->addAjaxJS()
            . $this->javaScript;
            
        return $retorno;
    }
}
