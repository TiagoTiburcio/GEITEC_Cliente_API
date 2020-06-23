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

    public function saidaJS()
    {
        $js =  new GeraJS();
        foreach ($this->inputs as $value) {
            if ($value['tipo_input'] == 'select') {
                $this->javaScript = $this->javaScript . $js->inputSelect($value['recurso'], $value['nome'], $value['codigo_recurso'], $value['nome_recurso']);
            }
        }
        $this->javaScript = $this->javaScript . PHP_EOL . ' $(function() { carregaLista(); });';
        $retorno =
            $js->btnAdd($this->nomeRecurso)
            . $js->btnSave($this->nomeRecurso, $this->inputs)
            . $js->btnDel($this->nomeRecurso)
            . $js->carregaLista($this->nomeRecurso, $this->inputs)
            . $js->montaEdit($this->nomeRecurso, $this->inputs)
            . $js->carregaEdit($this->nomeRecurso, $this->inputs)
            . $js->addAjax($this->nomeRecurso, $this->inputs)
            . $this->javaScript;
            
        return $retorno;
    }
}
