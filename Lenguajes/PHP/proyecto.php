<?php

function f($x,$funcion){
  /*Evalua la funcion(textual) ingresada, retorna un valor numérico
  Parametros: x=valor a evaluar, funcion= texto del tipo x**2+3*/
  return eval('return '.str_replace('x','("$x")',$funcion).';');
}

function derivada($x, $func){
    /*Evalua la funcion de la derivada de diferencias centrales, retorna un valor numérico
	Parámetros: x=valor a evaluar, func=texto del tipo x**2+3*/
	$epsilon = 10**-3; //el rango de tolerancia relativa
    $delta = 0.1;      //variación inicial que se ira reduciendo
	
    for($l=1;;$l++){
		// este ciclo avanza indefinidamente hasta converger
        $derivada_actual = (f($x+$delta, $func) - f($x-$delta, $func))/(2*$delta);
        if($l > 1){ // si existe algo con que comparar, lo hace.
            $convergencia = abs($derivada_actual - $derivada_anterior)/abs($derivada_actual); //mido el ERROR
            if($convergencia < $epsilon) //si el error es aceptable regreso la funcion que encontre
                return $derivada_actual;
        }
        $delta /= 2; //reduzco la variación a la mitad
        $derivada_anterior = $derivada_actual; //para medir la convergencia siguiente
    }
}

function obtenerRaices($lim_inf,$lim_sup,$Pn,$grado){
    /*Esta función devuelve un arreglo con tantas raices como sea el $grado*/
	global $tabla;
	$raices = [];
    $raices_encontradas = 0;
	//genera todos los intervalos en los que se buscará las raices
	$numero_intervalos = $grado*10;
	$delta = ($lim_sup-$lim_inf)/$numero_intervalos;
	
	for($i=0,$temp_a = $lim_inf;$temp_a < $lim_sup;$i++,$temp_a += $delta){
		//$intervalos es una matriz con un filas = $numero_intervalos y columnas = [inicio del intervalo, fin del intervalo]
		//$i controla el indice de la fila y temp_a es un valor auxiliar que definirá los limites inicial y final
		$intervalos[$i][0] = $temp_a;
		$intervalos[$i][1] = $temp_a + $delta;
	}
	
    foreach($intervalos as $intervalo){ //para cada intervalo...
        $inicio = $intervalo[0]; $fin = $intervalo[1];
        $signo = f($inicio,$Pn)*f($fin,$Pn);
		
        if($raices_encontradas > 0){ //si hay una raíz anterior con que comparar
			//compruebo cuando el $signo es 0, solo agrego una vez la raiz.
			//sin este condicional se agregaría al arreglo dos veces la misma raíz del MISMO PUNTO X (en un mismo punto no hay 2 raices)
            if($signo_anterior == 0 && $signo_anterior == $signo){
                continue;
            } 
        }
        
        if($signo == 0){ //si encontramos una raíz inmediata, agrega el procedimiento a la tabla y agrega la raíz
            if(f($inicio) == 0){
				$tabla .= "<div style='text-align:center;'><p style='color:blue;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Tabla para la raíz número $r</p>
				<table border = '1' style='background-color:#A9F5D0;margin: 0 auto;'>
				<tr style='background-color:#2EFE64;'><td ><strong>k</strong></td><td><strong>ak</strong></td><td><strong>bk</strong></td><td><strong>ck</strong></td></tr>";
				$tabla .= "<tr style='background-color:#2E9AFE;'><td><th colspan='4'>Cambio de signo entre [$inicio,$fin], se analiza el intervalo: </th></td></tr>";
				$tabla .= "<tr><td><th colspan='4'>Raiz inmediata(signo igual a cero): $inicio</th></td></tr>";
                $tabla .= "</table></div>";
				$raices[] += $inicio;
            }else{
				$tabla .= "<div style='text-align:center;'><p style='color:blue;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Tabla para la raíz número $r</p>
				<table border = '1' style='background-color:#A9F5D0;margin: 0 auto;'>
				<tr style='background-color:#2EFE64;'><td ><strong>k</strong></td><td><strong>ak</strong></td><td><strong>bk</strong></td><td><strong>ck</strong></td></tr>";
				$tabla .= "<tr style='background-color:#2E9AFE;'><td><th colspan='4'>Cambio de signo entre [$inicio,$fin], se analiza el intervalo: </th></td></tr>";
				$tabla .= "<tr><td><th colspan='4'>Raiz inmediata(signo igual a cero): $fin</th></td></tr>";
                $tabla .= "</table></div>";
				$raices[] += $fin;
			}
            $raices_encontradas++;
        }        
        else if($signo < 0){ //si no es raíz inmediata aplicamos bisección-secante
			$r = $raices_encontradas+1;
			
			$tabla .= "<div style='text-align:center;'><p style='color:blue;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Tabla para la raíz número $r</p>
			<table border = '1' style='background-color:#A9F5D0;margin: 0 auto;'>
			<tr style='background-color:#2EFE64;'><td ><strong>k</strong></td><td><strong>ak</strong></td><td><strong>bk</strong></td><td><strong>ck</strong></td></tr>";
			$tabla .= "<tr style='background-color:#2E9AFE;'><td><th colspan='4'>Cambio de signo entre [$inicio,$fin], se analiza el intervalo: </th></td></tr>";
			$raiz = biseccion_secante($inicio,$fin,1,$Pn);
			$tabla .= "</table></div>";
            $raices[] += $raiz;
            $raices_encontradas++;
        }
        $signo_anterior = $signo;
    }
	return $raices;
}

function biseccion_secante($a,$b,$k,$func){
    global $tabla;
    $epsilon = 10**-6;
    $c = $b - f($b,$func)*($b-$a)/(f($b,$func)-f($a,$func));
    $tabla .= "<tr><td>$k</td><td>$a</td><td>$b</td><td>$c</td></tr>";
	
    if(abs($b - $c) < $epsilon && (f($b,$func)<=$epsilon || f($a,$func) <=$epsilon))
        return $c;
    
    else if(f($b,$func)*f($c,$func) <= 0){
        $a = $c;
        return biseccion_secante($a,$b,$k+1,$func);
    }else{
        $b = $c;
        return biseccion_secante($a,$b,$k+1,$func);
    }
    
}

//$polinomios_cache = [];
function polinomioLegendre($grado){
	//global $polinomios_cache;
    /*Retorna como texto el polinomio de Legendre de grado n*/
    
    //Casos base de la función recursiva
    if($grado == 0) return 1;
    if($grado == 1) return "x";
    
    //Programacion dinámica, almacenamos en caché polinomios anteriormente encontrados
	//if(!array_key_exists("$grado", $polinomios_cache)){
		$Pn_menos_2 = polinomioLegendre($grado-2);
		$Pn_menos_1 = polinomioLegendre($grado-1);
		//$polinomios_cache["$grado"] = '(1/'.$grado.')*((2*'.$grado.'-1)'.'*x*'.$Pn_menos_1.'-('.$grado.'-1)*'.$Pn_menos_2.")"; //Polinomio de Legendre
	//}
		
	return '(1/'.$grado.')*((2*'.$grado.'-1)'.'*x*'.$Pn_menos_1.'-('.$grado.'-1)*'.$Pn_menos_2.")";
}

function xi($i,$a,$b,$raices){
    return (($b-$a)*$raices[$i])/2 + ($b+$a)/2;
}

function wi($grado,$i,$Pn,$Pn_mas_1,$raices){
	
    return -2/(($grado+1)*derivada($raices[$i],$Pn)*f($raices[$i],$Pn_mas_1));
}

$funcion = $_GET['funcion'];
$grado = 5;//$_GET['grado'];<p>Ingrese el grado de la funcion: <input type="text" name="grado" placeholder="2" /></p>
//limites para las raices
$lim_inf = -1;
$lim_sup = 1;
//integracion
$a = $_GET['a'];;
$b = $_GET['b'];;
/////////
$Pn = polinomioLegendre($grado);
$Pn_mas_1 = polinomioLegendre($grado+1);
	
		
$tabla_integral = "<div style='text-align:center;'><p style='color:blue;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Tabla para el proceso de la integral</p><table border = '1' style='background-color:#A9F5D0;margin: 0 auto;'><tr style='background-color:#2EFE64;'><td >Polinomio n de legendre</td><td>Polinomio n + 1 de legendre</td></tr><tr style='background-color:#2E9AFE;'><td >$Pn</td><td>$Pn_mas_1</td></tr></table></div>";
$tabla_integral .= "<table border = '1' style='background-color:#A9F5D0;margin: 0 auto;'><tr style='background-color:#2EFE64;'><td >i</td><td>wi</td><td >xi</td></tr>";

$tabla = "<p style='color:blue;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Tablas que muestran el proceso para encontrar las raices del polinomio de legendre $Pn</p>";
$raices = obtenerRaices($lim_inf,$lim_sup,$Pn,$grado);


$sumatoria_integral = 0;
for($i = 0;$i<$grado;$i++){
	$w = wi($grado,$i,$Pn,$Pn_mas_1,$raices); $x = xi($i,$a,$b,$raices);
	$tabla_integral .= "<tr style='background-color:#A9F5D0;'><td >$i</td><td>$w</td><td >$x</td></tr>";
    $sumatoria_integral += $w*f($x,$funcion);
}
$tabla_integral .= "</table></div>";

echo "<div style='text-align: center;color: black; border: 3px solid black;padding: 10px;font-style: italic;font-family: fantasy;text-shadow: -3px 3px 3px black;font-size:50px;'>Resultados</div>";
echo "<p style='color: green;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>El valor de la integral de $funcion entre [$a,$b] es igual a: ".round(($b-$a)/2 * $sumatoria_integral,3)."</p>";

echo $tabla_integral;
$const = ($b-$a)/2;
echo "<img src='/media/formula.png' style='margin:10px auto;display:block;'/>";
echo "<p style='text-align:center;'>Integral = $const * $sumatoria_integral = ".$const * $sumatoria_integral."</p>";
echo $tabla;

$tabla_raices = "<p style='color:red;text-shadow: rgba(0,0,255,0.5) -1px 2px 3px;text-align:center;'>Raices</p><table border = '1' style='background-color:#F4FA58;margin: 0 auto;'><tr>";
foreach($raices as $raiz){
$tabla_raices .= "<td><strong>$raiz</strong></td>";
}
$tabla_raices .= "</tr></table>";
echo $tabla_raices;
?>

<DOCTYPE! html>
<html>
<meta charset="utf-8">
  <head><title>Resultados PHP</title></head>
  <body style='background-color: #D0F5A9;'>
    <form action="main_proyecto.html" method="get">
		<p style="text-align:center;"><input type="submit" name="submit" value="Volver" /></p>
	</form>
  </body>
</html>