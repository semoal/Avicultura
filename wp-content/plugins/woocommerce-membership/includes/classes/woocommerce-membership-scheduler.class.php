<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle membership-related events
 *
 * @class WooCommerce_Membership_Scheduler
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Scheduler')) {

class WooCommerce_Membership_Scheduler
{
    public static $scheduler_hooks = array(
        'woocommerce_membership_scheduled_expiration'   => 'WooCommerce_Membership_Scheduler::scheduled_expiration',
        'woocommerce_membership_scheduled_grant_access' => 'WooCommerce_Membership_Scheduler::scheduled_grant_access',
        'woocommerce_membership_scheduled_reminder'     => 'WooCommerce_Membership_Scheduler::scheduled_reminder',
    );

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Set up all hooks
        foreach (self::$scheduler_hooks as $hook => $callable) {
            add_action($hook, $callable, 10, 20);
        }
    }

    /**
     * Main scheduling function
     *
     * @access public
     * @param int $timestamp
     * @param string $hook
     * @param array $args
     * @param
     * @return bool
     */
    public static function schedule($timestamp, $hook, $args)
    {
        // First make sure that we don't have exactly the same event scheduled for an earlier time
        $next_scheduled = wp_next_scheduled($hook, $args);

        if ($next_scheduled && $next_scheduled < $timestamp) {
            // Delete same event that is scheduled for an earlier time (we'are simply postponing it)
            wp_unschedule_event($next_scheduled, $hook, $args);
        }

        // Schedule event
        if (wp_schedule_single_event($timestamp, $hook, $args) === false) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Unschedule possibly previously scheduled event(s)
     *
     * @access public
     * @param string $hook
     * @param array $args
     * @param int $timestamp
     * @return void
     */
    public static function unschedule($hook, $args = array(), $timestamp = null)
    {
        // Specific single event?
        if ($timestamp) {

            // Match arguments?
            if (!empty($args)) {
                wp_unschedule_event($timestamp, $hook, $args);
            }
            else {
                wp_unschedule_event($timestamp, $hook);
            }
        }

        // All matching events?
        else {

            // Match arguments?
            if (!empty($args)) {
                wp_clear_scheduled_hook($hook, $args);
            }
            else {
                wp_clear_scheduled_hook($hook);
            }
        }
    }

    /**
     * Schedule membership expiration event for a specific membership plan and user
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $timestamp
     * @return void
     */
    public static function schedule_expiration($plan_id, $user_id, $timestamp)
    {
        return self::schedule($timestamp, 'woocommerce_membership_scheduled_expiration', array(
            (int) $plan_id,
            (int) $user_id,
        ));
    }

    /**
     * Unschedule membership expiration event for a specific membership plan and user
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $timestamp
     * @return void
     */
    public static function unschedule_expiration($plan_id, $user_id, $timestamp = null)
    {
        return self::unschedule('woocommerce_membership_scheduled_expiration', array(
            (int) $plan_id,
            (int) $user_id,
        ), $timestamp);
    }

    /**
     * Scheduled membership expiration event handler
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @return void
     */
    public static function scheduled_expiration($plan_id, $user_id)
    {
        WooCommerce_Membership_Plan::remove_member($plan_id, $user_id);
    }

    /**
     * Schedule grant access event for linked plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $expiration
     * @param int $timestamp
     * @return void
     */
    public static function schedule_grant_access($plan_id, $user_id, $expiration, $timestamp)
    {
        return self::schedule($timestamp, 'woocommerce_membership_scheduled_grant_access', array(
            (int) $plan_id,
            (int) $user_id,
            (int) $expiration,
        ));
    }

    /**
     * Unschedule grant access event for linked plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $expiration
     * @param int $timestamp
     * @return void
     */
    public static function unschedule_grant_access($plan_id, $user_id, $expiration, $timestamp = null)
    {
        return self::unschedule('woocommerce_membership_scheduled_grant_access', array(
            (int) $plan_id,
            (int) $user_id,
            (int) $expiration,
        ), $timestamp);
    }

    /**
     * Scheduled grant access event for linked plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $expiration
     * @return void
     */
    public static function scheduled_grant_access($plan_id, $user_id, $expiration)
    {
        WooCommerce_Membership_Plan::add_member($plan_id, $user_id, $expiration);
    }

    /**
     * Schedule reminder for a specific membership plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $timestamp
     * @return void
     */
    public static function schedule_reminder($plan_id, $user_id, $timestamp, $scheduled_expiration)
    {
        $args = array(
            (int) $plan_id,
            (int) $user_id,
            (int) $scheduled_expiration,
        );

        if (wp_schedule_single_event($timestamp, 'woocommerce_membership_scheduled_reminder', $args) === false) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Unschedule all reminders for a specific membership plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @return void
     */
    public static function unschedule_reminders($plan_id, $user_id, $scheduled_expiration)
    {
        return self::unschedule('woocommerce_membership_scheduled_reminder', array(
            (int) $plan_id,
            (int) $user_id,
            (int) $scheduled_expiration,
        ));
    }

    /**
     * Schedule all reminders for a specific membership plan
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param bool $remove_old
     * @return void
     */
    public static function schedule_reminders($plan_id, $user_id)
    {
        // Try to get scheduled expiration timestamp
        $scheduled_expiration = wp_next_scheduled('woocommerce_membership_scheduled_expiration', array((int) $plan_id, (int) $user_id));

        // If set, get and schedule reminders
        if ($scheduled_expiration) {

            // Get plan
            $plan = WooCommerce_Membership_Plan::cache($plan_id);

            foreach ($plan->get_reminders($scheduled_expiration) as $timestamp) {
                self::schedule_reminder($plan_id, $user_id, $timestamp, $scheduled_expiration);
            }
        }
    }

    /**
     * Scheduled reminder event handler
     *
     * @access public
     * @param int $plan_id
     * @param int $user_id
     * @param int $scheduled_expiration
     * @return void
     */
    public static function scheduled_reminder($plan_id, $user_id, $scheduled_expiration)
    {
        // Get plan
        $plan = WooCommerce_Membership_Plan::cache($plan_id);

        // Count days
        $days = (time() < $scheduled_expiration) ? number_format(($scheduled_expiration - time()) / 86400, 1) : 0;

        if ($plan && $user_id) {
            WooCommerce_Membership_Mailer::send('membership_expiration_reminder', $plan->name, $user_id, array('days' => $days));
        }
    }

}

new WooCommerce_Membership_Scheduler();

}
