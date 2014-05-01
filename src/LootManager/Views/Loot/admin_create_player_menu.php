<div class="block border_light">
    <h3 class="headline"><?php echo $lang->translate("CREATE_NEW_CHAR"); ?>:</h3>
    <div class="data_block">
        Name:
        <input id="create_char_name">
    </div>
    <div class="data_block">
        <?php echo $lang->translate("CLASS"); ?>:
        <input id="create_char_class">
    </div>
</div>

<div class="block border_light">
    <button id="save_new_player"><?php echo $lang->translate("SAVE"); ?></button>
</div>

<script>
    $(function() {
        $("#save_new_player").button().click(
        function ( ) {
            $.post("<?php echo $web_root ?>loot/create_player/", { "name": $("#create_char_name").val(), "type": $("#create_char_class").val()} , function () {
                window.location.reload()
            });
        }
    );
        var classList = <?php echo json_encode($json_klass_tags) ?>;
        $( "#create_char_class" ).autocomplete({
            source: classList
        });
    });
</script>