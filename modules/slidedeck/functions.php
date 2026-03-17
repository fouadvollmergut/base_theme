<?php

  add_action ('gdymc_module_options_settings', function ( $module ) {
    if ($module->type == gdymc_module_name( __FILE__ )):

      optionInput( 'items', array(
        'type' => 'sortable',
        'label' => __( 'Auflistung der Slide-Elemente', 'Theme' ),
      ), $module->id );

    endif;
  });

?>