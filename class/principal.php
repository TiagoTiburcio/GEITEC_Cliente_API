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

    public function __construct($nomeRecurso)
    {
        $this->nomeRecurso = $nomeRecurso;
    }
    
    
    public static function token($usuario = ""): string
    {
        // aqui deve ser implementada lógica buscar token no banco de dados do $usuario
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.CoshUvB4nV9VFw0HYyNddBqTORy1UpF21siLS6wWMM4";

        return $token;
    }

    public static function cabecalho($nomeRecurso)    {   
        $html = '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">'
            . '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">'
            . '<title>' . strtoupper($nomeRecurso) . ' Adm</title>'
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
    public function formEdit($nomeRecurso, $codigo = true, $datas = true, $inputs)    {
        
        $divPrincipal = '<div id="' . $nomeRecurso . '-editar" style="display: none;">';
        $inputs = $this->inputs($codigo, $datas, $inputs);
        $botoes = '<button id="salvar_<?= $nomeRecurso; ?>" class="btn btn-success">Salvar</button>'
            . '<button id="delete_<?= $nomeRecurso; ?>" class="btn btn-danger">Deletar</button>';
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

    public static function criaLista($nomeRecurso, $inputs)
    {
        $html = '<div id="' . $nomeRecurso . '-listar" class="col-xs-12">'
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
        $js = 'var divEditar = $("#'. $nomeRecurso . '-editar");'
            . 'var divListar = $("#'.$nomeRecurso . '-listar");';
        return $js;
    }
}
