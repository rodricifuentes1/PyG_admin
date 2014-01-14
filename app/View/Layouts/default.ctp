<!DOCTYPE html>
<html lang="es">
    <head>
        <title> <?php echo $title_for_layout; ?> </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo $this->Html->css('bootstrap'); ?>
        <?php echo $this->Html->css('datepicker'); ?>
        <?php echo $this->Html->css('select2'); ?>
        <style>
            body {
                margin-top: 100px;
            }

            .center {
                float: none;
                margin-left: auto;
                margin-right: auto;
            }

            a span {
                color: blue;
                font-size: 14px;
                font-style: italic;
            }
            
            .error_default{
                border-color: red;
            }
        </style>
        <?php echo $this->Html->script('//code.jquery.com/jquery-1.10.2.min.js'); ?>
        <?php echo $this->Html->script('bootstrap'); ?>
        <?php echo $this->Html->script('bootstrap-datepicker'); ?>
        <?php echo $this->Html->script('jquery.noty.packaged.min'); ?>
        <?php echo $this->Html->script('select2'); ?>
    </head>
    <body>
        <?php echo $this->fetch('content'); ?>
        <?php //echo $this->element('sql_dump'); ?>
    </body>
</html>
