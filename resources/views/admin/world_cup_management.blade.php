@extends('layouts.app')
@section('title', 'World Cup Management')
@section('content')

    <main>

        <div class="influ-strip-2 d-flex justify-content-between align-items-center">

            <div class="influ-search">
                <form>
                    <label>
                        <input type="text" name="search" placeholder="Search by country or year" id="search">
                        <button type="button">
                            <img src="{{ asset('assets/images/search-jb.png') }}" alt="">
                        </button>
                    </label>
                </form>
            </div>

            <div class="influ-btns ">

                <a href="javascript:void(0)" class="influ-btn" data-toggle="modal" data-target="#addWorldCupModal">
                    ADD
                </a>

            </div>

        </div>

        <div class="influ-table" id="worldcup">

            <div class="table-responsive">

                <table>

                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Country</th>
                            <th>Short Name</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $i = $worldcup->perPage() * ($worldcup->currentPage() - 1);
                        @endphp



                        @forelse($worldcup as $item)
                            @php
                                $countries = \App\Models\Country::whereIn('id', explode(',', $item->country_id))->get();
                            @endphp
                            <tr>

                                <td>{{ ++$i }}</td>

                                <!-- <td>{{ $item->Country->name ?? '' }}</td> -->
                                <td>
                                    {{ $countries->pluck('name')->implode(', ') }}
                                </td>
                                <!-- <td>{{ $item->Country->short_name ?? '' }}</td> -->
                                <td>
                                    {{ $countries->pluck('short_name')->implode(', ') }}
                                </td>

                                <td>{{ $item->year }}</td>

                                <td>
                                    @if ($item->is_upcoming == 0)
                                        Live
                                    @else
                                        Upcoming
                                    @endif
                                </td>

                                <td>
                                    <img src="{{ asset($item->image) }}" style="width:134px;">
                                </td>
                                <!-- <td>{{ $item->image }}</td>
                                          -->


                                <td>

                                    <!-- <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="">
                                                    </a> -->
                                    <a href="javascript:void(0)" class="editWorldCup" data-id="{{ $item->id }}">
                                        <img src="{{ asset('assets/images/edit.png') }}" alt="">
                                    </a>
                                    <!-- <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/images/remove.png') }}" alt="">
                                                    </a> -->

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {!! $worldcup->links('layouts.paginate') !!}

        </div>

    </main>
    <!-- add modal -->
    <div class="modal fade" id="addWorldCupModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add World Cup</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <form id="addWorldCupForm" enctype="multipart/form-data">

                        @csrf

                        <!-- <div class="form-group">
                                    <label>Country</label>

                                    <select class="form-control import-field" name="country_id" id="add_country_id">

                                        <option value="">Select Country</option>

                                        @foreach ($country as $item)
    <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                                ({{ $item->short_name }})
    </option>
    @endforeach

                                    </select>
                                </div>
                                    -->
                        <!-- change  -->
                        <div class="form-group">
                            <label>Country</label>

                            <select class="form-control" name="country_id[]" id="add_country_id" multiple="multiple">

                                @foreach ($country as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} ({{ $item->short_name }})
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <!-- change  -->
                        <div class="form-group">
                            <label>Year</label>

                            <input type="number" class="import-field" name="year" id="add_year"
                                placeholder="Enter Year">
                        </div>

                        <!-- <div class="form-group">
                                                <label>Short Name</label>

                                                <input type="text"
                                                       class="import-field"
                                                       name="short_name"
                                                       id="add_short_name"
                                                       placeholder="Enter short name">
                                            </div> -->

                        <div class="form-group">
                            <label>Status</label>

                            <select class="form-control import-field" name="is_upcoming" id="add_is_upcoming">

                                <option value="0">Live</option>
                                <option value="1">Upcoming</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Image</label>

                            <input type="file" class="import-field" name="image" id="image">
                        </div>

                        <button type="submit" class="logout-in-btn">
                            Save
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- add modal -->
    <!-- edit modal  -->

    <div class="modal fade" id="editWorldCupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-edit" role="document">
            <div class="modal-content clearfix">

                <div class="modal-heading">
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="edit-heading-popup">
                        <h4>Edit World Cup</h4>
                    </div>

                    <div class="edit-in">

                        <form id="editWorldCupForm" enctype="multipart/form-data">

                            @csrf

                            <input type="hidden" id="worldcup_id">

                            <!-- Country -->
                            <!-- <select class="edit-in_txt-2" id="country_id" name="country_id">
                                        @foreach ($country as $item)
    <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                                ({{ $item->short_name }})
    </option>
    @endforeach
                                    </select> -->

                            <select class="edit-in_txt-2" id="country_id" name="country_id[]" multiple="multiple">

                                @foreach ($country as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} ({{ $item->short_name }})
                                    </option>
                                @endforeach

                            </select>

                            <!-- Year -->
                            <input class="edit-in_txt-2" type="number" id="year" name="year"
                                placeholder="Year">

                            <!-- Status -->
                            <select class="edit-in_txt-2" id="is_upcoming" name="is_upcoming">

                                <option value="0">Live</option>
                                <option value="1">Upcoming</option>

                            </select>

                            <input class="edit-in_txt-2" type="file" id="edit_image" name="edit_image">
                            <img id="previewImage" src="" width="120" style="display:none; margin-top:10px;">

                            <input class="login-btn edit-in_btn" type="submit" value="Update">

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- edit modal  -->
    <script>
        $(document).ready(function() {

            // $('#search').on('keyup', function() {
            //     worldcup();
            // });

            $('#search').on('keyup', function () {

    history.replaceState({}, '', '?page=1');

    worldcup(1);

});

            // $('.influ-search form').on('submit', function(e) {
            //     e.preventDefault();
            //     worldcup();
            // });

            $('.influ-search form').on('submit', function(e) {

    e.preventDefault();

    history.replaceState({}, '', '?page=1');

    worldcup(1);

});

        });

        // function worldcup() {

        //     var page = new URLSearchParams(window.location.search).get('page') || 1;

        //     $.ajax({
        //         url: "{{ route('admin.world_cup_management') }}",
        //         type: "GET",
        //         data: {
        //             search: $('#search').val(),
        //             page: page
        //         },
        //         success: function(response) {

        //             $('#worldcup').html(
        //                 $(response).find('#worldcup').html()
        //             );

        //         },
        //         error: function(xhr) {
        //             console.log(xhr.responseText);
        //         }
        //     });

        // }
       
   function worldcup(page = 1) {

    $.ajax({
        url: "{{ route('admin.world_cup_management') }}",
        type: "GET",
        data: {
            search: $('#search').val(),
            page: page
        },
        success: function(response) {
            $('#worldcup').html($(response).find('#worldcup').html());
        }
    });

}

//change ajax pagnation
$(document).on('click', '.pagination a', function(e) {

    e.preventDefault();

    var url = $(this).attr('href');

    history.pushState({}, '', url);

    $.get(url, function(response) {

        $('#worldcup').html($(response).find('#worldcup').html());

    });

});


        // edit button js
        $(document).on('click', '.editWorldCup', function() {

            var id = $(this).data('id');

            // $.get("{{ url('admin/worldcup') }}/" + id, function(res) {
            //     $('#worldcup_id').val(res.id);

            //     $('#country_id').val(res.country_id);

            //     $('#year').val(res.year);

            //     $('#is_upcoming').val(res.is_upcoming);

            //     $('#editWorldCupModal').modal('show');

            // });

            $.get("{{ url('admin/worldcup') }}/" + id, function(res) {

                $('#worldcup_id').val(res.id);

                var countries = res.country_id.split(',');

                $('#country_id').val(countries).trigger('change');

                $('#year').val(res.year);

                $('#is_upcoming').val(res.is_upcoming);

                $('#editWorldCupModal').modal('show');

            });

        });

        // $('#editWorldCupForm').submit(function(e) {

        //     e.preventDefault();

        //     var id = $('#worldcup_id').val();

        //     $.ajax({

        //         url: "{{ url('admin/worldcup/update') }}/" + id,

        //         type: "POST",

        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             country_id: $('#country_id').val(),
        //             year: $('#year').val(),
        //             is_upcoming: $('#is_upcoming').val()
        //         },

        //         success: function(res) {

        //             if (res.success) {

        //                 toastr.success(res.message);

        //                 $('#editWorldCupModal').modal('hide');

        //                 worldcup();

        //             }

        //         }

        //         //        error:function(xhr){

        //         //     toastr.error(xhr.responseJSON.message);

        //         // }

        //     });

        // });

        $('#editWorldCupForm').submit(function(e) {

            e.preventDefault();

            var id = $('#worldcup_id').val();

            var formData = new FormData(this);

            $.ajax({

                url: "{{ url('admin/worldcup/update') }}/" + id,

                type: "POST",

                data: formData,

                processData: false,
                contentType: false,

                success: function(res) {

                    if (res.success) {

                        toastr.success(res.message);

                        $('#editWorldCupModal').modal('hide');

                        worldcup();

                    }

                }

            });

        });
        //add modal in worldcup 
        $('#addWorldCupForm').submit(function(e) {

            e.preventDefault();

            // $.ajax({

            //     url: "{{ route('admin.worldcup.store') }}",

            //     type: "POST",

            //     data: {
            //         _token: "{{ csrf_token() }}",
            //         country_id: $('#add_country_id').val(),
            //         short_name: $('#add_short_name').val(),
            //         year: $('#add_year').val(),
            //         is_upcoming: $('#add_is_upcoming').val(),
            //         image: $('#image').val()
            //     },
            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.worldcup.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,


                success: function(res) {

                    if (res.success) {

                        toastr.success(res.message);

                        $('#addWorldCupModal').modal('hide');

                        $('#addWorldCupForm')[0].reset();

                        worldcup();

                    }

                },

                error: function(xhr) {

                    toastr.error(xhr.responseJSON.message);

                }

            });

        });

        //chnage multiple select
        $('#add_country_id').select2({
            placeholder: "Select Countries",
            closeOnSelect: false,
            dropdownParent: $('#addWorldCupModal'),
            width: '100%'
        });

        //edit intialize 
        $('#country_id').select2({
            placeholder: "Select Countries",
            closeOnSelect: false,
            dropdownParent: $('#editWorldCupModal'),
            width: '100%'
        });
        //chnage multiple select
    </script>
    <style>
        .influ-btn {
            margin-left: 732px !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

@endsection
