<script type="text/javascript" src="<?php echo $web_root; ?>tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
    /**
     * This editor class handles all editable content on the page with the class
     * passed by the constructor.
     *
     * @author: Mesa (Daniel Langemann)
     */
    function editor (editorClassName, newsType, contentCss)  {
        var self = this;
        var className = editorClassName;
        var headlineClass = "headline";
        var contentClass = "content";
        var authorClass = "author";
        var dateClass = "date";
        var displayFromClass = "display_from";
        var displayToClass = "display_to";
        var editorObjs = null;
        var buttonEditClass = "editor_edit_btn";
        var buttonCancelClass = "editor_cancel_btn";
        var buttonDeleteClass = "editor_delete_btn";
        var buttonSaveClass = "editor_save_btn";
        var buttonCreateClass = "editor_create_btn";
        var actionClass = "action";
        var idClass = "id";
        var editorBorderColor = "orange";
        var editorBackgroundColor = "#F3F3F3";
        var textAreaPadding = 20;
        /* append this string to the path post path  */
        var deletePath = "delete/";
        var editPath = "edit/";
        var createPath = "create/";
        var editorCount = 0;

        /**
         * Constructor
         */
        editorObjs = $("."+editorClassName);
        prepare();
        /*  Constructor end */

        function prepare()
        {
            editorObjs.each( function ( ) {
                addButtons($(this));
            });

            /* thanks to http://www.blogrammierer.de/jquery-ui-datepicker-in-deutscher-sprache/  */
            $.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
                closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
                prevText: 'zurück', prevStatus: 'letzten Monat zeigen',
                nextText: 'Vor', nextStatus: 'nächsten Monat zeigen',
                currentText: 'heute', currentStatus: '',
                monthNames: ['Januar','Februar','März','April','Mai','Juni',
                    'Juli','August','September','Oktober','November','Dezember'],
                monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
                    'Jul','Aug','Sep','Okt','Nov','Dez'],
                monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
                weekHeader: 'Wo', weekStatus: 'Woche des Monats',
                dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
                dateFormat: 'dd.mm.yy', firstDay: 1,
                initStatus: 'Wähle ein Datum', isRTL: false};
            $.datepicker.setDefaults($.datepicker.regional['de']);
            /*  thanks for your work  */

            addEventHandler();
            $("." + buttonCreateClass).button({
                icons:{
                    primary: "ui-icon-circle-plus"
                },
                text: false
            }).click( function () {
                createNews( $(this) );
            });

            $("."+ className +" ." + contentClass).css({
                paddingBottom: textAreaPadding
            })
            /*  Some Style Information */
            $("."+buttonEditClass).button({
                icons:{
                    primary: "ui-icon-pencil"
                },
                text:false
            }).click( function () {
                activateEditor( $(this) );
            });

            $("."+buttonCancelClass).button({
            }).click( function () {
                var content = $(this).parent().parent().children(".content");
                content.html( content.data("html"));
                disableEditor($(this));
            });
            $("." + buttonSaveClass).button().click( function () {
                saveNews( $(this) );
            });

            $("."+buttonDeleteClass).button({
                icons:{
                    primary: "ui-icon-circle-close"
                },
                text:false
            }).click ( function () {
                deleteNews( $(this) );
            });

            $("." + buttonEditClass + ", ." + buttonDeleteClass +", ." + buttonCancelClass + ", ." + buttonCreateClass ).css({
                marginRight: "12px"
            });

            /* make sure the rights css vars are set */
            $("."+ editorClassName).css({
                "overflow": "visible",
                position: "relative"
            });

            var cssTarget = $("." + editorClassName + "");
            $("."+ editorClassName +" .options").css({
                position: "absolute",
                top: "-1px",
                right: "-1px",
                borderBottomWidth: cssTarget.css("border-bottom-width"),
                borderLeftWidth: cssTarget.css("border-left-width"),
                borderRightWidth: 0,
                borderTopWidth: 0,
                borderBottomStyle: cssTarget.css("border-bottom-style"),
                borderLeftStyle: cssTarget.css("border-left-style"),
                borderBottomColor: cssTarget.css("border-bottom-color"),
                borderLeftColor: cssTarget.css("border-left-color"),
                backgroundColor : cssTarget.css("background-color"),
                paddingTop: "8px",
                paddingLeft: "8px",
                paddingBottom: "8px",
                zIndex: "99"
            });
        }

        function createNews ( obj )
        {
            /**
             * http://api.jquery.com/first-selector/
             *
             *  To achieve the best performance when using :first to select
             * elements, first select the elements using a pure CSS selector,
             * then use .filter(":first").
             */
            var target = $("." + className).filter(":first");
            //            var newsContainer = target.parent();
            target.before( target.clone(true, true) );
            var newBlock = $("." + className).filter(":first");
            var displayFromObj = newBlock.children("." + displayFromClass);
            var displayToObj = newBlock.children("." + displayToClass);
            var contentObj = newBlock.children("." + contentClass);
            var headlineObj = newBlock.children("." + headlineClass);
            var IdObj = newBlock.children("." + idClass);
            var actionObj = newBlock.children("." + actionClass);

            //            var newActionPath = actionObj.val().replace(/([a-zA-Z0-9]+)\/$/, createPath);
            var newActionPath = actionObj.val();

            IdObj.val(0);
            actionObj.val( newActionPath );
            headlineObj.html("&nbsp;");
            contentObj.html("<br>");
            displayToObj.html("&nbsp;");
            displayFromObj.html("&nbsp;");
            /* reset all data */

        }

        function deleteNews( obj )
        {
            var target = obj.parent().parent();
            var idVal = target.children("." + idClass).val();
            var actionVal = target.children("." + actionClass).val() + deletePath;
            if( idVal > 0 ) {
                $.post(actionVal, {id: idVal}, function () {
                    target.detach();
                })
            } else {
                target.detach();
            }
        }

        function saveNews ( obj )
        {
            var target = obj.parent().parent();
            var actionVal = target.children("." + actionClass).val();
            var idVal = target.children("." + idClass).val();
            var headlineVal = target.children("." + headlineClass +"_edit").val();
            var contentVal = replaceLineFeed( target.children("." + contentClass + "_edit").val() );
            var displayFromVal = target.children("." + displayFromClass + "_edit").val();
            var displayToVal = target.children("." + displayToClass + "_edit").val();
            var postPath;

            if ( idVal > 0 ) {
                /* If an id exists, we will edit the news */
                postPath = actionVal + editPath;
            } else {
                /* If no id exists, we will create a new entry */
                postPath = actionVal + createPath;
            }

            $.post(postPath, {
                content: contentVal,
                headline: headlineVal,
                id: idVal,
                to: displayToVal,
                from: displayFromVal,
                type: newsType
            }, function ( data ) {
                target.children("." + headlineClass).text(headlineVal);
                target.children("." + contentClass).html(contentVal);
                target.children("." + displayFromClass).html(displayFromVal);
                target.children("." + displayToClass).html(displayToVal);
                /* Set the Id returned from server to avoid to create again this news and to edit it the next time */
                target.children("." + idClass).val( parseInt(data) );

                disableEditor(obj);
            });
        }

        function disableEditor ( obj )
        {
            var target = obj.parent().parent();
            var cancelDialog = obj.parent();
            var headlineObj = target.children("."+headlineClass);
            var contentObj = target.children("."+contentClass);
            var displayFromObj = target.children("." + displayFromClass);
            var displayToObj = target.children("." + displayToClass);
            var authorObj = target.children("." + authorClass);
            var dateObj = target.children("." + dateClass);

            authorObj.fadeIn("slow");
            dateObj.fadeIn("slow");

            target.css({borderStyle: target.data("borderStyle")});
            target.css({borderColor: target.data("borderColor")});
            hideInputField(displayFromObj);
            hideInputField(displayToObj);
            hideInputField(contentObj);
            hideInputField(headlineObj);
            cancelDialog.hide();
        }

        function activateEditor( obj )
        {
            var target = obj.parent().parent();
            var targetWidth = target.outerWidth();
            var headlineObj = target.children("."+headlineClass);
            var contentObj = target.children("."+contentClass);
            var cancelDialog = target.children(".cancel");
            var contentHtml = contentObj.html();
            var displayFromObj = target.children("." + displayFromClass);
            var displayToObj = target.children("." + displayToClass);
            var authorObj = target.children("." + authorClass);
            var dateObj = target.children("." + dateClass);

            target.data( "borderColor", target.css("borderColor") );
            target.data( "borderStyle", target.css("borderStyle") );
            target.css({borderColor: editorBorderColor});

            obj.parent().fadeOut("slow");
            /* Do some nice stuff with the cancel dialog to show it on top */
            cancelDialog.fadeIn("slow")
            var centerPosition =  targetWidth / 2 - cancelDialog.outerWidth() / 2
            cancelDialog.css({
                top:  - cancelDialog.outerHeight(),
                left: centerPosition
            });

            dateObj.fadeOut("slow");
            authorObj.fadeOut("slow");

            showInputField(headlineObj);
            showInputField(contentObj);
            showInputField(displayFromObj);
            showInputField(displayToObj);
            target.children(".display_from_edit, .display_to_edit").datepicker();
        }

        function hideInputField( obj )
        {
            var parent = obj.parent();
            var type = obj.attr("class");
            var input = parent.children("." + type + "_edit");
            //            var target = obj;
            if ( type == contentClass ) {
                obj.slideDown("slow");
                $("#" + input.attr("id") + "_parent").slideUp("slow");
            }

            input.hide();
        }

        function showInputField( obj )
        {
            var parent = obj.parent();
            var type = obj.attr("class");
            var input = parent.children("." + type + "_edit");
            var target = obj;

            if ( input.length == 0) {
                /* create input */
                addInputField(obj)
                input = parent.children("." + type + "_edit");

                if( type == contentClass ) {
                var textAreaId = input.attr("id");
                    copyCss(obj, input);

                    $("#" + textAreaId).tinymce({
                        script_url : '<?php echo $web_root;?>tiny_mce/tiny_mce.js',
                        // General options
                        theme : "advanced",
                        plugins : "emotions,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                        // Theme options
                        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2 : "forecolor,backcolor,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|preview",
                        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak,|,media,image,|,emotions",
                        theme_advanced_toolbar_location : "top",
                        theme_advanced_toolbar_align : "left",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : true,
                        // Example content CSS (should be your site CSS)
                        content_css : "<?php echo $web_root;?>" + contentCss,
                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url : "lists/template_list.js",
                        external_link_list_url : "lists/link_list.js",
                        external_image_list_url : "lists/image_list.js",
                        media_external_list_url : "lists/media_list.js"

                    });
                }
            }

            if( type == contentClass ) {
                var tinyEditor = $("#" + input.attr("id") + "_parent");

                target.slideUp("slow");
                tinyEditor.slideDown("slow");
                input.val( obj.html() );
            } else {
                var content = obj.html();
                if ( content.length == 0) {
                    obj.html("&nbsp;");
                }
                copyCss( target, input);
                input.val( obj.html().replace(/&nbsp;/,"") );
                input.show();
            }
        }

        function copyCss( copyFrom, copyTo )
        {
            var position = copyFrom.position();
            var minHeight = 40;
            var minWidth = 70;
            var newHeight, newWidth, newLeftPosition;
            var type = copyFrom.attr("class");
            var editorPadding = 150;

            if ( type == contentClass) {
                newHeight = parseInt(copyFrom.css("height").replace(/px/,"") ) + editorPadding;
                newHeight += "px";
            } else {
                newHeight = copyFrom.css("height");
            }

            if ( copyFrom.css("width").replace(/px/,"") < minWidth) {
                newWidth = minWidth + "px";
            } else {
                newWidth = copyFrom.css("width");
            }
            if ( $.browser.mozilla ) {
                /* I dont know why, but mozilla insert all input fields 1px to far right.
                 * It is a lack of my programming skill, but 4 Hours searching and testing
                 * are enough.
                 *
                 * Funny world, IE sucks not, opera too, but firefox???? And what
                 * happens next?
                 */
                newLeftPosition = position.left - 1;
            } else {
                newLeftPosition = position.left;
            }
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
                marginLeft: copyFrom.css("margin-left"),
                marginRight: copyFrom.css("margin-right"),
                marginBottom:copyFrom.css("margin-Bottom"),
                paddingLeft: copyFrom.css("padding-left"),
                paddingRight: copyFrom.css("padding-right"),
                paddingTop: copyFrom.css("padding-top"),
                paddingBottom: copyFrom.css("padding-bottom"),
                overflowY: "hidden",
                textAlign: copyFrom.css("text-align"),
                width: newWidth,
                height: newHeight,
                position: "absolute",
                backgroundColor: editorBackgroundColor,
                top: position.top,
                left: newLeftPosition,
                zIndex: "90"
            });
        }

        function replaceBR( string )
        {
            /* no need for textformating, its Tiny_MCE editors job */
            return string;
        }

        function replaceLineFeed( string )
        {
            var newString = string.replace(/\n/ig, "<br>");
            return string
        }

        function addInputField ( obj )
        {
            var type = obj.attr("class");
            if ( type == contentClass ) {
                editorCount++;
                obj.parent().append("<textarea class=\"hidden " + type + "_edit\" id=\"editor_"+ editorCount +"\"></textarea>");
            } else {
                obj.parent().append("<input class=\"" + type + "_edit\" type=\"text\" value=\" \">");
            }
        }

        function addButtons ( obj )
        {
            obj.children(":last").after(
            "<div class=\"cancel hidden\"><button class=\" "+buttonCancelClass+" \"><?php echo $lang->translate("CANCEL");?></button><button class=\" "+buttonSaveClass+" \"><?php echo $lang->translate("SAVE");?></button></div><div class=\"options hidden\"><button class=\""+buttonEditClass+"\"><?php echo $lang->translate("EDIT");?></button><button class=\""+buttonCreateClass+"\"><?php echo $lang->translate("CREATE");?></button><button class=\""+buttonDeleteClass+"\"><?php echo $lang->translate("DELETE");?></button></div>"
            );

            $(".cancel").css({
                position: "absolute",
                top: "0px",
                left: "0px",
                backgroundColor: obj.css("background-color"),
                padding: "8px",
                fontSize: "16px",
                border: "1px solid "+editorBorderColor,
                borderBottom: "0px solid white"
            });
        }

        function addEventHandler ( obj )
        {
            editorObjs.hover(
            function ( ) {
                if ( $(this).children(".cancel:hidden").length != 0 ) {
                    $(this).children(".options").fadeIn("fast");
                }
            },
            function () {
                if ( $(this).children(".cancel:hidden").length != 0 ) {
                    $(this).children(".options").fadeOut();
                }
            })
        }
    }
</script>