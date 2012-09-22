 * Zabbix PHP API (via the JSON-RPC Zabbix API)
 * @version 1.0 Public Release - December 23, 2009
 * @author Andrew Farley @ http://andrewfarley.com
 * @see http://andrewfarley.com/zabbix_php_api
 *
 * Based on the Zabbix 1.8 API - The official docs are still slim...
 * @see http://www.zabbix.com/documentation/1.8/api
 *
 * @requires PHP 5.2 or greater
 * @requires PHP JSON functions (json_encode/json_decode)
 * @requires PHP CURL (php5-curl in Ubuntu)
 * @requires Zabbix to be 1.7.2 or greater (so it has the API), preferably 1.8
 *
 * @copyright 2009 Andrew Farley - http://andrewfarley.com
 * @license Wishlist-ware
 * --------------------------------------------------------------------------------
 * Definition of "Wishlist-ware"
 *
 * Andrew Farley (andrewfarley.com) wrote this file. As long as you retain the 
 * copyright and license you can do whatever you want with this. If you use this 
 * and it helps benefit you or your company (and you can afford it) buy me an item
 * from one of my wish lists (on my website) or if we cross paths buy me caffeine
 * in some form and we'll call it even!
 * --------------------------------------------------------------------------------
 *
 * Design Notes:
 *      This was designed using a static design structure, where you call all
 *      methods of this class statically, and it returns data from the Zabbix server
 *      directly to you.  This was so that this class could remain abstracted and not
 *      require instantiation all over the place on large and fractured codebases.
 *
 *      Note: None of the actual objects/methods were implemented in this class, this
 *      leaves this class open to be able to use future methods and objects when they
 *      implement them.  It also makes it easier to make a mistake in calling this
 *      class though as it does no validation of your input, checking for a valid
 *      method, or parameters before calling the remote API.  A future version of the
 *      API will (possibly) create all the classes necessary to help validate data
 *      and ease the use of this API.
 *
 * Simple Usage Examples: 
 *      This is necessary before doing anything.  Your user must have API privileges
 *          ZabbixAPI::login('http://mywebsite.com/path/to/zabbix', 'api_user', 'api_pass');
 *      With no parameters, this simply grabs all valid userid into an array
 *          $users = ZabbixAPI::fetch_column('user','get');
 *      If you want verbose output from any fetch request, add parameter (extendoutput = 1)
 *          $all_hosts_verbose = ZabbixAPI::fetch_array('host','get',array('extendoutput'->1));
 *      This is how you update a user's properties, you just need the userid, and the
 *      value you want to set (in this case, refresh)
 *          $result = ZabbixAPI::query('user','update',array('userid'=>1, 'refresh'=>1000));
 *
 *      NOTE: If any methods return PHP === FALSE, then you can use 
 *      ZabbixAPI::getLastError() to check what the problem was!  :)
 */
