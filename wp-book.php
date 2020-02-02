<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              one.wordpress.test
 * @since             1.0.0
 * @package           Wp_Book
 *
 * @wordpress-plugin
 * Plugin Name:       wp-book
 * Plugin URI:        one.wordpress.test
 * Description:       Assingment Plugin
 * Version:           1.0.0
 * Author:            Hardik Lodhari
 * Author URI:        one.wordpress.test
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-book
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_BOOK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-book-activator.php
 */
function activate_wp_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-book-activator.php';
	Wp_Book_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-book-deactivator.php
 */
function deactivate_wp_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-book-deactivator.php';
	Wp_Book_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_book' );
register_deactivation_hook( __FILE__, 'deactivate_wp_book' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-book.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_book() {

	$plugin = new Wp_Book();
	$plugin->run();

}
run_wp_book();

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
/****Custom Post Type Book */
function book_post_type() {

	$labels = array(
		'name'               => 'Books',
		'singular_name'      => 'Book',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'rewrite'            => array( 'slug' => 'book' ),
		'has_archive'        => true
	);

	register_post_type( 'book', $args );

}
add_action( 'init', 'book_post_type' );

/**** Custom Hierarchical taxonomy  **** */

add_action( 'init', 'book_category_hierarchical_taxonomy');

function book_category_hierarchical_taxonomy() {

  $labels = array(
    'name' => _x( 'Book Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Book Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Book Category' ),
    'all_items' => __( 'All Book Category' ),
    'parent_item' => __( 'Parent Book Category' ),
    'parent_item_colon' => __( 'Parent Book Category:' ),
    'edit_item' => __( 'Edit Book Category' ),
    'update_item' => __( 'Update Book Category' ),
    'add_new_item' => __( 'Add New Book Category' ),
    'new_item_name' => __( 'New Book Category Name' ),
    'menu_name' => __( 'Book Category' ),
  );

  register_taxonomy('Book Category',['book'], array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'Book Category' ),
  ));

}

/**** Custom Non-Hierarchical taxonomy  **** */

add_action( 'init', 'book_tag_nonhierarchical_taxonomy');

function book_tag_nonhierarchical_taxonomy() {

  $labels = array(
    'name' => _x( 'Book Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Book Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Book Tag' ),
    'all_items' => __( 'All Book Tag' ),
    'parent_item' => __( 'Parent Book Tag' ),
    'parent_item_colon' => __( 'Parent Book Tag:' ),
    'edit_item' => __( 'Edit Book Tag' ),
    'update_item' => __( 'Update Book Tag' ),
    'add_new_item' => __( 'Add New Book Tag' ),
    'new_item_name' => __( 'New Book Tag Name' ),
    'menu_name' => __( 'Book Tag' ),
  );

  register_taxonomy('Book Tag',array('book'), array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'Book Tag' ),
  ));

}

/***Custom Meta Box* */

function book_info_meta_box()
{
    $screens = ['book', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'box_id',           // Unique ID
            'Book Information',  // Box title
            'book_info_meta_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'book_info_meta_box');

function book_info_meta_box_html($post)
{
	?>
	<form method="post">
    <table align="center">
	<tr>
		<td><label for="author_nm">Author Name</label></td>
    	<td><input type="text" name="author_nm" id="author_nm"/></td>
	</tr>
	<tr>
		<td><label for="price">Price</label></td>
		<td><input type="text" name="price" id="price"/></td>
	</tr>
	<tr>
		<td><label for="publ">Publisher</label></td>
    	<td><input type="text" name="publ" id="publ"/></td>
	</tr>
	<tr>
		<td><label for="isbn">ISBN number</label></td>
    	<td><input type="text" name="isbn" id="isbn"/></td>
	</tr>
	<tr>
		<td><label for="year">Year</label></td>
    	<td><input type="text" name="year" id="year"/></td>
	</tr>
	<tr>
		<td><label for="edition">Edition</label></td>
    	<td><input type="text" name="edition" id="edition"/></td>
	</tr>
	<tr>
		<td><label for="url">Url</label></td>
		<td><input type="text" name="url" id="url"/></td>
	</tr>
	</table>
</form>

    <?php
}
if(array_key_exists('publish',$_POST))
{
	save_meta();
}
function save_meta()
{
	global $wpdb;
	$table = 'book_info';
	$a = $wpdb->insert($table, array('author_name' => $_POST['author_nm'], 'price' => $_POST['price'], 'publisher' => $_POST['publ'], 'isbn' => $_POST['isbn'], 'yrs' => $_POST['year'],'ed' => $_POST['edition'],'urls' => $_POST['url']));
}

/**Custom admin setting page* */

add_action( 'admin_menu', 'wp_add_admin_menu' );
add_action( 'admin_init', 'wp_settings_init' );

function wp_add_admin_menu(  ) {

	add_options_page( 'Book Setting', 'Book Setting', 'manage_options', 'wp-book', 'wp_options_page' );
	add_submenu_page('edit.php?post_type=book', 'Book Admin', 'Book Settings', 'edit_posts', basename(__FILE__), 'wp_options_page');
	

}

function wp_settings_init(  ) {

	register_setting( 'pluginPage', 'wp_book' );

	add_settings_section(
		'wp_pluginPage_section',
		__( 'Select Settings', 'wp' ),
		'wp_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'wp_select_field_0',
		__( 'Select Currency', 'wp' ),
		'wp_select_field_0_render',
		'pluginPage',
		'wp_pluginPage_section'
	);

	add_settings_field(
		'wp_select_field_1',
		__( 'Number Of books display per page', 'wp' ),
		'wp_select_field_1_render',
		'pluginPage',
		'wp_pluginPage_section'
	);


}

function wp_select_field_0_render(  ) {

	$options = get_option( 'wp_settings' );
	?>
	<select name='wp_settings[wp_select_field_0]'>
		<option value='1' <?php selected( $options['wp_select_field_0'], 1 ); ?>>AUD</option>
		<option value='2' <?php selected( $options['wp_select_field_0'], 2 ); ?>>USD</option>
		<option value='3' <?php selected( $options['wp_select_field_0'], 3 ); ?>>INR</option>
		<option value='4' <?php selected( $options['wp_select_field_0'], 4 ); ?>>EUR</option>
		<option value='5' <?php selected( $options['wp_select_field_0'], 5 ); ?>>AED</option>
	</select>

<?php

}

function wp_select_field_1_render(  ) {

	$options = get_option( 'wp_settings' );
	?>
	<select name='wp_settings[wp_select_field_1]'>
		<option value='5' <?php selected( $options['wp_select_field_1'], 1 ); ?>>5</option>
		<option value='10' <?php selected( $options['wp_select_field_1'], 2 ); ?>>10</option>
		<option value='20' <?php selected( $options['wp_select_field_1'], 3 ); ?>>20</option>
	</select>

<?php

}

function wp_settings_section_callback(  ) {

	echo __( '', 'wp' );

}

function wp_options_page(  ) {

		?>
		<form action='options.php' method='post'>

			<h2>wp-book</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

}

/**Short Code */
function create_book_shortcode($atts) {

	$atts = shortcode_atts(
		array(
			'id' => '',
			'author_name' => '',
			'year' => '',
			'category' => '',
			'tag' => '',
			'publisher' => '',
		),
		$atts,
		'book'
	);

	$id = $atts['id'];
	$author_name = $atts['author_name'];
	$year = $atts['year'];
	$category = $atts['category'];
	$tag = $atts['tag'];
	$publisher = $atts['publisher'];

}
add_shortcode( 'book', 'create_book_shortcode' );

/**Creating Custom meta table */
function book_create_table() 
{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$create = "CREATE TABLE `book_info` (
		ID INTEGER NOT NULL AUTO_INCREMENT,
		author_name TEXT NOT NULL,
		price bigint(64),
		publisher text DEFAULT '',
		isbn text DEFAULT '',
		yrs text  DEFAULT '',
		ed text  DEFAULT '',
		urls text  DEFAULT '',
	PRIMARY KEY (ID)
) $charset_collate;";

dbDelta($create);
	
}
  register_activation_hook( __FILE__, 'book_create_table' );


  global $wpdb;
  $table = 'book_info';
/** Custom Widget */
class custom_widget extends WP_Widget {

	private $selected_category;

	function __construct() {
		parent::__construct(
		// widget ID
		'custom_widget',
		// widget name
		__('Book Category', 'custom_widget_domain'),
		// widget description
		array ( 'description' => __( 'Custom Book Widget ', 'custom_widget_domain' ), )
		);
		}

		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			$termsn = get_categories('taxonomy=Book Category&show_count=1&');
			// Get the taxonomy's terms		
			echo '<form style="font-family: Open sans Regular;font-size: 25px;color:#62585f;text-shadow: 0px 2px #fffffff;" action="#" method="post">Select Book Category<hr><select name="category">';
			// Check if any term exists
			if ( ! empty( $termsn ) && is_array( $termsn ) )
			{
				// Run a loop and print them all
				?><?php
				foreach ( $termsn as $term )
				{ ?>
					
						<?php echo '<option>'.$term->name.'</option>';?>
					<?php
				}?>
				<?php
				
			echo '</select> <input type="submit" name="submit" value="Show Posts" /></form><br><br>';
			
		} 
			if(isset($_POST['submit']))
			{
				set_transient('selected_item',$_POST['category']);
			}
			global $wpdb;
			$transient_var_category = get_transient('selected_item');
			$categoryy = $wpdb->get_row("SELECT term_id FROM wp_terms WHERE name='$transient_var_category'");

			$wpb_all_query = new WP_Query(array('post_type'=>'book', 'post_status'=>'publish', 'posts_per_page'=>-1,'tax_query' => array(
				array('taxonomy' => 'Book Category',
				'field' => 'id',
				'terms' => (int)$categoryy->term_id
				
				)))); ?>
 
			<?php if ( $wpb_all_query->have_posts() ) : ?>
			 
			<ul style="color:#ef4634;">
			 
				<!-- the loop -->
				<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post( ); ?>
					<li><a style="color:#ef4634;" href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></li>
				<?php endwhile; ?>
				<!-- end of the loop -->
			 
			</ul>
			 
				<?php wp_reset_postdata(); ?>
			 
			<?php else : ?>
				<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
			<?php endif; 
		}
			
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) )
			$title = $instance[ 'title' ];
			else
			$title = __( 'Default Title', 'hstngr_widget_domain' );
			?>
			<p>
		<?php
						
		?>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php
			}

			public function update( $new_instance, $old_instance ) {
				$instance = array();
				$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
				return $instance;
				}
} 

function register_custom_widget()
{
	register_widget( 'custom_widget' );
}

add_action( 'widgets_init', 'register_custom_widget' );



/** Dashboard Widget */

function add_dashboard_widget() 
{
	wp_add_dashboard_widget(
		'wporg_dashboard_widget',                          // Widget slug.
		esc_html__( 'TOP 5 Book Categories', 'dbook' ), // Title.
		'dashboard_widget_data'                    // Display function.
	); 
}
add_action( 'wp_dashboard_setup', 'add_dashboard_widget' );
 

function dashboard_widget_data() {
	// Display whatever you want to show.
	?>
	<?php

	// Get the taxonomy's terms
		$terms = get_terms(
			array(
				'taxonomy'   => 'Book Category',
				'hide_empty' => false,
				'orderby' => 'count',
				'order' => 'DESC',
				'number' => 5,
				'count' => true
			)
		);

		// Check if any term exists
		if ( ! empty( $terms ) && is_array( $terms ) ) {
			// Run a loop and print them all
			?><ul><?php
			foreach ( $terms as $term ) { ?>
				<li>
					<?php echo $term->name.' ['.$term->count.']<br>'; ?>
				</li><?php
			}?>
			</ul><?php
		}

			
}