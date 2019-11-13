<?php
/**
 * 一个基于MDUI的Material Design皮肤
 * 
 * @package Matecho
 * @author Zapic
 * @version 0.0.1-beta
 * @link https://i.zapic.cc
 * 
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
 $this->need('header.php');
?>
<?php while($this->next()): ?>
    <div class="post">
	<h2 class="entry_title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h2>
	<div class="entry_data">
	    Published by <a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a> on <?php $this->date('F j, Y'); ?> in <?php $this->category(','); ?>.
	    <?php $this->commentsNum('%d Comments'); ?>.
	</div>
	<div class="entry_text">
	    <?php $this->content('Continue Reading...'); ?>
	</div>
    </div>
<?php endwhile; ?>