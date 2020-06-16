<?php

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

    public function __construct($nomeRecurso, $inputs)
    {
        $this->nomeRecurso = $nomeRecurso;
        $this->inputs = $inputs;
    }


    public static function token($usuario = ""): string
    {
        // aqui deve ser implementada lógica buscar token no banco de dados do $usuario
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.L-j3Esvv6MfPo3ToCYonYY2nsc7SAuM0owlkEh62XHU";

        return $token;
    }

    public function cabecalho()
    {
        $html = '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">'
            . '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">'
            . '<title>' . strtoupper($this->nomeRecurso) . ' Adm</title>'
            . '</head>';
        return $html;
    }
    public function opcoes()
    {
        $html = '<div class="col-xs-12">'
            . '<a class="btn btn-dark" href="index.php"> Inicio</a>'
            . '<button id="adicionar" class="btn btn-primary">Add ' . strtoupper($this->nomeRecurso) . '</button>'
            . '</div>';
        return $html;
    }
    public function formEdit($codigo = true, $datas = true, $inputs)
    {

        $divPrincipal = '<div id="' . $this->nomeRecurso . '-editar" style="display: none;">';
        $inputs = $this->inputs($codigo, $datas, $inputs);
        $botoes = '<button id="salvar_' . $this->nomeRecurso . '" class="btn btn-success">Salvar</button>'
            . '<button id="delete_' . $this->nomeRecurso . '" class="btn btn-danger">Deletar</button>';
        $divPrincipal = $divPrincipal . $inputs . $botoes;
        $divPrincipal = $divPrincipal . '</div>';
        return $divPrincipal;
    }

    private function inputs($codigo, $datas, $inputs)
    {
        $inp_codigo = "";
        $inp_geral = "";
        $inp_datas = "";
        //Input Codigo
        if ($codigo) {
            $inp_codigo = '<div class="form-group">'
                . '<label for="codigo">Codigo:</label>'
                . '<input type="text" class="form-control" readonly id="codigo">'
                . '</div>';
        }
        // Inputs Gerais
        $inp_geral = "";

        foreach ($inputs as $key => $value) {
            $inp_geral = $inp_geral . $this->criaInput($value);
        }
        // Inputs Datas
        if ($datas) {
            $inp_datas = '<div class="form-group">'
                . '<label for="dataCriacao">Data Criação:</label>'
                . '<input type="text" class="form-control" readonly id="dataCriacao">'
                . '</div>'
                . '<div class="form-group">'
                . '<label for="dataAtu">Data Atualização:</label>'
                . '<input type="text" class="form-control" readonly id="dataAtu">'
                . '</div>';
        }
        return $inp_codigo . $inp_geral . $inp_datas;
    }
    private function criaInput($dados)
    {
        if ($dados['tipo_input'] == 'text') {
            $input = '<div class="form-group">'
                . '<label for="' . $dados['nome'] . '">' . $dados['descricao'] . ':</label>'
                . '<input type="text" class="form-control" id="' . $dados['nome'] . '">'
                . '</div>';
        } else {
            $input = "Tipo Input Não cadastrado: " . $dados['tipo_input'];
        }
        return $input;
    }

    public function criaLista($inputs)
    {
        $html = '<div id="' . $this->nomeRecurso . '-listar" class="col-xs-12">'
            . '<table id="lista" class="table table-striped table-condensed">'
            . '<thead>'
            . '<tr>';
        foreach ($inputs as $value) {
            if ($value['table']) {
                $html = $html . '<th>' . $value['descricao'] . '</th>';
            }
        }
        $html = $html
            . '<th>Manut</th>'
            . '</tr>'
            . '</thead>'
            . '<tbody></tbody>'
            . '</table>'
            . '</div>';

        return $html;
    }

    public function selectDivPricipaisJS()
    {
        $js = 'var divEditar = $("#' . $this->nomeRecurso . '-editar");'
            . 'var divListar = $("#' . $this->nomeRecurso . '-listar");';
        return $js;
    }

    public function btnAddJS()
    {
        $html = 'var btn_add = $("#adicionar");'
            . 'btn_add.on("click", function() {'
            . $this->selectDivPricipaisJS()
            . 'divEditar.toggle();'
            . 'divListar.toggle();'
            . 'if (divEditar.attr("style") == "display: none;") {'
            . 'carregaLista();'
            . '} else {'
            . 'montaEdit();'
            . '}'
            . '});';
        return $html;
    }

    public function btnSaveJS()
    {
        $qtd = (count($this->inputs) - 1);
        $html = 'var btn_save = $("#salvar_' . $this->nomeRecurso . '");'
            . 'btn_save.on("click", function() {'
            . $this->selectDivPricipaisJS() . "\n"
            . $this->importaInputsJS() . "\n"
            . $this->validaInputsVaziosJS() . "\n"
            . 'if (inp_codigo.val() == "") {'
            . 'salvar(' . $this->retornaValorInputsJS() . ');'
            . '} else {'
            . 'atualizar(inp_codigo.val(), ' . $this->retornaValorInputsJS() . ');'
            . '}'
            . 'divEditar.toggle();'
            . 'divListar.toggle();'
            . 'carregaLista();'
            . '}); ' . "\n";
        return $html;
    }

    private function retornaValorInputsJS()
    {
        $html = '';
        foreach ($this->inputs as $key => $value) {
            $html = $html . 'inp_' . $value['nome'] . '.val()';
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
            $html = $html . 'if (inp_' . $value['nome'] . '.val() == "") {'
                . 'alert("Campo ' . $value['nome'] . ' não pode ser vazio!!!");'
                . 'return;'
                . '}';
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
        $html = 'function salvar(nome, descricao) {'
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

        $html =  $html . '} }'. "\n".' var dados = $.ajax(settings, function(data) {
                return data;
            });
            return dados;
        }';
        $html =  $html . 'function atualizar(codigo, nome, descricao) {
            var settings = {
                "async": true,
                "method": "POST",
                "url": "/api/index.php/infraestrutura/' . $this->nomeRecurso . '/" + codigo + "/edit",
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
        $html =  $html . '} }
            var dados = $.ajax(settings, function(data) {
                return data;
            });
            return dados;
        }';

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
        }';

        return $html;
    }
}
