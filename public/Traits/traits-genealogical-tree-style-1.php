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

trait Genealogical_Tree_Style_1 {

	/**
	 * It displays the tree in style 1
	 *
	 * @param  int        $tree The tree to be displayed.
	 * @param  object     $setting The settings for the tree.
	 * @param  int|string $gen The generation of the tree.
	 *
	 * @return string
	 *
	 * @since    1.0.0
	 */
	public function display_tree_style1( $tree, $setting, $gen ) {
		ob_start();
		if($setting->gt_frontend == 'on' && current_user_can('edit_posts') && class_exists('ZQE\Genealogical_Tree_Frontend\Providers\ServiceProvider')){
			?>
			<div class="gt-style-1" id="gt-frontend"  data-rootId="<?php echo $tree; ?>" data-setting='<?php echo wp_json_encode( $setting ); ?>'></div>
			<?php
		} else {
			?>
			<div class="gt-style-1">
				<?php if ( isset( $setting->ancestor ) && 'on' === $setting->ancestor ) { ?>
				<ul class="has-ancestor">
					<li class="parent alter-tree">
						<ul class="parents">
							<?php $this->tree_style_alter( $tree, $setting, ( $gen + 1 ) ); ?>
						</ul>
					</li>
					<li class="child root">
					<?php } ?>
						<ul class="childs">
							<?php $this->tree_style1( $tree, $setting, $gen ); ?>
						</ul>
					<?php if ( isset( $setting->ancestor ) && 'on' === $setting->ancestor ) { ?>
					</li>
				</ul>
				<?php } ?>
			</div>
			<?php
		}
		return ob_get_clean();
	}

	/**
	 * It's a recursive function that displays a tree of people.
	 *
	 * @param  int        $tree The ID of the individual to be displayed.
	 * @param  object     $setting The settings for the tree.
	 * @param  int|string $gen The generation number.
	 * @param  array      $checker An array of all the people who have been displayed in the tree.
	 *
	 * @return void
	 *
	 * @since    1.0.0
	 */
	public function tree_style1( $tree, $setting, $gen = 0, $checker = array() ) {

		$gen++;

		$check_generation_with_gt_fs = $this->check_generation_with_gt_fs( $gen, $setting );

		if ( ! $check_generation_with_gt_fs ) {
			return;
		}

		$families = $this->get_families_by_root( $tree, $setting );

		$collapsible_family_root   = '';
		$collapsible_family_spouse = '';

		if ( $setting->collapsible_family_onload && count( $families ) > 0 && $setting->collapsible_family_root ) {
			$collapsible_family_root = 'display:none;';
		}

		if ( $setting->collapsible_family_onload && count( $families ) > 0 && $setting->collapsible_family_spouse ) {
			$collapsible_family_spouse = 'display:none;';
		}

		$sex = get_post_meta( $tree, 'sex', true );
		?>
		<li class="child root">
			<?php $this->ind_style( $tree, $setting, $gen, $families, 'root', ( $collapsible_family_root ? '+' : '-' ) ); ?>
			<?php if ( $families ) { ?>
				<ul class="families" style="<?php echo esc_attr( $collapsible_family_root ); ?>">
					<?php foreach ( $families as $key => $family ) { ?>
						<?php if ( 'M' === $sex || ( 'F' === $sex && 'on' !== $setting->female_tree ) ) { ?>
							<?php if ( $family->spouse ) { ?>
								<?php array_push( $checker, $family->spouse ); ?>
								<li class="family spouse">
									<?php $this->ind_style( $family->spouse, $setting, null, $family->chil, 'spouse', ( $collapsible_family_spouse ? '+' : '-' ) ); ?>
									<?php if ( $family->chil ) { ?>
										<?php $this->tree_style1__childs( $family->chil, $setting, $gen, $checker, $collapsible_family_spouse ); ?>
									<?php } ?>
								</li>
							<?php } else { ?>
								<?php if ( $family->chil ) { ?>
									<li class="family">
										<?php $this->ind_style_unknown( $setting, $sex ); ?>
										<?php $this->tree_style1__childs( $family->chil, $setting, $gen, $checker ); ?>
									</li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</li>
		<?php
	}

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
	public function tree_style1__childs( $chills, $setting, $gen = 0, $checker = array(), $collapsible_style = '' ) {
		?>
		<ul class="childs" style="<?php echo esc_attr( $collapsible_style ); ?>">
			<?php foreach ( $chills as $key => $chill ) { ?>
				<?php if ( ! in_array( $chill, $checker, true ) ) { ?>
					<?php array_push( $checker, $chill ); ?>
					<?php $this->tree_style1( $chill, $setting, $gen, $checker ); ?>
				<?php } ?>
			<?php } ?>
		</ul>
		<?php
	}
}
