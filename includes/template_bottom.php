<?php

/*

  $Id$



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2010 osCommerce



  Released under the GNU General Public License

*/

?>



</div> <!-- bodyContent //-->



<?php

	if($dontshowleftright){}else{

  if ($oscTemplate->hasBlocks('boxes_column_left')) {

?>


<?php /*?>
<div id="columnLeft" class="grid_<?php echo $oscTemplate->getGridColumnWidth(); ?> pull_<?php echo $oscTemplate->getGridContentWidth(); ?>">

  <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>

</div>
<?php */?>


<?php

  }



  if ($oscTemplate->hasBlocks('boxes_column_right')) {

?>


<?php /*?>
<div id="columnRight" class="grid_<?php echo $oscTemplate->getGridColumnWidth(); ?>">

  <?php echo $oscTemplate->getBlocks('boxes_column_right'); ?>

</div>
<?php */?>


<?php

  }

  	}

?>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>



</div> <!-- bodyWrapper //-->



<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>



</body>

</html>

