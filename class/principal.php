<?php

/**
 * Description of Principal
 * Classe contém fucionalidades genéricas do cliente API
 *
 * @author tiagoc
 */
class Principal
{

    public static function token($usuario = ""): string
    {
        // aqui deve ser implementada lógica buscar token no banco de dados do $usuario
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoidGlhZ29jIn0.CoshUvB4nV9VFw0HYyNddBqTORy1UpF21siLS6wWMM4";

        return $token;
    }

    public static function cabecalho($nomeRecurso)
    {
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
    public static function opcoes($nomeRecurso)
    {
        $html = '<div class="col-xs-12">'
            . '<a class="btn btn-dark" href="index.php"> Inicio</a>'
            . '<button id="adicionar" class="btn btn-primary">Add ' . strtoupper($nomeRecurso) . '</button>'
            . '</div>';
        return $html;
    }
}
