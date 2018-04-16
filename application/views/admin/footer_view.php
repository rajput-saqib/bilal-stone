        </div><!--/content-->
    </div>

    <hr>

    <div class="modal hide fade" id="myModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Settings</h3>
        </div>
        <div class="modal-body">
            <p>Here settings can be configured...</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Save Changes</a>
        </div>
    </div>

    <footer>
        <p class="pull-left">&copy; <a href="javascript:void()">Saqib Rajput</a> 2015</p>
    </footer>

    </div><!--/.fluid-container-->


    <!-- jQuery -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery-1.7.2.min.js"></script>

    <!-- custom javascriot -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/ajax_viewers.js"></script>
    <script src="<?= ADMIN_ASSETS_PATH ?>js/custom.js"></script>
    <!-- custom javascriot -->

    <!-- jQuery UI -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery-ui-1.8.21.custom.min.js"></script>
    <!-- transition / effect library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-transition.js"></script>
    <!-- alert enhancer library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-alert.js"></script>
    <!-- modal / dialog library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-modal.js"></script>
    <!-- custom dropdown library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-dropdown.js"></script>
    <!-- scrolspy library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-scrollspy.js"></script>
    <!-- library for creating tabs -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-tab.js"></script>
    <!-- library for advanced tooltip -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-tooltip.js"></script>
    <!-- popover effect library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-popover.js"></script>
    <!-- button enhancer library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-button.js"></script>
    <!-- accordion library (optional, not used in demo) -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-collapse.js"></script>
    <!-- carousel slideshow library (optional, not used in demo) -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-carousel.js"></script>
    <!-- autocomplete library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-typeahead.js"></script>
    <!-- tour library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/bootstrap-tour.js"></script>
    <!-- library for cookie management -->
    <!--<script src="<? /*=ADMIN_ASSETS_PATH*/ ?>js/jquery.cookie.js"></script>-->
    <!-- calander plugin -->
    <script src='<?= ADMIN_ASSETS_PATH ?>js/fullcalendar.min.js'></script>
    <!-- data table plugin -->
    <script src='<?= ADMIN_ASSETS_PATH ?>js/jquery.dataTables.min.js'></script>

    <!-- chart libraries start -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/excanvas.js"></script>
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.flot.min.js"></script>
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.flot.pie.min.js"></script>
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.flot.stack.js"></script>
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.flot.resize.min.js"></script>
    <!-- chart libraries end -->

    <!-- select or dropdown enhancer -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.chosen.min.js"></script>
    <!-- checkbox, radio, and file input styler -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.uniform.min.js"></script>
    <!-- plugin for gallery image view -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.colorbox.min.js"></script>
    <!-- rich text editor library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.cleditor.min.js"></script>
    <!-- notification plugin -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.noty.js"></script>
    <!-- file manager library -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.elfinder.min.js"></script>
    <!-- star rating plugin -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.raty.min.js"></script>
    <!-- for iOS style toggle switch -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.iphone.toggle.js"></script>
    <!-- autogrowing textarea plugin -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.autogrow-textarea.js"></script>
    <!-- multiple file upload plugin -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.uploadify-3.1.min.js"></script>
    <!-- history.js for cross-browser state change on ajax -->
    <script src="<?= ADMIN_ASSETS_PATH ?>js/jquery.history.js"></script>
    <!-- application script for Charisma demo -->
        <?
            $param = $obj->getUrlValue(4);

            if(false)//empty($param)
            {
                ?><script src="<?= ADMIN_ASSETS_PATH ?>js/charisma.js"></script><?
            }
        ?>



    </body>
    </html>


        <script>
            $(document).ready(function(){
                $('.datepicker').datepicker();

                $('.chosen').chosen();
                $('.chzn-container').css("width", "200px").css("font-weight", "normal");
            });
        </script>
        