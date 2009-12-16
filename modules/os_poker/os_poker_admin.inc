<?php
function os_poker_forms($form_id) {
  $forms = array();
  if (strpos($form_id, 'os_poker_shop_admin_form_') === 0) {
//    print "form_id : ".$form_id."<br/>";
    $forms[$form_id] = array(
			     'callback' => 'os_poker_shop_admin_form',
			     );
  }
  return $forms;
}

function	os_poker_shop_admin_form($form_state, $form_args)
{

	$form = array();

 
	$form['#submit'] = array(
				 'os_poker_shop_admin_form_submit',
				 );


	require_once(drupal_get_path('module', 'os_poker') . "/shop.class.php");

	$item_id = $form_args[0];
	$catlist = $form_args[1];
	$catid = $form_args[2];

	if ($item_id > 0)
		$item = new CItem($item_id);
	else
		$item = array();
	
	$form["id_item"] = array(
										'#type' => 'hidden',
										'#title' => t('id'),
										'#default_value' => $item->id_item,
								);
	$form["id_category"] = array(
										'#type' => 'select',
										'#default_value' => $catid,
										'#options' => $catlist,
										'#multiple' => FALSE,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["name"] = array(
										'#type' => 'textfield',
										'#default_value' => $item->name,
										'#size' => 10,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["picture"] = array(
										'#type' => 'textfield',
										'#default_value' => $item->picture,
										'#size' => 30,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["price"] = array(
										'#type' => 'textfield',
										'#default_value' => $item->price,
										'#size' => 8,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["ttl"] = array(
										'#type' => 'textfield',
										'#default_value' => $item->ttl,
										'#size' => 8,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
								
	$form["available"] = array(
										'#type' => 'checkbox',
										'#default_value' => $item->available,
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["submit"] = array(
										'#type' => 'submit',
										'#default_value' => t("Send"),
										'#prefix' => '<td>',
										'#suffix' => '</td>',
								);
	$form["#redirect"] = FALSE; //"?q=admin/poker/shop";
	
	return $form;
}


function	os_poker_shop_admin_form_submit($form, &$form_state)
{
  require_once(drupal_get_path('module', 'os_poker') . "/shop.class.php");

  if ($form_state["values"]['id_item'] > 0)
    $Item = new CItem($form_state["values"]['id_item']);
  else
    $Item = new CItem();
    if ($Item)
    {
	$updated = 0;
	foreach ($form_state['values'] as $key=>$value)
	{
	       if ($key == "op")
	       {
		      if ($updated > 0)
		      {
			$Item->Save();
			drupal_set_message(t("VG successfully modified"));
		      }
		      else
		      {
			drupal_set_message(t("Nothing to modify"));
		      }
		      return;
		}

		if ($Item->$key != $value)
		{
			$Item->$key = $value;
			$updated++;
		}
	}


	    
    }
}


?>