<?php

/**
 * Tree settings.
 *
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */
$background_color = $setting->background->color;
$line_border_width = ( $setting->line->border->width ? $setting->line->border->width : '1px' );
$line_border_style = ( $setting->line->border->style ? $setting->line->border->style : 'solid' );
$line_border_color = ( $setting->line->border->color ? $setting->line->border->color : '#000000' );
$line_border_radius = ( $setting->line->border->radius ? $setting->line->border->radius : '0px' );
$line_border = $line_border_width . ' ' . $line_border_style . ' ' . $line_border_color;
$box_width = ( $setting->box->width ? $setting->box->width : '90px' );
$box_background_color = ( $setting->box->background->color->other ? $setting->box->background->color->other : '#ffffff' );
$box_background_color_male = ( $setting->box->background->color->male ? $setting->box->background->color->male : '#daeef3' );
$box_background_color_female = ( $setting->box->background->color->female ? $setting->box->background->color->female : '#ffdbe0' );
$box_border_width = ( $setting->box->border->width ? $setting->box->border->width : '1px' );
$box_border_style = ( $setting->box->border->style ? $setting->box->border->style : 'solid' );
$box_border_radius = ( $setting->box->border->radius ? $setting->box->border->radius : '0px' );
$box_border = $box_border_width . ' ' . $box_border_style;
$box_border_color = ( $setting->box->border->color->other ? $setting->box->border->color->other : '#000000' );
$box_border_color_male = ( $setting->box->border->color->male ? $setting->box->border->color->male : '#000000' );
$box_border_color_female = ( $setting->box->border->color->female ? $setting->box->border->color->female : '#000000' );
$thumb_width = ( $setting->thumb->width ? $setting->thumb->width : '80px' );
$thumb_border_width = ( $setting->thumb->border->width ? $setting->thumb->border->width : '1px' );
$thumb_border_style = ( $setting->thumb->border->style ? $setting->thumb->border->style : 'solid' );
$thumb_border_radius = ( $setting->thumb->border->radius ? $setting->thumb->border->radius : '0px' );
$thumb_border = $thumb_border_width . ' ' . $thumb_border_style;
$thumb_border_color = ( $setting->thumb->border->color->other ? $setting->thumb->border->color->other : '#000000' );
$thumb_border_color_male = ( $setting->thumb->border->color->male ? $setting->thumb->border->color->male : '#000000' );
$thumb_border_color_female = ( $setting->thumb->border->color->female ? $setting->thumb->border->color->female : '#000000' );
$fonts = json_decode( $fonts )->items;
$other_text_font_family = $setting->other_text->font_family;
$other_text_font_weight = $setting->other_text->font_weight;
$other_text_font_style = $setting->other_text->font_style;
$other_text_font_size = $setting->other_text->font_size;
$other_text_color = $setting->other_text->color;
$other_text_align = $setting->other_text->align;
$other_text_font_file = array();
if ( 'regular' === $setting->other_text->font_style ) {
    $other_text_font_file[] = '0';
}
if ( 'italic' === $setting->other_text->font_style ) {
    $other_text_font_file[] = '1';
}
if ( 'regular' === $setting->other_text->font_weight ) {
    $other_text_font_file[] = '400';
} else {
    $other_text_font_file[] = $setting->other_text->font_weight;
}
$other_text_font_file = implode( ',', $other_text_font_file );
$other_text_font_family_for = '';
foreach ( $fonts as $key => $font ) {
    if ( $font->family === $other_text_font_family ) {
        $other_text_font_family_for = str_replace( ' ', '+', $other_text_font_family );
    }
}
$name_text_font_family = $setting->name_text->font_family;
$name_text_font_weight = $setting->name_text->font_weight;
$name_text_font_style = $setting->name_text->font_style;
$name_text_font_size = $setting->name_text->font_size;
$name_text_color = $setting->name_text->color;
$name_text_align = $setting->name_text->align;
$name_text_font_file = array();
if ( 'regular' === $setting->name_text->font_style ) {
    $name_text_font_file[] = '0';
}
if ( 'italic' === $setting->name_text->font_style ) {
    $name_text_font_file[] = '1';
}
if ( 'regular' === $setting->name_text->font_weight ) {
    $name_text_font_file[] = '400';
} else {
    $name_text_font_file[] = $setting->name_text->font_weight;
}
$name_text_font_file = implode( ',', $name_text_font_file );
$name_text_font_family_for = '';
foreach ( $fonts as $key => $font ) {
    if ( $font->family === $name_text_font_family ) {
        $name_text_font_family_for = str_replace( ' ', '+', $name_text_font_family );
    }
}
$inline_style = '';
$inline_style .= '
<style type="text/css">';
$inline_style .= '
div.gt-tree:not(.type-gt-tree).' . $rand_id . '  {
	background: ' . $background_color . ';
}
div.gt-tree.' . $rand_id . ' div.ind > .ind-cont > .info  {
	font-size: ' . $other_text_font_size . ';
	font-style: ' . $other_text_font_style . ';
	text-align: ' . $other_text_align . ';
	color: ' . $other_text_color . ';
}
div.gt-tree.' . $rand_id . ' div.ind > .ind-cont > .info > .name,
div.gt-tree.' . $rand_id . ' div.ind > .ind-cont > .info > .name > a {
	font-size: ' . $name_text_font_size . ';
	font-style: ' . $name_text_font_style . ';
	text-align: ' . $name_text_align . ';
	color: ' . $name_text_color . ' !important;
	word-break: initial;
	word-wrap: normal;
}
div.gt-tree.' . $rand_id . ' div.ind {
	background-color: ' . $box_background_color . ';
	width: ' . $box_width . ';
	border: ' . $box_border . ';
	border-color: ' . $box_border_color . ';
	border-radius: ' . $box_border_radius . ';
}
div.gt-tree.' . $rand_id . ' div.ind.M {
	background-color: ' . $box_background_color_male . ';
	border: ' . $box_border . ';
	border-color: ' . $box_border_color_male . ';
}
div.gt-tree.' . $rand_id . ' div.ind.F {
	background-color: ' . $box_background_color_female . ';
	border: ' . $box_border . ';
	border-color: ' . $box_border_color_female . ';
}
div.gt-tree.' . $rand_id . ' div.ind > .ind-cont > div.image > .image-cont {
	width: ' . $thumb_width . ';
}
div.gt-tree.' . $rand_id . ' div.ind > .ind-cont > div.image > .image-cont > img {
	border: ' . $thumb_border . ';
	border-color: ' . $thumb_border_color . ';
	border-radius: ' . $thumb_border_radius . ';
}
div.gt-tree.' . $rand_id . ' div.ind.M > .ind-cont > div.image > .image-cont > img {
	border-color: ' . $thumb_border_color_male . ';
}
div.gt-tree.' . $rand_id . ' div.ind.F > .ind-cont > div.image > .image-cont > img {
	border-color: ' . $thumb_border_color_female . ';
}
div.gt-tree.' . $rand_id . ' .gt-collapse-family {
	border: ' . $line_border . ';
	line-height: ' . (intval( 30 ) - intval( $line_border_width ) * 2) . 'px;
}
';
if ( 'vr' === $setting->layout || 'hr' !== $setting->layout ) {
    $inline_style .= '
	div.gt-tree.' . $rand_id . ' ul {
		padding-top: ' . (10 + intval( $line_border_width )) . 'px;
		padding-left:0;
		padding-right:0;
	}
	div.gt-tree.' . $rand_id . ' .alter-tree ul { /*.alter-tree*/
		padding-top: 0px;
		padding-bottom: ' . (10 + intval( $line_border_width )) . 'px;
	}
	div.gt-tree.' . $rand_id . ' li {
		padding-top: ' . (10 + intval( $line_border_width )) . 'px;
		padding-left:0;
		padding-right:0;
	}
	div.gt-tree.' . $rand_id . ' .alter-tree li { /*.alter-tree*/
		padding-top: 0px;
		padding-bottom: ' . (10 + intval( $line_border_width )) . 'px;
		padding-left:2px;
		padding-right:2px;
	}
	div.gt-tree.' . $rand_id . ' ul:before,
	div.gt-tree.' . $rand_id . ' li:before,
	div.gt-tree.' . $rand_id . ' li>.ind:before,
	div.gt-tree.' . $rand_id . ' li>.ind:after,
	div.gt-tree.' . $rand_id . ' li.child:after,
	div.gt-tree.' . $rand_id . ' li.family:after {
		top: 0px;
		content: "";
		height: ' . (10 + intval( $line_border_width )) . 'px;
		display: block;
		position: absolute;
		left: 50%;
		right: 50%;
	}
	div.gt-tree.' . $rand_id . ' .alter-tree ul:before,
	div.gt-tree.' . $rand_id . ' .alter-tree li:before,
	div.gt-tree.' . $rand_id . ' .alter-tree li>.ind:before,
	div.gt-tree.' . $rand_id . ' .alter-tree li>.ind:after,
	div.gt-tree.' . $rand_id . ' .alter-tree li.child:after,
	div.gt-tree.' . $rand_id . ' .alter-tree li.family:after { /*.alter-tree*/
		bottom: 0px;
		top: initial;
		height: ' . (10 + intval( $line_border_width )) . 'px;
	}
	div.gt-tree.' . $rand_id . ' ul:before {
		border-left: ' . $line_border . ';
		margin-left: -' . intval( $line_border_width ) / 2 . 'px;
	}
	div.gt-tree.' . $rand_id . ' li:before {
		border-top: ' . $line_border . ';
		left: 0%;
		right: 0%;
		width: inherit;
		margin: inherit;
	}
	div.gt-tree.' . $rand_id . ' .alter-tree li:before { /*.alter-tree*/
		border-top: 0px;
		border-bottom: ' . $line_border . ';
	}
	div.gt-tree.' . $rand_id . ' li:first-child:before {
		left: 50%;
		border-left: ' . $line_border . ';
		margin-left: -' . intval( $line_border_width ) / 2 . 'px;
		border-top-left-radius: ' . $line_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' .alter-tree li:first-child:before { /*.alter-tree*/
		border-top-left-radius: 0px;
		border-bottom-left-radius: ' . $line_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' li:last-child:before {
		right: 50%;
		border-right: ' . $line_border . ';
		margin-right: -' . intval( $line_border_width ) / 2 . 'px;
		border-top-right-radius: ' . $line_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' .alter-tree li:last-child:before { /*.alter-tree*/
		border-top-right-radius: 0px;
		border-bottom-right-radius: ' . $line_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' li:only-child:before {
		border-top: 0px solid;
		border-left: 0px solid;
		border-right: 0px solid;
	}
	div.gt-tree.' . $rand_id . ' .alter-tree li:only-child:before { /*.alter-tree*/
		border-left: ' . $line_border . ';
	}
	div.gt-tree.' . $rand_id . ' ul.parents > li:first-child > div.ind {
		margin-left: 2px;
		border-top-left-radius: ' . $box_border_radius . ';
		border-bottom-left-radius: ' . $box_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' ul.parents > li:last-child > div.ind {
		margin-right: 2px;
		border-top-right-radius: ' . $box_border_radius . ';
		border-bottom-right-radius: ' . $box_border_radius . ';
	}
	div.gt-tree.' . $rand_id . ' .gt-collapse-family:after {
		margin-left: -' . intval( $line_border_width ) / 2 . 'px;
		height: ' . (intval( 5 ) + intval( $line_border_width )) . 'px;
		border-left: ' . $line_border . ';
		bottom: -' . (intval( 5 ) + intval( $line_border_width )) . 'px;
	}
	';
    if ( '1' === $setting->style ) {
        $inline_style .= '
		div.gt-tree.' . $rand_id . ' .gt-style-1 div.ind {
			display: inline-table;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family {
			padding-top: ' . (50 + intval( $line_border_width ) * 1) . 'px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family:only-child {
			padding-top: 40px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 ul.families {
			padding-top: 10px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family:after {
			content: "💑" !important;
			width: 30px;
			height: 30px;
			top: ' . (10 + intval( $line_border_width ) * 1) . 'px;
			border: ' . $line_border . ';
			margin-left: -15px;
			font-size: 15px !important;
			line-height: ' . (30 - intval( $line_border_width ) * 2) . 'px;
			text-align: center; ';
        if ( $setting->marr_icon ) {
            $inline_style .= '
				font-size: 0px !important;
				background: url(' . $setting->marr_icon . ');
				background-position: center;
				background-size: 100%;
				background-repeat: no-repeat;';
        }
        $inline_style .= '
		} 
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family:only-child {
			padding-top: 40px !important;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family:only-child:after {
			top: 0;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family>div.ind:before {
			border-left: ' . $line_border . ';
			margin-left: -' . intval( $line_border_width ) / 2 . 'px;
			top: -' . (10 + intval( $box_border_width ) * 1) . 'px;
			height: 10px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family:last-child:not(:only-child):after {
			margin-left: -15px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li+li:not(:last-child)>div.ind:before {
			border-left: ' . $line_border . ';
			margin-left: -' . intval( $line_border_width ) / 2 . 'px;
			top: -' . (10 + intval( $box_border_width ) * 1) . 'px;
			height: 10px;
		}
		div.gt-tree.' . $rand_id . ' .gt-style-1 li.family+li.family:not(:last-child)>div.ind:after {
			border-left: ' . $line_border . ';
			top: -' . (30 + (10 + intval( $box_border_width )) * 2) . 'px;
			margin-left: -' . intval( $line_border_width ) / 2 . 'px;
		}';
    }
}
$inline_style .= '
</style>';