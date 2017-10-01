<?php
class EgyediMegoldasokSC
{
    const SCTAG = 'egyedi-megoldasok';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
        $output = '<div class="'.self::SCTAG.'-holder">';

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $egyedi_megoldasok = get_page_by_path('egyedi-megoldasaink');
        $attr['egyedimegoldasok'] = $egyedi_megoldasok;

        $datas = new WP_Query(array(
          'post_parent' => $egyedi_megoldasok->ID,
          'post_type' => 'page',
          'orderby' => 'menu_order',
          'order' => 'ASC'
        ));
        $attr['data'] = $datas;

        $output .= (new ShortcodeTemplates('egyedi-megoldasok'))->load_template($attr);

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new EgyediMegoldasokSC();

?>
