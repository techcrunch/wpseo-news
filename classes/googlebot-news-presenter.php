<?php
/**
 * Yoast SEO: News plugin file.
 *
 * @package WPSEO_News
 */

use Yoast\WP\SEO\Presenters\Abstract_Indexable_Presenter;

/**
 * Represents the Googlebot-News tag presenter.
 */
class WPSEO_News_Googlebot_News_Presenter extends Abstract_Indexable_Tag_Presenter {
	
	/**
	 * The tag key name.
	 *
	 * @var string
	 */
	protected $key = 'Googlebot-News';

	/**
	 * The tag format including placeholders.
	 *
	 * @var string
	 */
	protected $tag_format = self::META_NAME_CONTENT;

	/**
	 * Get the value for the Googlebot-news meta value.
	 *
	 * @return string The raw value.
	 */
	public function get() {
		if ( $this->presentation->model->object_type !== 'post' ) {
			return '';
		}

		/**
		 * Allow for running additional code before adding the News header tags.
		 *
		 * @since 12.5.0
		 */
		do_action( 'Yoast\WP\News\head' );

		if ( $this->display_noindex( $this->presentation->source ) ) {
			return 'noindex';
		}

		return '';
	}

	/**
	 * Shows the meta-tag with noindex when it has been decided to exclude the post from Google News.
	 *
	 * @see https://support.google.com/news/publisher/answer/93977?hl=en
	 *
	 * @param WP_Post|array $post The post object.
	 *
	 * @return bool True when noindex tag should be rendered.
	 */
	protected function display_noindex( $post ) {
		/**
		 * Filter: 'wpseo_news_head_display_noindex' - Allow preventing of outputting noindex tag.
		 *
		 * @param object $post The post.
		 *
		 * @api        string $meta_robots The noindex tag.
		 *
		 * @deprecated 12.5.0. Use the {@see 'Yoast\WP\News\head_display_noindex'} filter instead.
		 */
		$display_noindex = apply_filters_deprecated(
			'wpseo_news_head_display_noindex',
			[ true, $post ],
			'YoastSEO News 12.5.0',
			'Yoast\WP\News\head_display_noindex'
		);

		/**
		 * Filter: 'Yoast\WP\News\head_display_noindex' - Allow preventing of outputting noindex tag.
		 *
		 * @param object $post The post.
		 *
		 * @api   string $meta_robots The noindex tag.
		 *
		 * @since 12.5.0
		 */
		$display_noindex = apply_filters( 'Yoast\WP\News\head_display_noindex', $display_noindex, $post );

		if ( empty( $display_noindex ) ) {
			return false;
		}

		$robots_index = WPSEO_Meta::get_value( 'newssitemap-robots-index', $post->ID );

		return ! empty( $robots_index );
	}
}
