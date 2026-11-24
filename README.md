# Future Ukraine — WordPress / Elementor theme

WordPress port of [kangarooo/future-ukraine](https://github.com/kangarooo/future-ukraine) (React + Tailwind, built in Lovable), made for **ukrainengo.kangarooo.id.lv**.

Same design: dark olive background, Ukrainian blue and gold accents, Orbitron / JetBrains Mono / Inter type, grid and scan-line effects, gradient-border cards. Same content and section order:

Hero → About / Mission → Fundraising goals → Donate → Financial reports → Field news → Volunteer → FAQ → Contact

## Install

1. Download [`future-ukraine.zip`](future-ukraine.zip).
2. In wp-admin, go to **Appearance → Themes → Add New → Upload Theme**, then install and activate it.
3. Install and activate **Elementor** (the free version is enough).
4. Go to **Appearance → Future Ukraine Setup → Run import**.

The import is also run automatically the first time the theme is activated. It:

- creates a **Home** page built with Elementor, holding the 9 section widgets, and sets it as the front page
- creates the Primary, Quick links and Legal menus
- sets the Elementor kit's global colours (gold `#FFD700`, blue `#005BBD`, olive, background) and global fonts

## Editing

- **Sections:** open the Home page with Elementor. Each section is a widget in the **Future Ukraine** category (`FU · Hero`, `FU · Donate`, …). Every text, number, link, card, stat, report, campaign and FAQ item can be edited there, including repeaters. You can mix in any normal Elementor widgets, and the section widgets can be used on any page.
- **Header and footer:** use **Appearance → Customize → Future Ukraine** for the brand text, header button, footer text, registration line, copyright, meta description and the email address that form submissions go to. With Elementor Pro you can replace the header and footer using Theme Builder, because all core locations are registered.
- **News:** this section shows the latest WordPress posts. You can limit it to one category in the widget.
- **Without Elementor:** the theme still renders every section. You can use `[fu_home]` for all sections or `[fu_section name="donate"]` for one; any field can be passed as an attribute.

## Forms

The volunteer and contact forms replace the original app's Supabase table. Each submission is:

- saved under **wp-admin → Submissions**, as a private post type
- emailed to the admin address, or to the address set in the Customizer, with Reply-To set to the sender

The forms are protected by a nonce, a honeypot field and a 60-second limit per IP address.

## ⚠️ Before going live

The demo content copies the original repo exactly, including its **placeholder data**. Replace these in the Elementor widgets:

- **Donate:** IBAN `LV80HABA0551XXXXXXXX`, SWIFT, PayPal email, BTC and ETH addresses, and optionally a payment page URL (the chosen amount is added as `?amount=`)
- **About:** registration number `40008XXXXXX`, "ISO 9001 Certified" and the statistics
- **Reports:** the report files (each needs a download URL) and the statistics bar
- **Contact:** email, phone, address and social links
- **Customizer:** the footer registration line

## Structure

```
future-ukraine/
├── style.css, functions.php, header.php, footer.php
├── front-page.php, page.php, single.php, index.php, 404.php
├── inc/
│   ├── defaults.php                  # section schema + default content (single source of truth)
│   ├── class-fu-section-widget.php   # generic Elementor widget, controls generated from the schema
│   ├── elementor.php                 # widget registration, theme locations, page data builder
│   ├── setup.php                     # importer (Appearance → Future Ukraine Setup)
│   ├── forms.php                     # volunteer + contact handling, Submissions CPT
│   ├── template-functions.php, customizer.php, icons.php (Lucide SVGs)
├── template-parts/sections/*.php     # one template per section
├── elementor/home-page.json          # importable Elementor template (Templates → Import)
└── assets/ (css/theme.css, js/theme.js, img/hero-bg.jpg)
```

Requires WordPress 6.0+ and PHP 7.4+. Tested on WordPress trunk with PHP 8, with no notices or warnings.

## Build the zip

```sh
zip -r future-ukraine.zip future-ukraine -x '*.DS_Store'
```

License: GPL-2.0-or-later. Icons: [Lucide](https://lucide.dev) (ISC).
