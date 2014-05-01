<!DOCTYPE html>
<html>
    <head>
<?php echo $meta;?>

        <title><?php echo $title; ?></title>

        <style type="text/css">
            body {
                background-color: white;
                font-family: Tahoma, Verdana, Sans-serif;
                font-size: 1.1em;
                color: #444444;
            }

            h1 {
                color: steelblue;
                border-bottom: 2px dotted #CCCCCC;
                text-align: right;
                font-size: 1.6em;
            }

            .block {
                font-family: Verdana, Sans-serif;
                font-size: 0.7em;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: steelblue;
                margin: 14px 0px;
                padding: 12px 10px;
            }

            #copy {
                color: #C0C0C0;
                font-size: 0.9em;
                font-family: Arial;
                text-align: right;
            }

            #page_wrapper {
                min-width: 800px;
                width: 60%;
                margin: 0px auto;
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <?php echo $body?>
    </body>
</html>
