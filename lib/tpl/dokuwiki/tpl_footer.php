<?php

/**
 * Template footer, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
?>

<!-- ********** FOOTER ********** -->
<footer id="dokuwiki__footer"><div class="pad">
    <?php tpl_license(''); // license text ?>

    <?php tpl_includeFile('footer.html'); ?>
</div></footer><!-- /footer -->
