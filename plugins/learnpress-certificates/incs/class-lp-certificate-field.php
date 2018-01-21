<?php

/**
 * Class LP_Certificate_Field
 */
class LP_Certificate_Field {
	/**
	 * @return array
	 */
	static function google_fonts() {
		$fonts = array( "Abel", "Abril Fatface", "Aclonica", "Actor", "Adamina", "Aguafina Script", "Aladin", "Aldrich", "Alice", "Alike Angular", "Alike", "Allan", "Allerta Stencil", "Allerta", "Amaranth", "Amatic SC", "Andada", "Andika", "Annie Use Your Telescope", "Anonymous Pro", "Antic", "Anton", "Arapey", "Architects Daughter", "Arimo", "Artifika", "Arvo", "Asset", "Astloch", "Atomic Age", "Aubrey", "Bangers", "Bentham", "Bevan", "Bigshot One", "Bitter", "Black Ops One", "Bowlby One SC", "Bowlby One", "Brawler", "Bubblegum Sans", "Buda", "Butcherman Caps", "Cabin Condensed", "Cabin Sketch", "Cabin", "Cagliostro", "Calligraffitti", "Candal", "Cantarell", "Cardo", "Carme", "Carter One", "Caudex", "Cedarville Cursive", "Changa One", "Cherry Cream Soda", "Chewy", "Chicle", "Chivo", "Coda Caption", "Coda", "Comfortaa", "Coming Soon", "Contrail One", "Convergence", "Cookie", "Copse", "Corben", "Cousine", "Coustard", "Covered By Your Grace", "Crafty Girls", "Creepster Caps", "Crimson Text", "Crushed", "Cuprum", "Damion", "Dancing Script", "Dawning of a New Day", "Days One", "Delius Swash Caps", "Delius Unicase", "Delius", "Devonshire", "Didact Gothic", "Dorsa", "Dr Sugiyama", "Droid Sans Mono", "Droid Sans", "Droid Serif", "EB Garamond", "Eater Caps", "Expletus Sans", "Fanwood Text", "Federant", "Federo", "Fjord One", "Fondamento", "Fontdiner Swanky", "Forum", "Francois One", "Gentium Basic", "Gentium Book Basic", "Geo", "Geostar Fill", "Geostar", "Give You Glory", "Gloria Hallelujah", "Goblin One", "Gochi Hand", "Goudy Bookletter 1911", "Gravitas One", "Gruppo", "Hammersmith One", "Herr Von Muellerhoff", "Holtwood One SC", "Homemade Apple", "IM Fell DW Pica SC", "IM Fell DW Pica", "IM Fell Double Pica SC", "IM Fell Double Pica", "IM Fell English SC", "IM Fell English", "IM Fell French Canon SC", "IM Fell French Canon", "IM Fell Great Primer SC", "IM Fell Great Primer", "Iceland", "Inconsolata", "Indie Flower", "Irish Grover", "Istok Web", "Jockey One", "Josefin Sans", "Josefin Slab", "Judson", "Julee", "Jura", "Just Another Hand", "Just Me Again Down Here", "Kameron", "Kelly Slab", "Kenia", "Knewave", "Kranky", "Kreon", "Kristi", "La Belle Aurore", "Lancelot", "Lato", "League Script", "Leckerli One", "Lekton", "Lemon", "Limelight", "Linden Hill", "Lobster Two", "Lobster", "Lora", "Love Ya Like A Sister", "Loved by the King", "Luckiest Guy", "Maiden Orange", "Mako", "Marck Script", "Marvel", "Mate SC", "Mate", "Maven Pro", "Meddon", "MedievalSharp", "Megrim", "Merienda One", "Merriweather", "Metrophobic", "Michroma", "Miltonian Tattoo", "Miltonian", "Miss Fajardose", "Miss Saint Delafield", "Modern Antiqua", "Molengo", "Monofett", "Monoton", "Monsieur La Doulaise", "Montez", "Mountains of Christmas", "Mr Bedford", "Mr Dafoe", "Mr De Haviland", "Mrs Sheppards", "Muli", "Neucha", "Neuton", "News Cycle", "Niconne", "Nixie One", "Nobile", "Nosifer Caps", "Nothing You Could Do", "Nova Cut", "Nova Flat", "Nova Mono", "Nova Oval", "Nova Round", "Nova Script", "Nova Slim", "Nova Square", "Numans", "Nunito", "Old Standard TT", "Open Sans Condensed", "Open Sans", "Orbitron", "Oswald", "Over the Rainbow", "Ovo", "PT Sans Caption", "PT Sans Narrow", "PT Sans", "PT Serif Caption", "PT Serif", "Pacifico", "Passero One", "Patrick Hand", "Paytone One", "Permanent Marker", "Petrona", "Philosopher", "Piedra", "Pinyon Script", "Play", "Playfair Display", "Podkova", "Poller One", "Poly", "Pompiere", "Prata", "Prociono", "Puritan", "Quattrocento Sans", "Quattrocento", "Questrial", "Quicksand", "Radley", "Raleway", "Rammetto One", "Rancho", "Rationale", "Redressed", "Reenie Beanie", "Ribeye Marrow", "Ribeye", "Righteous", "Rochester", "Rock Salt", "Rokkitt", "Rosario", "Ruslan Display", "Salsa", "Sancreek", "Sansita One", "Satisfy", "Schoolbell", "Shadows Into Light", "Shanti", "Short Stack", "Sigmar One", "Signika Negative", "Signika", "Six Caps", "Slackey", "Smokum", "Smythe", "Sniglet", "Snippet", "Sorts Mill Goudy", "Special Elite", "Spinnaker", "Spirax", "Stardos Stencil", "Sue Ellen Francisco", "Sunshiney", "Supermercado One", "Swanky and Moo Moo", "Syncopate", "Tangerine", "Tenor Sans", "Terminal Dosis", "The Girl Next Door", "Tienne", "Tinos", "Tulpen One", "Ubuntu Condensed", "Ubuntu Mono", "Ubuntu", "Ultra", "UnifrakturCook", "UnifrakturMaguntia", "Unkempt", "Unlock", "Unna", "VT323", "Varela Round", "Varela", "Vast Shadow", "Vibur", "Vidaloka", "Volkhov", "Vollkorn", "Voltaire", "Waiting for the Sunrise", "Wallpoet", "Walter Turncoat", "Wire One", "Yanone Kaffeesatz", "Yellowtail", "Yeseva One", "Zeyada" );
		return $fonts;
	}

	/**
	 * @param null $field_type
	 *
	 * @return array|mixed|void
	 */
	static function get_options( $field_type = null ) {
		$fields = array(
			array(
				'name'        => 'fontFamily',
				'type'        => 'font',
				'title'       => __( 'Font', 'learnpress-certificates' ),
				'std'         => '',
				'google_font' => true
			),
			array(
				'name'  => 'fontSize',
				'type'  => 'slider',
				'title' => __( 'Font size', 'learnpress-certificates' ),
				'std'   => '',
				'min'   => 8,
				'max'   => 512
			),
			array(
				'name'    => 'fontStyle',
				'type'    => 'select',
				'title'   => __( 'Font style', 'learnpress-certificates' ),
				'std'     => '',
				'options' => array(
					'normal'  => __( 'Normal', 'learnpress-certificates' ),
					'italic'  => __( 'Italic', 'learnpress-certificates' ),
					'oblique' => __( 'Oblique', 'learnpress-certificates' )
				)
			),
			array(
				'name'    => 'fontWeight',
				'type'    => 'select',
				'title'   => __( 'Font weight', 'learnpress-certificates' ),
				'std'     => '',
				'options' => array(
					''     => __( 'Normal', 'learnpress-certificates' ),
					'bold' => __( 'Bold', 'learnpress-certificates' )
				)
			),
			array(
				'name'    => 'textDecoration',
				'type'    => 'select',
				'title'   => __( 'Text decoration', 'learnpress-certificates' ),
				'std'     => '',
				'options' => array(
					''             => __( 'Normal', 'learnpress-certificates' ),
					'underline'         => __( 'Underline', 'learnpress-certificates' ),
					'overline'     => __( 'Overline', 'learnpress-certificates' ),
					'line-through' => __( 'Line-through', 'learnpress-certificates' )
				)
			),
			array(
				'name'  => 'fill',
				'type'  => 'color',
				'title' => __( 'Color', 'learnpress-certificates' ),
				'std'   => ''
			),
			array(
				'name'  => 'originX',
				'type'  => 'text_align',
				'title' => __( 'Text align', 'learnpress-certificates' ),
				'std'   => ''
			),
			array(
				'name'  => 'originY',
				'type'  => 'vertical_align',
				'title' => __( 'Text vertical align', 'learnpress-certificates' ),
				'std'   => ''
			),
			array(
				'name'  => 'angle',
				'type'  => 'slider',
				'title' => __( 'Angle', 'learnpress-certificates' ),
				'std'   => '',
				'min'   => 0,
				'max'   => 360
			),
			array(
				'name'  => 'scaleX',
				'type'  => 'slider',
				'title' => __( 'Scale X', 'learnpress-certificates' ),
				'std'   => '',
				'min'   => - 50,
				'max'   => 50,
				'step'  => 0.1
			),
			array(
				'name'  => 'scaleY',
				'type'  => 'slider',
				'title' => __( 'Scale Y', 'learnpress-certificates' ),
				'std'   => '',
				'min'   => - 50,
				'max'   => 50,
				'step'  => 0.1
			)
		);
		return $field_type ? apply_filters( 'learn_press_certificate_field_options', $fields, $field_type ) : $fields;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	static function name_to_slug( $name ) {
		return preg_replace( '!_!', '-', $name );
		return preg_replace_callback( '!_!', array( __CLASS__, '_name_to_slug' ), $name );
	}

	/**
	 * @param $find
	 *
	 * @return string
	 */
	static function _name_to_slug( $find ) {
		return '--' . strtolower( $find[1] );
	}
}