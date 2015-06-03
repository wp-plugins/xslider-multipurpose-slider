<?php
/*!
Plugin Name: xSlider Free
Plugin URI: http://xslider.develvet.com/free
Description: xSlider Free - Multipurpose Slider Plugin for Wordpress
Author: Develvet
Version: 1.0.2
Author URI: http://www.develvet.com
 */

if (!class_exists('WP')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit;
}

require_once "includes/utils.php";

define("XFREE_AJAX_SECURITY", "sfsdfg76g2837oh8sdfoh89ysd9f");

$xSliderGlobalIdx    = 1;
$GLOBALS["xError"]   = "";
$GLOBALS["xSuccess"] = "";

class xSliderFree {
	const PLUGIN_NAME  = 'xslider-free';
	const PLUGIN_LABEL = 'xSlider Free';
	const VERSION      = '1.0.2';

	public function __construct() {
		global $wpdb;

		$this->ROOT_FILE      = __FILE__;
		$this->ROOT_PATH      = dirname(__FILE__);
		$this->ROOT_URL       = plugins_url('', __FILE__);
		$this->PLUGIN_SLUG    = basename(dirname(__FILE__));
		$this->PLUGIN_BASE    = plugin_basename(__FILE__);
		$this->PLUGIN_URL     = WP_PLUGIN_URL . '/' . self::PLUGIN_NAME;
		$this->ADMIN_BASE_URL = site_url() . "/wp-admin/admin.php";
		$this->BASE_DB_TABLE  = "xslider_free_sliders";

		register_activation_hook(__FILE__, array($this, 'activate'));
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));

		$tablePrefix = $wpdb->base_prefix;
		if (is_multisite()) {
			global $blog_id;

			if ($blog_id != 1) {
				$tablePrefix .= $blog_id . "_";
			}

		}

		$this->DB_TABLE = $tablePrefix . $this->BASE_DB_TABLE;

		register_activation_hook(__FILE__, array($this, 'activate'));
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));

		add_action('init', array($this, 'onWPInit'));
	}

	public function activate() {
		global $wpdb;

		if (is_plugin_active('xslider/xslider.php')) {
			echo '<span style="font-family:Open Sans,sans-serif;">xSlider Free can\'t be activated because you\'re using xSlider.<br>Please deactivate it and try again</span>';
			exit;
		}

		if (is_multisite() && isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
			$originalBlogId = get_current_blog_id();
			$blogs          = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' AND spam = '0' AND deleted = '0' AND archived = '0'");
			foreach ($blogs as $blog_id) {
				switch_to_blog($blog_id->blog_id);
				$this->doActivate();
			}
			switch_to_blog($originalBlogId);
		} else {
			$this->doActivate();
		}

	}

	public function doActivate() {
		global $wpdb;

		$tablePrefix = $wpdb->base_prefix;
		if (is_multisite()) {
			global $blog_id;
			if ($blog_id != 1) {
				$tablePrefix .= $blog_id . "_";
			}

		}

		$table = $tablePrefix . $this->BASE_DB_TABLE;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();

		if ($wpdb->get_var("SHOW TABLES LIKE '" . $table . "'") != $table) {
			$sql = "
CREATE TABLE `" . $table . "` (
   `id` mediumint(9) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `settings` LONGTEXT NOT NULL,
   PRIMARY KEY (`id`)
) $charset_collate;";
			dbDelta($sql);
		}

		update_option(self::PLUGIN_NAME . "_version", self::VERSION);
		add_option(self::PLUGIN_NAME . '_global_permission', "administrator");
		add_option(self::PLUGIN_NAME . '_global_js_on_footer', "true");
		add_option(self::PLUGIN_NAME . '_global_load_awesome', "true");
		add_option(self::PLUGIN_NAME . '_global_load_tweenmax', "true");
		add_option(self::PLUGIN_NAME . '_global_newsletter_panel', true);
		add_option(self::PLUGIN_NAME . '_google_fonts', '[]');
		add_option(self::PLUGIN_NAME . '_globals', '{"templatePanel":"true"}');
	}

	public function deactivate() {
	}

	public function getUserPermissions() {
		global $current_user;
		$this->userRoles = $current_user->roles;

		if (in_array("author", $this->userRoles)) {
			$this->userPermission = "edit_published_posts";
		}

		if (in_array("editor", $this->userRoles)) {
			$this->userPermission = "edit_pages";
		}

		if (in_array("administrator", $this->userRoles)) {
			$this->userPermission = "manage_options";
		}

	}

	public function onWPInit() {
		$this->getUserPermissions();

		require_once $this->ROOT_PATH . '/includes/utils.php';

		if (is_admin()) {
			add_action('admin_menu', array($this, 'addToMenu'));

			if (isset($_GET['page']) && $_GET['page'] == self::PLUGIN_NAME . '_admin') {
				add_action('admin_enqueue_scripts', array($this, 'adminEnqueue'), 99999);
			}

			add_action('wp_ajax_ajax_router', array($this, 'ajaxRouter'));
		} else {
			add_shortcode("xsliderfree", array($this, "xSliderShortcode"));
			add_action('wp_enqueue_scripts', array($this, 'frontendEnqueue'));
		}
	}

	public function addToMenu() {
		add_menu_page(
			self::PLUGIN_LABEL,
			self::PLUGIN_LABEL,
			$this->userPermission,
			self::PLUGIN_NAME . '_admin',
			array($this, 'loadAdminPages'),
			plugin_dir_url(__FILE__) . "/admin/images/menu-icon.png"

		);
	}

	public function loadAdminPages() {
		global $wpdb;
		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch ($current_page) {
			case 'edit':
				include 'admin/views/edit.php';
				break;

			default:
				include 'admin/views/list.php';
				break;
		}
	}

	public function adminEnqueue() {
		$this->loadGoogleFonts(true);

		wp_enqueue_media();
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');

		wp_enqueue_style(self::PLUGIN_NAME . '-admin-css', $this->PLUGIN_URL . '/admin/css/admin.min.css');
		wp_enqueue_style(self::PLUGIN_NAME . '-admin-css-awesome', $this->PLUGIN_URL . '/vendor/font-awesome/css/font-awesome.min.css');

		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-vendor', $this->PLUGIN_URL . '/admin/js/vendors.min.js');

		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-youtube-api', 'https://www.youtube.com/iframe_api');
		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-angular-ace', $this->PLUGIN_URL . '/vendor/ace/ui-ace.min.js');
		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-ace', $this->PLUGIN_URL . '/vendor/ace/src-min-noconflict/ace.js');
		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-ace-css', $this->PLUGIN_URL . '/vendor/ace/src-min-noconflict/mode-css.js');

		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-app', $this->PLUGIN_URL . '/admin/js/app.min.js');

		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js', $this->PLUGIN_URL . '/js/xslider.min.js');

		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch ($current_page) {
			case 'edit':
				wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-ctrl-edit', $this->PLUGIN_URL . '/admin/controllers/edit.js');
				wp_localize_script(self::PLUGIN_NAME . '-admin-js-ctrl-edit', 'xslider_ajax_router', array('wpnonce' => wp_create_nonce(XFREE_AJAX_SECURITY)));
				break;

			default:
				wp_enqueue_script(self::PLUGIN_NAME . '-admin-js-ctrl-list', $this->PLUGIN_URL . '/admin/controllers/list.js');
				wp_localize_script(self::PLUGIN_NAME . '-admin-js-ctrl-list', 'xslider_ajax_router', array('wpnonce' => wp_create_nonce(XFREE_AJAX_SECURITY)));
				break;
		}

	}

	public function ajaxRouter() {

		if (!isset($_POST['wpnonce']) || !wp_verify_nonce($_POST['wpnonce'], XFREE_AJAX_SECURITY) || !current_user_can($this->userPermission)) {
			die(json_encode(array('error' => __('ajax - permission denied'))));
		}

		switch ($_POST['do']) {
			case "getGlobals":
				$this->ajaxGetGlobals();
				break;

			case "updateGlobals":
				$this->ajaxUpdateGlobals();
				break;

			case "loadPreview":
				$this->ajaxLoadPreview();
				break;

			case "loadPreviewById":
				$this->ajaxLoadPreviewById();
				break;

			case "createProject":
				$this->ajaxCreateProject();
				break;

			case "updateProject":
				$this->ajaxUpdateProject();
				break;

			case "deleteProject":
				$this->ajaxDeleteProject();
				break;

			case "loadProjects":
				$this->ajaxLoadProjects();
				break;

			case "loadProject":
				$this->ajaxLoadProject();
				break;

			case "loadGoogleFonts":
				$this->ajaxLoadGoogleFonts();
				break;

			case "loadGoogleFontNames":
				$this->ajaxLoadGoogleFontNames();
				break;

			case "updateGoogleFonts":
				$this->ajaxUpdateGoogleFonts();
				break;

			case "loadVersionData":
				$this->ajaxLoadVersionData();
				break;

			case "toggleNewsletterPanel":
				$this->ajaxToggleNewsletterPanel();
				break;

			case "joinNewsletter":
				$this->ajaxJoinNewsletter();
				break;

			case "updateGlobalSettings":
				$this->ajaxUpdateGlobalSettings();
				break;

			case "loadGlobalSettings":
				$this->ajaxLoadGlobalSettings();
				break;

			default:
				die(json_encode(array('error' => __('ajax - no proper request'))));
				break;
		}
	}

	public function processData($settings) {
		$settings["width"]                           = intval($settings["width"]);
		$settings["height"]                          = intval($settings["height"]);
		$settings["preload"]["image"]                = (isset($settings["preload"]["image"]) && $settings["preload"]["image"] == 1) ? true : false;
		$settings["arrows"]["responsive"]            = ($settings["arrows"]["responsive"] == 1) ? true : false;
		$settings["arrows"]["alwaysVisible"]         = ($settings["arrows"]["alwaysVisible"] == 1) ? true : false;
		$settings["dots"]["responsive"]              = ($settings["dots"]["responsive"] == 1) ? true : false;
		$settings["dots"]["alwaysVisible"]           = ($settings["dots"]["alwaysVisible"] == 1) ? true : false;
		$settings["cssImportant"]                    = ($settings["cssImportant"] == 1) ? true : false;
		$settings["enableKeyboard"]                  = ($settings["enableKeyboard"] == 1) ? true : false;
		$settings["enableParallax"]                  = ($settings["enableParallax"] == 1) ? true : false;
		$settings["mobile"]["disableOnMobile"]       = ($settings["mobile"]["disableOnMobile"] == 1) ? true : false;
		$settings["mobile"]["disableOnIPad"]         = ($settings["mobile"]["disableOnIPad"] == 1) ? true : false;
		$settings["mobile"]["hideArrowsOnMobile"]    = ($settings["mobile"]["hideArrowsOnMobile"] == 1) ? true : false;
		$settings["mobile"]["hideArrowsOnIPad"]      = ($settings["mobile"]["hideArrowsOnIPad"] == 1) ? true : false;
		$settings["mobile"]["hideDotsOnMobile"]      = ($settings["mobile"]["hideDotsOnMobile"] == 1) ? true : false;
		$settings["mobile"]["hideDotsOnIPad"]        = ($settings["mobile"]["hideDotsOnIPad"] == 1) ? true : false;
		$settings["mobile"]["enableTouch"]           = ($settings["mobile"]["enableTouch"] == 1) ? true : false;
		$settings["mobile"]["fallbackToMouseEvents"] = ($settings["mobile"]["fallbackToMouseEvents"] == 1) ? true : false;

		if (!empty($settings["slides"])) {
			foreach ($settings["slides"] as $idx_slide => $slide) {
				if (isset($settings["slides"][$idx_slide]["ken"])) {
					$settings["slides"][$idx_slide]["ken"] = ($settings["slides"][$idx_slide]["ken"] === "true" || $settings["slides"][$idx_slide]["ken"] == 1) ? true : false;
				}

				if (!empty($slide["elements"])) {
					foreach ($slide["elements"] as $idx_element => $element) {
						switch ($element["type"]) {
							case 'text':
								$settings["slides"][$idx_slide]["elements"][$idx_element]["text"]["content"] = stripslashes($settings["slides"][$idx_slide]["elements"][$idx_element]["text"]["content"]);

								break;

							case 'fontIcon':
								$settings["slides"][$idx_slide]["elements"][$idx_element]["fontIcon"]["content"] = stripslashes($settings["slides"][$idx_slide]["elements"][$idx_element]["fontIcon"]["content"]);
								break;
						}
					}
				}
			}
		}

		return $settings;
	}

	public function ajaxCreateProject() {
		global $wpdb;

		$project = json_decode(stripslashes($_POST["project"]), true);
		$sql     = "INSERT INTO " . $this->DB_TABLE . " SET settings = ''";
		$wpdb->query($sql);
		$id = $wpdb->insert_id;

		$project["id"] = $id;
		$sql           = $wpdb->prepare("UPDATE " . $this->DB_TABLE . " SET name = %s, settings = %s WHERE id = %d", array($_POST["name"], json_encode($project), $id));
		$wpdb->query($sql);

		die(json_encode(array(
			"id" => $id,
		)));
	}

	public function ajaxUpdateProject() {
		global $wpdb;

		$sql = $wpdb->prepare("UPDATE " . $this->DB_TABLE . " SET name = %s, settings = %s WHERE id = %d", array($_POST["name"], stripslashes($_POST["project"]), $_POST["id"]));
		$wpdb->query($sql);

		die(json_encode(array(
			"status" => "updated",
		)));
	}

	public function ajaxDeleteProject() {
		global $wpdb;

		$sql = $wpdb->prepare("DELETE FROM " . $this->DB_TABLE . " WHERE id = %d", array($_POST["id"]));
		$wpdb->query($sql);

		die(json_encode(array(
			"status" => "success",
		)));
	}

	public function ajaxLoadProject() {
		global $wpdb;

		$sql = $wpdb->prepare("SELECT * FROM " . $this->DB_TABLE . " WHERE id = %d", array($_POST["id"]));
		$wpdb->query($sql);
		$result = $wpdb->get_results($sql, ARRAY_A);
		if ($wpdb->num_rows > 0) {
			die(json_encode(array(
				"id"      => $result[0]["id"],
				"name"    => $result[0]["name"],
				"project" => json_decode($result[0]["settings"], true),
			)));
		}
		die("");
	}

	public function ajaxLoadProjects() {
		global $wpdb;

		$sql      = "SELECT * FROM `" . $this->DB_TABLE . "` ORDER BY id";
		$dbResult = array();
		$dbResult = $wpdb->get_results($sql, ARRAY_A);

		$result = array();
		if (!empty($dbResult)) {
			foreach ($dbResult as $key => $value) {

				$result[$key]["id"]        = $value["id"];
				$result[$key]["name"]      = ($value["name"] != null) ? $value["name"] : "";
				$result[$key]["usedFonts"] = $this->getUsedFonts($value);
			}
		}

		die(json_encode($result));
	}

	public function getUsedFonts($project) {
		$settings      = json_decode($project["settings"], true);
		$standardFonts = array(
			"",
			"Arial, Helvetica, sans-serif",
			"Georgia, serif",
			"Verdana, Geneva, sans-serif",
			"Times New Roman, Times, serif",
			"Trebuchet MS, Helvetica, sans-serif",
			"Courier New, Courier, monospace",
			"Georgia, serif",
			"Comic Sans MS, cursive",
		);
		$fonts = array();
		if (!in_array($settings["preload"]["fontFamily"], $standardFonts)) {
			$fonts[] = $settings["preload"]["fontFamily"];
		}

		foreach ($settings["slides"] as $slide) {
			foreach ($slide["elements"] as $element) {
				if ($element["type"] == "text" && !in_array($element["text"]["fontFamily"], $standardFonts)) {
					$fonts[] = $element["text"]["fontFamily"];
				}

			}
		}
		$fonts = array_unique($fonts);
		$fonts = array_values($fonts);

		return $fonts;
	}

	public function ajaxGetGlobals() {
		$globals = get_option(self::PLUGIN_NAME . '_globals');
		$globals = ($globals === "null" || $globals == "") ? "{}" : $globals;
		$globals = json_decode($globals, true);

		$globals["templatePanel"] = ($globals["templatePanel"] === "true") ? true : false;

		die(json_encode($globals));
	}

	public function ajaxUpdateGlobals() {
		$globals = update_option(self::PLUGIN_NAME . '_globals', json_encode($_REQUEST["globals"]));
		die($globals);
	}

	public function ajaxLoadGoogleFonts() {
		$fonts = get_option(self::PLUGIN_NAME . '_google_fonts');
		$fonts = ($fonts === "null" || $fonts == "") ? "[]" : $fonts;
		die($fonts);
	}

	public function ajaxLoadGoogleFontNames() {
		$items = get_option(self::PLUGIN_NAME . '_google_fonts');
		$items = json_decode($items, true);

		$fontNames = array();
		if (!empty($items)) {
			foreach ($items as $f) {
				$parsedUrl = parse_url($f["url"]);

				$font_name = $parsedUrl['query'];
				$font_name = substr($font_name, strlen("family="));
				$pos       = strpos($font_name, ":");
				if ($pos !== false) {
					$font_name = substr($font_name, 0, $pos);
				}

				$font_name   = str_replace("+", " ", $font_name);
				$fontNames[] = $font_name;
			}
		}

		die(json_encode($fontNames));
	}

	public function ajaxUpdateGoogleFonts() {
		$fonts = $_POST["fonts"];
		foreach ($fonts as $idx => $f) {
			$parsedUrl = parse_url($f["url"]);

			$font_name = $parsedUrl['query'];
			$font_name = substr($font_name, strlen("family="));
			$pos       = strpos($font_name, ":");
			if ($pos !== false) {
				$font_name = substr($font_name, 0, $pos);
			}

			$font_name           = str_replace("+", " ", $font_name);
			$fonts[$idx]["name"] = $font_name;
		}
		update_option(self::PLUGIN_NAME . '_google_fonts', json_encode($fonts));
	}

	public function ajaxToggleNewsletterPanel() {
		$newsletterPanel = get_option(self::PLUGIN_NAME . "_global_newsletter_panel");
		update_option(self::PLUGIN_NAME . '_global_newsletter_panel', !$newsletterPanel);

		die(json_encode(array(
			'result' => !$newsletterPanel,
		)));
	}

	public function ajaxJoinNewsletter() {
		global $wp_version;

		update_option(self::PLUGIN_NAME . '_global_newsletter_email', $_REQUEST["email"]);
		update_option(self::PLUGIN_NAME . '_global_newsletter_panel', false);

		$request = array(
			'body'        => array(
				'action'  => 'join',
				'product' => 'xSlider Free',
				'version' => self::VERSION,
				'siteUrl' => get_site_url(),
				'email'   => $_REQUEST["email"],
				'ip'      => XSliderFreeUtils::getIp(),
			),
			'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'),
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'headers'     => array(
				'mime-type' => 'text/xml',
			),
		);

		$rawResponse = wp_remote_post('http://www.develvet.com/join-newsletter/index.php', $request);

		die(json_encode(array(
			'result' => true,
		)));
	}

	public function ajaxUpdateGlobalSettings() {
		$globalSettings = $_POST["globalSettings"];
		update_option(self::PLUGIN_NAME . '_global_permission', $globalSettings["permission"]);
		update_option(self::PLUGIN_NAME . '_global_js_on_footer', $globalSettings["jsOnFooter"]);
		update_option(self::PLUGIN_NAME . '_global_load_awesome', $globalSettings["loadAwesome"]);
		update_option(self::PLUGIN_NAME . '_global_load_tweenmax', $globalSettings["loadTweenMax"]);

		die(json_encode(array(
			'result' => true,
		)));
	}

	public function ajaxLoadGlobalSettings() {
		$newsletterEmail = get_option(self::PLUGIN_NAME . "_global_newsletter_email");
		if (!$newsletterEmail) {
			global $current_user;
			$newsletterEmail = $current_user->user_email;
		}

		die(json_encode(array(
			"globalSettings" => array(
				"newsletterEmail" => $newsletterEmail,
				"newsletterPanel" => get_option(self::PLUGIN_NAME . '_global_newsletter_panel'),
				"permission"      => get_option(self::PLUGIN_NAME . '_global_permission'),
				"jsOnFooter"      => (get_option(self::PLUGIN_NAME . '_global_js_on_footer') === "true") ? true : false,
				"loadAwesome"     => (get_option(self::PLUGIN_NAME . '_global_load_awesome') === "true") ? true : false,
				"loadTweenMax"    => (get_option(self::PLUGIN_NAME . '_global_load_tweenmax') === "true") ? true : false,
			),
		)));
	}

	public function ajaxLoadPreview() {
		$settings       = json_decode(stripslashes($_POST["project"]), true);
		$settings       = $this->processData($settings);
		$settings["id"] = $_POST["id"];

		$css  = $this->generateCss($_POST["id"], $settings, true);
		$html = $this->generateHtml($_POST["id"], $settings, true);
		$js   = $this->generateAdminJs($_POST["id"], $settings, true);

		die(json_encode(array(
			'css'  => $css,
			'html' => $html,
			'js'   => $js,
		)));
	}

	public function ajaxLoadPreviewById() {
		global $wpdb;

		$sql = $wpdb->prepare("SELECT * FROM " . $this->DB_TABLE . " WHERE id = %s", array($_POST["id"]));
		$wpdb->query($sql);
		$result = $wpdb->get_results($sql, ARRAY_A);
		if ($wpdb->num_rows > 0) {
			$settings = json_decode($result[0]["settings"], true);

			$css  = $this->generateCss($_POST["id"], $settings, true);
			$html = $this->generateHtml($_POST["id"], $settings, true);
			$js   = $this->generateAdminJs($_POST["id"], $settings, true);

			die(json_encode(array(
				'css'     => $css,
				'html'    => $html,
				'js'      => $js,
				'project' => $settings,
			)));
		}
	}

	public function getVimeoUrl($element, $id) {
		return "//player.vimeo.com/video/" . $element["vimeo"]["code"] . "?api=1&player_id=" . $id . "&title=" . $element["vimeo"]["title"] . "&byline=" . $element["vimeo"]["byline"] . "&badge=" . $element["vimeo"]["badge"] . "&color=" . str_replace("#", "", $element["vimeo"]["controlsColor"]);
	}

	public function generateCss($idProject, $settings, $preview) {
		$id = 'xslider_' . $idProject;

		$settings["css"] = str_replace(".xslider", '#' . $id . ' ', $settings["css"]);
		$settings["css"] = str_replace(".x_", '#' . $id . ' .x_', $settings["css"]);
		$settings["css"] = trim(preg_replace('/\s\s+/', ' ', str_replace(array("\n", "\t"), "", $settings["css"])));

		if ($settings["cssImportant"]) {
			$settings["css"] = str_replace(";", "!important;", $settings["css"]);
		}

		$css = "<style>\n" . $settings["css"] . "</style>\n";

		return $css;
	}

	public function generateHtml($idProject, $settings, $preview) {
		$id = 'xslider_' . $idProject;

		$html = "";

		$html .= '<div id="' . $id . '" class="xslider">';

		$html .= '<div id="' . $id . '_wrapper" style="position:relative">';

		$html .= '<div id="' . $id . '_preloader" class="x_preloader" style="visibility:hidden">';
		if (isset($settings["preload"]["image"]) && $settings["preload"]["image"]) {
			$html .= '  <div id="' . $id . '_preloader_image" class="x_preloader_image"><img src="' . $settings["preload"]["imageUrl"] . '" style="width:100%; height:100%"></div>';
		}

		$html .= '  <div id="' . $id . '_preloader_bar" class="x_preloader_bar"></div>';
		$html .= '  <div id="' . $id . '_preloader_text" class="x_preloader_text"></div>';
		$html .= '</div>';

		$html .= '<div id="' . $id . '_cont" style="visibility:hidden;">';

		$idxSlide = 0;
		if (!empty($settings["slides"])) {
			foreach ($settings["slides"] as $slide) {
				$idSlide = $id . "_s" . $idxSlide;
				$html .= '<div id="' . $idSlide . '_cont" class="x_slide_cont">';
				$html .= '<div id="' . $idSlide . '_bg" class="x_slide_bg"></div>';
				$html .= '<div id="' . $idSlide . '" class="x_slide">';

				$idxElement = 0;
				if (!empty($slide["elements"])) {
					foreach ($slide["elements"] as $element) {
						$idElement = $idSlide . "_e" . $idxElement;

						$html .= '<div id="' . $idElement . '_pos" class="x_el_pos">';

						$html .= '<div id="' . $idElement . '_lax" class="x_el_lax">';

						if (!empty($element["animations"])) {
							$html .= '<div id="' . $idElement . '_fx0" class="x_el_fx xslider_el_fx0">';
							for ($a = 2; $a < count($element["animations"]); $a++) {
								$html .= '<div id="' . $idElement . '_fx' . $a . '" class="x_el_fx">';
							}

							$html .= '<div id="' . $idElement . '_fx1" class="x_el_fx">';

						}

						if (isset($element["loopEffect"]) && $element["loopEffect"] != "none" && $element["loopEffect"] != "") {
							$html .= '<div id="' . $idElement . '_loop" class="x_el_loop">';
						}

						$html .= '<div id="' . $idElement . '_mouse" class="x_el_mouse">';

						$html .= '<div id="' . $idElement . '_cont" class="x_el_cont';
						if (isset($element["className"])) {
							$html .= ' ' . $element["className"];
						}

						$html .= '">';

						switch ($element["type"]) {
							case "text":
								$html .= '<' . (isset($element["text"]["tag"]) ? $element["text"]["tag"] : "div") . ' ';
								$html .= 'id="' . $idElement . '_text" class="x_el_text xtext">';
								if (!isset($element["text"]["contentType"]) || $element["text"]["contentType"] == 'plain') {
									$html .= htmlspecialchars($element["text"]["content"]);
								} else {
									$html .= $element["text"]["nl2br"] ? nl2br($element["text"]["content"]) : $element["text"]["content"];
								}

								$html .= '</' . (isset($element["text"]["tag"]) ? $element["text"]["tag"] : "div") . '>';
								break;

							case "fontIcon":
								$html .= '<div id="' . $idElement . '_font_icon" class="x_el_font_icon">';

								$html .= '<i class="fa ' . $element["fontIcon"]["icon"] . ' ' . $element["fontIcon"]["transform"] . '"></i>';
								$html .= '</div>';
								break;

							case "image":

								$element["image"]["altTag"] = isset($element["image"]["altTag"]) ? $element["image"]["altTag"] : "";
								$html .= '<img x-src="' . $element["path"] . '" id="' . $idElement . '_image" alt="' . htmlspecialchars($element["image"]["altTag"], ENT_QUOTES, 'UTF-8') . '" class="x_el_image">';
								break;

							case "empty":
								$html .= '<div id="' . $idElement . '_empty" class="x_el_empty">';
								$html .= '</div>';
								break;
						}

						$html .= '</div>';

						$html .= '</div>';

						if (isset($element["loopEffect"]) && $element["loopEffect"] != "none" && $element["loopEffect"] != "") {
							$html .= '</div>';
						}

						if (!empty($element["animations"])) {
							foreach ($element["animations"] as $animIdx => $anim) {
								$html .= '</div>';
							}
						}

						$html .= '</div>';

						$html .= '</div>';

						$idxElement++;
					}
				}

				$html .= '</div>';
				$html .= '</div>';

				$idxSlide++;
			}
		}

		if (!((XSliderFreeUtils::isIPad() && $settings["mobile"]["hideArrowsOnIPad"]) || (XSliderFreeUtils::isMobile() && $settings["mobile"]["hideArrowsOnMobile"]))) {
			if ($settings["arrows"]["source"] != "none" && count($settings["slides"]) > 1) {
				$html .= '<div id="' . $id . '_left_arrow" class="x_left_arrow" style="visibility:hidden">';
				if ($settings["arrows"]["source"] == "icon") {
					$html .= '<i class="fa ' . str_replace("right", "left", $settings["arrows"]["type"]) . '"></i>';
				} else if ($settings["arrows"]["source"] == "image") {
					$html .= '<img src="' . $settings["arrows"]["leftImage"] . '">';
				}

				$html .= '</div>';
				$html .= '<div id="' . $id . '_right_arrow" class="x_right_arrow" style="visibility:hidden">';
				if ($settings["arrows"]["source"] == "icon") {
					$html .= '<i class="fa ' . $settings["arrows"]["type"] . '"></i>';
				} else if ($settings["arrows"]["source"] == "image") {
					$html .= '<img src="' . $settings["arrows"]["rightImage"] . '">';
				}

				$html .= '</div>';
			}
		}

		if (!((XSliderFreeUtils::isIPad() && $settings["mobile"]["hideDotsOnIPad"]) || (XSliderFreeUtils::isMobile() && $settings["mobile"]["hideDotsOnMobile"]))) {
			if ($settings["dots"]["source"] != "none" && count($settings["slides"]) > 1) {
				$html .= '<div id="' . $id . '_dots" class="x_dots" style="visibility:hidden">';
				if (!empty($settings["slides"])) {
					foreach ($settings["slides"] as $idx => $slide) {
						if ($settings["dots"]["source"] == "icon") {
							$html .= '<div id="' . $id . '_dotcont_' . $idx . '" class="x_dot"><i id="' . $id . '_dot_' . $idx . '" class="fa ' . $settings["dots"]["iconOff"] . '"></i></div>';
						} else if ($settings["dots"]["source"] == "image") {
							$html .= '<div id="' . $id . '_dotcont_' . $idx . '" class="x_dot"><img id="' . $id . '_dot_' . $idx . '" src="' . $settings["dots"]["offImage"] . '"></div>';
						}

					}
				}

				$html .= '</div>';
			}
		}

		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	public function generateAdminJs($idProject, $settings, $preview) {
		$id = 'xslider_' . $idProject;

		$settings["id"] = $idProject;
		$json           = json_encode($settings);

		$js = "";
		$js .= '<script>';
		$js .= 'xSliderPreview = new xSliderClass("' . $idProject . '", "", "");';
		$js .= 'xSliderPreview.settings = ' . $json . ';';
		$js .= 'xSliderPreview.settings.start = "ready";';
		$js .= 'xSliderPreview.element = "xslider_' . $idProject . '";';
		$js .= 'xSliderPreview.isMobile = ' . (XSliderFreeUtils::isMobile() ? "true" : "false") . ';';
		$js .= 'xSliderPreview.isIPad = ' . (XSliderFreeUtils::isIPad() ? "true" : "false") . ';';
		$js .= '</script>';

		return $js;
	}

	public function generateFrontendJs($id, $settings, $preview) {
		$json = json_encode($settings);

		$js = "";
		$js .= '<script>';
		$js .= 'jQuery(document).ready(function(){';

		$js .= 'xSlider_' . $id . ' = new xSliderClass("' . $id . '", "", "");';
		$js .= 'xSlider_' . $id . '.settings = ' . $json . ';';
		$js .= 'xSlider_' . $id . '.settings.id = ' . $id . ';';
		$js .= 'xSlider_' . $id . '.element = "xslider_' . $id . '";';
		$js .= 'xSlider_' . $id . '.isMobile = ' . (XSliderFreeUtils::isMobile() ? "true" : "false") . ';';
		$js .= 'xSlider_' . $id . '.isIPad = ' . (XSliderFreeUtils::isIPad() ? "true" : "false") . ';';
		$js .= 'xSlider_' . $id . '.play(0,false);';

		$js .= '})';
		$js .= '</script>';

		return $js;
	}

	public function loadGoogleFonts($loadAll) {
		$fonts_option = get_option(self::PLUGIN_NAME . '_google_fonts');
		$fonts        = json_decode($fonts_option, true);

		$idx = 0;
		if (!empty($fonts)) {
			foreach ($fonts as $f) {
				if ($loadAll || $f["loadOnWebsite"] == "true") {
					$url = str_replace("\/", "/", $f["url"]);
					wp_enqueue_style('xslider-googlefonts-' . $idx, $url);
					$idx++;
				}
			}
		}

	}

	public function xSliderShortcode($atts) {
		extract(shortcode_atts(array("id" => ''), $atts));
		return $this->getXSlider($id);
	}

	public function showXSlider($id, $pages = array()) {
		global $post;

		if ($pages != "" && !empty($pages)) {
			$ps = explode(",", $pages);
			$ps = array_map(function ($v) {return intval(trim($v));}, $ps);
			if (!in_array($post->ID, $ps)) {
				return "";
			}

		}

		return $this->getXSlider($id);
	}

	public function getXSlider($id) {
		global $wpdb;
		global $xSliderGlobalIdx;

		$sql = $wpdb->prepare("SELECT * FROM " . $this->DB_TABLE . " WHERE id = %d", array($id));
		$wpdb->query($sql);
		$result = $wpdb->get_results($sql, ARRAY_A);
		if ($wpdb->num_rows > 0) {
			$settings = json_decode($result[0]["settings"], true);

			unset($settings["ui"]);

			if (XSliderFreeUtils::isIPad()) {
				if ($settings["mobile"]["disableOnIPad"]) {
					return "";
				}

			} else if (XSliderFreeUtils::isMobile()) {
				if ($settings["mobile"]["disableOnMobile"]) {
					return "";
				}

			}

			$css  = $this->generateCss($xSliderGlobalIdx, $settings, true);
			$html = $this->generateHtml($xSliderGlobalIdx, $settings, true);
			$js   = $this->generateFrontendJs($xSliderGlobalIdx, $settings, true);
			$xSliderGlobalIdx++;

			return $css . $html . $js;
		}

		return "";
	}

	public function frontendEnqueue() {

		$this->loadGoogleFonts(false);

		wp_enqueue_script('jquery');

		$jsOnFooter   = (get_option(self::PLUGIN_NAME . '_global_js_on_footer') === "true") ? true : false;
		$loadAwesome  = (get_option(self::PLUGIN_NAME . '_global_load_awesome') === "true") ? true : false;
		$loadTweenMax = (get_option(self::PLUGIN_NAME . '_global_load_tweenmax') === "true") ? true : false;

		if ($loadAwesome) {
			wp_enqueue_style(self::PLUGIN_NAME . '-xslider-css-awesome', $this->PLUGIN_URL . '/vendor/font-awesome/css/font-awesome.min.css');
		}

		if ($loadTweenMax) {
			wp_enqueue_script(self::PLUGIN_NAME . '-js-tweenmax', $this->PLUGIN_URL . '/vendor/TweenMax.min.modified.js', array(), self::VERSION, $jsOnFooter);
		}

		wp_enqueue_style(self::PLUGIN_NAME . '-xslider-css', $this->PLUGIN_URL . '/css/xslider.css', array(), self::VERSION);

		wp_enqueue_script(self::PLUGIN_NAME . '-js-youtube-api', 'https://www.youtube.com/iframe_api');
		wp_enqueue_script(self::PLUGIN_NAME . '-js-touchswipe', $this->PLUGIN_URL . '/vendor/jquery.touchSwipe.min.js', array(), self::VERSION, $jsOnFooter);

		wp_enqueue_script(self::PLUGIN_NAME . '-admin-js', $this->PLUGIN_URL . '/js/xslider.min.js', array(), self::VERSION, $jsOnFooter);
	}

}

$xSliderFree = new xSliderFree();

function showXSliderFree($id, $pages = array()) {
	global $xSliderFree;
	echo $xSliderFree->showXSlider($id, $pages);
}
