<style type="text/css">
    * {
        margin: 0px;
        padding: 0px;
    }

    ul {
        padding-left: 15px;
    }

    h4 {
        padding-top: 15px;
    }

    body {
        color: #333333;
        background-color: #f9f9f9;
        font-size: 12px;
        font-family: Tahoma,Verdana,Sans-serif;
    }

    a, a:hover {
        color: steelblue;
    }


    #header {
        position: relative;
        font-size: 1.2em;
        text-align: center;
        z-index: 99;
    }

    #header img {
        width: 500px;
        height: 80px;
    }

    #wrapper {
        width: 1000px;
        margin: 0px auto;
        position: relative;
    }

    #footer {
        position: relative;
        margin-bottom: 15px;
    }

    #copy, #copy a, #footer, #footer a {
        text-align: right;
        color: #888888;
        text-align: center;
    }

    .border_light {
        border: 1px solid lightgray;
    }

    .border_dark {
        border: 1px solid steelblue;
    }

    .headline {
        margin-bottom: 10px;
        padding-top: 3px;
        padding-bottom: 3px;
        background-color: #EEEEEE;
        color: steelblue;
        font-family:  Patrick Hand, sans;
        font-size: 1.2em;
        border-bottom: 1px solid lightgray;
        border-top: 1px solid lightgray;
        text-align: center;
    }

    h3.headline, h2.headline, h1.headline {
        margin-left:-8px;
        margin-right: -8px;
    }

    .headline .options {
        position: absolute;
        top:3px;
        right: 3px;
        font-size: 14px;
    }

    .block  {
        margin-bottom: 15px;
        padding: 5px;
        box-shadow: 2px 2px 15px lightgray;
        -moz-box-shadow: 2px 2px 15px lightgrey;
        -webkit-box-shadow: 2px 2px 15px lightgrey;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -khtml-border-radius: 4px;
        border-radius: 4px;
        background-color: white;
        -ms-filter:"progid:DXImageTransform.Microsoft.DropShadow(color=#333333, offx=2, offy=2)";
        -ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#AAAAAA')";
        /* For IE 5.5 - 7 */
        filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#AAAAAA');
    }

    #login {
        font-size: 1.5em;
        margin: 0px auto;
        padding: 20px;
        width: 200px;
        font-family: Patrick Hand, Sans;
    }


    #remember_me {
        width: 20px !important;
    }

    #login input {
        width: 85%;
        text-align: center;
        margin-left:5%;
    }

    .user_input {
        margin-left: 33%;
        margin-bottom: 5px;
        margin-top: 5px;
        padding: 2px;
        border: 1px solid lightgray;
        color: steelblue;
        font-size: 1.4em;
        font-weight: 900;
        width: 40%;
    }

    #stay_online {
        font-size: 0.7em;
    }

    #left_block {
        width: 160px;
        float: right;
    }

    #right_block {
        width: 80%;
        margin-top: -5px;
        float: left;
    }
/*    .menu {

        font-size: 1.2em;
        padding-left:5px !important;
        padding-right: 5px !important;
    }
    .menu li {
        display:inline;
        padding: 5px;
    }*/
    .ui-dialog {
        font-size: 0.9em !important;
    }
    .ui-dialog .block {
        text-align: center;
    }
    .ui-dialog .headline {
        font-size: 1.4em !important;
    }
    #create_player, .ui-autocomplete {
        font-size: 1.2em;
    }
    .error_msg {
        color: darkred;
        background-color:#ffcccc;
        border: 1px solid darkred;
        font-size: 12px;
    }
    .replace_value {
        color: grey;
    }
    .hidden {
        display: none;
    }
    .clear {
        clear: both;
    }
    #footer .right_block {
        position: absolute;
        top: 0px;
        right: 0px;
        padding-right: 3px;
    }

    #footer .left_block {
        position: absolute;
        left: 0px;
        top: 0px;
        padding-left: 3px;
    }

    .float_left {
        float: left;
    }

    .float_right {
        float: right;
    }

    .width_50 {
        width: 49%;
    }

    .highlight {
        background-color: #ffffAA;
    }

    .information {
        border: 1px solid steelblue;
        background-color: #DDEEFF;
        margin-top: 5px;
        margin-bottom: 10px;
        padding: 5px;
        color: #333333;
    }

    .data_block {
        border-bottom: 1px solid #C0C0C0;
        padding-right: 53%;
        position: relative;
        padding-top: 3px;
        padding-bottom: 5px;
        margin-bottom: 5px;
    }

    .block textarea {
        width: 100%;
        height: 50px;
    }

    .data_block input{
        position: absolute;
        top: 0px;
        right: 0px;
        width: 50%;
        font-size: 1.2em;
        text-align: center;
    }

    .slide_block_menu {
        position: relative;
        z-index: 100;
    }

    .slide_block_menu .input_block {
        position: absolute;
        background-color: white;
        top:23px;
        left: 0px;
        width: 250px;
        padding: 5px;
    }

    .input_block {
        text-align: center;
    }

    .pre-loader {
        font-size: 1.2em;
        font-family: Patrick Hand, Sans;
        background-color: #DDEEFF;
        border: 1px solid steelblue;
        padding: 10px;
    }

    .warning {
        background-color: #ffffAA;
        border: 1px solid orange;
        padding: 5px 5px 5px 35px;
        color: black;
        background-image: url(<?php echo $web_root ?>error.png);
        background-repeat: no-repeat;
        background-position: 10px center;
    }

    .javascript-message {
        background-image: url(<?php echo $web_root?>opacity_dark.png);
        border: 1px solid black;
        color: white;
        margin-bottom: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        -khtml-border-radius: 5px;
        border-radius: 5px;
        padding:10px;
    }

    #javascript-messages {
        width: 300px;
        position: absolute;
        top: 30px;
        right: 30px;
    }
    #header-options {
        position: relative;
    }

    #language-switcher {
        font-size: 0.8em;
        position: absolute;
        top: -18px;
        right: 3px;
        border-top: 1px solid lightgray;
        border-left: 1px solid lightgray;
        border-right: 1px solid lightgray;
        background-color: #EEE;
        overflow: hidden;
        z-index: 99;
        height: 26px;
    }
    #language-switcher ul {
        margin: 0px;
        padding: 0px;
    }
    #language-switcher li {
        padding: 8px;
        list-style: none;
        border-bottom: 1px solid lightgray;
    }
    #language-switcher .selected {
        color: steelblue;
        background-color: white;
    }

    .input_desc {
        color: lightgray;
    }

    .autocomplete {
        background-color: white;
        border: 1px solid steelblue;
    }
    .autocomplete ul{
        margin: 0px;
        padding: 0px;
    }

    .autocomplete li{
        list-style: none;
        border-bottom: 1px solid lightgray;
        margin: 0px;
        padding: 5px;
    }

    .autocomplete .hightlight {
        background-color: #ffffAA;
    }

    #edit-me-editor {
        z-index: 199;
        background-color: white;
        border: 1px solid lightgray;
    }

    #edit-me-editor ul {
        padding: 0px;
        margin: 0px;
    }
    #edit-me-editor li {
        list-style: none;
        margin: 0px;
        padding: 5px;
        cursor: pointer;
    }

    #submenu {
        z-index: 70;
        margin-bottom: 10px;
        margin-top:-8px;
    }
    .submenu_bottom {
        padding         : 0px !important;
        background-color: #F9F9F9;
        height          : 15px;
        border          : 1px solid lightgray;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -khtml-border-radius: 4px;
        border-radius: 4px;
    }
    #submenu button {
        position: relative;
        border: 1px solid lightgray;
        background-color: white;
        width: 80px;
        height: 80px;
        margin-left: 5px;
        margin-right: 5px;
        float: right;
    }
    #submenu button img{
        margin-top: 10px;
    }
    #submenu .description {
        color: #999;
        text-align: center;
        font-family: Patrick Hand;
    }
    #submenu button:hover {
        border: 1px solid steelblue;
        background-color: lightyellow;
        width: 80px;
        height: 80px;
        margin-left: 5px;
        margin-right: 5px;
    }

    .btn_down {
        border: 1px solid black !important;
        background-color: lightgray !important;
    }

    #edit-me-layer button {
        color: steelblue;
        border: 1px solid steelblue;
        background-color: #F9F9F9;
        width: 120px;
        height: 25px;
        margin-top: 5px;
        margin-bottom: 5px;
        font-weight: 900;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        -khtml-border-radius: 4px;
        border-radius: 4px;
        cursor: pointer;
        background-repeat: no-repeat;
        background-position: 5px 3px;
        /*float: right;*/
    }
    #edit-me-layer #edit-me-button-save {
        background-image: url(<?php echo $web_root?>disk.png);
    }
    #edit-me-layer #edit-me-button-cancel {
        background-image: url(<?php echo $web_root?>cross.png);
    }
    #edit-me-layer #edit-me-button-edit {
        background-image: url(<?php echo $web_root?>pencil.png);
    }
    #edit-me-layer button:hover {
        color: white;
        background-color: steelblue
    }
    #user-info {
        position: absolute;
        bottom: -12px;
        left: -10px;
        color: #444444;
        font-size: 0.8em;
        border-top: 1px solid lightgray;
        border-right: 1px solid lightgray;
        padding: 5px 8px 15px 15px;
        background-color: #F9F9F9;
    }
    #user-info a {
        margin-top: 5px;
        font-size: 0.9em;
    }
    .text {
        height: 1.4em;
    }
</style>