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
