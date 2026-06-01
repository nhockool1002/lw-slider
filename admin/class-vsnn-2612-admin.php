<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class VSNN_2612_Admin {

    public function run() {
        add_action( 'init', array( $this, 'register_cpt' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_data' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
        add_filter( 'manage_lw_slider_posts_columns', array( $this, 'add_columns' ) );
        add_action( 'manage_lw_slider_posts_custom_column', array( $this, 'render_columns' ), 10, 2 );
        add_action( 'admin_footer', array( $this, 'render_docs_modal' ) );
    }

    public function register_cpt() {
        $icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iIzIyNzFCMSI+PHBhdGggZD0iTTEyIDJMNCA2bDggNCA4LTQtOC00em0wIDE0bC04LTR2NGw4IDQgOC00di00bC04IDR6bTAtNmwtOC00djRsOCA0IDgtNHYtNGwtOCA0eiIvPjwvc3ZnPg==';
        register_post_type( 'lw_slider', array(
            'labels' => array('name'=>'LWSlider','singular_name'=>'Slider','menu_name'=>'LWSlider','add_new'=>'Add New','edit_item'=>'Edit Slider','all_items'=>'All Sliders'),
            'public' => false, 'show_ui' => true, 'menu_position' => 20, 'menu_icon' => $icon, 'supports' => array('title')
        ));
    }

    public function enqueue_styles_scripts( $hook ) {
        $screen = get_current_screen();
        if ( ! is_object($screen) || 'lw_slider' !== $screen->post_type || 'post' !== $screen->base ) return;

        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_style( 'animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css' );

        $custom_css = "
            /* --- COMPACT LAYOUT --- */
            #vsnn_settings .inside { padding: 10px 12px!important; margin: 0!important; }
            .vsnn-row { margin-bottom: 6px; }
            .vsnn-label { display: block; font-weight: 700; margin-bottom: 2px; font-size: 12px; color: #444; }
            .vsnn-select, .vsnn-input { width: 100%; height: 30px!important; line-height: 1!important; font-size: 13px; border: 1px solid #ccc; border-radius: 3px; box-shadow: none!important; }
            .vsnn-shortcode-wrap { display: flex; margin-bottom: 10px; }
            .vsnn-shortcode-wrap input { flex: 1; background: #f0f0f1; border: 1px solid #ccc; height: 30px; padding: 0 10px; font-family: monospace; font-size: 11px; }
            .vsnn-shortcode-wrap button { width: 30px; height: 30px; padding: 0; display:flex; align-items:center; justify-content:center; background:#fff; border:1px solid #ccc; border-left:0; cursor:pointer; color:#0073aa; }

            /* --- APPLY BUTTON --- */
            #vsnn-apply-preview {
                width: 100%; margin-top: 10px; background: linear-gradient(135deg, #28a745, #218838);
                border: none; color: white; padding: 8px 0; font-weight: 600; cursor: pointer;
                border-radius: 4px; transition: opacity 0.2s;
            }
            #vsnn-apply-preview:hover { opacity: 0.9; }

            /* --- LOADING OVERLAY --- */
            #vsnn-loading { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.85); z-index: 999999; align-items: center; justify-content: center; flex-direction: column; backdrop-filter: blur(2px); }
            .vsnn-spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #28a745; border-radius: 50%; animation: vsnn-spin 0.8s linear infinite; margin-bottom: 10px; }
            .vsnn-loading-text { font-weight: 600; color: #28a745; font-size: 14px; }
            @keyframes vsnn-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

            /* --- CHECKBOXES --- */
            .vsnn-check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; margin-bottom: 8px; padding: 6px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
            .vsnn-check-row { display: flex; align-items: center; min-height: 22px; }
            .vsnn-check-row input[type='checkbox'] { appearance: none!important; width: 16px!important; height: 16px!important; background: #fff!important; border: 1px solid #8c8f94!important; border-radius: 3px!important; margin: 0 8px 0 0!important; cursor: pointer; position: relative!important; }
            .vsnn-check-row input[type='checkbox']:checked { background-color: #00a32a!important; border-color: #00a32a!important; }
            .vsnn-check-row input[type='checkbox']:checked::after { content: ''; position: absolute; left: 5px; top: 1px; width: 4px; height: 9px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); display: block!important; }

            /* --- MEDIA SORTING --- */
            #vsnn-list { margin: 0 0 10px; }
            .vsnn-media-row { background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 5px; display: flex; gap: 10px; align-items: center; }
            .vsnn-media-row.ui-sortable-helper { box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
            .vsnn-drag { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; color: #8c8f94; cursor: grab; flex-shrink: 0; }
            .vsnn-drag:active, .vsnn-media-row.ui-sortable-helper .vsnn-drag { cursor: grabbing; color: #2271b1; }
            .vsnn-sort-placeholder { min-height: 72px; margin-bottom: 5px; border: 1px dashed #2271b1; background: #f0f6fc; }

            /* --- PREVIEW BOX --- */
            .vsnn-preview-wrapper { position: relative; width: 100%; max-width: 700px; margin: 0 auto; background: #222; border: 1px solid #ccc; overflow: hidden; display: flex; }
            .vsnn-prev-main { position: relative; flex: 1; height: 400px; overflow: hidden; }
            
            /* Items */
            .vsnn-preview-item { position: absolute; top:0; left:0; width:100%; height:100%; display: none; align-items: center; justify-content: center; z-index: 1; transition: opacity 0.5s ease; }
            .vsnn-preview-item.active { display: flex; opacity: 1; z-index: 5; }
            .vsnn-preview-item img { width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1; }
            .vsnn-preview-item.is-portrait { background: #111; }
            .vsnn-preview-item.is-portrait img { width: auto; max-width: 100%; height: 100%; object-fit: contain; }
            
            /* --- TRANSITION EFFECTS --- */
            .vsnn-preview-wrapper[class*='effect-']:not(.effect-slide) .vsnn-preview-item { display: flex !important; opacity: 0; visibility: hidden; pointer-events: none; transition: transform 0.6s ease, opacity 0.6s ease, filter 0.6s ease; }
            .vsnn-preview-wrapper[class*='effect-']:not(.effect-slide) .vsnn-preview-item.active { opacity: 1; visibility: visible; pointer-events: auto; }

            .effect-fade .vsnn-preview-item { opacity: 0; }
            .effect-fade .vsnn-preview-item.active { opacity: 1; }
            .effect-zoom .vsnn-preview-item { transform: scale(0.5); opacity: 0; }
            .effect-zoom .vsnn-preview-item.active { transform: scale(1); opacity: 1; }
            .effect-vertical .vsnn-preview-item { transform: translateY(100%); opacity: 0; }
            .effect-vertical .vsnn-preview-item.active { transform: translateY(0); opacity: 1; }
            .effect-flip .vsnn-preview-item { transform: rotateY(90deg); perspective: 1000px; opacity: 0; }
            .effect-flip .vsnn-preview-item.active { transform: rotateY(0); opacity: 1; }
            .effect-blur .vsnn-preview-item { filter: blur(10px); opacity: 0; }
            .effect-blur .vsnn-preview-item.active { filter: blur(0); opacity: 1; }

            /* --- LAYERS --- */
            .vsnn-prev-overlay { position: absolute; inset: 0; z-index: 10; pointer-events: none; }
            .vsnn-preview-caption { position: absolute; bottom: 90px; left: 10%; right: 10%; background: rgba(0,0,0,0.5); color: #fff; padding: 10px; text-align: center; border-radius: 4px; z-index: 20; }
            
            .vsnn-prev-thumbs { z-index: 30; display: flex; gap: 5px; padding: 10px; background: rgba(0,0,0,0.6); backdrop-filter: blur(2px); }
            .vsnn-prev-thumbs img { width: 60px; height: 40px; object-fit: cover; border: 2px solid transparent; opacity: 0.6; cursor: pointer; border-radius: 2px; transition: .2s; background: #000; }
            .vsnn-prev-thumbs img.active, .vsnn-prev-thumbs img:hover { opacity: 1; border-color: #fff; transform: scale(1.05); }

            .vsnn-prev, .vsnn-next { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border: none; cursor: pointer; background: transparent; color: #fff; font-size: 24px; display:flex; align-items:center; justify-content:center; z-index: 40; }
            .vsnn-prev { left: 0; } .vsnn-next { right: 0; }
            .arrow-box .vsnn-prev, .arrow-box .vsnn-next { background: rgba(0,0,0,0.5); border-radius: 4px; }
            .arrow-circle .vsnn-prev, .arrow-circle .vsnn-next { background: rgba(0,0,0,0.5); border-radius: 50%; }
            .arrow-dark .vsnn-prev, .arrow-dark .vsnn-next { color: #000; text-shadow: 0 0 2px #fff; }
            
            /* Layouts */
            .vsnn-preview-wrapper.layout-bottom { display: block; }
            .vsnn-preview-wrapper.layout-bottom .vsnn-prev-thumbs { position: absolute; bottom: 0; left: 0; width: 100%; justify-content: center; pointer-events: none; }
            .vsnn-preview-wrapper.layout-bottom .vsnn-prev-thumbs img { pointer-events: auto; border-color: rgba(255,255,255,0.5); }
            .vsnn-preview-wrapper.layout-bottom .vsnn-prev-thumbs img.active { border-color: #fff; }

            .vsnn-preview-wrapper.layout-left { flex-direction: row; align-items: center; }
            .vsnn-preview-wrapper.layout-left .vsnn-prev-thumbs { flex-direction: column; width: 80px; max-height: 400px; overflow-y: auto; order: 1; }
            .vsnn-preview-wrapper.layout-left .vsnn-prev-main { order: 2; }

            .vsnn-preview-wrapper.layout-right { flex-direction: row; align-items: center; }
            .vsnn-preview-wrapper.layout-right .vsnn-prev-thumbs { flex-direction: column; width: 80px; max-height: 400px; overflow-y: auto; order: 2; }
            .vsnn-preview-wrapper.layout-right .vsnn-prev-main { order: 1; }
            
            /* --- DOCS MODAL STYLES --- */
            #vsnn-docs-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:99999; backdrop-filter: blur(2px); }
            #vsnn-docs-content { background:#fff; width:70%; max-width:800px; margin:5% auto; padding:0; border-radius:5px; box-shadow:0 10px 25px rgba(0,0,0,0.5); position:relative; overflow:hidden; display:flex; flex-direction:column; max-height:85vh; }
            .vsnn-docs-header { background: #2271b1; color: #fff; padding: 15px 20px; display:flex; justify-content:space-between; align-items:center; }
            .vsnn-docs-header h2 { margin:0; color:#fff; font-size:18px; }
            .vsnn-docs-close { cursor:pointer; font-size:24px; line-height:1; opacity:0.8; }
            .vsnn-docs-close:hover { opacity:1; }
            .vsnn-docs-body { padding:20px; overflow-y:auto; line-height:1.6; color:#333; }
            .vsnn-docs-body h3 { border-bottom: 1px solid #eee; padding-bottom: 8px; margin-top: 20px; color: #2271b1; }
            .vsnn-docs-body ul { list-style: disc; margin-left: 20px; }
            .vsnn-docs-body code { background: #f0f0f1; padding: 2px 5px; border-radius: 3px; font-family: monospace; color: #d63638; }
        ";
        wp_add_inline_style( 'wp-admin', $custom_css );
    }

    public function add_meta_boxes() {
        add_meta_box( 'vsnn_preview', 'Preview', array( $this, 'render_preview_box' ), 'lw_slider', 'normal', 'high' );
        add_meta_box( 'vsnn_images', 'Media', array( $this, 'render_images_box' ), 'lw_slider', 'normal', 'high' );
        add_meta_box( 'vsnn_settings', 'Settings', array( $this, 'render_settings_box' ), 'lw_slider', 'side', 'high' );
    }

    public function render_settings_box( $post ) {
        $s = get_post_meta( $post->ID, '_vsnn_settings', true ) ?: array();
        $defaults = array('effect'=>'slide','text_anim'=>'fadeInUp','arrow_style'=>'box','thumb_pos'=>'bottom','radius'=>0,'shadow'=>'none','overlay'=>'none','interval'=>3000,'thumbnails'=>'on','autoplay'=>'on','lazyload'=>'on','random'=>'off');
        $s = wp_parse_args( $s, $defaults );
        ?>
        <div id="vsnn-loading">
            <div class="vsnn-spinner"></div>
            <div class="vsnn-loading-text">Applying Changes...</div>
        </div>

        <div id="vsnn_settings">
            <button type="button" class="button" style="width:100%;margin-bottom:10px;" onclick="document.getElementById('vsnn-docs-modal').style.display='block'">View Documentation</button>
            
            <label class="vsnn-label">Shortcode:</label>
            <div class="vsnn-shortcode-wrap">
                <input type="text" value='[lw_slider id="<?php echo $post->ID; ?>"]' id="vsnn-sc" readonly onclick="this.select()">
                <button type="button" id="vsnn-copy"><span class="dashicons dashicons-admin-page"></span></button>
            </div>

            <div class="vsnn-row"><label class="vsnn-label">Transition Effect:</label>
                <select name="vsnn_set[effect]" class="vsnn-select" id="vsnn_effect">
                    <?php foreach(['slide'=>'Slide','fade'=>'Fade','vertical'=>'Vertical','zoom'=>'Zoom','flip'=>'Flip','blur'=>'Blur'] as $k=>$v) echo '<option value="'.$k.'" '.selected($s['effect'],$k,false).'>'.$v.'</option>'; ?>
                </select>
            </div>

            <div class="vsnn-row"><label class="vsnn-label">Text Animation:</label>
                <select name="vsnn_set[text_anim]" class="vsnn-select" id="vsnn_anim">
                    <option value="none" <?php selected($s['text_anim'],'none'); ?>>None</option>
                    <optgroup label="Fading"><option value="fadeInUp" <?php selected($s['text_anim'],'fadeInUp'); ?>>Fade In Up</option><option value="fadeInDown" <?php selected($s['text_anim'],'fadeInDown'); ?>>Fade In Down</option></optgroup>
                    <optgroup label="Zoom"><option value="zoomIn" <?php selected($s['text_anim'],'zoomIn'); ?>>Zoom In</option><option value="bounceIn" <?php selected($s['text_anim'],'bounceIn'); ?>>Bounce In</option></optgroup>
                </select>
            </div>

            <div class="vsnn-row"><label class="vsnn-label">Arrow Style:</label>
                <select name="vsnn_set[arrow_style]" class="vsnn-select" id="vsnn_arrow">
                    <option value="default" <?php selected($s['arrow_style'],'default'); ?>>Default</option>
                    <option value="box" <?php selected($s['arrow_style'],'box'); ?>>Box</option>
                    <option value="circle" <?php selected($s['arrow_style'],'circle'); ?>>Circle</option>
                    <option value="dark" <?php selected($s['arrow_style'],'dark'); ?>>Dark</option>
                </select>
            </div>

            <div class="vsnn-row" id="vsnn_thumb_pos_row"><label class="vsnn-label">Thumbnails Position:</label>
                <select name="vsnn_set[thumb_pos]" class="vsnn-select" id="vsnn_thumb_pos_select">
                    <option value="bottom" <?php selected($s['thumb_pos'],'bottom'); ?>>Bottom</option>
                    <option value="left" <?php selected($s['thumb_pos'],'left'); ?>>Left</option>
                    <option value="right" <?php selected($s['thumb_pos'],'right'); ?>>Right</option>
                </select>
            </div>

            <div class="vsnn-row" style="display:flex;gap:10px">
                <div style="flex:1"><label class="vsnn-label">Radius (px):</label>
                    <input type="number" name="vsnn_set[radius]" value="<?php echo esc_attr($s['radius']); ?>" class="vsnn-input" id="vsnn_radius">
                </div>
                <div style="flex:1"><label class="vsnn-label">Shadow:</label>
                    <select name="vsnn_set[shadow]" class="vsnn-select" id="vsnn_shadow">
                        <option value="none" <?php selected($s['shadow'],'none'); ?>>None</option>
                        <option value="sm" <?php selected($s['shadow'],'sm'); ?>>Small</option>
                        <option value="reg" <?php selected($s['shadow'],'reg'); ?>>Regular</option>
                        <option value="lg" <?php selected($s['shadow'],'lg'); ?>>Large</option>
                    </select>
                </div>
            </div>

            <div class="vsnn-row"><label class="vsnn-label">Overlay Color:</label>
                <select name="vsnn_set[overlay]" class="vsnn-select" id="vsnn_overlay">
                    <option value="none" <?php selected($s['overlay'],'none'); ?>>None</option>
                    <option value="dark30" <?php selected($s['overlay'],'dark30'); ?>>Dark 30%</option>
                    <option value="dark50" <?php selected($s['overlay'],'dark50'); ?>>Dark 50%</option>
                    <option value="gradient" <?php selected($s['overlay'],'gradient'); ?>>Gradient</option>
                </select>
            </div>

            <div class="vsnn-check-grid">
                <div class="vsnn-check-row"><label><input type="checkbox" name="vsnn_set[thumbnails]" value="on" id="vsnn_thumb_check" <?php checked($s['thumbnails'],'on'); ?>> Thumbnails</label></div>
                <div class="vsnn-check-row"><label><input type="checkbox" name="vsnn_set[autoplay]" value="on" id="vsnn_autoplay" <?php checked($s['autoplay'],'on'); ?>> Autoplay</label></div>
                <div class="vsnn-check-row"><label><input type="checkbox" name="vsnn_set[lazyload]" value="on" <?php checked($s['lazyload'],'on'); ?>> Lazy Load</label></div>
                <div class="vsnn-check-row"><label><input type="checkbox" name="vsnn_set[random]" value="on" id="vsnn_random" <?php checked($s['random'],'on'); ?>> Random Order</label></div>
            </div>

            <div class="vsnn-row"><label class="vsnn-label">Interval (ms):</label>
                <input type="number" name="vsnn_set[interval]" value="<?php echo esc_attr($s['interval']); ?>" class="vsnn-input" id="vsnn_interval" step="100">
            </div>

            <button type="button" id="vsnn-apply-preview">Apply Preview</button>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('#vsnn-copy').click(function(e){ e.preventDefault(); $('#vsnn-sc').select(); document.execCommand('copy'); });
            
            // Slider Logic Variables
            var vIdx = 0;
            var vTimer;
            var originalItems = [];
            var originalThumbs = [];

            // Store initial order
            $('#vsnn-prev-inner .vsnn-preview-item').each(function(){ originalItems.push($(this)); });
            $('#vsnn-prev-thumbs img').each(function(){ originalThumbs.push($(this)); });

            function updatePreview() {
                var wrap = $('#vsnn-prev-wrap');
                var ov = $('.vsnn-prev-overlay'); 
                
                // --- 1. CSS UPDATES ---
                
                // Arrows & Radius
                wrap.removeClass('arrow-default arrow-box arrow-circle arrow-dark').addClass('arrow-' + $('#vsnn_arrow').val());
                wrap.css('border-radius', $('#vsnn_radius').val() + 'px');
                
                // Shadow
                var sh = $('#vsnn_shadow').val();
                var bs = 'none';
                if(sh=='sm') bs='0 2px 4px rgba(0,0,0,0.1)';
                if(sh=='reg') bs='0 8px 16px rgba(0,0,0,0.2)';
                if(sh=='lg') bs='0 16px 48px rgba(0,0,0,0.22)';
                wrap.css('box-shadow', bs);
                
                // Overlay
                var ovVal = $('#vsnn_overlay').val();
                var bg = 'transparent';
                if(ovVal=='dark30') bg='rgba(0,0,0,0.3)';
                if(ovVal=='dark50') bg='rgba(0,0,0,0.5)';
                if(ovVal=='gradient') bg='linear-gradient(to top, rgba(0,0,0,0.9), transparent)';
                ov.css('background', bg);
                
                // Thumbnails Visibility & Position
                var showThumb = $('#vsnn_thumb_check').is(':checked');
                var thumbPos = $('#vsnn_thumb_pos_select').val();
                var tWrap = $('#vsnn-prev-thumbs');
                tWrap.css('display', showThumb ? 'flex' : 'none');
                
                // Transition Effects
                var effect = $('#vsnn_effect').val();
                wrap.removeClass(function (index, className) {
                    return (className.match (/(^|\s)effect-\S+/g) || []).join(' ');
                });
                wrap.addClass('effect-' + effect);

                // Layout Reset
                wrap.removeClass('layout-bottom layout-left layout-right');
                if(showThumb) {
                    wrap.addClass('layout-' + thumbPos);
                } else {
                    wrap.addClass('layout-bottom');
                }

                // --- 2. LOGIC UPDATES (Random, Autoplay) ---
                
                // Clear Timer
                clearInterval(vTimer);
                
                // Handle Random
                var isRandom = $('#vsnn_random').is(':checked');
                var containerItems = $('#vsnn-prev-inner');
                var containerThumbs = $('#vsnn-prev-thumbs');
                
                // Always reset to original first
                containerItems.empty();
                containerThumbs.empty();
                
                var currentItems = originalItems.slice();
                var currentThumbs = originalThumbs.slice();
                
                if(isRandom) {
                    // Shuffle arrays (keeping pairs synced)
                    // Simple shuffle
                    for (let i = currentItems.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [currentItems[i], currentItems[j]] = [currentItems[j], currentItems[i]];
                        [currentThumbs[i], currentThumbs[j]] = [currentThumbs[j], currentThumbs[i]];
                    }
                }
                
                // Append back
                $.each(currentItems, function(i, el){ containerItems.append(el); });
                $.each(currentThumbs, function(i, el){ 
                    // Re-bind click index because order changed
                    el.attr('onclick', 'vsnnMoveTo('+i+')');
                    containerThumbs.append(el); 
                });

                // Reset Index
                vIdx = 0;
                updateActiveState();

                // Handle Autoplay
                var isAutoplay = $('#vsnn_autoplay').is(':checked');
                var interval = parseInt($('#vsnn_interval').val()) || 3000;
                
                if(isAutoplay) {
                    vTimer = setInterval(function(){ vsnnMove(1); }, interval);
                }
            }
            
            // Shared Logic functions (Global scope needed for onclick attributes)
            window.updateActiveState = function() {
                var vEls = $('#vsnn-prev-inner .vsnn-preview-item');
                var vThm = $('#vsnn-prev-thumbs img');
                
                vEls.removeClass('active');
                vEls.eq(vIdx).addClass('active');
                
                vThm.removeClass('active');
                vThm.eq(vIdx).addClass('active');
                
                // Anim
                var cap = vEls.eq(vIdx).find('.vsnn-preview-caption');
                var animName = $('#vsnn_anim').val();
                if(cap.length) { 
                    cap.css('opacity', 0).removeClass('animate__animated animate__'+animName);
                    setTimeout(function(){
                        cap.css('opacity', 1).addClass('animate__animated animate__'+animName);
                    }, 50);
                }
            }
            
            window.vsnnMove = function(n) {
                var len = $('#vsnn-prev-inner .vsnn-preview-item').length;
                vIdx = (vIdx + n + len) % len;
                updateActiveState();
            }
            
            window.vsnnMoveTo = function(n) {
                vIdx = n;
                updateActiveState();
                // Reset timer on click
                var isAutoplay = $('#vsnn_autoplay').is(':checked');
                if(isAutoplay) {
                    clearInterval(vTimer);
                    var interval = parseInt($('#vsnn_interval').val()) || 3000;
                    vTimer = setInterval(function(){ vsnnMove(1); }, interval);
                }
            }

            // Prevent auto-reload
            $('#vsnn_effect, #vsnn_arrow, #vsnn_shadow, #vsnn_overlay, #vsnn_thumb_pos_select').on('change', function(e){
                e.preventDefault(); e.stopPropagation();
            });
            
            // Toggle Thumbnails Checkbox
            $('#vsnn_thumb_check').change(function(){
                var disabled = !$(this).is(':checked');
                $('#vsnn_thumb_pos_select').prop('disabled', disabled);
                $('#vsnn_thumb_pos_row').css('opacity', disabled ? '0.5' : '1');
            }).trigger('change');
            
            // APPLY BUTTON
            $('#vsnn-apply-preview').click(function(e){
                e.preventDefault();
                $('#vsnn-loading').css('display', 'flex');
                setTimeout(function(){
                    updatePreview();
                    $('#vsnn-loading').fadeOut(200);
                }, 500);
            });

            // Arrows binding
            $(document).on('click', '.vsnn-prev', function(e){ e.preventDefault(); vsnnMove(-1); });
            $(document).on('click', '.vsnn-next', function(e){ e.preventDefault(); vsnnMove(1); });

            // Initial call
            updatePreview();
        });
        </script>
        <?php
    }

    public function render_images_box( $post ) {
        $items = get_post_meta( $post->ID, '_vsnn_items', true );
        wp_nonce_field( 'vsnn_save', 'vsnn_nonce' );
        ?>
        <div id="vsnn-wrapper">
            <ul id="vsnn-list" class="ui-sortable"><?php if(!empty($items)) foreach($items as $i) $this->render_item_row($i); ?></ul>
            <button class="button button-primary" id="vsnn-add">Add Media</button>
        </div>
        <div id="vsnn-tmpl" style="display:none;"><?php $this->render_item_row(['id'=>'','url'=>'','caption'=>'','video'=>'']); ?></div>
        <script>
        jQuery(function($){
            $('#vsnn-list').sortable({
                cursor: 'grabbing',
                forcePlaceholderSize: true,
                handle: '.vsnn-drag',
                placeholder: 'vsnn-sort-placeholder',
                tolerance: 'pointer'
            });
            var f;
            $('#vsnn-add').click(function(e){
                e.preventDefault(); if(f){f.open();return;}
                f = wp.media({multiple:true});
                f.on('select',function(){ f.state().get('selection').map(function(a){
                    a=a.toJSON(); var t=$($('#vsnn-tmpl').html());
                    t.find('img').attr('src',a.url); t.find('.u').val(a.url); t.find('.i').val(a.id);
                    $('#vsnn-list').append(t);
                });});
                f.open();
            });
            $(document).on('click','.vsnn-rm',function(){ $(this).closest('li').remove(); });
        });
        </script>
        <?php
    }

    private function render_item_row($d){
        $p = $d['url']?:'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCI+PHJlY3QgZmlsbD0iI2VlZSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==';
        echo '<li class="vsnn-media-row">
            <span class="dashicons dashicons-move vsnn-drag" title="Drag to reorder"></span>
            <img src="'.$p.'" style="width:50px;height:50px;object-fit:cover;">
            <div style="flex:1"><input type="hidden" name="vsnn_i[id][]" class="i" value="'.$d['id'].'"><input type="hidden" name="vsnn_i[url][]" class="u" value="'.$d['url'].'">
            <input type="text" name="vsnn_i[cap][]" value="'.$d['caption'].'" placeholder="Caption" style="width:100%;margin-bottom:5px">
            <input type="text" name="vsnn_i[vid][]" value="'.$d['video'].'" placeholder="Video URL" style="width:100%"></div>
            <button type="button" class="button vsnn-rm" style="color:red;border-color:red">&times;</button>
        </li>';
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

    public function render_preview_box($post) {
        $items = get_post_meta( $post->ID, '_vsnn_items', true );
        if(empty($items)) { echo 'Please add media & save.'; return; }
        ?>
        <div class="vsnn-preview-wrapper" id="vsnn-prev-wrap">
            <div class="vsnn-prev-main">
                <div class="vsnn-preview-inner" id="vsnn-prev-inner">
                    <?php foreach($items as $k=>$v): $cls=trim(($k==0?'active ':'') . ($this->is_portrait_item($v)?'is-portrait':'')); ?>
                    <div class="vsnn-preview-item <?php echo esc_attr($cls); ?>">
                        <img src="<?php echo esc_url($v['url']); ?>">
                        <div class="vsnn-prev-overlay"></div>
                        <div class="vsnn-preview-caption animate__animated"><?php echo esc_html($v['caption']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="vsnn-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
                <button class="vsnn-next"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
            </div>
            
            <div class="vsnn-prev-thumbs" id="vsnn-prev-thumbs">
                <?php foreach($items as $k=>$v): $ac=$k==0?'active':''; ?>
                <img src="<?php echo $v['url']; ?>" class="<?php echo $ac; ?>">
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function save_meta_data( $post_id ) {
        if ( ! isset( $_POST['vsnn_nonce'] ) || ! wp_verify_nonce( $_POST['vsnn_nonce'], 'vsnn_save' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if(isset($_POST['vsnn_i'])){
            $d=[];
            $ii=$_POST['vsnn_i'];
            for($i=0;$i<count($ii['url']);$i++){
                if($ii['url'][$i]) $d[]=['id'=>$ii['id'][$i], 'url'=>$ii['url'][$i], 'caption'=>sanitize_text_field($ii['cap'][$i]), 'video'=>esc_url_raw($ii['vid'][$i])];
            }
            update_post_meta($post_id,'_vsnn_items',$d);
        }

        if(isset($_POST['vsnn_set'])){
            $s = $_POST['vsnn_set'];
            $new = array(
                'effect' => sanitize_key($s['effect']), 'text_anim' => sanitize_text_field($s['text_anim']),
                'arrow_style' => sanitize_key($s['arrow_style']), 'thumb_pos' => sanitize_key($s['thumb_pos']),
                'radius' => intval($s['radius']), 'shadow' => sanitize_key($s['shadow']),
                'overlay' => sanitize_key($s['overlay']), 'interval' => intval($s['interval']),
                // REMOVED INDICATORS
                'thumbnails' => isset($s['thumbnails'])?'on':'off',
                'autoplay' => isset($s['autoplay'])?'on':'off', 'lazyload' => isset($s['lazyload'])?'on':'off',
                'random' => isset($s['random'])?'on':'off'
            );
            update_post_meta($post_id,'_vsnn_settings',$new);
        }
    }

    public function render_docs_modal() {
        global $post_type;
        // FIX: Check current screen to prevent leak on list table
        $screen = get_current_screen();
        if ( ! $screen || 'lw_slider' !== $screen->post_type || 'post' !== $screen->base ) return;
        
        $file = plugin_dir_path( __FILE__ ) . 'partials/docs-display.php';

        if ( file_exists( $file ) ) {
            include $file;
        } else {
            echo '<div id="vsnn-docs-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:99999"><div style="background:#fff;padding:20px;margin:10% auto;width:50%;">Documentation file not found.</div></div>';
        }
    }
    
    public function add_columns($c){ $c['shortcode'] = 'Shortcode'; return $c; }
    public function render_columns($c, $id){ if('shortcode'==$c) echo '[lw_slider id="'.$id.'"]'; }
}
?>