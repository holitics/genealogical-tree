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
namespace Zqe\Traits;

trait Genealogical_Tree_Style_4
{
    /**
     * It's a recursive function that prints out a tree of categories.
     *
     * @param  array      $chills The childs of the current category.
     * @param  object     $setting The setting array.
     * @param  int|string $gen The generation of the current category.
     * @param  array      $checker This is an array that contains all the categories that have been displayed.
     * @param  string     $collapsible_style This is the CSS that will be applied to the childs ul.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function tree_style4__childs(
        $chills,
        $setting,
        $gen = 0,
        $checker = array(),
        $collapsible_style = ''
    ) {
        ?>
		<ul class="childs" style="<?php 
        echo esc_attr( $collapsible_style );
        ?>">
			<?php 
        foreach ( $chills as $key => $chill ) {
            ?>
				<?php 
            if ( !in_array( $chill, $checker, true ) ) {
                ?>
					<?php 
                array_push( $checker, $chill );
                ?>
					<?php 
                $this->tree_style4__premium_only(
                    $chill,
                    $setting,
                    $gen,
                    $checker
                );
                ?>
				<?php 
            }
            ?>
			<?php 
        }
        ?>
		</ul>
		<?php 
    }

}