<?php
/** 
 * Charts, graphs and any presentation related classes 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
class C2P_bar_chart {
    public function x_axis_ul( $x_axis_array ) {
        echo '<ul class="label">'; 
        foreach( $x_axis_array as $key => $label){
            echo '<li>'.ucfirst( $label).'</li>';
        }
        echo '</ul>';        
    }
    public function y_axis_ul_numeric( $highest_value) {
        $digits = strlen( $highest_value);
        
        if( $digits == 1 || $digits == 2){
            $more_zero = '';    
        }elseif( $digits == 3){
            $more_zero = '0';
        }elseif( $digits == 4){
            $more_zero = '00';
        }elseif( $digits == 5){
            $more_zero = '000';
        }elseif( $digits == 6){
            $more_zero = '0000';
        }
        
        $yaxis_list = '<ul class="y-axis"">';
        $yaxis_list .= '<li>100'.$more_zero.'</li>';
        $yaxis_list .= '<li>75'.$more_zero.'</li>';
        $yaxis_list .= '<li>50'.$more_zero.'</li>';
        $yaxis_list .= '<li>25'.$more_zero.'</li>';
        $yaxis_list .= '<li>0</li>';
        $yaxis_list .= '</ul>';  

        echo $yaxis_list;
    }
    /**
    * establish a $ceil for using in percentage calculations, based on the number of digits and not the number itself.
    * The ceil is purpose made, we do not use ceil() or proper maths in this function. It returns a 1 with following zeroes only.
    * 
    * 1. 87 returns 100
    * 2. 24 returns 100
    * 3. 345 returns 1000
    * 4. 893 returns 1000
    * 5. 8930 returns 10000 
    * 
    * Example Use: $pixels = ( $array[$key]['items'] / ceil_digits_to_zeroes( $digits) ) * 100;
    */
    public function ceil_digits_to_zeroes( $number){
        $digits = strlen( $number);
        
        if( $digits == 1 || $digits == 2){
            $acting_ceil = 100;                                    
        }elseif( $digits == 3){
            $acting_ceil = 1000; 
        }elseif( $digits == 4){
            $acting_ceil = 10000;                           
        }elseif( $digits == 5){
            $acting_ceil = 100000;                            
        }elseif( $digits == 6){
            $acting_ceil = 1000000;                           
        }
        
        return $acting_ceil;        
    }
    public function bar( $key, $array, $color = 'purplebar', $highest_value) { 
        $pixels_per_item = 0;

        $pixels = ( $array[$key]['items'] / $this->ceil_digits_to_zeroes( $highest_value) ) * 100;
        $pixels = $pixels * 2;# chart is 200 pixels high so we double our pixel count    
        $pixels = round( $pixels);# the division creates decimal numbers
                         
        // item display label
        $item_label = $array[$key]['items'];
        if( $array[$key]['items'] < 10){
            $item_label = '<span>'.$array[$key]['items'].'</span>';                 
        }
                                                          
        $this->bar_li( $key, $color, $pixels, $item_label);
    } 
    public function bar_users( $key, $array, $color = 'purplebar', $highest_value) {    
        $pixels_per_item = 0;

        $pixels = ( $array[$key]['users'] / $this->ceil_digits_to_zeroes( $highest_value) ) * 100;
        $pixels = $pixels * 2;# chart is 200 pixels high so we double our pixel count    
        $pixels = round( $pixels);# the division creates decimal numbers
                         
        // item display label
        $item_label = $array[$key]['users'];
        if( $array[$key]['users'] < 10){
            $item_label = '<span>'.$array[$key]['users'].'</span>';                 
        }
        
        $this->bar_li( $key, $color, $pixels, $item_label);
    }   
    public function bar_li( $key, $color, $pixels, $label) {
        echo '<li class="bar'.$key.' '.$color.'" style="height: '.$pixels.'px;">'.$label.'</li>';    
    }                      
}// end class C2P_bar_chart  

?>
