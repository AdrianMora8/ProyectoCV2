<?php
$tetso="hola wilson chuplao";
echo $tetso;
echo "<br>";
print $tetso;
echo "<br>";
$number=8;
echo $number;
echo "<h1>$tetso</h1>";
var_dump($tetso);
echo "<br>";
$verdadero=true;
var_dump($verdadero);
echo "<br>";
$verctor=array("rojo","verde","azul");
var_dump($verctor);
echo "<h2>$verctor[0]</h2>";
echo "<h2>$verctor[1]</h2>";
echo "<h2>$verctor[2]</h2>";
echo "<br>";
$cv=array("F1"=>"mango","F2"=>"pera","F3"=>"naranja");
var_dump($cv);
echo "<h2>$cv[F1]</h2>";

for ($i=0 ; $i < count($verctor); $i++) { 
    echo "<h2>$verctor[$i]</h2>";
}

echo "<br>";

$object=(object)['F1'=>"mango",'F2'=>"pera",'F3'=>"naranja"]    ;
var_dump($object);
echo "<h2>$object->F1</h2>";

if ($number>5) {
    echo "el numero $number es mayor a 5";
    echo "<br>";
} else {
    
    echo "el numero $number es menor a 5";
    echo "<br>";
}

function hola()
{
    echo "hola mundo";
}
hola();
echo "<br>";
function googbay($msg)
{
    echo $msg;
}
googbay("adios mundo");
echo "<br>";
function googbay2($msg)
{
    return $msg;
}
echo googbay2("adios mundo");
echo "<br>";

$auto1=(object)['marca'=>"toyota",'modelo'=>"corolla",'color'=>"negro"];
$auto2=(object)['marca'=>"honda",'modelo'=>"civic",'color'=>"blanco"];

function mostart($auto)
{
    echo "<h2>marca: $auto->marca</h2>";
    echo "<h2>modelo: $auto->modelo</h2>";
    echo "<h2>color: $auto->color</h2>";
}
mostart($auto1);
echo "<br>";
mostart($auto2);

?>
