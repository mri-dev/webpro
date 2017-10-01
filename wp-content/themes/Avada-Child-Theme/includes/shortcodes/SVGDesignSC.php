<?php
class SVGDesignSC
{
    const SCTAG = 'svgdesign';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
              'key' => 'none',
              'width' => 100,
              'padding' => 0
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );
        
        $output = '<div class="'.self::SCTAG.'-holder style-'.$attr['key'].'">';
        $output .= (new ShortcodeTemplates('svgdesign'))->load_template($attr);
        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new SVGDesignSC();

?>
