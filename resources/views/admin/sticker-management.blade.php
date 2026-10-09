@extends('layouts.app')
@section('title', 'Sticker Management')
@section('content')
    <main>
        <div class="influ-strip-2">
            <div class="influ-search">
                <form>
                    {{-- <label for="">
								<input type="text" name="" placeholder="Search by player name">
								<button><img src="images/search.png" alt=""></button>
							</label> --}}
                </form>
            </div>
            <div class="influ-btns">

                <form id="statistic_filter">
                    <div class="influ-search">

                        <!-- 1 -->
                        <label for="">
                            <input type="text" name="search" placeholder="Search by player name" id="search"
                                value="{{ $request->search ?? '' }}">
                            <button><img src="{{ asset('assets/images/search-jb.png') }}" alt=""></button>
                        </label>
                    </div>

                    <!-- 2 -->

                    <a href="javascript:void(0)"class="influ-btn" data-toggle="modal" data-target="#addModal">ADD </a>

            </div>
            </form>
        </div>
        <div class="influ-table" id="sticker">
            <div id="table-responsive-1" class="table-responsive">

                <table>
                    <tr>
                        <th>S. No.</th>
                        <th>Player</th>
                        <th>year</th>
                        <th>Sticker image</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>
                    @php
                        $i = $sticker->perPage() * ($sticker->currentPage() - 1);
                    @endphp
                    @foreach ($sticker as $index => $row)
                        <tr>
                            <td>{{ ++$i }}.</td>

                            <td>{{ $row->FullName ? $row->FullName : '--' }}</td>
                             <td>{{$row->year ? $row->year : '--'}}</td>
                            <td><img src="{{ asset($row->image) }}" style="width: 134px;"></td>
                          
                            <td>
    {{ $row->status == 0 ? 'Normal' : ($row->status == 1 ? 'World Cup' : ($row->status == 2 ? 'Manager' : ($row->status == 3 ? 'Legion' : 'Unknown'))) }}
</td>
                           


                            <td>
                                <!-- Edit Button -->
                                <a href="javascript:void(0);" class="show-modal"
                                    onclick="editSticker('{{ $row->id }}')">
                                    <img src="{{ asset('assets/images/edit.png') }}" alt="Edit">
                                </a>

                                &nbsp;

                                <a type="button"class="show-modal" onclick="conform_delete('{{ $row->id }}')">
                                    <img src="{{ asset('assets/images/remove.png') }}"alt=""></a>
                            </td>

                        </tr>
                    @endforeach





                </table>
                {!! $sticker->onEachSide(1)->links('layouts.paginate') !!}

            </div>
    </main>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Sticker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form action="{{ url('admin/add_sticker') }}" method="post" enctype="multipart/form-data"> -->
                    <form id="addStickerForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="" class="sticke_id">
                        @csrf


                        <!-- change -->
                        <div class="form-group">
                            <label for="year">Session:</label>

                            <select class="form-control import-field" id="year" name="year" required
                                style="width: 301px;">
                                <option value="">Select Session</option>

                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        

                        <div class="form-group">
                            <label for="player_id">Player:</label>

                            <select class="form-control import-field" id="player_id" name="player_id">
                                <option value="">Select Player</option>
                            </select>
                        </div>
                        <!-- change -->

                        <div class="form-group">
                           <label>Status</label>
                           <select name="status" id="status" class="form-control">
                               <option value="">-- Select Status --</option>
                               <option value="0">Normal</option>
                               <option value="1">World Cup</option>
                               <option value="2">Manager</option>
                               <option value="3">Legion</option>
                           </select>
                       </div>

                        <div class="form-group">
                            <label for="message">Sticker Image:</label>
                            <input class="import-field" id="sticker_image" name="sticker_image" type="file"
                                placeholder="User Name">
                        </div>
                        <button type="submit" class="logout-in-btn">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Sticker Modal -->
    <div class="modal fade" id="editStickerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-edit" role="document">
            <div class="modal-content clearfix">

                <div class="modal-heading">
                    <button type="button" class="close close-btn" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="edit-heading-popup">
                        <h4>Edit Sticker</h4>
                    </div>

                    <div class="edit-in">

                        <form id="editStickerForm" enctype="multipart/form-data">

                            @csrf

                            <input type="hidden" id="sticker_id">

                            <!-- Player -->
                              <label>Session:</label>

                                    <select class="edit-in_txt-2" id="edit_year" name="year">
                                        <option value="">Select Session</option>

                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                               
                            <select class="edit-in_txt-2" id="edit_player_id" name="player_id">
                                @foreach ($players as $player)
                                    <option value="{{ $player->id }}">
                                        {{ $player->FullName }}
                                    </option>
                                @endforeach
                            </select>
                            
                           
                            <select class="edit-in_txt-2 mt-4" name="edit_status" id="edit_status">
                                <option value="">select status</option>
                                <option value="0">Normal</option>
                                <option value="1">World Cup</option>
                                <option value="2">Manager</option>
                                <option value="3">Legion</option>
                            </select>
                           
                                   

                            <!-- Sticker Image -->
                               
                               
                                <input type="file" class="edit-in_txt-2 mt-4" id="sticker_image" name="sticker_image">
                           
                           
                            <br>
                           

                              

                            <!-- <img id="previewImage"
                                         src=""
                                         width="120"
                                         style="display:none;margin-bottom:10px;"> -->

                            <input class="login-btn edit-in_btn" type="submit" value="Update">

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- delete popup -- -->

    <div class="modal fade" id="delete-query" tabindex="-1"role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-edit"role="document">
            <div class="modal-content clearfix">
                <div class="modall-head">
                    <button type="button" class="close close-btn-front" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body-2">
                    <div class="small-box">
                        <form id="delete_form" method="post">
                            @csrf
                            <input type="hidden" name="sticker_id" id="delete_sticker_id" value="">
                            <div class="content-box">
                                <img src="images/roam-images/delete-popup.png" alt="">
                                <h3 class="heading-delete">Delete!</h3>
                                <p class="para-2">Are you sure you want to delete ?</p>
                            </div>
                            <div>
                                {{-- <button  type="submit" class="confirm-btn" data-dismiss="modal"aria-label="Close">Confirm</a> --}}
                                <button type="submit" class="confirm-btn">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete popup -- -->
    <script>
        function conform_delete(sticker_id) {
            $('#delete-query').modal('show');
            $('#delete_sticker_id').val(sticker_id);

        }
        $("#delete_form").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // cache: false,
                url: '{{ route('admin.delete_sticker') }}',
                type: 'POST',
                dataType: 'json',
                data: formData, //form.serialize()
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status == 200) {
                        toastr.success('success..!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    } else {
                        $.each(res.errors, function(field_name, error) {
                            toastr.error(error);
                        });
                    }
                },
                error: function(result) {
                    alert(result);
                }
            });
        })

        $(document).ready(function() {
            $('#search').on('keyup', function() {
                loadSticker();
            })

        });

        function loadSticker() {
            $.ajax({
                url: "{{ route('admin.sticker_management') }}",
                type: "get",
                data: {
                    search: $('#search').val()
                },
                success: function(response) {
                    $('#sticker').html(
                        $(response).find('#sticker').html()
                    );
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        //chnage js
        $('#year').change(function() {

            var year = $(this).val();

            $('#player_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ url('admin/get-players') }}/" + year,
                type: "GET",
                success: function(response) {
                            console.log(response); 
                    var html = '<option value="">Select Player</option>';

                    $.each(response, function(index, row) {
                        html += '<option value="' + row.id + '">' + row.FullName + '</option>';
                    });

                    $('#player_id').html(html);
                     console.log($('#player_id').html());
                    $('#player_id').trigger('change.select2');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        });

        //select2 js
        // $('#addModal').on('shown.bs.modal', function () {

        //     $('#player_id').select2({
        //         // placeholder: "Search Player",
        //         dropdownParent: $('#addModal'),
        //         width: '100%'
        //     });

        // });

        $('#addModal').on('shown.bs.modal', function() {

            if ($('#player_id').hasClass('select2-hidden-accessible')) {
                $('#player_id').select2('destroy');
            }

            $('#player_id').select2({
                placeholder: "Select Player",
                dropdownParent: $('#addModal'),
                width: '100%'
            });

        });

        $('#editStickerModal').on('shown.bs.modal', function() {

            $('#edit_player_id').select2({
                placeholder: "Search Player",
                dropdownParent: $('#editStickerModal'),
                width: '100%'
            });

        });
        //select2 js
    </script>

    <script>
        function editSticker(id) {

            $.ajax({
                url: "{{ url('admin/sticker') }}/" + id,
                type: "GET",

                success: function(res) {

                    $('#sticker_id').val(res.id);
                   $('#edit_year').val(res.year);
                    $('#edit_player_id').val(res.player_id).trigger('change');
                      $('#edit_status').val(res.status);
                   

                    if (res.image) {
                        $('#previewImage')
                            .attr('src', '/' + res.image)
                            .show();
                    }

                    $('#editStickerModal').modal('show');
                }
            });

        }

        $('#editStickerForm').submit(function(e) {

            e.preventDefault();

            var id = $('#sticker_id').val();

            var formData = new FormData(this);

            $.ajax({
                url: "{{ url('admin/sticker/update') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {

                    if (res.success) {
                        toastr.success(res.message);
                        $('#editStickerModal').modal('hide');
                        location.reload();
                    }

                }

            });

        });


        //image preview
        $('#sticker_image').change(function() {

            let reader = new FileReader();

            reader.onload = function(e) {

                $('#previewImage')
                    .attr('src', e.target.result)
                    .show();

            };

            reader.readAsDataURL(this.files[0]);

        });

        //add modal js
        $('#addStickerForm').submit(function(e) {

            e.preventDefault();

            var formData = new FormData(this);
            
           
            $.ajax({
                url: "{{ url('admin/add_sticker') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(res) {
                         
                    if (res.status == 200) {

                        toastr.success(res.message);

                        $('#addModal').modal('hide');

                        setTimeout(function() {
                            location.reload();
                        }, 1000);

                    } else {

                        toastr.error(res.message);

                    }

                },

                error: function(xhr) {

                    if (xhr.status == 422) {

                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });

                    } else {

                        toastr.error("Something went wrong.");

                    }

                }
               
            });

        });
    </script>




    <style>
        /* Add Sticker Modal */
        #addModal .modal-content {
            border-radius: 12px !important;
            border: none !important;
        }

        #addModal .modal-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        #addModal .modal-title {
            font-size: 20px !important;
            font-weight: 600 !important;
        }

        #addModal .form-group {
            margin-bottom: 18px !important;
        }

        #addModal label {
            display: block !important;
            font-weight: 600 !important;
            margin-bottom: 8px !important;
            color: #333 !important;
        }

        #addModal .form-control,
        #addModal .import-field {
            width: 100% !important;
            height: 44px !important;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
        }

        #addModal input[type="file"] {
            height: auto !important;
            padding: 8px !important;
        }

        #addModal .logout-in-btn {
            width: 100% !important;
            height: 45px !important;
            border-radius: 8px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            margin-top: 10px !important;
            transition: all .3s ease !important;
        }

        #addModal .logout-in-btn:hover {
            transform: translateY(-2px) !important;
        }

        #addModal .select2-container {
            width: 100% !important;
        }

        #addModal .select2-container--default .select2-selection--single {
            height: 44px !important;
            border: 1px solid #ced4da !important;
            border-radius: 8px !important;
        }

        #addModal .select2-selection__rendered {
            line-height: 44px !important;
            padding-left: 12px !important;
        }

        #addModal .select2-selection__arrow {
            height: 44px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            background: #EFEFEF !important;
        }
    </style>


@endsection
