<div id="pointofsale" style="margin-bottom: 16px;" class="text-center">
    <a href="#" class="btn btn-info" data-toggle="modal" data-target="#pointofsale_scanner"><i style="display:block"
            class="fa fa-barcode fa-3x"></i> Begin Scanning</a>
</div>

<!-- Scanner modal -->
<div class="modal fade" id="pointofsale_scanner" tabindex="-1" role="dialog" aria-labelledby="Scanner">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="scannerModalLabel">Point Of Sale Scanning</h4>
            </div>
            <div class="modal-body">
                {!! Former::text('pointofsale_barcode')->label('')->addClass('form-control
                text-center')->placeholder('Barcode') !!}
            </div>
        </div>
    </div>
</div>

<!-- Serial input modal -->
<div class="modal fade" id="pointofsale_serial_scanner" tabindex="-1" role="dialog" aria-labelledby="Serial Number">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="serialModalLabel">Serial Number</h4>
            </div>
            <div class="modal-body">
                {!! Former::text('pointofsale_serial')->label('')->addClass('form-control
                text-center')->placeholder('Serial Number') !!}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // move the button to the top of the page
    $("#pointofsale").insertBefore('.main-form');
    
    // autofocus the barcode field
    $('#pointofsale_scanner').on('shown.bs.modal', function (e) {
        $("#pointofsale_barcode").focus();
    });

    // add listener for enter key on the barcode field
    $('#pointofsale_barcode').keyup(function() {
        if (event.keyCode === 13) {
            event.preventDefault();
            findProductByBarcode();
        }
    });

    // autofocus the serial field
    $('#pointofsale_serial_scanner').on('shown.bs.modal', function (e) {
        $("#pointofsale_serial").focus();
    });


    function showSerialEntryForm(data) {
        $('#pointofsale_scanner').modal('hide');

        $('#pointofsale_serial').val('');
        $('#pointofsale_serial').data("productData", data);
        $("#pointofsale_serial_scanner").modal('show');
    }

    // add listener for enter key
    $('#pointofsale_serial').keyup(function() {
        if (event.keyCode === 13) {
            event.preventDefault();

            var data = $('#pointofsale_serial').data("productData");

            data.serial_number = $("#pointofsale_serial").val();
            addScannedItem(data);
            $("#pointofsale_serial_scanner").modal('hide');
            $('#pointofsale_scanner').modal('show');
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
                    if(responseData.manufacturer_product_details.serialized == 1) {
                        showSerialEntryForm(responseData);
                    } else {
                        addScannedItem(responseData);
                    }

                    var message = '<div class="pointofsale_scan_info text-center alert alert-success">';
                    message += 'Added ' + barcode + '</div>';
                } else {
                    var message = '<div class="pointofsale_scan_info text-center alert alert-danger" style="margin-top: 6px;">';
                        message += barcode + ' not found!</div>';
                }
                                
                $('#pointofsale_scanner .modal-body')
                     .append(message);
                
                $('.pointofsale_scan_info')
                     .delay(3000).slideUp(300);

                $('#pointofsale_barcode').val('');
            },
            type: 'GET'
        });
    }

    function addScannedItem(data) {
        console.log(data);
        var notes = data.notes;

        if(data.serial_number) {
            notes += "\r\n\r\n###SN: " + data.serial_number;
        }

        var productKey = data.product_key;
        var cost = roundSignificant(data.cost, true);
        var serialized = data.manufacturer_product_details.serialized;
        var customValue1 = data.custom_value1;
        var customValue2 = data.custom_value2;
        var taxName1 = data.tax_name1;
        var taxName2 = data.tax_name2;
        var taxRate1 = data.tax_rate1;
        var taxRate2 = data.tax_rate2;

        existingItem = ko.utils.arrayFirst(invoice.invoice_items_without_tasks(), function(im) {
            return (im.product_key() == productKey && im.notes() == notes && im.cost() == cost) && im.tax_rate1() == taxRate1 && im.tax_name1() == taxName1 && im.tax_rate2() == taxRate2 && im.tax_name2() == taxName2 && im.custom_value1() == customValue1 && im.custom_value2() == customValue2;
        });

        if(existingItem && !data.serial_number) {
            qty = existingItem.qty();
            existingItem.qty(++qty);
        } else {
            replacementItem = new ItemModel();
            replacementItem.product_key(productKey);
            replacementItem.notes(notes);
            replacementItem.cost(cost);
            replacementItem.custom_value1(customValue1);
            replacementItem.custom_value2(customValue2);
            replacementItem.qty(1);
            var currentIndex = -1;
            
            // get the empty entry row itemModel
            entryItem = ko.utils.arrayFirst(invoice.invoice_items_without_tasks(), function(im, index) {
                currentIndex = index;
                return im.product_key() == "" && im.notes() == "" && im.cost() == 0 && im.qty() == 0;
            });

            if(entryItem) {
                // found the row
                invoice.invoice_items_without_tasks.replace(entryItem, replacementItem);
                invoice.addItem(false);
            } else {
                // couldn't find the empty row so add a new one, update it, and then add another new
                // row for further items
                itemModel = invoice.addItem(false);
                invoice.invoice_items_without_tasks.replace(itemModel, replacementItem);
                invoice.addItem(false);
            }

            $('[name="invoice_items[' + currentIndex + '][tax_name1]"]').prev('select').val('0 ' + taxRate1 + ' ' + taxName1).trigger('change');
            $('[name="invoice_items[' + currentIndex + '][tax_name2]"]').prev('div').find('.tax-select').val('0 ' + taxRate2 + ' ' + taxName2).trigger('change');
        }

        invoice.invoice_items_without_tasks.valueHasMutated();
        refreshPDF(true);
    }
</script>