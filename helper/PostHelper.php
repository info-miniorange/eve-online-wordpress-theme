<?php

namespace WordPress\Themes\EveOnline\Helper;

\defined('ABSPATH') or die();

class PostHelper {
	public static function getPostMetaInformation() {
		$options = \get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

		if(!empty($options['post_meta']['show'])) {
			\printf(\__('Posted on <time class="entry-date" datetime="%3$s">%4$s</time><span class="byline"> <span class="sep"> by </span> <span class="author vcard">%7$s</span></span>', 'eve-online'),
				\esc_url(\get_permalink()),
				\esc_attr(\get_the_time()),
				\esc_attr(\get_the_date('c')),
				\esc_html(\get_the_date()),
				\esc_url(\get_author_posts_url(\get_the_author_meta('ID'))),
				\esc_attr(\sprintf(\__('View all posts by %s', 'eve-online'),
					\get_the_author()
				)),
				\esc_html(get_the_author())
			);
		} // END if(!empty($options['post_meta']['show']))
	} // END public static function getPostMetaInformation()

	/**
	 * Display template for post categories and tags
	 */
	public static function getPostCategoryAndTags() {
		$options = \get_option('eve_theme_options', ThemeHelper::getThemeDefaultOptions());

		if(!empty($options['show_post_meta']['yes'])) {
			\printf('<span class="cats_tags"><span class="glyphicon glyphicon-folder-open" title="My tip"></span><span class="cats">');
			\printf(\the_category(', '));
			\printf('</span>');

			if(\has_tag() === true) {
				\printf('<span class="glyphicon glyphicon-tags"></span><span class="tags">');
				\printf(\the_tags(' '));
				\printf('</span>');
			} // END if(has_tag() === true)

			\printf('</span>');
		} // END if(!empty($options['show_post_meta']['yes']))
	} // END public static function getPostCategoryAndTags()

	/**
	 * check if a post has content or not
	 *
	 * @param int $postID ID of the post
	 * @return boolean
	 */
	public static function hasContent($postID) {
		$content_post = \get_post($postID);
		$content = $content_post->post_content;

		return \trim(\str_replace('&nbsp;','',  \strip_tags($content))) !== '';
	} // END public static function hasContent($postID)

	public static function getHeaderColClasses($echo = false) {
		if(ThemeHelper::hasSidebar('header-widget-area')) {
			$contentColClass = 'col-xs-12 col-sm-9 col-md-6 col-lg-6';
		} else {
			$contentColClass = 'col-xs-12 col-sm-9 col-md-9 col-lg-9';
		} // END if(Helper\ThemeHelper::hasSidebar('header-widget-area'))

		if($echo === true) {
			echo $contentColClass;
		} else {
			return $contentColClass;
		} // END if($echo === true)
	} // END public static function getHeaderColClasses($echo = false)

	public static function getMainContentColClasses($echo = false) {
		if(\is_page() || \is_home()) {
			if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
				$contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
			} else {
				$contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} else {
			if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
				$contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
			} else {
				$contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} // END if(\is_page())

		if($echo === true) {
			echo $contentColClass;
		} else {
			return $contentColClass;
		} // END if($echo === true)
	} // END public static function getMainContentColClasses($echo = false)

	public static function getLoopContentClasses($echo = false) {
		if(\is_page() || \is_home()) {
			if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
				$contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
			} else {
				$contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} else {
			if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
				$contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
			} else {
				$contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} // END if(\is_page())

		if($echo === true) {
			echo $contentColClass;
		} else {
			return $contentColClass;
		} // END if($echo === true)
	} // END function public static function geLoopContentClasses($echo = false)

	public static function getArticleNavigationPanelClasses($echo = false) {
		if(\is_page() || \is_home()) {
			if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
				$contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-6';
			} else {
				$contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-6';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} else {
			if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
				$contentColClass = 'col-lg-4 col-md-6 col-sm-6 col-xs-6';
			} else {
				$contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-6';
			} // END if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post'))
		} // END if(\is_page())

		if($echo === true) {
			echo $contentColClass;
		} else {
			return $contentColClass;
		} // END if($echo === true)
	} // END function public static function getArticleNavigationPanelClasses($echo = false)

	public static function getContentColumnCount($echo = false) {
		if(\is_page() || \is_home()) {
			if(ThemeHelper::hasSidebar('sidebar-page') || ThemeHelper::hasSidebar('sidebar-general')) {
				$columnCount = 3;
			} else {
				$columnCount = 4;
			} // END if(ThemeHelper::hasSidebar('sidebar-page'))
		} else {
			if(ThemeHelper::hasSidebar('sidebar-general') || ThemeHelper::hasSidebar('sidebar-post')) {
				$columnCount = 3;
			} else {
				$columnCount = 4;
			} // END if(ThemeHelper::hasSidebar('sidebar-page'))
		} // END if(\is_page())

		if($echo === true) {
			echo $columnCount;
		} else {
			return $columnCount;
		} // END if($echo === true)
	} // END public static function getContentColumnCount($echo = false)

	public static function getExcerptById($postID, $excerptLength = 35) {
		$the_post = \get_post($postID); //Gets post ID
		$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
		$the_excerpt = \strip_tags(\strip_shortcodes($the_excerpt)); //Strips tags and images
		$words = \explode(' ', $the_excerpt, $excerptLength + 1);

		if(\count($words) > $excerptLength) {
			\array_pop($words);
			\array_push($words, '…');
			$the_excerpt = \implode(' ', $words);
		} // END if(\count($words) > $excerptLength)

		$the_excerpt = '<p>' . $the_excerpt . '</p>';

		return $the_excerpt;
	} // END public static function getExcerptById($postID, $excerptLength = 35)
} // END class PostHelper
