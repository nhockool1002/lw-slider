(function($) {
    // 1. Khởi tạo nút cho TinyMCE (Classic Editor)
    if (typeof tinymce !== 'undefined') {
        tinymce.create('tinymce.plugins.LWSlider_Plugin', {
            init: function(ed, url) {
                ed.addButton('lw_slider_btn', {
                    title: 'Insert LWSlider',
                    icon: 'lw-slider-toolbar-icon', // Icon class đã define trong PHP
                    onclick: function() {
                        openLWSliderModal(ed); // Gọi hàm mở modal
                    }
                });
            },
            createControl: function(n, cm) { return null; },
        });
        tinymce.PluginManager.add('lw_slider_button', tinymce.plugins.LWSlider_Plugin);
    }

    // 2. Khởi tạo nút cho Gutenberg (Block Editor)
    if (typeof wp !== 'undefined' && wp.richText && wp.element && wp.editor) {
        const { registerFormatType } = wp.richText;
        const { RichTextToolbarButton } = wp.editor;
        const { createElement } = wp.element;

        const LWSliderButton = function( props ) {
            return createElement( RichTextToolbarButton, {
                icon: 'images-alt2', // Dashicon name
                title: 'Insert LWSlider',
                onClick: function() {
                    openLWSliderModal({ isGutenberg: true }); 
                },
            } );
        }

        registerFormatType( 'lw-slider/insert-button', {
            title: 'Insert LWSlider',
            tagName: 'span',
            className: 'lw-slider-placeholder',
            edit: LWSliderButton,
        } );
    }

    // 3. Hàm chính: Mở Modal & Xử lý Logic
    function openLWSliderModal(editorInstance) {
        // A. Tạo HTML Modal nếu chưa có trong DOM
        if ($('#lw-slider-selector-modal').length === 0) {
            $('body').append(`
                <div id="lw-slider-selector-modal" style="display:none;">
                    <div class="lw-modal-overlay"></div>
                    <div class="lw-modal-content">
                        <div class="lw-modal-header">
                            <h3>Select Sliders</h3>
                            <span class="lw-close">&times;</span>
                        </div>
                        <div class="lw-modal-body">
                            <div class="lw-loading">
                                <div class="lw-spinner"></div>
                                <p>Loading sliders...</p>
                            </div>
                            <ul id="lw-slider-list"></ul>
                        </div>
                        <div class="lw-modal-footer">
                            <button type="button" id="lw-insert-btn" disabled>Insert Selected</button>
                        </div>
                    </div>
                </div>
                <style>
                    /* Modal Container */
                    #lw-slider-selector-modal { position: fixed; z-index: 100000; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; }
                    .lw-modal-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(2px); }
                    .lw-modal-content { position: relative; background: #fff; width: 500px; max-width: 90%; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; z-index: 100001; display: flex; flex-direction: column; max-height: 80vh; }
                    
                    /* Header */
                    .lw-modal-header { background: #f0f0f1; padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
                    .lw-modal-header h3 { margin: 0; font-size: 18px; font-weight: 600; color: #23282d; }
                    .lw-close { cursor: pointer; font-size: 24px; color: #666; line-height: 1; }
                    .lw-close:hover { color: #d63638; }

                    /* Body */
                    .lw-modal-body { padding: 0; overflow-y: auto; flex: 1; min-height: 200px; position: relative; }
                    
                    /* Loading Spinner */
                    .lw-loading { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #fff; z-index: 10; }
                    .lw-spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #2271b1; border-radius: 50%; animation: lw-spin 1s linear infinite; margin-bottom: 10px; }
                    @keyframes lw-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

                    /* List Items */
                    #lw-slider-list { list-style: none; margin: 0; padding: 0; }
                    #lw-slider-list li { padding: 12px 20px; border-bottom: 1px solid #eee; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: space-between; }
                    #lw-slider-list li:hover { background: #f6f7f7; }
                    #lw-slider-list li.selected { background: #f0f6fc; border-left: 4px solid #2271b1; }
                    #lw-slider-list li:last-child { border-bottom: none; }
                    
                    .lw-item-info { display: flex; align-items: center; gap: 10px; }
                    .lw-checkbox { width: 18px; height: 18px; border: 1px solid #8c8f94; border-radius: 3px; display: inline-block; position: relative; background: #fff; }
                    li.selected .lw-checkbox { background: #2271b1; border-color: #2271b1; }
                    li.selected .lw-checkbox::after { content: ''; position: absolute; left: 5px; top: 1px; width: 5px; height: 10px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); }

                    /* Footer & Button */
                    .lw-modal-footer { padding: 15px 20px; background: #f0f0f1; border-top: 1px solid #ddd; text-align: right; }
                    #lw-insert-btn { 
                        background: linear-gradient(135deg, #28a745, #218838); 
                        border: none; color: white; padding: 10px 25px; 
                        font-size: 14px; font-weight: 600; border-radius: 4px; 
                        cursor: pointer; transition: opacity 0.2s; 
                    }
                    #lw-insert-btn:hover { opacity: 0.9; }
                    #lw-insert-btn:disabled { background: #ccc; cursor: not-allowed; opacity: 0.7; }
                </style>
            `);

            // Xử lý sự kiện đóng Modal
            $(document).on('click', '.lw-close, .lw-modal-overlay', function() {
                $('#lw-slider-selector-modal').fadeOut(200);
            });

            // Xử lý sự kiện chọn Slider (Multi-select)
            $(document).on('click', '#lw-slider-list li', function() {
                $(this).toggleClass('selected'); // Toggle class selected
                updateInsertButtonState();
            });

            // Xử lý sự kiện nhấn nút Insert
            $(document).on('click', '#lw-insert-btn', function() {
                var contentToInsert = '';
                
                // Lặp qua các item đã chọn
                $('#lw-slider-list li.selected').each(function() {
                    var id = $(this).data('id');
                    contentToInsert += '[lw_slider id="' + id + '"] '; // Thêm khoảng trắng để tách
                });

                if (contentToInsert) {
                    if (editorInstance && !editorInstance.isGutenberg) {
                        // TinyMCE
                        editorInstance.insertContent(contentToInsert);
                    } else {
                        // Gutenberg Block
                        wp.data.dispatch('core/block-editor').insertBlocks(
                            wp.blocks.createBlock('core/shortcode', { text: contentToInsert.trim() })
                        );
                    }
                }
                $('#lw-slider-selector-modal').fadeOut(200);
            });
        }

        // B. Reset trạng thái Modal mỗi lần mở
        var modal = $('#lw-slider-selector-modal');
        var list = $('#lw-slider-list');
        var loading = $('.lw-loading');
        
        modal.fadeIn(200); // Hiện modal
        loading.show(); // Hiện loading
        list.hide().empty(); // Ẩn và xóa list cũ
        $('#lw-insert-btn').prop('disabled', true).text('Insert Selected'); // Reset nút

        // C. Gọi AJAX lấy danh sách
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: { action: 'lw_get_sliders_list' },
            success: function(response) {
                loading.hide(); // Tắt loading
                list.show(); // Hiện list

                if (response.success && response.data.length > 0) {
                    var html = '';
                    response.data.forEach(function(slider) {
                        html += `<li data-id="${slider.ID}">
                                    <div class="lw-item-info">
                                        <span class="lw-checkbox"></span>
                                        <strong>${slider.post_title}</strong>
                                    </div>
                                    <span class="dashicons dashicons-slides"></span>
                                 </li>`;
                    });
                    list.html(html);
                } else {
                    list.html('<div style="padding:20px;text-align:center;">No sliders found. <a href="post-new.php?post_type=lw_slider" target="_blank">Create one now</a></div>');
                }
            },
            error: function() {
                loading.hide();
                list.html('<p style="padding:20px;color:red;text-align:center;">Failed to load sliders. Please try again.</p>').show();
            }
        });
    }

    // Helper: Cập nhật trạng thái nút Insert
    function updateInsertButtonState() {
        var count = $('#lw-slider-list li.selected').length;
        var btn = $('#lw-insert-btn');
        
        if (count > 0) {
            btn.prop('disabled', false).text('Insert (' + count + ')');
        } else {
            btn.prop('disabled', true).text('Insert Selected');
        }
    }

})(jQuery);