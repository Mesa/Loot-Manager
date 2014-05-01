<script type="text/javascript">
    $(document).ready( function () {

        $("#create_new_group").button({
            icons: {
                primary: "ui-icon-plusthick"
            }
        }).click( function () {
            var item = $("#create_new_group_dialog");
            positionDialog($(this), item);
        }) ;

<?php if($rights->hasRight("admin_edit_user")): ?>
        $(".admin_edit_user").button({
            icons: {
                primary: "ui-icon-pencil"
            },
            text: false
        }).click( function () {
            var item = $("#admin_edit_user");
            var user_id = $(this).val()
            var user_name = $("#user_id_"+ user_id +" .secret_name").val();
            var password = "password";
            var name = $("#user_id_"+ user_id +" .user_name").text().trim();
            var email = $("#user_id_"+ user_id +" .email").text().trim();

            $("#edit_user_display_name").val(name);
            $("#edit_user_username").val(user_name);
            $("#edit_user_email").val(email);
            $("#edit_user_id").val(user_id);

            positionDialog( $(this), item );
        });

        $("#submit_btn_user_edit").button({
            icons: {
                primary: "ui-icon-pencil"
            }
        }).click( function () {
            var user_name = $("#edit_user_username").val();
            var password = $("#edit_user_password").val();
            var name = $("#edit_user_display_name").val();
            var e_mail = $("#edit_user_email").val();
            var userid = $("#edit_user_id").val();
            var min_password_length = <?php echo $this->registry->get("MIN_PASSWORD_LENGTH") ?>;

            if ( password.length < min_password_length) {
                password = false;
            }

            $.post("<?php echo $web_root ?>edit_user/",
            {
                username: user_name,
                new_password: password,
                shown_name: name,
                email: e_mail,
                user_id: userid
            },
            function (data) {
                if ( data ) {
                    showMessage(data);
                } else {
                    $("#admin_edit_user").dialog("close");
                    window.location.reload();
                }
            });
        });
<?php endif; ?>

<?php if($rights->hasRight("admin_edit_user") || $rights->hasRight("admin_create_user")): ?>
        $("#edit_user_password, #create_user_dialog_password").keyup( function () {
            var content = $(this).val();
            var password_min_length = <?php echo $this->registry->get("MIN_PASSWORD_LENGTH") ?>;
            var html_id = $(this).attr("Id");
            var target = html_id.replace(/(_user_password|_user_dialog_password)/,"");

            var info_item = $(this).next();

            if ( content.length < password_min_length ) {
                info_item.html(
                "<?php echo $lang->translate("PASSWORD_TO_SHORT"); ?>.\n\
        <br>Min. <?php echo $this->registry->get("MIN_PASSWORD_LENGTH") . " " .
 $lang->translate("LETTERS") ?>");

                     info_item.css("color","red");
                 } else {
                     info_item.html("");
                 }

                 if ( content.length == 0 ) {
                     if ( target == "edit" ) {
                         info_item.html("<?php echo $lang->translate("NO_PASSWORD_CHANGE");
$this->registry->get("MIN_PASSWORD_LENGTH") . " " . $lang->translate("LETTERS") ?>");
                    info_item.css("color","green");
                }
            }
        });
<?php endif; ?>
<?php if($rights->hasRight("admin_edit_user")): ?>
        $("#admin_edit_user").dialog({
            autoOpen: false
        });
<?php endif; ?>

<?php if($rights->hasRight("admin_create_group")): ?>
        $("#submit_btn_group_create").button({
            icons: {
                primary: "ui-icon-disk"
            }
        }).click( function () {
            var group_name = $("#create_group_dialog_name").val();
            var dialogWindow = $("#create_new_group_dialog");

            if( group_name.length > 0) {
                $.post("<?php echo $web_root ?>create_group/",
                {name: group_name}, function (data) {
                    if( data.length > 0 ) {
                        showMessage(data);
                    } else {
                        window.location.reload()
                    }
                }
            );
            }

        });
<?php endif; ?>
        $("#create_new_group_dialog").dialog({
            autoOpen: false,
            title: "<?php echo $lang->translate("CREATE_GROUP"); ?>"
        });

        $(".group_member_btn").button({
            icons: {
                primary: "ui-icon-person"
            },
            text: false
        }).click( function () {
            var group_id = $(this).parent().parent().attr("ID").replace("group_id_", "");
            var group_name = $("#group_id_" + group_id + " .name").text().trim();

            $("#group_management_group_id").val(group_id);
            var active_member = $("#active_member");
            var all_member = $("#all_member");

            $("#active_member h3").html(group_name);
            /**
             * delete all groups in the menu
             */
            $(".user_dropper").remove();

            $.post("<?php echo $web_root ?>get_group_user/",{groupid : group_id},
            function (data) {
                var daten = $.parseJSON(data);
                $.each(daten, function(index, value) {

                    if ( typeof value == "object") {

                        $.each(value, function (key, group) {
                            if ( index == "active_member") {
                                addUserToList(group["Name"], group["id"], active_member)
                            } else {
                                addUserToList(group["Name"], group["id"], all_member)
                            }
                        })
                    }
                });

                $(".user_dropper").draggable({
                    revert: "invalid"
                });
            })

            var dialogObj = $("#group_member_manager");
            dialogObj.dialog("option",{
                title: group_name,
                width: 400
            })
            positionDialog($(this), dialogObj);
        });

        $(".group_rights_btn").button({
            icons: {
                primary: "ui-icon-key"
            },
            text: false
        }).click(
        function (e) {
            getRightsFrame( $(this) );
        }
    );
        $(".group_delete_btn").button({
            icons: {
                primary: "ui-icon-circle-close"
            },
            text: false
        }).click( function () {
            var item = $(this);
            var group_id = item.parent().parent().attr("ID").replace("group_id_", "");
            var group_name = $("#group_id_" + group_id + " .name").text().trim();
            $("#commit_delete_group_id").val(group_id);
            $("#commit_delete_group").dialog("option",{
                title: "<?php echo $lang->translate("DELETE_GROUP"); ?>? [ <b style='font-size: 1.1em;'>" + group_name + "</b> ]"
            });
            $("#commit_delete_group").dialog("open");
        });

        $(".user_rights_btn").button({
            icons: {
                primary: "ui-icon-key"
            },
            text: false
        }).click(
        function (e) {
            getRightsFrame($(this));
        }
    );
        $(".user_delete_btn").button({
            icons: {
                primary: "ui-icon-circle-close"
            },
            text: false
        }).click( function () {
            var item = $(this);
            var user_id = item.parent().parent().attr("ID").replace("user_id_", "");
            var user_name = $("#user_id_" + user_id + " .user_name").text().trim();
            $("#commit_delete_user_id").val(user_id);
            $("#commit_delete_user").dialog("option",{
                title: "<?php echo $lang->translate("DELETE_USER"); ?>? [ <b style='font-size: 1.1em;'>" + user_name + "</b> ]"
            });
            $("#commit_delete_user").dialog("open");
        });

        $(".user_groups_btn").button({
            icons: {
                primary: "ui-icon-person"
            },
            text: false
        }).click( function () {
            var user_id = $(this).parent().parent().attr("ID").replace("user_id_", "");
            var user_name = $("#user_id_" + user_id + " .user_name").text().trim();
            $("#group_management_user_id").val(user_id);
            var active_groups = $("#active_groups");
            var deactive_groups = $("#deactive_groups");

            $("#active_groups h3").html(user_name);
            /**
             * delete all groups in the menu
             */
            $(".dropper").remove();

            $.post("<?php echo $web_root ?>get_user_groups/",{userid : user_id},
            function (data) {
                var daten = $.parseJSON(data);
                $.each(daten, function(index, value) {

                    if ( typeof value == "object") {

                        $.each(value, function (key, group) {
                            if ( index == "active_groups") {
                                addGroupToList(group["Name"], group["Id"], active_groups)
                            } else {
                                addGroupToList(group["Name"], group["Id"], deactive_groups)
                            }
                        })
                    }
                });

                $(".dropper").draggable({
                    revert: "invalid"
                });
            })

            var dialogObj = $("#user_group_manager");
            dialogObj.dialog("option",{
                title: user_name,
                width: 400
            })
            positionDialog($(this), dialogObj);
        });

        $("#group_filter").keyup( function () {
            var group_search = $(this).val().toLowerCase();

            if ( group_search.length > 0 ) {
                $(".group .name").each( function () {
                    var group_name = $(this).text().toLowerCase();
                    var result = group_name.match(group_search);
                    if ( result ) {
                        $(this).parent().slideDown("slow");
                    } else {
                        $(this).parent().slideUp("slow");
                    }
                });
            } else {
                $(".group").slideDown();
            }
        });

        $("#user_filter").keyup( function () {
            var user_search = $(this).val().toLowerCase();

            if ( user_search.length > 0) {

                $(".user .user_name").each( function () {
                    var user_name = $(this).text().toLowerCase();
                    var result = user_name.match(user_search);
                    if ( result ) {
                        $(this).parent().slideDown("slow");
                    } else {
                        $(this).parent().slideUp("slow");
                    }
                })
            } else {
                $(".user").slideDown("fast");
            }
        }
    );

        $("#submit_btn_user_create").button( {
            icons: {
                primary: "ui-icon-disk"
            }
        }).click( function () {
            var username = $("#create_user_dialog_username");
            var password = $("#create_user_dialog_password");
            var display_name = $("#create_user_dialog_display_name");
            var email = $("#create_user_dialog_email");
            var min_password_length = <?php echo $this->registry->get("MIN_PASSWORD_LENGTH") ?>;

            if ( password.val().length >= min_password_length ) {

                $("#create_new_user_dialog").dialog("close");
                $.post("<?php echo $web_root ?>create_user/",
                { user_name: username.val(),
                    password: password.val(),
                    displayname: display_name.val(),
                    e_mail: email.val()
                }, function (data) {
                    if( data ) {
                        showMessage(data);
                    } else {
                        window.location.reload()

                    }
                });
            }
        });

        $("#create_new_user").button({
            icons: {
                primary: "ui-icon-plusthick"
            }
        }).click ( function () {
            positionDialog($(this), $("#create_new_user_dialog"));
        });

        $("#create_new_user_dialog").dialog({
            autoOpen: false,
            title: "<?php echo $lang->translate("CREATE_USER"); ?>"
        });

        $("#commit_delete_group").dialog({
            resizable: false,
            height:140,
            autoOpen: false,
            buttons: {
                "<?php echo $lang->translate("DELETE_GROUP"); ?>": function () {
                    var group_id = $("#commit_delete_group_id").val();

                    $.post("<?php echo $web_root; ?>delete_group/", { groupid : group_id } );
                    $(this).dialog("close");
                    $("#group_id_" + group_id).slideUp("slow");
                },
                "<?php echo $lang->translate("CANCEL"); ?>": function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#commit_delete_user").dialog({
            resizable: false,
            height:140,
            autoOpen: false,
            buttons: {
                "<?php echo $lang->translate("DELETE_USER"); ?>": function () {
                    var user_id = $("#commit_delete_user_id").val();

                    $.post("<?php echo $web_root; ?>delete_user/", { userid : user_id } , function(data){
                        if(data) {
                            showMessage(data);
                        } else {
                            $("#user_id_" + user_id).slideUp("slow");
                        }
                    });
                    $(this).dialog("close");
                },
                "<?php echo $lang->translate("CANCEL"); ?>": function () {
                    $(this).dialog("close");
                }
            }
        });


        $(".group, .user").hover( function () {
            $(this).addClass("highlight");
        }, function () {
            $(this).removeClass("highlight");
        });

        $("#user_group_manager").dialog({
            autoOpen: false
        });

        $(".dropper").draggable({
            revert: "invalid"
        });

        $("#active_groups").droppable({
            accept: "#deactive_groups>.dropper",
            activeClass: 'highlight',
            drop: function( event, ui ) {
                moveGroup( $(this), ui.draggable );
            }
        });

        $("#group_member_manager").dialog({
            autoOpen: false
        });

        $("#deactive_groups").droppable({
            accept: "#active_groups>.dropper",
            activeClass: 'highlight',
            drop: function( event, ui ) {
                moveGroup( $(this), ui.draggable );
            }
        });

        $("#active_member").droppable({
            accept: "#all_member>.user_dropper",
            activeClass: 'highlight',
            drop: function( event, ui ) {
                moveUser( $(this), ui.draggable );
            }
        });

        $("#all_member").droppable({
            accept: "#active_member>.user_dropper",
            activeClass: 'highlight',
            drop: function( event, ui ) {
                moveUser( $(this), ui.draggable );
            }
        });

        function moveUser ( target, item ) {
            var user_id = item.attr("id").replace("user_id_","");
            var group_id = $("#group_management_group_id").val();
            var target_id = target.attr("id");

            if ( target_id == "all_member") {
                var post_path = "<?php echo $web_root ?>remove_user_from_group/";
            }

            if ( target_id == "active_member") {
                var post_path = "<?php echo $web_root ?>add_user_to_group/";
            }

            if( post_path ) {
                $.post( post_path, {groupid: group_id, userid: user_id}, function (data){
                    if ( data ) {
                        showMessage(data);
                    }
                });
            }

            item.fadeOut( function () {
                item.appendTo( target )
                .css( {top: "0px", left: "0px"} )
                .fadeIn("slow");
            })
        }

        function addUserToList( name, id, list ) {

            var newElement = $("<div></div>")
            .attr("class","block border_dark user_dropper ui-draggable")
            .attr("id","user_id_"+id)
            .text(name);
            list.append(newElement);
        }

        function moveGroup ( target, item ) {
            var group_id = item.attr("id").replace("group_id_","");
            var user_id = $("#group_management_user_id").val();
            var target_id = target.attr("id");

            if( target_id == "active_groups") {
                var post_path = "<?php echo $web_root; ?>add_user_to_group/";
            }

            if ( target_id == "deactive_groups") {
                var post_path = "<?php echo $web_root; ?>remove_user_from_group/";
            }

            if( post_path ) {
                $.post( post_path, {groupid: group_id, userid: user_id}, function (data){
                    showMessage(data);
                });
            }

            item.fadeOut( function () {
                item.appendTo( target )
                .css( {top: "0px", left: "0px"} )
                .fadeIn("slow");
            })
        }

        function positionDialog ( button, dialog ) {
            var padding = 10;
            var button_offset = button.offset();
            dialog.dialog("open");

            var dialog_width = dialog.width();
            var dialog_height = dialog.height();

            dialog.dialog("option",{
                position: [button_offset.left - dialog_width - padding, button_offset.top - ( dialog_height / 2)]
            });
        }

        function addGroupToList( name, id, list ) {

            var newElement = $("<div></div>")
            .attr("class","block border_dark dropper ui-draggable")
            .attr("id","group_id_"+id)
            .text(name);
            list.append(newElement);
        }
    });

function getRightsFrame( button ) {

    var id_nr, right_frame, right_frame_id, get_option;

    if( button.hasClass("group_rights_btn")) {
        id_nr = button.parent().parent().attr("ID").replace("group_id_", "");
        right_frame_id = "group_rights_" + id_nr;
        get_option = "g";
    }

    if( button.hasClass("user_rights_btn")) {
        id_nr = button.parent().parent().attr("ID").replace("user_id_", "");
        right_frame_id = "user_rights_" + id_nr;
        get_option = "u";
    }

    right_frame = $("#" + right_frame_id);

    if ( right_frame.length > 0 ) {
        right_frame.remove();
    }

    $.get('<?php echo $web_root ?>rights/show_rights_menu/' + get_option + '/' + id_nr + '/', function (data){
        $("#frame_pool").append(data);

        right_frame = $("#" + right_frame_id);
        right_frame.dialog({
            width:300,
            title: "<?php echo $lang->translate("RIGHTS") ?>"
        });

        right_frame.children(".rights").accordion({
            autoHeight: false,
            navigation: true
        });

        $("#" + right_frame_id + " input").each( function (){
            var status = $(this).attr('checked');
            if ( status ) {
                $(this).button({text:false, icons:{primary:"ui-icon-circle-check"}})
            } else {
                $(this).button({text:false, icons:{primary:"ui-icon-circle-close"}})
            }
        });

        $("#" + right_frame_id + " .right_select").change( function () {
            var checkBox = $(this);
            var status = checkBox.attr('checked');
            var right_name = $(this).val();

            if ( status ) {
                checkBox.attr("disabled","disabled");
                    $.get("<?php echo $web_root ?>rights/addright/" + get_option + "/" + id_nr +"/" + right_name + "/", function (data) {
                        checkBox.removeAttr("disabled");
                        checkBox.button({icons:{primary:"ui-icon-check"}});
                        if ( data ) {
                            showMessage(data);
                        }
                    });
            } else {
                checkBox.attr("disabled","disabled");
                    $.get("<?php echo $web_root ?>rights/removeright/" + get_option + "/" + id_nr +"/" + right_name + "/", function (data) {
                        checkBox.removeAttr("disabled");
                        checkBox.button({icons:{primary:"ui-icon-close"}});
                        if ( data ){
                            showMessage(data);
                        }
                    });
            }
        });

        $("#" + right_frame_id + " .right").hover( function (){
            $(this).addClass("highlight");
        },function() {
            $(this).removeClass("highlight");
        })

    });
}
</script>