@extends('layouts.root.main')

@section('main')
    <div class="row mt-3 ml-2">
        <nav aria-label="breadcrumb">
            <ol class="shadow breadcrumb breadcrumb-style1" style="background-color: #ffffff;">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" style="color: black !important">Electric</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);" class="active" style="color: gray !important">Traceability</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="shadow card" style="padding: 2rem; border-radius:8px">
                <input id="code" type="text" class="form-control" name="code" tabindex="1"
                    placeholder="scan or type part here..." required autofocus autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow" style="border-radius:8px">
                <div class="card-header">
                    <h4>Traceability Data</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="table-1">
                            <thead>
                                <tr>
                                    <th>Line</th>
                                    <th>Production Date</th>
                                    <th>Scanned By</th>
                                    <th>Model</th>
                                    <th>Status</th>
                                    <th>Shot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="data">
                                    <td colspan="6" id="nullData">
                                        <h5 class="text-center mt-4" id="splash">Please scan part first</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
    <script>
        $(document).ready(function() {
            let barcode = "";
            let barcodecomplete = "";
            $("#code").keypress(function(e) {
                e.preventDefault();
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code == 13) // Enter key hit
                {
                    barcodecomplete = barcode;
                    barcode = "";
                    console.log(barcodecomplete);
                    if (barcodecomplete.length == 21) {

                        $('#data').html(
                            `<td colspan="6" id="nullData">
                                <h5 class="text-center mt-4" id="splash">Fetching Data...</h5>
                            </td>`);

                        trace(barcodecomplete);

                    } else if (barcodecomplete.length != 21) {

                        $('#data').html(
                            `<td colspan="6" id="nullData">
                                <h5 class="text-center mt-4" id="splash">Fetching Data...</h5>
                            </td>`);
                        notif('error', 'Part tidak dikenali')

                    } else {
                        $('#data').html(
                            `<td colspan="6" id="nullData">
                                <h5 class="text-center mt-4" id="splash">Fetching Data...</h5>
                            </td>`);
                        notif('error', 'Mohon Scan Ulang')
                    }
                } else {
                    barcode = barcode + String.fromCharCode(e.which);
                }
            });


            $('#logout').click(function() {
                localStorage.removeItem('first')
            })

        });

        function trace(code) {
            $.ajax({
                type: 'get',
                url: "{{ url('/trace/part') }}" + "/" + code,
                dataType: 'json',
                success: function(data) {
                    if (data.status == "successOk") {
                        $('#data').html(`<td id="line">ASAN01</td>
                                    <td class="align-middle" id="date">
                                        ${data.date}
                                    </td>
                                    <td class="align-middle" id="scanned-by">
                                        ${data.npk}
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn btn-info" id="model"> ${data.model}</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-success" id="status">OK</div>
                                    </td>
                                    <td class="align-middle" id="shot">
                                        ${data.shot}
                                    </td>`);

                        notif("success", "Data part berhasil diambil");

                        return true
                    } else if (data.status == "successNg") {
                        $('#data').html(`<td id="line">ASAN01</td>
                                    <td class="align-middle" id="date">
                                        ${data.date}
                                    </td>
                                    <td class="align-middle" id="scanned-by">
                                        ${data.npk}
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn btn-info" id="model"> ${data.model}</div>
                                    </td>
                                    <td>
                                        <div class="btn btn-danger" id="status">NG</div>
                                    </td>
                                    <td class="align-middle" id="shot">
                                        ${data.shot}
                                    </td>`);

                        notif("success", "Data part berhasil diambil");

                        return true
                    } else if (data.status == "error") {
                        notif("error", data.messege);
                        return false
                    } else if (data.status == "null") {
                        notif("error", "Part tidak terdaftar");
                        return false
                    }
                },
                error: function(xhr) {
                    if (xhr.status == 0) {
                        notif("error", 'Connection Error');
                        return;
                    }
                    notif("error", 'Internal Server Error');
                }
            });
        }

        function notif(type, message) {
            if (type == 'error') {
                iziToast.error({
                    title: 'Error!  ' + message,
                    position: 'bottomRight'
                });
            } else if (type == 'success') {
                iziToast.success({
                    title: 'Success! ' + message,
                    position: 'bottomRight'
                });
            }
        }
    </script>
@endsection
