<style type="text/css">

    #event_loot_dialog, #player_loot_dialog {
        padding: 0px !important;
    }
    #total_player_list .headline {
        color: steelblue;
        border-bottom: 1px solid #C0C0C0;
        font-weight: 900;
        font-size: 1.8em;
        text-align: center;
        padding-bottom: 20px;
        margin-left:-8px;
        margin-right:-8px;
        position: relative;
    }

    #edit_player_position, #edit_char_name {
        font-size: 1.2em;
    }

    #player_menu .block {
        text-align: center;
    }

    #player_menu .headline {
        font-size: 1.5em;
        position: relative;
    }


    #player_menu .headline img {
        position: absolute;
        top: 3px;
        left: 10px;
    }

    #create_event_input {
        font-size: 1em;
        width: 90%;
        margin-left: 5%;
    }

    #create_player .block {
        text-align: center;
    }

    #create_player_btn, .event_link {
        width: 90%;
        font-size: 1.1em;
        margin-left: 5%;
    }

    .event_link {
        margin-bottom: 5px;
    }

    #create_new_event {
        margin-top: 15px;
    }

    .class_filter {
        border-bottom: 1px solid #C0C0C0;
        font-weight: 900;
        text-align: center;
        font-size: 1.1em;
        position: relative;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .class_filter .button {
        position: absolute;
        top: 2px;
        left: 3px;
    }

    .position {
        color: #999999;
        font-size: 1.2em;
    }

    .last_loot{
        color: #999999;
        font-size: 0.9em;
    }

    .name, .class {
        font-size: 1.2em;
    }

    #total_player_list .active, .list .active {
        background-color: lightblue;
        color: black;
    }

    .filter {
        width: 150px;
        position: absolute;
        top: 15px;
        left: 3px;
        z-index: 99;
    }

    #total_player_list {
        position: relative;
    }

    #total_player_list  {
        width: 80%;
        margin: 0px auto;
        float: left;
    }

    #event_name_edit {
        width: 80%;
    }

    .missing_player {
        border-bottom: 1px solid lightgray;
        position: relative;
        padding-bottom: 6px;
        padding-left: 23px;
        padding-top: 6px;
    }

    .missing_player .options_right {
        position: absolute;
        top: 2px;
        right: 0px;
    }

    .missing_player .options_left {
        position: absolute;
        top: 2px;
        left: 0px;
    }

    .total_list,#list_desc {
        position: relative;
        padding-bottom: 3px;
        display: block;
    }

    #list_desc {
        margin-top: -24px;
    }

    #total_player_list li {
        list-style-type: none;
    }

    .total_list .position, #list_desc .position {
        position: absolute;
        top: 0px;
        left: 0px;
        text-align: right;
        font-size:0.9em;
        width:25px;
        font-family: knewave, sans;
    }

    .total_list .name, #list_desc .name {
        padding-left: 45px;
    }

    .total_list .class, #list_desc .class {
        position: absolute;
        right: 400px;
        top: 0px;
        width: 100px;
        text-align: left;
    }

    .total_list .type,
    #list_desc .type {
        position: absolute;
        top: 0px;
        right: 100px;
        width: 280px;
        text-align: left;
    }

    .total_list .last_loot,
    #list_desc .last_loot {
        font-family: knewave, sans;
        position: absolute;
        top: 0px;
        right: 0px;
        width: 80px;
        text-align: left;
    }

    #list_desc .position,
    #list_desc .name,
    #list_desc .class,
    #list_desc .last_loot,
    #list_desc .type {
        color: #999999;
        font-size: 1em;
        font-family: Tahoma;
    }

    #player_loot_dialog .date,
    #event_loot_log .date {
        position: absolute;
        top: 3px;
        right: 0px;
        font-size: 0.9em;
    }

    #player_loot_dialog .description,
    #event_loot_log .description {
        font-weight: 900;
        padding-top: 5px;
        padding-top: 15px;
        padding-left: 20px;
        color: steelblue;
        font-size: 1.1em;
        float: left;
    }

    #player_loot_dialog .admin_name,
    #event_loot_log .admin_name {
        color: steelblue;
        float: right;
        width: 100px;
        font-weight: 900;
        font-size: 1em;
        text-align: right;
    }

    #player_loot_dialog .fromPosition,
    #player_loot_dialog .toPosition,
    #event_loot_log .fromPosition,
    #event_loot_log .toPosition    {
        position: absolute;
        top: 0px;
        left: 20px;
        font-size: 0.9em;
    }

    #player_loot_dialog .toPosition,
    #event_loot_log .toPosition {
        left: 65px;
    }

    #player_loot_dialog .fromPosition span,
    #player_loot_dialog .toPosition span ,
    #event_loot_log .fromPosition span,
    #event_loot_log .toPosition span
    {
        color: steelblue;
        font-family: knewave;
    }

    #event_loot_log .log_event,
    #player_loot_dialog .log_event {
        position: relative;
        border-bottom: 1px solid lightgray;
        padding-bottom: 5px;
        padding-top: 5px;
    }

    #player_loot_dialog .action_icon,
    #event_loot_log .action_icon {
        position: absolute;
        top: 3px;
        left: 0px;
    }

    #player_loot_dialog h3,
    #event_loot_log .action h3 {
        padding-left: 20px;
    }

    #event_loot_log .char_name,
    #player_loot_dialog .event_name {
        position: absolute;
        top: 3px;
        left: 200px;
        font-weight: 900;
        color: steelblue;
    }

    .ui-log-removed {
        background-image: url(<?php echo $web_root?>group_delete.png) !important;
    }

    .ui-log-suicide {
        background-image: url(<?php echo $web_root?>coins.png) !important;
    }

    .ui-log-moved {
        background-image: url(<?php echo $web_root?>arrow_switch.png) !important;
    }
    .ui-log-added {
        background-image: url(<?php echo $web_root?>group_add.png) !important;
    }

    .extra_log_data {
        position: relative;
    }

    .player-log-info {
        background-color: #FBCB46;
        border: 1px solid orange;
    }

    .log_filter {
        float: left;
        width: 24%;
        padding-bottom: 10px;
    }

    .log-refresh-button {
        background-image: url(<?php echo $web_root?>arrow_rotate_clockwise.png) !important;
    }

    .log_filter_bottom label {
        float: right;
    }
    .log_filter_bottom button {
        float: left;
    }

    .log_filter_bottom {
        float:left;
        width: 49%;
    }

    #event_log_filter_count,#char_log_filter_count {
        text-align: center;
        width: 40px;
    }

</style>