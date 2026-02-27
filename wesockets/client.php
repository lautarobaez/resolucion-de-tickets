<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat WebSocket</title>
    <style>
        html, body {
            font: normal 0.9em arial, helvetica;
        }
        #log {
            width: 600px;
            height: 300px;
            border: 1px solid #7F9DB9;
            overflow: auto;
            padding: 5px;
            background: #f8f8f8;
        }
        #msg {
            width: 400px;
        }
        #users_online {
            font-weight: bold;
            color: #007bff;
        }
        button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <h3>Chat WebSocket</h3>

    <div>
        Usuarios conectados: <span id="users_online">0</span>
    </div>

    <div id="log"></div>
    <br>
    <label>Alias: </label>
    <input id="alias" type="text" placeholder="Nombre">
    <br><br>
    <input id="msg" type="text" onkeypress="onkey(event)" placeholder="Escribe un mensaje"/>
    <button onclick="send()">Enviar</button>
    <button onclick="quit()">Salir</button>
    <button onclick="reconnect()">Reconectar</button>

    <script>
        var socket;
        var alias = "";

        function init() {
            var host = "ws://localhost:8000"; 
            try {
                socket = new WebSocket(host);
                log("WebSocket - status " + socket.readyState);

                socket.onopen = function() {
                    log("Bienvenido al chat. Conectado - Estado " + this.readyState);
                };

                socket.onmessage = function(event) {
                    try {
                        var data = JSON.parse(event.data);
                        if(data.type === "chat") {
                            log(data.msg);
                        } else if(data.type === "users_count") {
                            document.getElementById("users_online").innerText = data.count;
                        }
                    } catch(e) {
                        console.error("Error parseando mensaje:", e);
                    }
                };

                socket.onclose = function() {
                    log("Desconectado - estado " + this.readyState);
                };
            }
            catch (ex) {
                log(ex);
            }
            $("msg").focus();
        }

        function send() {
            var txt = $("msg");
            var message = txt.value.trim();

            if (!message) {
                alert("El mensaje no puede estar vacío");
                return;
            }

            alias = $("alias").value.trim();  
            if (!alias) {
                alert("Debes ingresar un alias");
                return;
            }

            var fullMessage = alias + ": " + message;

            txt.value = "";
            txt.focus();
            try {
                socket.send(fullMessage);
            } catch (ex) {
                log(ex);
            }
        }

        function quit() {
            if (socket != null) {
                log("Adiós!");
                socket.close();
                socket = null;
            }
        }

        function reconnect() {
            quit();
            init();
        }

        // Utilities
        function $(id) { return document.getElementById(id); }
        function log(msg) { $("log").innerHTML += "<br>" + msg; }
        function onkey(event) { if (event.keyCode == 13) { send(); } }

        window.onload = init;
    </script>
</body>
</html>
