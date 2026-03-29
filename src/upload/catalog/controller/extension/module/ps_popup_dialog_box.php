<?php
class ControllerExtensionModulePsPopupDialogBox extends Controller
{
    /**
     * Index
     *
     * @param array<string, mixed> $setting array of module settings
     *
     * @return string
     */
    public function index($setting)
    {
        static $module = 0;

        if ($module === 0) {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/ps_popup_dialog_box.css');

            $this->document->addScript('catalog/view/javascript/ps_popup_dialog_box.min.js');
        }

        $this->load->language('extension/module/ps_popup_dialog_box');

        $language_id = $this->config->get('config_language_id');

        if ($this->request->server['HTTPS']) {
            $image_url = $this->config->get('config_ssl') . 'image/';
        } else {
            $image_url = $this->config->get('config_url') . 'image/';
        }

        $data['content'] = isset($setting['content'][$language_id]) ? html_entity_decode($setting['content'][$language_id], ENT_QUOTES, 'UTF-8') : '';
        $data['bg_image'] = isset($setting['bg_image'][$language_id]['image']) && $setting['bg_image'][$language_id]['image'] ? $image_url . html_entity_decode($setting['bg_image'][$language_id]['image'], ENT_QUOTES, 'UTF-8') : '';

        $positionMap = array(
            'top_left' => array('position' => 'top-left', 'suffix' => 'TopLeft'),
            'top_center' => array('position' => 'top-center', 'suffix' => 'TopCenter'),
            'top_right' => array('position' => 'top-right', 'suffix' => 'TopRight'),
            'center_left' => array('position' => 'center-left', 'suffix' => 'CenterLeft'),
            'center_center' => array('position' => 'center-center', 'suffix' => 'CenterCenter'),
            'center_right' => array('position' => 'center-right', 'suffix' => 'CenterRight'),
            'bottom_left' => array('position' => 'bottom-left', 'suffix' => 'BottomLeft'),
            'bottom_center' => array('position' => 'bottom-center', 'suffix' => 'BottomCenter'),
            'bottom_right' => array('position' => 'bottom-right', 'suffix' => 'BottomRight'),
        );

        $pos = isset($positionMap[$setting['position']])
            ? $positionMap[$setting['position']]
            : array('position' => 'center-center', 'suffix' => 'CenterCenter');

        $data['position'] = $pos['position'];
        $data['animation_in'] = $setting['animation_in'] . $pos['suffix'];
        $data['animation_out'] = $setting['animation_out'] . $pos['suffix'];

        $data['trigger'] = $setting['trigger'];
        $data['close_behavior'] = $this->getCookieDays($setting['close_behavior']);
        $data['page_load_delay'] = $setting['page_load_delay'];
        $data['scroll_threshold'] = $setting['scroll_threshold'];
        $data['close_overlay_click'] = $setting['close_overlay_click'];
        $data['width'] = $setting['width'];
        $data['height'] = $setting['height'];
        $data['bg_color'] = $setting['bg_color'];
        $data['box_shadow_color'] = $this->hexToRgb($setting['box_shadow_color']);
        $data['box_shadow_opacity'] = $setting['box_shadow_opacity'];
        $data['backdrop_color'] = $setting['backdrop_color'];
        $data['backdrop_opacity'] = $setting['backdrop_opacity'];
        $data['border_radius'] = $setting['border_radius'];
        $data['cookie_name'] = $setting['cookie_name'];
        $data['module'] = $module++;

        return $this->load->view('extension/module/ps_popup_dialog_box', $data);
    }

    private function getCookieDays($behavior)
    {
        switch ($behavior) {
            case 'immediately':
                return 0;
            case 'day':
                return 1;
            case 'week':
                return 7;
            case 'month':
                return 30;
            case 'year':
                return 365;
            default:
                return 30; // fallback
        }
    }

    private function hexToRgb($hex)
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // If shorthand (e.g., #000) expand to full
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "$r, $g, $b";
    }
}
