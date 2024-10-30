<?php

/**
Plugin Name: Coursecheck
Plugin URI: https://wordpress.org/plugins/coursecheck/
Description: Reviews widget for training providers using coursecheck.com to collect student feedback.
Version: 1.10.12
Author: South Coast Online Solutions
Author URI: https://south-coast.online
License: GPLv3
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

define('COURSECHECK_PLUGIN_VERSION', '1.10.12');

$coursecheck_settings = array(
	'version' => COURSECHECK_PLUGIN_VERSION,
	'defaults' => array(
		'blog_charset' => get_option('blog_charset'),
		'date_format' => get_option('date_format')
	)
);

/**
	Coursecheck meta box
 */
class Coursecheck_Meta_Box
{
	private $config = '{"title":"Coursecheck","description":"Enter your coursecheck CourseID for this page.","prefix":"coursecheck_","domain":"coursecheck","class_name":"Coursecheck_Meta_Box","post-type":["post","page"],"context":"side","priority":"high","fields":[{"type":"number","label":"CourseID:","step":"1","id":"coursecheck_courseid"}]}';

	public function __construct()
	{
		$this->config = json_decode($this->config, true);
		add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
		add_action('admin_head', [$this, 'admin_head']);
		add_action('save_post', [$this, 'save_post']);
	}

	public function add_meta_boxes()
	{
		foreach ($this->config['post-type'] as $screen) {
			add_meta_box(
				sanitize_title($this->config['title']),
				$this->config['title'],
				[$this, 'add_meta_box_callback'],
				$screen,
				$this->config['context'],
				$this->config['priority']
			);
		}
	}

	public function admin_head()
	{
		global $typenow;
		if (in_array($typenow, $this->config['post-type'])) {
?><?php
			}
		}

		public function save_post($post_id)
		{
			foreach ($this->config['fields'] as $field) {
				switch ($field['type']) {
					default:
						if (isset($_POST[$field['id']])) {
							$sanitized = sanitize_text_field($_POST[$field['id']]);
							update_post_meta($post_id, $field['id'], $sanitized);
						}
				}
			}
		}

		public function add_meta_box_callback()
		{
			echo '<div style="padding-bottom:8px;">' . esc_html($this->config['description']) . '</div>';
			$this->fields_div();
		}

		private function fields_div()
		{
			foreach ($this->config['fields'] as $field) {
				?><div class="components-base-control">
	<div class="components-base-control__field"><?php
												$this->label($field);
												$this->field($field);
												?></div>
</div><?php
			}
		}

		private function label($field)
		{
			switch ($field['type']) {
				default:
					printf(
						'<label class="components-base-control__label" for="%s" style="display:inline-block;padding-bottom:8px;">%s</label>',
						$field['id'],
						$field['label']
					);
			}
		}

		private function field($field)
		{
			switch ($field['type']) {
				case 'number':
					$this->input_minmax($field);
					break;
				default:
					$this->input($field);
			}
		}

		private function input($field)
		{
			printf(
				'<input class="components-text-control__input %s" id="%s" name="%s" %s type="%s" value="%s">',
				isset($field['class']) ? $field['class'] : '',
				$field['id'],
				$field['id'],
				isset($field['pattern']) ? "pattern='{$field['pattern']}'" : '',
				$field['type'],
				$this->value($field)
			);
		}

		private function input_minmax($field)
		{
			printf(
				'<input class="components-text-control__input" id="%s" %s %s name="%s" %s type="%s" value="%s">',
				$field['id'],
				isset($field['max']) ? "max='{$field['max']}'" : '',
				isset($field['min']) ? "min='{$field['min']}'" : '',
				$field['id'],
				isset($field['step']) ? "step='{$field['step']}'" : '',
				$field['type'],
				$this->value($field)
			);
		}

		private function value($field)
		{
			global $post;
			if (metadata_exists('post', $post->ID, $field['id'])) {
				$value = get_post_meta($post->ID, $field['id'], true);
			} else if (isset($field['default'])) {
				$value = $field['default'];
			} else {
				return '';
			}
			return str_replace('\u0027', "'", $value);
		}
	}
	new Coursecheck_Meta_Box;

	/**
	 * Coursecheck widget
	 */
	class Coursecheck_Widget extends WP_Widget
	{

		// Main constructor
		public function __construct()
		{
			parent::__construct(
				'Coursecheck_Widget',
				__('Coursecheck Widget', 'coursecheck'),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		// The widget form (for the backend )
		public function form($instance)
		{

			// Set widget defaults
			$defaults = array(
				'title'    => '',
				'company_id'     => ''
			);

			// Parse current settings with defaults
			extract(wp_parse_args((array) $instance, $defaults)); ?>

<?php // Widget Title 
?>
<p>
	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'coursecheck'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>

<?php // CompanyID 
?>
<p>
	<label for="<?php echo esc_attr($this->get_field_id('company_id')); ?>"><?php _e('CompanyID:', 'coursecheck'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('company_id')); ?>" name="<?php echo esc_attr($this->get_field_name('company_id')); ?>" type="number" value="<?php echo esc_attr($company_id); ?>" />
</p>

<?php }

		// Update widget settings
		public function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
			$instance['company_id'] = isset($new_instance['company_id']) ? wp_strip_all_tags($new_instance['company_id']) : '';
			return $instance;
		}

		// Display the widget
		public function widget($args, $instance)
		{
			global $post;
			extract($args);

			// Check the widget options
			$title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
			// Get company id from widget
			$company_id = isset($instance['company_id']) ? $instance['company_id'] : '';
			// Get course id from post meta coursecheck_courseid
			$course_id = get_post_meta($post->ID, 'coursecheck_courseid', true);

			// WordPress core before_widget hook (always include )
			echo $before_widget;

			// Display widget title if defined
			if ($title) {
				echo $before_title . esc_html($title) . $after_title;
			}
			// Display coursecheck widget
			if (is_numeric($course_id) && $course_id > 0) {
				echo coursecheck_cchk_course_html($course_id);
			} elseif (is_numeric($company_id) && $company_id > 0) {
				echo coursecheck_cchk_company_html($company_id);
			} else {
				echo _e('Coursecheck widget requires company_id or course_id', 'coursecheck');
			}

			// WordPress core after_widget hook (always include )
			echo $after_widget;
		}
	}

	/**
	 * Coursecheck recent reviews widget
	 */
	class Coursecheck_Recent_Reviews extends WP_Widget
	{

		// Main constructor
		public function __construct()
		{
			parent::__construct(
				'Coursecheck_Recent_Reviews',
				__('Coursecheck Recent Reviews', 'coursecheck'),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		// The widget form (for the backend )
		public function form($instance)
		{

			// Set widget defaults
			$defaults = array(
				'title'    => 'Recent Reviews',
				'company_id'     => '',
				'num_reviews'     => '5'
			);

			// Parse current settings with defaults
			extract(wp_parse_args((array) $instance, $defaults)); ?>

	<?php // Widget Title 
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'coursecheck'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>

	<?php // CompanyID 
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('company_id')); ?>"><?php _e('CompanyID:', 'coursecheck'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('company_id')); ?>" name="<?php echo esc_attr($this->get_field_name('company_id')); ?>" type="number" value="<?php echo esc_attr($company_id); ?>" />
	</p>

	<?php // NumReviews 
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('num_reviews')); ?>"><?php _e('Number of reviews to show:', 'coursecheck'); ?></label>
		<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_reviews')); ?>" name="<?php echo esc_attr($this->get_field_name('num_reviews')); ?>" type="number" min="1" max="10" value="<?php echo esc_attr($num_reviews); ?>" />
	</p>

<?php }

		// Update widget settings
		public function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
			$instance['company_id'] = isset($new_instance['company_id']) ? wp_strip_all_tags($new_instance['company_id']) : '';
			$instance['num_reviews'] = isset($new_instance['num_reviews']) ? wp_strip_all_tags($new_instance['num_reviews']) : '';
			return $instance;
		}

		// Display the widget
		public function widget($args, $instance)
		{
			global $coursecheck_settings;
			extract($args);

			// Check the widget options
			$title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
			// Get data from widget
			$company_id = isset($instance['company_id']) ? $instance['company_id'] : '';
			$num_reviews = isset($instance['num_reviews']) ? $instance['num_reviews'] : '';

			// WordPress core before_widget hook (always include )
			echo $before_widget;

			// Display widget title if defined
			if ($title) {
				echo $before_title . esc_html($title) . $after_title;
			}

			// Display coursecheck recent reviews list
			if (is_numeric($company_id) && $company_id > 0) {
				echo coursecheck_cchk_reviews_list_html($company_id, $num_reviews);
			} else {
				echo _e('Coursecheck recent reviews widget requires company_id', 'coursecheck');
			}

			// WordPress core after_widget hook (always include )
			echo $after_widget;
		}
	}

	// Register the widgets
	if (!function_exists('coursecheck_cchk_register_widgets')) {
		function coursecheck_cchk_register_widgets()
		{
			register_widget('Coursecheck_Widget');
			register_widget('Coursecheck_Recent_Reviews');
		}
		add_action('widgets_init', 'coursecheck_cchk_register_widgets');
	}

	/**
	 * The coursecheck widget shortcode.  Accepts company_id or course_id attributes
	 *
	 * @param array  $atts     Shortcode attributes. Default empty.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_shortcode')) {
		function coursecheck_cchk_shortcode($atts = [])
		{
			// normalize attribute keys, lowercase
			$atts = array_change_key_case((array) $atts, CASE_LOWER);

			// override default attributes with user attributes
			$a = shortcode_atts(
				array(
					'company_id' => 0,
					'course_id' => 0
				),
				$atts
			);
			// build the widegt html
			if (count($a) > 0) {
				if (is_numeric($a['course_id']) && $a['course_id'] > 0) {
					$return = coursecheck_cchk_course_html($a['course_id']);
				} elseif (is_numeric($a['company_id']) && $a['company_id'] > 0) {
					$return = coursecheck_cchk_company_html($a['company_id']);
				} else {
					$return = __('Coursecheck shortcode requires company_id or course_id', 'coursecheck');
				}
			} else {
				$return = __('Coursecheck shortcode requires company_id or course_id', 'coursecheck');
			}

			// return output
			return $return;
		}
		add_shortcode('coursecheck', 'coursecheck_cchk_shortcode');
	}

	/**
	 * The coursecheck reviews carousel shortcode.  Accepts company_id attribute and gets latest reviews from coursecheck servers
	 *
	 * @param array  $atts     Shortcode attributes. Default empty.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_reviews_shortcode')) {
		function coursecheck_cchk_reviews_shortcode($atts = [])
		{
			// normalize attribute keys, lowercase
			$atts = array_change_key_case((array) $atts, CASE_LOWER);

			// override default attributes with user attributes
			$a = shortcode_atts(
				array(
					'company_id' => 0,
					'course_id' => 0,
					'num_reviews' => '10', // default numer of reviews to display
					'speed' => '5', // slider default speed in seconds
					'display' => 'carousel', // 'carousel/list'
					'layout' => 'list', // 'list/columns'
				),
				$atts
			);
			// get the relevant html
			if (is_numeric($a['company_id']) && $a['company_id'] > 0) {
				if ($a['display'] == 'list') {
					$return = coursecheck_cchk_reviews_list_html($a['company_id'], $a['num_reviews'], $a['course_id'], $a['layout']);
				} else {
					$return = coursecheck_cchk_reviews_carousel_html($a['company_id'], $a['num_reviews'], $a['speed'], $a['course_id']);
				}
			} else {
				$return = __('Coursecheck latest reviews shortcode requires company_id', 'coursecheck');
			}

			// return output
			return $return;
		}
		add_shortcode('coursecheck_reviews', 'coursecheck_cchk_reviews_shortcode');
	}

	/**
	 * HTML builder for company widget
	 *
	 * @param int  $company_id     Coursecheck CompanyID. Default 0.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_company_html')) {
		function coursecheck_cchk_company_html($company_id = 0)
		{
			global $coursecheck_settings;
			if (is_numeric($company_id) && $company_id > 0) {
				$return = '<!-- Coursecheck plugin v.' . esc_html(COURSECHECK_PLUGIN_VERSION) . ' wordpress.org/plugins/coursecheck/ -->';
				$return .= '<div id="cchk-widget" style="width:160px;"><a href="https://www.coursecheck.com/reviews/provider/' . esc_attr($company_id) . '?utm_source=wp_plugin">View reviews</a></div>';
				// register/enqueue the relevant js file from coursecheck server
				wp_enqueue_script('coursecheck-cchk-company', 'https://www.coursecheck.com/widget/rating/' . esc_attr($company_id) . '.js', false, COURSECHECK_PLUGIN_VERSION, true);
			} else {
				$return = __("Coursecheck widget requires company_id", "coursecheck");
			}
			// make return string safe using wp_kses
			$allowed_html = wp_kses_allowed_html('post');
			return wp_kses($return, $allowed_html);
		}
	}

	/**
	 * HTML builder for course widget
	 *
	 * @param int  $course_id     Coursecheck CourseID. Default 0.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_course_html')) {
		function coursecheck_cchk_course_html($course_id = 0)
		{
			global $coursecheck_settings;
			if (is_numeric($course_id) && $course_id > 0) {
				$return = $return = '<!-- Coursecheck plugin v.' . esc_html(COURSECHECK_PLUGIN_VERSION) . ' wordpress.org/plugins/coursecheck/ -->';
				$return .= '<div id="cchk-widget" style="width:160px;"><a href="https://www.coursecheck.com/reviews/course/' . esc_attr($course_id) . '?utm_source=wp_plugin">View reviews</a></div>';
				// register/enqueue the relevant js file from coursecheck server
				wp_enqueue_script('coursecheck-cchk-course', 'https://www.coursecheck.com/widget/course/' . esc_attr($course_id) . '.js', false, COURSECHECK_PLUGIN_VERSION, true);
			} else {
				$return = __("Coursecheck widget requires course_id", "coursecheck");
			}
			// make return string safe using wp_kses
			$allowed_html = wp_kses_allowed_html('post');
			return wp_kses($return, $allowed_html);
		}
	}

	/**
	 * HTML builder for company latest reviews list
	 *
	 * @param int  $company_id     Coursecheck CompanyID. Default 0.
	 * @param int  $num_reviews    Number of reviews to display. Default 5.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_reviews_list_html')) {
		function coursecheck_cchk_reviews_list_html($company_id = 0, $num_reviews = 5, $course_id = 0, $layout = 'list')
		{
			global $coursecheck_settings;
			if (is_numeric($company_id) && $company_id > 0) {
				$return = $return = '<!-- Coursecheck plugin v.' . esc_html(COURSECHECK_PLUGIN_VERSION) . ' wordpress.org/plugins/coursecheck/ -->';
				$reviews = coursecheck_cchk_get_reviews($company_id, $course_id);
				if (is_wp_error($reviews)) {
					$return = $reviews->get_error_message();
				} else {
					if (count($reviews['reviews']) > 0) {
						// enqueue css & javascript
						wp_enqueue_style('coursecheck-recent-reviews', plugins_url('/public/css/recent-reviews.css', __FILE__), false, COURSECHECK_PLUGIN_VERSION, 'all');
						if ($layout == 'columns') {
							$return .= '<div class="cchk-recent-reviews-columns">';
						} else {
							$return .= '<div class="cchk-recent-reviews">';
						}
						for ($i = 0; $i < $num_reviews; $i++) {
							if ($reviews['reviews'][$i]['rating']) {
								$return .= '<div class="cchk-recent-review">
									<div class="cchk-recent-rating cchk-recent-rating-' . esc_html($reviews['reviews'][$i]['rating']) . '">
										<div class="cchk-stars"><span></span><span></span><span></span><span></span><span></span></div>
									</div>';
								if (strlen($reviews['reviews'][$i]['course']) > 1) {
									$return .= '<div class="cchk-recent-course">' . esc_html($reviews['reviews'][$i]['course']) . '</div>';
								}
								$return .= '<div class="cchk-recent-comment">
										<div>' . esc_html($reviews['reviews'][$i]['comment']) . '</div>
									</div>
									<div class="cchk-recent-credits">
										<span class="cchk-recent-author">' . esc_html($reviews['reviews'][$i]['name']) . '</span>
										<span class="cchk-recent-date">
										' . esc_html(wp_date($coursecheck_settings['defaults']['date_format'], strtotime($reviews['reviews'][$i]['date']))) . '
										</span>
									</div>
								</div>';
							}
						}
						$return .= '</div>';
						if ((int)$course_id > 0) {
							$return .= '<div class="cchk-recent-more"><a href="https://www.coursecheck.com/reviews/course/' . esc_attr($course_id) . '?utm_source=wp_plugin" target="_blank">' . __('Read more reviews', 'coursecheck') . '</a></div>';
						} else {
							$return .= '<div class="cchk-recent-more"><a href="https://www.coursecheck.com/reviews/provider/' . esc_attr($company_id) . '?utm_source=wp_plugin" target="_blank">' . __('Read more reviews', 'coursecheck') . '</a></div>';
						}
					} else {
						$return .= __("There are currently no reviews for this provider", "coursecheck");
					}
				}
			} else {
				$return = __("Coursecheck latest reviews shortcode requires course_id", "coursecheck");
			}
			// make return string safe using wp_kses
			$allowed_html = wp_kses_allowed_html('post');
			return wp_kses($return, $allowed_html);
		}
	}

	/**
	 * HTML builder for company latest reviews carousel
	 *
	 * @param int  $company_id     Coursecheck CompanyID. Default 0.
	 *
	 * @return string
	 */
	if (!function_exists('coursecheck_cchk_reviews_carousel_html')) {
		function coursecheck_cchk_reviews_carousel_html($company_id = 0, $num_reviews = 10, $speed = 5, $course_id = 0)
		{
			global $coursecheck_settings;
			if (is_numeric($company_id) && $company_id > 0) {
				$return = $return = '<!-- Coursecheck plugin v.' . esc_html(COURSECHECK_PLUGIN_VERSION) . ' wordpress.org/plugins/coursecheck/ -->';
				$reviews = coursecheck_cchk_get_reviews($company_id, $course_id);
				if (is_wp_error($reviews)) {
					$return = $reviews->get_error_message();
				} else {
					if (count($reviews['reviews']) > 0) {
						if (is_numeric($speed) && $speed > 0) {
							$speed = $speed * 1000; // multiply by 1000 as slider needs milliseconds
						} else {
							$speed = 5000; // default if no valid speed specified
						}
						$return .= '<div class="cchk-carousel">';
						$return .= "<div class='cchk-slick' data-slick='{\"autoplaySpeed\": \"" . esc_attr($speed) . "\"}'>";
						// enqueue css & javascript
						wp_enqueue_style('coursecheck-slick', plugins_url('/public/css/slick.css', __FILE__), false, COURSECHECK_PLUGIN_VERSION, 'all');
						wp_enqueue_script('coursecheck-slick', plugins_url('/public/js/slick.min.js', __FILE__), array('jquery'), COURSECHECK_PLUGIN_VERSION, true);
						wp_enqueue_script('coursecheck-slick-init', plugins_url('/public/js/slick-init.js', __FILE__), array('coursecheck-slick'), COURSECHECK_PLUGIN_VERSION, true);
						for ($i = 0; $i < $num_reviews; $i++) {
							if ($reviews['reviews'][$i]['rating']) {
								$return .= '<div class="slick-slide">
											<div class="slick-author">
											' . esc_html($reviews['reviews'][$i]['name']) . '
											</div>
											<div class="slick-date">
												' . esc_html(wp_date($coursecheck_settings['defaults']['date_format'], strtotime($reviews['reviews'][$i]['date']))) . '
											</div>
											<div class="slick-rating slick-rating-' . esc_attr($reviews['reviews'][$i]['rating']) . '">
												<div class="slick-stars">
													<span></span><span></span><span></span><span></span><span></span>
												</div>
											</div>';
								if (strlen($reviews['reviews'][$i]['course']) > 1) {
									$return .= '<div class="slick-course">' . esc_html($reviews['reviews'][$i]['course']) . '</div>';
								}
								$return .= '<div class="slick-comment">' . esc_html($reviews['reviews'][$i]['comment']) . '</div>
										</div>';
							}
						}
						$return .= '</div>';
						if ((int)$course_id > 0) {
							$return .= '<div class="slick-more"><a href="https://www.coursecheck.com/reviews/course/' . esc_attr($course_id) . '?utm_source=wp_plugin" target="_blank">' . __('Read more reviews', 'coursecheck') . '</a></div>';
						} else {
							$return .= '<div class="slick-more"><a href="https://www.coursecheck.com/reviews/provider/' . esc_attr($company_id) . '?utm_source=wp_plugin" target="_blank">' . __('Read more reviews', 'coursecheck') . '</a></div>';
						}
						$return .= '</div>';
					} else {
						$return .= __("There are currently no reviews for this provider", "coursecheck");
					}
				}
			} else {
				$return = __("Coursecheck latest reviews shortcode requires company_id", "coursecheck");
			}
			// make return string safe using wp_kses
			$allowed_html = wp_kses_allowed_html('post');
			return wp_kses($return, $allowed_html);
		}
	}

	/**
	 * Get json reviews feed from coursecheck server
	 *
	 * @param int  $company_id Coursecheck CompanyID. Default 0.
	 *
	 * @return array
	 */
	if (!function_exists('coursecheck_cchk_get_reviews')) {
		function coursecheck_cchk_get_reviews($company_id = 0, $course_id = 0)
		{
			global $coursecheck_settings;
			if (is_numeric($company_id) && $company_id > 0) {
				// get the json file from coursecheck servers
				$url = "https://www.coursecheck.com/api/provider/reviews/" . $company_id;
				// append course_id filter to api call if present
				if (is_numeric($course_id) && $course_id > 0) {
					$url .= "?course=" . $course_id;
				}
				$response = wp_remote_get(esc_url_raw($url));
				if (!is_wp_error($response) && $response['response']['code'] == 200) {
					$json = wp_remote_retrieve_body($response);
					// coursecheck feed contains all sorts of character sets so attempt to convert them to UTF-8 that json should be
					//$json = iconv('CP1252','UTF-8//IGNORE', $json); 
					$json = mb_convert_encoding($json, 'UTF-8', mb_detect_encoding($json));
					$return = json_decode($json, true);
				} else {
					$return = new WP_Error('coursecheck_cchk_get_reviews', __("Could not retrieve reviews from coursecheck server", "coursecheck"));
				}
			} else {
				$return = new WP_Error('coursecheck_cchk_get_reviews', __("Coursecheck latest reviews requires company_id", "coursecheck"));
			}
			return $return;
		}
	}
