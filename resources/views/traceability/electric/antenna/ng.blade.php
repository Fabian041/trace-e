@extends('layouts.root.traceability')

@section('main')
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12">
            <h5 class="mb-3 text-right"><span class="badge badge-dark" style="border-radius: 7px !important">Welcome,
                    {{ auth()->user()->name }}</span></h5>
            <div class="shadow hero bg-white text-dark rounded-3">
                <div class="hero-inner">
                    <div class="row mb-3 w-100">
                        <div class="col-12 d-flex justify-content-center w-100">
                            <h1 class="text-dark" style="font-weight: 800 !impoertant">Input Part NG - LINE ASAN01</h1>
                        </div>
                    </div>
                    <div class="row mb-3 w-100">
                        <div class="col-12 d-flex justify-content-center w-100">
                            <h1 class="text-danger" style="font-weight: 400 !impoertant">{{ $ngName->name }}</h1>
                        </div>
                    </div>

                    <form class="row mb-3 w-100">
                        <input id="code" type="text" class="form-control" name="code" tabindex="1"
                            placeholder="scan part here..." autofocus>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
<script>
    $(document).ready(function() {
        let part = "";
        let ngcode = "{{$ngId}}";
        $('#code').focus();
        $('#code').keypress( function(e) {
            let code = (e.keyCode ? e.keyCode : e.which);
            if(code==13) {
                e.preventDefault();
                part = $('#code').val();
                
                if (part == "NGMODE") {
                    window.location.replace("{{url('/trace/scan/antenna')}}");
                    return;
                }
                
                if (part.length == 21) {
                    storeNgPart(ngcode,part);
                } else {
                    notif("error", "TOLONG SCAN PART KEMBALI");
                    $('#code').val("");
                    $('#code').focus();
                }
            }
        });
    });

    function storeNgPart(ngId, part) {
            $.ajax({
                type: 'get',
                url: "{{ url('/trace/scan/antenna/ng/store') }}"+'/'+ngId+'/'+part,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.status == "success") {
                        notif("success", data.message);

                        setInterval(() => {
                            window.location.replace("{{url('/trace/scan/antenna')}}");
                        }, 2000);

                        return true
                    } else if (data.status== "error") {
                        notif("error", data.message);
                        return false
                    } else if (data.status == "Kanbannotreset") {
                        notif("error", "Kanban masih berisi Part");
                        return false
                    } else if (data.status == "notregistered") {
                        notif("error", "Kanban Tidak Terdaftar");
                        return false
                    } else if (data.status == "unfinished") {
                        notif("error", "Part belum lengkap");
                        return false
                    } else {
                        notif("error", "Server Error");
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

    function notif(type, message){
        if(type == 'error'){
            iziToast.error({
                title: 'Error!  ' + message,
                position: 'topCenter'
            });
        }else if(type == 'success'){
            iziToast.success({
                title: 'Success! ' + message,
                position: 'topCenter'
            });
        }
    }
</script>
@endsection