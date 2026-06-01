<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class VSNN_2612_Filters {

    public static function get_presets() {
        $presets = array(
            'Vintage Film' => array(
                'vintage-film-01' => array( 'label' => 'Vintage Film 01 - Kodak Warm', 'css' => 'sepia(0.18) contrast(1.08) saturate(1.18) brightness(1.04)' ),
                'vintage-film-02' => array( 'label' => 'Vintage Film 02 - Fuji Soft', 'css' => 'sepia(0.12) contrast(0.96) saturate(1.24) brightness(1.05) hue-rotate(-4deg)' ),
                'vintage-film-03' => array( 'label' => 'Vintage Film 03 - Portra Cream', 'css' => 'sepia(0.16) contrast(0.94) saturate(1.1) brightness(1.08)' ),
                'vintage-film-04' => array( 'label' => 'Vintage Film 04 - Gold Hour', 'css' => 'sepia(0.28) contrast(1.05) saturate(1.22) brightness(1.06) hue-rotate(-8deg)' ),
                'vintage-film-05' => array( 'label' => 'Vintage Film 05 - Faded Matte', 'css' => 'sepia(0.2) contrast(0.86) saturate(0.92) brightness(1.12)' ),
                'vintage-film-06' => array( 'label' => 'Vintage Film 06 - Dusty Rose', 'css' => 'sepia(0.22) contrast(0.92) saturate(1.12) brightness(1.05) hue-rotate(-12deg)' ),
                'vintage-film-07' => array( 'label' => 'Vintage Film 07 - 70s Print', 'css' => 'sepia(0.35) contrast(1.08) saturate(1.28) brightness(1.02) hue-rotate(-10deg)' ),
                'vintage-film-08' => array( 'label' => 'Vintage Film 08 - 80s Fade', 'css' => 'sepia(0.14) contrast(0.9) saturate(1.35) brightness(1.08) hue-rotate(8deg)' ),
                'vintage-film-09' => array( 'label' => 'Vintage Film 09 - Retro Chrome', 'css' => 'sepia(0.12) contrast(1.18) saturate(1.3) brightness(1.02) hue-rotate(-6deg)' ),
                'vintage-film-10' => array( 'label' => 'Vintage Film 10 - Old Postcard', 'css' => 'sepia(0.45) contrast(0.96) saturate(0.9) brightness(1.06)' ),
                'vintage-film-11' => array( 'label' => 'Vintage Film 11 - Classic Negative', 'css' => 'sepia(0.1) contrast(1.12) saturate(1.08) brightness(0.98)' ),
                'vintage-film-12' => array( 'label' => 'Vintage Film 12 - Cinema Amber', 'css' => 'sepia(0.24) contrast(1.14) saturate(1.18) brightness(0.98) hue-rotate(-8deg)' ),
                'vintage-film-13' => array( 'label' => 'Vintage Film 13 - Warm Grain', 'css' => 'sepia(0.32) contrast(1.02) saturate(1.08) brightness(1.02)' ),
                'vintage-film-14' => array( 'label' => 'Vintage Film 14 - Soft Brown', 'css' => 'sepia(0.38) contrast(0.88) saturate(0.86) brightness(1.1)' ),
                'vintage-film-15' => array( 'label' => 'Vintage Film 15 - Memory Lane', 'css' => 'sepia(0.26) contrast(0.92) saturate(0.96) brightness(1.08) hue-rotate(-4deg)' ),
                'vintage-film-16' => array( 'label' => 'Vintage Film 16 - Analog Blue', 'css' => 'sepia(0.08) contrast(1.02) saturate(1.08) brightness(1.02) hue-rotate(12deg)' ),
                'vintage-film-17' => array( 'label' => 'Vintage Film 17 - Tea Tone', 'css' => 'sepia(0.48) contrast(0.95) saturate(0.82) brightness(1.08)' ),
                'vintage-film-18' => array( 'label' => 'Vintage Film 18 - Burnt Orange', 'css' => 'sepia(0.34) contrast(1.12) saturate(1.42) brightness(0.98) hue-rotate(-16deg)' ),
                'vintage-film-19' => array( 'label' => 'Vintage Film 19 - Retro Green', 'css' => 'sepia(0.18) contrast(1.04) saturate(1.18) brightness(1.02) hue-rotate(18deg)' ),
                'vintage-film-20' => array( 'label' => 'Vintage Film 20 - Washed Print', 'css' => 'sepia(0.22) contrast(0.82) saturate(0.88) brightness(1.16)' ),
                'vintage-film-21' => array( 'label' => 'Vintage Film 21 - Warm Slide', 'css' => 'sepia(0.2) contrast(1.22) saturate(1.32) brightness(1.0) hue-rotate(-7deg)' ),
                'vintage-film-22' => array( 'label' => 'Vintage Film 22 - Soft Sepia', 'css' => 'sepia(0.55) contrast(0.92) saturate(0.78) brightness(1.1)' ),
                'vintage-film-23' => array( 'label' => 'Vintage Film 23 - Muted Gold', 'css' => 'sepia(0.3) contrast(0.9) saturate(1.04) brightness(1.1) hue-rotate(-10deg)' ),
                'vintage-film-24' => array( 'label' => 'Vintage Film 24 - Classic Fade', 'css' => 'sepia(0.16) contrast(0.78) saturate(0.92) brightness(1.18)' ),
                'vintage-film-25' => array( 'label' => 'Vintage Film 25 - Redscale', 'css' => 'sepia(0.4) contrast(1.08) saturate(1.36) brightness(1.02) hue-rotate(-24deg)' ),
                'vintage-film-26' => array( 'label' => 'Vintage Film 26 - Polaroid Warm', 'css' => 'sepia(0.26) contrast(0.9) saturate(1.1) brightness(1.12)' ),
                'vintage-film-27' => array( 'label' => 'Vintage Film 27 - Polaroid Cool', 'css' => 'sepia(0.1) contrast(0.88) saturate(1.04) brightness(1.1) hue-rotate(10deg)' ),
                'vintage-film-28' => array( 'label' => 'Vintage Film 28 - Noir Soft', 'css' => 'grayscale(0.74) sepia(0.18) contrast(1.12) brightness(1.02)' ),
                'vintage-film-29' => array( 'label' => 'Vintage Film 29 - Noir Deep', 'css' => 'grayscale(0.95) contrast(1.24) brightness(0.96)' ),
                'vintage-film-30' => array( 'label' => 'Vintage Film 30 - Archive', 'css' => 'sepia(0.62) contrast(0.86) saturate(0.72) brightness(1.12)' ),
            ),
            'Chill Tone' => array(
                'chill-tone-01' => array( 'label' => 'Chill Tone 01 - Cafe Latte', 'css' => 'sepia(0.18) contrast(0.9) saturate(0.95) brightness(1.08)' ),
                'chill-tone-02' => array( 'label' => 'Chill Tone 02 - Creamy Day', 'css' => 'sepia(0.12) contrast(0.88) saturate(0.9) brightness(1.14)' ),
                'chill-tone-03' => array( 'label' => 'Chill Tone 03 - Pastel Air', 'css' => 'contrast(0.86) saturate(0.82) brightness(1.16)' ),
                'chill-tone-04' => array( 'label' => 'Chill Tone 04 - Soft Peach', 'css' => 'sepia(0.16) contrast(0.92) saturate(1.1) brightness(1.1) hue-rotate(-10deg)' ),
                'chill-tone-05' => array( 'label' => 'Chill Tone 05 - Calm Blue', 'css' => 'contrast(0.92) saturate(0.94) brightness(1.06) hue-rotate(10deg)' ),
                'chill-tone-06' => array( 'label' => 'Chill Tone 06 - Lazy Sunday', 'css' => 'sepia(0.1) contrast(0.84) saturate(0.86) brightness(1.18)' ),
                'chill-tone-07' => array( 'label' => 'Chill Tone 07 - Minimal Beige', 'css' => 'sepia(0.22) contrast(0.88) saturate(0.78) brightness(1.12)' ),
                'chill-tone-08' => array( 'label' => 'Chill Tone 08 - Cozy Room', 'css' => 'sepia(0.2) contrast(0.94) saturate(1.04) brightness(1.06) hue-rotate(-8deg)' ),
                'chill-tone-09' => array( 'label' => 'Chill Tone 09 - Indie Warm', 'css' => 'sepia(0.24) contrast(1.0) saturate(1.18) brightness(1.04)' ),
                'chill-tone-10' => array( 'label' => 'Chill Tone 10 - Lo-fi Soft', 'css' => 'sepia(0.14) contrast(0.8) saturate(0.9) brightness(1.2)' ),
                'chill-tone-11' => array( 'label' => 'Chill Tone 11 - Ocean Chill', 'css' => 'contrast(0.94) saturate(1.06) brightness(1.04) hue-rotate(14deg)' ),
                'chill-tone-12' => array( 'label' => 'Chill Tone 12 - Blue Hour', 'css' => 'contrast(0.96) saturate(0.92) brightness(0.98) hue-rotate(18deg)' ),
                'chill-tone-13' => array( 'label' => 'Chill Tone 13 - Pink Cloud', 'css' => 'sepia(0.08) contrast(0.92) saturate(1.12) brightness(1.08) hue-rotate(-18deg)' ),
                'chill-tone-14' => array( 'label' => 'Chill Tone 14 - Matcha Cream', 'css' => 'sepia(0.12) contrast(0.9) saturate(0.96) brightness(1.08) hue-rotate(22deg)' ),
                'chill-tone-15' => array( 'label' => 'Chill Tone 15 - Warm Breeze', 'css' => 'sepia(0.2) contrast(0.96) saturate(1.12) brightness(1.08) hue-rotate(-6deg)' ),
                'chill-tone-16' => array( 'label' => 'Chill Tone 16 - Soft Mint', 'css' => 'contrast(0.88) saturate(0.88) brightness(1.12) hue-rotate(24deg)' ),
                'chill-tone-17' => array( 'label' => 'Chill Tone 17 - Nude Tone', 'css' => 'sepia(0.24) contrast(0.92) saturate(0.86) brightness(1.1)' ),
                'chill-tone-18' => array( 'label' => 'Chill Tone 18 - Dream Pop', 'css' => 'contrast(0.84) saturate(1.16) brightness(1.16) hue-rotate(-8deg)' ),
                'chill-tone-19' => array( 'label' => 'Chill Tone 19 - Brown Sugar', 'css' => 'sepia(0.34) contrast(0.9) saturate(0.9) brightness(1.06)' ),
                'chill-tone-20' => array( 'label' => 'Chill Tone 20 - Warm Haze', 'css' => 'sepia(0.18) contrast(0.82) saturate(1.02) brightness(1.18) hue-rotate(-4deg)' ),
                'chill-tone-21' => array( 'label' => 'Chill Tone 21 - Quiet Green', 'css' => 'sepia(0.08) contrast(0.92) saturate(0.9) brightness(1.08) hue-rotate(20deg)' ),
                'chill-tone-22' => array( 'label' => 'Chill Tone 22 - Soft Coral', 'css' => 'sepia(0.14) contrast(0.9) saturate(1.14) brightness(1.1) hue-rotate(-14deg)' ),
                'chill-tone-23' => array( 'label' => 'Chill Tone 23 - Milk Tea', 'css' => 'sepia(0.28) contrast(0.86) saturate(0.82) brightness(1.14)' ),
                'chill-tone-24' => array( 'label' => 'Chill Tone 24 - Cloudy Film', 'css' => 'sepia(0.08) contrast(0.78) saturate(0.86) brightness(1.2)' ),
                'chill-tone-25' => array( 'label' => 'Chill Tone 25 - Sunset Chill', 'css' => 'sepia(0.22) contrast(0.96) saturate(1.2) brightness(1.06) hue-rotate(-16deg)' ),
            ),
            'Landscape Color' => array(
                'landscape-01' => array( 'label' => 'Landscape 01 - Vivid Nature', 'css' => 'contrast(1.08) saturate(1.28) brightness(1.02)' ),
                'landscape-02' => array( 'label' => 'Landscape 02 - Mountain Crisp', 'css' => 'contrast(1.16) saturate(1.12) brightness(1.0)' ),
                'landscape-03' => array( 'label' => 'Landscape 03 - Tropical Green', 'css' => 'contrast(1.04) saturate(1.34) brightness(1.04) hue-rotate(8deg)' ),
                'landscape-04' => array( 'label' => 'Landscape 04 - Ocean Pop', 'css' => 'contrast(1.08) saturate(1.22) brightness(1.04) hue-rotate(12deg)' ),
                'landscape-05' => array( 'label' => 'Landscape 05 - Desert Warm', 'css' => 'sepia(0.16) contrast(1.1) saturate(1.18) brightness(1.04) hue-rotate(-8deg)' ),
                'landscape-06' => array( 'label' => 'Landscape 06 - Forest Deep', 'css' => 'contrast(1.18) saturate(1.08) brightness(0.94) hue-rotate(8deg)' ),
                'landscape-07' => array( 'label' => 'Landscape 07 - Sky Clear', 'css' => 'contrast(1.04) saturate(1.16) brightness(1.08) hue-rotate(10deg)' ),
                'landscape-08' => array( 'label' => 'Landscape 08 - Sunset Gold', 'css' => 'sepia(0.2) contrast(1.12) saturate(1.32) brightness(1.02) hue-rotate(-14deg)' ),
                'landscape-09' => array( 'label' => 'Landscape 09 - Sunrise Soft', 'css' => 'sepia(0.14) contrast(0.98) saturate(1.18) brightness(1.1) hue-rotate(-10deg)' ),
                'landscape-10' => array( 'label' => 'Landscape 10 - Rainy Mood', 'css' => 'contrast(0.94) saturate(0.78) brightness(0.98) hue-rotate(8deg)' ),
                'landscape-11' => array( 'label' => 'Landscape 11 - Misty Hill', 'css' => 'contrast(0.82) saturate(0.82) brightness(1.14)' ),
                'landscape-12' => array( 'label' => 'Landscape 12 - Green Valley', 'css' => 'contrast(1.06) saturate(1.24) brightness(1.04) hue-rotate(6deg)' ),
                'landscape-13' => array( 'label' => 'Landscape 13 - Blue Lagoon', 'css' => 'contrast(1.08) saturate(1.3) brightness(1.04) hue-rotate(18deg)' ),
                'landscape-14' => array( 'label' => 'Landscape 14 - Autumn Leaf', 'css' => 'sepia(0.18) contrast(1.08) saturate(1.28) brightness(1.02) hue-rotate(-18deg)' ),
                'landscape-15' => array( 'label' => 'Landscape 15 - Snow Clean', 'css' => 'contrast(1.04) saturate(0.82) brightness(1.16) hue-rotate(6deg)' ),
                'landscape-16' => array( 'label' => 'Landscape 16 - Film Landscape', 'css' => 'sepia(0.12) contrast(1.02) saturate(1.16) brightness(1.04)' ),
                'landscape-17' => array( 'label' => 'Landscape 17 - Clear Travel', 'css' => 'contrast(1.12) saturate(1.18) brightness(1.06)' ),
                'landscape-18' => array( 'label' => 'Landscape 18 - Moody Travel', 'css' => 'contrast(1.16) saturate(0.92) brightness(0.96)' ),
                'landscape-19' => array( 'label' => 'Landscape 19 - Island Bright', 'css' => 'contrast(1.02) saturate(1.24) brightness(1.12) hue-rotate(8deg)' ),
                'landscape-20' => array( 'label' => 'Landscape 20 - Jungle Shade', 'css' => 'contrast(1.12) saturate(1.1) brightness(0.92) hue-rotate(14deg)' ),
                'landscape-21' => array( 'label' => 'Landscape 21 - Road Trip', 'css' => 'sepia(0.1) contrast(1.1) saturate(1.08) brightness(1.02)' ),
                'landscape-22' => array( 'label' => 'Landscape 22 - Beach Pastel', 'css' => 'contrast(0.94) saturate(1.06) brightness(1.14) hue-rotate(10deg)' ),
                'landscape-23' => array( 'label' => 'Landscape 23 - Golden Field', 'css' => 'sepia(0.2) contrast(1.04) saturate(1.24) brightness(1.06) hue-rotate(-12deg)' ),
                'landscape-24' => array( 'label' => 'Landscape 24 - Dramatic Sky', 'css' => 'contrast(1.24) saturate(1.06) brightness(0.96)' ),
                'landscape-25' => array( 'label' => 'Landscape 25 - Fresh Morning', 'css' => 'contrast(0.98) saturate(1.12) brightness(1.12) hue-rotate(6deg)' ),
            ),
        );

        return array_merge( $presets, self::get_extended_presets() );
    }

    private static function get_extended_presets() {
        return array(
            'Vintage Film Extended' => self::build_vintage_extended_presets(),
            'Landscape Color Extended' => self::build_landscape_extended_presets(),
            'Nature Green Extended' => self::build_nature_green_extended_presets(),
        );
    }

    private static function build_vintage_extended_presets() {
        $names = array( 'Amber Roll', 'Expired Stock', 'Cinema Dust', 'Warm Memory', 'Old Negative', 'Retro Grain', 'Sepia Fade', 'Analog Cream', 'Soft Print', 'Copper Light' );
        $presets = array();

        for ( $i = 1; $i <= 70; $i++ ) {
            $sepia      = self::css_decimal( 0.21 + ( ( $i * 7 ) % 49 ) / 100 );
            $contrast   = self::css_decimal( 0.83 + ( ( $i * 5 ) % 44 ) / 100 );
            $saturate   = self::css_decimal( 0.79 + ( ( $i * 11 ) % 84 ) / 100 );
            $brightness = self::css_decimal( 0.93 + ( ( $i * 3 ) % 31 ) / 100 );
            $hue        = -29 + ( ( $i * 13 ) % 59 );
            $key        = sprintf( 'vintage-extended-%03d', $i );
            $label      = sprintf( 'Vintage Extended %03d - %s', $i, $names[ ( $i - 1 ) % count( $names ) ] );

            $presets[ $key ] = array(
                'label' => $label,
                'css'   => sprintf( 'sepia(%s) contrast(%s) saturate(%s) brightness(%s) hue-rotate(%ddeg)', $sepia, $contrast, $saturate, $brightness, $hue ),
            );
        }

        return $presets;
    }

    private static function build_landscape_extended_presets() {
        $names = array( 'Open Sky', 'Mountain Air', 'Desert Glow', 'Coastal Light', 'Golden Valley', 'Misty Road', 'Travel Clear', 'Dramatic Peak', 'Blue Horizon', 'Sunlit Field' );
        $presets = array();

        for ( $i = 1; $i <= 65; $i++ ) {
            $sepia      = self::css_decimal( ( ( $i * 3 ) % 20 ) / 100 );
            $contrast   = self::css_decimal( 0.97 + ( ( $i * 7 ) % 38 ) / 100 );
            $saturate   = self::css_decimal( 1.02 + ( ( $i * 9 ) % 62 ) / 100 );
            $brightness = self::css_decimal( 0.95 + ( ( $i * 5 ) % 30 ) / 100 );
            $hue        = -18 + ( ( $i * 11 ) % 43 );
            $key        = sprintf( 'landscape-extended-%03d', $i );
            $label      = sprintf( 'Landscape Extended %03d - %s', $i, $names[ ( $i - 1 ) % count( $names ) ] );

            $presets[ $key ] = array(
                'label' => $label,
                'css'   => sprintf( 'sepia(%s) contrast(%s) saturate(%s) brightness(%s) hue-rotate(%ddeg)', $sepia, $contrast, $saturate, $brightness, $hue ),
            );
        }

        return $presets;
    }

    private static function build_nature_green_extended_presets() {
        $names = array( 'Forest Calm', 'Moss Light', 'Jungle Fresh', 'Olive Shade', 'Tea Garden', 'Leaf Pop', 'Rainforest', 'Pine Mood', 'Emerald Air', 'Green Valley' );
        $presets = array();

        for ( $i = 1; $i <= 65; $i++ ) {
            $sepia      = self::css_decimal( 0.03 + ( ( $i * 5 ) % 24 ) / 100 );
            $contrast   = self::css_decimal( 0.90 + ( ( $i * 6 ) % 42 ) / 100 );
            $saturate   = self::css_decimal( 0.98 + ( ( $i * 13 ) % 78 ) / 100 );
            $brightness = self::css_decimal( 0.91 + ( ( $i * 4 ) % 33 ) / 100 );
            $hue        = 8 + ( ( $i * 17 ) % 53 );
            $key        = sprintf( 'nature-green-extended-%03d', $i );
            $label      = sprintf( 'Nature Green Extended %03d - %s', $i, $names[ ( $i - 1 ) % count( $names ) ] );

            $presets[ $key ] = array(
                'label' => $label,
                'css'   => sprintf( 'sepia(%s) contrast(%s) saturate(%s) brightness(%s) hue-rotate(%ddeg)', $sepia, $contrast, $saturate, $brightness, $hue ),
            );
        }

        return $presets;
    }

    private static function css_decimal( $value ) {
        return rtrim( rtrim( number_format( $value, 2, '.', '' ), '0' ), '.' );
    }

    public static function get_flat_presets() {
        $flat = array();

        foreach ( self::get_presets() as $group ) {
            $flat = array_merge( $flat, $group );
        }

        return $flat;
    }

    public static function get_filter_map() {
        $map = array( 'none' => 'none' );

        foreach ( self::get_flat_presets() as $key => $preset ) {
            $map[ $key ] = $preset['css'];
        }

        return $map;
    }

    public static function sanitize( $filter ) {
        $filter = sanitize_key( $filter );

        if ( 'none' === $filter || isset( self::get_flat_presets()[ $filter ] ) ) {
            return $filter;
        }

        return 'none';
    }

    public static function get_css( $filter ) {
        $filter = self::sanitize( $filter );

        if ( 'none' === $filter ) {
            return 'none';
        }

        $presets = self::get_flat_presets();

        return $presets[ $filter ]['css'];
    }
}
