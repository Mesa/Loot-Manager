
(function( $ ){
    var status = "stanby";
    var events = new Object();
    var items = new Array();
    var settings;
    var methods = {
        init : function( options ) {
            settings = $.extend( {
            'menu_pos_x'   :  "top",
            'menu_pos_y'   :  "right",
            'menu_x_margin':  "-1px",
            'menu_y_margin':  "-1px",
            'menu_padding' :  "15px 5px 15px 35px",
            'menu_width'   :  "150px",
            'backgroundColor':"white",
            'border'       :  "1px solid lightgray",
            'textAlign'    :  "left",
            'edit_btn_txt' :  "Bearbeiten",
            'cancel_btn_txt': "Abbrechen",
            'save_btn_txt' :  "Speichern",
            'target_url'   :  '',
            'close_editor_txt' : 'Editor schließen',
            'open_editor_txt' : 'Editor öffnen',
            'img_root_path': '',
            'vars'         :  {},
            'tiny_mce_path': "",
            'callBack'     : ''
            }, options);


            methods.observe("start", methods.addLayer);

            methods.observe("active", methods.showButtons);
            methods.observe("active", methods.activate);
            methods.observe("standby", methods.hideButtons);
            methods.observe("standby", methods.standby);
            methods.observe("cancel", methods.cancel);
            methods.observe("save", methods.save);
            methods.observe("active", methods.showHint);
            methods.observe("standby", methods.hideHint);

            this.each( function (){
                items.push($(this));
            });
            if ( items.length > 0) {
                methods.notify("start");
            }
        },

        showButtons : function ( ) {
            $("#edit-me-button-edit").hide();
            $("#edit-me-button-cancel").show();
            $("#edit-me-button-save").show();
        },

        hideButtons : function ( ) {
            $("#edit-me-button-edit").show();
            $("#edit-me-button-cancel").hide();
            $("#edit-me-button-save").hide();
        },

        addLayer : function ( ) {
            if ( $("#submenu").length == 1 && $("#edit-me-button-edit").length == 0 ) {
                $("#submenu").find(".description").eq(0).after(
                        "<button id=\"edit-me-button-edit\">\
                            " + settings.edit_btn_txt + "<br>\
                            <img src=\""+settings.img_root_path +"pencil_big.png\">\
                        </button>\
                        <button id=\"edit-me-button-save\">\
                            " + settings.save_btn_txt + "<br>\
                            <img src=\""+settings.img_root_path +"disk_big.png\">\
                        </button>\
                        <button id=\"edit-me-button-cancel\">\
                            " + settings.cancel_btn_txt + "<br>\
                            <img src=\""+settings.img_root_path +"cross_big.png\">\
                        </button>"
                );
                $("#edit-me-button-cancel, #edit-me-button-save").hide();
                $("#edit-me-button-save, #edit-me-button-cancel,#edit-me-button-edit").css({
                    "float": "left"
                })
                $("#edit-me-button-edit").click( function (){
                    methods.notify("active");
                });

                $("#edit-me-button-cancel").click( function (){
                    methods.notify("cancel");
                    methods.notify("standby");
                });

                $("#edit-me-button-save").click( function (){
                    methods.notify("save");
                    methods.notify("standby");
                });
            } else if ( $("#submenu").length == 0 && $("#edit-me-layer").length == 0 ) {
            $("body").prepend(
                "<div id=\"edit-me-layer\">\
                        <button id=\"edit-me-button-edit\">" + settings.edit_btn_txt + "</button>\
                        <button id=\"edit-me-button-cancel\">" + settings.cancel_btn_txt + "</button>\
                        <button id=\"edit-me-button-save\">" + settings.save_btn_txt + "</button>\
                </div>"
            );

            $("#edit-me-button-cancel").hide();
            $("#edit-me-button-save").hide();

            $("#edit-me-button-edit").click( function (){
                methods.notify("active");
            });

            $("#edit-me-button-cancel").click( function (){
                methods.notify("cancel");
                methods.notify("standby");
            });

            $("#edit-me-button-save").click( function (){
                methods.notify("save");
                methods.notify("standby");
            });

            switch (settings.menu_pos_x) {
                case "top":
                    $("#edit-me-layer").css({
                        top : settings.menu_x_margin
                    });
                break;

                case "bottom":
                    $("#edit-me-layer").css({
                        bottom : settings.menu_x_margin
                    });
                break;
            };

            switch (settings.menu_pos_y) {
                case "left":
                    $("#edit-me-layer").css({
                        left : settings.menu_y_margin
                    });
                break;

                case "right":
                    $("#edit-me-layer").css({
                        right : settings.menu_y_margin
                    });
                break;
            };

            $("#edit-me-layer").css({
                position: "absolute",
                width           : settings.menu_width,
                padding         : settings.menu_padding,
                backgroundColor : settings.backgroundColor,
                border          : settings.border,
                textAlign       : settings.textAlign,
                zIndex          : "99"
            });
        }

        },

        showHint  : function ( ) {
            for (var i = 0; i < items.length; i++ ) {
                var childNodes = items[i].find("[data-name]");

                if (childNodes.length > 0 ) {
                    for ( var z = 0; z < childNodes.length; z++) {
                        if ( childNodes.eq(z).data("readonly") != "") {
                            childNodes.eq(z).data("oldBg",childNodes.eq(z).css("backgroundColor"));
                            childNodes.eq(z).animate({
                                backgroundColor : "#FFFFCE"
                            });
                        }
                    }
                } else {
                    $.data(items[i], "oldBg", items[i].css("backgroundColor"));
                    items[i].animate({
                        backgroundColor : "#FFFFCE"
                    })
                }
            }
        },

        hideHint  : function ( ) {
            for (var i = 0; i < items.length; i++ ) {
                var childNodes = items[i].find("[data-name]");

                if (childNodes.length > 0 ) {
                    for ( var z = 0; z < childNodes.length; z++) {
                        childNodes.eq(z).animate({
                            backgroundColor: childNodes.eq(z).data("oldBg" )
                        })
                    }
                } else {
                    items[i].animate({
                        backgroundColor: $.data(items[i], "oldBg")
                    })
                }
            }
        },

        notify : function ( event, args ) {
            if (typeof events[event] == "object" ) {

                for ( var i = 0; i < events[event].length; i++) {
                    callback = events[event][i];
                    if ( callback != undefined && typeof callback == 'function') {
                        callback( event, args );
                    }
                }
            }
        },

        observe : function ( event, callBack ) {
            if ( events[event] == undefined) {
                events[event] = new Array();
            }
            events[event].push(callBack);
        },

        activate : function ( ) {
            for (var i = 0; i < items.length; i++ ) {

                var childNodes =  items[i].find("*[data-name]");
                var tabIndex = 1;
                if ( childNodes.length > 0 ) {
                    for ( var z = 0; z < childNodes.length; z++) {
                        childNodes.eq(z).data("oldData", childNodes.eq(z).html())
                        methods.activateInput(childNodes.eq(z), tabIndex++);
                    }
                } else {
                    methods.activateInput(items[i], tabIndex++);
                    $.data(items[i],"oldData", items[i].html())
                    items[i].attr("contenteditable", "true");
                }
            }
        },

        getAutoCompleteData : function ( item ) {
            var requestInProgress = false;
            var requestTriggered   = false;

            return function (event) {
                var input  = $(this);
                var target = input.data("autocomplete");
                var value  = input.html();
                var list   = item.find("ul");

                var ignoreKeys = [9, 16, 17, 18, 19, 20, 27, 35, 36, 37, 39];

                if ( jQuery.inArray(event.keyCode, ignoreKeys) > -1) {
                    return;
                }

                var specialKeys = [38, 40, 13, 33, 34];

                if ( jQuery.inArray(event.keyCode,specialKeys) > -1 ) {
                    var selectedItem = list.find("li.highlight");

                    if ( selectedItem.length == 0) {
                        list.find("li:first-child").addClass("highlight");
                    } else  {
                        selectedItem.removeClass("highlight");

                        switch ( event.keyCode ) {
                            case 38:
                            case 33:
                                if ( selectedItem.prev().length == 0) {
                                    list.find("li:last-child").addClass("highlight")
                                } else {
                                    selectedItem.prev().addClass("highlight");
                                }
                            break;

                            case 40:
                            case 34:
                                if ( selectedItem.next().length == 0) {
                                    list.find("li:first-child").addClass("highlight");
                                } else {
                                    selectedItem.next().addClass("highlight");
                                }
                            break;

                            case 13:
                                input.html(selectedItem.text());
                                item.slideUp("slow");
                            break;

                        }
                    }
                } else {
                    if ( value.length > 3 ) {
                        item.slideUp("fast");

                        if ( requestInProgress == true ) {
                            if ( requestTriggered == false ) {
                                requestTriggered = true;
                                setTimeout(function () {
                                    methods.getAutoCompleteData(item);
                                    requestTriggered = false;
                                },3000);
                            }
                        } else {
                            requestInProgress = true;
                            $.post(target,{data:value}, function (data){

                                item.find('ul li').remove();

                                if ( typeof data.length == "number" && data.length > 0 ) {
                                    for( var i = 0; i < data.length; i++) {
                                        list.append("<li>"+ data[i] + "</li>");
                                    }

                                    list.find("li").hover( function (){
                                        $(this).addClass("highlight").css({
                                            cursor: "pointer"
                                        });
                                    }, function (){
                                        $(this).removeClass("highlight");
                                    });

                                    list.find("li").click( function (){
                                        input.html( $(this).text() )
                                    });
                                }
                                item.slideDown("fast");
                                requestInProgress = false;
                            }, "json");

                        }
                    }
                }
            }
        },

        showAutoComplete : function ( item ) {

            if ( typeof item.data("autoCompleteId") == "undefined" || item.data("autoCompleteId") == "") {
                var divNr = Math.round(Math.random() * 10000000000);
                item.data("autoCompleteId", divNr);
                $("body").append("<div class=\"autocomplete\" id=\"autocomplete" + divNr + "\"><ul></ul></div>");
                $("#autocomplete" + divNr).hide();

                var offset = item.offset();
                var itemHeight = item.height();
                var autoComplete = $("#autocomplete" + divNr);

                autoComplete.css({
                    position: "absolute",
                    left    : offset.left,
                    top     : offset.top + itemHeight,
                    zIndex  : "100"
                });
                item.keyup(  methods.getAutoCompleteData( autoComplete ) );
            }
        },

        reload : function () {

            items = new Array();

            this.each( function (){
                items.push($(this));
            });
        },

        hideAutoComplete : function ( item ) {
            var divNr = item.data("autoCompleteId");
            $("#autocomplete" + divNr).slideUp("slow");
        },

        showHtmlEditor : function ( button, item ) {
            var data = item.html();
            if ($("#tinyeditor").length == 0) {
                var editorDiv = $("<div id=\"tinyeditor\"><textarea></textarea></div>");

                editorDiv.find("textarea").css({
                    width : "100%",
                    height: "100%"
                });

                editorDiv.css({
                    position : "absolute"
                });

                $("body").append(editorDiv);
                methods.observe("standby", function () {
                    methods.hideHtmlEditor($("#"+item.data("editorid")), item)
                });
                $("#tinyeditor textarea").tinymce({
                    script_url : settings.tiny_mce_path,
                    theme : "advanced",
                    theme_advanced_toolbar_align : "left",
                    theme_advanced_statusbar_location : "bottom",
                    theme_advanced_resizing : true,
                    plugins : "emotions,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                    // Theme options
                    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,link,unlink,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor,|,styleselect,formatselect,fontselect,fontsizeselect|,media,image,|,emotions|,hr,advhr,|,styleprops|,undo,redo",
                    theme_advanced_buttons2 : "replace,|,outdent,indent,blockquote,|,anchor,cleanup,|preview,|,cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,",
                    theme_advanced_buttons3 : "tablecontrols,|,removeformat,visualaid,|,sub,sup,|,charmap,iespell,|,print,|,ltr,rtl,|,fullscreen",
                    content_css : "editor.css"
                });
                $("#tinyeditor").hide();
            }

            var inputHeight = item.outerHeight();
            var inputWidth  = item.outerWidth() ;
            var inputPosition = item.offset();
            $("#tinyeditor").css({
                width    : inputWidth,
                height   : inputHeight + 24,
                top      : inputPosition.top,
                left     : inputPosition.left
            });

            button.text(settings.close_editor_txt);
            $("#tinyeditor textarea").html(data)
            $("#tinyeditor").data("target_editor", item);
            $("#tinyeditor").show();
        },

        hideHtmlEditor : function ( button, item ) {
            var editor = $("#tinyeditor");
            var target = editor.data("target_editor");

            editor.hide();
            target.html($("#tinyeditor textarea").html())
            button.html(settings.open_editor_txt);
        },
        showHtmlEditorBtn : function ( item ) {
            if ( typeof item.data("editorid") == "undefined" ) {
                var layer = $("<div>"+ settings.open_editor_txt +"</div>");
                var divNr = Math.round(Math.random() * 10000000000);
                item.data("editorid", "editor" + divNr);
                layer.attr("id","editor" + divNr);
                methods.observe("standby", function () {
                    methods.hideHtmlEditorBtn(item);
                });
                $("body").append(layer);

                layer.click( function () {
                    var active = $("#tinyeditor").is(':visible');

                    if ( active ) {
                        methods.hideHtmlEditor($(this),item)
                    } else {
                        methods.showHtmlEditor($(this), item);
                    }
                });
                layer.css({
                    position        : "absolute",
                    cursor          : "pointer",
                    padding         : "3px 5px 5px 5px",
                    backgroundColor : "white",
                    color           : "steelblue",
                    borderTop       : "1px solid lightgray",
                    borderLeft      : "1px solid lightgray",
                    borderRight     : "1px solid lightgray"
                });
                layer.hide();
            }
            var editorId = item.data("editorid");
            var editor = $("#"+editorId);
            editor.show();
            var parentOffset = item.offset();
            var positionLeft = parentOffset.left + 25;
            var positionTop = parentOffset.top - editor.outerHeight();


            $("#"+editorId).css({
                top      : positionTop,
                left     : positionLeft
            });
        },

        hideHtmlEditorBtn : function ( item ) {
            var id = item.data("editorid");
            $("#" + id).hide();
        },

        activateInput : function ( input, tabIndex ) {
            if (input.data("readonly") != "") {
                input.attr("contenteditable", "true");
                input.attr("tabindex", tabIndex);

                if ( typeof input.data("htmleditor") != "undefined" ) {
                        methods.showHtmlEditorBtn(input);
                } else if (  input.data("autocomplete") != "" && typeof input.data("autocomplete") != "undefined") {
                    input.focus( function () {
                        methods.showAutoComplete($(this))
                    }).blur( function () {
                        methods.hideAutoComplete($(this))
                    });
                }
            }
        },

        cancel : function ( ) {
            for (var i = 0; i < items.length; i++ ) {
                var childNodes = items[i].find("[data-name]");

                if ( childNodes.length > 0 ) {
                    for ( var z = 0; z < childNodes.length; z++) {
                        if ( childNodes.eq(z).data("oldData") != childNodes.eq(z).html()) {
                            childNodes.eq(z).html(childNodes.eq(z).data("oldData"));
                        }
                    }
                } else {
                    if ( $.data(items[i],"oldData") != items[i].html()) {
                        items[i].text( $.data(items[i],"oldData") );
                    }
                }
            }
        },

        save : function ( ) {
            var target_url;
            var valueChanged = false;
            if ( $("#tinyeditor").is(":visible")) {
                var target_editor = $("#tinyeditor").data("target_editor");
                target_editor.html( $("#tinyeditor textarea").html()  );
            }

            for (var i = 0; i < items.length; i++ ) {

                if (typeof items[i].data("url") != "undefined" && items[i].data("url") != "") {
                    target_url = items[i].data("url");
                } else {
                    target_url = settings.target_url;
                }

                var nodeData = new Object();
                var childNodes = items[i].find("[data-name]");
                var name, value;
                if ( childNodes.length > 0 ) {

                    for (var z = 0; z < childNodes.length; z++) {
                        name = childNodes.eq(z).data("name");
                        value = childNodes.eq(z).html();
                        nodeData[name] = value;
                        if ( value != childNodes.eq(z).data("oldData") ) {
                            valueChanged = true;
                        }
                    }
                } else {
                    name = items[i].data("name");
                    value = items[i].html();
                    nodeData[name] = value;
                    if ( value != $.data(items[i], "oldData")) {
                        valueChanged = true
                    }
                }

                if ( valueChanged ) {
                    var item;
                    if ( settings.callBack != "") {
                        item = items[i];
                        $.post(target_url, nodeData, settings.callBack(item), "json");
                    } else {
                        item = items[i];
                        $.post(target_url, nodeData, methods.postCallBack(item,childNodes), "json" );
                    }
                }
                valueChanged = false;
            }
        },

        postCallBack : function ( item, childNodes ) {
            return function ( response ) {

                if (response != null && response.executed == true ) {
                    if ( childNodes.length > 0 ) {
                        for ( var i = 0; i < childNodes.length; i++) {
                            if ( childNodes.eq(i).html() != response[childNodes.eq(i).data("name")] ) {
                                childNodes.eq(i).html(response[childNodes.eq(i).data("name")]);
                            }
                        }
                    } else {
                        if ( item.html() != response.value) {
                            item.html(response.value);
                        }
                    }
                } else {

                    if ( childNodes.length > 0 ) {
                        if ( response.message != null ) {
                            methods.showErrorMsg(childNodes.eq(0), response.message);
                        }
                        for ( var i = 0; i < childNodes.length; i++) {
                            childNodes.eq(i).html(childNodes.eq(i).data("oldData"));
                        }
                    } else {
                        if ( response.message != null ) {
                            methods.showErrorMsg(item, response.message);
                        }
                        item.html($.data(item, "oldData"));
                    }
                }
            }
        },

        showErrorMsg : function ( item, msg ) {
            var errorDiv = $("<div class=\"error_msg\">s</div>");

            $("body").append(errorDiv);

            var itemOffset = item.offset();
            var errorBottom = itemOffset.top;
            var errorRight  = itemOffset.left;

            errorDiv.html(msg);
            errorDiv.css({
                display : "none",
                position: "absolute",
                top  : errorBottom,
                left   : errorRight,
                padding : "10px"
            });
            errorDiv.fadeIn("slow");
            errorDiv.click( function () {
                $(this).fadeOut("fast", function (){
                    $(this).remove();
                });
            })
        },

        standby : function ( ) {
            var allInputs = $("[data-name]");
            for ( var i = 0; i < allInputs.length; i++) {
                allInputs.eq(i).attr("contenteditable", "false");
                allInputs.eq(i).removeAttr("tabindex");
            }
        }
    };

  $.fn.editMe = function( method ) {
    if ( methods[method] ) {
        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
        return methods.init.apply( this, arguments );
    } else {
        $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
    }
  };
})( jQuery );

function errorMessage( data )
{
    var displayTime = 10000;
    var framePoolId = "javascript-messages";
    var messageWrapper = $("<div>");
    var messageContent = $("<p>");
    var framePool = $("#" + framePoolId);

    messageContent.html(data);

    messageWrapper.addClass("javascript-message hidden");

    messageWrapper.append(messageContent);
    framePool.append(messageWrapper);

    messageWrapper.slideDown( function (){
        var target = $(this);
        setTimeout(function ( ){
            target.slideUp( function ( ){
                $(this).remove();
            });
        }, displayTime)
    });
}
