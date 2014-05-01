$(document).ready( function (){
    buildLangSelect($("#language-switcher"));
    $(".sliding-block h3").click( function (){
        $(this).parent().children(".block_data").slideToggle("fast");
    });

    $("#logout_btn").button();
    dialogTrigger("#login_btn");
    $(".menu a").button();

    $(".event_link").button();

    $("#language-switcher").hover( function (){
        showSelectList($(this));
    }, function (){
        hideSelectList($(this));
    });

    var submenu = $("#submenu");
    if ( submenu.length == 1) {
        $("#submenu").submenu();
    }

});

function positionDialog ( button, dialog ) {
    var padding = 10;
    var button_offset = button.offset();
    dialog.dialog("open");

    var dialog_width = dialog.width();
    var dialog_height = dialog.height();

    dialog.dialog("option",{
        position: [button_offset.left - dialog_width - padding, button_offset.top - ( dialog_height / 2 )]
    });
}

var preLoadDiv, preLoadInterval, preLoadText, preLoadAniStatus = "rm";
var preLoadMaxDots = 60;

function addPreloader( target, text )
{
    if( typeof preLoadInterval == "number") {
        stopPreLoaderAnimation("",function(){});
    }
        preLoadDiv = $("<div>");
        preLoadText = text;

        preLoadDiv.html(preLoadText);
        preLoadDiv.attr("class", "pre-loader hidden");
        target.prepend(preLoadDiv);
        preLoadDiv.slideDown("fast");
        preLoadInterval = window.setInterval("animationStepPreLoader()",100);
}

function animationStepPreLoader ( )
{
    var dotCount;

    if ( preLoadText.match(/\./g) == null ) {
        dotCount = 0
        preLoadAniStatus = "add";
    } else {
        dotCount = preLoadText.match(/\./g).length;
    }

    if ( preLoadMaxDots <= dotCount) {
        preLoadAniStatus = "rm";
    }

    if ( preLoadAniStatus == "add" ) {
        preLoadText += ".";
    } else {
        preLoadText = preLoadText.substr(0, preLoadText.length - 1);
    }
    preLoadDiv.text(preLoadText);
}

function showMessage( data )
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

function showResponse( data )
{
    if ( data != "" ) {
        showMessage(data);
    }
}

function stopPreLoaderAnimation( data , callBack)
{
    var target = preLoadDiv.parent();
    preLoadDiv.slideUp("fast", function (){
        if ( data != "") {
            target.html(data);
        }
        callBack()
    })
    window.clearInterval(preLoadInterval);
}

function dialogTrigger( buttonId )
{
    var buttonObj = $(buttonId);
    var dialogObj = $("<div>");
    var getPath = buttonObj.attr("val");

    dialogObj.dialog({
        autoOpen:false,
        title: buttonObj.html(),
        open: function () {
            /**
             * resize the dialog if needed
             */
            var dialogPadding = 50;
            var newWidth = dialogObj.find("#login").outerWidth();
            if ( dialogObj.outerWidth() - dialogPadding <= newWidth) {
                dialogObj.dialog({
                    width:newWidth + dialogPadding
                });
            }
        }
    });

    buttonObj.button().click( function ( ) {
        if (! getPath == "" ) {
            $.get(getPath, function( data ) {
                dialogObj.html( data );
                dialogObj.dialog("open");
            })
        } else {
            dialogObj.dialog("open");
        }

    });
}

function showSelectList( item ) {
    if ( item.data("oldWidth") == undefined ) {
        item.data("oldWidth",item.width());
        item.data("oldHeight",item.height());
    }
    var itemHeight = item.find("ul").outerHeight() + 1;
    var totalHeight = itemHeight;
    item.stop(true, true).animate({
            height: totalHeight
    })
    item.find("ul").stop(true, true).animate({
        marginTop: 0
    })
}
function hideSelectList( item ) {
    var itemHeight = item.find("li").outerHeight() + 1;
    var position = item.find(".selected").index();
    var listMargin = itemHeight * position;

    if (item.data("oldHeight") != undefined ) {
        item.stop(true, true).animate({
            height: item.data("oldHeight")
        })
        item.find("ul").animate({
            marginTop: "-" + listMargin
        })
    } else {
        item.find("ul").css({
            marginTop: "-" + listMargin + "px"
        })
    }

}
function clickSelectList( item, callBack ) {
    var list = item.parent().parent();
    list.find(".selected").removeClass("selected");
    item.addClass("selected");
    hideSelectList(list);

    if( callBack != "") {
        callBack( item )
    }
}
function buildLangSelect ( item ) {
    var selectedLang = "DE";
    if ( item.length == 1 ) {
        selectedLang = item.html().toUpperCase()
    }
    item.html("");
    var cookie = getCookie("user-lang");

    var data = new Array("DE", "EN");

    var list = $("<ul>");
    var newItem;
    for( i=0; i < data.length; i++) {
        newItem = $("<li>");
        newItem.html(data[i])
        list.append(newItem);
    }
    item.append(list);
    if ( cookie != null ) {
        selectedLang = cookie.toUpperCase();
    }

    var selectedItem;
    for( i=0; i < data.length; i++) {
        if ( data[i] == selectedLang ) {
            selectedItem = item.find("li:eq(" + i + ")");
        }
    }
    if (selectedItem != undefined ) {
        selectedItem.addClass("selected");
    }

    hideSelectList(item);
    item.children().children("li").click(function ( target ) {
        clickSelectList( $(this) , function ( item ){
            setCookie("user-lang", item.html() );
        });


    })
}

function getCookieVal (offset) {
    var endstr = document.cookie.indexOf (";", offset);
    if (endstr == -1)
        endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}
function getCookie (name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg)
        return getCookieVal (j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}
function setCookie(name, value, path) {
    if ( typeof path == "undefined") {
        path = "/";
    }
    document.cookie = name + "=" + value + "; path=" + path;
}

function replaceInputDesc ( item, text)
{
    item.val(text);
    item.addClass("input_desc");

    item.focus( function () {
        if ( $(this).val() == text) {
            $(this).removeClass("input_desc");
            $(this).val("");
        }
    })

    item.blur( function () {
        if ( $(this).val() == "") {
            $(this).addClass("input_desc");
            $(this).val(text);
        }
    })
}
//function eraseCookie(name) {
//	createCookie(name,"",-1);
//}
