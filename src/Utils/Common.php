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

            ob_start();

            if ( isset( $items[$key]['content'] ) ) {
                $items[$key]['content']();
            }
            $content = ob_get_clean();

            $items[$key]['content'] = $content;

            if ( isset( $items[$key]['contentCache'] ) ) {
                $items[$key]['contentCache'] = false;
            }
        }

        return $items;
    }
}
