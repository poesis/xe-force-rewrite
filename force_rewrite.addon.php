<?php

/**
 * @file force_rewrite.addon.php
 * @author Kijin Sung <kijin@kijinsung.com>
 * @license GPLv2 or Later <https://www.gnu.org/licenses/gpl-2.0.html>
 */
if (!defined('__XE__') || $_SERVER['REQUEST_METHOD'] !== 'GET' || $called_position !== 'before_module_init' || !Context::isAllowRewrite())
{
	return;
}

// Get the current query string.
$query_string = strrchr($_SERVER['REQUEST_URI'], '?');

// If there is no query string, the current URL is already in a permalink format.
if (!$query_string)
{
	// If the current URL is the permalink for a document...
	if (Context::get('mid') && $document_srl = Context::get('document_srl'))
	{
		// Get all request vars to check that there are no extra variables.
		$request_vars = Context::getRequestVars();
		
		// Restore the page number.
		if ($addon_info->remove_page == 'Y')
		{	
			if (!isset($request_vars->page) && isset($_SESSION['force_rewrite'][$document_srl]['page']))
			{
				Context::set('page', $_SESSION['force_rewrite'][$document_srl]['page'], true);
			}
		}
		
		// Restore search settings.
		if ($addon_info->remove_search == 'Y')
		{
			if (!isset($request_vars->search_target) && isset($_SESSION['force_rewrite'][$document_srl]['search_target']))
			{
				Context::set('search_target', $_SESSION['force_rewrite'][$document_srl]['search_target'], true);
			}
			if (!isset($request_vars->search_keyword) && isset($_SESSION['force_rewrite'][$document_srl]['search_keyword']))
			{
				Context::set('search_keyword', $_SESSION['force_rewrite'][$document_srl]['search_keyword'], true);
			}
		}
		
		// Restore search division settings.
		if ($addon_info->remove_other == 'Y')
		{
			if (!isset($request_vars->division) && isset($_SESSION['force_rewrite'][$document_srl]['division']))
			{
				Context::set('division', $_SESSION['force_rewrite'][$document_srl]['division'], true);
			}
			if (!isset($request_vars->last_division) && isset($_SESSION['force_rewrite'][$document_srl]['last_division']))
			{
				Context::set('last_division', $_SESSION['force_rewrite'][$document_srl]['last_division'], true);
			}
		}
	}
	
	// Stop processing here.
	return;
}

// If there is a query string, parse it to extract arguments.
parse_str(substr($query_string, 1), $query_args);
$query_count = count($query_args);
$redirect_url = null;

// If there is only mid...
if ($query_count == 1 && isset($query_args['mid']))
{
	$redirect_url = getNotEncodedUrl('', 'mid', $query_args['mid']);
}

// If there are mid and document_srl...
elseif ($query_count == 2 && isset($query_args['mid']) && isset($query_args['document_srl']))
{
	$redirect_url = getNotEncodedUrl('', 'mid', $query_args['mid'], 'document_srl', $query_args['document_srl']);
}

// If there are other variables besides mid and document_srl...
elseif ($query_count >= 3 && isset($query_args['mid']) && isset($query_args['document_srl']))
{
	// Remove the page number.
	if ($addon_info->remove_page == 'Y')
	{
		if (isset($query_args['page']))
		{
			$_SESSION['force_rewrite'][$query_args['document_srl']]['page'] = intval($query_args['page']);
			unset($query_args['page']);
		}
		else
		{
			unset($_SESSION['force_rewrite'][$query_args['document_srl']]['page']);
		}
	}
	
	// Remove search settings.
	if ($addon_info->remove_search == 'Y')
	{
		if (isset($query_args['search_target']))
		{
			$_SESSION['force_rewrite'][$query_args['document_srl']]['search_target'] = trim($query_args['search_target']);
			unset($query_args['search_target']);
		}
		else
		{
			unset($_SESSION['force_rewrite'][$query_args['document_srl']]['search_target']);
		}
		if (isset($query_args['search_keyword']))
		{
			$_SESSION['force_rewrite'][$query_args['document_srl']]['search_keyword'] = trim($query_args['search_keyword']);
			unset($query_args['search_keyword']);
		}
		else
		{
			unset($_SESSION['force_rewrite'][$query_args['document_srl']]['search_keyword']);
		}
	}
	
	// Remove search division settings.
	if ($addon_info->remove_other == 'Y')
	{
		if (isset($query_args['division']))
		{
			$_SESSION['force_rewrite'][$query_args['document_srl']]['division'] = intval($query_args['division']);
			unset($query_args['division']);
		}
		else
		{
			unset($_SESSION['force_rewrite'][$query_args['document_srl']]['division']);
		}
		if (isset($query_args['last_division']))
		{
			$_SESSION['force_rewrite'][$query_args['document_srl']]['last_division'] = intval($query_args['last_division']);
			unset($query_args['last_division']);
		}
		else
		{
			unset($_SESSION['force_rewrite'][$query_args['document_srl']]['last_division']);
		}
	}
	
	// Prevent session storage from becoming too large.
	if (count($_SESSION['force_rewrite']) > 1000)
	{
		array_shift($_SESSION['force_rewrite']);
	}
	
	// If the URL can be rewritten, redirect to the permalink. Otherwise, stop processing.
	if (count($query_args) == 2)
	{
		$redirect_url = getNotEncodedUrl('', 'mid', $query_args['mid'], 'document_srl', $query_args['document_srl']);
	}
	else
	{
		return;
	}
}

// Do not try to process any other form of URL.
else
{
	return;
}

// Redirect to the permalink.
if ($redirect_url)
{
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0, no-store, no-cache', true);
	header('Location: ' . $redirect_url, true, 301);
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT', true);
	exit;
}
