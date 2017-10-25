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

$query_string = strrchr($_SERVER['REQUEST_URI'], '?');
if ($query_string)
{
	parse_str(substr($query_string, 1), $query_args);
	if (count($query_args) == 1 && isset($query_args['mid']))
	{
		$redirect_url = getNotEncodedUrl('', 'mid', $query_args['mid']);
	}
	elseif (count($query_args) == 2 && isset($query_args['mid']) && isset($query_args['document_srl']))
	{
		$redirect_url = getNotEncodedUrl('', 'mid', $query_args['mid'], 'document_srl', $query_args['document_srl']);
	}
	else
	{
		return;
	}
	
	header('Location: ' . $redirect_url, true, 301);
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0, no-store, no-cache', true);
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT', true);
	exit;
}
