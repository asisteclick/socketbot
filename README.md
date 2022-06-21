# socketbot
Adaptador web para conectar con un chatbot de AsisteClick y controlar la recepción y envío de mensajes. Esta adaptor sirve cuando se requiere:

- Integrar un chatbot en un flujo propietario
- Integrar un chatbot en una app nativa 
- Modificar y controlar el look & feel web
- Agregar un canal alternativo (por ejemplo, voice)

# Funcionamiento
El adaptador se conecta con AsisteClick mediante Socket.IO en Javascript utilizando el protocolo Websockets. Para realizar la conexión con un bot en particular se requiere conocer la siguiente información:

- ID de la cuenta (en AsisteClick, ver Mi Cuenta > Empresa) 
- ID del chatbot (en AsisteClick, ver Configuración > Chatbots)

Las conversaciones se inicializan con los siguientes parámetros:

- Source. Es un ID descriptivo del proveedor del adaptador, por ejemlpo, "PHONEMYBOT".
- Fingerprint. Es un ID que identifica unívocamente al cliente que conversa con el bot. En un canal de voz, el fingerprint puede ser el número de teléfono del cliente.
- Customer_name. Es el nombre del cliente. Si no se tiene este valor se puede reemplazar por "n/a".
- Customer_phone. Es el teléfono del cliente. Si no se tiene este valor se puede reemplazar por "n/a".
- Language. Es el idioma por default del bot. Al momento, este valor es "es".

# Estructura del adaptor
El adaptador se compone de tres partes:

- Conexión con Websocket
- Inicialización de una conversación
- Recepción de respuestas del bot
- Envío de preguntas al bot
- Finalizar una conversación

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

## Recepción de respuesta del bot

```
// evento que recepciona una respuesta del bot
socket.on('agent says', function(data) {
    console.log(data)
});
```

## Envio de preguntas al bot

```
// Objeto con la pregunta a enviar
var data = {
    token: session_token, 
    session_id: session_id, 
    message: "Cuál es tu nombre?"
}
// Envio la pregunta al servidor
socket.emit('socketbot customer says', data); 
```

## Finalizar una conversación

```
socket.emit('client: client closes chat', {token: session_token, session_id: session_id} );
```

# Ejemplo

El archivo **sample.php** es un ejemplo funcional del HTML y Javascript necesario para conversar con un chatbot. Se puede probar online en: https://app.asisteclick.com/tools/socketbot/sample.php?phone=&aspid=11&bot_id=4155&lang=es
