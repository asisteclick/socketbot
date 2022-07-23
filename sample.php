<?php
    header('Access-Control-Allow-Origin: *');
    $phone = $_GET['phone'];
    $aspid = $_GET['aspid']; 
    $lang = $_GET['lang'];
    $bot_id = $_GET['bot_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>AsisteClick Socket-Client Bot</title>
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"/> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body> 

    <!-- a bootstrap input field with a submit button to the right -->
    <div class="container">
        <div class="row">
			<div class="col-md-12" style="margin-bottom: 20px;">
				<h1>AsisteClick Socket-Client Bot</h1>	
				Ejemplo de adaptador de sockets en Javascript para el control de una conversación con un bot. Este bot habla en español y puede conversar varios tópicos, responder consultas científicas, realizar cálculos, buscar datos históricos, contar chistes y muchos más. 
				Github: <a href="https://github.com/asisteclick/socketbot" target="_blank">https://github.com/asisteclick/socketbot</a>.
			</div>
			<div class="col-md-12">
				<div class="input-group">
					<input id="message" type="text" class="form-control" placeholder="Mensaje al bot" disabled>
					<div class="input-group-btn">
						<button id="send_message" class="btn btn-primary" type="submit">
						&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;&nbsp;
						</button>
					</div>
				</div>
            </div>
        </div>
		<div id="messages" class="row"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

    <script>
    var session_id = null;
    var session_token = null; 

    // connects socket
    var socket = io.connect("https://sockets.asisteclick.com", {transports: ['websocket']});  
    log("Iniciando sesión...");
    
    // socket connected
    socket.on('connect', function() {

	// inits bot session
        var data_in = { 
            source: "PHONEMYBOT",
            fingerprint: "<?=$phone?>", 
            aspid: <?=$aspid?>, 
            bot_id: <?=$bot_id?>,
            customer_name: "n/a", 
            customer_email: "n/a", 
            customer_phone: "<?=$phone?>",
            language: "<?=$lang?>"
        };
	    socket.emit('socketbot new chat request', data_in, function(data) {
            session_id = data.session_id;
            session_token = data.session_token;
	    $("#message").prop("disabled", false);
            log("ID de sesión:", data.session_id);
        });

    });

    // socket listens to bot messages
    socket.on('agent says', function(data) {
    	$("#message").prop("disabled", false);
    	log("Bot:", data.msg, data);
    });

    // sends a message to the bot
    $("#send_message").click(function() {
        var message = $("#message").val();
	$("#message").prop("disabled", true);
        var data = {
            token: session_token, 
            session_id: session_id, 
            message: message
        }
        socket.emit('socketbot customer says', data); 
        $("#message").val("");
        log("Cliente:", message);
    });

    // log conversation to the screen
    function log(who, what, data) { 
	what = (what) ? what : "";
	data = (data) ? `<br/><span style="color:lightgray">${JSON.stringify(data)}</span>` : "";
        $("#messages").prepend(`
           <div class="col-md-12" style="margin-top: 20px;">
               <b>${who}</b> ${what}
	       ${data}
           </div>
        `);
    }
    </script>         

</body>
</html> 
