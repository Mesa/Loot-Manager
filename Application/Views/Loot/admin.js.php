<script type="text/javascript">
    $(document).ready( function (){

        var menu_id = "player_menu";
        var hidden_player_id = "hidden_player";
        var create_player_id = "create_player";

        var frame_pool = $("#frame_pool");
        var menu_dialog = document.createElement("div");
        var hidden_player = document.createElement("div");
        var create_player = document.createElement("div");

        menu_dialog.id = menu_id;
        frame_pool.append(menu_dialog);

        hidden_player.id = hidden_player_id;
        frame_pool.append(hidden_player);

        create_player.id = create_player_id;
        frame_pool.append(create_player);

        $("#"+create_player_id).dialog({
            autoOpen: false
        });

        $(".total_list").hover(
        function () {
            $(this).css('cursor','pointer');
            $(this).addClass("highlight");
        },
        function () {
            $(this).css('cursor','pointer');
            $(this).removeClass("highlight");
        });

        $.get("<?php echo $web_root . $loot_path ?>menu/create_player/",
        function (data) {
            $("#"+create_player_id).html(data);
        });

        var event_id = $("#event_id").val();
        $.ajax({
            url: "<?php echo $web_root . $loot_path ?>menu/"+event_id+"/",
            context: document.body,
            success: function( data ){
                $("#"+menu_id).html(data);

                $("#" + menu_id + " .block .headline").click( function (){
                    $("#" + menu_id + " .block .menu_data:visible").slideUp("slow");
                    $(this).parent().children(".menu_data").slideToggle("slow");
                });

                var item_list = <?php echo json_encode($item_list)?>;
                $("#suicide_desc").autocomplete({
                    source: item_list
                });
                var moved_desc_list = <?php echo json_encode($moved_desc) ?>;
                $("#move_player_desc").autocomplete({
                    source: moved_desc_list
                })

                $("#kill_suicide_king").button({
                    icons: {
                        primary: "ui-icon-circle-arrow-s"
                    }
                }).click( function ( e ) {
                    menu_dialog.dialog("close");
                    var player_id = $("#dialog_player_id").val();
                    var event_id = $("#event_id").val();
                    var action_desc = $("#suicide_desc").val();
                    var oldPosition = $(".total_list").index( $("#" + player_id) ) + 1;
                    var newPosition = $(".total_list").size();

                    $.post("<?php echo $web_root . $loot_path ?>kill/", {
                        "event_id": event_id,
                        "player_id": player_id,
                        description: action_desc,
                        fromPosition: oldPosition,
                        "new_position": newPosition
                    }, function ( data ) {
                        $("#"+ player_id)
                        .hide()
                        .insertAfter($(".total_list:last"))
                        .slideDown("slow");
                    });
                });

                $("#delete_player").button({
                    icons: {
                        primary: "ui-icon-circle-close"
                    }
                }).click( function () {
                    var player_id = $("#dialog_player_id").val();
                    $("#"+player_id).remove();
                    $.get("<?php echo $web_root . $loot_path ?>delete_player/"+ player_id +"/", function () {
                        menu_dialog.dialog("close");
                    });
                });

                $("#remove_player").button({
                    icons: {
                        text: false,
                        primary: "ui-icon-arrowreturn-1-e"
                    }
                }).click( function ( e ) {

                    var row_id = $("#dialog_row_id").val();
                    var player_id = $("#dialog_player_id").val();
                    $.get("<?php echo $web_root . $loot_path ?>drop_player/"+ row_id +"/", function () {
                        $("#"+player_id).remove();
                        menu_dialog.dialog("close");
                    })
                });

                $(".delete_missing_player").button({
                    icons: {
                        primary: "ui-icon-circle-close"
                    },
                    text: false
                }).click ( function ( ) {
                    $.get("<?php echo $web_root . $loot_path ?>delete_player/"+ $(this).val() +"/", function () {
                        menu_dialog.dialog("close");
                        window.location.reload();
                    });
                }).hover(  function () {
                    $(this).parent().parent().toggleClass("highlight");
                });

                $(".add_missing_player").button({
                    icons: {
                        primary: "ui-icon-arrowreturn-1-w"
                    },
                    text: false
                }).click ( function ( ) {
                    $.get("<?php echo $web_root . $loot_path ?>add_char_to_event/"+$(this).val()+"/"+event_id+"/", function () {
                        menu_dialog.dialog("close");
                        window.location.reload();
                    });
                }).hover( function () {
                    $(this).parent().parent().toggleClass("highlight");
                });

                $("#change_position").button({
                    icons: {
                        primary: "ui-icon-transferthick-e-w"
                    }
                }).click( function ( ) {
                    menu_dialog.dialog("close");
                    var player_id = $("#dialog_player_id").val();
                    var event_id = $("#event_id").val();
                    var new_position = $("#edit_player_position").val();
                    var count = $("#event_player_list > .total_list").size();
                    var from_position = $("#" + player_id).index() + 1;
                    var description = $("#move_player_desc").val();

                    $.post("<?php echo $web_root . $loot_path ?>move/",{
                        "event_id" : event_id,
                        "player_id": player_id,
                        "description": description,
                        "from_position": from_position,
                        "new_position": new_position
                    }, function () {
                        if( new_position < count) {
                            if( new_position > from_position) {
                                $(".total_list:nth-child("+ new_position +")").after($("#"+ player_id).slideDown("fast"));
                            } else {
                                $(".total_list:nth-child("+ new_position +")").before($("#"+ player_id).slideDown("fast"));
                            }
                        } else {
                            $("#"+player_id).hide();
                            $(".total_list:nth-child("+ count +")").after($("#"+ player_id).slideDown("fast"));
                        }
                        $("#move_player_desc").val("");
                    });
                });
<?php if ( $rights->hasRight("loot_edit_char") ): ?>
            var char_list = <?php echo json_encode($class_list)?>;
                $("#edit_char_class").autocomplete({
                    source: char_list
                });

                $("#edit_player").button({
                    icons: {
                        primary: "ui-icon-pencil"
                    }
                }).click( function () {
                    var char_id = $("#dialog_player_id").val();
                    var char_name = $("#edit_char_name").val();
                    var char_class = $("#edit_char_class").val();
                    var char_description = $("#edit_char_description").val();
                    $.post("<?php echo $web_root . $loot_path ?>edit_player/",{
                        "char_id":char_id,
                        "char_name": char_name,
                        "char_class": char_class,
                        "char_description":char_description
                    }, function () {
                        menu_dialog.dialog("close");
                        window.location.reload();
                    });

                });
<?php endif; ?>
                $("#create_player_btn").button({
                    icons: {
                        primary: "ui-icon-circle-plus"
                    }
                }).click( function () {
                    menu_dialog.dialog("close");
                    positionDialog($(this), $("#"+create_player_id));
                });
            }
        });

        $("#"+menu_id).dialog({
            autoOpen: false
        });

<?php if ( $rights->hasRight("loot_edit_event") ): ?>
            $(".edit_event_btn").button({
                icons:{
                    primary: "ui-icon-pencil"
                },
                text: false
            }).click( function () {
                var target = $("#event_name");
                var event_name = target.text();
                var input = $("#event_name_edit");

                target.hide();
                input.show();
                input.css({
                    "border-top-width":"0px",
                    "border-bottom-width":"0px",
                    "border-left-width":"0px",
                    "border-right-width":"0px"
                });
                copyCssProperty(target, input, "font-family");
                copyCssProperty(target, input, "text-align");
                copyCssProperty(target, input, "color");
                copyCssProperty(target, input, "font-size");
                copyCssProperty(target, input, "font-weight");
                copyCssProperty(target, input, "background-color");
                copyCssProperty(target, input, "font-height");
                input.focus();
            });
            $("#edit_event_dialog").dialog({
                autoOpen: false
            });

            $("#event_name_edit").blur( function () {
                var target = $("#event_name");
                var eventName = target.text();
                var input = $("#event_name_edit");
                var new_event_name = input.val();
                var eventId = $("#event_id").val();

                if ( new_event_name != eventName && new_event_name != "") {
                    /* Neuen eventnamen speichern */

                    $.post("<?php echo $web_root . $loot_path ?>edit_event/", {event_name: new_event_name, event_id: eventId}, function () {
                        target.text(new_event_name);
                        input.hide();
                        target.show();
                    });
                } else {
                    /* Eventname wurde nicht geändert, input verstecken */
                    input.hide();
                    target.show();
                }
            });
<?php endif; ?>
<?php if ( $rights->hasRight("loot_delete_event") ): ?>
            $(".delete_event_btn").button({
                icons:{
                    primary: "ui-icon-circle-close"
                },
                text: false
            }).click( function () {
                positionDialog($(this).parent(),$("#delete_event_dialog") );

            });
            $("#delete_event_dialog").dialog({
                autoOpen: false,
                title: "<?php echo $lang->translate("DELETE_EVENT"); ?> ?",
                buttons: {
                    "<?php echo $lang->translate("DELETE_EVENT"); ?>": function () {
                        var eventId = $("#event_id").val();
                        $.post("<?php echo "" ?>delete_event/",{event_id: eventId}, function (){
                            window.location.reload();
                        });
                    },
                    "<?php echo $lang->translate("CANCEL"); ?>": function () {
                        $(this).dialog("close");
                    }
                }
            });
<?php endif; ?>
<?php if ( $rights->hasRight("loot_create_event") ): ?>

            $("#create_event_input").keyup( function ( event ) {

                if ( event.which == 13 ) {
                    // Catch Enter
                    var item = $(this);
                    var eventName = item.val();

                    if( eventName != "") {

                        var data = {
                            event_name: eventName
                        }
                        $.post("<?php echo $web_root . $loot_path ?>create_event/", data, function ( data ){
                            window.location.reload();
                        });
                    }
                }
            })

<?php endif; ?>
        $(".replace_value").focus( function () {
            var item = $(this);
            var backup = item.attr("title");
            var value = item.val();

            if ( backup == "" || value == backup) {
                item.css("color","steelblue");
                item.attr("title",value);
                item.val("");
            }
        }).blur( function () {
            var item = $(this);
            var backup = item.attr("title");
            var value = item.val();

            if ( value == "" ) {
                item.css("color","grey");
                item.val(backup);
            }


        });

        menu_dialog = $("#"+menu_id);
        $(".total_list").click( function (e) {
            var target = $(this);
            var target_id = target.attr("ID");
            var position_x = e.pageX;
            var position_y = e.pageY;
            var target_width = target.width();
            var char_name = $("#"+target_id + " .name").text();
            var char_class = $("#"+target_id + " .class").text();
            var char_position = $("#"+target_id + " .position").text()
            var row_id = $("#"+target_id + " input").val();
            var char_description = $("#"+target_id + " .type").html();


            if ( char_position == "" ) {
                positionDialog($(this), $("#"+create_player_id));
            } else {

                menu_dialog.dialog("open");
                menu_dialog.dialog("option",{
                    title: char_name,
                    width: '300',
                    position:[position_x ,position_y-(menu_dialog.height()/2)]
                });

                $("#edit_char_name").val(char_name);
                $("#suicide_desc").val("");
                $("#edit_char_class").val(char_class);
                $("#edit_char_description").val(char_description);
                $("#edit_player_position").val(char_position);
                $("#dialog_player_id").val(target_id);
                $("#dialog_row_id").val(row_id);
                $("#head_remove_char").text( char_name +" <?php echo strtolower($lang->translate("REMOVE")); ?>?");
                $("#head_delete_char").html( char_name +" <?php echo strtolower($lang->translate("DELETE")); ?>?");
            }

        });

    });

    function copyCssProperty( from, to, property) {
        var value = from.css(property);
        to.css(property,value);
    }
</script>