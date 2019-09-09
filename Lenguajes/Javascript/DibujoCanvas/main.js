// en document se puede analizar el teclado
var canvas = document.getElementById("dibujito")
var lienzo = canvas.getContext("2d")


var ancho = 2000
var alto = 400
var x = ancho/2
var y = alto/2

// dibujarLinea(149, 149, 151, 151, "red", lienzo)  // se hizo un punto

dibujarLinea(1, 1, ancho-1, 1, "red", lienzo)
dibujarLinea(ancho-1, 1, ancho-1 , alto-1, "red", lienzo)
dibujarLinea(1, 1, 1, alto-1, "red", lienzo)
dibujarLinea(1, alto-1, ancho-1, alto-1, "red", lienzo)


document.addEventListener("keydown", dibujarTeclado)


document.addEventListener("mousedown", mouseDown)
document.addEventListener("mouseup", mouseUp)
// mouseup y mousedown hasta en el celular vale
// hace retos Freddy



colorsito = "purple"
color_anterior = "purple"
console.log(document)



var diccionario_teclas = {
	ARRIBA: 38,
	DERECHA: 39,
	ABAJO: 40,
	IZQUIERDA: 37
	
};


function dibujarLinea(x1, y1, x2, y2, color, lienzo){
	lienzo.beginPath()  // inicia el dibujo
	lienzo.strokeStyle = color
	lienzo.linewidth = 5  // Freddy mostró esto luego, no lo puso todo a la vez
	lienzo.moveTo(x1, y1)
	lienzo.lineTo(x2, y2)
	lienzo.stroke()  // dibuja el camino
	lienzo.closePath()  // levanta el lapiz, termina
	// Importante: si no pongo el closePath la siguiente linea inicia desde el último punto
}

function dibujarTeclado(evento){
	
	// se puede mandar el evento
	console.log(evento)
	color = color_anterior
	movimiento = 20
	
	switch(evento.keyCode){
		case diccionario_teclas.ARRIBA:
			dibujarLinea(x, y, x, y-movimiento, color, lienzo)
			y -= movimiento
			break;
		case diccionario_teclas.DERECHA:
			dibujarLinea(x, y, x+movimiento, y, color, lienzo)
			x += movimiento
			break;
		case diccionario_teclas.ABAJO:
			dibujarLinea(x, y, x, y+movimiento, color, lienzo)
			y += movimiento
			break;
		case diccionario_teclas.IZQUIERDA:
			dibujarLinea(x, y, x-movimiento, y, color, lienzo)
			x -= movimiento
			break;
		default:
			console.log("Otra tecla")
			break;	
		
	}
	
}

function mouseUp(evento){
	colores = ["red", "blue", "green", , "yellow", "orange", "purple", "brown", "black", "black"]
	color_anterior = colorsito
	colorsito = colores[aleatorio(0, 9)]	
	//console.log(colorsito)
	document.removeEventListener("mousemove", dibujarMouse)
}

function aleatorio(inicio, fin){
	
	var rand = Math.random()*fin;
	return inicio + parseInt(rand)
}

function dibujarMouse(evento){
	console.log(evento)
	dibujarLinea(x, y, evento.layerX-6, evento.layerY-6, colorsito, lienzo)
	x = evento.layerX-6
	y = evento.layerY-6
}

function mouseDown(evento){
	console.log(evento)
	x = evento.layerX-6
	y = evento.layerY-6
	document.addEventListener("mousemove", dibujarMouse)
	
	
}