<?php
/**
 * Corppage Plugin
 */

namespace WordPress\Themes\EveOnline\Plugins;

use WordPress\Themes\EveOnline;

\defined('ABSPATH') or die();

class Corppage {
	private $eveApi = null;

	public function __construct() {
		$this->eveApi = new EveOnline\Helper\EveApiHelper;

		$this->registerMetaBoxes();
		$this->registerShortcodes();
	} // END public function __construct()

	public function registerShortcodes() {
		\add_shortcode('corplist', array(
			$this,
			'shortcodeCorplist'
		));
	} // END public function registerShortcodes()

	public function shortcodeCorplist($attributes) {
		$args = \shortcode_atts(
			array(
				'type' => 'boxes'
			),
			$attributes
		);

		/**
		 * Not used at this moment
		 */
		unset($args);
//		$type = $args['type'];

		$corpPages = $this->getCorporationPages();
		$corplistHTML = null;

		if($corpPages !== false) {
			$corplistHTML = $this->getCorporationPagesLoop($corpPages);
		} // END if($corpPages !== false)

		return $corplistHTML;
	} // END public function shortcodeCorplist($attributes)

	/**
	 * Rendering the loop for the corporation pages
	 *
	 * @param object $corpPages
	 * @return string
	 */
	private function getCorporationPagesLoop($corpPages) {
		$uniqueID = \uniqid();
		$corplistHTML .= '<div class="gallery-row row">';
		$corplistHTML .= '<ul class="bootstrap-gallery bootstrap-corporationlist bootstrap-corporationlist-' . $uniqueID . ' clearfix">';

		foreach($corpPages as $page) {
			if(!empty($page->post_content)) {
				$corplistHTML .= $this->getCorporationPageLoopItem($page);
			} // END if(!empty($page->post_content))
		} // END foreach($corpPages as $page)

		$corplistHTML .= '</ul>';
		$corplistHTML .= '</div>';

		$corplistHTML .= '<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery("ul.bootstrap-corporationlist-' . $uniqueID . '").bootstrapGallery({
									"classes" : "col-lg-3 col-md-4 col-sm-6 col-xs-12",
									"hasModal" : false
								});
							});
							</script>';

		return $corplistHTML;
	} // END private function getCorporationPagesLoop($corpPages)

	/**
	 * Rendering the single loop item for the corporation pages
	 *
	 * @param object $page
	 * @return string
	 */
	private function getCorporationPageLoopItem($page) {
		$corpID = \get_post_meta($page->ID, 'eve_page_corp_eve_ID', true);

		$corpLogo = EveOnline\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('corporation', $this->eveApi->getImageServerEndpoint('corporation') . $corpID . '_256.png');

		$corplistHTML .= '<li>';
		$corplistHTML .= '<figure><a href="' . \get_permalink($page->ID) . '"><img src="' . $corpLogo . '" alt="' . $page->post_title . '"></a></figure>';
		$corplistHTML .= '<header><h2 class="corporationlist-title"><a href="' . \get_permalink($page->ID) . '">' . $page->post_title . '</a></h2></header>';

		$corplistHTML .= '<p>' . EveOnline\Helper\StringHelper::cutString(strip_shortcodes($page->post_content), '200') . '</p>';

		$corplistHTML .= '</li>';

		return $corplistHTML;
	} // END private function getCorporationPageLoopItem($page)

	private function getCorporationPages() {
		$returnValue = false;

		$result = new \WP_Query(array(
			'post_type' => 'page',
			'meta_key' => 'eve_page_is_corp_page',
			'meta_value' => 1,
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'ASC'
		));

		if($result) {
			$returnValue =  $result->posts;
		} // END if($result)

		return $returnValue;
	} // END public function getCorporationPages()

	public function registerMetaBoxes() {
		\add_action('add_meta_boxes', array($this, 'addMetaBox'));
		\add_action('save_post', array($this, 'savePageSettings'));
	} // END public function registerMetaBoxes()

	public function addMetaBox() {
		\add_meta_box('eve-corp-page-box', __('Corp Page?', 'eve-online'), array($this, 'renderMetaBox'), 'page', 'side');
	} // END public function addMetaBox()

	public function renderMetaBox($post) {
		$isCorpPage = \get_post_meta($post->ID, 'eve_page_is_corp_page', true);
		$showCorpLogo = \get_post_meta($post->ID, 'eve_page_show_corp_logo', true);
		$corpName = \get_post_meta($post->ID, 'eve_page_corp_name', true);
		$corpID = \get_post_meta($post->ID, 'eve_page_corp_eve_ID', true);
		?>
		<label><strong><?php \_e('Corp Page Settings', 'eve-online'); ?></strong></label>
		<p class="checkbox-wrapper">
			<input id="eve_page_is_corp_page" name="eve_page_is_corp_page" type="checkbox" <?php \checked($isCorpPage); ?>>
			<label for="eve_page_is_corp_page"><?php \_e('Is Corp Page?', 'eve-online'); ?></label>
		</p>
		<p class="checkbox-wrapper">
			<input id="eve_page_show_corp_logo" name="eve_page_show_corp_logo" type="checkbox" <?php \checked($showCorpLogo); ?>>
			<label for="eve_page_show_corp_logo"><?php \_e('Show Corp Logo at the beginning of your page\'s content?', 'eve-online'); ?></label>
		</p>
		<p class="checkbox-wrapper">
			<label for="eve_page_corp_name"><?php \_e('Corporation Name:', 'eve-online'); ?></label><br>
			<input id="eve_page_corp_name" name="eve_page_corp_name" type="text" value="<?php echo $corpName; ?>">
		</p>
		<?php
		if(!empty($corpID)) {
			?>
			<p class="checkbox-wrapper">
				<label for="eve_page_corp_ID"><?php \_e('Corporation ID', 'eve-online'); ?></label>
				<input id="eve_page_corp_ID" name="eve_page_corp_ID" type="text" value="<?php echo \esc_html($corpID); ?>" disabled>
			</p>
			<p class="checkbox-wrapper">
				<label><strong><?php \_e('Corporation Logo', 'eve-online'); ?></strong></label>
				<br>
				<?php
//				$corpLogoPath = $this->eveApi->getImageServerEndpoint('corporation') . $eve_page_corp_eve_ID . '_256.png';
				$corpLogoPath = EveOnline\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('corporation', $this->eveApi->getImageServerEndpoint('corporation') . $corpID . '_256.png');
				?>
				<img src="<?php echo $corpLogoPath; ?>" alt="<?php echo $corpName; ?>">
			</p>
			<?php
		} // END if(!empty($eve_page_corp_eve_ID))

		\wp_nonce_field('save', '_eve_corp_page_nonce');
	} // END public function renderMetaBox($post)

	public function savePageSettings($postID) {
		$postNonce = \filter_input(\INPUT_POST, '_eve_corp_page_nonce');

		if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save')) {
			return false;
		} // END if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save'))

		if(!\current_user_can('edit_post', $postID)) {
			return false;
		} // END if(!\current_user_can('edit_post', $postID))

		if(\defined('DOING_AJAX')) {
			return false;
		} // END if(defined('DOING_AJAX'))

		$isCorpPage = \filter_input(INPUT_POST, 'eve_page_is_corp_page') === 'on';
		$showCorpLogo = '';
		$corpName = '';
		$corpID = '';

		/**
		 * only if we really have a corp page ....
		 */
		if(!empty($isCorpPage)) {
			$showCorpLogo = \filter_input(INPUT_POST, 'eve_page_show_corp_logo') === 'on';
			$corpName = \filter_input(\INPUT_POST, 'eve_page_corp_name');
			$corpID = $this->eveApi->getEveIdFromName(\stripslashes(\filter_input(\INPUT_POST, 'eve_page_corp_name')));
		} // END if(!empty($isCorpPage))

		\update_post_meta($postID, 'eve_page_corp_name', $corpName);
		\update_post_meta($postID, 'eve_page_is_corp_page', $isCorpPage);
		\update_post_meta($postID, 'eve_page_show_corp_logo', $showCorpLogo);
		\update_post_meta($postID, 'eve_page_corp_eve_ID', $corpID);
	} // END public function savePageSettings($postID)

	public static function getCorprationLogo($corpPageID) {
		$eveApi = new EveOnline\Helper\EveApiHelper;

		$corpName = \get_post_meta($corpPageID, 'eve_page_corp_name', true);
		$corpID = \get_post_meta($corpPageID, 'eve_page_corp_eve_ID', true);

		$imagePath = EveOnline\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('corporation', $eveApi->getImageServerEndpoint('corporation') . $corpID . '_256.png');

		if($imagePath !== false) {
			$html = '<div class="eve-corp-page-corp-logo eve-image eve-corporation-logo-container"><figure><img src="' . $imagePath . '" class="eve-corporation-logo" alt="' . esc_html($corpName) . '" width="256">';
			$html .= '<figcaption>' . esc_html($corpName) . '</figcaption>';
			$html .= '</figure></div>';

			return $html;
		} // END if($imagePath !== false)

		return false;
	} // END public static function getCorprationLogo($corpPageID)
} // END class Corppage
