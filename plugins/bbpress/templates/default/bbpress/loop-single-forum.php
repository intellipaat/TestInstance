<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<style type="text/css">
li.bbp-forum-info { width:40%;}
li.bbp-forum-topic-count { width:20%; }
li.bbp-forum-reply-count { width:40%; text-align:right; padding-right:40px; }

.bbp-header.new{ background-color:#fff !important; border:none !important;}
.bbp-header.new .bbp-forum-info a, .bbp-header.new .bbp-forum-topic-count, .bbp-header.new .bbp-forum-reply-count{ color:#000 !important; font-weight:bold !important; text-transform:none; font-size:16px;}
.bbp-forums-list { padding:0 !important; }
.bbp-my-list {border:#000 1px solid; width: 100%;float: left;    padding: 10px 0;}
.bbp-forums-list li.bbp-forum-info { width:40%; }
.bbp-forums-list li.bbp-forum-topic-count { width:20%; }
.bbp-forums-list li.bbp-forum-reply-count { width:40%; text-align:right; padding-right:40px; color:#000; }
.bbp-my-list .bbp-forum-topic-count{color:#000;}
.bbp-forum-reply-count .bbp-topic-freshness-author, .bbp-forum-reply-count a {text-transform:none; color:#000;}
.bbp-body > ul {padding:0 !important; border:none !important; }
.bbp-footer, .bbp-author-avatar { display:none; }
.my-bb-header div{width:60%; text-align:center;  margin: 0 auto;}
.my-bb-header { width:100%; margin-bottom: 40px;}
.bbp-forums-list .bbp-forum a, .bbp-forum-reply-count .bbp-topic-freshness-author {font-size:14px;}
</style>
<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>
	<li class="bbp-header new">
		<ul class="forum-titles">
			<li class="bbp-forum-info">
			<?php do_action( 'bbp_theme_before_forum_title' ); ?>
            <a class="bbp-forum-title" ><?php bbp_forum_title(); ?></a>
            <?php do_action( 'bbp_theme_after_forum_title' ); ?>
            </li>
			<li class="bbp-forum-topic-count">Threads / Posts</li>
			<li class="bbp-forum-reply-count">Last Post</li>
		</ul>
	</li>
    <li class="bbp-my-list"><ul>
	<li class="bbp-forum-info">

		<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

			<span class="bbp-row-actions">

				<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

				<?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

				<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

			</span>

		<?php endif; ?>

		<?php /*?><?php do_action( 'bbp_theme_before_forum_description' ); ?>

		<div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?><?php */?>

		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php bbp_list_forums(); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

	</li>

	<li class="bbp-forum-topic-count"><?php bbp_forum_topic_count(); ?> / <?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

	<?php /*?><li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li><?php */?>

	<li class="bbp-forum-reply-count">

		<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>
		<span class="bbp-topic-freshness-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 14 ) ); ?></span><br />
		<?php bbp_forum_freshness_link(); ?>

		<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>

		<?php /*?><p class="bbp-topic-meta">

			<?php do_action( 'bbp_theme_before_topic_author' ); ?>

			<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 14 ) ); ?></span>

			<?php do_action( 'bbp_theme_after_topic_author' ); ?>

		</p><?php */?>
	</li>
    </ul></li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
