<?php

function f($x){
     
  /*Evalua la funcion ingresada como texto*/   
  global $funcion;
   
  $copia = "";
  for($i=0;$i<strlen($funcion);$i++){ 
       
      if($funcion[$i] != 'x'){
           $copia .= $funcion[$i]; 
      }
      else{
           $copia .= "($x)"; //intercambio la x por $x para poder usar eval(), y obtener el resultado
      }
  }
   
  return eval('return '.$copia.';');
}


function busquedaCambioSigno($a,$b,$intervalos){
    global $grado,$tabla,$raices;
    $cont = 0;
    
    $raices_encontradas = 0;
    foreach($intervalos as $intervalo){
        $inicio = $intervalo[0];
        $fin = $intervalo[1];
        $signo = f($inicio)*f($fin);
        //echo "$inicio y $fin";
        
        if($cont > 0){
            if($signo_anterior == 0 && $signo_anterior == $signo){
                $signo_anterior = $signo;
                $cont++;
                continue;
            } 
        }
        
        if($signo == 0){
            if(f($inicio) == 0){
				$tabla .= 
				"
				<tr>
				  <td><th colspan='4'>Raiz inmediata(signo igual a cero): $inicio</th></td>
				</tr>
				";
                $raices .= 
				"
				<tr>
				  <td>$inicio</td>
				</tr>
				";
            }else{
				$tabla .= 
				"
				<tr>
				  <td><th colspan='4'>Raiz inmediata(signo igual a cero): $fin</th></td>
				</tr>
				";
                $raices .= 
				"
				<tr>
				  <td>$fin</td>
				</tr>
			";}
            $raices_encontradas++;
        }
        
        else if($signo < 0){
            
            $tabla .= 
			"
			<tr>
			  <td><th colspan='4'>Nota: Cambio de signo entre [$inicio,$fin]</th></td>
			</tr>
			";
			$raiz = biseccion($inicio,$fin,1);
			$raices .= 
			"
			<tr>
			  <td>$raiz</td>
			</tr>
			";
			
            //echo "Raiz: ".biseccion($inicio,$fin,1)."\n";
            $raices_encontradas++;
            
        }
        
        $signo_anterior = $signo;
        $cont++;
    }
    
}

function biseccion($a,$b,$k){
    global $tabla;
    $epsilon = 10**-6;
    $c = $b - f($b)*($b-$a)/(f($b)-f($a));
    $tabla .= 
	"
	<tr>
	  <td>$k</td>
	  <td>$a</td>
	  <td>$b</td>
	  <td>$c</td>
	</tr>
	";
	
    if($b - $c < $epsilon && (f($b)<=$epsilon || f($a) <=$epsilon)){
        return $c;
    }
    else if(f($b)*f($c) <= 0){
        
        $a = $c;
        return biseccion($a,$b,$k+1);
    }else{
        $b = $c;
        return biseccion($a,$b,$k+1);
    }
    
}

$funcion = $_GET['funcion']; //"x**2+x-2";
$grado = $_GET['grado'];
$a = $_GET['a'];
$b = $_GET['b'];
$n = $grado*20;
$delta = ($b-$a)/$n;

$temp_a = $a;

$i = 0;
while($temp_a < $b){
    $intervalos[$i][0] = $temp_a;
    $intervalos[$i][1] = $temp_a + $delta;
    //echo $intervalos[$i][0]." - ".$intervalos[$i][1]."\n";
    $temp_a += $delta;
    
    $i++;
}

$tabla = 
"<table border = '1'>
<tr>
  <td><strong>k</strong></td>
  <td><strong>ak</strong></td>
  <td><strong>bk</strong></td>
  <td><strong>ck</strong></td>
</tr>";
$raices = 
"<br><br><table border = '1'>
<tr>
  <td><strong>Raices</strong></td>
</tr>";
busquedaCambioSigno($a,$b,$intervalos);
$tabla .= 
"
</table>
";
$raices .= 
"
</table>
";

echo $tabla;
echo $raices;


?>


<DOCTYPE! html>
<html>
<meta charset="utf-8">
  <head><title>Raices por bisecciónSecante</title></head>
  <body>
    <form action="main_proyecto.html" method="get">
		<p><input type="submit" name="submit" value="Volver" /></p>
	</form>
  </body>
</html>


