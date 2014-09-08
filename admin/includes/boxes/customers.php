<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'heading' => BOX_HEADING_CUSTOMERS,
    'apps' => array(
      array(
        'code' => FILENAME_CUSTOMERS,
        'title' => BOX_CUSTOMERS_CUSTOMERS,
        'link' => tep_href_link(FILENAME_CUSTOMERS)
      ),
      array(
        'code' => FILENAME_ORDERS,
        'title' => BOX_CUSTOMERS_ORDERS,
        'link' => tep_href_link(FILENAME_ORDERS)
      ),
	   array(
        'code' => FILENAME_CREATE_ACCOUNT,
        'title' => BOX_CUSTOMERS_CREATE_ACCOUNT,
        'link' => tep_href_link(FILENAME_CREATE_ACCOUNT)
      ),
      array(
        'code' => 'customers_groups.php',
        'title' => 'Customer Groups',
        'link' => tep_href_link('customers_groups.php')
      ),
	   array(
        'code' => 'import_customer.php',
        'title' => 'Import Customer',
        'link' => 'import_customer.php'
      ),
	   array(
        'code' => 'rfq_orders.php',
        'title' => 'RFQ',
        'link' => tep_href_link('rfq_orders.php')
      )
    )
  );
?>
