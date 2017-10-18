<?php
class EgyediMegoldasokTemplateSC
{
    const SCTAG = 'EgyediMegoldasok';

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
              'key' => ''
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $uid = uniqid();
        $attr['uid'] = $uid;

        switch ($attr['key']) {
          case 'inteligensuzenetkuldo':
            $attr['title'] = 'Intelligens üzenetküldő technológia';
          break;
          case 'popup':
            $attr['title'] = 'Intelligens popup technológia';
          break;
          case 'vasarlasgeneraloajanlo':
            $attr['title'] = 'Vásárlást generáló intelligens ajánló rendszer';
          break;
          case 'automatizalt_webaruhaz':
            $attr['title'] = 'Önműködő, automatizált webáruház - CRM rendszerekre';
          break;
          case 'mozimusor':
            $attr['title'] = 'Automatizált moziműsor megjelenítés';
          break;
          case 'facebookapp':
            $attr['title'] = 'Facebook applikáció fejlesztése';
          break;
          case 'fina17molkampany':
            $attr['title'] = 'Kupon rendszer: 17. FINA Világbajnokság Arena - Mol kampány';
          break;
        }

        $output = '<div class="'.self::SCTAG.'-holder template-'.$attr['key'].'">';

        $output .= (new ShortcodeTemplates(__CLASS__.'/template-'.$attr['key']))->load_template($attr);

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new EgyediMegoldasokTemplateSC();

?>
