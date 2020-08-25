<div class='wrap'>
    <div id='real3dflipbook-admin' style="display:none;">
        <a href="admin.php?page=real3d_flipbook_admin" class="back-to-list-link">&larr; 
            <?php _e('Back to flipbooks list', 'flipbook'); ?>
        </a>
        
        <form method="post" enctype="multipart/form-data" action="admin.php?page=real3d_flipbook_admin&action=save_settings&bookId=<?php echo($current_id);?>">
        <h1><span id="edit-flipbook-text"></span>
<input type="submit" name="submit" id="submit" class="alignright button save-button button-primary" value="Save Flipbook">
                <a href="#TB_inline?width=600&height=550&inlineId=flipbook-preview-container" class="alignright thickbox flipbook-preview button save-button button-secondary">Preview Flipbook</a>

        </h1>
                <!-- <span class="unsaved" sytle="display:none;">Unsaved  </span> -->
 
            <div class="tabs">




            <h2 class="nav-tab-wrapper">
                <a class="nav-tab" href="#tab-pages">Pages</a>
                <a class="nav-tab" href="#tab-general">General</a>
                <a class="nav-tab" href="#tab-lightbox">Lightbox</a>
                <a class="nav-tab" href="#tab-menu">Menu</a>
                <a class="nav-tab" href="#tab-webgl">WebGL</a>
                <a class="nav-tab" href="#tab-mobile">Mobile</a>
                <a class="nav-tab" href="#tab-ui">Layout & Design</a>
            </h2>
            <div class="sections">
    
                <div id="tab-pages" style="display:none;">
                <p>

                <span><a class="add-jpg-pages-button button-primary" href="#">Add jpg pages</a></span>

                <span><a class="add-pdf-pages-button button-primary" href="#">Add pages from PDF</a></span>

                <span><a class="delete-pages-button button-secondary" href="#">Delete all pages</a></span>
                </p>


                <table  style="" class="form-table" id="flipbook-pdf-options">
                                <tbody></tbody>
                            </table>


                    <div >

                        <h2 style="display:none;">Select flipbook type</h2>
                        <p style="display:none;">
                            <label>
                                <input id="flipbook-type-pdf" name="type" type="radio" value="pdf"> PDF     
                            </label>
                        </p>
                        <p style="display:none;">
                            <label>
                                <input id="flipbook-type-jpg" name="type" type="radio" value="jpg"> JPG     
                            </label>
                        </p>
                        <div  style="display:none;" class="clear" ></div>
                        <div id="select-pdf">
                             <h2></h2>
                            <table  style="display:none;" class="form-table" id="flipbook-pdf-options">
                                <tbody></tbody>
                            </table> 
                            <h3 style="display:none;">Pages </h3>











                            <div class="attachments-browser">


                            <div class="media-sidebar">


    <div tabindex="0" data-id="-1" class="attachment-details">



        <h2>
            Edit page
        </h2>



        <!--<div class="attachment-info">
            <div class="thumbnail thumbnail-image">
                
                    <img src="" draggable="false" alt="">
                
            </div>
            <div class="details">

                  <button type="button" class="button-link delete-attachment">Delete</button>
                                    

            </div>

        </div>-->


                
                    <img id="edit-page-img" src="" draggable="false" alt="">

        <div class="details">
<button type="button" class="button-secondary replace-page">Replace</button>
                  <button type="button" class="button-link delete-page">Delete</button>
                                    

            </div>

                <label class="setting" data-setting="title">
            <span class="name">Title</span>
            <input id="edit-page-title" type="text" value="">
        </label>    

      <!--  <label class="setting" data-setting="src">
            <span class="name">Image</span>
            <input id="edit-page-src" type="text" value="">
        </label>    

        <label class="setting" data-setting="thumb">
            <span class="name">Thumbnail</span>
            <input id="edit-page-thumb" type="text" value="">
        </label> 
-->
        <label class="setting" data-setting="html-content">
            <span class="name">HTML content</span>
            <textarea id="edit-page-html-content"></textarea>
        </label>
    </div>

</div>


    <ul id="pages-container" tabindex="-1" class="attachments ui-sortable">


        
        
</ul>

</div>




























                        </div>

                    </div>
                    <div id="convert-pdf" style="display:none;">PDF
                        <a href="#" class="add-new-h2 select-pdf-button">Select</a>
                    </div>
                </div>


                <div id="tab-general" style="display:none;">
                    <table class="form-table" id="flipbook-general-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-normal"  style="display:none;">
                    <table class="form-table" id="flipbook-normal-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-mobile"  style="display:none;">
                    <table class="form-table" id="flipbook-mobile-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-lightbox"  style="display:none;">
                    <table class="form-table" id="flipbook-lightbox-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-webgl"  style="display:none;">
                    <table class="form-table" id="flipbook-webgl-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-menu"  style="display:none;">
                    <h3 class="hndle">Current page</h3>
                    <table class="form-table" id="flipbook-currentPage-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button autoplay</h3>
                    <table class="form-table" id="flipbook-btnAutoplay-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button next page</h3>
                    <table class="form-table" id="flipbook-btnNext-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button last page</h3>
                    <table class="form-table" id="flipbook-btnLast-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button previous page</h3>
                    <table class="form-table" id="flipbook-btnPrev-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button first page</h3>
                    <table class="form-table" id="flipbook-btnFirst-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button zoom in</h3>
                    <table class="form-table" id="flipbook-btnZoomIn-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button zoom out</h3>
                    <table class="form-table" id="flipbook-btnZoomOut-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button table of content</h3>
                    <table class="form-table" id="flipbook-btnToc-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button thumbnails</h3>
                    <table class="form-table" id="flipbook-btnThumbs-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button share</h3>
                    <table class="form-table" id="flipbook-btnShare-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button download pages</h3>
                    <table class="form-table" id="flipbook-btnDownloadPages-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button download PDF</h3>
                    <table class="form-table" id="flipbook-btnDownloadPdf-options">
                        <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button sound</h3>
                    <table class="form-table" id="flipbook-btnSound-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button expand</h3>
                    <table class="form-table" id="flipbook-btnExpand-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button expand lightbox</h3>
                    <table class="form-table" id="flipbook-btnExpandLightbox-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Button print</h3>
                    <table class="form-table" id="flipbook-btnPrint-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Share on Google plus</h3>
                    <table class="form-table" id="flipbook-google_plus-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Share on Twitter</h3>
                    <table class="form-table" id="flipbook-twitter-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Share on Facebook</h3>
                    <table class="form-table" id="flipbook-facebook-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Share on pinterest</h3>
                    <table class="form-table" id="flipbook-pinterest-options">
                         <tbody></tbody>
                    </table>
                    <h3 class="hndle">Share by email</h3>
                    <table class="form-table" id="flipbook-email-options">
                        <tbody></tbody>
                    </table>
                </div>
                <div id="tab-ui"  style="display:none;">

                    <table class="form-table" id="flipbook-ui-options">
                        <tbody></tbody>
                    </table>

                    <h3>Background</h3>
                    <table class="form-table" id="flipbook-background-options">
                        <tbody></tbody>
                    </table>
                    
                    <h3>Menu bar</h3>
                    <table class="form-table" id="flipbook-menu-bar-options">
                        <tbody></tbody>
                    </table>
                    <h3>Menu buttons</h3>
                    <table class="form-table" id="flipbook-menu-buttons-options">
                        <tbody></tbody>
                    </table>
                    <h3>Side navigation buttons</h3>
                    <table class="form-table" id="flipbook-side-buttons-options">
                        <tbody></tbody>
                    </table>
                    <h3>Current page display</h3>
                    <table class="form-table" id="flipbook-current-page-options">
                        <tbody></tbody>
                    </table>
                </div>

                </div>

                </div>
                <div id="flipbook-preview-container" style="display:none;">
                    <div id="flipbook-preview-container-inner" style="position:relative;height:100%"></div>
                </div>


            <p class="submit">
                <!-- <span class="unsaved" sytle="display:none;">Unsaved  </span> -->
                <input type="submit" name="submit" id="submit" class="alignright button save-button button-primary" value="Save Flipbook">
                <a href="#TB_inline?width=800&height=400&inlineId=flipbook-preview-container" class="alignright thickbox flipbook-preview button save-button button-secondary">Preview Flipbook</a>
            </p>
        </form>
    </div>
</div>
<?php 
wp_enqueue_media();
add_thickbox(); 
wp_enqueue_script( "real3d_flipbook", plugins_url(). "/real3d-flipbook/js/flipbook.min.js", array( 'jquery'),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'flipbook_style', plugins_url(). "/real3d-flipbook/css/flipbook.style.css" , array(),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'font_awesome', plugins_url(). "/real3d-flipbook/css/font-awesome.css" , array(),REAL3D_FLIPBOOK_VERSION); 
//wp_enqueue_script( "compatibilityjs", plugins_url(). "/real3d-flipbook/js/compatibility.min.js", array(),REAL3D_FLIPBOOK_VERSION); 



/*wp_enqueue_script( "pdfjs", plugins_url(). "/real3d-flipbook/js/pdf.min.js", array(),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_script( "pdfworkerjs", plugins_url(). "/real3d-flipbook/js/pdf.worker.min.js", array(),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'wp-color-picker' ); 
wp_enqueue_script( "real3d_flipbook_admin", plugins_url(). "/real3d-flipbook/js/edit_flipbook.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'jquery-ui-tabs', 'pdfjs', 'wp-color-picker' ),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'real3d_flipbook_admin_css', plugins_url(). "/real3d-flipbook/css/flipbook-admin.css",array(), REAL3D_FLIPBOOK_VERSION ); 
wp_enqueue_style( 'jquery-ui-style', plugins_url(). "/real3d-flipbook/css/jquery-ui.css",array(), REAL3D_FLIPBOOK_VERSION ); 
wp_localize_script( 'real3d_flipbook_admin', 'flipbooks', json_encode($flipbooks) );*/






wp_enqueue_script( "pdfjs", plugins_url(). "/real3d-flipbook/js/pdf.min.js", array(),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_script( "pdfworkerjs", plugins_url(). "/real3d-flipbook/js/pdf.worker.min.js", array(),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'wp-color-picker' ); 
wp_enqueue_script( "real3d_flipbook_admin", plugins_url(). "/real3d-flipbook/js/edit_flipbook.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'pdfjs', 'wp-color-picker' ),REAL3D_FLIPBOOK_VERSION); 
wp_enqueue_style( 'real3d_flipbook_admin_css', plugins_url(). "/real3d-flipbook/css/flipbook-admin.css",array(), REAL3D_FLIPBOOK_VERSION ); 
//wp_enqueue_style( 'jquery-ui-style', plugins_url(). "/real3d-flipbook/css/jquery-ui.css",array(), REAL3D_FLIPBOOK_VERSION ); 
wp_localize_script( 'real3d_flipbook_admin', 'flipbooks', json_encode($flipbooks) );