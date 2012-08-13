<style type="text/css">
    .dungeon {
        margin:5px;
        margin-bottom: 25px;
        position: relative;
        width: 99%;
        padding: 3px 5px 5px 5px;        
    }

    .dungeon>.options{
        position: absolute;
        top: 6px;
        right: 10px;
    }

    .encounter .options {
        position: absolute;
        top: 2px;
        left: 2px;
    }

    .encounter .status {
        position: absolute;
        top:2px;
        right: 5px;
        text-decoration: none;
    }
    .status img {
        width: 25px;
        height: 25px;
    }
    .encounter {
        position:relative;
        padding-right: 25px;
        width: 19%;
        float: left;
        padding-top: 8px;
        padding-bottom: 8px;
        margin-left: 10px;
        margin-bottom: 5px;
        margin-top: 5px;
    }
    
    #trash .options {
        display: none;
    }
    
    #trash img {
        width: 20px;
        height: 20px;
    }
    
    #trash {
        position: relative;
        width: 93%;
    }

    .block input {
        width: 99%;
    }

    #trash .clear {
        position: absolute;
        top: 30px;
        right: 3px;
    }

    #trash .dungeon {
        font-size: 0.6em;
        margin: 1px;
        width: 95%    
    }

    #trash .encounter {
        float: none;
        width:95%;
        margin: 2px;
        padding-right: 0px;
        z-index: 70;
        text-align: left;
        font-size: 0.6em;
    }
/*
    .dungeon .block, #trash .block {
        border: 1px solid lightgray;
    }*/

    .dungeon .enc_down, #trash .enc_down {
        border: 1px solid green;
        color: green;
    }

    .encounter {
        text-align: center;
        font-size: 1em;
        font-weight: 900;
    }
</style>