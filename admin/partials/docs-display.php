<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<div id="vsnn-docs-modal">
    <div id="vsnn-docs-content">
        <div class="vsnn-docs-header">
            <h2>LWSlider Documentation (v<?php echo defined('VSNN_2612_VERSION') ? VSNN_2612_VERSION : '1.0.0'; ?>)</h2>
            <span class="vsnn-docs-close" onclick="document.getElementById('vsnn-docs-modal').style.display='none'">&times;</span>
        </div>
        <div class="vsnn-docs-body">
            <p>Welcome to <strong>LWSlider</strong> - A lightweight, flexible, and powerful slider plugin for WordPress.</p>
            
            <h3>1. Quick Start</h3>
            <ul>
                <li>Upload images via the <strong>Media</strong> section.</li>
                <li>Rearrange images by drag & drop.</li>
                <li>Click <strong>Publish</strong> or <strong>Update</strong> to save your slider.</li>
                <li>Copy the <code>[lw_slider id="..."]</code> shortcode and paste it into any post, page, or widget.</li>
            </ul>

            <h3>2. Configuration Options</h3>
            <ul>
                <li><strong>Transition Effect:</strong> Choose from 6 effects: Slide, Fade, Vertical, Zoom, Flip, Blur. Note: 3D effects (Zoom/Flip) work best with standard aspect ratios.</li>
                <li><strong>Text Animation:</strong> Select entrance animations for your captions (e.g., Fade In Up, Bounce In). Powered by Animate.css.</li>
                <li><strong>Arrow Style:</strong> Customize navigation arrows (Box, Circle, Dark Mode, or Default).</li>
                <li><strong>Thumbnails Position:</strong>
                    <ul>
                        <li><strong>Bottom:</strong> Thumbnails overlay at the bottom of the slider.</li>
                        <li><strong>Left/Right:</strong> Creates a sidebar layout with scrollable thumbnails.</li>
                    </ul>
                </li>
                <li><strong>Overlay Color:</strong> Adds a layer over images to make text more readable (Dark 30%, 50%, or Gradient).</li>
                <li><strong>Image Filter:</strong> Click an image in the Media section to open the filter modal. Each image can use its own preset, with 730 options grouped into collapsible filter categories, including Intervention Image, Instagraph-style, and Grafika-inspired sets.</li>
            </ul>

            <h3>3. Tips & Troubleshooting</h3>
            <ul>
                <li><strong>Preview Button:</strong> Use the green "Apply Preview" button to see your changes instantly without saving.</li>
                <li><strong>Full Width:</strong> Images are set to <code>object-fit: cover</code> to fill the container. Ensure high-quality images for best results.</li>
                <li>If the slider doesn't appear on the frontend, check if your theme footer <code>wp_footer()</code> is present.</li>
            </ul>
            
            <p style="margin-top:20px; font-size:12px; color:#888;">&copy; 2023 LWSlider Plugin. All rights reserved.</p>
        </div>
    </div>
</div>