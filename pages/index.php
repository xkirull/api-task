<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index page</title>
</head>
<body>

    <button id="loginButton" style="display: none;">Войти</button>
    <button id="logoutButton" style="display: none;">Выйти</button>

    <br />

    <button id="eventButton1">CLICK event</button>
    <button id="eventButton2">OPEN PAGE event</button>
    <button id="eventButton3">LIKE event</button>

    <br />
    <br />

    <div style="border: 2px solid; padding: 8px; width: max-content;">
        <h3>API link example</h3>
        <a href="/api/getEvents" target="_blank">/api/getEvents</a> <br />
        <a href="/api/getEventsByUser" target="_blank">/api/getEventsByUser</a> <br />
        <a href="/api/getEventsByStatusUser" target="_blank">/api/getEventsByStatusUser</a> <br />
        <a href="/api/getEvents?eventName=click" target="_blank">/api/getEvents?eventName=click</a> <br />
        <a href="/api/getEventsByUser?eventName=click" target="_blank">/api/getEventsByUser?eventName=click</a> <br />
        <a href="/api/getEvents?startDate=2023-22-22" target="_blank">/api/getEvents?startDate=2023-22-22</a> <br />
    </div>

    <script>
        function sendRequest(path, method, body) {
            fetch(path, {
                method: method,
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: body
            });
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            return parts.length === 2 ? parts.pop().split(';').shift() : null;
        }

        const parseCookie = 1;

        if (parseCookie && parseCookie["isAuth"] == 1) {
            document.getElementById("logoutButton").style.display = "block";
        } else {
            document.getElementById("loginButton").style.display = "block";
        }

        document.getElementById("eventButton1").onclick = sendRequest.bind(this, "/api/setEvent", "POST", `eventname=click`);
        document.getElementById("eventButton2").onclick = sendRequest.bind(this, "/api/setEvent", "POST", `eventname=open page`);
        document.getElementById("eventButton3").onclick = sendRequest.bind(this, "/api/setEvent", "POST", `eventname=like`);

        document.getElementById("loginButton").onclick = () => {
            sendRequest.call(this, "/api/userLogin", "POST", "1");
            document.getElementById("logoutButton").style.display = "block";
            document.getElementById("loginButton").style.display = "none";
        }

        document.getElementById("logoutButton").onclick = () => {
            sendRequest.call(this, "/api/userLogout", "POST", "0");
            document.getElementById("logoutButton").style.display = "none";
            document.getElementById("loginButton").style.display = "block";
        }
    </script>
</body>
</html>
