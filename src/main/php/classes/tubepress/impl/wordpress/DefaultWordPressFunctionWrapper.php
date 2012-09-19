<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class tubepress_impl_wordpress_DefaultWordPressFunctionWrapper implements tubepress_spi_wordpress_WordPressFunctionWrapper
{
    /**
     * Retrieves the translated string from WordPress's translate().
     *
     * @param string $message Text to translate.
     * @param string $domain  Domain to retrieve the translated text.
     *
     * @return string Translated text.
     */
    public final function __($message, $domain)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return $message == '' ? '' : __($message, $domain);
    }

    /**
     * Use the function update_option() to update a named option/value pair to the options database table.
     * The option_name value is escaped with $wpdb->escape before the INSERT statement.
     *
     * @param string $name  Name of the option to update.
     * @param string $value The NEW value for this option name. This value can be a string, an array,
     *                      an object or a serialized value.
     *
     * @return boolean True if option value has changed, false if not or if update failed.
     */
    public final function update_option($name, $value)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return update_option($name, $value);
    }

    /**
     * A safe way of getting values for a named option from the options database table.
     *
     * @param string $name Name of the option to retrieve.
     *
     * @return mixed Mixed values for the option.
     */
    public final function get_option($name)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return get_option($name);
    }

    /**
     * A safe way of adding a named option/value pair to the options database table. It does nothing if the option already exists.
     *
     * @param string $name  Name of the option to// TODO: Implement add_options_page() method. be added. Use underscores to separate words, and do not
     *                      use uppercase—this is going to be placed into the database.
     * @param string $value Value for this option name.
     *
     * @return void
     */
    public final function add_option($name, $value)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        add_option($name, $value);
    }

    /**
     * A safe way of removing a named option/value pair from the options database table.
     *
     * @param string $name Name of the option to be deleted.
     *
     * @return boolean TRUE if the option has been successfully deleted, otherwise FALSE.
     */
    public final function delete_option($name)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        delete_option($name);
    }

    /**
     * Add sub menu page to the Settings menu.
     *
     * @param string $pageTitle  The text to be displayed in the title tags of the page when the menu is selected.
     * @param string $menuTitle  The text to be used for the menu
     * @param string $capability The capability required for this menu to be displayed to the user.
     * @param string $menu_slug  The slug name to refer to this menu by (should be unique for this menu).
     * @param mixed  $callback   The function to be called to output the content for this page.
     *
     * @return mixed
     */
    public final function add_options_page($pageTitle, $menuTitle, $capability, $menu_slug, $callback)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return add_options_page($pageTitle, $menuTitle, $capability, $menu_slug, $callback);
    }

    /**
     * Tests if the current request was referred from an admin page, or (given $action parameter)
     * if the current request carries a valid nonce. Used to avoid security exploits.
     *
     * @param string $action   Action nonce.
     * @param string $queryArg Where to look for nonce in $_REQUEST
     *
     * @return mixed Function dies with an appropriate message ("Are you sure you want to do this?" is the default)
     *               if not referred from admin page, returns boolean true if the admin referer was was successfully validated.
     */
    public final function check_admin_referer($action, $queryArg)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return check_admin_referer($action, $queryArg);
    }

    /**
     * This Conditional Tag checks if the Dashboard or the administration panel is being displayed.
     *
     * @return boolean True on success, otherwise false.
     */
    public final function is_admin()
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return is_admin();
    }

    /**
     * The plugins_url template tag retrieves the url to the plugins directory or to a specific file within that directory.
     *
     * @param string $path   Path relative to the plugins URL.
     * @param string $plugin The plugin file that you want to be relative to.
     *
     * @return string Plugins url link with optional path appended.
     */
    public final function plugins_url($path, $path)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return plugins_url($path, $path);
    }

    /**
     * The safe and recommended method of adding JavaScript to a WordPress generated page.
     *
     * @param string $handle Name of the script.
     *
     * @return void
     */
    public final function wp_enqueue_script($handle)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_enqueue_script($handle);
    }

    /**
     * A safe way to add/enqueue a CSS style file to the wordpress generated page.
     *
     * @param string $handle Name of the stylesheet.
     *
     * @return void
     */
    public final function wp_enqueue_style($handle)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_enqueue_style($handle);
    }

    /**
     * A safe way of regisetring javascripts in WordPress for later use with wp_enqueue_script().
     *
     * @param string $handle Name of the script.
     * @param string $src    URL to the script.
     *
     * @return void
     */
    public final function wp_register_script($handle, $src)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_register_script($handle, $src);
    }

    /**
     * Register WordPress Widgets for use in your themes sidebars.
     *
     * @param string $id       Widget ID.
     * @param string $name     Widget display title.
     * @param mixed  $callback Run when widget is called.
     * @param array  $options  Widget options.
     *
     * @return void
     */
    public final function wp_register_sidebar_widget($id, $name, $callback, $options)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_register_sidebar_widget($id, $name, $callback, $options);
    }

    /**
     * A safe way to register a CSS style file for later use with wp_enqueue_style().
     *
     * @param string $handle Name of the stylesheet (which should be unique as it is used to identify the script in the whole system.
     * @param string $src    URL to the stylesheet.     *
     *
     * @return void
     */
    public final function wp_register_style($handle, $src)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_register_style($handle, $src);
    }

    /**
     * Registers widget control callback for customizing options.
     *
     * @param string $id       Sidebar ID.
     * @param string $name     Sidebar display name.
     * @param mixed  $callback Runs when the sidebar is displayed.
     *
     * @return void
     */
    public final function wp_register_widget_control($id, $name, $callback)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        wp_register_widget_control($id, $name, $callback);
    }

    /**
     * Hooks a function on to a specific action.
     *
     * @param string $tag      The name of the action to which $function_to_add is hooked.
     * @param mixed  $function The name of the function you wish to be called.
     *
     * @return void
     */
    public final function add_action($tag, $function)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        add_action($tag, $function);
    }

    /**
     * Hooks a function to a specific filter action.
     *
     * @param string $tag      The name of the filter to hook the $function_to_add to.
     * @param mixed  $function A callback for the function to be called when the filter is applied.
     *
     * @return void
     */
    public final function add_filter($tag, $function)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        add_filter($tag, $function);
    }

    /**
     * Checks if SSL is being used.
     *
     * @return boolean True if SSL, false otherwise.
     */
    public final function is_ssl()
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return is_ssl();
    }

    /**
     * Loads the plugin's translated strings.
     *
     * @param string $domain  Unique identifier for retrieving translated strings.
     * @param string $absPath Relative path to ABSPATH of a folder, where the .mo file resides. Deprecated, but still functional until 2.7.
     * @param string $relPath Relative path to WP_PLUGIN_DIR, with a trailing slash. This is the preferred argument to use.
     *                        It takes precendence over $abs_rel_path
     *
     * @return void
     */
    public final function load_plugin_textdomain($domain, $absPath, $relPath)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        load_plugin_textdomain($domain, $absPath, $relPath);
    }

    /**
     * The site_url template tag retrieves the site url for the current site (where the WordPress core files reside)
     * with the appropriate protocol, 'https' if is_ssl() and 'http' otherwise.
     * If scheme is 'http' or 'https', is_ssl() is overridden.
     *
     * @return string The site URL link.
     */
    public final function site_url()
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        return site_url();
    }
}