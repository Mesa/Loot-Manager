(function( $ ){
    var settings;
    var events = {};
    var item;
    var methods = {
        init : function( options ) {
            settings = $.extend( {
            }, options);

            methods.observe("start", methods.addLayer);
            methods.observe("toggleState", methods.toggle);

            if ( this.length == 1) {
                item = this;
                methods.notify("start");
            } else if (this.length == 0) {
                alert("EditMe: No Submenu found");
            } else {
                alert("EditMe: To many Menus");
            }
        },

        addLayer : function ( ) {
            var wrapper = $("<div></div>");
            var menu_id = item.attr("id");
            item.attr("id", "");
            item.after(wrapper);
            wrapper.attr("id", menu_id);
            item.eq(0).appendTo(wrapper);
            var border_bottom = $("<div id=\"submenu_bottom\">&nbsp;</div>");
            border_bottom.appendTo(wrapper);
            border_bottom.addClass("submenu_bottom");
            border_bottom.css({
                width          : wrapper.outerWidth() - wrapper.css("borderRightWidth").replace("px","") - wrapper.css("borderLeftWidth").replace("px",""),
                cursor         : "pointer",
                position       : "absolute",
                left           : 0 - border_bottom.css("borderLeftWidth").replace("px",""),
                bottom         : 0 - border_bottom.css("borderBottomWidth").replace("px","")
            });

            border_bottom.click( function (){
               methods.notify("toggleState");
            });
            border_bottom.hover( function () {
                if (typeof $(this).data("defaultColor") == "undefined") {
                    $(this).data("defaultColor", $(this).css("backgroundColor"));
                }
                $(this).stop(true, true).animate({
                    backgroundColor : "#EEE"
                }, 200)
            }, function () {
                $(this).stop(true, true).animate({
                    backgroundColor: $(this).data("defaultColor")
                }, 200);
            })
            wrapper.css({
                position: "relative",
                paddingBottom : border_bottom.height() + 5
            });
            if ( getCookie("submenu") == "on") {

            } else {
                item.hide();
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

        toggle : function ( ) {
            if ( item.is(":visible")) {
                item.slideUp("slow");
                setCookie("submenu", "off", document.URL);
            } else {
                item.slideDown("slow");
                setCookie("submenu", "on", document.URL);
            }
        }

    };

  $.fn.submenu = function( method ) {
    if ( methods[method] ) {
        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
        return methods.init.apply( this, arguments );
    } else {
        $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
    }
  };
})( jQuery );
