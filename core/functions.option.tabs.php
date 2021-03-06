<?php
/**
 * Handles additional widget tab options
 * run on __construct function
 */
if( !class_exists( 'PHPBITS_extendedWidgetsTabs' ) ):
class PHPBITS_extendedWidgetsTabs {

    private $widgetopts_tabs = array() , $settings = array() ;

    public function __construct() {

        /*
         * Check for transient. If none, then execute Query
         */
        if ( false === ( $widgetopts_tabs = get_transient( 'widgetopts_tabs_transient' ) ) ) {

            $widgetopts_tabs = array(
                'visibility'    => get_option( 'widgetopts_tabmodule-visibility' ),
                'devices'       => get_option( 'widgetopts_tabmodule-devices' ),
                'alignment'     => get_option( 'widgetopts_tabmodule-alignment' ),
                'hide_title'    => get_option( 'widgetopts_tabmodule-hide_title' ),
                'classes'       => get_option( 'widgetopts_tabmodule-classes' ),
                'logic'         => get_option( 'widgetopts_tabmodule-logic' )
            );
            $widgetopts_tabs = maybe_serialize($widgetopts_tabs);
          // Put the results in a transient. Expire after 4 weeks.
          set_transient( 'widgetopts_tabs_transient', $widgetopts_tabs, 4 * WEEK_IN_SECONDS );
        }
        $this->widgetopts_tabs = unserialize( $widgetopts_tabs );
        $this->settings = unserialize( get_option( 'widgetopts_tabmodule-settings' ) );

        if( 'activate' == $this->widgetopts_tabs['visibility'] ){
            add_action( 'extended_widget_opts_tabs', array( &$this,'tab_visibility' ) );
            add_action( 'extended_widget_opts_tabcontent', array( &$this,'content_visibility' ) );
        }
        if( 'activate' == $this->widgetopts_tabs['devices'] ){
            add_action( 'extended_widget_opts_tabs', array( &$this,'tab_devices' ) );
            add_action( 'extended_widget_opts_tabcontent', array( &$this,'content_devices' ) );
        }
        if( 'activate' == $this->widgetopts_tabs['alignment'] ){
            add_action( 'extended_widget_opts_tabs', array( &$this,'tab_alignment' ) );
            add_action( 'extended_widget_opts_tabcontent', array( &$this,'content_alignment' ) );
        }
        if( 'activate' == $this->widgetopts_tabs['classes'] || 'activate' == $this->widgetopts_tabs['hide_title'] ){
            add_action( 'extended_widget_opts_tabs', array( &$this,'tab_class' ) );
            add_action( 'extended_widget_opts_tabcontent', array( &$this,'content_class' ) );
        }
        add_action( 'extended_widget_opts_tabs', array( &$this,'tab_gopro' ) );
        add_action( 'extended_widget_opts_tabcontent', array( &$this,'gopro_alignment' ) );
    }

    /**
     * Called on 'extended_widget_opts_tabs'
     * create new tab navigation for alignment options
     */
    function tab_alignment( $args ){ ?>
        <li class="extended-widget-opts-tab-alignment">
            <a href="#extended-widget-opts-tab-<?php echo $args['id'];?>-alignment" title="<?php _e( 'Alignment', 'widget-options' );?>" ><span class="dashicons dashicons-editor-aligncenter"></span> <span class="tabtitle"><?php _e( 'Alignment', 'widget-options' );?></span></a>
        </li>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabcontent'
     * create new tab content options for alignment options
     */
    function content_alignment( $args ){
        $desktop = '';
        $tablet  = '';
        $mobile  = '';
        if( isset( $args['params'] ) && isset( $args['params']['alignment'] ) ){
            if( isset( $args['params']['alignment']['desktop'] ) ){
                $desktop = $args['params']['alignment']['desktop'];
            }
            if( isset( $args['params']['alignment']['tablet'] ) ){
                $tablet = $args['params']['alignment']['tablet'];
            }
            if( isset( $args['params']['alignment']['mobile'] ) ){
                $mobile = $args['params']['alignment']['mobile'];
            }
        }
        ?>
        <div id="extended-widget-opts-tab-<?php echo $args['id'];?>-alignment" class="extended-widget-opts-tabcontent extended-widget-opts-tabcontent-alignment">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td scope="row"><strong><?php _e( 'Devices', 'widget-options' );?></strong></td>
                        <td><strong><?php _e( 'Alignment', 'widget-options' );?></strong></td>
                    </tr>
                    <tr valign="top">
                        <td scope="row"><span class="dashicons dashicons-desktop"></span> <?php _e( 'All Devices', 'widget-options' );?></td>
                        <td>
                            <select class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][alignment][desktop]">
                                <option value="default"><?php _e( 'Default', 'widget-options' );?></option>
                                <option value="center" <?php if( $desktop == 'center' ){ echo 'selected="selected"'; }?> ><?php _e( 'Center', 'widget-options' );?></option>
                                <option value="left" <?php if( $desktop == 'left' ){ echo 'selected="selected"'; }?>><?php _e( 'Left', 'widget-options' );?></option>
                                <option value="right" <?php if( $desktop == 'right' ){ echo 'selected="selected"'; }?>><?php _e( 'Right', 'widget-options' );?></option>
                                <option value="justify" <?php if( $desktop == 'justify' ){ echo 'selected="selected"'; }?>><?php _e( 'Justify', 'widget-options' );?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top" class="widgetopts-topro">
                        <td colspan="2"><small><?php _e( '<em>Upgrade to <a href="https://phpbits.net/plugin/extended-widget-options/" target="_blank">Pro Version</a> for Multiple Devices Alignment and Additional Widget Options.</em>', 'widget-options' );?></small></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabs'
     * create new tab navigation for visibility options
     */
    function tab_visibility( $args ){ ?>
        <li class="extended-widget-opts-tab-visibility">
            <a href="#extended-widget-opts-tab-<?php echo $args['id'];?>-visibility" title="<?php _e( 'Visibility', 'widget-options' );?>" ><span class="dashicons dashicons-visibility"></span> <span class="tabtitle"><?php _e( 'Visibility', 'widget-options' );?></span></a>
        </li>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabcontent'
     * create new tab content options for visibility options
     */
    function content_visibility( $args ){
        $checked    = "";
        $selected   = 0;

        //declare miscellaneous pages - wordpress default pages
        $misc       = array(
                        'home'      =>  __( 'Home/Front', 'widget-options' ),
                        'blog'      =>  __( 'Blog', 'widget-options' ),
                        'archives'  =>  __( 'Archives', 'widget-options' ),
                        'single'    =>  __( 'Single Post', 'widget-options' ),
                        '404'       =>  __( '404', 'widget-options' ),
                        'search'    =>  __( 'Search', 'widget-options' )
                    );

        /*
         * get available pages
         * Check for transient. If none, then execute Query
         */
        if ( false === ( $pages = get_transient( 'widgetopts_pages' ) ) ) {

            $pages  = get_posts( array(
                                'post_type'     => 'page',
                                'post_status'   => 'publish',
                                'numberposts'   => -1,
                                'orderby'       => 'title',
                                'order'         => 'ASC',
                                'fields'        => array('ID', 'name')
                    ));

          // Put the results in a transient. Expire after 4 weeks.
          set_transient( 'widgetopts_pages', $pages, 4 * WEEK_IN_SECONDS );
        }


        /*
         * get all post types
         * Check for transient. If none, then execute Query
         */
        if ( false === ( $types = get_transient( 'widgetopts_types' ) ) ) {

            $types  = get_post_types( array(
                            'public' => true,
                    ), 'object' );

          // Put the results in a transient. Expire after 10minutes.
          set_transient( 'widgetopts_types', $types, 10 * 60 );
        }

        //unset builtin post types
        foreach ( array( 'revision', 'attachment', 'nav_menu_item' ) as $unset ) {
            unset( $types[ $unset ] );
        }

        /*
         * get post categories
         * Check for transient. If none, then execute Query
         */
        if ( false === ( $categories = get_transient( 'widgetopts_categories' ) ) ) {

            $categories = get_categories( array(
                        'hide_empty'    => false
                    ) );

          // Put the results in a transient. Expire after 4 WEEKS.
          set_transient( 'widgetopts_categories', $categories, 4 * WEEK_IN_SECONDS );

        }


        $taxonomies = array();


        //get save values
        $options_values = '';
        $misc_values    = array();
        $pages_values   = array();
        $types_values   = array();
        $cat_values     = array();
        $tax_values     = array();
        if( isset( $args['params'] ) && isset( $args['params']['visibility'] ) ){
            if( isset( $args['params']['visibility']['options'] ) ){
                $options_values = $args['params']['visibility']['options'];
            }

            if( isset( $args['params']['visibility']['misc'] ) ){
                $misc_values = $args['params']['visibility']['misc'];
            }

            if( isset( $args['params']['visibility']['pages'] ) ){
                $pages_values = $args['params']['visibility']['pages'];
            }

            if( isset( $args['params']['visibility']['types'] ) ){
                $types_values = $args['params']['visibility']['types'];
            }

            if( isset( $args['params']['visibility']['categories'] ) ){
                $cat_values = $args['params']['visibility']['categories'];
            }

            if( isset( $args['params']['visibility']['taxonomies'] ) ){
                $tax_values = $args['params']['visibility']['taxonomies'];
            }
            if( isset( $args['params']['visibility']['selected'] ) ){
                $selected = $args['params']['visibility']['selected'];
            }
        }

        ?>
        <div id="extended-widget-opts-tab-<?php echo $args['id'];?>-visibility" class="extended-widget-opts-tabcontent extended-widget-opts-inside-tabcontent extended-widget-opts-tabcontent-visibility">
            <p><strong><?php _e( 'Hide/Show', 'widget-options' );?></strong>
            <select class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][options]">
                <option value="hide" <?php if( $options_values == 'hide' ){ echo 'selected="selected"'; }?> ><?php _e( 'Hide on checked pages', 'widget-options' );?></option>
                <option value="show" <?php if( $options_values == 'show' ){ echo 'selected="selected"'; }?>><?php _e( 'Show on checked pages', 'widget-options' );?></option>
            </select>
            </p>

            <div class="extended-widget-opts-visibility-tabs extended-widget-opts-inside-tabs">
                <input type="hidden" id="extended-widget-opts-visibility-selectedtab" value="<?php echo $selected;?>" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][selected]" />
                <!--  start tab nav -->
                <ul class="extended-widget-opts-visibility-tabnav-ul">
                    <?php if( isset( $this->settings['visibility'] ) &&
                              isset( $this->settings['visibility']['misc'] ) &&
                              '1' == $this->settings['visibility']['misc'] ){ ?>
                        <li class="extended-widget-opts-visibility-tab-visibility">
                            <a href="#extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-misc" title="<?php _e( 'Home, Blog, Search, etc..', 'widget-options' );?>" ><?php _e( 'Misc', 'widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <?php if( isset( $this->settings['visibility'] ) &&
                              isset( $this->settings['visibility']['post_type'] ) &&
                              '1' == $this->settings['visibility']['post_type'] ){ ?>
                        <li class="extended-widget-opts-visibility-tab-visibility">
                            <a href="#extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-types" title="<?php _e( 'Pages & Custom Post Types', 'widget-options' );?>" ><?php _e( 'Post Types', 'widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <?php if( isset( $this->settings['visibility'] ) &&
                              isset( $this->settings['visibility']['taxonomies'] ) &&
                              '1' == $this->settings['visibility']['taxonomies'] ){ ?>
                        <li class="extended-widget-opts-visibility-tab-visibility">
                            <a href="#extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-tax" title="<?php _e( 'Categories, Tags & Taxonomies', 'widget-options' );?>" ><?php _e( 'Taxonomies', 'widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <div class="extended-widget-opts-clearfix"></div>
                </ul><!--  end tab nav -->
                <div class="extended-widget-opts-clearfix"></div>

                <?php if( isset( $this->settings['visibility'] ) &&
                          isset( $this->settings['visibility']['misc'] ) &&
                          '1' == $this->settings['visibility']['misc'] ){ ?>
                    <!--  start misc tab content -->
                    <div id="extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-misc" class="extended-widget-opts-visibility-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="extended-widget-opts-misc">
                            <?php foreach ($misc as $key => $value) {
                                if( isset( $misc_values[ $key ] ) && $misc_values[ $key ] == '1' ){
                                    $checked = 'checked="checked"';
                                }else{
                                    $checked = '';
                                }
                                ?>
                                <p>
                                    <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][misc][<?php echo $key;?>]" id="<?php echo $args['id'];?>-opts-misc-<?php echo $key;?>" value="1" <?php echo $checked;?> />
                                    <label for="<?php echo $args['id'];?>-opts-misc-<?php echo $key;?>"><?php echo $value;?></label>
                                </p>
                            <?php } ?>
                        </div>
                    </div><!--  end misc tab content -->
                <?php } ?>

                <?php if( isset( $this->settings['visibility'] ) &&
                          isset( $this->settings['visibility']['post_type'] ) &&
                          '1' == $this->settings['visibility']['post_type'] ){ ?>
                    <!--  start types tab content -->
                    <div id="extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-types" class="extended-widget-opts-visibility-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="extended-widget-opts-inner-lists" style="height: 230px;padding: 5px;overflow:auto;">
                            <h4 id="extended-widget-opts-pages"><?php _e( 'Pages', 'widget-options' );?> +/-</h4>
                            <div class="extended-widget-opts-pages">
                                <?php foreach ($pages as $page) {
                                        if( isset( $pages_values[ $page->ID ] ) && $pages_values[ $page->ID ] == '1' ){
                                            $checked = 'checked="checked"';
                                        }else{
                                            $checked = '';
                                        }
                                    ?>
                                    <p>
                                        <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][pages][<?php echo $page->ID;?>]" id="<?php echo $args['id'];?>-opts-pages-<?php echo $page->ID;?>" value="1" <?php echo $checked;?> />
                                        <label for="<?php echo $args['id'];?>-opts-pages-<?php echo $page->ID;?>"><?php echo $page->post_title;?></label>
                                    </p>
                                <?php } ?>
                            </div>

                            <h4 id="extended-widget-opts-types"><?php _e( 'Custom Post Types', 'widget-options' );?> +/-</h4>
                            <div class="extended-widget-opts-types">
                                <?php foreach ($types as $ptype => $type) {
                                    // if ( ! $type->has_archive ) {
                                    //     // don't give the option if there is no archive page
                                    //     continue;
                                    // }

                                        if( isset( $types_values[ $ptype ] ) && $types_values[ $ptype ] == '1' ){
                                            $checked = 'checked="checked"';
                                        }else{
                                            $checked = '';
                                        }
                                    ?>
                                    <p>
                                        <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][types][<?php echo $ptype;?>]" id="<?php echo $args['id'];?>-opts-types-<?php echo $ptype;?>" value="1" <?php echo $checked;?> />
                                        <label for="<?php echo $args['id'];?>-opts-types-<?php echo $ptype;?>"><?php echo stripslashes( $type->labels->name );?></label>
                                    </p>
                                <?php
                                    /*
                                     * get post type taxonomies
                                     * Check for transient. If none, then execute Query
                                     */
                                    if ( false === ( $post_taxes = get_transient( 'widgetopts_post_taxes_'. $ptype ) ) ) {

                                        $post_taxes = get_object_taxonomies( $ptype );

                                      // Put the results in a transient. Expire after 5 minutes.
                                      set_transient( 'widgetopts_post_taxes_'. $ptype, $post_taxes, 5 * 60 );
                                    }


                                    foreach ( $post_taxes as $post_tax) {
                                        if ( in_array( $post_tax, array( 'category', 'post_format' ) ) ) {
                                            continue;
                                        }

                                        $taxonomy = get_taxonomy( $post_tax );
                                        $name = $post_tax;

                                        if ( isset( $taxonomy->labels->name ) && ! empty( $taxonomy->labels->name ) ) {
                                            $name = $taxonomy->labels->name . ' <small>'. $type->labels->name .'</small>';
                                        }

                                        $taxonomies[ $post_tax ] = $name;
                                    }
                                } ?>
                            </div>
                        </div>
                    </div><!--  end types tab content -->
                <?php } ?>

                <?php if( isset( $this->settings['visibility'] ) &&
                          isset( $this->settings['visibility']['taxonomies'] ) &&
                          '1' == $this->settings['visibility']['taxonomies'] ){ ?>
                    <!--  start tax tab content -->
                    <div id="extended-widget-opts-visibility-tab-<?php echo $args['id'];?>-tax" class="extended-widget-opts-visibility-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="extended-widget-opts-inner-lists" style="height: 230px;padding: 5px;overflow:auto;">
                            <h4 id="extended-widget-opts-categories"><?php _e( 'Categories', 'widget-options' );?> +/-</h4>
                            <div class="extended-widget-opts-categories">
                                <p>
                                    <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][categories][all_categories]" id="<?php echo $args['id'];?>-opts-categories-all" value="1" <?php if( isset( $cat_values['all_categories'] ) ){ echo 'checked="checked"'; };?> />
                                    <label for="<?php echo $args['id'];?>-opts-categories-all"><?php _e( 'All Categories', 'widget-options' );?></label>
                                </p>
                                <?php foreach ($categories as $cat) {
                                        if( isset( $cat_values[ $cat->cat_ID ] ) && $cat_values[ $cat->cat_ID ] == '1' ){
                                            $checked = 'checked="checked"';
                                        }else{
                                            $checked = '';
                                        }
                                    ?>
                                    <p>
                                        <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][categories][<?php echo $cat->cat_ID;?>]" id="<?php echo $args['id'];?>-opts-categories-<?php echo $cat->cat_ID;?>" value="1" <?php echo $checked;?> />
                                        <label for="<?php echo $args['id'];?>-opts-categories-<?php echo $cat->cat_ID;?>"><?php echo $cat->cat_name;?></label>
                                    </p>
                                <?php } ?>
                            </div>

                            <h4 id="extended-widget-opts-taxonomies"><?php _e( 'Taxonomies', 'widget-options' );?> +/-</h4>
                            <div class="extended-widget-opts-taxonomies">
                                <?php foreach ($taxonomies as $tax_key => $tax_label) {
                                        if( isset( $tax_values[ $tax_key ] ) && $tax_values[ $tax_key ] == '1' ){
                                            $checked = 'checked="checked"';
                                        }else{
                                            $checked = '';
                                        }
                                    ?>
                                    <p>
                                        <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][visibility][taxonomies][<?php echo $tax_key;?>]" id="<?php echo $args['id'];?>-opts-taxonomies-<?php echo $tax_key;?>" value="1" <?php echo $checked;?> />
                                        <label for="<?php echo $args['id'];?>-opts-taxonomies-<?php echo $tax_key;?>"><?php echo $tax_label;?></label>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div><!--  end tax tab content -->
                <?php } ?>
            </div>

        </div>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabs'
     * create new tab navigation for devices visibility options
     */
    function tab_devices( $args ){ ?>
        <li class="extended-widget-opts-tab-devices">
            <a href="#extended-widget-opts-tab-<?php echo $args['id'];?>-devices" title="<?php _e( 'Devices', 'widget-options' );?>" ><span class="dashicons dashicons-smartphone"></span> <span class="tabtitle"><?php _e( 'Devices', 'widget-options' );?></span></a>
        </li>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabcontent'
     * create new tab content options for devices visibility options
     */
    function content_devices( $args ){
        $desktop        = '';
        $tablet         = '';
        $mobile         = '';
        $options_role   = '';
        if( isset( $args['params'] ) && isset( $args['params']['devices'] ) ){
            if( isset( $args['params']['devices']['options'] ) ){
                $options_role = $args['params']['devices']['options'];
            }
            if( isset( $args['params']['devices']['desktop'] ) ){
                $desktop = $args['params']['devices']['desktop'];
            }
            if( isset( $args['params']['devices']['tablet'] ) ){
                $tablet = $args['params']['devices']['tablet'];
            }
            if( isset( $args['params']['devices']['mobile'] ) ){
                $mobile = $args['params']['devices']['mobile'];
            }
        }
        ?>
        <div id="extended-widget-opts-tab-<?php echo $args['id'];?>-devices" class="extended-widget-opts-tabcontent extended-widget-opts-tabcontent-devices">
            <p>
                <strong><?php _e( 'Hide/Show', 'widget-options' );?></strong>
                <select class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][devices][options]">
                    <option value="hide" <?php if( $options_role == 'hide' ){ echo 'selected="selected"'; }?> ><?php _e( 'Hide on checked devices', 'widget-options' );?></option>
                    <option value="show" <?php if( $options_role == 'show' ){ echo 'selected="selected"'; }?>><?php _e( 'Show on checked devices', 'widget-options' );?></option>
                </select>
            </p>
            <table class="form-table">
                <tbody>
                     <tr valign="top">
                        <td scope="row"><strong><?php _e( 'Devices', 'widget-options' );?></strong></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td scope="row">
                            <label for="opts-devices-desktop-<?php echo $args['id'];?>">
                                <span class="dashicons dashicons-desktop"></span> <?php _e( 'Desktop', 'widget-options' );?>
                            </label>
                            </td>
                        <td>
                            <input type="checkbox" id="opts-devices-desktop-<?php echo $args['id'];?>" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][devices][desktop]" value="1" <?php  if( !empty( $desktop ) ){ echo 'checked="checked"'; }?> />
                        </td>
                    </tr>
                    <tr valign="top">
                        <td scope="row">
                            <label for="opts-devices-tablet-<?php echo $args['id'];?>">
                                <span class="dashicons dashicons-tablet"></span> <?php _e( 'Tablet', 'widget-options' );?>
                            </label>
                        </td>
                        <td>
                            <input type="checkbox" id="opts-devices-tablet-<?php echo $args['id'];?>" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][devices][tablet]" value="1" <?php  if( !empty( $tablet ) ){ echo 'checked="checked"'; }?> />
                        </td>
                    </tr>
                    <tr valign="top">
                        <td scope="row">
                            <label for="opts-devices-mobile-<?php echo $args['id'];?>">
                                <span class="dashicons dashicons-smartphone"></span> <?php _e( 'Mobile', 'widget-options' );?>
                            </label>
                        </td>
                        <td>
                            <input type="checkbox" id="opts-devices-mobile-<?php echo $args['id'];?>" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][devices][mobile]" value="1" <?php  if( !empty( $mobile ) ){ echo 'checked="checked"'; }?> />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php
    }

    /**
     * Called on 'extended_widget_opts_tabs'
     * create new tab navigation for custom class & ID options
     */
    function tab_class( $args ){ ?>
        <li class="extended-widget-opts-tab-class">
            <a href="#extended-widget-opts-tab-<?php echo $args['id'];?>-class" title="<?php _e( 'Class,ID & Display Logic', 'widget-options' );?>" ><span class="dashicons dashicons-admin-generic"></span> <span class="tabtitle"><?php _e( 'Class,ID & Logic', 'widget-options' );?></span></a>
        </li>
    <?php
    }

    function content_class( $args ){
        $id         = '';
        $classes    = '';
        $logic      = '';
        $selected   = 0;
        $check      = '';
        if( isset( $args['params'] ) && isset( $args['params']['class'] ) ){
            if( isset( $args['params']['class']['id'] ) ){
                $id = $args['params']['class']['id'];
            }
            if( isset( $args['params']['class']['classes'] ) ){
                $classes = $args['params']['class']['classes'];
            }
            if( isset( $args['params']['class']['selected'] ) ){
                $selected = $args['params']['class']['selected'];
            }
            if( isset( $args['params']['class']['logic'] ) ){
                $logic = $args['params']['class']['logic'];
            }
            if( isset( $args['params']['class']['title'] ) && $args['params']['class']['title'] == '1' ){
                $check = 'checked="checked"';
            }
        }

        $options    = get_option('extwopts_class_settings');
        $predefined = array();
        if( isset( $this->settings['classes'] ) && isset( $this->settings['classes']['classlists'] ) && !empty( $this->settings['classes']['classlists'] ) ){
            $predefined = $this->settings['classes']['classlists'];
        }
        ?>
        <div id="extended-widget-opts-tab-<?php echo $args['id'];?>-class" class="extended-widget-opts-tabcontent extended-widget-opts-inside-tabcontent extended-widget-opts-tabcontent-class">

            <div class="extended-widget-opts-settings-tabs extended-widget-opts-inside-tabs">
                <input type="hidden" id="extended-widget-opts-settings-selectedtab" value="<?php echo $selected;?>" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][selected]" />
                <!--  start tab nav -->
                <ul class="extended-widget-opts-settings-tabnav-ul">
                    <?php if( 'activate' == $this->widgetopts_tabs['hide_title'] ){ ?>
                        <li class="extended-widget-opts-settings-tab-title">
                            <a href="#extended-widget-opts-settings-tab-<?php echo $args['id'];?>-title" title="<?php _e( 'Misc', 'extended-widget-options' );?>" ><?php _e( 'Misc', 'extended-widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <?php if( 'activate' == $this->widgetopts_tabs['classes'] ){ ?>
                        <li class="extended-widget-opts-settings-tab-class">
                            <a href="#extended-widget-opts-settings-tab-<?php echo $args['id'];?>-class" title="<?php _e( 'Class & ID', 'widget-options' );?>" ><?php _e( 'Class & ID', 'widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <?php if( 'activate' == $this->widgetopts_tabs['logic'] ){ ?>
                        <li class="extended-widget-opts-settings-tab-logic">
                            <a href="#extended-widget-opts-settings-tab-<?php echo $args['id'];?>-logic" title="<?php _e( 'Display Logic', 'widget-options' );?>" ><?php _e( 'Display Logic', 'widget-options' );?></a>
                        </li>
                    <?php } ?>
                    <div class="extended-widget-opts-clearfix"></div>
                </ul><!--  end tab nav -->
                <div class="extended-widget-opts-clearfix"></div>

                <?php if( 'activate' == $this->widgetopts_tabs['hide_title'] ){ ?>
                    <!--  start title tab content -->
                    <div id="extended-widget-opts-settings-tab-<?php echo $args['id'];?>-title" class="extended-widget-opts-settings-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="widget-opts-title">
                            <?php if( 'activate' == $this->widgetopts_tabs['hide_title'] ){ ?>
                                <p>
                                    <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][title]" id="opts-class-title-<?php echo $args['id'];?>" value="1" <?php echo $check;?> />
                                    <label for="opts-class-title-<?php echo $args['id'];?>"><?php _e( 'Check to hide widget title', 'extended-widget-options' );?></label>
                                </p>
                            <?php } ?>
                        </div>
                    </div><!--  end title tab content -->
                <?php } ?>

                <?php if( 'activate' == $this->widgetopts_tabs['classes'] ){ ?>
                    <!--  start class tab content -->
                    <div id="extended-widget-opts-settings-tab-<?php echo $args['id'];?>-class" class="extended-widget-opts-settings-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="widget-opts-class">
                            <table class="form-table">
                            <tbody>
                                <?php if( isset( $this->settings['classes'] ) && ( isset( $this->settings['classes']['id'] ) && '1' == $this->settings['classes']['id'] ) ){?>
                                    <tr valign="top">
                                        <td scope="row">
                                            <strong><?php _e( 'Widget CSS ID:', 'widget-options' );?></strong><br />
                                            <input type="text" id="opts-class-id-<?php echo $args['id'];?>" class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][id]" value="<?php echo $id;?>" />
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if( !isset( $this->settings['classes'] ) ||
                                         ( isset( $this->settings['classes'] ) && isset( $this->settings['classes']['type'] ) && !in_array( $this->settings['classes']['type'] , array( 'hide', 'predefined' ) ) ) ){ ?>
                                    <tr valign="top">
                                        <td scope="row">
                                            <strong><?php _e( 'Widget CSS Classes:', 'widget-options' );?></strong><br />
                                            <input type="text" id="opts-class-classes-<?php echo $args['id'];?>" class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][classes]" value="<?php echo $classes;?>" />
                                            <small><em><?php _e( 'Separate each class with space.', 'widget-options' );?></em></small>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if( !isset( $this->settings['classes'] ) ||
                                         ( isset( $this->settings['classes'] ) && isset( $this->settings['classes']['type'] ) && !in_array( $this->settings['classes']['type'] , array( 'hide', 'text' ) ) ) ){ ?>
                                    <?php if( is_array( $predefined ) && !empty( $predefined ) ){
                                        $predefined = array_unique( $predefined ); //remove dups
                                        ?>
                                            <tr valign="top">
                                                <td scope="row">
                                                    <strong><?php _e( 'Available Widget Classes:', 'widget-options' );?></strong><br />
                                                    <div class="extended-widget-opts-class-lists" style="max-height: 230px;padding: 5px;overflow:auto;">
                                                        <?php foreach ($predefined as $key => $value) {
                                                            if(  isset( $args['params']['class']['predefined'] ) &&
                                                                 is_array( $args['params']['class']['predefined'] ) &&
                                                                 in_array( $value , $args['params']['class']['predefined'] ) ){
                                                                $checked = 'checked="checked"';
                                                            }else{
                                                                $checked = '';
                                                            }
                                                            ?>
                                                            <p>
                                                                <input type="checkbox" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][predefined][]" id="<?php echo $args['id'];?>-opts-class-<?php echo $key;?>" value="<?php echo $value;?>" <?php echo $checked;?> />
                                                                <label for="<?php echo $args['id'];?>-opts-class-<?php echo $key;?>"><?php echo $value;?></label>
                                                            </p>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }else{ ?>
                                            <tr valign="top">
                                                <td scope="row">
                                                    <small><a href="<?php echo esc_url( admin_url( 'options-general.php?page=widgetopts_plugin_settings&module=classes' ) );?>" target="_blank"><?php _e( 'Click here to create predefined classes.', 'extended-widget-options' );?></a></small>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                <?php } ?>
                            </tbody>
                            </table>
                        </div>
                    </div><!--  end class tab content -->
                <?php } ?>

                <?php if( 'activate' == $this->widgetopts_tabs['logic'] ){ ?>
                    <!--  start logiv tab content -->
                    <div id="extended-widget-opts-settings-tab-<?php echo $args['id'];?>-logic" class="extended-widget-opts-settings-tabcontent extended-widget-opts-inner-tabcontent">
                        <div class="widget-opts-logic">
                            <p><small><?php _e( 'The text field lets you use <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">WP Conditional Tags</a>, or any general PHP code.', 'widget-options' );?></small></p>
                            <textarea class="widefat" name="extended_widget_opts-<?php echo $args['id'];?>[extended_widget_opts][class][logic]"><?php echo stripslashes( $logic );?></textarea>
                            <?php if( !isset( $this->settings['logic'] ) ||
                                     ( isset( $this->settings['logic']  ) && !isset( $this->settings['logic']['notice']  ) ) ){ ?>
                                         <p><a href="#" class="widget-opts-toggler-note"><?php _e( 'Click to Toggle Note', 'widget-options' );?></a></p>
                                         <p class="widget-opts-toggle-note"><small><?php _e( 'PLEASE NOTE that the display logic you introduce is EVAL\'d directly. Anyone who has access to edit widget appearance will have the right to add any code, including malicious and possibly destructive functions. There is an optional filter <em>"widget_options_logic_override"</em> which you can use to bypass the EVAL with your own code if needed.', 'widget-options' );?></small></p>
                            <?php } ?>
                        </div>
                    </div><!--  end logiv tab content -->
                <?php } ?>

            </div><!-- end .extended-widget-opts-settings-tabs -->

        </div>
    <?php
    }

    /**
     * Called on 'extended_widget_pro_tabs'
     * create new tab navigation for alignment options
     */
    function tab_gopro( $args ){ ?>
        <li class="extended-widget-gopro-tab-alignment">
            <a href="#extended-widget-opts-tab-<?php echo $args['id'];?>-gopro">+</a>
        </li>
    <?php
    }

    function gopro_alignment( $args ){ ?>
        <div id="extended-widget-opts-tab-<?php echo $args['id'];?>-gopro" class="extended-widget-opts-tabcontent extended-widget-opts-tabcontent-gopro">
            <p class="widgetopts-unlock-features">
                <span class="dashicons dashicons-lock"></span><?php _e( 'Unlock all Options', 'widget-options' );?>
            </p>
            <p>
                <?php _e( 'Get the world\'s most complete widget management and get the best out of your widgets! Upgrade to extended version to get:', 'widget-options' );?>
            </p>
            <ul>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Animation Options', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Custom Styling Options', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Column Display', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'User Roles Visibility Restriction', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Fixed/Sticky Widget Options', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Days and Date Range Restriction', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Link Widget Options', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Shortcodes Options', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Extended Taxonomy and Post Types Support', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Disable Widgets and Permissions', 'widget-options' );?>
                </li>
                <li>
                    <span class="dashicons dashicons-lock"></span> <?php _e( 'Pagebuilder by SiteOrigin Support', 'widget-options' );?>
                </li>
            </ul>
            <p><strong><a href="http://widget-options.com/?utm_source=wordpressadmin&utm_medium=widgettabs&utm_campaign=widgetoptsprotab" class="button-primary" target="_blank"><?php _e( 'Learn More', 'widget-options' );?></a></strong></p>
        </div>
    <?php
    }


    /**
     * Called on 'sidebar_admin_setup'
     * adds in the admin control per widget, but also processes import/export
     */
}
new PHPBITS_extendedWidgetsTabs();
endif;
?>
