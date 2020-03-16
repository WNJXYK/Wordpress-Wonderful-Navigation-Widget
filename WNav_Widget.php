<?php
/**
 * Widget API: Wonderful_Nav_Widget class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

class WNav_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description'                 => __( '添加一个优雅的可折叠导航菜单到您的边栏' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'wnav', __( '导航菜单栏' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		if ( ! $nav_menu ) return;
		
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) echo $args['before_title'] . "<i class=\"wnav-icon\"></i> " . $title . $args['after_title'];

		
		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu' => $nav_menu,
			'menu_class' => 'wnav-widget',
			'items_wrap'  => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			"walker" => new class extends Walker_Nav_Menu{
			    function start_lvl(&$output, $depth = 0, $args = array() ) {
			    	$output .= "<a class=\"wnav-widget-icon wnav-widget-fold\"><i class=\"wnav-fold\"></i></a>";
			    	$output .= "<a class=\"wnav-widget-icon wnav-widget-open\"><i class=\"wnav-open\"></i></a>";
			    	$output .= "<ul class=\"wnav-widget-sub\">\n";
			    }
			}
		);
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );

		echo $args['after_widget'];
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) $instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( ! empty( $new_instance['nav_menu'] ) )  $instance['nav_menu'] = (int) $new_instance['nav_menu'];
		return $instance;
	}

	public function form( $instance ) {
		global $wp_customize;
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		$empty_menus_style     = '';
		$not_empty_menus_style = '';
		if ( empty( $menus ) ) {
			$empty_menus_style = ' style="display:none" ';
		} else {
			$not_empty_menus_style = ' style="display:none" ';
		}

		$nav_menu_style = '';
		if ( ! $nav_menu ) {
			$nav_menu_style = 'display: none;';
		}

		// If no menus exists, direct the user to go and create some.
		?>
		<p class="nav-menu-widget-no-menus-message" <?php echo $not_empty_menus_style; ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}

			/* translators: %s: URL to create a new menu. */
			printf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) );
			?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php echo $empty_menus_style; ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
				<p class="edit-selected-nav-menu" style="<?php echo $nav_menu_style; ?>">
					<button type="button" class="button"><?php _e( 'Edit Menu' ); ?></button>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}

add_action( 'widgets_init', 'wnav_widget_init' );
function wnav_widget_init() { register_widget( 'WNav_Widget' ); }

?>