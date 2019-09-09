// en document se puede analizar el teclado
var canvas = document.getElementById("dibujito")
var lienzo = canvas.getContext("2d")


var botonIniciar = document.getElementById("iniciar");

botonIniciar.addEventListener("click",simularVida);

var botonReiniciar = document.getElementById("reiniciar");

botonReiniciar.addEventListener("click",reset);

var botonLimpiar = document.getElementById("limpiar");

botonLimpiar.addEventListener("click",limpiarEspacio);


var texto_generaciones = document.getElementById("generacion_actual")


canvas.width = screen.availWidth-50


//console.log(lienzo)
var ancho = canvas.width
var alto = canvas.height
var lado_celula = 10
var columnas = ancho/lado_celula
var filas = alto/lado_celula

var color_celula_viva = "green"
var color_celula_muerta = "black"
var color_borde = "red"

document.addEventListener("mousedown", mouseDown)
document.addEventListener("mouseup", mouseUp)

var celulas = []
for(var f = 0; f< filas; f++){
	var fila = []
	for(var c = 0; c < columnas; c++){
		
		
		if(c == 0 || f == 0 || c == columnas-1 || f == filas-1){
			fila.push(-1)
			continue
		}
			
		var celula = aleatorio(0,10) //fuerzo las muertas 10 veces más que la s vivas
		//console.log(celula)
		if(celula == 0){
			fila.push(1)
		}else{
			fila.push(0)
		}		
	}
	celulas.push(fila)
}


dibujarMatrizCelulas(celulas)


var offsetLeft = canvas.offsetLeft
var offsetTop = canvas.offsetTop

async function simularVida() {
	
	var retardo = document.getElementById("delay").value
	var apocalipsis = document.getElementById("generaciones").value
	
	if(apocalipsis == ""){
		texto_generaciones.innerHTML = "Ingrese el valor de la generacion máxima"
		return
	}
	
	
	if(retardo == ""){
		texto_generaciones.innerHTML = "Ingrese el valor de la pausa"
		return
	}
	
	if(apocalipsis < 1){
		texto_generaciones.innerHTML = "El día del fin debe ser mayor o igual a 1"
		return
	}
	
	if(retardo  < 0){
		texto_generaciones.innerHTML = "El retardo debe ser mayor o igual a 0"
		return
	}
	
	//console.log(retardo)
	
	for(var generacion = 1; generacion<=apocalipsis; generacion++){
		texto_generaciones.innerHTML = "Generacion: " + generacion
		
		
		
		//console.log(celulas == nuevas_celulas)
		//console.log(lienzo)
		
		nuevas_celulas = obtenerNuevaGeneracion(celulas)
		celulas = nuevas_celulas
		
		dibujarMatrizCelulas(celulas)
		await sleep(retardo)
	}
	
}

function dibujarMatrizCelulas(matriz){
	
	for(var f = 0; f< filas; f++){
		for(var c = 0; c < columnas; c++){
			
			
			
			if(matriz[f][c] == 0){
				dibujarCelula(f,c, color_celula_muerta)
			}else if(matriz[f][c] == 1){
				dibujarCelula(f,c, color_celula_viva)
			}else{
				dibujarCelula(f,c, color_borde)
			}
		}
	}
	
}

function limpiarEspacio(){
	
	lienzo.fillStyle = color_borde
	
	lienzo.fillRect(0,0,ancho, lado_celula)
	lienzo.fillRect(0,lado_celula,lado_celula, alto-lado_celula)
	lienzo.fillRect(0, alto-lado_celula,ancho, lado_celula)
	lienzo.fillRect(ancho-lado_celula,0,lado_celula, alto)
	
	lienzo.fillStyle = color_celula_muerta
	lienzo.fillRect(lado_celula,lado_celula,ancho-lado_celula*2, alto-lado_celula*2)
	
	celulas = []
	for(var f = 0; f< filas; f++){
		var fila = []
		for(var c = 0; c < columnas; c++){
			
			
			if(c == 0 || f == 0 || c == columnas-1 || f == filas-1){
				fila.push(-1)
				continue
			}
			fila.push(0)		
		}
		celulas.push(fila)
	}
	
}

function dibujarCelula(fila, columna, color){
	
	dibujarCuadrado(columna*lado_celula, fila*lado_celula, color)	
	
}

function dibujarCuadrado(x, y, color){
	
	lienzo.fillStyle = color
	lienzo.fillRect(x,y,lado_celula, lado_celula)
}


function dibujarLinea(x1, y1, x2, y2, color){
	lienzo.beginPath()  // inicia el dibujo
	lienzo.strokeStyle = color
	lienzo.linewidth = 10
	
	lienzo.moveTo(x1, y1)
	lienzo.lineTo(x2, y2)
	lienzo.stroke()  // dibuja el camino
	lienzo.closePath()  // levanta el lapiz, termina
	// Importante: si no pongo el closePath la siguiente linea inicia desde el último punto
}

function aleatorio(inicio, fin){
	
	var rand = Math.random()*(fin+0.99);
	return inicio + parseInt(rand)
}



function mouseUp(evento){
	//console.log(evento)
	document.removeEventListener("mousemove", dibujarMouse)
}

function dibujarMouse(evento){
	columna_pulsada = parseInt((evento.layerX-offsetLeft)/lado_celula);
	fila_pulsada = parseInt((evento.layerY-offsetTop)/lado_celula);
	
	if(columna_pulsada > 0 && columna_pulsada < columnas -1
	&& fila_pulsada > 0 && fila_pulsada < filas-1){
		
		//console.log("PULSO VALIDO")
		celulas[fila_pulsada][columna_pulsada] = 1
		dibujarCelula(fila_pulsada, columna_pulsada, color_celula_viva)
	}
}

function mouseDown(evento){
	console.log(evento)
	columna_pulsada = parseInt((evento.layerX-offsetLeft)/lado_celula);
	fila_pulsada = parseInt((evento.layerY-offsetTop)/lado_celula);
	
	if(columna_pulsada > 0 && columna_pulsada < columnas -1
	&& fila_pulsada > 0 && fila_pulsada < filas-1){
		
		//console.log("PULSO VALIDO")
		celulas[fila_pulsada][columna_pulsada] = 1
		dibujarCelula(fila_pulsada, columna_pulsada, color_celula_viva)
		document.addEventListener("mousemove", dibujarMouse)
	}/*else{
		console.log("PULSO NO VALIDO")
	}*/

	//console.log(fila_pulsada, columna_pulsada)
	
	
}

function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

function obtenerNuevaGeneracion(matriz)
{
	var valorCelVivas = 1
	var valorCelMuertas = 0
	var matrizCopia = []
	
	for(var i=0;i<filas;i++){
		var arreglo_copia = []
		for(var j=0;j<columnas;j++){
			arreglo_copia.push(matriz[i][j])
		}
		matrizCopia.push(arreglo_copia)
	
	}
	
    var vecinos=0

    for(var i=1; i<filas-1; i++)
    {
        for(var j=1; j<columnas-1; j++)
        {
            var posiciones =
            [
                [i-1,j],
                [i-1,j+1],
                [i,j+1],
                [i+1,j+1],
                [i+1,j],
                [i+1,j-1],
                [i,j-1],
                [i-1,j-1]
            ]

			//cuenta los vecinos
            for(var k=0; k<8; k++)
                if(matriz[posiciones[k][0]][posiciones[k][1]]==valorCelVivas)
                    vecinos++;

            if((vecinos==3 && matriz[i][j]==valorCelMuertas) || ((vecinos==2||vecinos==3) && matriz[i][j]==valorCelVivas))
                matrizCopia[i][j]=valorCelVivas;

            if((vecinos<2||vecinos>3) && matriz[i][j]==valorCelVivas)
                matrizCopia[i][j]=valorCelMuertas;
            vecinos=0;
        }
    }
	
	return matrizCopia
}


function reset()
{
	
	location.reload();
}
