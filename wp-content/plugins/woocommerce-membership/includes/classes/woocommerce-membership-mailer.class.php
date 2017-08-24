<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Email handling class
 *
 * @class WooCommerce_Membership_Mailer
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Mailer')) {

class WooCommerce_Membership_Mailer
{
    public static $aliases = array(
        'customer_membership_granted'             => 'WooCommerce_Membership_Email_Customer_Membership_Granted',
        'customer_membership_expired'             => 'WooCommerce_Membership_Email_Customer_Membership_Expired',
        'customer_membership_expiration_reminder' => 'WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder',
    );

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Add email classes
        add_action('woocommerce_email_classes', array($this, 'add_email_classes'));
    }

    /**
     * Add more email classes besides standard WooCommerce email classess
     *
     * @access public
     * @param array $emails
     * @return array
     */
    public function add_email_classes($emails)
    {
        // Load parent email class first
        require RPWCM_PLUGIN_PATH . 'includes/classes/emails/woocommerce-membership-email.class.php';

        // Load child email classes (parent will be skipped when loading)
        foreach (glob(RPWCM_PLUGIN_PATH . 'includes/classes/emails/*.class.php') as $filename)
        {
            require $filename;
        }

        // Customer emails
        $emails['WooCommerce_Membership_Email_Customer_Membership_Granted'] = new WooCommerce_Membership_Email_Customer_Membership_Granted();
        $emails['WooCommerce_Membership_Email_Customer_Membership_Expired'] = new WooCommerce_Membership_Email_Customer_Membership_Expired();
        $emails['WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder'] = new WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder();

        return $emails;
    }

    /**
     * Send selected email
     *
     * @access public
     * @param string $alias
     * @param string $plan_name
     * @param int $user_id
     * @param array $args
     * @param array $customer_email
     * @return void
     */
    public function send_email($alias, $plan_name, $user_id, $args = array(), $customer_email = false)
    {
        // Cancel sending emails if this is a duplicate website
        if (!apply_filters('woocommerce_membership_send_email', WooCommerce_Membership::is_main_site(), $alias, $plan_name, $user_id, $args)) {
            return;
        }

        // Cancel sending email if we don't have such email
        if (!isset(self::$aliases[$alias])) {
            return;
        }

        global $woocommerce;

        $woocommerce_mailer = $woocommerce->mailer();
        $emails = $woocommerce_mailer->get_emails();
        $emails[self::$aliases[$alias]]->trigger($plan_name, $user_id, $args);

        // Check if we need to send a copy of customer email to admin
        if ($customer_email && $emails[self::$aliases[$alias]]->send_to_admin == 'yes') {
            $emails[self::$aliases[$alias]]->trigger($plan_name, $user_id, $args, true);
        }
    }

    /**
     * Select proper email and send it
     *
     * @access public
     * @param string $alias
     * @param string $plan_name
     * @param int $user_id
     * @param array $args
     * @return void
     */
    public static function send($alias, $plan_name, $user_id, $args = array())
    {
        $mailer = new WooCommerce_Membership_Mailer();
        $mailer->send_email('customer_' . $alias, $plan_name, $user_id, $args, true);
        $mailer->send_email('admin_' . $alias, $plan_name, $user_id, $args);
    }

}

new WooCommerce_Membership_Mailer();

}
