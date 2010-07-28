<?php
// $Id: autoadmin.cache.inc,v 1.1 2009/12/06 21:16:07 rune Exp $

/**
 * @file
 * Cache functions.
 *
 * @ingroup autoadmin
 * @author Rune Kaagaard rune@prescriba.com
 */

/**
 * Caches the output of a function.
 *
 * @staticvar array $cache
 * @param string $function
 *   The name of the function to cache.
 * @param array $options
 *   The following options are supported. Default values are marked with ().
 *      'clear' => TRUE/(FALSE) // Clear cache for this function.
 * @return mixed
 */
function autoadmin_cache_function($function, $options = array()) {
  static $cache;
  if (!empty($options['clear'])) {
    cache_clear_all($function, 'cache');
  }
  if (isset($cache[$function])) {
    return $cache[$function];
  }
  $function_cache = cache_get($cache);
  if (isset($function_cache->data)) {
    $cache[$function] = $function_cache->data;
    return $cache[$function];
  }
  else {
    $cache[$function] = call_user_func($function);
    cache_set($function, $cache[$function]);
    return $cache[$function];
  }
}