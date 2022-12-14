<?php

namespace DoatKolom\Ui\Utils;

class Common
{
    public static function generateRandomString( $length = 10 )
    {
        $characters       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen( $characters );
        $randomString     = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $randomString .= $characters[rand( 0, $charactersLength - 1 )];
        }
        return strtolower( $randomString );
    }

    public static function contentBuffer( array $items )
    {
        foreach ( $items as $key => $tab ) {
            
            if ( isset( $items[$key]['content'] ) && is_callable($items[$key]['content']) ) {
                ob_start();
                $items[$key]['content']();
                $items[$key]['content'] = ob_get_clean();
            }

            if ( isset( $items[$key]['head'] ) && is_callable($items[$key]['head']) ) {
                ob_start();
                $items[$key]['head']();
                $items[$key]['head'] = ob_get_clean();
            }


            if ( isset( $items[$key]['contentCache'] ) ) {
                $items[$key]['contentCache'] = false;
            }
        }

        return $items;
    }

    public static function mergeClasses( array $defaultSettings, array $settingArgs ) : array
    {
        if ( !empty( $settingArgs['classes'] ) ) {
            foreach ( $defaultSettings['classes'] as $key => $classes ) {
                if ( isset( $settingArgs['classes'][$key] ) ) {
                    $defaultSettings['classes'][$key] .= $settingArgs['classes'][$key];
                }
            }
        }

        return $defaultSettings;
    }
}
