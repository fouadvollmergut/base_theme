<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>">

    <?php wp_head(); ?>

    <?php include_once( get_template_directory() . '/includes/templates/browser.php' ); ?>

    <title>
      <?php if (is_front_page() || is_home()){
        echo get_bloginfo('name');
      } else {
        echo get_the_title('') .  ' | ' .  get_bloginfo( 'name' );
      }?>
    </title>
  </head>
  
  <body <?php body_class(); ?>>

    <?php include_once( get_template_directory() . '/includes/templates/navigation.php' ); ?>

    <main>
