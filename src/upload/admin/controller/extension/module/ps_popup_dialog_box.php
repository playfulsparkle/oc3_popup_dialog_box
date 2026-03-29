<?php
class ControllerExtensionModulePsPopupDialogBox extends Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The documentation URL for the extension.
     */
    const EXTENSION_DOC = 'https://github.com/playfulsparkle/oc3_popup_dialog_box.git';

    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/ps_popup_dialog_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/javascript/codemirror/lib/codemirror.css');
        $this->document->addStyle('view/javascript/codemirror/theme/monokai.css');
        $this->document->addStyle('view/javascript/summernote/summernote.min.css');

        $this->document->addScript('view/javascript/codemirror/lib/codemirror.js');
        $this->document->addScript('view/javascript/codemirror/lib/xml.js');
        $this->document->addScript('view/javascript/codemirror/lib/formatting.js');
        $this->document->addScript('view/javascript/summernote/summernote.min.js');
        $this->document->addScript('view/javascript/summernote/summernote-image-attributes.js');
        $this->document->addScript('view/javascript/summernote/opencart.js');

        $this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_setting_module->addModule('ps_popup_dialog_box', $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['cookie_name'])) {
            $data['error_cookie_name'] = $this->error['cookie_name'];
        } else {
            $data['error_cookie_name'] = '';
        }

        if (isset($this->error['error_content_url'])) {
            $data['error_error_content_url'] = (array) $this->error['error_content_url'];
        } else {
            $data['error_error_content_url'] = [];
        }

        if (isset($this->error['page_load_delay'])) {
            $data['error_page_load_delay'] = $this->error['page_load_delay'];
        } else {
            $data['error_page_load_delay'] = '';
        }

        if (isset($this->error['scroll_threshold'])) {
            $data['error_scroll_threshold'] = $this->error['scroll_threshold'];
        } else {
            $data['error_scroll_threshold'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/ps_popup_dialog_box', 'user_token=' . $this->session->data['user_token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/ps_popup_dialog_box', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/ps_popup_dialog_box', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/ps_popup_dialog_box', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['user_token'] = $this->session->data['user_token'];

        $data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], true));

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['content'])) {
            $data['content'] = (array) $this->request->post['content'];
        } elseif (!empty($module_info)) {
            $data['content'] = (array) $module_info['content'];
        } else {
            $data['content'] = [];
        }

        if (isset($this->request->post['content_url'])) {
            $data['content_url'] = (array) $this->request->post['content_url'];
        } elseif (!empty($module_info)) {
            $data['content_url'] = (array) $module_info['content_url'];
        } else {
            $data['content_url'] = [];
        }

        if (isset($this->request->post['cookie_name'])) {
            $data['cookie_name'] = $this->request->post['cookie_name'];
        } elseif (!empty($module_info)) {
            $data['cookie_name'] = $module_info['cookie_name'];
        } else {
            $data['cookie_name'] = $this->generateUniqueCookieName();
        }

        if (isset($this->request->post['position'])) {
            $data['position'] = $this->request->post['position'];
        } elseif (!empty($module_info)) {
            $data['position'] = $module_info['position'];
        } else {
            $data['position'] = 'center_center';
        }

        if (isset($this->request->post['trigger'])) {
            $data['trigger'] = $this->request->post['trigger'];
        } elseif (!empty($module_info)) {
            $data['trigger'] = $module_info['trigger'];
        } else {
            $data['trigger'] = 'page_load';
        }

        if (isset($this->request->post['page_load_delay'])) {
            $data['page_load_delay'] = $this->request->post['page_load_delay'];
        } elseif (!empty($module_info)) {
            $data['page_load_delay'] = $module_info['page_load_delay'];
        } else {
            $data['page_load_delay'] = 0;
        }

        if (isset($this->request->post['scroll_threshold'])) {
            $data['scroll_threshold'] = $this->request->post['scroll_threshold'];
        } elseif (!empty($module_info)) {
            $data['scroll_threshold'] = $module_info['scroll_threshold'];
        } else {
            $data['scroll_threshold'] = '';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($module_info)) {
            $data['width'] = $module_info['width'];
        } else {
            $data['width'] = '';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($module_info)) {
            $data['height'] = $module_info['height'];
        } else {
            $data['height'] = '';
        }

        if (isset($this->request->post['bg_color'])) {
            $data['bg_color'] = $this->request->post['bg_color'];
        } elseif (!empty($module_info)) {
            $data['bg_color'] = $module_info['bg_color'];
        } else {
            $data['bg_color'] = '#ffffff';
        }

        if (isset($this->request->post['box_shadow_color'])) {
            $data['box_shadow_color'] = $this->request->post['box_shadow_color'];
        } elseif (!empty($module_info)) {
            $data['box_shadow_color'] = $module_info['box_shadow_color'];
        } else {
            $data['box_shadow_color'] = '#000000';
        }

        if (isset($this->request->post['box_shadow_opacity'])) {
            $data['box_shadow_opacity'] = $this->request->post['box_shadow_opacity'];
        } elseif (!empty($module_info)) {
            $data['box_shadow_opacity'] = $module_info['box_shadow_opacity'];
        } else {
            $data['box_shadow_opacity'] = '0.5';
        }

        if (isset($this->request->post['backdrop_color'])) {
            $data['backdrop_color'] = $this->request->post['backdrop_color'];
        } elseif (!empty($module_info)) {
            $data['backdrop_color'] = $module_info['backdrop_color'];
        } else {
            $data['backdrop_color'] = '#000000';
        }

        if (isset($this->request->post['backdrop_opacity'])) {
            $data['backdrop_opacity'] = $this->request->post['backdrop_opacity'];
        } elseif (!empty($module_info)) {
            $data['backdrop_opacity'] = $module_info['backdrop_opacity'];
        } else {
            $data['backdrop_opacity'] = '0.5';
        }

        if (isset($this->request->post['border_radius'])) {
            $data['border_radius'] = $this->request->post['border_radius'];
        } elseif (!empty($module_info)) {
            $data['border_radius'] = $module_info['border_radius'];
        } else {
            $data['border_radius'] = 16;
        }

        if (isset($this->request->post['close_behavior'])) {
            $data['close_behavior'] = $this->request->post['close_behavior'];
        } elseif (!empty($module_info)) {
            $data['close_behavior'] = $module_info['close_behavior'];
        } else {
            $data['close_behavior'] = 'immediately';
        }

        if (isset($this->request->post['animation_in'])) {
            $data['animation_in'] = $this->request->post['animation_in'];
        } elseif (!empty($module_info)) {
            $data['animation_in'] = $module_info['animation_in'];
        } else {
            $data['animation_in'] = 'fadeIn';
        }

        if (isset($this->request->post['animation_out'])) {
            $data['animation_out'] = $this->request->post['animation_out'];
        } elseif (!empty($module_info)) {
            $data['animation_out'] = $module_info['animation_out'];
        } else {
            $data['animation_out'] = 'fadeOut';
        }

        if (isset($this->request->post['close_overlay_click'])) {
            $data['close_overlay_click'] = $this->request->post['close_overlay_click'];
        } elseif (!empty($module_info)) {
            $data['close_overlay_click'] = $module_info['close_overlay_click'];
        } else {
            $data['close_overlay_click'] = '1';
        }

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        $data['languages'] = $languages;

        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $bg_images = array();

        if (isset($this->request->post['bg_image'])) {
            $bg_images = (array) $this->request->post['bg_image'];
        } elseif (!empty($module_info)) {
            $bg_images = (array) $module_info['bg_image'];
        } else {
            foreach ($languages as $language) {
                $bg_images[$language['language_id']] = array();
            }
        }

        foreach ($bg_images as $language_id => $bg_image) {
            if (isset($bg_image['image']) && $bg_image['image'] && is_file(DIR_IMAGE . $bg_image['image'])) {
                $bg_images[$language_id]['thumb'] = $this->model_tool_image->resize($bg_image['image'], 100, 100);
            } else {
                $bg_images[$language_id]['thumb'] = $data['placeholder'];
            }
        }

        $data['bg_image'] = $bg_images;

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $data['positions'] = array(
            'top_left' => $this->language->get('text_top_left'),
            'top_center' => $this->language->get('text_top_center'),
            'top_right' => $this->language->get('text_top_right'),

            'center_left' => $this->language->get('text_center_left'),
            'center_center' => $this->language->get('text_center_center'),
            'center_right' => $this->language->get('text_center_right'),

            'bottom_left' => $this->language->get('text_bottom_left'),
            'bottom_center' => $this->language->get('text_bottom_center'),
            'bottom_right' => $this->language->get('text_bottom_right'),
        );

        $data['triggers'] = array(
            'page_load' => $this->language->get('text_page_load'),
            'exit_intent' => $this->language->get('text_exit_intent'),
            'scroll' => $this->language->get('text_scroll'),
        );

        $data['close_behaviors'] = array(
            'immediately' => $this->language->get('text_reappear_immediately'),
            'day' => $this->language->get('text_reappear_day'),
            'week' => $this->language->get('text_reappear_week'),
            'month' => $this->language->get('text_reappear_month'),
            'year' => $this->language->get('text_reappear_year'),
        );

        $data['animations_in'] = array(
            'fadeIn' => $this->language->get('text_animation_fade'),
            'zoomIn' => $this->language->get('text_animation_zoom'),
            'slideIn' => $this->language->get('text_animation_slide'),
        );

        $data['animations_out'] = array(
            'fadeOut' => $this->language->get('text_animation_fade'),
            'zoomOut' => $this->language->get('text_animation_zoom'),
            'slideOut' => $this->language->get('text_animation_slide'),
        );

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/ps_popup_dialog_box', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/ps_popup_dialog_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $required = array(
            'module_id' => 0,
            'name' => '',
            'cookie_name' => '',
            'page_load_delay' => 0,
            'scroll_threshold' => 0,
            'width' => 0,
            'height' => 0
        );

        $post_info = $this->request->post + $required;

        if ((utf8_strlen($post_info['name']) < 3) || (utf8_strlen($post_info['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (strlen($post_info['cookie_name']) < 3 || strlen($post_info['cookie_name']) > 24) { // 1. Length check (3-24 characters)
            $this->error['cookie_name'] = $this->language->get('error_cookie_name');
        } elseif (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $post_info['cookie_name'])) { // 2. Allowed characters: alphanumeric, underscore, hyphen, dot
            $this->error['cookie_name'] = $this->language->get('error_cookie_name');
        } elseif (preg_match('/^[0-9]/', $post_info['cookie_name'])) { // 3. Optional: must not start with a digit (recommended for compatibility)
            $this->error['cookie_name'] = $this->language->get('error_cookie_name');
        } elseif (strpos($post_info['cookie_name'], '__') === 0) { // 4. Optional: prevent reserved prefixes like "__" (used by some browsers)
            $this->error['cookie_name'] = $this->language->get('error_cookie_name');
        }

        if ($post_info['trigger'] === 'page_load' && $post_info['page_load_delay'] < 0) {
            $this->error['page_load_delay'] = $this->language->get('error_page_load_delay');
        }

        if ($post_info['trigger'] === 'scroll' && !$post_info['scroll_threshold']) {
            $this->error['scroll_threshold'] = $this->language->get('error_scroll_threshold');
        }

        if (!$post_info['width']) {
            $this->error['width'] = $this->language->get('error_width');
        }

        if (!$post_info['height']) {
            $this->error['height'] = $this->language->get('error_height');
        }

        return !$this->error;
    }

    public function install()
    {

    }

    public function uninstall()
    {

    }

    public function generate_cookie_name()
    {
        $json['cookie_name'] = $this->generateUniqueCookieName();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Generate a unique cookie name that passes the validation rules.
     *
     * Rules:
     * - Length: 3-24 characters
     * - Allowed: a-z, A-Z, 0-9, underscore (_), hyphen (-), dot (.)
     * - Must not start with a digit
     * - Must not start with "__" (double underscore)
     *
     * @return string A valid, unique cookie name.
     */
    private function generateUniqueCookieName()
    {
        $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-.';
        $maxLength = 24;
        $minRandom = 5; // ensure at least 5 random chars to keep uniqueness

        // Calculate remaining length after prefix
        $prefix = 'ps_';
        $prefixLength = strlen($prefix);
        $randomLength = $maxLength - $prefixLength;

        if ($randomLength < $minRandom) {
            // Prefix too long; fallback to ignore prefix and generate full random
            $prefix = '';
            $prefixLength = 0;
            $randomLength = $maxLength;
        }

        // Generate random part
        $randomPart = '';
        $charsCount = strlen($allowedChars) - 1;
        for ($i = 0; $i < $randomLength; $i++) {
            $randomPart .= $allowedChars[random_int(0, $charsCount)];
        }

        // Combine prefix + random part
        $cookieName = $prefix . $randomPart;

        // Ensure no double underscore at start (if prefix is empty or ends with underscore)
        if (strpos($cookieName, '__') === 0) {
            // Remove the second underscore or replace
            $cookieName = substr_replace($cookieName, '', 1, 1);
        }

        // Final length check - trim if too long (should not happen, but safe)
        if (strlen($cookieName) > $maxLength) {
            $cookieName = substr($cookieName, 0, $maxLength);
        }

        return $cookieName;
    }
}
