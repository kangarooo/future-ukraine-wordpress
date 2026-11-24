<?php
/**
 * Section schema + default content (ported 1:1 from github.com/kangarooo/future-ukraine).
 *
 * Every section is described once here. The same schema drives:
 *  - the Elementor widget controls (inc/elementor.php),
 *  - the [fu_section] shortcode and the non-Elementor front page,
 *  - the content importer (inc/setup.php).
 *
 * Field spec: key => array( type, label, default [, options] )
 * Types: text, textarea, number, url, select, media, switcher.
 *
 * @package FutureUkraine
 */

defined( 'ABSPATH' ) || exit;

/**
 * Accent colour choices.
 *
 * @return array
 */
function fu_color_choices() {
	return array(
		'gold'  => __( 'Gold', 'future-ukraine' ),
		'blue'  => __( 'Blue', 'future-ukraine' ),
		'light' => __( 'Light', 'future-ukraine' ),
	);
}

/**
 * Standard section heading fields.
 *
 * @param string $kicker    Kicker text.
 * @param string $title     Title.
 * @param string $highlight Highlighted word.
 * @param string $color     Highlight colour.
 * @param string $intro     Intro paragraph.
 * @return array
 */
function fu_heading_fields( $kicker, $title, $highlight, $color = 'gold', $intro = '' ) {
	return array(
		'anchor'          => array( 'text', __( 'Section anchor (ID)', 'future-ukraine' ), '' ),
		'kicker'          => array( 'text', __( 'Kicker', 'future-ukraine' ), $kicker ),
		'title'           => array( 'text', __( 'Title', 'future-ukraine' ), $title ),
		'highlight'       => array( 'text', __( 'Highlighted word', 'future-ukraine' ), $highlight ),
		'highlight_color' => array( 'select', __( 'Highlight colour', 'future-ukraine' ), $color, fu_color_choices() ),
		'intro'           => array( 'textarea', __( 'Intro text', 'future-ukraine' ), $intro ),
	);
}

/**
 * Full schema.
 *
 * @return array
 */
function fu_sections_schema() {
	static $schema = null;
	if ( null !== $schema ) {
		return $schema;
	}

	$stat_fields = array(
		'value' => array( 'text', __( 'Value', 'future-ukraine' ), '' ),
		'label' => array( 'text', __( 'Label', 'future-ukraine' ), '' ),
		'color' => array( 'select', __( 'Colour', 'future-ukraine' ), 'gold', fu_color_choices() ),
	);

	$schema = array(

		'hero'      => array(
			'title'     => __( 'Hero', 'future-ukraine' ),
			'icon'      => 'eicon-banner',
			'anchor'    => 'top',
			'fields'    => array(
				'anchor'             => array( 'text', __( 'Section anchor (ID)', 'future-ukraine' ), '' ),
				'badge'              => array( 'text', __( 'Status badge', 'future-ukraine' ), 'ACTIVE HUMANITARIAN OPERATIONS' ),
				'title_1'            => array( 'text', __( 'Heading word 1 (gold)', 'future-ukraine' ), 'STAND' ),
				'title_2'            => array( 'text', __( 'Heading word 2', 'future-ukraine' ), 'WITH' ),
				'title_3'            => array( 'text', __( 'Heading line 2 (blue)', 'future-ukraine' ), 'UKRAINE' ),
				'subtitle'           => array( 'textarea', __( 'Subtitle', 'future-ukraine' ), 'Ukrainian NGO in Latvia providing critical humanitarian aid, military support, and rehabilitation services for those affected by war.' ),
				'cta_primary_text'   => array( 'text', __( 'Primary button text', 'future-ukraine' ), 'Donate Now' ),
				'cta_primary_url'    => array( 'url', __( 'Primary button link', 'future-ukraine' ), '#donate' ),
				'cta_secondary_text' => array( 'text', __( 'Secondary button text', 'future-ukraine' ), 'Learn More' ),
				'cta_secondary_url'  => array( 'url', __( 'Secondary button link', 'future-ukraine' ), '#about' ),
				'background'         => array( 'media', __( 'Background image', 'future-ukraine' ), '' ),
				'show_scroll'        => array( 'switcher', __( 'Show scroll indicator', 'future-ukraine' ), 'yes' ),
			),
			'repeaters' => array(
				'stats' => array(
					'label'   => __( 'Stats', 'future-ukraine' ),
					'fields'  => $stat_fields,
					'default' => array(
						array( 'value' => '€500K+', 'label' => 'AID DELIVERED', 'color' => 'gold' ),
						array( 'value' => '2,500+', 'label' => 'FAMILIES HELPED', 'color' => 'blue' ),
						array( 'value' => '100%', 'label' => 'TRANSPARENT', 'color' => 'light' ),
					),
				),
			),
		),

		'about'     => array(
			'title'     => __( 'About / Mission', 'future-ukraine' ),
			'icon'      => 'eicon-info-box',
			'anchor'    => 'about',
			'fields'    => fu_heading_fields( '// ABOUT US', 'OUR', 'MISSION', 'gold', 'We are a non-governmental organization operating from Latvia, dedicated to supporting Ukraine and its people through comprehensive humanitarian efforts.' ) + array(
				'org_title'     => array( 'text', __( 'Organisation block title', 'future-ukraine' ), 'REGISTERED &' ),
				'org_highlight' => array( 'text', __( 'Organisation block highlight', 'future-ukraine' ), 'VERIFIED' ),
				'org_text'      => array( 'textarea', __( 'Organisation text', 'future-ukraine' ), 'Our organization is officially registered in Latvia and operates in full compliance with international humanitarian standards. We maintain complete transparency in all our operations, with regular audits and detailed financial reports available to all donors.' ),
			),
			'repeaters' => array(
				'missions'  => array(
					'label'   => __( 'Mission cards', 'future-ukraine' ),
					'fields'  => array(
						'icon'        => array( 'select', __( 'Icon', 'future-ukraine' ), 'shield', fu_icon_choices() ),
						'title'       => array( 'text', __( 'Title', 'future-ukraine' ), '' ),
						'description' => array( 'textarea', __( 'Description', 'future-ukraine' ), '' ),
					),
					'default' => array(
						array( 'icon' => 'shield', 'title' => 'Military Support', 'description' => 'Providing essential equipment, drones, vehicles, and tactical gear to Ukrainian armed forces.' ),
						array( 'icon' => 'heart', 'title' => 'Medical Aid', 'description' => 'Supplying medical equipment, first aid kits, and supporting rehabilitation programs for wounded soldiers.' ),
						array( 'icon' => 'users', 'title' => 'Refugee Assistance', 'description' => 'Helping Ukrainian refugees in Latvia with housing, legal aid, and integration support.' ),
						array( 'icon' => 'truck', 'title' => 'Humanitarian Convoys', 'description' => 'Organizing regular supply convoys delivering food, medicine, and essential goods to affected regions.' ),
					),
				),
				'org_items' => array(
					'label'   => __( 'Organisation facts', 'future-ukraine' ),
					'fields'  => array(
						'text'  => array( 'text', __( 'Text', 'future-ukraine' ), '' ),
						'color' => array( 'select', __( 'Dot colour', 'future-ukraine' ), 'gold', fu_color_choices() ),
					),
					'default' => array(
						array( 'text' => 'Reg. No: 40008XXXXXX', 'color' => 'gold' ),
						array( 'text' => 'Operating since 2022', 'color' => 'blue' ),
						array( 'text' => 'ISO 9001 Certified', 'color' => 'gold' ),
					),
				),
				'org_stats' => array(
					'label'   => __( 'Organisation stats', 'future-ukraine' ),
					'fields'  => $stat_fields,
					'default' => array(
						array( 'value' => '3+', 'label' => 'YEARS ACTIVE', 'color' => 'gold' ),
						array( 'value' => '50+', 'label' => 'VOLUNTEERS', 'color' => 'blue' ),
						array( 'value' => '150+', 'label' => 'CONVOYS SENT', 'color' => 'light' ),
						array( 'value' => '24/7', 'label' => 'OPERATIONS', 'color' => 'gold' ),
					),
				),
			),
		),

		'campaigns' => array(
			'title'     => __( 'Fundraising goals', 'future-ukraine' ),
			'icon'      => 'eicon-skill-bar',
			'anchor'    => 'campaigns',
			'fields'    => fu_heading_fields( '// ACTIVE CAMPAIGNS', 'FUNDRAISING', 'GOALS', 'blue', 'Choose a goal and see exactly where your money goes.' ) + array(
				'currency' => array( 'text', __( 'Currency symbol', 'future-ukraine' ), '€' ),
			),
			'repeaters' => array(
				'campaigns' => array(
					'label'   => __( 'Campaigns', 'future-ukraine' ),
					'fields'  => array(
						'title'       => array( 'text', __( 'Title', 'future-ukraine' ), '' ),
						'description' => array( 'textarea', __( 'Description', 'future-ukraine' ), '' ),
						'goal'        => array( 'number', __( 'Goal amount', 'future-ukraine' ), 10000 ),
						'raised'      => array( 'number', __( 'Raised so far', 'future-ukraine' ), 0 ),
						'button_text' => array( 'text', __( 'Button text', 'future-ukraine' ), 'Support this goal' ),
						'button_url'  => array( 'url', __( 'Button link', 'future-ukraine' ), '#donate' ),
					),
					'default' => array(
						array( 'title' => 'FPV Drones for the Front', 'description' => 'Reconnaissance and FPV drones for frontline units.', 'goal' => 50000, 'raised' => 37250, 'button_text' => 'Support this goal', 'button_url' => '#donate' ),
						array( 'title' => 'Tactical Medicine Kits', 'description' => 'IFAK first aid kits and tourniquets for soldiers.', 'goal' => 20000, 'raised' => 16400, 'button_text' => 'Support this goal', 'button_url' => '#donate' ),
						array( 'title' => 'Evacuation Vehicle', 'description' => 'A 4x4 vehicle for evacuating the wounded.', 'goal' => 35000, 'raised' => 12900, 'button_text' => 'Support this goal', 'button_url' => '#donate' ),
					),
				),
			),
		),

		'donate'    => array(
			'title'     => __( 'Donate', 'future-ukraine' ),
			'icon'      => 'eicon-price-table',
			'anchor'    => 'donate',
			'fields'    => fu_heading_fields( '// SUPPORT', 'MAKE A', 'DONATION', 'gold', 'Every contribution directly supports our humanitarian operations. 100% of donations go towards aid for Ukraine.' ) + array(
				'quick_title'    => array( 'text', __( 'Quick donate title', 'future-ukraine' ), 'QUICK DONATE' ),
				'amounts'        => array( 'text', __( 'Amounts (comma separated)', 'future-ukraine' ), '10,25,50,100,250,500' ),
				'default_amount' => array( 'number', __( 'Pre-selected amount', 'future-ukraine' ), 50 ),
				'currency'       => array( 'text', __( 'Currency symbol', 'future-ukraine' ), '€' ),
				'donate_url'     => array( 'url', __( 'Payment page URL (amount is appended as ?amount=)', 'future-ukraine' ), '' ),
				'bank_title'     => array( 'text', __( 'Bank card title', 'future-ukraine' ), 'Bank Transfer' ),
				'bank_sub'       => array( 'text', __( 'Bank card subtitle', 'future-ukraine' ), 'EUR / USD' ),
				'iban'           => array( 'text', 'IBAN', 'LV80HABA0551XXXXXXXX' ),
				'swift'          => array( 'text', 'SWIFT', 'HABALV22' ),
				'paypal_title'   => array( 'text', __( 'PayPal card title', 'future-ukraine' ), 'PayPal' ),
				'paypal_sub'     => array( 'text', __( 'PayPal card subtitle', 'future-ukraine' ), 'Instant transfer' ),
				'paypal_text'    => array( 'textarea', __( 'PayPal text', 'future-ukraine' ), 'Send donations directly via PayPal. Include "Charitable contribution" in the note.' ),
				'paypal_email'   => array( 'text', __( 'PayPal email', 'future-ukraine' ), 'donate@ukraine-support.lv' ),
				'paypal_url'     => array( 'url', __( 'PayPal link', 'future-ukraine' ), 'https://paypal.me' ),
				'crypto_title'   => array( 'text', __( 'Crypto card title', 'future-ukraine' ), 'Crypto' ),
				'crypto_sub'     => array( 'text', __( 'Crypto card subtitle', 'future-ukraine' ), 'BTC / ETH / USDT' ),
				'btc'            => array( 'text', __( 'Bitcoin address', 'future-ukraine' ), 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh' ),
				'eth'            => array( 'text', __( 'Ethereum address', 'future-ukraine' ), '0x71C76521834D1C5C5e9E8F' ),
				'trust'          => array( 'text', __( 'Trust line (separate with |)', 'future-ukraine' ), 'Secure payments|100% goes to aid|Tax deductible' ),
			),
			'repeaters' => array(),
		),

		'shop'      => array(
			'title'     => __( 'Shop (WooCommerce products)', 'future-ukraine' ),
			'icon'      => 'eicon-products',
			'anchor'    => 'shop',
			'fields'    => fu_heading_fields( '// SHOP', 'SUPPORT', 'SHOP', 'gold', 'Every purchase supports Ukraine. Handmade goods and merchandise — proceeds go to aid.' ) + array(
				'count'          => array( 'number', __( 'Number of products', 'future-ukraine' ), 8 ),
				'category'       => array( 'text', __( 'Product category slug (optional)', 'future-ukraine' ), '' ),
				'orderby'        => array(
					'select',
					__( 'Order', 'future-ukraine' ),
					'date',
					array(
						'date'       => __( 'Newest', 'future-ukraine' ),
						'popularity' => __( 'Best selling', 'future-ukraine' ),
						'price'      => __( 'Price: low to high', 'future-ukraine' ),
						'title'      => __( 'Name', 'future-ukraine' ),
						'rand'       => __( 'Random', 'future-ukraine' ),
					),
				),
				'featured_only'  => array( 'switcher', __( 'Featured products only', 'future-ukraine' ), '' ),
				'view_text'      => array( 'text', __( 'Product button text (when not added to cart)', 'future-ukraine' ), 'View product' ),
				'view_all_text'  => array( 'text', __( '"All products" button text', 'future-ukraine' ), 'All Products' ),
				'view_all_url'   => array( 'url', __( '"All products" link (empty = shop page)', 'future-ukraine' ), '' ),
				'empty_text'     => array( 'text', __( 'Text when there are no products', 'future-ukraine' ), 'Products will appear here soon.' ),
			),
			'repeaters' => array(),
		),

		'reports'   => array(
			'title'     => __( 'Financial reports', 'future-ukraine' ),
			'icon'      => 'eicon-document-file',
			'anchor'    => 'reports',
			'fields'    => fu_heading_fields( '// TRANSPARENCY', 'FINANCIAL', 'REPORTS', 'blue', 'Complete transparency in all our operations. Download our financial statements, audit reports, and impact assessments.' ) + array(
				'source'        => array(
					'select',
					__( 'Reports to show', 'future-ukraine' ),
					'auto',
					array(
						'auto'   => __( 'Report posts if any exist, else the list below', 'future-ukraine' ),
						'posts'  => __( 'Report posts (wp-admin → Reports)', 'future-ukraine' ),
						'manual' => __( 'Only the list below', 'future-ukraine' ),
					),
				),
				'posts_count'   => array( 'number', __( 'Number of report posts', 'future-ukraine' ), 4 ),
				'view_all_text' => array( 'text', __( '"View all" button text', 'future-ukraine' ), 'View All Reports' ),
				'view_all_url'  => array( 'url', __( '"View all" button link (empty or # = /reports/ archive)', 'future-ukraine' ), '' ),
			),
			'repeaters' => array(
				'reports' => array(
					'label'   => __( 'Reports (manual list)', 'future-ukraine' ),
					'fields'  => array(
						'type'  => array( 'text', __( 'Type', 'future-ukraine' ), 'Financial' ),
						'title' => array( 'text', __( 'Title', 'future-ukraine' ), '' ),
						'date'  => array( 'text', __( 'Date', 'future-ukraine' ), '' ),
						'file'  => array( 'url', __( 'File / download URL', 'future-ukraine' ), '' ),
					),
					'default' => array(
						array( 'type' => 'Financial', 'title' => 'Q4 2024 Financial Report', 'date' => 'December 2024', 'file' => '' ),
						array( 'type' => 'Impact', 'title' => 'Annual Impact Report 2024', 'date' => 'November 2024', 'file' => '' ),
						array( 'type' => 'Operations', 'title' => 'Q3 2024 Operations Summary', 'date' => 'October 2024', 'file' => '' ),
						array( 'type' => 'Audit', 'title' => 'Audit Report 2023', 'date' => 'March 2024', 'file' => '' ),
					),
				),
				'stats'   => array(
					'label'   => __( 'Stats bar', 'future-ukraine' ),
					'fields'  => $stat_fields,
					'default' => array(
						array( 'value' => '€523,847', 'label' => 'TOTAL RAISED 2024', 'color' => 'gold' ),
						array( 'value' => '98.7%', 'label' => 'TO DIRECT AID', 'color' => 'blue' ),
						array( 'value' => '1.3%', 'label' => 'ADMIN COSTS', 'color' => 'light' ),
						array( 'value' => '4', 'label' => 'AUDITS PASSED', 'color' => 'gold' ),
					),
				),
			),
		),

		'news'      => array(
			'title'     => __( 'Field news (latest posts)', 'future-ukraine' ),
			'icon'      => 'eicon-posts-grid',
			'anchor'    => 'news',
			'fields'    => fu_heading_fields( '// LATEST UPDATES', 'FIELD', 'NEWS', 'blue', '' ) + array(
				'count'      => array( 'number', __( 'Number of posts', 'future-ukraine' ), 3 ),
				'category'   => array( 'text', __( 'Category slug (optional)', 'future-ukraine' ), '' ),
				'read_more'  => array( 'text', __( '"Read more" text', 'future-ukraine' ), 'Read more' ),
				'empty_text' => array( 'text', __( 'Text when there are no posts', 'future-ukraine' ), 'News from the field will appear here soon.' ),
			),
			'repeaters' => array(),
		),

		'volunteer' => array(
			'title'     => __( 'Volunteer form', 'future-ukraine' ),
			'icon'      => 'eicon-form-horizontal',
			'anchor'    => 'volunteer',
			'fields'    => fu_heading_fields( '// JOIN US', 'BECOME A', 'VOLUNTEER', 'gold', 'Give your time and skills. Tell us how you can help.' ) + array(
				'areas'       => array( 'textarea', __( 'Help areas (one per line)', 'future-ukraine' ), "Logistics\nSorting supplies\nTranslation\nDriving\nFundraising\nOther" ),
				'button_text' => array( 'text', __( 'Button text', 'future-ukraine' ), 'Sign up to volunteer' ),
				'success'     => array( 'text', __( 'Success message', 'future-ukraine' ), 'Thank you! We will contact you soon.' ),
			),
			'repeaters' => array(),
		),

		'faq'       => array(
			'title'     => __( 'FAQ', 'future-ukraine' ),
			'icon'      => 'eicon-accordion',
			'anchor'    => 'faq',
			'fields'    => fu_heading_fields( '// QUESTIONS', 'FREQUENTLY', 'ASKED', 'gold', '' ),
			'repeaters' => array(
				'faqs' => array(
					'label'   => __( 'Questions', 'future-ukraine' ),
					'fields'  => array(
						'q' => array( 'text', __( 'Question', 'future-ukraine' ), '' ),
						'a' => array( 'textarea', __( 'Answer', 'future-ukraine' ), '' ),
					),
					'default' => array(
						array( 'q' => 'Where does my donation go?', 'a' => 'Donations go directly to aid: equipment, medical supplies and support for refugees. Our financial reports show every expense.' ),
						array( 'q' => 'Can I get a receipt for my donation?', 'a' => 'Yes. Write to us with your payment details and we will send you a donation confirmation.' ),
						array( 'q' => 'Can I donate items instead of money?', 'a' => 'Yes. Contact us first so we can tell you which items are needed right now and where to bring them.' ),
						array( 'q' => 'How can I volunteer?', 'a' => 'We always need help with logistics, sorting supplies and translation. Send us a message through the contact form.' ),
						array( 'q' => 'Is the organization officially registered?', 'a' => 'Yes, we are a registered non-governmental organization in Latvia. Our registration number is shown in the About section.' ),
					),
				),
			),
		),

		'contact'   => array(
			'title'     => __( 'Contact', 'future-ukraine' ),
			'icon'      => 'eicon-mail',
			'anchor'    => 'contact',
			'fields'    => fu_heading_fields( '// GET IN TOUCH', 'CONTACT', 'US', 'gold', "Have questions? Want to volunteer or partner with us? We'd love to hear from you." ) + array(
				'email'       => array( 'text', __( 'Email', 'future-ukraine' ), 'info@ukraine-support.lv' ),
				'phone'       => array( 'text', __( 'Phone', 'future-ukraine' ), '+371 2000 0000' ),
				'address'     => array( 'textarea', __( 'Address', 'future-ukraine' ), "Brīvības iela 100\nRīga, LV-1001, Latvia" ),
				'facebook'    => array( 'url', 'Facebook URL', '#' ),
				'instagram'   => array( 'url', 'Instagram URL', '#' ),
				'linkedin'    => array( 'url', 'LinkedIn URL', '#' ),
				'twitter'     => array( 'url', 'X / Twitter URL', '' ),
				'hours_week'  => array( 'text', __( 'Hours Monday – Friday', 'future-ukraine' ), '09:00 - 18:00' ),
				'hours_sat'   => array( 'text', __( 'Hours Saturday', 'future-ukraine' ), '10:00 - 14:00' ),
				'hours_sun'   => array( 'text', __( 'Hours Sunday', 'future-ukraine' ), 'Closed' ),
				'form_title'  => array( 'text', __( 'Form title', 'future-ukraine' ), 'SEND A MESSAGE' ),
				'button_text' => array( 'text', __( 'Button text', 'future-ukraine' ), 'Send Message' ),
				'success'     => array( 'text', __( 'Success message', 'future-ukraine' ), 'Thank you! Your message has been sent.' ),
			),
			'repeaters' => array(),
		),
	);

	/**
	 * Filter the section schema.
	 *
	 * @param array $schema Schema.
	 */
	$schema = apply_filters( 'fu_sections_schema', $schema );
	return $schema;
}

/**
 * Order of sections on the home page (same as the React app).
 *
 * @return array
 */
function fu_home_sections() {
	return apply_filters( 'fu_home_sections', array( 'hero', 'about', 'campaigns', 'donate', 'shop', 'reports', 'news', 'volunteer', 'faq', 'contact' ) );
}

/**
 * Default navigation (same as the React app).
 *
 * @return array label => anchor
 */
function fu_default_nav() {
	return array(
		'About Us'  => '#about',
		'Reports'   => '#reports',
		'Goals'     => '#campaigns',
		'Donate'    => '#donate',
		'Shop'      => '#shop',
		'News'      => '#news',
		'Volunteer' => '#volunteer',
		'FAQ'       => '#faq',
		'Contact'   => '#contact',
	);
}

/**
 * Merge stored settings with defaults for one section.
 *
 * @param string $name     Section name.
 * @param array  $settings Settings (Elementor or shortcode).
 * @return array
 */
function fu_section_args( $name, $settings = array() ) {
	$schema = fu_sections_schema();
	if ( empty( $schema[ $name ] ) ) {
		return array();
	}
	$def  = $schema[ $name ];
	$args = array();

	foreach ( $def['fields'] as $key => $spec ) {
		$val = isset( $settings[ $key ] ) ? $settings[ $key ] : null;
		if ( 'media' === $spec[0] ) {
			$val = is_array( $val ) && ! empty( $val['url'] ) ? $val['url'] : ( is_string( $val ) ? $val : '' );
		}
		if ( 'switcher' === $spec[0] ) {
			$args[ $key ] = null === $val ? $spec[2] : $val;
			continue;
		}
		$args[ $key ] = ( null === $val || '' === $val ) ? $spec[2] : $val;
	}
	// Allow intentionally empty intro / anchor when the key exists in settings.
	foreach ( array( 'intro', 'anchor' ) as $k ) {
		if ( isset( $def['fields'][ $k ] ) && array_key_exists( $k, $settings ) && '' === $settings[ $k ] ) {
			$args[ $k ] = '';
		}
	}
	if ( empty( $args['anchor'] ) ) {
		$args['anchor'] = $def['anchor'];
	}

	foreach ( $def['repeaters'] as $key => $rep ) {
		$val          = isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) && ! empty( $settings[ $key ] ) ? $settings[ $key ] : $rep['default'];
		$args[ $key ] = $val;
	}

	return apply_filters( 'fu_section_args', $args, $name, $settings );
}
