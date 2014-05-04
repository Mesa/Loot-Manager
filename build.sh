#!/bin/sh

case $1 in
"js")
    java -jar ../compiler.jar --js_output_file web/js/main.min.js \
    --js foundation/bower_components/jquery/dist/jquery.min.js \
    foundation/bower_components/modernizr/modernizr.js \
    foundation/bower_components/foundation/js/foundation.min.js \
    foundation/js/* \
    src/LootManager/public/js/module/* \
    src/LootManager/public/js/page/* \
    --process_jquery_primitives --warning_level=QUIET
;;
"db")
   ./doctrine.php orm:schema-tool:update --force
;;
*)

;;
esac
