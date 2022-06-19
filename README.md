# socketbot
Adaptador web para conectar con un chatbot de AsisteClick y controlar la recepción y envío de mensajes. Esta adaptor sirve cuando se requiere:

- Integrar un chatbot en un flujo propietario
- Integrar un chatbot en una app nativa 
- Modificar y controlar el look & feel web
- Agregar un canal alternativo (por ejemplo, voice)

Puedes probarlo chateando con este bot: https://app.asisteclick.com/tools/socketbot/sample.php?phone=&aspid=11&bot_id=4155&lang=es

# Funcionamiento
El adaptar se conecta con el servidor usando Socket.IO en Javascript.

# Estructura del adaptor
El adaptador se compone de tres partes:

- Conexión con Websocket
- Inicialización de una conversación
- Recepción de respuestas del bot
- Envío de preguntas al bot

## Conexión con Websocket

```
var socket = io.connect("https://sockets.asisteclick.com", {transports: ['websocket']});
```

## Inicialización de una conversación

```
// evento de socket conectado
socket.on('connect', function() {
		// objeto con datos de nueva sesión
    var data_in = { 
        source: "PROVIDER_NAME",
        fingerprint: "5491150173420", 
        aspid: 11, 
        bot_id: 4155,
        customer_name: "n/a", 
        customer_email: "n/a", 
        customer_phone: "n/a",
        language: "es"
    };
    // envia la solicitud de nueva sesión al servidor
		socket.emit('socketbot new chat request', data_in, function(data) {
        // guardar la session_id y session_token para usarlo en el envio 
        // de preguntas 
        session_id = data.session_id;
        session_token = data.session_token;
    });
});
```
