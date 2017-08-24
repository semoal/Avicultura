<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Extends WC_Email class to override some methods
 *
 * @class WooCommerce_Membership_Email
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Email')) {

class WooCommerce_Membership_Email extends WC_Email
{
    public $plan_name;
    public $user_id;

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Template file name
        $this->template = str_replace('_', '-', $this->id);

        // Send a copy to admin?
        $this->send_to_admin = $this->get_option('send_to_admin');

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Trigger a notification
     *
     * @access public
     * @param string $plan_name
     * @param int $user_id
     * @param array $args
     * @param bool $send_to_admin
     * @return void
     */
    public function trigger($plan_name, $user_id, $args = array(), $send_to_admin = false)
    {
        $this->plan_name = $plan_name;
        $this->user_id = $user_id;

        if ($send_to_admin) {
            $this->recipient = get_option('admin_email');
        }
        else {
            $this->recipient = RightPress_WC_Legacy::customer_get_billing_email($user_id);
        }

        // Check if this email type is enabled, recipient is set and we are not on a development website
        if (!$this->is_enabled() || !$this->get_recipient() || !WooCommerce_Membership::is_main_site()) {
            return;
        }

        $this->template_variables = array(
            'user_id'       => $user_id,
            'plan_name'     => $plan_name,
            'days'          => !empty($args['days']) ? $args['days'] : '',
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
        );

        $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
    }

    /**
     * Get subject
     *
     * @access public
     * @return string
     */
    public function get_subject()
    {
        return apply_filters('woocommerce_membership_email_subject_' . $this->id, $this->format_string($this->subject), $this->plan_name, $this->user_id);
    }

    /**
     * Get heading
     *
     * @access public
     * @return string
     */
    public function get_heading()
    {
        return apply_filters('woocommerce_membership_email_heading_' . $this->id, $this->format_string($this->heading), $this->plan_name, $this->user_id);
    }

    /**
     * Get recipient
     *
     * @access public
     * @return string
     */
    public function get_recipient()
    {
        return apply_filters('woocommerce_membership_email_recipient_' . $this->id, $this->recipient, $this->plan_name, $this->user_id);
    }

    /**
     * Get HTML email content
     *
     * @access public
     * @return string
     */
    public function get_content_html()
    {
        ob_start();
        RightPress_Helper::include_template('emails/' . $this->template, RPWCM_PLUGIN_PATH, 'woocommerce-membership', array_merge($this->template_variables, array('plain_text' => false)));
        return ob_get_clean();
    }

    /**
     * Get plain text email content
     *
     * @access public
     * @return string
     */
    public function get_content_plain()
    {
        ob_start();
        RightPress_Helper::include_template('emails/plain/' . $this->template, RPWCM_PLUGIN_PATH, 'woocommerce-membership', array_merge($this->template_variables, array('plain_text' => true)));
        return ob_get_clean();
    }

    /**
     * Initialise settings form fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'     => __('Enable/Disable', 'woocommerce-membership'),
                'type'      => 'checkbox',
                'label'     => __('Enable this email notification', 'woocommerce-membership'),
                'default'   => 'yes',
            ),
            'send_to_admin' => array(
                'title'     => __('Send to admin?', 'woocommerce-membership'),
                'type'      => 'checkbox',
                'label'     => __('Send copy of this email to admin', 'woocommerce-membership'),
                'default'   => 'no',
            ),
            'subject' => array(
                'title'         => __('Subject', 'woocommerce-membership'),
                'type'          => 'text',
                'description'   => sprintf(__('Defaults to <code>%s</code>', 'woocommerce-membership'), $this->subject),
                'placeholder'   => '',
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __('Email heading', 'woocommerce-membership'),
                'type'          => 'text',
                'description'   => sprintf(__('Defaults to <code>%s</code>', 'woocommerce-membership'), $this->heading),
                'placeholder'   => '',
                'default'       => '',
            ),
            'email_type' => array(
                'title'         => __('Email type', 'woocommerce-membership'),
                'type'          => 'select',
                'description'   => __('Choose which format of email to send.', 'woocommerce-membership'),
                'default'       => 'html',
                'class'         => 'email_type',
                'options'       => array(
                    'plain'         => __('Plain text', 'woocommerce-membership'),
                    'html'          => __('HTML', 'woocommerce-membership'),
                    'multipart'     => __('Multipart', 'woocommerce-membership'),
                ),
            ),
        );
    }

}
}
