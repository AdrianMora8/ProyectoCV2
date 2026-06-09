<?php
class controllersEnlaces
{
    public function platilla()
    {
        include "views/template.php";
    }

    public function controladorEnlaces()
    {
        $opcion = isset($_GET["option"]) ? $_GET["option"] : "Inicio";
        $pagina = modeloEnlaces::EnlacesPaginas($opcion);
        include $pagina;
    }
}
?>
