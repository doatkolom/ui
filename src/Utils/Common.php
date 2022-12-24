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

            if ( isset( $items[$key]['content'] ) && is_callable( $items[$key]['content'] ) ) {
                ob_start();
                $items[$key]['content']();
                $items[$key]['content'] = ob_get_clean();
            }

            if ( isset( $items[$key]['head'] ) && is_callable( $items[$key]['head'] ) ) {
                ob_start();
                $items[$key]['head']();
                $items[$key]['head'] = ob_get_clean();
            }

            if ( empty( $items[$key]['contentCache'] ) ) {
                $items[$key]['contentCache'] = false;
            }
        }

        return $items;
    }

    public static function mergeClasses( array $defaultSettings, array $settingArgs ): array
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

    public static function moveIcon()
    {
        return '<svg class="cursor-move" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24">
            <path fill="none" d="M0 0h24v24H0V0z"/>
            <path d="M11 18c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 4c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>';
    }
}
