<script type="text/javascript">
    $(document).ready( function (){

        $(".encounter").dblclick( function () {
            editText( $(this).children(".name").attr("id") );
        });

        $(".encounter img").css("cursor", "pointer");

        $(".dungeon h3").dblclick( function () {
            editText( $(this).attr("id") );
        });

        $("#clear_trash").button({
            icons:{
                primary: "ui-icon-trash"
            },
            text: false
        }).click( function() {
            $.post("<?php echo $web_root ?>progress/clear_trash/",{}, function (data) {
                showResponse(data);
                if ( data == "") {
                    $("#trash .encounter, #trash .dungeon").fadeOut();
                }
            });
        })

        $("#dungeons").sortable({
            cursor: "move",
            start: function ( event, ui ) {
                ui.item.data("startPosition", ui.item.index());
            },
            stop: function ( event, ui ) {
                sortDungeon(ui.item);
            }
        });

        $("#create_dungeon").keyup( function ( event ) {
            if ( event.which == 13 ) {
                //catch enter
                var target = $(this);
                var dungeonName = target.val();

                if( dungeonName !== "" ) {
                    $.post("<?php echo $web_root ?>progress/create_dungeon/", { name: dungeonName }, function ( data ) {
                        showResponse(data);
                        target.val("");
                    });
                }

            }
        })

        $("#create_encounter").keyup( function ( event ) {
            if ( event.which == 13 ) {
                var target = $(this);
                var encounterName = target.val();

                if ( encounterName !== "" ) {
                    $.post("<?php echo $web_root ?>progress/create_encounter/", { name: encounterName } , function ( data ) {
                        if ( data !== "false") {
                            target.val("");
                            var trashObj = $("#trash");
                            var item = $(".encounter:eq(0)").clone(true).appendTo(trashObj);
                            var oldId = item.children(".name").attr("id");
                            if( item.find("img").attr("src").match(/\/clear.png/i) ) {
                                var newImgpath = item.find("img").attr("src").replace(/(http:\/\/[\w\/\d]+)clear\.png/i,"$1" + "not_clear.png");
                            } else {

                            }
                            item.removeClass("enc_down");
                            item.children(".name").html(encounterName);
                            item.children(".name").attr("id",oldId.replace(/(\w+_)\d+/i, "$1" + data) );
                            item.find("img").attr("src", newImgpath);

                        } else {
                            showResponse(data);
                        }
                    });
                }
            }
        });

        $(".dungeon>.options button").button({
            icons:{
                primary: "ui-icon-pencil"
            },
            text: false
        }).click( function () {
            editText($(this).val());
        });

        $(".status").click( function ( event ) {
            toggleEncounterState($(this));
        });

        $(".encounter .options button").button({
            icons:{
                primary: "ui-icon-pencil"
            },
            text: false
        }).click( function () {
            editText($(this).val());
        });

        $("#dungeons").droppable({
            accept: "#trash .dungeon",
            tolerance: "touch",
            activeClass: "ui-state-highlight",
            drop: function( event, ui ) {
                ui.draggable.css("zIndex",80);
                changeDungeonStatus( ui.draggable, $(this), 1 )
            }
        });

        $(".dungeon").droppable({
            accept: ".encounter",
            tolerance: "touch",
            activeClass: "ui-state-highlight",
            drop: function( event, ui ) {
                ui.draggable.css("zIndex",80);
                moveEncounter( ui.draggable, $(this) )
            }
        });

        $(".dropper").sortable({
            cursor: "move",
            start: function ( event, ui ) {
                ui.item.data("startPosition", ui.item.index());
            },
            stop: function ( event, ui ) {
                sortEncounter(ui.item, $(this))
            }
        });

        $("#trash .dungeon").draggable();

        $("#trash").droppable({
            accept: ".dungeon .encounter, .dungeon",
            activeClass: "ui-state-highlight",
            tolerance: "touch",
            drop: function( event, ui ) {

                if ( ui.draggable.attr("class").match(/dungeon/i) ) {
                    changeDungeonStatus(ui.draggable, $(this), 0);
                } else {
                    ui.draggable.css("zIndex", 80);
                    moveEncounter( ui.draggable, $(this) );
                };
            }
        });
});

        function changeDungeonStatus ( item, target, status )
        {
            var dungeonId = item.children("h3").attr("id").replace(/.+_([0-9]+)/i,"$1");
            $.post("<?php echo $web_root; ?>progress/edit_dungeon/",{dungId: dungeonId, dungStatus: status}, function (data) {
                showResponse(data);
                item.fadeOut("fast", function () {
                    item.appendTo(target)
                    .css({top: "0px",left: "0px","zIndex":"70"})
                    .slideDown("fast");
                })
            })
        }
        function sortDungeon ( item )
        {
            var newPosition = item.index();
            var dungeonId = item.children(".headline").attr("id").replace(/.+_([0-9]+)/i,"$1");
            var oldPosition = item.data("startPosition");

            if ( oldPosition != newPosition) {
                $.post("<?php echo $web_root ?>progress/edit_dungeon/", {dungId: dungeonId, newPos: newPosition});
            }

        }

        function sortEncounter ( item, target )
        {
            var newPosition = item.index();
            var oldPosition = item.data("startPosition");
            var encounterId = item.children(".name").attr("id").replace(/.+_([0-9]+)/i,"$1");
            var dungeonId = item.parent().parent().children("h3").attr("id").replace(/.+_([0-9]+)/i,"$1");

            if ( oldPosition != newPosition ) {
                $.post("<?php echo $web_root;?>progress/edit_encounter/",{encId:encounterId, newPos: newPosition, oldPos: oldPosition, dungId: dungeonId}, function (data){
                    showResponse(data);
                });
            }
        }

        function hideOptionsButton ( target )
        {
            target.fadeOut();
        }

        function showOptionsButton ( target )
        {
            target.fadeIn();
        }

        function saveText( target, input )
        {
            if( input.data("default_val") !== input.val() ) {
                // save the new data
                var regEx = /\s/ig;
                target.html( input.val().replace(regEx, "&nbsp;") ) ;

                var itemId = input.attr("id").replace(/([a-z]+)_([0-9]+)_edit/i, "$2");
                var editType = input.attr("id").replace(/([a-z]+)_([0-9]+)_edit/i, "$1");

                var encounterId = "encId";
                var dungeonId = "dungeon";

                switch ( editType ){
                    case encounterId:
                        editEncounterName( itemId, input.val() );
                        break;

                    case dungeonId:
                        editDungeonName(itemId, input.val() );
                        break;
                }
            }
        }

        function hideEdit ( target, input )
        {
            target.css("visibility","visible");
            input.hide()
            showOptionsButton( input.parent().parent().children(".options") );
        }

        function showEdit ( target, input )
        {
            var regEx = /&nbsp;/ig;
            var targetData = target.html().replace(regEx," ").trim();
            input.data("default_val", targetData)
            input.val( targetData );
            target.css("visibility","hidden");
            input.show().focus();
            hideOptionsButton( input.parent().parent().children(".options") );
        }

        function editText( idName )
        {
            var target = $("#" + idName );
            var input = $("#" + idName + "_edit");

            if ( target.length == 1 ) {

                if( input.length == 0) {
                    /**
                     * create Input field
                     **/
                    target.parent().append("<div><input id=\"" + idName + "_edit\">");
                    input = $("#" + idName + "_edit");
                    input.blur( function () {
                        saveText( target, $(this) );
                        hideEdit( target, $(this))
                    })
                    copyCss(target, input);
                }
                showEdit( target, input );
            } else {
            <?php if (! \PRODUCTION_USE): ?>
                alert( idName + " Not found on the Page");
             <?php endif; ?>
            }
        }

        function copyCss( copyFrom, copyTo )
        {
            var position = copyFrom.position();
            var minHeight = 40;
            var minWidth = 70;
            var newHeight, newWidth, newLeftPosition;
            var type = copyFrom.attr("class");

            //        newHeight = copyFrom.css("height");
            newHeight = copyFrom.innerHeight();

            if ( copyFrom.css("width").replace(/px/,"") < minWidth) {
                newWidth = minWidth + "px";
            } else {
                newWidth = copyFrom.css("width");
            }

            newLeftPosition = position.left;

            copyTo.css({
                color: copyFrom.css("color"),
                borderLeftColor: copyFrom.css("border-left-color"),
                borderTopColor: copyFrom.css("border-top-color"),
                borderRightColor: copyFrom.css("border-right-color"),
                borderBottomColor: copyFrom.css("border-bottom-color"),
                borderBottomWidth: copyFrom.css("border-bottom-width"),
                borderLeftWidth: copyFrom.css("border-left-width"),
                borderTopWidth: copyFrom.css("border-top-width"),
                borderRightWidth: copyFrom.css("border-right-width"),
                borderBottomStyle: copyFrom.css("border-bottom-style"),
                borderLeftStyle: copyFrom.css("border-left-style"),
                borderTopStyle: copyFrom.css("border-top-style"),
                borderRightStyle: copyFrom.css("border-right-style"),
                fontSize: copyFrom.css("font-size"),
                lineHeight: copyFrom.css("line-height"),
                fontWeight: copyFrom.css("font-weight"),
                fontFamily: copyFrom.css("font-family"),
                fontStyle: copyFrom.css("font-style"),
                marginTop: copyFrom.css("margin-top"),
                letterSpacing: copyFrom.css("letter-spacing"),
                lineHeight: copyFrom.css("line-height"),
                marginLeft: copyFrom.css("margin-left"),
                marginRight: copyFrom.css("margin-right"),
                marginBottom:copyFrom.css("margin-Bottom"),
                paddingLeft: copyFrom.css("padding-left"),
                overflowY: "hidden",
                textAlign: copyFrom.css("text-align"),
                width: newWidth,
                height: newHeight,
                position: "absolute",
                backgroundColor: copyFrom.css("background-color"),
                top: position.top,
                left: newLeftPosition,
                zIndex: "99"
            });
        }

        function editEncounterName ( id, name )
        {
            $.post("<?php echo $web_root ?>progress/edit_encounter/", { encId: id, encName: name });
        }

        function editDungeonName ( id, name )
        {
            $.post("<?php echo $web_root ?>progress/edit_dungeon/", { dungId: id, dungName: name });
        }

        function toggleEncounterState( target )
        {
            var clearedImg = "clear";
            var unClearedImg = "not_clear";
            var image = target.children("img");
            var status = image.attr("src").replace(/(.+)\/([a-zA-Z_]+).png$/,"$2");
            var imgPath = image.attr("src").replace(/(.+)\/([a-zA-Z_]+).png$/,"$1") + "/";
            var encounterId = target.parent().children(".name").attr("id").replace(/encId_/,"");

            if ( clearedImg == status ) {
                image.attr("src",imgPath + unClearedImg + ".png");
                status = 0;
                target.parent().removeClass("enc_down", 500);
            } else {
                image.attr("src",imgPath + clearedImg + ".png");
                status = 1;
                target.parent().addClass("enc_down", 500);
            }

            $.post("<?php echo $web_root; ?>progress/edit_encounter/",{encId: encounterId, cleared: status}, function (data) {
            } );
        }


        function moveEncounter(item, target)
        {
            var dungeonId = target.children("h3").attr("id").replace(/dungeon_/,"");
            var encounterId = item.children(".name").attr("id").replace(/encId_/, "");

            var from = item.parent().parent().children("h3").attr("id").replace(/dungeon_/,"");

            if( target.attr("class").match(/dungeon/) ) {
                target = target.children(".dropper");
            }

            if( from != dungeonId) {
                //move encounter from one dungeon to another
                $.post("<?php echo $web_root; ?>progress/add_encounter/", { encId: encounterId, dungId: dungeonId },
                    function ( data ) {
                        item.fadeOut("fast", function () {
                            item.appendTo(target)
                            .css({top: "0px",left: "0px","zIndex":"70"})
                            .slideDown("fast");
                        });
                    }
                );
            }
        }
</script>