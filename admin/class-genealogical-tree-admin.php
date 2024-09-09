<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */
namespace Zqe;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   public
     * @var      Genealogical_Tree    $plugin    The ID of this plugin.
     */
    public $plugin;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string $plugin       The name of this plugin.
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin->name . '-select2-css',
            plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
            array(),
            $this->plugin->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-admin.css',
            array('wp-color-picker'),
            $this->plugin->version,
            'all'
        );
    }

    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin->name . '-select2-js',
            plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
            array('jquery', 'wp-color-picker'),
            $this->plugin->version,
            true
        );
        wp_enqueue_script(
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-admin.js',
            array(
                'jquery',
                'wp-color-picker',
                'jquery-ui-sortable',
                $this->plugin->name . '-select2-js'
            ),
            $this->plugin->version,
            true
        );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        if ( !did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_localize_script( $this->plugin->name, 'gt_ajax_var', array(
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'gt_ajax_nonce' ),
        ) );
    }

    /**
     * It registers the custom post types and taxonomies.
     *
     * @since    1.0.0
     */
    public function init_post_type_and_taxonomy() {
        $labels = array(
            'name'               => _x( 'Members', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Member', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Members', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Member', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'member', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Member', 'genealogical-tree' ),
            'new_item'           => __( 'New Member', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Member', 'genealogical-tree' ),
            'view_item'          => __( 'View Member', 'genealogical-tree' ),
            'all_items'          => __( 'Members', 'genealogical-tree' ),
            'search_items'       => __( 'Search Members', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Members:', 'genealogical-tree' ),
            'featured_image'     => __( 'Member Image', 'genealogical-tree' ),
            'set_featured_image' => __( 'Set Member Image', 'genealogical-tree' ),
            'not_found'          => __( 'No members found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No members found in Trash.', 'genealogical-tree' ),
        );
        $supports = array('title', 'author', 'revisions');
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            array_push( $supports, 'custom-fields' );
        }
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'genealogical-tree',
            'query_var'          => true,
            'rewrite'            => array(
                'slug' => 'gt-member',
            ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        register_post_type( 'gt-member', $args );
        $labels = array(
            'name'               => _x( 'Families', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Family', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Families', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Family', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'family', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Family', 'genealogical-tree' ),
            'new_item'           => __( 'New Family', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Family', 'genealogical-tree' ),
            'view_item'          => __( 'View Family', 'genealogical-tree' ),
            'all_items'          => __( 'Families', 'genealogical-tree' ),
            'search_items'       => __( 'Search Families', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Families:', 'genealogical-tree' ),
            'not_found'          => __( 'No families found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No families found in Trash.', 'genealogical-tree' ),
        );
        $supports = array('title', 'author', 'revisions');
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            array_push( $supports, 'custom-fields' );
        }
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array(
                'slug' => 'gt-family',
            ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            $args['show_in_menu'] = 'genealogical-tree';
        }
        register_post_type( 'gt-family', $args );
        $labels = array(
            'name'               => _x( 'Trees', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Tree', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Trees', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Tree', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'tree', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Tree', 'genealogical-tree' ),
            'new_item'           => __( 'New Tree', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Tree', 'genealogical-tree' ),
            'view_item'          => __( 'View Tree', 'genealogical-tree' ),
            'all_items'          => __( 'Trees', 'genealogical-tree' ),
            'search_items'       => __( 'Search Trees', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Trees:', 'genealogical-tree' ),
            'not_found'          => __( 'No trees found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No trees found in Trash.', 'genealogical-tree' ),
        );
        $supports = array('title', 'author', 'revisions');
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            array_push( $supports, 'custom-fields' );
        }
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'genealogical-tree',
            'query_var'          => true,
            'rewrite'            => array(
                'slug' => 'gt-tree',
            ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        register_post_type( 'gt-tree', $args );
        $labels = array(
            'name'                       => _x( 'Family Groups', 'genealogical-tree', 'genealogical-tree' ),
            'singular_name'              => _x( 'Family Group', 'taxonomy singular name', 'genealogical-tree' ),
            'search_items'               => __( 'Search Family Groups', 'genealogical-tree' ),
            'popular_items'              => __( 'Popular Family Groups', 'genealogical-tree' ),
            'all_items'                  => __( 'All Family Groups', 'genealogical-tree' ),
            'parent_item'                => __( 'Parent Family Group', 'genealogical-tree' ),
            'parent_item_colon'          => __( 'Parent Family Group', 'genealogical-tree' ),
            'edit_item'                  => __( 'Edit Family Group', 'genealogical-tree' ),
            'update_item'                => __( 'Update Family Group', 'genealogical-tree' ),
            'add_new_item'               => __( 'Add New Group', 'genealogical-tree' ),
            'new_item_name'              => __( 'New Group Name', 'genealogical-tree' ),
            'separate_items_with_commas' => __( 'Separate family group with commas', 'genealogical-tree' ),
            'add_or_remove_items'        => __( 'Add or remove family group', 'genealogical-tree' ),
            'choose_from_most_used'      => __( 'Choose from the most used family group', 'genealogical-tree' ),
            'not_found'                  => __( 'No family group found.', 'genealogical-tree' ),
            'menu_name'                  => __( 'Family Groups', 'genealogical-tree' ),
        );
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array(
                'slug' => 'gt-family-group',
            ),
        );
        register_taxonomy( 'gt-family-group', array('gt-member', 'gt-family'), $args );
    }

    /**
     * It adds a menu item to the admin menu
     *
     * @since    1.0.0
     */
    public function admin_menu() {
        add_menu_page(
            __( 'Genealogical Tree', 'genealogical-tree' ),
            __( 'Genealogical Tree', 'genealogical-tree' ),
            'manage_categories',
            'genealogical-tree',
            function () {
            },
            plugin_dir_url( __FILE__ ) . 'img/menu-icon.png',
            4
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Genealogical Tree', 'genealogical-tree' ),
            __( 'Genealogical Tree', 'genealogical-tree' ),
            'manage_categories',
            'genealogical-tree',
            function () {
                require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-admin-dashboard.php';
            },
            0
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Family Group', 'genealogical-tree' ),
            __( 'Family Group', 'genealogical-tree' ),
            'manage_categories',
            'edit-tags.php?taxonomy=gt-family-group&post_type=gt-member',
            null,
            1
        );
    }

    /**
     * It adds meta boxes to the gt-member post type
     *
     * Long Description.
     *
     * @param string $post_type The post type slug.
     * @param object $post  The post object..
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_member( $post_type, $post ) {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        add_meta_box(
            'genealogical-tree-meta-box-member-info',
            __( 'Member Info', 'genealogical-tree' ),
            array($this, 'render_meta_box_member_info'),
            'gt-member',
            'normal',
            'high'
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-member-debug',
                __( 'Member Debug', 'genealogical-tree' ),
                array($this, 'render_meta_box_member_debug'),
                'gt-member',
                'normal',
                'high'
            );
        }
    }

    /**
     * It adds a meta box to the Family post type
     *
     * Long Description.
     *
     * @param string $post_type The post type of the current post.
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_family( $post_type, $post ) {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-family-debug',
                __( 'Family info', 'genealogical-tree' ),
                array($this, 'render_meta_box_family_debug'),
                'gt-family',
                'normal',
                'high'
            );
        }
    }

    /**
     * It adds a meta box to the tree post type
     *
     * Long Description.
     *
     * @param string $post_type The post type of the current post.
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_tree( $post_type, $post ) {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        add_meta_box(
            'genealogical-tree-meta-box-tree-settings',
            __( 'Tree Settings', 'genealogical-tree' ),
            array($this, 'render_meta_box_tree_settings'),
            'gt-tree',
            'normal',
            'high'
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-tree-debug',
                __( 'Family info', 'genealogical-tree' ),
                array($this, 'render_meta_box_tree_debug'),
                'gt-tree',
                'normal',
                'high'
            );
        }
    }

    /**
     * It renders the meta box for the member info.
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_info( $post ) {
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-member-info.php';
    }

    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_debug( $post ) {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['names'] = get_post_meta( $post->ID, 'names' );
        $get_post_meta['even'] = get_post_meta( $post->ID, 'even' );
        $get_post_meta['attr'] = get_post_meta( $post->ID, 'attr' );
        $get_post_meta['famc'] = get_post_meta( $post->ID, 'famc' );
        $get_post_meta['fams'] = get_post_meta( $post->ID, 'fams' );
        $get_post_meta['email'] = get_post_meta( $post->ID, 'email' );
        $get_post_meta['phone'] = get_post_meta( $post->ID, 'phone' );
        $get_post_meta['address'] = get_post_meta( $post->ID, 'address' );
        $get_post_meta['additional_fields'] = get_post_meta( $post->ID, 'additional_fields' );
        $get_post_meta['slgc'] = get_post_meta( $post->ID, 'slgc' );
        $get_post_meta['note'] = get_post_meta( $post->ID, 'note' );
        echo '<pre>';
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        echo '</pre>';
    }

    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_family_debug( $post ) {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['chil'] = get_post_meta( $post->ID, 'chil' );
        $get_post_meta['even'] = get_post_meta( $post->ID, 'even' );
        $get_post_meta['slgs'] = get_post_meta( $post->ID, 'slgs' );
        echo '<pre>';
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        echo '</pre>';
    }

    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_tree_debug( $post ) {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['tree'] = get_post_meta( $post->ID, 'tree' );
        echo '<pre>';
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        echo '</pre>';
    }

    /**
     * It renders the meta box for the tree settings
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_tree_settings( $post ) {
        $border_style = $this->plugin->helper->border_style();
        $data = ( get_post_meta( $post->ID, 'tree', true ) ? get_post_meta( $post->ID, 'tree', true ) : array(
            'family' => '',
        ) );
        $family_group_id = $data['family'];
        unset($data['family']);
        $base = $this->plugin->helper->tree_default_meta();
        $is_default = false;
        if ( empty( $data ) ) {
            $is_default = true;
        }
        $data = $this->plugin->helper->tree_merge( $base, $data, $is_default );
        $data['family'] = $family_group_id;
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-google-fonts.php';
        $premium = false;
        if ( !isset( $premium ) || !$premium ) {
            require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-settings.php';
        }
    }

    /**
     * It updates the meta boxes for the custom post type gt-member
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return int the post id.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_gt_member( $post_id ) {
        // Return if nonce field not exist.
        if ( !isset( $_POST['_nonce_update_member_info_nonce'] ) ) {
            return $post_id;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['_nonce_update_member_info_nonce'] ) );
        // Return if verify not success.
        if ( !wp_verify_nonce( $nonce, 'update_member_info_nonce' ) ) {
            return $post_id;
        }
        // stop autosave.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Return if not desire post type, and user don't have permission to update.
        if ( isset( $_POST['post_type'] ) && 'gt-member' === sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        $family_group = get_the_terms( $post_id, 'gt-family-group' );
        if ( is_wp_error( $family_group ) ) {
            return;
        }
        if ( isset( $_POST['tax_input'] ) && isset( $_POST['tax_input']['gt-family-group'] ) && isset( $_POST['tax_input']['gt-family-group'][1] ) && sanitize_text_field( wp_unslash( $_POST['tax_input']['gt-family-group'][1] ) ) ) {
            $family_group = true;
        }
        /*
        Checking if the family group is empty. If it is empty, it will set the error message and update the
        option.
        */
        if ( !$family_group ) {
            $errors = 'Whoops... you forgot to select family group.';
            update_option( 'family_group_validation', $errors );
            if ( get_post( $post_id )->post_status !== 'draft' ) {
                wp_update_post( array(
                    'ID'          => $post_id,
                    'post_status' => 'draft',
                ) );
            }
        }
        /* Checking if the family group is set. If it is, it will update the family group validation to false. */
        if ( $family_group ) {
            update_option( 'family_group_validation', false );
        }
        /* Sanitizing the data that is being passed in. */
        $names = ( isset( $_POST['gt']['names'] ) ? map_deep( wp_unslash( $_POST['gt']['names'] ), 'sanitize_text_field' ) : array(array(
            'name' => '',
            'npfx' => '',
            'givn' => '',
            'nick' => '',
            'spfx' => '',
            'surn' => '',
            'nsfx' => '',
        )) );
        foreach ( $names as $key => $name ) {
            $names[$key]['name'] = $this->plugin->helper->repear_full_name( sanitize_text_field( $name['name'] ) );
            $names[$key]['npfx'] = sanitize_text_field( $name['npfx'] );
            $names[$key]['givn'] = sanitize_text_field( $name['givn'] );
            $names[$key]['nick'] = sanitize_text_field( $name['nick'] );
            $names[$key]['spfx'] = sanitize_text_field( $name['spfx'] );
            $names[$key]['surn'] = sanitize_text_field( $name['surn'] );
            $names[$key]['nsfx'] = sanitize_text_field( $name['nsfx'] );
        }
        /* Deleting the post meta for the post id and then adding the post meta for the post id. */
        delete_post_meta( $post_id, 'names' );
        if ( isset( $names ) && is_array( $names ) && !empty( $names ) ) {
            foreach ( $names as $key => $value ) {
                add_post_meta( $post_id, 'names', $value );
            }
        }
        /*
        Checking if the sex field is set and if it is, it is sanitizing the text and then deleting the post
        meta. If the sex field is set, it is adding the post meta.
        */
        $sex = ( isset( $_POST['gt']['sex'] ) ? sanitize_text_field( wp_unslash( $_POST['gt']['sex'] ) ) : null );
        delete_post_meta( $post_id, 'sex' );
        if ( isset( $sex ) && $sex ) {
            add_post_meta( $post_id, 'sex', $sex );
        }
        /* Sanitizing the data and saving it to the database. */
        $attr = ( isset( $_POST['gt']['attr'] ) ? map_deep( wp_unslash( $_POST['gt']['attr'] ), 'sanitize_text_field' ) : array() );
        delete_post_meta( $post_id, 'attr' );
        if ( isset( $attr ) && is_array( $attr ) && !empty( $attr ) ) {
            foreach ( $attr as $key => $value ) {
                add_post_meta( $post_id, 'attr', $value );
            }
        }
        /* Saving the data from the form to the database. */
        $even = ( isset( $_POST['gt']['even'] ) ? map_deep( wp_unslash( $_POST['gt']['even'] ), 'sanitize_text_field' ) : array() );
        $birt = $even['BIRT'];
        $deat = $even['DEAT'];
        unset($even['BIRT']);
        unset($even['DEAT']);
        delete_post_meta( $post_id, 'even' );
        if ( isset( $even ) && is_array( $even ) && !empty( $even ) ) {
            foreach ( $even as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        if ( isset( $birt ) && is_array( $birt ) && !empty( $birt ) ) {
            foreach ( $birt as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        if ( isset( $deat ) && is_array( $deat ) && !empty( $deat ) ) {
            foreach ( $deat as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        /* Checking if the note is set in the POST array. If it is, it is sanitizing the text field. */
        $note = ( isset( $_POST['gt']['note'] ) ? map_deep( wp_unslash( $_POST['gt']['note'] ), 'sanitize_textarea_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'note' );
        if ( isset( $note ) && is_array( $note ) && !empty( $note ) ) {
            foreach ( $note as $key => $value ) {
                add_post_meta( $post_id, 'note', $value );
            }
        }
        /*
        Checking if the phone number is set in the  array. If it is, it is sanitizing the phone
        number.
        */
        $phone = ( isset( $_POST['gt']['phone'] ) ? map_deep( wp_unslash( $_POST['gt']['phone'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta for the phone number and then adding it back in. */
        delete_post_meta( $post_id, 'phone' );
        if ( isset( $phone ) && is_array( $phone ) && !empty( $phone ) ) {
            foreach ( $phone as $key => $value ) {
                add_post_meta( $post_id, 'phone', $value );
            }
        }
        /*
        Checking if the email is set in the ['gt']['email'] array. If it is, it is sanitizing the
        text field.
        */
        $email = ( isset( $_POST['gt']['email'] ) ? map_deep( wp_unslash( $_POST['gt']['email'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'email' );
        if ( isset( $email ) && is_array( $email ) && !empty( $email ) ) {
            foreach ( $email as $key => $value ) {
                add_post_meta( $post_id, 'email', $value );
            }
        }
        /* Checking if the address is set in the POST array. If it is, it is sanitizing the address. */
        $address = ( isset( $_POST['gt']['address'] ) ? map_deep( wp_unslash( $_POST['gt']['address'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'address' );
        if ( isset( $address ) && is_array( $address ) && !empty( $address ) ) {
            foreach ( $address as $key => $value ) {
                add_post_meta( $post_id, 'address', $value );
            }
        }
        /*
        Checking if the additional_info field is set and if it is, it is sanitizing the input and saving
        it to the post meta.
        */
        if ( isset( $_POST['additional_info'] ) ) {
            $additional_info = wp_kses_post( wp_unslash( $_POST['additional_info'] ) );
            update_post_meta( $post_id, 'additional_info', $additional_info );
        }
        /* Sanitizing the data that is being passed to the database. */
        if ( isset( $_POST['some_custom_gallery'] ) ) {
            $some_custom_gallery = map_deep( wp_unslash( $_POST['some_custom_gallery'] ), 'sanitize_text_field' );
            update_post_meta( $post_id, 'some_custom_gallery', $some_custom_gallery );
        }
        /* Sanitizing the additional fields. */
        $additional_fields = ( isset( $_POST['additional_fields'] ) ? map_deep( wp_unslash( $_POST['additional_fields'] ), 'sanitize_text_field' ) : array() );
        if ( $additional_fields ) {
            foreach ( $additional_fields as $key => $field ) {
                $additional_fields[$key]['name'] = sanitize_text_field( $field['name'] );
                $additional_fields[$key]['value'] = sanitize_text_field( $field['value'] );
            }
        }
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'additional_fields' );
        foreach ( $additional_fields as $key => $field ) {
            add_post_meta( $post_id, 'additional_fields', $field );
        }
        // family.
        $indis = array();
        array_push( $indis, $post_id );
        // FAMC.
        $famc_old_array = array();
        $famc_new_array = array();
        $famc_old = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
        foreach ( $famc_old as $key => $value ) {
            if ( isset( $value['famc'] ) && $value['famc'] ) {
                array_push( $famc_old_array, $value['famc'] );
            }
        }
        delete_post_meta( $post_id, 'famc' );
        $parents = ( isset( $_POST['gt']['family']['parents'] ) ? map_deep( wp_unslash( $_POST['gt']['family']['parents'] ), 'sanitize_text_field' ) : array() );
        foreach ( $parents as $key => $parent ) {
            $wife = ( isset( $parent['wife'] ) ? $parent['wife'] : 0 );
            $husb = ( isset( $parent['husb'] ) ? $parent['husb'] : 0 );
            if ( $wife || $husb ) {
                $chills = array(array(
                    'id'   => $post_id,
                    'pedi' => $parent['pedi'],
                ));
                $family_id = $this->find_or_create_family( $wife, $husb, $chills );
                array_push( $famc_new_array, $family_id );
                $famc = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
                foreach ( $famc as $key => $value ) {
                    if ( isset( $value['famc'] ) && $value['famc'] ) {
                        $famc[] = (int) $value['famc'];
                        unset($famc[$key]);
                    }
                }
                if ( !in_array( (int) $family_id, $famc, false ) ) {
                    add_post_meta( $post_id, 'famc', array(
                        'famc' => $family_id,
                        'pedi' => $parent['pedi'],
                    ) );
                }
                if ( $wife ) {
                    array_push( $indis, $wife );
                }
                if ( $husb ) {
                    array_push( $indis, $husb );
                }
                $this->repear_family( $family_id );
            }
        }
        // FAMS.
        $fams_new_array = array();
        $fams_old_array = array();
        $fams_old = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
        foreach ( $fams_old as $key => $value ) {
            if ( isset( $value['fams'] ) && $value['fams'] ) {
                array_push( $fams_old_array, $value['fams'] );
            }
        }
        delete_post_meta( $post_id, 'fams' );
        $spouses = ( isset( $_POST['gt']['family']['spouses'] ) ? map_deep( wp_unslash( $_POST['gt']['family']['spouses'] ), 'sanitize_text_field' ) : array() );
        foreach ( $spouses as $key => $spouse ) {
            $order = ( isset( $spouse['order'] ) ? $spouse['order'] : 0 );
            $chil = ( isset( $spouse['chil'] ) ? $spouse['chil'] : array() );
            if ( $spouse['id'] || !empty( $chil ) ) {
                $wife_or_husb = $this->is_wife_or_husband( $post_id, $spouse['id'] );
                $wife = $wife_or_husb['wife'];
                $husb = $wife_or_husb['husb'];
                if ( $wife || $husb ) {
                    $family_id = $this->find_or_create_family(
                        $wife,
                        $husb,
                        $chil,
                        $order
                    );
                    array_push( $fams_new_array, $family_id );
                    $even = $spouse['even'];
                    delete_post_meta( $family_id, 'even' );
                    if ( isset( $even ) && is_array( $even ) && !empty( $even ) ) {
                        foreach ( $even as $key => $value ) {
                            add_post_meta( $family_id, 'even', $value );
                        }
                    }
                    if ( $wife ) {
                        array_push( $indis, $wife );
                    }
                    if ( $husb ) {
                        array_push( $indis, $husb );
                    }
                    $this->repear_family( $family_id );
                }
            }
        }
        /* Deleting the old family relationships that are no longer valid. */
        $missing_famc = array_diff( $famc_old_array, $famc_new_array );
        $missing_fams = array_diff( $fams_old_array, $fams_new_array );
        /* Deleting the family from the child's record. */
        foreach ( $missing_famc as $key => $fam ) {
            delete_post_meta( $fam, 'chil', $post_id );
            $this->check_and_delete_family( $fam, $indis );
        }
        /* Deleting the family from the database. */
        foreach ( $missing_fams as $key => $fam ) {
            delete_post_meta( $fam, 'husb', $post_id );
            delete_post_meta( $fam, 'wife', $post_id );
            $this->check_and_delete_family( $fam, $indis );
        }
        /* Checking if the post meta 'slgc' exists and if it does, it deletes it. */
        $slgc_new = array();
        $slgc = ( isset( $_POST['gt']['slgc'] ) ? map_deep( wp_unslash( $_POST['gt']['slgc'] ), 'sanitize_text_field' ) : array() );
        delete_post_meta( $post_id, 'slgc' );
        /* Adding the new slgc data to the post meta. */
        if ( !empty( $famc_new_array ) ) {
            $slgc_new['famc'] = $famc_new_array[$slgc['slgc_check']];
            $slgc_new['date'] = $slgc[$slgc['slgc_check']]['date'];
            $slgc_new['plac'] = $slgc[$slgc['slgc_check']]['plac'];
            add_post_meta( $post_id, 'slgc', $slgc_new );
        }
        $this->repear_member( array($post_id) );
    }

    /**
     * If the nonce is valid, and the user has permission to edit the post, then update the post meta
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return int the post id.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_gt_tree( $post_id ) {
        if ( !isset( $_POST['_nonce_update_tree_settings_nonce'] ) ) {
            return $post_id;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['_nonce_update_tree_settings_nonce'] ) );
        if ( !wp_verify_nonce( $nonce, 'update_tree_settings_nonce' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( isset( $_POST['post_type'] ) && 'gt-tree' === sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        if ( isset( $_POST['tree'] ) && !empty( $_POST['tree'] ) ) {
            update_post_meta( $post_id, 'tree', map_deep( wp_unslash( $_POST['tree'] ), 'sanitize_text_field' ) );
        }
    }

    /**
     * It adds the columns to the member post type.
     *
     * @param array $columns An array of column names.
     *
     * @return array The columns for the member post type.
     *
     * @since    1.0.0
     */
    public function member_posts_columns( $columns ) {
        $columns['ID'] = __( 'ID', 'genealogical-tree' );
        $columns['born'] = __( 'Born', 'genealogical-tree' );
        $columns['title'] = __( 'Name', 'genealogical-tree' );
        $columns['parent'] = __( 'Parents', 'genealogical-tree' );
        $columns['spouses'] = __( 'Spouses', 'genealogical-tree' );
        $columns['author'] = __( 'Author', 'genealogical-tree' );
        return $columns;
    }

    /**
     * It adds a column to the admin table for each post type
     *
     * @param string $column The name of the column.
     * @param int    $post_id The ID of the post.
     *
     * @since    1.0.0
     */
    public function member_posts_custom_column( $column, $post_id ) {
        switch ( $column ) {
            case 'ID':
                echo esc_html( $post_id );
                break;
            case 'born':
                $even = ( get_post_meta( $post_id, 'even' ) ? get_post_meta( $post_id, 'even' ) : array() );
                $birt = array();
                foreach ( $even as $key => $value ) {
                    if ( 'BIRT' === $value['tag'] ) {
                        $birt[] = $value;
                    }
                }
                if ( !empty( $birt ) ) {
                    if ( isset( $birt[0] ) && $birt[0]['date'] ) {
                        echo esc_html( $birt[0]['date'] );
                    }
                }
                break;
            case 'parent':
                $famc = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
                foreach ( $famc as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset($famc[$key]);
                    }
                }
                foreach ( $famc as $key => $value ) {
                    $husb_id = get_post_meta( $value['famc'], 'husb', true );
                    if ( $husb_id && get_post( $husb_id ) ) {
                        echo '
						<div>
							<b>' . esc_html( __( 'Father', 'genealogical-tree' ) ) . ' : </b>
							<a href="' . esc_url( get_edit_post_link( $husb_id ) ) . '">
								' . esc_html( get_the_title( $husb_id ) ) . '
							</a>
						</div>
						';
                    }
                    $wife_id = get_post_meta( $value['famc'], 'wife', true );
                    if ( $wife_id && get_post( $wife_id ) ) {
                        echo '
						<div>
							<b>' . esc_html( __( 'Mother', 'genealogical-tree' ) ) . ' : </b>
							<a href="' . esc_url( get_edit_post_link( $wife_id ) ) . '">
								' . esc_html( get_the_title( $wife_id ) ) . '
							</a>
						</div>
						';
                    }
                }
                break;
            case 'spouses':
                $fams = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
                foreach ( $fams as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset($fams[$key]);
                    }
                }
                if ( !empty( $fams ) ) {
                    foreach ( $fams as $key => $value ) {
                        $husb_id = get_post_meta( $value['fams'], 'husb', true );
                        $wife_id = get_post_meta( $value['fams'], 'wife', true );
                        $spouse_id = ( $husb_id === $post_id ? $wife_id : $husb_id );
                        if ( $spouse_id && get_post( $spouse_id ) ) {
                            if ( $key > 0 ) {
                                echo ', ';
                            }
                            echo '
							<a href="' . esc_url( get_edit_post_link( $spouse_id ) ) . '">
								' . esc_html( get_the_title( $spouse_id ) ) . '
							</a>
							';
                        }
                    }
                }
                break;
        }
    }

    /**
     * This function adds the ability to sort the columns in the admin area
     *
     * @param array $columns The array of columns to be sorted.
     *
     * @since    1.0.0
     */
    public function member_sortable_columns( $columns ) {
        $columns['ID'] = 'ID';
        $columns['born'] = 'born';
        $columns['title'] = 'title';
        $columns['taxonomy-gt-family-group'] = 'gt-family-group';
        return $columns;
    }

    /**
     * It adds a new column to the list of posts
     *
     * @param array $columns The names of the columns.
     *
     * @return array The shortcode for the post.
     *
     * @since    1.0.0
     */
    public function tree_posts_columns( $columns ) {
        $columns['shortcode'] = __( 'Shortcode', 'genealogical-tree' );
        return $columns;
    }

    /**
     * It adds a column to the admin list of trees, and in that column it displays the shortcode for that
     * tree
     *
     * @param array $column The name of the column.
     * @param int   $post_id The ID of the post.
     *
     * @since    1.0.0
     */
    public function tree_posts_custom_column( $column, $post_id ) {
        switch ( $column ) {
            case 'shortcode':
                echo sprintf( '<input style="max-width:%2$s" type="text" readonly value="[tree id=%1$s]">', esc_attr( $post_id ), esc_attr( '100%' ) );
                break;
        }
    }

    /**
     * It adds a rewrite rule to WordPress that allows us to use the URL
     * `/gt-member/{member}/tab/{tab-slug}` to access the tab `{tab-slug}` on the profile of the user
     * with the member `{member}`
     *
     * @since    1.0.0
     */
    public function init_add_rewrite_rule_gt_member_tab() {
        add_rewrite_rule( 'gt-member/( [A-Za-z0-9\\-\\_]+ )/tab/( [A-Za-z0-9\\-\\_]+ )', 'index.php?gt-member=$matches[1]&tab=$matches[2]', 'top' );
    }

    /**
     * It adds the query variable `tab` to the list of query variables that WordPress will recognize
     *
     * @param array $query_vars The query variables that will be used to determine which tab is being displayed.
     *
     * @return array The query_vars array.
     *
     * @since    1.0.0
     */
    public function query_vars_gt_member_tab( $query_vars ) {
        $query_vars[] = 'tab';
        return $query_vars;
    }

    /**
     * It adds the gt_member role to the user if they are an administrator or gt_manager
     *
     * @param int $user_id The ID of the user being registered.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function user_register_action( $user_id ) {
        $user = get_user_by( 'id', $user_id );
        // On user registration.
        if ( $user && in_array( 'administrator', $user->roles, true ) ) {
            $user->add_role( 'gt_member' );
            $user->add_role( 'gt_manager' );
        }
        if ( $user && in_array( 'gt_manager', $user->roles, true ) ) {
            $user->add_role( 'gt_member' );
        }
        if ( !isset( $_POST['gt_login_form_nonce'] ) ) {
            return;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['gt_login_form_nonce'] ) );
        if ( !wp_verify_nonce( $nonce, 'gt_login_form_action' ) ) {
            return;
        }
        // User registration through gt registerantion form.
        if ( isset( $_POST['role'] ) ) {
            if ( 'gt_manager' === sanitize_text_field( wp_unslash( $_POST['role'] ) ) ) {
                $user->add_role( 'gt_manager' );
                $user->add_role( 'gt_member' );
            }
            if ( 'gt_member' === sanitize_text_field( wp_unslash( $_POST['role'] ) ) ) {
                $user->add_role( 'gt_member' );
            }
        }
    }

    /**
     * A callback function for the import form.
     *
     * @since    1.0.0
     */
    public function process_import_post() {
        require_once 'genealogical-tree-handel-import.php';
    }

    /**
     * A callback function for the export ged.
     *
     * @since    1.0.0
     */
    public function process_export_post() {
        require_once 'genealogical-tree-handel-export.php';
    }

    /**
     * View for How It Work page.
     *
     * @since    1.0.0
     */
    public function settings() {
    }

    /**
     * It takes a wife, husband, and children, and creates a family if one doesn't exist.
     *
     * @param  int   $wife  The ID of the wife.
     * @param  int   $husb  The ID of the husband.
     * @param  array $chil  An array of child IDs.
     * @param  int   $order This is the order of the family. If you have a person who has been married multiple times, this is the order of the marriage.
     *
     * @return int         The ID of the created / exist family.
     *
     * @since    2.1.1
     */
    public function find_or_create_family(
        $wife,
        $husb,
        $chil,
        $order = 0
    ) {
        if ( $wife || $husb ) {
            if ( $wife && $husb ) {
                $query = new \WP_Query(array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'wife',
                            'value'   => $wife,
                            'compare' => '=',
                        ),
                        array(
                            'key'     => 'husb',
                            'value'   => $husb,
                            'compare' => '=',
                        ),
                    ),
                ));
            }
            if ( !$wife && $husb ) {
                $query = new \WP_Query(array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'husb',
                            'value'   => $husb,
                            'compare' => '=',
                        ),
                        array(
                            'key'     => 'wife',
                            'compare' => 'NOT EXISTS',
                        ),
                    ),
                ));
            }
            if ( $wife && !$husb ) {
                $query = new \WP_Query(array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'wife',
                            'value'   => $wife,
                            'compare' => '=',
                        ),
                        array(
                            'key'     => 'husb',
                            'compare' => 'NOT EXISTS',
                        ),
                    ),
                ));
            }
            /*
            Checking if the family exists and if it does, it will return the family ID. If it doesn't exist, it
            will create a new family and return the family ID.
            */
            if ( isset( $query ) && $query->posts && !empty( $query->posts ) ) {
                $family_id = current( $query->posts )->ID;
            } else {
                if ( $wife && $husb ) {
                    $post_title = get_the_title( $husb ) . ' and ' . get_the_title( $wife );
                }
                if ( !$wife && $husb ) {
                    $post_title = get_the_title( $husb );
                }
                if ( $wife && !$husb ) {
                    $post_title = get_the_title( $wife );
                }
                $family_id = wp_insert_post( array(
                    'post_title'   => $post_title,
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                    'post_type'    => 'gt-family',
                ) );
            }
            if ( $husb ) {
                // Manage family.
                /* Checking to see if the husband is already in the family. If not, it adds him. */
                if ( !in_array( (string) $husb, get_post_meta( $family_id, 'husb' ), true ) ) {
                    add_post_meta( $family_id, 'husb', $husb );
                }
                /* Get families. */
                $fams = ( get_post_meta( $husb, 'fams' ) ? get_post_meta( $husb, 'fams' ) : array() );
                /* Prepare for checking. */
                foreach ( $fams as $value ) {
                    if ( isset( $value['fams'] ) && $value['fams'] ) {
                        $fams[] = (int) $value['fams'];
                    }
                }
                /*
                Checking to see if the family_ID is in the array of families of husband. If it is not, it adds it to the
                families.
                */
                if ( !in_array( (int) $family_id, $fams, true ) ) {
                    add_post_meta( $husb, 'fams', array(
                        'fams'  => $family_id,
                        'order' => $order,
                    ) );
                }
            }
            if ( $wife ) {
                // Manage family.
                /* Checking if the wife is already in the family. If not, it adds the wife to the family. */
                if ( !in_array( (string) $wife, get_post_meta( $family_id, 'wife' ), true ) ) {
                    add_post_meta( $family_id, 'wife', $wife );
                }
                /* Get families. */
                $fams = ( get_post_meta( $wife, 'fams' ) ? get_post_meta( $wife, 'fams' ) : array() );
                /* Prepare for checking. */
                foreach ( $fams as $value ) {
                    if ( isset( $value['fams'] ) && $value['fams'] ) {
                        $fams[] = (int) $value['fams'];
                    }
                }
                /*
                Checking to see if the family_ID is in the array of families of wife. If it is not, it adds it to the
                families.
                */
                if ( !in_array( (int) $family_id, $fams, true ) ) {
                    add_post_meta( $wife, 'fams', array(
                        'fams'  => $family_id,
                        'order' => $order,
                    ) );
                }
            }
            if ( is_array( $chil ) && !empty( $chil ) ) {
                foreach ( $chil as $ch ) {
                    if ( is_array( $ch ) ) {
                        // Manage family.
                        /* Checking if the child is already in the family of parents. If not, it adds the child to the family. */
                        $current_chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
                        if ( !in_array( (string) $ch['id'], $current_chil, true ) ) {
                            add_post_meta( $family_id, 'chil', $ch['id'] );
                        }
                        /* Get parent families. */
                        $famc = ( get_post_meta( $ch['id'], 'famc' ) ? get_post_meta( $ch['id'], 'famc' ) : array() );
                        /* Prepare for checking. */
                        foreach ( $famc as $value ) {
                            if ( isset( $value['famc'] ) && $value['famc'] ) {
                                $famc[] = (int) $value['famc'];
                            }
                        }
                        /*
                        Checking to see if the family_ID is in the array of parents families. If it is not, it adds it to the
                        families.
                        */
                        if ( !in_array( (int) $family_id, $famc, true ) ) {
                            add_post_meta( $ch['id'], 'famc', array(
                                'famc' => $family_id,
                                'pedi' => ( $ch['pedi'] ? $ch['pedi'] : '' ),
                            ) );
                        }
                    }
                }
            }
            return $family_id;
        }
    }

    /**
     * If a family has a husband, wife, and/or children, then don't delete it.  Otherwise, delete it
     *
     * @param int   $family_id  The ID of the family you want to check.
     * @param array $member_ids An array of member IDs that are related to the family.
     *
     * @return mixed.
     *
     * @since    2.1.1
     */
    public function check_and_delete_family( $family_id, $member_ids ) {
        $husb = get_post_meta( $family_id, 'husb', true );
        $wife = get_post_meta( $family_id, 'wife', true );
        $chil = get_post_meta( $family_id, 'chil', true );
        if ( $husb && $wife || $wife && $chil || $husb && $chil ) {
            return;
        } else {
            $member_ids = array_unique( $member_ids );
            foreach ( $member_ids as $member_id ) {
                delete_post_meta( $member_id, 'fams', $family_id );
                delete_post_meta( $member_id, 'famc', $family_id );
            }
            wp_delete_post( $family_id );
        }
    }

    /**
     * If the current screen is the edit screen for the gt-member post type, then add the merged_with or
     * merged_to class to the post row
     *
     * @param array  $classes An array of post classes.
     * @param string $class The class name.
     * @param int    $post_id The ID of the post.
     *
     * @return array An array of post classes.
     *
     * @since    1.0.0
     */
    public function post_class_filter( $classes, $class, $post_id ) {
        if ( !is_admin() ) {
            return $classes;
        }
        $screen = get_current_screen();
        if ( 'gt-member' !== $screen->post_type && 'edit' !== $screen->base ) {
            return $classes;
        }
        $merged_with = ( get_post_meta( $post_id, 'merged_with' ) ? get_post_meta( $post_id, 'merged_with' ) : array() );
        if ( !empty( $merged_with ) ) {
            $classes[] = 'merged_with';
        }
        $merged_to = ( get_post_meta( $post_id, 'merged_to' ) ? get_post_meta( $post_id, 'merged_to' ) : array() );
        if ( !empty( $merged_to ) ) {
            $classes[] = 'merged_to';
        }
        return $classes;
    }

    function search_members_ajax() {
        $search_term = ( isset( $_POST['searchTerm'] ) ? sanitize_text_field( $_POST['searchTerm'] ) : '' );
        // Assuming this function is inside a class, you might need to adjust how you call it
        $results = $this->get_useable_members_ajax( $search_term );
        $formatted_data = array();
        $categories = array(
            'Males'    => $results['males'],
            'Females'  => $results['females'],
            'Unknowns' => $results['unknowns'],
        );
        foreach ( $categories as $category_name => $members ) {
            if ( empty( $members ) ) {
                // If no members in this category, add a "Not Found" message
                $formatted_data[] = array(
                    'text'     => $category_name,
                    'children' => array(array(
                        'id'   => '',
                        'text' => 'No ' . strtolower( $category_name ) . ' found',
                    )),
                );
            } else {
                // If members are found, format them as usual
                $formatted_data[] = array(
                    'text'     => $category_name,
                    'children' => array_map( function ( $id ) {
                        return array(
                            'id'   => $id,
                            'text' => get_the_title( $id ),
                        );
                    }, $members ),
                );
            }
        }
        // // Prepare your results in the required format for JSON response
        // $data = array();
        // foreach ($results as $gender => $members) {
        // 	foreach ($members as $member_id) {
        // 		$data[] = array(
        // 			'id'   => $member_id,
        // 			'text' => get_the_title($member_id), // or any other title you use for members
        // 		);
        // 	}
        // }
        wp_send_json( $formatted_data );
    }

    /**
     * It returns an array of member IDs that the current user can use
     *
     * @param object $post The post object of the current post.
     *
     * @return array An array of arrays.
     *
     * @since    1.0.0
     */
    public function get_useable_members( $post ) {
        $males = array();
        $females = array();
        $unknowns = array();
        /*
        Creating an array of all the members that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'author'         => get_current_user_id(),
            'post__not_in'   => array($post->ID),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            unset($args['author']);
        }
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'post__not_in'   => array($post->ID),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'can_use',
                    'value'   => get_current_user_id(),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use  and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'post__not_in'   => array($post->ID),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'can_use_by_allowed_group',
                    'value'   => get_current_user_id(),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        return array(
            'males'    => $males,
            'females'  => $females,
            'unknowns' => $unknowns,
        );
    }

    /**
     * It returns an array of member IDs that the current user can use
     *
     * @param object $post The post object of the current post.
     *
     * @return array An array of arrays.
     *
     * @since    1.0.0
     */
    public function get_useable_members_ajax( $search_term = '' ) {
        $males = array();
        $females = array();
        $unknowns = array();
        /*
        Creating an array of all the members that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            's'              => $search_term,
            'author'         => get_current_user_id(),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            unset($args['author']);
        }
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            's'              => $search_term,
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'can_use',
                    'value'   => get_current_user_id(),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use  and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            's'              => $search_term,
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'can_use_by_allowed_group',
                    'value'   => get_current_user_id(),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        $query = new \WP_Query($args);
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        return array(
            'males'    => $males,
            'females'  => $females,
            'unknowns' => $unknowns,
        );
    }

    /**
     * It creates a select box with the option groups of female, male and unknown.
     *
     * @param array  $females  An array of female members.
     * @param array  $males    An array of male members.
     * @param array  $unknowns An array of unknown gender members.
     * @param string $name     The name of the select box.
     * @param string $value    The value of the option.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function select_member_html(
        $females = array(),
        $males = array(),
        $unknowns = array(),
        $name = '',
        $value = ''
    ) {
        ?>
		<option value=""><?php 
        esc_html_e( 'Select', 'genealogical-tree' );
        ?> <?php 
        echo esc_html( $name );
        ?> </option>
		<optgroup label="<?php 
        esc_html_e( 'Female', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $females as $female ) {
            ?>
				<option <?php 
            selected( $female, $value );
            ?> value="<?php 
            echo esc_attr( $female );
            ?>">
					<?php 
            echo esc_html( $this->plugin->helper->get_full_name( $female ) );
            ?>
					<?php 
            echo esc_html( '[' . $female . ']' );
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<optgroup label="<?php 
        esc_html_e( 'Male', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $males as $male ) {
            ?>
				<option <?php 
            selected( $male, $value );
            ?> value="<?php 
            echo esc_attr( $male );
            ?>">
					<?php 
            echo esc_html( $this->plugin->helper->get_full_name( $male ) );
            ?>
					<?php 
            echo esc_html( '[' . $male . ']' );
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<optgroup label="<?php 
        esc_html_e( 'Unknown', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $unknowns as $unknown ) {
            ?>
				<option <?php 
            selected( $unknown, $value );
            ?> value="<?php 
            echo esc_attr( $unknown );
            ?>">
					<?php 
            echo esc_html( $this->plugin->helper->get_full_name( $unknown ) );
            ?>
					<?php 
            echo esc_html( '[' . $unknown . ']' );
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<?php 
    }

    /**
     * It takes a string, checks if it exists as a term in the taxonomy `gt-family-group`, and if it does,
     * it returns a string with a number appended to the end
     *
     * @param string $filename The name of the family group.
     *
     * @return string The first suggestion for a family group name.
     *
     * @since    1.0.0
     */
    public function generate_family_group_name( $filename ) {
        if ( $filename ) {
            $filename = sanitize_text_field( $filename );
            $term = term_exists( $filename, 'gt-family-group' );
            $suggestions = array();
            if ( 0 !== $term && null !== $term ) {
                $terms_slug = array();
                $count = 0;
                $names_left = 1000;
                $terms = get_terms( 'gt-family-group', array(
                    'hide_empty' => false,
                ) );
                if ( $terms ) {
                    foreach ( $terms as $key => $term ) {
                        array_push( $terms_slug, $term->slug );
                    }
                }
                while ( $names_left > 0 ) {
                    $count++;
                    if ( !in_array( sanitize_title( $filename ) . '-' . $count, $terms_slug, true ) ) {
                        $suggestions[] = $filename . ' ' . $count;
                        $names_left--;
                    }
                }
            } else {
                return $filename;
            }
            return $suggestions[0];
        }
    }

    /**
     * It creates a new taxonomy term (family group) and returns the term ID
     *
     * @param array $file_args This is an array of information about the file that was uploaded.
     *
     * @return mixed The family group id.
     *
     * @since    1.0.0
     */
    public function generate_family_group_id( $file_args ) {
        $family_group_name = $this->generate_family_group_name( $file_args['filename'] );
        if ( current_user_can( 'gt_member' ) || current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            $family_group = wp_insert_term( $family_group_name, 'gt-family-group' );
            if ( !is_wp_error( $family_group ) ) {
                $family_group_id = $family_group['term_id'];
                update_term_meta( $family_group_id, 'created_by', get_current_user_id() );
                return $family_group_id;
            } else {
                echo esc_html( __( 'Something wnt worng.', 'genealogical-tree' ) );
                die;
            }
        }
    }

    /**
     * If the sex of the person is known, then the person is the husband or wife, otherwise, the spouse is
     * the husband or wife
     *
     * @param int $post_id   The ID of the person you're checking.
     * @param int $spouse_id The ID of the spouse.
     * @param int $wife      The wife's ID.
     * @param int $husb      The husband's ID.
     *
     * @return array
     *
     * @since    1.0.0
     */
    public function is_wife_or_husband(
        $post_id,
        $spouse_id,
        $wife = 0,
        $husb = 0
    ) {
        $sex = ( get_post_meta( $post_id, 'sex', true ) ? get_post_meta( $post_id, 'sex', true ) : '' );
        if ( $sex ) {
            if ( 'M' === $sex ) {
                $husb = $post_id;
                $wife = $spouse_id;
            }
            if ( 'F' === $sex ) {
                $wife = $post_id;
                $husb = $spouse_id;
            }
        } else {
            $sex = ( get_post_meta( $spouse_id, 'sex', true ) ? get_post_meta( $spouse_id, 'sex', true ) : '' );
            if ( $sex ) {
                if ( 'M' === $sex ) {
                    $wife = $post_id;
                    $husb = $spouse_id;
                }
                if ( 'F' === $sex ) {
                    $husb = $post_id;
                    $wife = $spouse_id;
                }
            } else {
                $husb = $post_id;
                $wife = $spouse_id;
            }
        }
        return array(
            'wife' => $wife,
            'husb' => $husb,
        );
    }

    /**
     * It deletes all the meta data associated with a person when that person is deleted.
     *
     * @param int $post_id The ID of the post being deleted.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function before_delete_post( $post_id ) {
        $args = array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => 'husb',
                    'compare' => '=',
                    'value'   => $post_id,
                ),
                array(
                    'key'     => 'husb',
                    'compare' => '=',
                    'value'   => $post_id,
                ),
                array(
                    'key'     => 'chil',
                    'compare' => 'IN',
                    'value'   => $post_id,
                ),
            ),
        );
        $query = new \WP_Query($args);
        $families = $query->posts;
        if ( $families ) {
            foreach ( $families as $key => $value ) {
                delete_post_meta( $value->ID, 'husb', $post_id );
                delete_post_meta( $value->ID, 'wife', $post_id );
                delete_post_meta( $value->ID, 'chil', $post_id );
            }
        }
    }

    function get_posts_by_term_ajax() {
        $term_id = ( isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0 );
        $args = array(
            'post_type'      => ['gt-member', 'gt-family', 'gt-tree'],
            'tax_query'      => array(array(
                'taxonomy' => 'gt-family-group',
                'field'    => 'term_id',
                'terms'    => $term_id,
            )),
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );
        $query = new \WP_Query($args);
        wp_send_json_success( array(
            'posts' => $query->posts,
        ) );
    }

    function get_posts_by_term_or_no_term_ajax() {
        $term_id = ( isset( $_POST['term_id'] ) ? $_POST['term_id'] : null );
        $args = array(
            'post_type'      => ['gt-member', 'gt-family', 'gt-tree'],
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );
        // If term_id is null, get posts without terms
        if ( $term_id == null ) {
            $args['tax_query'] = array(array(
                'taxonomy' => 'gt-family-group',
                'operator' => 'NOT EXISTS',
            ));
        } else {
            $args['tax_query'] = array(array(
                'taxonomy' => 'gt-family-group',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ));
        }
        $query = new \WP_Query($args);
        wp_send_json_success( array(
            'posts' => $query->posts,
        ) );
    }

    function delete_posts_by_ids_ajax() {
        if ( !current_user_can( 'delete_posts' ) ) {
            wp_send_json_error( 'Unauthorized', 401 );
        }
        $post_ids = ( isset( $_POST['post_ids'] ) ? $_POST['post_ids'] : array() );
        foreach ( $post_ids as $post_id ) {
            wp_delete_post( $post_id, true );
        }
        wp_send_json_success( 'Posts deleted' );
    }

    /**
     * It compares two objects by their ID property.
     *
     * @param  object $a The first object to compare.
     * @param  object $b The next object to compare.
     *
     * @return object   The object.
     *
     * @since           1.0.0
     */
    public function sort_member_posts( $a, $b ) {
        return strcmp( $a->ID, $b->ID );
    }

    /**
     * Used to get add or delete button on the clone field area.
     *
     * @param int $key The key of the current element in the array.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function clone_delete( $key ) {
        if ( 0 === (int) $key ) {
            echo '<span class="clone">' . esc_html__( 'Add', 'genealogical-tree' ) . '</span>';
        }
        if ( (int) $key > 0 ) {
            echo '<span class="delete">' . esc_html__( 'Delete', 'genealogical-tree' ) . '</span>';
        }
    }

    /**
     * Function for `bp_manage_capabilities`
     *
     * @param int    $allcaps allcaps.
     * @param int    $caps caps.
     * @param int    $args args.
     * @param object $user user.
     *
     * @since    1.0.0
     */
    public function bp_manage_capabilities(
        $allcaps,
        $caps,
        $args,
        $user
    ) {
        global $bp;
        $roles = (array) $user->roles;
        if ( function_exists( 'groups_get_group_members' ) ) {
            $admin_mods = groups_get_group_members( array(
                'group_id' => 1,
            ) );
            $target = array(
                'administrator',
                'editor',
                'gt_manager',
                'gt_member'
            );
            $result = array_intersect( $roles, $target );
            if ( !empty( $result ) || $bp && get_user_meta( $user->ID, 'total_group_count', true ) ) {
                $allcaps['upload_files'] = true;
                $allcaps['edit_posts'] = true;
                $allcaps['edit_published_posts'] = true;
                $allcaps['publish_posts'] = true;
                $allcaps['read'] = true;
                $allcaps['level_2'] = true;
                $allcaps['level_1'] = true;
                $allcaps['level_0'] = true;
                $allcaps['delete_posts'] = true;
                $allcaps['delete_published_posts'] = true;
                $allcaps['gt_member'] = true;
                $allcaps['manage_categories'] = true;
            }
        }
        return $allcaps;
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_tab() {
        global $bp;
        if ( function_exists( 'bp_core_new_nav_item' ) ) {
            bp_core_new_nav_item( array(
                'name'                => 'Family Tree',
                'slug'                => 'family-tree',
                'screen_function'     => array($this, 'bp_family_tree_screen'),
                'position'            => 40,
                'parent_url'          => bp_loggedin_user_domain() . '/family-tree/',
                'parent_slug'         => $bp->profile->slug,
                'default_subnav_slug' => 'family-tree',
            ) );
        }
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_screen() {
        if ( function_exists( 'bp_core_load_template' ) ) {
            bp_core_load_template( 'buddypress/members/single/plugins' );
        }
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_title() {
        echo 'Family Tree';
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_content() {
        echo 'Content';
    }

    /**
     * Gedcom parse.
     *
     * @param int $file file.
     * @param int $family_group_id family_group_id.
     * @param int $file_map file_map.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function gedcom_parse( $file, $family_group_id, $file_map = array() ) {
        $parser = new \PhpGedcom\Parser();
        $gedcom = $parser->parse( $file );
        $file_map_new = array();
        if ( $gedcom->getObje() ) {
            foreach ( $gedcom->getObje() as $obje ) {
                $file_path = str_replace( '\\', '/', $obje->getFile() );
                $basename = pathinfo( $file_path )['basename'];
                $file_map_new[$basename] = $obje->getId();
            }
        }
        $x = array();
        foreach ( $file_map as $key => $value ) {
            if ( isset( $file_map_new[$key] ) ) {
                $x[$key]['attachment_id'] = $value;
                $x[$key]['id'] = $file_map_new[$key];
            }
        }
        $y = array();
        foreach ( $x as $key => $value ) {
            if ( $value['id'] ) {
                $y[$value['id']] = $value['attachment_id'];
            }
        }
        if ( $gedcom->getIndi() ) {
            foreach ( $gedcom->getIndi() as $individual ) {
                $data['persons'][$individual->getId()]['obje'] = array();
                if ( $individual->getObje() ) {
                    foreach ( $individual->getObje() as $individual_obje ) {
                        $data['persons'][$individual->getId()]['obje'][] = $individual_obje->getObje();
                    }
                }
                $data['persons'][$individual->getId()]['id'] = $individual->getId();
                $ind_names = $individual->getName();
                foreach ( $ind_names as $key => $name ) {
                    $data['persons'][$individual->getId()]['names'][$key]['name'] = wp_strip_all_tags( trim( str_replace( array('/', '\\', '  '), array(' ', '', ' '), $name->getName() ) ) );
                    $data['persons'][$individual->getId()]['names'][$key]['npfx'] = $name->getNpfx();
                    $data['persons'][$individual->getId()]['names'][$key]['givn'] = $name->getGivn();
                    $data['persons'][$individual->getId()]['names'][$key]['nick'] = $name->getNick();
                    $data['persons'][$individual->getId()]['names'][$key]['spfx'] = $name->getSpfx();
                    $data['persons'][$individual->getId()]['names'][$key]['surn'] = $name->getSurn();
                    $data['persons'][$individual->getId()]['names'][$key]['nsfx'] = $name->getNsfx();
                }
                $data['persons'][$individual->getId()]['sex'] = $individual->getSex();
                if ( $individual->getBapl() ) {
                    $bapl = $individual->getBapl();
                    $data['persons'][$individual->getId()]['bapl']['tag'] = 'BAPL';
                    $data['persons'][$individual->getId()]['bapl']['stat'] = $bapl->getStat();
                    $data['persons'][$individual->getId()]['bapl']['date'] = $bapl->getDate();
                    $data['persons'][$individual->getId()]['bapl']['plac'] = $bapl->getPlac();
                    $data['persons'][$individual->getId()]['bapl']['temp'] = $bapl->getTemp();
                }
                if ( $individual->getConl() ) {
                    $conl = $individual->getConl();
                    $data['persons'][$individual->getId()]['conl']['tag'] = 'CONL';
                    $data['persons'][$individual->getId()]['conl']['stat'] = $conl->getStat();
                    $data['persons'][$individual->getId()]['conl']['date'] = $conl->getDate();
                    $data['persons'][$individual->getId()]['conl']['plac'] = $conl->getPlac();
                    $data['persons'][$individual->getId()]['conl']['temp'] = $conl->getTemp();
                }
                if ( $individual->getEndl() ) {
                    $endl = $individual->getEndl();
                    $data['persons'][$individual->getId()]['endl']['tag'] = 'ENDL';
                    $data['persons'][$individual->getId()]['endl']['stat'] = $endl->getStat();
                    $data['persons'][$individual->getId()]['endl']['date'] = $endl->getDate();
                    $data['persons'][$individual->getId()]['endl']['plac'] = $endl->getPlac();
                    $data['persons'][$individual->getId()]['endl']['temp'] = $endl->getTemp();
                }
                if ( $individual->getSlgc() ) {
                    $slgc = $individual->getSlgc();
                    $data['persons'][$individual->getId()]['slgc']['tag'] = 'SLGC';
                    $data['persons'][$individual->getId()]['slgc']['stat'] = $slgc->getStat();
                    $data['persons'][$individual->getId()]['slgc']['date'] = $slgc->getDate();
                    $data['persons'][$individual->getId()]['slgc']['plac'] = $slgc->getPlac();
                    $data['persons'][$individual->getId()]['slgc']['temp'] = $slgc->getTemp();
                    $data['persons'][$individual->getId()]['slgc']['famc'] = $slgc->getFamc();
                }
                if ( $individual->getFamc() ) {
                    foreach ( $individual->getFamc() as $key => $famc ) {
                        $data['persons'][$individual->getId()]['famc'][$key]['famc'] = $famc->getFamc();
                        $data['persons'][$individual->getId()]['famc'][$key]['pedi'] = $famc->getPedi();
                    }
                }
                if ( $individual->getFams() ) {
                    foreach ( $individual->getFams() as $key => $fams ) {
                        $data['persons'][$individual->getId()]['fams'][$key]['fams'] = $fams->getFams();
                    }
                }
                if ( $individual->getAttr() ) {
                    foreach ( $individual->getAttr() as $key => $attr ) {
                        $data['persons'][$individual->getId()]['attr'][$key]['tag'] = $attr->getTag();
                        $data['persons'][$individual->getId()]['attr'][$key]['attr'] = $attr->getAttr();
                        $data['persons'][$individual->getId()]['attr'][$key]['type'] = $attr->getType();
                        $data['persons'][$individual->getId()]['attr'][$key]['date'] = $attr->getDate();
                        if ( $attr->getPlac() ) {
                            $data['persons'][$individual->getId()]['attr'][$key]['plac'] = $attr->getPlac()->getPlac();
                        }
                    }
                }
                if ( $individual->getEven() ) {
                    foreach ( $individual->getEven() as $key => $event ) {
                        $data['persons'][$individual->getId()]['even'][$key]['tag'] = $event->getTag();
                        $data['persons'][$individual->getId()]['even'][$key]['even'] = $event->getEven();
                        $data['persons'][$individual->getId()]['even'][$key]['type'] = $event->getType();
                        $data['persons'][$individual->getId()]['even'][$key]['date'] = $event->getDate();
                        if ( $event->getPlac() ) {
                            $data['persons'][$individual->getId()]['even'][$key]['plac'] = $event->getPlac()->getPlac();
                        } else {
                            $data['persons'][$individual->getId()]['even'][$key]['plac'] = '';
                        }
                    }
                }
                if ( $individual->getNote() ) {
                    foreach ( $individual->getNote() as $key => $note ) {
                        $data['persons'][$individual->getId()]['note'][$key]['note'] = $note->getNote();
                        $data['persons'][$individual->getId()]['note'][$key]['isRef'] = $note->isReference();
                    }
                }
            }
        }
        if ( $gedcom->getFam() ) {
            foreach ( $gedcom->getFam() as $fam ) {
                $data['families'][$fam->getId()]['id'] = $fam->getId();
                $data['families'][$fam->getId()]['husb'] = $fam->getHusb();
                $data['families'][$fam->getId()]['wife'] = $fam->getWife();
                if ( $fam->getChil() ) {
                    foreach ( $fam->getChil() as $key => $chil ) {
                        $data['families'][$fam->getId()]['chil'][] = $chil;
                    }
                }
                if ( $fam->getSlgs() ) {
                    foreach ( $fam->getSlgs() as $key => $slgs ) {
                        $data['families'][$fam->getId()]['slgs'][$key]['tag'] = 'SLGS';
                        $data['families'][$fam->getId()]['slgs'][$key]['stat'] = $slgs->getStat();
                        $data['families'][$fam->getId()]['slgs'][$key]['date'] = $slgs->getDate();
                        $data['families'][$fam->getId()]['slgs'][$key]['plac'] = $slgs->getPlac();
                        $data['families'][$fam->getId()]['slgs'][$key]['temp'] = $slgs->getTemp();
                    }
                }
                if ( $fam->getEven() ) {
                    foreach ( $fam->getEven() as $key => $event ) {
                        $data['families'][$fam->getId()]['even'][$key]['tag'] = $event->getTag();
                        $data['families'][$fam->getId()]['even'][$key]['even'] = $event->getEven();
                        $data['families'][$fam->getId()]['even'][$key]['type'] = $event->getType();
                        $data['families'][$fam->getId()]['even'][$key]['date'] = $event->getDate();
                        if ( $event->getPlac() ) {
                            $data['families'][$fam->getId()]['even'][$key]['plac'] = $event->getPlac()->getPlac();
                        } else {
                            $data['families'][$fam->getId()]['even'][$key]['plac'] = '';
                        }
                    }
                }
            }
        }
        $members = array();
        $have_slgc = array();
        if ( $data['persons'] ) {
            foreach ( $data['persons'] as $key => $person ) {
                $obje_mob = '';
                if ( isset( $person['obje'] ) ) {
                    foreach ( $person['obje'] as $key => $obje ) {
                        if ( isset( $y[$obje] ) ) {
                            $obje_mob .= ',' . $y[$obje];
                        }
                    }
                }
                if ( isset( $person['names'] ) && isset( $person['names'][0] ) && isset( $person['names'][0]['name'] ) ) {
                    $post_title = wp_strip_all_tags( trim( str_replace( array('/', '\\', '  '), array(' ', '', ' '), $person['names'][0]['name'] ) ) );
                    $my_post = array(
                        'post_title'   => $post_title,
                        'post_content' => '',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                        'post_type'    => 'gt-member',
                    );
                    $gt_member_id = wp_insert_post( $my_post );
                    array_push( $members, $gt_member_id );
                    add_post_meta( $gt_member_id, 'through_import', true );
                    delete_post_meta( $gt_member_id, 'names' );
                    if ( isset( $person['names'] ) && is_array( $person['names'] ) && !empty( $person['names'] ) ) {
                        foreach ( $person['names'] as $key => $value ) {
                            add_post_meta( $gt_member_id, 'names', $value );
                        }
                    }
                    delete_post_meta( $gt_member_id, 'some_custom_gallery' );
                    if ( $obje_mob ) {
                        add_post_meta( $gt_member_id, 'some_custom_gallery', $obje_mob );
                    }
                    delete_post_meta( $gt_member_id, 'ref_id' );
                    if ( isset( $person['id'] ) && $person['id'] ) {
                        add_post_meta( $gt_member_id, 'ref_id', $person['id'] );
                    }
                    delete_post_meta( $gt_member_id, 'bapl' );
                    if ( isset( $person['bapl'] ) && $person['bapl'] ) {
                        add_post_meta( $gt_member_id, 'bapl', $person['bapl'] );
                    }
                    delete_post_meta( $gt_member_id, 'conl' );
                    if ( isset( $person['conl'] ) && $person['conl'] ) {
                        add_post_meta( $gt_member_id, 'conl', $person['conl'] );
                    }
                    delete_post_meta( $gt_member_id, 'endl' );
                    if ( isset( $person['endl'] ) && $person['endl'] ) {
                        add_post_meta( $gt_member_id, 'endl', $person['endl'] );
                    }
                    delete_post_meta( $gt_member_id, 'slgc' );
                    if ( isset( $person['slgc'] ) && $person['slgc'] ) {
                        add_post_meta( $gt_member_id, 'slgc', $person['slgc'] );
                        $have_slgc[] = $gt_member_id;
                    }
                    delete_post_meta( $gt_member_id, 'sex' );
                    if ( isset( $person['sex'] ) && $person['sex'] ) {
                        add_post_meta( $gt_member_id, 'sex', $person['sex'] );
                    }
                    delete_post_meta( $gt_member_id, 'attr' );
                    if ( isset( $person['attr'] ) && is_array( $person['attr'] ) && !empty( $person['attr'] ) ) {
                        foreach ( $person['attr'] as $key => $value ) {
                            add_post_meta( $gt_member_id, 'attr', $value );
                        }
                    }
                    delete_post_meta( $gt_member_id, 'even' );
                    if ( isset( $person['even'] ) && is_array( $person['even'] ) && !empty( $person['even'] ) ) {
                        foreach ( $person['even'] as $key => $value ) {
                            add_post_meta( $gt_member_id, 'even', $value );
                        }
                    }
                    delete_post_meta( $gt_member_id, 'note' );
                    if ( isset( $person['note'] ) && is_array( $person['note'] ) && !empty( $person['note'] ) ) {
                        foreach ( $person['note'] as $key => $value ) {
                            add_post_meta( $gt_member_id, 'note', $value );
                        }
                    }
                    wp_set_object_terms( $gt_member_id, $family_group_id, 'gt-family-group' );
                    $person_map[$person['id']] = $gt_member_id;
                }
            }
        }
        if ( $data['families'] ) {
            foreach ( $data['families'] as $key => $family ) {
                if ( $family['husb'] || $family['wife'] ) {
                    if ( $family['husb'] && $family['wife'] ) {
                        $husb = $person_map[$family['husb']];
                        $wife = $person_map[$family['wife']];
                    }
                    if ( !$family['husb'] && $family['wife'] ) {
                        $husb = null;
                        $wife = $person_map[$family['wife']];
                    }
                    if ( $family['husb'] && !$family['wife'] ) {
                        $wife = null;
                        $husb = $person_map[$family['husb']];
                    }
                    $chil = array();
                    if ( isset( $family['chil'] ) && $family['chil'] && !empty( $family['chil'] ) && is_array( $family['chil'] ) ) {
                        foreach ( $family['chil'] as $key_chil => $chi ) {
                            if ( isset( $person_map[$chi] ) ) {
                                $chil[$key_chil]['id'] = $person_map[$chi];
                                $chil[$key_chil]['pedi'] = '';
                            }
                        }
                    }
                    $family_id = $this->find_or_create_family( $wife, $husb, $chil );
                    delete_post_meta( $family_id, 'ref_id' );
                    if ( isset( $family['id'] ) && $family['id'] ) {
                        add_post_meta( $family_id, 'ref_id', $family['id'] );
                    }
                    delete_post_meta( $family_id, 'slgs' );
                    if ( isset( $family['slgs'] ) && is_array( $family['slgs'] ) && !empty( $family['slgs'] ) ) {
                        foreach ( $family['slgs'] as $key => $value ) {
                            add_post_meta( $family_id, 'slgs', $value );
                        }
                    }
                    delete_post_meta( $family_id, 'even' );
                    if ( isset( $family['even'] ) && is_array( $family['even'] ) && !empty( $family['even'] ) ) {
                        foreach ( $family['even'] as $key => $value ) {
                            add_post_meta( $family_id, 'even', $value );
                        }
                    }
                    wp_set_object_terms( $family_id, $family_group_id, 'gt-family-group' );
                    add_post_meta( $family_id, 'through_import', true );
                    $family_map[$family['id']] = $family_id;
                }
            }
        }
        if ( $data['persons'] ) {
            foreach ( $data['persons'] as $key => $person ) {
                $person_id = $person_map[$person['id']];
                if ( isset( $person['famc'] ) && $person['famc'] && is_array( $person['famc'] ) ) {
                    foreach ( $person['famc'] as $key => $value ) {
                        $value['famc'] = $family_map[$value['famc']];
                        $famc = ( get_post_meta( $person_id, 'famc' ) ? get_post_meta( $person_id, 'famc' ) : array() );
                        foreach ( $famc as $key_famc => $famc_value ) {
                            if ( $famc_value['famc'] ) {
                                $famc[] = (int) $famc_value['famc'];
                                unset($famc[$key_famc]);
                            }
                        }
                        if ( !in_array( (int) $value['famc'], $famc, false ) ) {
                            if ( isset( $value['famc'] ) ) {
                                add_post_meta( $person_id, 'famc', $value );
                            }
                        }
                    }
                }
                if ( isset( $person['fams'] ) && $person['fams'] && is_array( $person['fams'] ) ) {
                    foreach ( $person['fams'] as $key => $value ) {
                        if ( isset( $family_map[$value['fams']] ) ) {
                            $value['fams'] = $family_map[$value['fams']];
                            $fams = ( get_post_meta( $person_id, 'fams' ) ? get_post_meta( $person_id, 'fams' ) : array() );
                            foreach ( $fams as $key_fams => $fams_value ) {
                                if ( $fams_value['fams'] ) {
                                    $fams[] = (int) $fams_value['fams'];
                                    unset($fams[$key_fams]);
                                }
                            }
                            if ( !in_array( (int) $value['fams'], $fams, false ) ) {
                                if ( $value['fams'] ) {
                                    add_post_meta( $person_id, 'fams', $value );
                                }
                            }
                        }
                    }
                }
            }
        }
        if ( $family_map ) {
            foreach ( $family_map as $key => $family_id ) {
                $chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
                $husb = get_post_meta( $family_id, 'husb', true );
                $wife = get_post_meta( $family_id, 'wife', true );
                foreach ( $chil as $key => $member_id ) {
                    $famc = ( get_post_meta( $member_id, 'famc' ) ? get_post_meta( $member_id, 'famc' ) : array() );
                    foreach ( $famc as $key => $value ) {
                        if ( $value['famc'] ) {
                            $famc[] = (int) $value['famc'];
                            unset($famc[$key]);
                        }
                    }
                    if ( !in_array( (int) $family_id, $famc, false ) ) {
                        add_post_meta( $member_id, 'famc', array(
                            'famc' => $family_id,
                            'pedi' => '',
                        ) );
                    }
                }
                if ( $husb ) {
                    $fams = ( get_post_meta( $husb, 'fams' ) ? get_post_meta( $husb, 'fams' ) : array() );
                    foreach ( $fams as $key => $value ) {
                        if ( $value['fams'] ) {
                            $fams[] = (int) $value['fams'];
                            unset($fams[$key]);
                        }
                    }
                    if ( !in_array( (int) $family_id, $fams, false ) ) {
                        add_post_meta( $husb, 'fams', array(
                            'fams' => $family_id,
                        ) );
                    }
                }
                if ( $wife ) {
                    $fams = ( get_post_meta( $wife, 'fams' ) ? get_post_meta( $wife, 'fams' ) : array() );
                    foreach ( $fams as $key => $value ) {
                        if ( $value['fams'] ) {
                            $fams[] = (int) $value['fams'];
                            unset($fams[$key]);
                        }
                    }
                    if ( !in_array( (int) $family_id, $fams, false ) ) {
                        add_post_meta( $wife, 'fams', array(
                            'fams' => $family_id,
                        ) );
                    }
                }
            }
        }
        foreach ( $have_slgc as $member_id ) {
            $slgc = ( get_post_meta( $member_id, 'slgc' ) ? current( get_post_meta( $member_id, 'slgc' ) ) : array(
                'famc' => '',
            ) );
            if ( $slgc['famc'] ) {
                $slgc['famc'] = $family_map[$slgc['famc']];
                delete_post_meta( $member_id, 'slgc' );
                if ( isset( $slgc ) && $slgc ) {
                    add_post_meta( $member_id, 'slgc', $slgc );
                }
            }
        }
        update_option( 'genealogical_tree_last_imported_group', $family_group_id );
    }

    /**
     * It deletes a family if it has no husband, wife, or children
     * 
     * @param family_id The ID of the family you want to check.
     */
    public function repear_family( $family_id ) {
        $chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
        $wife = get_post_meta( $family_id, 'wife', true );
        $husb = get_post_meta( $family_id, 'husb', true );
        $error = array();
        foreach ( $chil as $key => $ch ) {
            if ( is_array( $ch ) || !is_numeric( $ch ) ) {
                $error[] = $ch;
                unset($chil[$key]);
            }
        }
        $chil = array_unique( $chil );
        if ( !empty( $error ) ) {
            delete_post_meta( $family_id, 'chil' );
            foreach ( $chil as $key => $ch ) {
                add_post_meta( $family_id, 'chil', $ch );
            }
        }
        if ( $wife && !$husb && !$chil || $husb && !$wife && !$chil || $chil && !$husb && !$wife || !$wife && !$husb && !$chil ) {
            wp_delete_post( $family_id );
        }
        $member_ids = array();
        $member_ids[] = $wife;
        $member_ids[] = $husb;
        foreach ( $chil as $key => $ch ) {
            $member_ids[] = $ch;
        }
        $this->repear_member( $member_ids );
    }

    /**
     * It removes any family relationships that are not associated with a valid family post
     * 
     * @param array $member_ids An array of member IDs to be repaired.
     */
    public function repear_member( $member_ids ) {
        foreach ( $member_ids as $key => $member ) {
            $famc = ( get_post_meta( $member, 'famc' ) ? get_post_meta( $member, 'famc' ) : array() );
            $error = array();
            foreach ( $famc as $key => $fam ) {
                if ( !get_post( $fam['famc'] ) ) {
                    $error[] = $fam;
                    unset($famc[$key]);
                }
            }
            if ( !empty( $error ) ) {
                delete_post_meta( $member, 'famc' );
                foreach ( $famc as $key => $fam ) {
                    # code...
                    add_post_meta( $member, 'famc', $fam );
                }
            }
            $fams = ( get_post_meta( $member, 'fams' ) ? get_post_meta( $member, 'fams' ) : array() );
            $error = array();
            foreach ( $fams as $key => $fam ) {
                if ( !get_post( $fam['fams'] ) ) {
                    $error[] = $fam;
                    unset($fams[$key]);
                }
            }
            if ( !empty( $error ) ) {
                delete_post_meta( $member, 'fams' );
                foreach ( $fams as $key => $fam ) {
                    add_post_meta( $member, 'fams', $fam );
                }
            }
        }
    }

    /**
     * The function `tree_family_and_root_columns()` is a filter that adds two columns to the array of
     * columns that are displayed in the admin table
     * 
     * the above hook will add columns only for default 'post' post type, for CPT:
     * manage_{POST TYPE NAME}_posts_columns
     * 
     * @param array $column_array This is the array of columns that are currently being displayed.
     * 
     * @return array The array of columns.
     */
    public function tree_family_and_root_columns( $column_array ) {
        $column_array['family'] = 'Family';
        $column_array['root'] = 'Root';
        // the above code will add columns at the end of the array
        // if you want columns to be added in another order, use array_slice()
        return $column_array;
    }

    /**
     * It adds two columns to the admin page for the custom post type, and populates them with the values
     * of the custom fields 'family' and 'root'
     * 
     * @param string $column_name the name of the column you want to populate
     * @param int    $post_id The ID of the post being displayed
     */
    public function tree_populate_family_and_root_columns( $column_name, $post_id ) {
        $tree = get_post_meta( $post_id, 'tree', true );
        $tree = ( $tree ? $tree : array() );
        $family = ( isset( $tree['family'] ) && $tree['family'] ? $tree['family'] : '' );
        $root = ( isset( $tree['root'] ) && $tree['root'] ? $tree['root'] : '' );
        // if you have to populate more that one columns, use switch()
        switch ( $column_name ) {
            case 'family':
                $title = ( $family ? ' - ' . get_term( $family )->name : '' );
                echo '<span>' . $family . '</span>' . $family . $title;
                break;
            case 'root':
                $title = ( $root ? ' - ' . get_the_title( $root ) : '' );
                echo '<span>' . $root . '</span>' . $title;
                break;
        }
    }

    /**
     * It adds a dropdown to the quick edit screen for the custom post type
     * 
     * @param string $column_name The name of the column.
     * @param string $post_type The post type slug.
     */
    public function tree_quick_edit_fields( $column_name, $post_type ) {
        switch ( $column_name ) {
            case 'family':
                ?>
					<fieldset class="inline-edit-col-left">

						<div class="inline-edit-col">
							<label>
								<span class="title">Select Family</span>
								<select id="family" name="family">
									<option value="">
										<?php 
                esc_html_e( 'Select Family', 'genealogical-tree' );
                ?>
									</option>
									<?php 
                $terms = $this->get_gt_family();
                foreach ( $terms as $key => $fg_term ) {
                    ?>
										<option value="<?php 
                    echo esc_attr( $fg_term->term_id );
                    ?>">
											<?php 
                    echo esc_html( $fg_term->term_id );
                    ?> - <?php 
                    echo esc_html( $fg_term->name );
                    ?>
										</option>
										<?php 
                }
                ?>
								</select>
							</label>
						</div>

						<?php 
                break;
            case 'root':
                ?>
						<div class="inline-edit-col">
							<label>
								<span class="title">Select Root</span>
								<select id="root" name="root">
									<option value="">
										<?php 
                esc_html_e( 'Select Root', 'genealogical-tree' );
                ?>
									</option>
									<?php 
                $members = $this->get_gt_member();
                foreach ( $members as $key => $member ) {
                    $term_list = wp_get_post_terms( $member->ID, 'gt-family-group', array(
                        'fields' => 'ids',
                    ) );
                    $term_list = implode( ',', $term_list );
                    ?>
										<option data-famly="<?php 
                    echo esc_attr( $term_list );
                    ?>" value="<?php 
                    echo esc_attr( $member->ID );
                    ?>"> 
											<?php 
                    echo esc_html( $member->ID );
                    ?> - <?php 
                    echo esc_html( $member->post_title );
                    ?>
										</option>
									<?php 
                }
                ?>
								</select>
							</label>
						</div>

					</fieldset>
				<?php 
                break;
        }
    }

    /**
     * If the nonce is valid, update the family and root post meta
     * 
     * @param $post_id $post_id The ID of the post being edited.
     * 
     * @return mixed the value of the variable .
     */
    public function tree_quick_edit_save( $post_id ) {
        // Check inlint edit nonce.
        if ( !isset( $_POST['_inline_edit'] ) || isset( $_POST['_inline_edit'] ) && !wp_verify_nonce( $_POST['_inline_edit'], 'inlineeditnonce' ) ) {
            return;
        }
        $tree = get_post_meta( $post_id, 'tree', true );
        $tree = ( $tree ? $tree : array() );
        $family = ( !empty( $_POST['family'] ) ? sanitize_text_field( $_POST['family'] ) : '' );
        $root = ( !empty( $_POST['root'] ) ? sanitize_text_field( $_POST['root'] ) : '' );
        $tree['family'] = $family;
        $tree['root'] = $root;
        update_post_meta( $post_id, 'tree', $tree );
    }

    /**
     * It returns an array of all the terms in the taxonomy 'gt-family-group' that are associated with the
     * current user
     * 
     * @return array An array of terms.
     */
    public function get_gt_family() {
        $meta_query = array(array(
            'key'     => 'created_by',
            'value'   => get_current_user_id(),
            'compare' => '=',
        ));
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            $meta_query = array();
        }
        $terms = get_terms( array(
            'taxonomy'   => 'gt-family-group',
            'hide_empty' => false,
            'meta_query' => $meta_query,
        ) );
        if ( is_wp_error( $terms ) ) {
            $terms = array();
        }
        return $terms;
    }

    /**
     * It gets all the posts of type `gt-member` that the current user is the author of, and then gets all
     * the posts of type `gt-member` that the current user is allowed to use, and then merges the two
     * arrays and sorts them by ID
     * 
     * @return array An array of member posts.
     */
    public function get_gt_member() {
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'fields'         => 'ids, post_title',
            'author'         => get_current_user_id(),
            'order_by'       => 'ID',
        );
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            unset($args['author']);
        }
        $members = get_posts( $args );
        $args = array(
            'numberposts' => -1,
            'post_type'   => 'gt-member',
            'fields'      => 'ids, post_title',
            'order_by'    => 'ID',
            'meta_query'  => array(array(
                'key'     => 'can_use',
                'value'   => get_current_user_id(),
                'compare' => 'IN',
            )),
        );
        $members = array_merge( $members, get_posts( $args ) );
        usort( $members, array($this, 'sort_member_posts') );
        return $members;
    }

}
