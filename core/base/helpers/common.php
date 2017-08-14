<?php

use Botble\Base\Facades\AdminBarFacade;
use Botble\Base\Facades\PageTitleFacade;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Base\Supports\Editor;
use Botble\Base\Supports\PageTitle;

if (!function_exists('format_time')) {
    /**
     * @param DateTime $timestamp
     * @param $format
     * @return mixed
     * @author Sang Nguyen
     */
    function format_time(DateTime $timestamp, $format = 'j M Y H:i')
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);
        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }
}

if (!function_exists('data_from_database')) {
    /**
     * @param $time
     * @param string $format
     * @return mixed
     * @author Sang Nguyen
     */
    function date_from_database($time, $format = 'Y-m-d')
    {
        return format_time(Carbon::parse($time), $format);
    }
}

if (!function_exists('human_file_size')) {
    /**
     * @param $bytes
     * @param int $precision
     * @return string
     * @author Sang Nguyen
     */
    function human_file_size($bytes, $precision = 2)
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }
}

if (!function_exists('is_image')) {
    /**
     * Is the mime type an image
     *
     * @param $mimeType
     * @return bool
     * @author Sang Nguyen
     */
    function is_image($mimeType)
    {
        return starts_with($mimeType, 'image/');
    }
}

if (!function_exists('get_file_by_size')) {
    /**
     * @param $url
     * @param $size
     * @return mixed
     * @author Sang Nguyen
     */
    function get_file_by_size($url, $size)
    {
        return str_replace(File::name($url), File::name($url) . '-' . $size, $url);
    }
}

if (!function_exists('table_actions')) {
    /**
     * @param $edit
     * @param $delete
     * @param $item
     * @return string
     * @author Sang Nguyen
     */
    function table_actions($edit, $delete, $item)
    {
        return view('bases::elements.tables.actions', compact('edit', 'delete', 'item'))->render();
    }
}

if (!function_exists('restore_action')) {
    /**
     * @param $restore
     * @param $item
     * @return string
     * @author Sang Nguyen
     */
    function restore_action($restore, $item)
    {
        return view('bases::elements.tables.restore', compact('restore', 'item'))->render();
    }
}

if (!function_exists('anchor_link')) {
    /**
     * @param $link
     * @param $name
     * @return string
     * @author Sang Nguyen
     */
    function anchor_link($link, $name)
    {
        return view('bases::elements.tables.link', compact('link', 'name'))->render();
    }
}

if (!function_exists('table_checkbox')) {
    /**
     * @param $id
     * @return string
     * @author Sang Nguyen
     */
    function table_checkbox($id)
    {
        return view('bases::elements.tables.checkbox', compact('id'))->render();
    }
}

if (!function_exists('table_status')) {
    /**
     * @param $status
     * @param null $activated_text
     * @param null $deactivated_text
     * @return string
     * @author Sang Nguyen
     */
    function table_status($status, $activated_text = null, $deactivated_text = null)
    {
        return view('bases::elements.tables.status', compact('status', 'activated_text', 'deactivated_text'))->render();
    }
}

if (!function_exists('table_featured')) {
    /**
     * @param $is_featured
     * @param null $featured_text
     * @param null $not_featured_text
     * @return string
     * @author Tedozi Manson <github.com/duyphan2502>
     */
    function table_featured($is_featured, $featured_text = null, $not_featured_text = null)
    {
        return view('bases::elements.tables.is_featured', compact('is_featured', 'featured_text', 'not_featured_text'))->render();
    }
}

if (!function_exists('string_limit_words')) {
    /**
     * @param $string
     * @param $limit
     * @return string
     * @author Sang Nguyen
     */
    function string_limit_words($string, $limit)
    {
        $ext = null;
        if (strlen($string) > $limit) {
            $ext = '...';
        }
        $string = substr($string, 0, $limit);
        return $string . $ext;
    }
}

if (!function_exists('get_file_data')) {
    /**
     * @param $file
     * @param $convert_to_array
     * @return bool|mixed
     * @author Sang Nguyen
     */
    function get_file_data($file, $convert_to_array = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($convert_to_array) {
                return json_decode($file, true);
            } else {
                return $file;
            }
        }
        return false;
    }
}

if (!function_exists('json_encode_prettify')) {
    /**
     * @param $data
     * @return string
     */
    function json_encode_prettify($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('save_file_data')) {
    /**
     * @param $path
     * @param $data
     * @param $json
     * @return bool|mixed
     * @author Sang Nguyen
     */
    function save_file_data($path, $data, $json = true)
    {
        try {
            if ($json) {
                $data = json_encode_prettify($data);
            }
            File::put($path, $data);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (!function_exists('scan_folder')) {
    /**
     * @param $path
     * @param array $ignore_files
     * @return array
     * @author Sang Nguyen
     */
    function scan_folder($path, $ignore_files = [])
    {
        try {
            if (is_dir($path)) {
                $data = array_diff(scandir($path), array_merge(['.', '..'], $ignore_files));
                natsort($data);
                return $data;
            }
            return [];
        } catch (Exception $ex) {
            return [];
        }
    }
}

/**
 * @return boolean
 * @author Sang Nguyen
 */
function check_database_connection()
{
    try {
        DB::connection()->reconnect();
        return true;
    } catch (Exception $ex) {
        return false;
    }
}

if (!function_exists('language_flag')) {
    /**
     * @return string
     * @param $flag
     * @param $name
     * @author Sang Nguyen
     */
    function language_flag($flag, $name = null)
    {
        return HTML::image(url(BASE_LANGUAGE_FLAG_PATH . $flag . '.png'), $name, ['title' => $name]);
    }
}

function sanitize_html_class($class, $fallback = '')
{
    //Strip out any % encoded octets
    $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $class);

    //Limit to A-Z,a-z,0-9,_,-
    $sanitized = preg_replace('/[^A-Za-z0-9_-]/', '', $sanitized);

    if ('' == $sanitized && $fallback) {
        return sanitize_html_class($fallback);
    }
    /**
     * Filters a sanitized HTML class string.
     *
     * @since 2.8.0
     *
     * @param string $sanitized The sanitized HTML class.
     * @param string $class HTML class before sanitization.
     * @param string $fallback The fallback string.
     */
    return apply_filters('sanitize_html_class', $sanitized, $class, $fallback);
}

if (!function_exists('parse_args')) {
    /**
     * @param $args
     * @param string $defaults
     * @return array
     */
    function parse_args($args, $defaults = '')
    {
        if (is_object($args)) {
            $result = get_object_vars($args);
        } else {
            $result =& $args;
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $result);
        }
        return $result;
    }
}

if (!function_exists('get_object_image')) {
    /**
     * @param $image
     * @param null $size
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function get_object_image($image, $size = null)
    {
        if (!empty($image)) {
            if (empty($size) || $image == '__value__') {
                return url($image);
            }
            return url(get_file_by_size($image, $size));
        } else {
            return url(get_file_by_size(config('media.default-img'), config('media.thumb-size')));
        }
    }
}

if (!function_exists('is_plugin_active')) {
    /**
     * @param $alias
     * @return bool
     */
    function is_plugin_active($alias)
    {
        $plugin = app(PluginInterface::class)->getFirstBy(['alias' => $alias]);
        if (!empty($plugin) && $plugin->status == 1) {
            return true;
        }
        return false;
    }
}

if (!function_exists('render_editor')) {
    /**
     * @param $name
     * @param null $value
     * @return string
     * @author Sang Nguyen
     */
    function render_editor($name, $value = null) {
        $editor = new Editor();
        return $editor->render($name, $value);
    }
}

if (!function_exists('is_in_admin')) {
    /**
     * @return bool
     */
    function is_in_admin()
    {
        $segment = request()->segment(1);
        if ($segment === config('cms.admin_dir')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('admin_bar')) {
    /**
     * @return Botble\Base\Supports\AdminBar
     */
    function admin_bar()
    {
        return AdminBarFacade::getFacadeRoot();
    }
}

if (!function_exists('page_title')) {
    /**
     * @return PageTitle
     */
    function page_title()
    {
        return PageTitleFacade::getFacadeRoot();
    }
}