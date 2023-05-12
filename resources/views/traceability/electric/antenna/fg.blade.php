@extends('layouts.root.traceability')

@section('main')    

<div class="row mt-5">
    <div class="col-12 col-sm-12 col-md-12">
        <h5 class="mb-3 text-right"><span class="badge badge-dark" style="border-radius: 7px !important">Welcome, {{ auth()->user()->name }}</span></h5>
        <div class="shadow hero bg-white text-dark rounded-3">
            <div class="hero-inner">
                <input id="code" type="text" class="form-control" name="code" tabindex="1" placeholder="scan part here..." required autofocus>
                
                <div class="row mt-3">
                    <div class="col-md-2 col-sm-12" style="margin-top: 2.5rem">
                        <p style="color: #595757; font-size:1.5rem; font-weight: bold">Line</p>
                        <h1 class="text-dark" style="font-weight: 800 !impoertant">ASAN01</h1>
                    </div>
                    <div class="col-md-10 col-sm-12">
                        <div class="hero bg-primary text-dark" id="alert">
                            <div class="hero-inner">
                                <h5 class="text-left" style="color: #ffffff; margin-top:-1.5rem" id="status">Part Scanned</h5>
                                <h1 class="text-center" style="color: #ffffff" id="result">-</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-10 col-sm-10 col-md-10 col-sm-12 mt-4">
        <div class="shadow hero bg-white text-dark rounded-3">
            <div class="hero-inner">
                <div class="row">
                    <div class="col-9 col-sm-9 col-md-9 col-sm-12">
                        <h5 class="text-left" style="color:#595757;">Parts Scanned</h5>
                        <div class="bg-secondary m-auto" style="max-height: 7rem; width: 100%; border-radius: 6px;">
                            <div class="list-group mt-3" style="max-height: 7rem; width: 100%; overflow:scroll; overflow-x: hidden" id="part-scanned">
                                <li class="list-group-item example-list" id="example-list">
                                    <h4 style="color: #ffffff">081920192819281</h4>
                                    <h4 style="color: #ffffff">081920192819281</h4>
                                    <h4 style="color: #ffffff">081920192819281</h4>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-sm-3 col-md-3 col-sm-12">
                        <h5 class="text-center mb-3" style="color:#595757;">Kanban</h5>
                        <div class="m-auto" style="height: 7rem; width: 100%; background-color:#EAEEED; border-radius: 6px; padding: 35px 0">
                            <h1 class="text-center" style="color: #000000;" id="kanban-scanned"></h1>         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-2 col-sm-2 col-md-2 col-sm-12 mt-4">
        <div class="shadow pt-4"  style="height: 7rem; width: 100%; background-color: #ffffff; border-radius: 6px">
            <div class="hero-inner">
                <h5 class="text-center"  style="color:#595757;">Total Scan</h5>
                <div class="m-auto shadow" style="background-color:#EAEEED; height: 13rem; width: 85%; border-radius: 6px; padding: 80px 0">
                    <h1 class="text-center" style="color: #000000;" id="total-scan">0</h1>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal info --}}
<div class="modal fade" tabindex="-1" role="dialog" id="infoModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Traceability Step</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="wizard-steps">
                            <div class="wizard-step wizard-step-active">
                                <div class="wizard-step-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="wizard-step-label">
                                    Scan Kanban
                                </div>
                            </div>
                            <div class="wizard-step wizard-step-active">
                                <div class="wizard-step-icon">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div class="wizard-step-label">
                                    Scan 100 Parts
                                </div>
                            </div>
                            <div class="wizard-step wizard-step-warning">
                                <div class="wizard-step-icon">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <div class="wizard-step-label">
                                    Kembali ke Step 1
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end of modal --}}
@endsection

@section('custom-script')
<script>
    function initApp(){
        let kanban = localStorage.getItem('kanban');
        let counter = localStorage.getItem('counter');
        
        if (kanban != null || kanban != undefined) {
            $('#kanban-scanned').text(kanban);
        }

        if (counter != null || counter != undefined) {
            $('#total-scan').text(counter);
        }
        
    }

    $(document).ready(function() {
        initApp();
        $('#code').focus(); 
        $('#infoModal').modal('show');
        
        setTimeout(() => {
            $('#infoModal').modal('hide');
            $('#code').focus();
        }, 5000);
        
        $(document).on('click', function() {
            $('#code').focus(); 
        })
        
        let barcode = "";
        let barcodecomplete = "";
        $("#code").keypress(function(e) {
            e.preventDefault();
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code==13)// Enter key hit
            {
                barcodecomplete = barcode;
                barcode = "";
                console.log(barcodecomplete);
                if (barcodecomplete.length == 21) {
                    // if data kanban exists inside local storage
                    if (checkDataLocal() == true ){
                        // insert part
                        storePart(barcodecomplete);
                        
                    }else{
                        notifMessege("error", "Scan Kanban Dulu!");
                    }
                } 
                else if(barcodecomplete.length == 230) 
                {
                    storeKanban(barcodecomplete);
                } 
                // improve ng at double scan part
                else if (barcodecomplete.length <= 2)
                {
                    checkNg(barcodecomplete);
                }
                else if (barcodecomplete.length == 13)
                {
                    window.location.replace("{{url('//logout')}}");
                    
                }
                else if(barcodecomplete.length != 230) 
                {
                    notifMessege('error', 'Part atau Kanban Tidak Dikenali')
                }
                else if (barcodecomplete == "RELOAD")
                {
                    location.reload();
                    
                }
                else
                {
                    notifMessege('error', 'Mohon Scan Ulang')
                }
            }
            else
            {
                barcode=barcode+String.fromCharCode(e.which);
            }
        });
        
        function storeKanban(kanban) {
            console.log(kanban);
            $.ajax({
                type: 'get',
                url: "{{ url('/trace/scan/antenna/storeKanban') }}",
                data: {
                    kanban : kanban,
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.status == "success") {
                        saveDataLocalStorage(data.code);
                        return true
                    } else if (data.status== "error") {
                        notifMessege("error", data.messege);
                        return false
                    } else if (data.status == "Kanbannotreset") {
                        notifMessege("error", "Kanban masih berisi Part");
                        return false
                    } else if (data.status == "notregistered") {
                        notifMessege("error", "Kanban Tidak Terdaftar");
                        return false
                    } else if (data.status == "unfinished") {
                        notifMessege("error", "Part belum lengkap");
                        return false
                    } else {
                        notifMessege("error", "Server Error");
                        return false
                    }
                },
                error: function (xhr) {
                    if (xhr.status == 0) {
                        notifMessege("error", 'Connection Error');
                        return;
                    }
                    notifMessege("error", 'Fatal Error');
                }
            });
        };
        
        function storePart(code) {
            let kanban = localStorage.getItem('kanban');

            $.ajax({
                type: 'get',
                url: "{{ url('/trace/scan/antenna/storePart') }}",
                data: {
                    code : code,
                    kanban : kanban.substr(5, 4),
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == "success") {
                        $('#total-scan').text(data.counter);
                        localStorage.setItem('counter', data.counter);
                        notifMessege("success", data.code);
                        displayPart(data.code);
                        return true
                    } else if (data.status== "error") {
                        notifMessege("error", data.messege);
                        return false
                    } else if (data.status == "exist") {
                        notifMessege("error", "Part Sudah Ada");
                        return false
                    } else if (data.status == "notregistered") {
                        notifMessege("error", "Kanban Tidak Terdaftar");
                        return false
                    } else if (data.status == "unfinished") {
                        notifMessege("error", "Part belum lengkap");
                        return false
                    }
                },
                error: function (xhr) {
                    if (xhr.status == 0) {
                        notifMessege("error", 'Connection Error');
                        return;
                    }
                    notifMessege("error", 'Fatal Error');
                }
            });
        }

        function checkNg(ngId){
            $.ajax({
                type: 'get',
                url: "{{ url('/trace/scan/ng/check') }}",
                data: {
                    ng : ngId,
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == "success") {

                        // redirect to ng mode
                        window.location.replace("{{url('/trace/scan/antenna/ng')}}"+"/"+ ngId);

                        return true
                    } else if (data.status== "error") {
                        notifMessege("error", data.message);
                        return false
                    }
                },
                error: function (xhr) {
                    if (xhr.status == 0) {
                        notifMessege("error", 'Connection Error');
                        return;
                    }
                    notifMessege("error", 'Fatal Error');
                }
            });
        }
        
        
        function saveDataLocalStorage(backNumber) {
            let kanban = localStorage.getItem('kanban');
            if (kanban == null || kanban == undefined) {
                localStorage.setItem('kanban', backNumber);
                $('#kanban-scanned').text(backNumber);
                notifMessege("success", backNumber);
                console.log('test');
            } else {
                
                // check if kanban already exists in local storage
                // that means MP will start for another kanban 
                // (so check 1st the counter, is it multiple of 100 or not)
                
                // get current counter value
                let current_counter = $('#total-scan').text();
                
                // check is it multiple of 100
                if(current_counter % 100 == 0){
                    localStorage.setItem('kanban', backNumber);
                    $('#parts').removeClass('parts');
                    $('#kanban-scanned').text(backNumber);
                    notifMessege("success", backNumber);
                }else{
                    notifMessege("error", "Part belum lengkap");
                }
            }
            
        }

        function displayPart(code){
            $('#example-list').remove();

            $('#part-scanned').append(
                `<li class="list-group-item parts" id="parts">
                    <h4 style="color: #000000">${code}</h4>
                </li>`);
        }
        
        function checkDataLocal() {
            let kanban = localStorage.getItem('kanban');
            
            if (kanban == null || kanban == undefined) {
                return false
            } else {
                return true;
            }
        }
        
        function notifMessege(type, messege) {
            if (type == "error") {
                $('#alert').removeClass('bg-success');
                $('#alert').addClass('bg-danger');
                $('#status').html(`<h5 class='text-left' style='color: #ffffff; margin-top:-1.5rem' id='status'>Error!</h5>`);
                $('#result').text(messege);
            } else if (type == "success") {
                $('#alert').removeClass('bg-danger');
                $('#alert').addClass('bg-success');
                $('#status').html(`<h5 class='text-left' style='color: #ffffff; margin-top:-1.5rem' id='status'>Success</h5>`);
                $('#result').text(messege);
            }
        }
    });
</script>
@endsection