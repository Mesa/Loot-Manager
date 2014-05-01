<?php if ( $this->rights->hasRight("edit_menue_link") ): ?>
<script type="text/javascript">
    $(document).ready( function (){
        $( "#link-list" ).sortable({
            update: function ( event, ui){
                itemChangedOrder( ui )
            },
            placeholder: "ui-state-highlight"
        });
        $( "#link-list" ).disableSelection();
        $(".delete-btn").button({
            icons:{
                primary: "ui-icon-closethick"
            }
        }).click(function(){
            deleteItem($(this));
        });

        $(".right-select").change( function (){
            setRight($(this));
        })
        $("#add-btn").button({
            icons : {
                primary : "ui-icon-plusthick"
            }
        }).click(function(){
            var item = $("#new-item").parent().clone(true);
            var target = $("#link-list");
            item.children("div").removeClass("hidden");
            item.hide();
            target.append(item);
            item.removeAttr("Id");
            item.slideDown("slow");
        });
        $(".save-btn").button().click(function(){
            var target = $(this).parent().parent();
            var nameVal = target.children(".name").children("input").val();
            var pathVal = target.children(".path").children("input").val();

            if ( nameVal != "" && pathVal != "") {
                $.post("add/",{
                    name: nameVal,
                    path: pathVal
                }, function ( data ) {
                    showResponse(data);
                    if ( data == "") {
                        window.location.reload();
                    }
                })
            } else {
                showMessage("Ein Feld war leer");
            }
        });
        observeItem($(".link .name input"),"name/");
        observeItem($(".link .path input"), "path/")
    });

    function setRight ( item ) {
        var itemId = item.parent().parent().children("input").val();
        var rightId = item.val();

        $.get("right/" + itemId + "/" + rightId + "/", function ( data ){
            showResponse(data);
        })

    }
    function observeItem ( item, path ) {

        item.focus( function (){
            if( $(this).data("oldVal") == undefined) {
                $(this).data("oldVal", $(this).val());
            }
        });

        item.blur( function () {
            var newVal = $(this).val();
            var oldVal = $(this).data("oldVal");
            var itemId = $(this).parent().parent().children("input").val();

            if( newVal != oldVal && oldVal != undefined) {
                $.post( path + itemId + "/",{
                    value: newVal
                }, function ( data ) {
                    showResponse(data);
                })
            }
        })
    }

    function deleteItem ( item ) {
        var target = item.parent().parent();
        var itemId = target.children("input").val();
        $.get("delete/" + itemId, function ( data ){
            showResponse(data);
            if ( data == "") {
                target.slideUp("fast", function (){
                    target.remove();
                });
            }
        })
    }

    function itemChangedOrder( ui ) {
        var target = ui.item.children("div");
        var itemId  = getItemId(target);
        var newPosition = ui.item.index();

        $.get("order/" + itemId + "/" + newPosition+ "/", function ( data ){
            showResponse(data);
        });

    }

    function getItemId ( item )
    {
        return item.children("input").val();
    }
</script>

<?php endif; ?>