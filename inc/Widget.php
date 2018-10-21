<?php
/**
 * @package NewsRecommendations
 */

namespace NewsRecommendations;

use WP_Query;
use WP_Widget;

/**
 * Ad widget class.
 */
class Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'news-recommendations',
			__( 'News Recommendations', 'news-recommendations' )
		);
	}

	public $args = [
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	];

	/**
	 * Displays the ad widget on the front end.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ): void {
		$query_args = [
			'post_type'      => POST_TYPE_NAME,
			'posts_per_page' => absint( $instance['number_of_items']  ),
		];

		/**
		 * Filters the query args in the News Recommendations widget.
		 *
		 * @param array $query_args WP_Query args.
		 */
		$query = new WP_Query( apply_filters( 'news-recommendations.widget_query_args', $query_args ) );

		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo '<div class="widget-content">';

		$template_part = [ 'templates/widget', 'recommendation' ];

		/**
		 * Filters the theme template part used in the News Recommendations widget.
		 *
		 * @see get_template_part()
		 *
		 * @param string|array $template_part Template part passed to get_template_part()
		 */
		$template_part = apply_filters( 'news-recommendations.widget_template_part', $template_part );

		while ( $query->have_posts() ) {
			$query->the_post();

			call_user_func_array( 'get_template_part', (array) $template_part );
		}

		wp_reset_postdata();

		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * Displays the widget's settings form.
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ): void {
		$title           = $instance['title'] ?? _x( 'Recommendations', 'default widget title', 'news-recommendations' );
		$number_of_items = $instance['number_of_items'] ?? 10;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title', 'news-recommendations' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_items' ) ); ?>">
				<?php esc_attr_e( 'Number of items', 'news-recommendations' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'number_of_items' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'number_of_items' ) ); ?>"
				type="number"
				min="1"
				value="<?php echo esc_attr( (int) $number_of_items ); ?>"
			/>
		</p>
		<p class="help">
			<?php _e( 'Choose the number of recommendations you want to display.', 'news-recommendations' ); ?>
		</p>
		<?php

	}

	/**
	 * Handles widget settings updates.
	 *
	 * @param array $new_instance Old widget instance.
	 * @param array $old_instance New widget instance.
	 * @return array Updated widget instance.
	 */
	public function update( $new_instance, $old_instance ): array {
		$instance = [];

		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number_of_items']   = isset( $new_instance['number_of_items'] ) ? sanitize_text_field( $new_instance['number_of_items'] ) : '';

		return $instance;
	}

}
