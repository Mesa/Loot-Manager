<style text="text/css">
    #menu a {
        display: inline-block;
        width: 100%;
        padding: 6px 0px;
        text-decoration: none;
        font-weight: 900;
        /*font-size: 0.8em;*/
        /*font-size: 12px;*/
    }
    #header #menu {
        font-size: 0.8em;
    }

    #menu ul {
        padding: 0px;
        background-color: white;
    }

    #menu {
        padding: 0px;
        margin-top: -6px;
        margin-bottom: -5px;
        text-align: center;
    }

    #menu .menu-hover>a {
        color: white;
    }

    #menu .menu-hover {
        background-color: steelblue;
        color: white;
    }

    #menu span {
        background-color: lightgray;
        border: 1px solid #999;
        background-image: url(<?php echo $this->registry->get("WEB_ROOT") ?>bullet_arrow_right.png);
        background-position: 1px 0px;
        cursor: pointer;
        position: absolute;
        top: 5px;
        right: 3px;
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    #menu li {
        position: relative;
        display: inline-block;
        padding-left: 25px;
        padding-right: 25px;
        width: 130px;
    }

    .topnav>li {
        border-left: 1px solid lightgray;
        border-right: 1px solid lightgray;
        margin-left: -1px;
    }

    #menu .icon {
        position: absolute;
        top:5px;
        left: 5px;
    }

    .subnav {
        position: absolute;
        top:100%;
        left: -1px;
        border-left: 1px solid lightgray;
        border-right: 1px solid lightgray;
        border-bottom: 1px solid lightgray;
        z-index: 99;
        display: none;
    }

    .subnav li {
        border-top: 1px solid lightgray;
    }

    #menu img {
        width: 16px;
        height: 16px;
    }
</style>