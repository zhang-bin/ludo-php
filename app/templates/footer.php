<footer class="main-footer">
    <?php if (DEBUG) { ?>
        <span>Process time: <?=sprintf('%.3f', (microtime(true) - SYS_START_TIME)*1000)?> ms.</span>
        <a href="<?=LD_UPLOAD_URL?>/debug_console.php" target="debug_console">debug_console</a>

        <?php
        if (extension_loaded('xhprof')) {
            $xhprof_data = xhprof_disable();
            include_once SITE_ROOT . '/../xhprof_lib/utils/xhprof_lib.php';
            include_once SITE_ROOT . '/../xhprof_lib/utils/xhprof_runs.php';

            $xhprof_runs = new XHProfRuns_Default();
            $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
            $url = SITE_URL.'/public/xhprof_html/index.php?run='.$run_id.'&source=xhprof_foo';
            echo '<a href="'.$url.'" target="xhprof">xhprof</a>';
        }
        ?>
    <?php } ?>
</footer>
<?php View::loadCss();?>
<?php View::loadJs();?>
</body>
</html>