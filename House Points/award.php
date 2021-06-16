<?php
// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/award.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
            print "You do not have access to this action." ;
    print "</div>" ;
} else {

    $page->breadcrumbs->add(__('Award student points'));
    
    $modpath =  "./modules/".$session->get('module');
    include $modpath."/function.php";
    include $modpath."/award_function.php";
   
    ?>
    <script>
        var modpath = '<?php echo $modpath ?>';
    </script>
    <?php
    
    $pt = new pt($guid, $connection2);
    $pt->modpath = $modpath;
    
    $pt->mainform();
}
