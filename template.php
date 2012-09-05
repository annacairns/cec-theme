<?php
/**
 * Override or insert variables into the node template.
 */
function cec_preprocess_node(&$variables) {

	// Default code from bartik/template.php:
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
    $variables['summaries'] = '';
  }
  
  // Add the $summaries variable which contains the rendered output
  // of the teasers of the nodes referenced in fields field_node_link.
  // Add 'Read more' button after each teaser.
  $summaries = ' ';
  if ($variables['node']->type == 'summary_page' && isset($variables['node']->field_node_link)){
  	if ( $variables['node']->field_node_link == array() ){
  		return;
  	};
  	// Get the array of the field values of field field_node_link
  	$links = $variables['node']->field_node_link['und'];
  	// Convert it into an array of $nids
  	// WARNING: this works only if the field_node_links are of the pattern "node/$nid"
  	$nodes = array();
  	foreach( $links as $l ){
  		$ex = explode('/', strval($l['value']));
  		$nodes[] = array("id" => $ex[1], "link" => $l['value']);
  	}
  	// Create the rendered output
  	foreach( $nodes as $n ){
  		$no = node_view(node_load($n['id']),'teaser');
  		$summaries .= drupal_render($no['body'][0]);
  		$li = array('#type' => 'link',  '#href' => $n['link'], '#title' => t('Read more'));
  		$summaries .= drupal_render( $li );
  	}
  }
 	$variables['summaries'] = $summaries;
}