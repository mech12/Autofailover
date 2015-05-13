<?php
/**
 * Content DB data load
 * @param $file
 * @param string $type
 * @return array|bool
 */
function content_load($file, $type = 'default') {
    $ci =& get_instance();
    $ci->load->driver('cache', array(
        'adapter' => 'apc',
        'backup' => 'file'
    ));

    if( $data = $ci->cache->get($file) ) {
        return $data;
    }

    $filename = ROOT_PATH."/content_db/".$type."/".$file.".csv";

    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 2000, ",")) !== FALSE)
        {
            if(!$header) {
                $row = str_replace("\xEF\xBB\xBF",'',$row);
                $header = $row;
            }
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }

    $ci->cache->save($file, $data, 99999);
    return $data;
}

function content_cache_clean() {
    $ci =& get_instance();
    $ci->load->driver('cache', array(
        'adapter' => 'apc',
        'backup' => 'file'
    ));
    return $ci->cache->clean();
}