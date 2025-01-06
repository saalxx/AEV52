<?php
session_start();
?>
<html>
<head>
    <title>ex1aEval</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <h1>Examen 1a Evaluación</h1>
    
<html>
    <div class="contenedor">
        <ul>
            <li>El tablero está formado por 36 casillas dispuestas en 6 filas y 6 columnas.</li>
            <li>Cada casilla tiene un número y un color de manera que no se repite.</li>
            <li>Hay 6 colores y 6 números, o sea las 36 combinaciones diferentes.</li>
            <li>El objetivo del juego es lograr ir de una casilla (inicio) a otra (fin).</li>
            <li>Para ir del inicio al fin podemos hacer tantos movimientos como queramos.</li>
            <li>Los movimientos permitidos solo se pueden hacer en la misma columna o misma fila.</li>
            <li>Para poder hacer un movimiento tienen que coincidir o el mismo color o el mismo número de las casillas inicio y fin.</li>
        </ul>
    </div>
</html>
    <form method="POST" action="" >
        <label for="inicioC">Introduce una columna inicio:</label>
        <input type="number" name="inicioC" id="inicioC">
        <br>
        <label for="inicioF">Introduce una fila inicio:</label>
        <input type="number" name="inicioF" id="inicioF">
        <br>
        <label for="col">Introduce una columna fin:</label>
        <input type="number" name="col" id="col">
        <br>
        <label for="fila">Introduce una fila fin:</label>
        <input type="number" name="fila" id="fila">
        <br>
        <input type="Submit" value="Enviar" class="button"> 
        
    </form>
</body>
</html>

<?php
$colors = [];
$numbers = [1, 2, 3, 4, 5, 6];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inicioF = isset($_POST['inicioF']) ? intval($_POST['inicioF']) : null;
    $inicioC = isset($_POST['inicioC']) ? intval($_POST['inicioC']) : null;
    $fila = isset($_POST['fila']) ? intval($_POST['fila']) : null;
    $col = isset($_POST['col']) ? intval($_POST['col']) : null;

    if ($inicioC !== null && $inicioF !== null && $col !== null && $fila !== null) {
        if ($inicioC >= 1 && $inicioC <= 6 && $inicioF >= 1 && $inicioF <= 6 &&
            $col >= 1 && $col <= 6 && $fila >= 1 && $fila <= 6) {
            if (isset($_SESSION['combi'])) {
                Validar($_SESSION['combi'], $inicioC, $inicioF, $col, $fila);
            }
        } else {
            echo "Por favor, ingresa valores válidos entre 1 y 6 para filas y columnas.";
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}

if (!isset($_SESSION['combi'])) {
    $combi = [];
    $_SESSION['combi'] = $combi;



    function generarCombinaciones(array &$combi, $numbers, $colors) {
        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 6; $j++) {
                $aux = [];
                $aux[0] = $numbers[$i];
                $aux[1] = $colors[$j];
                $combi[$i][$j] = $aux;
            }
        }
    }

    function createArrayColors(&$colors, $cont) {
        $words = ["red", "yellow", "blue", "green", "pink", "orange"];
        if (count($colors) >= 36) return;
        $push = $words[rand(0, 5)];
        $countValues = array_count_values($colors);
        if (isset($countValues[$push]) && $countValues[$push] == 6) {
            createArrayColors($colors, $cont);
        } else {
            $colors[] = $push;
            $cont++;
            createArrayColors($colors, $cont);
        }
    }

    function createArrayNumbers(&$numbers, $cont) {
        if (count($numbers) >= 36) return;
        $push = rand(1, 6);
        $countValues = array_count_values($numbers);
        if (isset($countValues[$push]) && $countValues[$push] == 6) {
            createArrayNumbers($numbers, $cont);
        } else {
            $numbers[] = $push;
            $cont++;
            createArrayNumbers($numbers, $cont);
        }
    }

    createArrayColors($colors, 0);
    createArrayNumbers($numbers, 0);

    function mix(&$combi, $colors, $numbers) {
        $combi = [];
        $vista = [];

        foreach ($colors as $color) {
            foreach ($numbers as $number) {
                $key = $color . '-' . $number;
                if (!isset($vista[$key])) {
                    $combi[] = [$color, $number];
                    $vista[$key] = true;
                }
            }
        }
    }

    mix($combi, $colors, $numbers);
    shuffle($combi);


    $_SESSION['combi'] = $combi;
}


$combi = $_SESSION['combi'];

function printTable($combi) {
    echo "<div style='display: flex; flex-wrap: wrap; justify-content: flex-start;'>"; 
    $cont = 0;

    for ($i = 0; $i < count($combi); $i++) {
        if ($cont == 6) {
            echo "</div><div style='display: flex; flex-wrap: wrap; justify-content: flex-start;'>";
            $cont = 0;
        }

        $color = $combi[$i][0];
        $number = $combi[$i][1];

        echo "<span style='display: inline-block; width: 50px; height: 50px; background-color: $color; text-align: center; line-height: 50px; margin: 1px;'>";
        echo $number;
        echo "</span>";

        $cont++;
    }

    echo "</div>";
}

printTable($combi);

function Validar ($combi, $inicioC, $inicioF, $col, $fila){
    if ($col == $inicioC || $fila == $inicioF){
        $indexA =  ($inicioF - 1) * 6 + ($inicioC - 1);
        $indexF = ($fila - 1) * 6 + ($col - 1);
        if ($combi [$indexA][0] == $combi [$indexF][0] || $combi [$indexA][1] == $combi [$indexF][1]){
            echo "JUGADA VALIDA";
        }
        else {
            echo "JUGADA NO VALIDA";
        }
    }
    else {
        echo "COLUMNA/FILA DIFERENTE ";
    }
}
?> 
