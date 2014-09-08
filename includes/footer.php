<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>

<div class="grid_24 footer" style="float:none;">
  <p align="center"><?php echo FOOTER_TEXT_BODY; ?> <!--| Developed by <a href="http://www.skiify.com" target="_blank" >Skiify Solutions</a>-->

<span ><br />
<!--<b>Powered by:</b><br /><a href="http://www.skiify.com" target="_blank" ><img style="height:30px" src="images/skiify_logo.png" alt="Skiify Solutions" /></a>--></div>
</span>
</p>

</div>



<?php
  if ($banner = tep_banner_exists('dynamic', 'footer')) {
?>

<div class="grid_24" style="text-align: center; padding-bottom: 20px;">
  <?php echo tep_display_banner('static', $banner); ?>
</div>

<?php
  }
?>

<script type="text/javascript">
$('.productListTable tr:nth-child(even)').addClass('alt');
</script>
