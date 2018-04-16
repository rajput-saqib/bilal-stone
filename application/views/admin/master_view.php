<?= $header ?>

<body>

<?= $topMenu ?>

    <div class="container-fluid">
        <div class="row-fluid">

        <?=$leftMenu?>

        <div id="content" class="span10">

        <?=Admin_Controller::fileMap($controllerName)?>

        <div class="row-fluid sortable ui-sortable">
            <div class="box span12">
                <div data-original-title="" class="box-header well">
                    <h2><i class="icon-th"></i> <?=$obj->createHeading($controllerName)?></h2>
                </div>

                <div class="box-content">
                    <?=$pageData?>
                </div>
            </div>
        </div>

        <?=$footer?>


<?
    if(isset($notification))
    {
        $color = 'red';
        if($notification['status'] == '1')
        {
            $color = 'green';
        }
        ?>
            <script type="text/javascript">
                showNotification('<?=$notification['msg']?>', '<?=$color?>');
            </script>
        <?
    }
?>
