<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class VSNN_2612_Public {

    public function run() {
        add_shortcode( 'lw_slider', array( $this, 'render_shortcode' ) );
    }

    public function render_shortcode( $atts ) {
        $id = absint( shortcode_atts( array( 'id' => 0 ), $atts )['id'] );
        if ( ! $id ) return '';

        $items = get_post_meta( $id, '_vsnn_items', true );
        if ( empty( $items ) ) return '';

        $s = get_post_meta( $id, '_vsnn_settings', true ) ?: array();
        $defaults = array('effect'=>'slide','arrow_style'=>'box','autoplay'=>'on','interval'=>3000,'thumbnails'=>'on','lazyload'=>'on','random'=>'off','text_anim'=>'fadeInUp','overlay'=>'none','thumb_pos'=>'bottom','radius'=>0,'shadow'=>'none');
        $s = wp_parse_args( $s, $defaults );

        if ($s['random'] === 'on') { shuffle($items); }

        $uid = 'vsnn-' . $id . '-' . wp_rand();
        
        // --- CLASSES & LAYOUT ---
        // Base Wrapper Class
        $wrapper_cls = 'vsnn-public-wrapper';
        
        // Layout Class
        $show_thumb = ($s['thumbnails']=='on' && count($items)>1);
        if ($show_thumb) {
            $wrapper_cls .= ' layout-' . $s['thumb_pos'];
        } else {
            $wrapper_cls .= ' layout-bottom';
        }

        // Effect Class
        $wrapper_cls .= ' effect-' . $s['effect'];

        // Arrow Class
        $arrow_cls = 'arrow-' . $s['arrow_style'];
        
        // Inline Styles
        $wrapper_style = '';
        if($s['radius']>0) $wrapper_style.='border-radius:'.$s['radius'].'px;';
        if($s['shadow']=='sm') $wrapper_style.='box-shadow:0 2px 4px rgba(0,0,0,0.1);';
        if($s['shadow']=='reg') $wrapper_style.='box-shadow:0 8px 16px rgba(0,0,0,0.2);';
        if($s['shadow']=='lg') $wrapper_style.='box-shadow:0 16px 48px rgba(0,0,0,0.22);';

        // Overlay Style
        $ov_style = '';
        if($s['overlay']=='dark30') $ov_style = 'background:rgba(0,0,0,0.3);';
        if($s['overlay']=='dark50') $ov_style = 'background:rgba(0,0,0,0.5);';
        if($s['overlay']=='gradient') $ov_style = 'background:linear-gradient(to top, rgba(0,0,0,0.9), transparent);';

        // Enqueue Assets
        wp_enqueue_style( 'animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css' );

        ob_start();
        ?>
        <style>
            /* --- WRAPPER LAYOUT (Matches Admin) --- */
            .vsnn-public-wrapper { 
                position: relative; width: 100%; height: 500px; 
                background: #222; overflow: hidden; display: flex; 
                margin-bottom: 20px; box-sizing: border-box;
                z-index: 1; /* Create local stacking context */
            }
            .vsnn-public-wrapper * { box-sizing: border-box; }
            
            /* Main Area */
            .vsnn-public-main { position: relative; flex: 1; height: 100%; overflow: hidden; }
            
            /* Items */
            .vsnn-public-item { 
                position: absolute; top:0; left:0; width:100%; height:100%; 
                display: none; align-items: center; justify-content: center; z-index: 1;
                /* Base transition matching Admin */
                transition: opacity 0.6s ease, transform 0.6s ease, filter 0.6s ease;
            }
            .vsnn-public-item.active { display: flex; opacity: 1; z-index: 5; }
            
            /* Image: Cover Mode */
            .vsnn-public-item img { 
                width: 100%; height: 100%; object-fit: cover; 
                position: relative; z-index: 1; 
            }
            .vsnn-public-item.is-portrait { background: #111; }
            .vsnn-public-item.is-portrait img {
                width: auto; max-width: 100%; height: 100%; object-fit: contain;
            }

            /* --- LAYOUT MODES --- */
            /* Bottom */
            .vsnn-public-wrapper.layout-bottom { display: block; }
            .vsnn-public-wrapper.layout-bottom .vsnn-public-thumbs { 
                position: absolute; bottom: 0; left: 0; width: 100%; 
                justify-content: flex-start; padding-bottom: 10px;
            }

            /* Left */
            .vsnn-public-wrapper.layout-left { flex-direction: row; align-items: center; }
            .vsnn-public-wrapper.layout-left .vsnn-public-thumbs { 
                flex-direction: column; width: 80px; max-height: 100%; 
                overflow-x: hidden; overflow-y: auto; order: 1; border-right: 1px solid rgba(255,255,255,0.1);
            }
            .vsnn-public-wrapper.layout-left .vsnn-public-main { order: 2; }

            /* Right */
            .vsnn-public-wrapper.layout-right { flex-direction: row; align-items: center; }
            .vsnn-public-wrapper.layout-right .vsnn-public-thumbs { 
                flex-direction: column; width: 80px; max-height: 100%; 
                overflow-x: hidden; overflow-y: auto; order: 2; border-left: 1px solid rgba(255,255,255,0.1);
            }
            .vsnn-public-wrapper.layout-right .vsnn-public-main { order: 1; }

            /* --- LAYERS Z-INDEX --- */
            /* Overlay */
            .vsnn-public-overlay { position: absolute; inset: 0; z-index: 10; pointer-events: none; }

            /* Caption */
            .vsnn-public-caption { 
                position: absolute; z-index: 20; pointer-events: none;
                bottom: 90px; left: 10%; right: 10%;
                background: rgba(0,0,0,0.5); color: #fff; padding: 10px; 
                text-align: center; border-radius: 4px; 
            }
            .vsnn-public-caption * { margin: 0; color: #fff; }

            /* Thumbnails */
            .vsnn-public-thumbs { 
                z-index: 30; display: flex; gap: 5px; padding: 10px; 
                background: rgba(0,0,0,0.6); backdrop-filter: blur(2px);
                overflow-x: auto; overflow-y: hidden; flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch; scrollbar-width: thin; box-sizing: border-box;
            }
            .vsnn-public-thumbs img { 
                width: 60px; height: 40px; object-fit: cover; 
                border: 2px solid transparent; opacity: 0.6; cursor: pointer; 
                border-radius: 2px; transition: .2s; background: #000; flex-shrink: 0;
            }
            .vsnn-public-thumbs img.active, .vsnn-public-thumbs img:hover { opacity: 1; border-color: #fff; transform: scale(1.05); }

            /* Arrows (Z-Index lowered to 40) */
            .vsnn-prev, .vsnn-next { 
                position: absolute; top: 50%; transform: translateY(-50%); 
                width: 50px; height: 50px; border: none; cursor: pointer; 
                background: transparent; z-index: 40; 
                display: flex; align-items: center; justify-content: center;
                opacity: 1; 
            }
            .vsnn-prev { left: 0; } .vsnn-next { right: 0; }
            
            /* Arrow Icons */
            .vsnn-icon { width: 30px; height: 30px; display: block; filter: drop-shadow(0 0 2px rgba(0,0,0,0.5)); }
            
            /* Arrow Styles */
            .arrow-box .vsnn-prev, .arrow-box .vsnn-next { background: rgba(0,0,0,0.5); border-radius: 4px; }
            .arrow-circle .vsnn-prev, .arrow-circle .vsnn-next { background: rgba(0,0,0,0.5); border-radius: 50%; }
            .arrow-dark .vsnn-icon { filter: invert(1); }

            /* --- TRANSITION EFFECTS (Match Admin) --- */
            .vsnn-public-wrapper[class*='effect-'] .vsnn-public-item { display: flex !important; opacity: 0; visibility: hidden; pointer-events: none; }
            .vsnn-public-wrapper[class*='effect-'] .vsnn-public-item.active { opacity: 1; visibility: visible; pointer-events: auto; }

            .effect-slide .vsnn-public-item { display: none !important; opacity: 1; visibility: visible; transition: none; }
            .effect-slide .vsnn-public-item.active { display: flex !important; }

            .effect-fade .vsnn-public-item { opacity: 0; }
            .effect-fade .vsnn-public-item.active { opacity: 1; }
            .effect-zoom .vsnn-public-item { transform: scale(0.5); opacity: 0; }
            .effect-zoom .vsnn-public-item.active { transform: scale(1); opacity: 1; }
            .effect-vertical .vsnn-public-item { transform: translateY(100%); opacity: 0; }
            .effect-vertical .vsnn-public-item.active { transform: translateY(0); opacity: 1; }
            .effect-flip .vsnn-public-item { transform: rotateY(90deg); perspective: 1000px; opacity: 0; }
            .effect-flip .vsnn-public-item.active { transform: rotateY(0); opacity: 1; }
            .effect-blur .vsnn-public-item { filter: blur(10px); opacity: 0; }
            .effect-blur .vsnn-public-item.active { filter: blur(0); opacity: 1; }

            /* Responsive */
            @media (max-width: 768px) {
                .vsnn-public-wrapper { height: 300px; }
                .vsnn-public-thumbs img { width: 40px; height: 30px; }
            }
        </style>

        <div class="<?php echo esc_attr($wrapper_cls); ?>" id="<?php echo esc_attr($uid); ?>" style="<?php echo $wrapper_style; ?>" 
             data-interval="<?php echo esc_attr($s['interval']); ?>" 
             data-autoplay="<?php echo esc_attr($s['autoplay']); ?>"
             data-anim="<?php echo esc_attr($s['text_anim']); ?>">
            
            <div class="vsnn-public-main <?php echo esc_attr($arrow_cls); ?>">
                
                <div class="vsnn-public-inner">
                    <?php foreach($items as $k => $v): $act=trim(($k==0?'active ':'') . ($this->is_portrait_item($v)?'is-portrait':'')); ?>
                    <div class="vsnn-public-item <?php echo esc_attr($act); ?>">
                        <?php
                        $image_attrs = array( 'loading' => ( $s['lazyload'] == 'on' && $k > 0 ? 'lazy' : 'eager' ) );
                        $image_filter_css = VSNN_2612_Filters::get_css( $v['filter'] ?? 'none' );

                        if ( 'none' !== $image_filter_css ) {
                            $image_attrs['style'] = 'filter:' . esc_attr( $image_filter_css ) . ';';
                        }

                        echo wp_get_attachment_image( $v['id'], 'full', false, $image_attrs );
                        ?>
                        
                        <?php if($ov_style) echo '<div class="vsnn-public-overlay" style="'.$ov_style.'"></div>'; ?>
                        
                        <?php if($v['caption']): ?>
                        <div class="vsnn-public-caption animate__animated">
                            <?php echo esc_html($v['caption']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button class="vsnn-prev">
                    <img src="data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e" class="vsnn-icon">
                </button>
                <button class="vsnn-next">
                    <img src="data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e" class="vsnn-icon">
                </button>

            </div>

            <?php if($show_thumb): ?>
            <div class="vsnn-public-thumbs">
                <?php foreach($items as $k=>$v): 
                    $thumb_url = wp_get_attachment_image_url($v['id'], 'thumbnail') ?: $v['url']; 
                    $ac = $k==0 ? 'active' : '';
                    $thumb_filter_css = VSNN_2612_Filters::get_css( $v['filter'] ?? 'none' );
                ?>
                <img src="<?php echo esc_url($thumb_url); ?>" class="<?php echo esc_attr($ac); ?>" data-idx="<?php echo esc_attr($k); ?>" style="filter:<?php echo esc_attr($thumb_filter_css); ?>;">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var wrapper = document.getElementById('<?php echo esc_js($uid); ?>');
            if(!wrapper) return;

            var items = wrapper.querySelectorAll('.vsnn-public-item');
            var thumbs = wrapper.querySelectorAll('.vsnn-public-thumbs img');
            var prevBtn = wrapper.querySelector('.vsnn-prev');
            var nextBtn = wrapper.querySelector('.vsnn-next');
            
            var currentIndex = 0;
            var total = items.length;
            var intervalTime = parseInt(wrapper.getAttribute('data-interval')) || 3000;
            var isAutoplay = wrapper.getAttribute('data-autoplay') === 'on';
            var animName = wrapper.getAttribute('data-anim');
            var autoTimer;

            function showSlide(index) {
                if (index >= total) index = 0;
                if (index < 0) index = total - 1;
                currentIndex = index;

                // Remove Active
                items.forEach(el => el.classList.remove('active'));
                thumbs.forEach(el => el.classList.remove('active'));

                // Add Active
                items[currentIndex].classList.add('active');
                if(thumbs[currentIndex]) thumbs[currentIndex].classList.add('active');

                // Caption Animation
                if(animName !== 'none') {
                    var cap = items[currentIndex].querySelector('.vsnn-public-caption');
                    if(cap) {
                        cap.style.opacity = 0;
                        cap.classList.remove('animate__animated', 'animate__'+animName);
                        setTimeout(() => {
                            cap.style.opacity = 1;
                            cap.classList.add('animate__animated', 'animate__'+animName);
                        }, 50);
                    }
                }
            }

            function nextSlide() { showSlide(currentIndex + 1); }
            function prevSlide() { showSlide(currentIndex - 1); }

            // Event Listeners
            if(nextBtn) nextBtn.addEventListener('click', function(e){ e.preventDefault(); nextSlide(); resetTimer(); });
            if(prevBtn) prevBtn.addEventListener('click', function(e){ e.preventDefault(); prevSlide(); resetTimer(); });

            thumbs.forEach(function(t){
                t.addEventListener('click', function(e){
                    e.preventDefault();
                    var idx = parseInt(this.getAttribute('data-idx'));
                    showSlide(idx);
                    resetTimer();
                });
            });

            // Autoplay
            function startTimer() {
                if(isAutoplay) {
                    autoTimer = setInterval(nextSlide, intervalTime);
                }
            }
            function resetTimer() {
                clearInterval(autoTimer);
                startTimer();
            }

            // Init Animation for first slide
            if(animName !== 'none') {
                var firstCap = wrapper.querySelector('.vsnn-public-item.active .vsnn-public-caption');
                if(firstCap) {
                    firstCap.style.opacity = 1; 
                    firstCap.classList.add('animate__animated', 'animate__'+animName);
                }
            }

            startTimer();
        });
        </script>
        <?php
        return ob_get_clean();
    }

    private function is_portrait_item( $item ) {
        $attachment_id = ! empty( $item['id'] ) ? absint( $item['id'] ) : 0;

        if ( ! $attachment_id ) {
            return false;
        }

        $meta = wp_get_attachment_metadata( $attachment_id );

        if ( empty( $meta['width'] ) || empty( $meta['height'] ) ) {
            return false;
        }

        return (int) $meta['height'] > (int) $meta['width'];
    }
}
?>