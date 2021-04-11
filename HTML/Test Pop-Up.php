<!DOCTYPE html>
<html>
    <head>
        <style>
            body{
                height: 5000px;
            }
            .loginModel{
                display: none;
                position: fixed;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
            }
            form {
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }
        </style>
    </head>
    <body>
        <button id="btn" onclick="op()">Click Me</button>
        <div id="loginModel" class="loginModel">
            <form>
                <input placeholder="Username">
                <input placeholder="Password">
                <button id="close" onclick="cl()">Close</button>
            </form>
        </div>
        
        <script>
            var login = document.getElementById('loginModel');
            var open = document.getElementById('btn');
            var close = document.getElementById('close');
            
            function op() {
                login.style.display = 'block';
            }
            function cl() {
                login.style.display = 'none';
            }
            /*
            window.onclick = function(event) {
                if(event.target == login){
                    login.style.display = 'none';
                }
            }
            */
        </script>
    </body>
</html>