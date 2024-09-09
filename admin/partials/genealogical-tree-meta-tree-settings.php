<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin/partials
 */

?>

<?php

$meta_query = array(
	array(
		'key'     => 'created_by',
		'value'   => get_current_user_id(),
		'compare' => '=',
	),
);

if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
	$meta_query = array();
}

$terms = get_terms(
	array(
		'taxonomy'   => 'gt-family-group',
		'hide_empty' => false,
		'meta_query' => $meta_query,
	)
);

if ( is_wp_error( $terms ) ) {
	$terms = array();
}
?>
<?php wp_nonce_field( 'update_tree_settings_nonce', '_nonce_update_tree_settings_nonce' ); ?>

<style type="text/css">
	tr.pro > td > label {
		color: red;
	}
	tr.pro > td > label:after {
		content: '* ( Pro )'
	}
</style>

<table border="0" class="gt-tree">
	<tbody>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'General Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr>
			<td width="160">
				<label for="family">
					<?php esc_html_e( 'Select Family', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select id="family" name="tree[family]">
					<option value="0">
						<?php esc_html_e( 'Select Family', 'genealogical-tree' ); ?>
					</option>
					<?php
					foreach ( $terms as $key => $fg_term ) {
						?>
						<option <?php selected( $data['family'], $fg_term->term_id ); ?> value="<?php echo esc_attr( $fg_term->term_id ); ?>">
							<?php echo esc_html( $fg_term->term_id ); ?> - <?php echo esc_html( $fg_term->name ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="root">
					<?php esc_html_e( 'Select Root', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<?php
				$args = array(
					'post_type'      => 'gt-member',
					'posts_per_page' => -1,
					'fields'         => 'ids, post_title',
					'author'         => get_current_user_id(),
					'order_by'       => 'ID',
				);
				if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					unset( $args['author'] );
				}
				$members = get_posts( $args );
				$args    = array(
					'numberposts' => -1,
					'post_type'   => 'gt-member',
					'fields'      => 'ids, post_title',
					'order_by'    => 'ID',
					'meta_query'  => array(
						array(
							'key'     => 'can_use',
							'value'   => get_current_user_id(),
							'compare' => 'IN',
						),
					),
				);
				$members = array_merge( $members, get_posts( $args ) );

				usort( $members, array( $this, 'sort_member_posts' ) );

				?>
				<select id="root" name="tree[root]">
					<option value="0">
						<?php esc_html_e( 'Select Root', 'genealogical-tree' ); ?>
					</option>
					<?php
					foreach ( $members as $key => $member ) {
						$term_list = wp_get_post_terms( $member->ID, 'gt-family-group', array( 'fields' => 'ids' ) );
						$term_list = implode( ',', $term_list );
						?>
						<option <?php selected( $data['root'], $member->ID ); ?> data-famly="<?php echo esc_attr( $term_list ); ?>" value="<?php echo esc_attr( $member->ID ); ?>"> 
							<?php echo esc_html( $member->ID ); ?> - <?php echo esc_html( $member->post_title ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="root_highlight">
					<?php esc_html_e( 'Highlight Root', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" id="root_highlight" name="tree[root_highlight]" <?php checked( $data['root_highlight'], 'on' ); ?> >
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="gt_frontend">
					<?php esc_html_e( 'GT Frontend', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" >
			</td>
		</tr>
		<tr>
			<td>
				<label for="style">
					<?php esc_html_e( 'Select Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select id="style" name="tree[style]">
					<option <?php selected( $data['style'], '1' ); ?> value="1">
						<?php esc_html_e( 'Style 1', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '2' ); ?> value="2">
						<?php esc_html_e( 'Style 2', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '2-alt' ); ?> value="2-alt">
						<?php esc_html_e( 'Style 2-Alt', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '3' ); ?> value="3">
						<?php esc_html_e( 'Style 3', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '3-alt' ); ?> value="3-alt">
						<?php esc_html_e( 'Style 3-Alt', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '4' ); ?> value="4">
						<?php esc_html_e( 'Style 4', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $data['style'], '5' ); ?> value="5">
						<?php esc_html_e( 'Style 5', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4">
				<small>
					<i><?php esc_html_e( 'Style 1, Style 4 and Style 5 support separate child for separate spouse', 'genealogical-tree' ); ?> </i>
				</small>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Select Layout', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<option selected>
						<?php esc_html_e( 'Vertical', 'genealogical-tree' ); ?>
					</option>
					<option disabled>
						<?php esc_html_e( 'Horizontal', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Enable Ajax', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>


		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Enable Popup', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Hide Female Tree', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>


		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Hide Spouse', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
				<span><i><?php esc_html_e( 'For Style 2, Style 3, Style 2-Alt, Style 3-Alt', 'genealogical-tree' ); ?> </i></span>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Hide Unknown Spouse', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
				<span><i> <?php esc_html_e( 'For Style 2, Style 3, Style 2-Alt, Style 3-Alt', 'genealogical-tree' ); ?></i></span>
			</td>
		</tr>

		<tr class="pro">
			<td style="vertical-align: top;">
				<label>
					<?php esc_html_e( 'Collapsible Family', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="2">
				<input type="checkbox" disabled>
				<?php esc_html_e( 'Root', 'genealogical-tree' ); ?>
				<input type="checkbox" disabled>
				<?php esc_html_e( 'Spouse', 'genealogical-tree' ); ?>
			</td>
			<td colspan="2" style="vertical-align: top;">
				<input type="checkbox" disabled>
				<?php esc_html_e( 'Collaps Onload', 'genealogical-tree' ); ?>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td colspan="5">
				<span><i><?php esc_html_e( 'Spouse option not for: Style 3, Style 3-Alt, Style 2, Style 2-Alt', 'genealogical-tree' ); ?></i></span>
			</td>
		</tr>
		<tr>
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_color">
					<?php esc_html_e( 'Background Color', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<input type="text" id="container_background_color" value="<?php echo esc_attr( $data['background']['color'] ); ?>" name="tree[background][color]"><br>
				<i><?php esc_html_e( 'HEX/RGB/RGBA', 'genealogical-tree' ); ?></i>
			</td>
		</tr>
		<tr class="pro">
			<td valign="top" style="vertical-align:top;">
				<label>
					<?php esc_html_e( 'Marriage Icon', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<input type="text" disabled><br>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Visibility Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name">
					<?php esc_html_e( 'Name', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" id="name" name="tree[name]" value="title" <?php checked( $data['name'], 'title' ); ?>>
				<?php esc_html_e( 'Title', 'genealogical-tree' ); ?>
			</td>
			<td>

				<input type="radio" id="name" name="tree[name]" value="full" <?php checked( $data['name'], 'full' ); ?>>
				<?php esc_html_e( 'Full', 'genealogical-tree' ); ?>
			</td>
			<td colspan="2">
				<input type="radio" id="name" name="tree[name]" value="first" <?php checked( $data['name'], 'first' ); ?>>
				<?php esc_html_e( 'First', 'genealogical-tree' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="birt">
					<?php esc_html_e( 'Birth Day', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" id="birt" name="tree[birt]" value="full" <?php checked( $data['birt'], 'full' ); ?>>
				<?php esc_html_e( 'Full', 'genealogical-tree' ); ?>
			</td>
			<td>
				<input type="radio" id="birt" name="tree[birt]" value="year" <?php checked( $data['birt'], 'year' ); ?>>
				<?php esc_html_e( 'Year', 'genealogical-tree' ); ?>
			</td>
			<td colspan="2">
				<input type="radio" id="birt" name="tree[birt]" value="none" <?php checked( $data['birt'], 'none' ); ?>>
				<?php esc_html_e( 'None', 'genealogical-tree' ); ?>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Hide Birth Day Who Alive', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>
		<tr>
			<td>
				<label for="deat">
					<?php esc_html_e( 'Died', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" id="deat" name="tree[deat]" value="full" <?php checked( $data['deat'], 'full' ); ?>>
				<?php esc_html_e( 'Full', 'genealogical-tree' ); ?>
			</td>
			<td>
				<input type="radio" id="deat" name="tree[deat]" value="year" <?php checked( $data['deat'], 'year' ); ?>>
				<?php esc_html_e( 'Year', 'genealogical-tree' ); ?>
			</td>
			<td colspan="2">
				<input type="radio" id="deat" name="tree[deat]" value="none" <?php checked( $data['deat'], 'none' ); ?>>
				<?php esc_html_e( 'None', 'genealogical-tree' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="gender">
					<?php esc_html_e( 'Show Gender', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="icon" <?php checked( $data['gender'], 'icon' ); ?>>
				<?php esc_html_e( 'Icon', 'genealogical-tree' ); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="full" <?php checked( $data['gender'], 'full' ); ?>>
				<?php esc_html_e( 'Full', 'genealogical-tree' ); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="short" <?php checked( $data['gender'], 'short' ); ?>>
				<?php esc_html_e( 'Short', 'genealogical-tree' ); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="none" <?php checked( $data['gender'], 'none' ); ?>>
				<?php esc_html_e( 'None', 'genealogical-tree' ); ?>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Sibling Order', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4" >
				<select disabled>
					<option selected disabled>
						<?php esc_html_e( 'Default', 'genealogical-tree' ); ?>
					</option>
					<option disabled>
						<?php esc_html_e( 'Oldest', 'genealogical-tree' ); ?>
					</option>
					<option disabled>
						<?php esc_html_e( 'Youngest', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Show Generation', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>
		<tr>
			<td>
				<label for="generation_number">
					<?php esc_html_e( 'Number Of Generation', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_number" name="tree[generation_number]" value="<?php echo esc_attr( $data['generation_number'] ); ?>">
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td colspan="4">
				<small><i> <?php esc_html_e( '"-1" is for unlimited generation.', 'genealogical-tree' ); ?></i></small>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Show Ancestor', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>
		<tr>
			<td>
				<label for="generation_number">
					<?php esc_html_e( 'Number Of Ancestor Generation', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_number_ancestor" name="tree[generation_number_ancestor]" value="<?php echo esc_attr( $data['generation_number_ancestor'] ); ?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="generation_number">
					<?php esc_html_e( 'Generation Start From', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_start_from"  name="tree[generation_start_from]" value="<?php echo esc_attr( $data['generation_start_from'] ); ?>">
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td colspan="4">
				<small><i> <?php esc_html_e( 'If Show Ancestor is Enabled. "-1" is for unlimited generation.', 'genealogical-tree' ); ?></i></small>
			</td>
		</tr>
		<tr>
			<td>
				<label for="treelink">
					<?php esc_html_e( 'Show Tree Link', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" id="treelink" name="tree[treelink]" <?php checked( $data['treelink'], 'on' ); ?>>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Container Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr>
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_color">
					<?php esc_html_e( 'Background Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="text" id="container_background_color" value="<?php echo esc_attr( $data['container']['background']['color'] ); ?>" class="gt-color-field" name="tree[container][background][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color">
					<?php esc_html_e( 'Border Width', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][width]">
				<?php
				$container_border_width = $data['container']['border']['width'];
				for ( $i = 0; $i < 20; $i++ ) {
					?>
					<option <?php selected( $container_border_width, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color">
					<?php esc_html_e( 'Border Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][style]">
					<?php
					$container_border_style = $data['container']['border']['style'];
					foreach ( $border_style as $key => $value ) {
						?>
						<option <?php selected( $container_border_style, $key ); ?> value="<?php echo esc_attr( $key ); ?>">
							<?php echo esc_attr( ucfirst( $value ) ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_border_color">
					<?php esc_html_e( 'Border Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="text" id="container_border_color" class="gt-color-field" name="tree[container][border][color]" value="<?php echo esc_attr( $data['container']['border']['color'] ); ?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color">
					<?php esc_html_e( 'Border Radius', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][radius]">
				<?php
				$container_border_radius = $data['container']['border']['radius'];
				for ( $i = 0; $i < 50; $i++ ) {
					?>
					<option <?php selected( $container_border_radius, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_image">
					<?php esc_html_e( 'Background Image', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<textarea style="width: 100%; height: 70px;" id="container_background_image" name="tree[container][background][image]"><?php echo esc_attr( $data['container']['background']['image'] ); ?></textarea><br>
				<i>  <?php esc_html_e( '( css linear-gradient )', 'genealogical-tree' ); ?>  </i>
			</td>
		</tr>
		<tr>
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_size">
					<?php esc_html_e( 'Background Size', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="text" style="width: 100%;" id="container_background_size" name="tree[container][background][size]" value="<?php echo esc_attr( $data['container']['background']['size'] ); ?>">
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Box Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Layout', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<option disabled selected>
						<?php esc_html_e( 'Vertical', 'genealogical-tree' ); ?>
					</option>
					<option disabled>
						<?php esc_html_e( 'Horizontal', 'genealogical-tree' ); ?>
					</option>
			</select></td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color">
					<?php esc_html_e( 'Width', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="text"  name="tree[box][width]" value="<?php echo esc_attr( $data['box']['width'] ); ?>"> 
				<br><small><i><?php esc_html_e( 'If Layout is Horizontal, Width is  \'auto\' is recommended.', 'genealogical-tree' ); ?> </i></small>

			</td>
		</tr>
		<tr>
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label for="container_background_color">
					<?php esc_html_e( 'Background Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php esc_html_e( 'Male', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['background']['color']['male'] ); ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][male]">
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Female', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['background']['color']['female'] ); ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][female]">
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Other', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['background']['color']['other'] ); ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][other]">
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Border Width', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
				<?php
				for ( $i = 0; $i < 20; $i++ ) {
					?>
					<option <?php selected( '1px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Border Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php
					foreach ( $border_style as $key => $value ) {
						?>
						<option <?php selected( 'solid', $key ); ?> disabled>
							<?php echo esc_html( ucfirst( $value ) ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label for="box_border_color">
					<?php esc_html_e( 'Border Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php esc_html_e( 'Male', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['border']['color']['male'] ); ?>" type="text" id="box_border_color_male" class="gt-color-field" name="tree[box][border][color][male]">
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Female', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['border']['color']['female'] ); ?>" type="text" id="box_border_color_female" class="gt-color-field" name="tree[box][border][color][female]">
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Other', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo esc_attr( $data['box']['border']['color']['other'] ); ?>" type="text" id="box_border_color_other" class="gt-color-field" name="tree[box][border][color][other]">
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Border Radius', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
				<?php
				for ( $i = 0; $i < 50; $i++ ) {
					?>
					<option <?php selected( '0px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td )>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Line Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Line Size', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
				<?php
				for ( $i = 0; $i < 20; $i++ ) {
					?>
					<option <?php selected( '1px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Line Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php
					foreach ( $border_style as $key => $value ) {
						?>
						<option <?php selected( 'solid', $key ); ?> disabled>
							<?php echo esc_html( ucfirst( $value ) ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Line Corner Radius', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
				<?php
				for ( $i = 0; $i < 20; $i++ ) {
					?>
					<option <?php selected( '0px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Line Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input value="<?php echo '#000000'; ?>" type="text" disabled>
			</td>
		</tr>
		<tr class="pro">
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Image Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Show Image', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Width', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input type="text" disabled> 
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Border Width', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
				<?php
				for ( $i = 0; $i < 20; $i++ ) {
					?>
					<option <?php selected( '1px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Border Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php
					foreach ( $border_style as $key => $value ) {
						?>
						<option <?php selected( 'solid', $key ); ?> disabled>
							<?php echo esc_html( ucfirst( $value ) ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label>
					<?php esc_html_e( 'Border Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php esc_html_e( 'Male', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo '#000000'; ?>" type="text" disabled>
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Female', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo '#000000'; ?>" type="text" disabled>
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e( 'Other', 'genealogical-tree' ); ?>
			</td>
			<td colspan="3">
				<input value="<?php echo '#000000'; ?>" type="text" disabled>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color">
					<?php esc_html_e( 'Border Radius', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php
					for ( $i = 0; $i < 50; $i++ ) {
						?>
					<option <?php selected( '0px', $i . 'px' ); ?> disabled>
						<?php echo esc_html( $i ); ?>px
					</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Name Text Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr class="pro"> 
			<td>
				<label>
					<?php esc_html_e( 'Font Family', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<option disabled>
						<?php esc_html_e( 'Default', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_size">
					<?php esc_html_e( 'Font Size', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select name="tree[name_text][font_size]">
				<?php
				$name_text_font_size = $data['name_text']['font_size'];
				for ( $i = 5; $i < 25; $i++ ) {
					?>
					<option <?php selected( $name_text_font_size, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_weight">
					<?php esc_html_e( 'Font Weight', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select id="name_text_font_weight" name="tree[name_text][font_weight]">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_style">
					<?php esc_html_e( 'Font Style', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select id="name_text_font_style" name="tree[name_text][font_style]">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_color">
					<?php esc_html_e( 'Font Color', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<input value="<?php echo esc_attr( $data['name_text']['color'] ); ?>" type="text" id="name_text_color" class="gt-color-field" name="tree[name_text][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_align">
					<?php esc_html_e( 'Text Align', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<?php
				$name_text_align = $data['name_text']['align'];
				?>
				<select name="tree[name_text][align]">
					<option <?php selected( $name_text_align, 'left' ); ?> value="left">Left</option>
					<option <?php selected( $name_text_align, 'center' ); ?> value="center">Center</option>
					<option <?php selected( $name_text_align, 'right' ); ?> value="right">Right</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php esc_html_e( 'Other Text Setting', 'genealogical-tree' ); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label>
					<?php esc_html_e( 'Font Family', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select disabled>
					<option disabled>
						<?php esc_html_e( 'Default', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_size">
					<?php esc_html_e( 'Font Size', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select name="tree[other_text][font_size]">
				<?php
				$other_text_font_size = $data['other_text']['font_size'];
				for ( $i = 5; $i < 25; $i++ ) {
					?>
					<option <?php selected( $other_text_font_size, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_weight">
					<?php esc_html_e( 'Font Weight', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td colspan="4">
				<select id="other_text_font_weight" name="tree[other_text][font_weight]">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_style">
					<?php esc_html_e( 'Font Style', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<select id="other_text_font_style" name="tree[other_text][font_style]">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_color">
					<?php esc_html_e( 'Font Color', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<?php
				$other_text_color = $data['other_text']['color'];
				?>
				<input value="<?php echo esc_attr( $other_text_color ); ?>" type="text" id="other_text_color" class="gt-color-field" name="tree[other_text][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_align">
					<?php esc_html_e( 'Text Align', 'genealogical-tree' ); ?> 
				</label>
			</td>
			<td colspan="4">
				<?php
				$other_text_align = $data['other_text']['align'];
				?>
				<select name="tree[other_text][align]">
					<option <?php selected( $other_text_align, 'left' ); ?> value="left">
						<?php esc_html_e( 'Left', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $other_text_align, 'center' ); ?> value="center">
						<?php esc_html_e( 'Center', 'genealogical-tree' ); ?>
					</option>
					<option <?php selected( $other_text_align, 'right' ); ?> value="right">
						<?php esc_html_e( 'Right', 'genealogical-tree' ); ?>
					</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
