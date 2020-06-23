<?php

/**
 * Description of Principal
 * Classe contém fucionalidades genéricas do cliente API
 *
 * @author tiagoc
 */
class GeraJS
{
    private function selectDivPricipais($nomeRecurso)
    {
        $js = 'var divEditar = $("#' . $nomeRecurso . '-editar");'
            . 'var divListar = $("#' . $nomeRecurso . '-listar");';
        return $js;
    }

    public function btnAdd($nomeRecurso)
    {
        $js = 'var btn_add = $("#adicionar");' . PHP_EOL
            . 'btn_add.on("click", function() {' . PHP_EOL
            . $this->selectDivPricipais($nomeRecurso) . PHP_EOL
            . 'divEditar.toggle();' . PHP_EOL
            . 'divListar.toggle();' . PHP_EOL
            . 'if (divEditar.attr("style") == "display: none;") {' . PHP_EOL
            . 'carregaLista();' . PHP_EOL
            . '} else {' . PHP_EOL
            . 'montaEdit();' . PHP_EOL
            . '}' . PHP_EOL
            . '});' . PHP_EOL;
        return $js;
    }

    public function btnSave($nomeRecurso, $inputs)
    {
        $qtd = (count($inputs) - 1);
        $js = 'var btn_save = $("#salvar_' . $nomeRecurso . '");' . PHP_EOL
            . 'btn_save.on("click", function() {' . PHP_EOL
            . $this->selectDivPricipais($nomeRecurso) . PHP_EOL
            . $this->importaInputs($inputs) . PHP_EOL
            . $this->validaInputsVazios($inputs) . PHP_EOL
            . 'if (inp_codigo.val() == "") {' . PHP_EOL
            . 'salvar(' . $this->retornaValorInputs($inputs) . ');' . PHP_EOL
            . '} else {' . PHP_EOL
            . 'atualizar(inp_codigo.val(), ' . $this->retornaValorInputs($inputs) . ');' . PHP_EOL
            . '}' . PHP_EOL
            . 'divEditar.toggle();' . PHP_EOL
            . 'divListar.toggle();' . PHP_EOL
            . 'carregaLista();' . PHP_EOL
            . '}); ' . PHP_EOL;
        return $js;
    }

    private function retornaValorInputs($inputs)
    {
        $js = '';
        foreach ($inputs as $key => $value) {
            if ($value['tipo_input'] == 'text') {
                $js = $js . 'inp_' . $value['nome'] . '.val()';
            } elseif ($value['tipo_input'] == 'select') {
                $js = $js . 'inputSelecionado' . strtoupper($value['recurso']) . '()';
            }
            if (count($inputs) <> ($key + 1)) {
                $js = $js . ', ';
            }
        }
        return $js;
    }
    private function importaInputs($inputs)
    {
        $js = 'var inp_codigo = divEditar.find("#codigo");';
        foreach ($inputs as $value) {
            $js = $js . 'var inp_' . $value['nome'] . ' = divEditar.find("#' . $value['nome'] . '");';
        }
        return $js;
    }

    private function validaInputsVazios($inputs)
    {
        $js = '';
        foreach ($inputs as $value) {
            if ($value['tipo_input'] == 'text') {
                $js = $js . 'if (inp_' . $value['nome'] . '.val() == "") {'
                    . 'alert("Campo ' . $value['nome'] . ' não pode ser vazio!!!");'
                    . 'return;'
                    . '}';
            }
        }
        return $js;
    }

    public function btnDel($nomeRecurso)
    {
        $html = 'var btn_del = $("#delete_' . $nomeRecurso . '");'
            . 'btn_del.on("click", function() {'
            . $this->selectDivPricipais($nomeRecurso)
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
    private function addAjaxSalvar()
    {
        # code...
    }
    public function addAjax()
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

    public function montaEdit()
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

    public function carregaEdit()
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
    public function carregaLista()
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


    public function inputSelect($recurso, $nome, $codigo_recurso, $nome_recurso)
    {
        $retorno = $this->inputSelectCarrega($recurso, $nome, $codigo_recurso, $nome_recurso);
        $retorno = $retorno . PHP_EOL . $this->inputSelectSelecionado($recurso, $nome);
        return $retorno;
    }

    private function inputSelectCarrega($recurso, $nome, $codigo_recurso, $nome_recurso)
    {
        $saida =  'function carrega' . strtoupper($recurso) . '(codigo' . strtoupper($recurso) . ') {' . PHP_EOL
            . 'var recurso = getTodos("' . $recurso . '");' . PHP_EOL
            . 'var select = $("#' . $nome . '");' . PHP_EOL
            . '$("#' . $nome . ' option").remove();' . PHP_EOL
            . 'recurso.done(function(data) {' . PHP_EOL
            . 'for (let index = 0; index < data.length; index++) {' . PHP_EOL
            . 'const element = data[index];' . PHP_EOL
            . 'var linha = $("<option>");' . PHP_EOL
            . 'linha.val(element.' . $codigo_recurso . ');' . PHP_EOL
            . 'linha.text(element.' . $nome_recurso . ');' . PHP_EOL
            . 'if (codigoDS == element.' . $codigo_recurso . ') {' . PHP_EOL
            . 'linha.attr("selected", "true");' . PHP_EOL
            . '}' . PHP_EOL . 'select.append(linha);' . PHP_EOL
            . '}' . PHP_EOL . '}); } ';
        return $saida;
    }
    private function inputSelectSelecionado($recurso, $nome)
    {
        $saida =  'function inputSelecionado' . strtoupper($recurso) . '() {' . PHP_EOL
            . 'var optionSelected = $("#' . $nome . ' option:selected");' . PHP_EOL
            . 'return optionSelected.val();' . PHP_EOL
            . '};';
        return $saida;
    }
}
