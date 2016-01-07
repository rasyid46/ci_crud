<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Shopping Cart Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Shopping Cart
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/cart.html
 */
class CI_bcampur {

	// These are the regular expression rules that we use to validate the product ID and product name
	var $product_id_rules	= '\.a-z0-9_-'; // alpha-numeric, dashes, underscores, or periods
	var $product_name_rules	= '\.\:\-_ a-z0-9'; // alpha-numeric, dashes, underscores, colons or periods

	// Private variables.  Do not change!
	var $CI;
	var $_bcampur_contents	= array();


	/**
	 * Shopping Class Constructor
	 *
	 * The constructor loads the Session class, used to store the shopping bcampur contents.
	 */
	public function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();

		// Are any config settings being passed manually?  If so, set them
		$config = array();
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$config[$key] = $val;
			}
		}

		// Load the Sessions class
		$this->CI->load->library('session', $config);

		// Grab the shopping bcampur array from the session table, if it exists
		if ($this->CI->session->userdata('bcampur_contents') !== FALSE)
		{
			$this->_bcampur_contents = $this->CI->session->userdata('bcampur_contents');
		}
		else
		{
			// No bcampur exists so we'll set some base values
			$this->_bcampur_contents['bcampur_totaldinar'] = 0;
			$this->_bcampur_contents['totaldinar_items'] = 0;
		}

		log_message('debug', "bcampur Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Insert items into the bcampur and save it to the session table
	 *
	 * @access	public
	 * @param	array
	 * @return	bool
	 */
	function insert($items = array())
	{
		// Was any bcampur data passed? No? Bah...
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		// You can either insert a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one. The way we
		// determine the array type is by looking for a required array key named "id"
		// at the top level. If it's not found, we will assume it's a multi-dimensional array.

		$save_bcampur = FALSE;
		if (isset($items['id']))
		{
			if (($rowid = $this->_insert($items)))
			{
				$save_bcampur = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['id']))
				{
					if ($this->_insert($val))
					{
						$save_bcampur = TRUE;
					}
				}
			}
		}

		// Save the bcampur data if the insert was successful
		if ($save_bcampur == TRUE)
		{
			$this->_save_bcampur();
			return isset($rowid) ? $rowid : TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */
	function _insert($items = array())
	{
		// Was any bcampur data passed? No? Bah...
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		// --------------------------------------------------------------------

		// Does the $items array contain an id, quantity, dinar, and name?  These are required
		if ( ! isset($items['id']) OR ! isset($items['qty']) OR ! isset($items['dinar']) OR ! isset($items['name']) OR ! isset($items['dirham']))
		{
			log_message('error', 'The bcampur array must contain a product ID, quantity, dinar, and name. and dirham');
			return FALSE;
		}

		// --------------------------------------------------------------------

		// Prep the quantity. It can only be a number.  Duh...
		$items['qty'] = trim(preg_replace('/([^0-9])/i', '', $items['qty']));
		// Trim any leading zeros
		$items['qty'] = trim(preg_replace('/(^[0]+)/i', '', $items['qty']));

		// If the quantity is zero or blank there's nothing for us to do
		if ( ! is_numeric($items['qty']) OR $items['qty'] == 0)
		{
			return FALSE;
		}

		// --------------------------------------------------------------------

		// Validate the product ID. It can only be alpha-numeric, dashes, underscores or periods
		// Not totaldinarly sure we should impose this rule, but it seems prudent to standardize IDs.
		// Note: These can be user-specified by setting the $this->product_id_rules variable.
		if ( ! preg_match("/^[".$this->product_id_rules."]+$/i", $items['id']))
		{
			log_message('error', 'Invalid product ID.  The product ID can only contain alpha-numeric characters, dashes, and underscores');
			return FALSE;
		}

		// --------------------------------------------------------------------

		// Validate the product name. It can only be alpha-numeric, dashes, underscores, colons or periods.
		// Note: These can be user-specified by setting the $this->product_name_rules variable.
		if ( ! preg_match("/^[".$this->product_name_rules."]+$/i", $items['name']))
		{
			log_message('error', 'An invalid name was submitted as the product name: '.$items['name'].' The name can only contain alpha-numeric characters, dashes, underscores, colons, and spaces');
			return FALSE;
		}

		// --------------------------------------------------------------------

		// Prep the dinar.  Remove anything that isn't a number or decimal point.
		$items['dinar'] = trim(preg_replace('/([^0-9\.])/i', '', $items['dinar']));
		// Trim any leading zeros
		$items['dinar'] = trim(preg_replace('/(^[0]+)/i', '', $items['dinar']));

		// Is the dinar a valid number?
                //tambahan
                
                $items['dirham'] = trim(preg_replace('/([^0-9\.])/i', '', $items['dirham']));
		// Trim any leading zeros
		$items['dirham'] = trim(preg_replace('/(^[0]+)/i', '', $items['dirham']));
                
                //
                
                
		if ( ! is_numeric($items['dinar']))
		{
			log_message('error', 'An invalid dinar was submitted for product ID: '.$items['id']);
			return FALSE;
		}
                
		// --------------------------------------------------------------------
                //tamabahn
                if ( ! is_numeric($items['dirham']))
		{
			log_message('error', 'An invalid dirham was submitted for product ID: '.$items['id']);
			return FALSE;
		}
                
                //
		// We now need to create a unique identifier for the item being inserted into the bcampur.
		// Every time something is added to the bcampur it is stored in the master cart array.
		// Each row in the cart array, however, must have a unique index that identifies not only
		// a particular product, but makes it possible to store identical products with different options.
		// For example, what if someone buys two identical t-shirts (same product ID), but in
		// different sizes?  The product ID (and other attributes, like the name) will be identical for
		// both sizes because it's the same shirt. The only difference will be the size.
		// Internally, we need to treat identical submissions, but with different options, as a unique product.
		// Our solution is to convert the options array to a string and MD5 it along with the product ID.
		// This becomes the unique "row ID"
		if (isset($items['options']) AND count($items['options']) > 0)
		{
			$rowid = md5($items['id'].implode('', $items['options']));
		}
		else
		{
			// No options were submitted so we simply MD5 the product ID.
			// Technically, we don't need to MD5 the ID in this case, but it makes
			// sense to standardize the format of array indexes for both conditions
			$rowid = md5($items['id']);
		}

		// --------------------------------------------------------------------

		// Now that we have our unique "row ID", we'll add our cart items to the master array

		// let's unset this first, just to make sure our index contains only the data from this submission
		unset($this->_bcampur_contents[$rowid]);

		// Create a new index with our new row ID
		$this->_bcampur_contents[$rowid]['rowid'] = $rowid;

		// And add the new items to the bcampur array
		foreach ($items as $key => $val)
		{
			$this->_bcampur_contents[$rowid][$key] = $val;
		}

		// Woot!
		return $rowid;
	}

	// --------------------------------------------------------------------

	/**
	 * Update the bcampur
	 *
	 * This function permits the quantity of a given item to be changed.
	 * Typically it is called from the "view bcampur" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @access	public
	 * @param	array
	 * @param	string
	 * @return	bool
	 */
	function update($items = array())
	{
		// Was any bcampur data passed?
		if ( ! is_array($items) OR count($items) == 0)
		{
			return FALSE;
		}

		// You can either update a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one.  The way we
		// determine the array type is by looking for a required array key named "id".
		// If it's not found we assume it's a multi-dimensional array
		$save_bcampur = FALSE;
		if (isset($items['rowid']) AND isset($items['qty']))
		{
			if ($this->_update($items) == TRUE)
			{
				$save_bcampur = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['rowid']) AND isset($val['qty']))
				{
					if ($this->_update($val) == TRUE)
					{
						$save_bcampur = TRUE;
					}
				}
			}
		}

		// Save the bcampur data if the insert was successful
		if ($save_bcampur == TRUE)
		{
			$this->_save_bcampur();
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Update the bcampur
	 *
	 * This function permits the quantity of a given item to be changed.
	 * Typically it is called from the "view bcampur" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */
	function _update($items = array())
	{
		// Without these array indexes there is nothing we can do
		if ( ! isset($items['qty']) OR ! isset($items['rowid']) OR ! isset($this->_bcampur_contents[$items['rowid']]))
		{
			return FALSE;
		}

		// Prep the quantity
		$items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);

		// Is the quantity a number?
		if ( ! is_numeric($items['qty']))
		{
			return FALSE;
		}

		// Is the new quantity different than what is already saved in the bcampur?
		// If it's the same there's nothing to do
		if ($this->_bcampur_contents[$items['rowid']]['qty'] == $items['qty'])
		{
			return FALSE;
		}

		// Is the quantity zero?  If so we will remove the item from the bcampur.
		// If the quantity is greater than zero we are updating
		if ($items['qty'] == 0)
		{
			unset($this->_bcampur_contents[$items['rowid']]);
		}
		else
		{
			$this->_bcampur_contents[$items['rowid']]['qty'] = $items['qty'];
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Save the bcampur array to the session DB
	 *
	 * @access	private
	 * @return	bool
	 */
	function _save_bcampur()
	{
		// Unset these so our totaldinar can be calculated correctly below
		unset($this->_bcampur_contents['totaldinar_items']);
		unset($this->_bcampur_contents['bcampur_totaldinar']);

		// Lets add up the individual dinars and set the bcampur sub-totaldinar
		$totaldinar = 0;
		$items = 0;
		foreach ($this->_bcampur_contents as $key => $val)
		{
			// We make sure the array contains the proper indexes
			if ( ! is_array($val) OR ! isset($val['dinar']) OR ! isset($val['qty']))
			{
				continue;
			}

			$totaldinar += ($val['dinar'] * $val['qty']);
			$items += $val['qty'];

			// Set the subtotaldinar
			$this->_bcampur_contents[$key]['subtotaldinar'] = ($this->_bcampur_contents[$key]['dinar'] * $this->_bcampur_contents[$key]['qty']);
		}

		// Set the bcampur totaldinar and totaldinar items.
		$this->_bcampur_contents['totaldinar_items'] = $items;
		$this->_bcampur_contents['bcampur_totaldinar'] = $totaldinar;

		// Is our bcampur empty?  If so we delete it from the session
		if (count($this->_bcampur_contents) <= 2)
		{
			$this->CI->session->unset_userdata('bcampur_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}

		// If we made it this far it means that our bcampur has data.
		// Let's pass it to the Session class so it can be stored
		$this->CI->session->set_userdata(array('bcampur_contents' => $this->_bcampur_contents));

		// Woot!
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * bcampur totaldinar
	 *
	 * @access	public
	 * @return	integer
	 */
	function totaldinar()
	{
		return $this->_bcampur_contents['bcampur_totaldinar'];
	}

	// --------------------------------------------------------------------

	/**
	 * totaldinar Items
	 *
	 * Returns the totaldinar item count
	 *
	 * @access	public
	 * @return	integer
	 */
	function totaldinar_items()
	{
		return $this->_bcampur_contents['totaldinar_items'];
	}

	// --------------------------------------------------------------------

	/**
	 * bcampur Contents
	 *
	 * Returns the entire bcampur array
	 *
	 * @access	public
	 * @return	array
	 */
	function contents()
	{
		$bcampur = $this->_bcampur_contents;

		// Remove these so they don't create a problem when showing the bcampur table
		unset($bcampur['totaldinar_items']);
		unset($bcampur['bcampur_totaldinar']);

		return $bcampur;
	}

	// --------------------------------------------------------------------

	/**
	 * Has options
	 *
	 * Returns TRUE if the rowid passed to this function correlates to an item
	 * that has options associated with it.
	 *
	 * @access	public
	 * @return	array
	 */
	function has_options($rowid = '')
	{
		if ( ! isset($this->_bcampur_contents[$rowid]['options']) OR count($this->_bcampur_contents[$rowid]['options']) === 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Product options
	 *
	 * Returns the an array of options, for a particular product row ID
	 *
	 * @access	public
	 * @return	array
	 */
	function product_options($rowid = '')
	{
		if ( ! isset($this->_bcampur_contents[$rowid]['options']))
		{
			return array();
		}

		return $this->_bcampur_contents[$rowid]['options'];
	}

	// --------------------------------------------------------------------

	/**
	 * Format Number
	 *
	 * Returns the supplied number with commas and a decimal point.
	 *
	 * @access	public
	 * @return	integer
	 */
	function format_number($n = '')
	{
		if ($n == '')
		{
			return '';
		}

		// Remove anything that isn't a number or decimal point.
		$n = trim(preg_replace('/([^0-9\.])/i', '', $n));

		return number_format($n, 2, '.', ',');
	}

	// --------------------------------------------------------------------

	/**
	 * Destroy the bcampur
	 *
	 * Empties the bcampur and kills the session
	 *
	 * @access	public
	 * @return	null
	 */
	function destroy()
	{
		unset($this->_bcampur_contents);

		$this->_bcampur_contents['bcampur_totaldinar'] = 0;
		$this->_bcampur_contents['totaldinar_items'] = 0;

		$this->CI->session->unset_userdata('bcampur_contents');
	}


}
// END bcampur Class

/* End of file bcampur.php */
/* Location: ./system/libraries/bcampur.php */