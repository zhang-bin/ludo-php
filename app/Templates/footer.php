</div>
</div>
</div>
</section>
</div>
<?php

use Ludo\Support\Facades\Lang;
use Ludo\View\View;
?>
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
<div class="modal-del modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p class="text-center"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Lang::get('base.cancel')?></button>
                <button type="button" class="btn btn-primary" id="confirmProcess"><?=Lang::get('base.confirm')?></button>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-danger alert-box" id="alert-box" style="display: none;" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <p></p>
</div>
<?php
View::loadCss();
View::loadJs();
?>
</body>
</html>