<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>

        <meta charset="utf-8">

        <!--Import Google Icon Font-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">

        <!--Import materialize.css-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

        <!--Let browser know website is optimized for mobile and don't allow resizing on mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="chat.css">
    </head>
    <body class="grey lighten-5">
        <div class="container">
            <div id="card" class="card-panel blue">
                <div id="title" class="col s12 blue darken-3 valign-wrapper">
                    <a href="#"><i id="back" class="material-icons white-text">arrow_back</i></a>
                    <img id="image" class="circle" src="https://lh3.googleusercontent.com/-lKI-x6oWTNs/AAAAAAAAAAI/AAAAAAAAAAA/ACnBePbz_cEtXffDDO3RdqA400SfVq7PPg/s32-c-mo/photo.jpg">
                    <p id="user" class="white-text">Usuario</p>
                </div>

                <div id="messages" class="row"></div>

                <div id="message-box-card" class="card-panel">
                    <div id="message-box-row" class="row valign-wrapper">
                        <div id="message-box-wrapper" class="input-field col s12">
                            <textarea id="message-box" class="materialize-textarea" placeholder="Escribir mensaje"></textarea>
                        </div>
                        <i id="publish" class="material-icons disabled grey-text">send</i>
                    </div>
                </div>
            </div>
        </div>

        <!--Import jQuery before materialize.js-->
        <script src="https://unpkg.com/jquery/dist/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

        <script src="chat.js"></script>
    </body>
</html>