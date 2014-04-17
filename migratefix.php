<?php
 define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

/**
 * Done with credit to this article
 * http://www.phase2technology.com/blog/entityfieldquery-let-drupal-do-the-heavy-lifting-pt-2/.
 * Extend the EntityFieldQuery function to clean up later in the code.
 *
 * First the Node.
 */
class NodeEntityFieldQuery extends EntityFieldQuery {
  /**
   * @function constructor for node
   */
  public function __construct() {
    // Now we don't need to define these over and over anymore.
    $this
      ->entityCondition('entity_type', 'node')
      ->propertyCondition('status', 1)
      ->propertyOrderBy('title', 'ASC');
    // Define a pager.

  }


}

/**
 * Extend the EntityFieldQuery function to clean up later in the code.
 * Then the product.
 */
class ProductEntityFieldQuery extends EntityFieldQuery {
  // Define some defaults for the class.
  public function __construct() {
    // Now we don't need to define these over and over anymore.
    $this
      ->entityCondition('entity_type', 'commerce_product')
      ->propertyCondition('status', 1)
      ->propertyOrderBy('title', 'ASC');
    // Define a pager.

  }


}



/**
 * Create a function to do the work.
 */
function migratefix_iteration() {
   $node_query = new NodeEntityFieldQuery();
// Set the conditions.
// Adjust to node product type machine name you use.
      $node_query
	  ->entityCondition('bundle', array('publication'))
	  ->range(0, 1);
// Run the query.
      $node_results = $node_query->execute();
if (!empty($node_results['node'])) {
  // $result['node'] keys are nids, values are stub entities.
  $nodes = entity_load('node', array_keys($node_results['node']));
  foreach ($nodes as $node) {
    $node_title = $node->title;
  $prod_efq = new ProductEntityFieldQuery();
  $prod_efq
		->entityCondition('bundle', array('publication'))
		->propertyCondition('title', "%".$node_title."%","LIKE"); 
  // Execute query and collect results
  $result = $prod_efq->execute(); 
  $products = entity_load('commerce_product', array_keys($result['commerce_product']));
  // Load the product record
  $product_id = $products['product_id'];
  //print_r($product_id);
 // print_r($products);
  
  //print "\n value of result is ";
	//print_r($result);
	print $product_id;
	//$products = entity_load('commerce_product', array_keys($products_results['node']));
	//foreach ($products as $product) {
    //$product_title = $product->title;
	//print $product_title;
	//}
	//$node->status = 1;
    //node_save($node);
  }
}
    }
migratefix_iteration();
?>