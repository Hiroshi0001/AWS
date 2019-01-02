<?php
get_header();
$u = get_userdata(intval($author));
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<h1><?php echo $u->last_name . " " . $u->first_name; ?></h1>
        <div style="float:left;margin-right:10px"><?php echo get_wp_user_avatar(intval($author),360); ?></div>
        <p><?php echo nl2br($u->user_description); ?></p>
</div>
<?php get_footer(); ?>
