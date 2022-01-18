<?php
/**
 * This class is responsible for returning svg icons used in the site
 * 
 */

 class SvgIcon{
     public static function get_svg($icon, $size=18){
        //takes in icon name and returns the svg
        if ( array_key_exists( $icon, self::$icons ) ) {
			$repl = sprintf( '<svg class="svg-icon" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $size, $size );
			$svg  = preg_replace( '/^<svg /', $repl, trim( self::$icons[ $icon ] ) ); // Add extra attributes to SVG code.
			$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
			$svg  = preg_replace( '/>\s*</', '><', $svg );    // Remove whitespace between SVG tags.
			return $svg;
        }
        
        return NULL;
     }

     public static $icons = array(
         'menu' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="18px" height="18px"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>'
     );
 }