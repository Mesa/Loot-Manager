<script type="text/javascript">
    var charLogCharId;
    $(document).ready( function (){
        $("#event_log_filter_removed, #char_log_filter_removed").button({text:false,icons:{primary:"ui-log-removed"}});
        $("#event_log_filter_added, #char_log_filter_added").button({text:false,icons:{primary:"ui-log-added"}});
        $("#event_log_filter_suicide, #char_log_filter_suicide").button({text:false,icons:{primary:"ui-log-suicide"}});
        $("#event_log_filter_moved,#char_log_filter_moved").button({text:false,icons:{primary:"ui-log-moved"}});
        $("#log-refresh-btn,#charLog-refresh-btn").button({icons:{primary:"log-refresh-button"}});
        $("#event_log_btn").button({icons:{primary:"ui-icon-note"}});

        $("#event_log_btn, #log-refresh-btn, .event_log_filter_btn").button().click(function (){
            loadLog(charLogCharId);
        });

        $("#char_log_btn, #charLog-refresh-btn, .char_log_filter_btn").button().click(function (){
            loadCharLog(charLogCharId);
        });

        changeUserLogEventFilterBtn($("#char_log_filter_event"))

        $("#char_log_filter_event").button().click(function (){
            changeUserLogEventFilterBtn($(this))
        });
        replaceInputDesc($("#name_filter"), "Nach Spieler suchen");
        $("#name_filter").keyup( function () {
            var target = $(this);
            var search_name = target.val().toLowerCase();
            var minWidth = 1;

            if ( search_name.length > 0 ) {
                $("#event_player_list .name").each( function () {
                    var item = $(this);

                    var char_name = item.text().toLowerCase();
                    var result = char_name.match(search_name);
                    if ( result != null && item.is(":hidden")) {
                        item.parent().slideDown("fast");
                    } else if (result == null && item.parent().is(":visible")) {
                        item.parent().slideUp("fast");
                    }

                });
            } else {
                $(".total_list:hidden").slideDown("fast");
            }
        });

        $("#player_loot_dialog").dialog({
            autoOpen: false,
            width: "400px"
        });

        $("#event_loot_dialog").dialog({
            autoOpen: false,
            width: "400px"
        });

        $(".player_loot_log").button({
            icons: {
                primary: "ui-icon-note"
            }
        }).click( function () {
            charLogCharId = $(this).parent().parent().attr("Id");
            loadCharLog(charLogCharId);
            return false;
        });

        $(".select_class").button({
            icons :
                {
                primary: "ui-icon-circle-plus"
            },
            text: false
        }).click( function (e) {

            if ( $(this).attr('checked') ) {

                $(this).button( "option", "icons", {
                    primary:"ui-icon-circle-minus"
                } );
            } else {
                $(this).button( "option", "icons", {
                    primary:"ui-icon-circle-plus"
                } );
            }

            var filter_count = $(".select_class:checked").length;
            if ( filter_count == 0 ) {
                $(".total_list:hidden").slideDown("fast");
            } else {
                $(".select_class:checked").each( function() {
                    var target = $("." + $(this).attr('id').replace("slct_",""));
                    if ( target.is(":visible") == false ) {
                        target.slideDown("fast");
                    }
                });
                $(".select_class").not(":checked").each( function (){
                    var target = $("."+ $(this).attr('id').replace("slct_",""));
                    if ( target.is(":visible") ) {
                        target.slideUp("fast");
                    }
                })
            }

        });
    });


    function changeUserLogEventFilterBtn( target )
    {
        if ( target.attr("checked") == "checked" ) {
            target.button( "option", "label",  $("#event_name").text() );
        } else {
            target.button( "option", "label",  "<?php echo $lang->translate("All") ?>");
        }
    }

    function loadCharLog( char_id )
    {
        var eventId = $("#event_id").val();
        var charId = char_id;
        var char_name = $("#" + charId + " .name").text();
        var options = new Array();
        var newDataTarget = $("#player_loot_dialog .content");

        if( $("#char_log_filter_event").attr('checked') == "checked") {
            options["event_id"] = eventId;
        } else {
            options["event_id"] = 0;
        }
        options["suicide"] = $("#char_log_filter_suicide").attr('checked');
        options["moved"] = $("#char_log_filter_moved").attr('checked');
        options["added"] = $("#char_log_filter_added").attr('checked');
        options["removed"] = $("#char_log_filter_removed").attr('checked');
        options["item_count"] = $("#char_log_filter_count").val();

        addPreloader(newDataTarget, "Lade das Spieler Log");
        if(! $("#player_loot_dialog").dialog("isOpen") ) {
            $("#player_loot_dialog").dialog("open");
        }

        $.get("char_log/" + charId + "/", {
            "suicide": options["suicide"],
            "moved":  options["moved"],
            "added": options["added"],
            "removed": options["removed"],
            "event_id": options["event_id"],
            "item_count": options["item_count"]
        } , function ( data ) {
            stopPreLoaderAnimation(data, function (){
                $(".log_event").hover( function () {
                    $(this).addClass("highlight");
                }, function () {
                    $(this).removeClass("highlight");
                });

                $(".log_event").click( function () {
                    $(this).parent().find(".extra_log_data:visible").slideUp("fast");
                    $(this).children(".extra_log_data").stop(true, true).slideToggle("fast");
                });
            });
        });

        $("#player_loot_dialog").dialog("option", "title", "Char: " + char_name);


    }

    function loadLog( )
    {
        var eventId = $("#event_id").val();
        var eventName = $("#event_name").text();
        var options = new Array();
        var newDataTarget = $("#event_loot_dialog .content");

        options["suicide"] = $("#event_log_filter_suicide").attr('checked');
        options["moved"] = $("#event_log_filter_moved").attr('checked');
        options["added"] = $("#event_log_filter_added").attr('checked');
        options["removed"] = $("#event_log_filter_removed").attr('checked');
        options["item_count"] = $("#event_log_filter_count").val();

        addPreloader(newDataTarget, "Lade das Event-Log");
        $.get("<?php echo $web_root ?>loot/event_log/" + eventId + "/",
        {
            "suicide": options["suicide"],
            "moved":  options["moved"],
            "added": options["added"],
            "removed": options["removed"],
            "item_count": options["item_count"]
        } ,
        function ( data ){
            stopPreLoaderAnimation( data , function () {

                $("#event_loot_dialog .log_event").hover( function () {
                    $(this).addClass("highlight");
                }, function () {
                    $(this).removeClass("highlight");
                });

                $("#event_loot_dialog .log_event").click( function () {
                    $(this).parent().find(".extra_log_data:visible").slideUp("fast");
                    $(this).children(".extra_log_data").stop(true, true).slideToggle("fast");
                });
            });
        })
        if(! $("#event_loot_dialog").dialog("isOpen")) {
            $("#event_loot_dialog").dialog("open");
        }
        $("#event_loot_dialog").dialog("option","title","<?php echo $lang->translate("EVENT");?>: " + eventName);
    }

</script>