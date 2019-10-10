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

                <div id="info"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // move the button to the top of the page
    $("#pointofsale").insertBefore("form[action='{{ url("invoices") }}']");

    // autofocus the barcode field
    $('#pointofsale_scanner').on('shown.bs.modal', function (e) {
        $("#pointofsale_barcode").focus();
    });

    // add listener for enter key
    var input = document.getElementById("pointofsale_barcode");
    
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            findProductByBarcode();
        }
    });

    // query for the barcode
    function findProductByBarcode() {
        var barcode = $("#pointofsale_barcode").val();
        console.log(barcode);

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
                $('#info').html('<p>An error has occurred</p>');
            },
            success: function(data) {
                console.log(data);
                // var $title = $('<h1>').text(data.talks[0].talk_title);
                // var $description = $('<p>').text(data.talks[0].talk_description);
                // $('#info')
                //     .append($title)
                //     .append($description);
            },
            type: 'GET'
        });
    }
</script>