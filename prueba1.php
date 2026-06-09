<?php
class Auto
{
    public $marca;
    public $modelo;
    public $color;

    public function __construct($marca,$modelo,$color)
    {
        $this->marca=$marca;
        $this->modelo=$modelo;
        $this->color=$color;
    }

    public function mostrar()
    {
        echo("<h2>".$this->marca." ".$this->modelo." ".$this->color."</h2>");
    }
}
$obj=new Auto("toyota", "corolla", "negro");
$obj2=new Auto("honda", "civic", "blanco");
$obj->mostrar();
echo "<br>";
$obj2->mostrar();
?>

//get para enviar valores de una pagina a otra en forma no segura
//post para enviar valores de una pagina a otra en forma segura