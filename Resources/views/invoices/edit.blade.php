<div id="pointofsale" style="margin-bottom: 16px;" class="text-center">
    <a href="#" class="btn btn-info" data-toggle="modal" data-target="#pointofsale_scanner"><i style="display:block" class="fa fa-barcode fa-3x"></i> Begin Scanning</a>
</div>

<!-- Modal -->
<div class="modal fade" id="pointofsale_scanner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Point Of Sale Scanning</h4>
            </div>
            <div class="modal-body">
                {!! Former::text('pointofsale_barcode')->label('')->addClass('form-control text-center')->placeholder('Barcode') !!}

                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // move the button to the top of the page
    // $("#pointofsale").insertBefore("form[action='{{ url("invoices") }}']");
    $("#pointofsale").insertBefore('.main-form');
    
    // autofocus the barcode field
    $('#pointofsale_scanner').on('shown.bs.modal', function (e) {
        focusBarcodeField();
    });

    // focus the barcode field
    function focusBarcodeField() {
        $("#pointofsale_barcode").focus();
    }

    // add listener for enter key
    $('#pointofsale_barcode').keyup(function() {
        if (event.keyCode === 13) {
            event.preventDefault();
            findProductByBarcode();
        }
    });

    // query for the barcode
    function findProductByBarcode() {
        var barcode = $("#pointofsale_barcode").val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('productsByBarcode') }}',
            data: {
                'barcode': barcode
            },
            error: function() {
                $('#pointofsale_scan_info').html('<p>An error has occurred</p>');
            },
            success: function(data) {
                var responseData = data.data[0];

                if(responseData) {
                    var notes = responseData.notes;
                    var productKey = responseData.product_key;
                    var cost = responseData.cost;
                
                    var message = '<div class="pointofsale_scan_info text-center alert alert-success">';
                        message += 'Added ' + barcode + '</div>';

                    if(invoice.invoice_items_without_tasks().length == 1) {
                        // the list is empty, replace the first row and add a new one
                        itemModel = new ItemModel();
                        itemModel.product_key(productKey);
                        itemModel.notes(notes);
                        itemModel.cost(cost);
                        itemModel.qty(1);

                        // get the empty entry row itemModel
                        i = ko.utils.arrayFirst(invoice.invoice_items_without_tasks(), function(im) {
                            var retval = im.product_key() == "" && im.notes() == "" && im.cost() == 0 && im.qty() == 0;
                            return retval;
                        });

                        invoice.invoice_items_without_tasks.replace(i, itemModel);
                        invoice.addItem(false);
                    } else {
                        i = ko.utils.arrayFirst(invoice.invoice_items_without_tasks(), function(im) {
                            var retval = im.product_key() == productKey && im.notes() == notes && im.cost() == cost;
                            return retval;
                        });

                        if(i) {
                            // found a row with the same data, just increment the quantity
                            var num = i.qty();
                            i.qty(++num);
                        } else {
                            itemModel = invoice.addItem(false);
                            itemModel.product_key(productKey);
                            itemModel.notes(notes);
                            itemModel.cost(cost);
                            itemModel.qty(1);
                            invoice.addItem(false);
                        }
                    }

                    invoice.invoice_items_without_tasks.valueHasMutated();
                } else {
                    var message = '<div class="pointofsale_scan_info text-center alert alert-danger" style="margin-top: 6px;">';
                        message += barcode + ' not found!</div>';
                }
                                
                $('#pointofsale_scanner .modal-body')
                     .append(message);
                
                $('.pointofsale_scan_info')
                     .delay(3000).slideUp(300);

                $('#pointofsale_barcode').val('');
                focusBarcodeField();
            },
            type: 'GET'
        });
    }
</script>