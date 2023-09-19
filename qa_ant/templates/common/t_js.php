<script src="vendors/jquery/jquery-3.6.0.min.js" ></script>
    
		<script src="vendors/bootstrap/js/bootstrap.bundle.min.js" ></script>
    <script src="vendors/bootstrap/js/bs-custom-file-input.min.js" type="text/javascript"></script>  
    <script src="vendors/bootstrap/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="vendors/bootstrap/js/bootstrap4-toggle.min.js" type="text/javascript"></script>
    
    <script src="vendors/datatable/datatables.min.js" type="text/javascript"></script>
    <script src="vendors/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="vendors/datatable/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="vendors/datatable/buttons.html5.min.js" type="text/javascript"></script>
    <script src="vendors/datatable/buttons.print.min.js" type="text/javascript"></script>
    <script src="vendors/datatable/jszip.min.js" type="text/javascript"></script>
    
    <script src="vendors/flatpickr/flatpickr.min.js" type="text/javascript"></script>
    <script src="vendors/flatpickr/plugins/monthSelect/index.js" type="text/javascript"></script>
    
    <script src="vendors/select2/js/select2.min.js" type="text/javascript"></script>
    
    <script src="vendors/ezview/draggable.js" type="text/javascript"></script>
    <script src="vendors/ezview/EZView.js" type="text/javascript"></script>
    
    <script src="vendors/apexchart/apexcharts.min.js" type="text/javascript"></script>
    <script>
      $.fn.selectpicker.Constructor.BootstrapVersion = '4';
      
      $('#accordionMenu').on('shown.bs.collapse', function (e) {
        saveActiveAccordionPanel('accordion-activePanel', e);
      });

      $('#accordionMenu').on('hide.bs.collapse', function (e) {
        saveActiveAccordionPanel('accordion-activePanel', e);
      });

      function restoreAccordionPanel(storageKey, accordionId) {
        var activeItem = localStorage.getItem(storageKey);
        if (activeItem) {
          //remove default collapse settings
          $(accordionId + " .collapse").removeClass('show');

          //show the account_last visible group
          $("#" + activeItem).addClass("show");
        }
      }

      function saveActiveAccordionPanel(storageKey, e) {
        //console.log(e.type);
        if (e.type == "shown") {
          localStorage.setItem(storageKey, e.target.id);
        } else {
          localStorage.removeItem(storageKey);
        }

      }

      $(document).ready(function () {
        restoreAccordionPanel('accordion-activePanel', '#accordionMenu');
      });
      
      $(".select2").select2({
        theme: 'bootstrap4',
      });
    </script>