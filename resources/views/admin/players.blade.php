    @extends('layouts.app')
    @section('title', 'Player Management')
    @section('content')
        <main>
            <div class="influ-strip-2">
                <div class="influ-search">
                    <form>
                        <!-- <label for="">
                            <input type="text" name="" placeholder="Search by Sur name">
                            <button><img src="{{ asset('assets/images/search.png') }}" alt=""></button>
                        </label> -->
                        <label for="">
                            <input type="text" placeholder="Search by name or full name" name="search" id="search"
                                value="{{ $request->search ?? '' }}">
                            <button type="button" onclick="player_manage_search()"><img
                                    src="{{ asset('assets/images/search-jb.png') }}" alt=""></button>
                        </label>
                    </form>
                </div>
                <div class="influ-btns">

                    <form id="player_manage_fillter">

                        <input type="hidden" name="search" value="{{ $request->search ?? '' }}">


                        <div class="influ-search">
                            <!-- <label for="">
                            <input type="text" placeholder="Search by Sur name" name="search" value="{{ $request->search ?? '' }}">
                            <button><img src="{{ asset('assets/images/search-jb.png') }}" alt=""></button>
                        </label> -->
                        </div>

                        <!-- 1 -->

                        <div class="quotes">
                            <div id="dropdown-btn-8" class="dropdown-btn">Country <i class="far fa-chevron-down"></i></div>
                            <div id="dropdown-8" class="dropdown " style="display: none">

                                <div class="quotes-list">
                                    @forelse ($countries as $country)
                                        <div id="search-value-1" class="search-value">
                                            <label class="influ-btns-label"><input type="checkbox" name="country[]"
                                                    value="{{ $country->name }}"
                                                    {{ !empty($request->country) && in_array($country->name, $request->country) ? 'checked' : '' }}>{{ $country->name }}</label>
                                        </div>
                                    @empty
                                    @endforelse
                                    {{-- <div id="search-value-1" class="search-value">
                                    <label class="influ-btns-label"><input type="checkbox" value="">Germany</label>
                                </div>
                                <div id="search-value-1" class="search-value">
                                    <label class="influ-btns-label"><input type="checkbox" value="">France</label>
                                </div>
                                <div id="search-value-1" class="search-value">
                                    <label class="influ-btns-label"><input type="checkbox" value="">Argentina</label>
                                </div>
                                <div id="search-value-1" class="search-value">
                                    <label class="influ-btns-label"><input type="checkbox" value="">Italy</label>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- 2 -->
                        <div class="quotes">
                            <div id="dropdown-btn-9" class="dropdown-btn">Type <i class="far fa-chevron-down"></i></div>
                            <div id="dropdown-9" class="dropdown " style="display: none">

                                <div class="quotes-list">
                                    <div id="search-value-2" class="search-value">
                                        <label class="influ-btns-label"><input type="checkbox" name="types[]"
                                                {{ !empty($request->types) && in_array('Gold', $request->types) ? 'checked' : '' }}
                                                value="Gold">Gold</label>
                                    </div>
                                    <div id="search-value-2" class="search-value">
                                        <label class="influ-btns-label"><input type="checkbox" name="types[]"
                                                {{ !empty($request->types) && in_array('Red', $request->types) ? 'checked' : '' }}
                                                value="Red">Red</label>
                                    </div>
                                    <div id="search-value-2" class="search-value">
                                        <label class="influ-btns-label"><input type="checkbox" name="types[]"
                                                {{ !empty($request->types) && in_array('Purple', $request->types) ? 'checked' : '' }}
                                                value="Purple">Purple</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <a href="javascript:void()" class="influ-btn" onclick="modal_open_csv()"><img
                                src="{{ asset('assets/images/importcsvicon.png') }}" alt=""> Import CSV</a>
                        <a href="javascript:void()" class="influ-btn" onclick="export_csv()"><img
                                src="{{ asset('assets/images/exporticon.svg') }}" alt=""> Export CSV </a>
                        <a href="javascript:void(0)" class="influ-btn" onclick="sample_csv()">
                            <img src="{{ asset('assets/images/exporticon.svg') }}" alt="">
                            Download Template
                        </a>

                        <div class="elipse-wrap">
                            <!-- <i class="fa fa-ellipsis-v" aria-hidden="true"></i> -->
                            <!-- <i aria-hidden="true"><img src="{{ asset('assets/images/elipsisicon.png') }}" alt="" alt=""></i> -->
                        </div>

                        <!-- <div class="show-elipse-card" style="display:none;">
                            <a href="javascript:void(0)" class="btn-elipse1" onclick="delete_all()">Delete All</a>
                            <a href="javascript:void(0)" class="btn-elipse1" onclick="sample_csv()">Download Sample CSV</a>
                        </div> -->


                    </form>
                </div>
            </div>

            <div class="influ-table" id="playerTable">
                <div id="table-responsive-1" class="table-responsive">
                    <table>
                        <tr>
                            <!-- <th>S. No.</th>
                            <th>Surname</th>
                            <th>Country</th>
                            <th>Type</th>
                            <th>Designation</th>
                            <th>Action</th> -->


                            <th>S. No.</th>
                            <!-- <th>Competition</th> -->
                            <th>Year</th>
                            <th>Team</th>
                            <th>Number</th>
                            <th>Position</th>
                            <!-- <th>Full Name</th> -->
                            <th>Country</th>
                            <!-- <th>Country ID</th> -->
                            <th>Captain</th>
                            <th>Name</th>
                            <!-- <th>Position ID</th> -->
                            <th>Special</th>
                            <!-- <th>Sticker</th> -->
                            <th>Action</th>

                        </tr>
                        @php
                            $i = $players->perPage() * ($players->currentPage() - 1);
                        @endphp





                        @forelse($players as $player)
                            <tr>
                                <!-- <td>{{ ++$i }}</td> -->
                                <td>{{ $loop->index + 1 + ($players->currentPage() - 1) * $players->perPage() }}</td>
                                <!-- <td>{{ $player->Competition ?? '-' }}</td> -->
                                <td>{{ $player->Year ?? '-' }}</td>
                                <td>{{ $player->team ?? '-' }}</td>
                                <td>{{ $player->number ?? '-' }}</td>
                                <td>{{ $player->position ?? '-' }}</td>
                                <!-- <td>{{ $player->FullName ?? '-' }}</td> -->
                                <td>{{ $player->Country ?? '-' }}</td>
                                <!-- <td>{{ $player->country_id ?? '-' }}</td> -->
                                <!-- <td>{{ $player->IsCaptain ?? '-' }}</td> -->
                                <td>{{ $player->IsCaptain == 'TRUE' ? 'Yes' : 'No' }}</td>

                                <td>{{ $player->Name ?? '-' }}</td>
                                <!-- <td>{{ $player->Position_id ?? '-' }}</td> -->
                                <td>{{ ucfirst($player->Special) ?? '-' }}</td>
                                <!-- <td>
                                       @if ($player->sticker)
    <img src="{{ asset('upload/stickers/' . $player->sticker) }}"
                                                width="50"
                                                height="50"
                                                style="object-fit:cover;">
@else
    -
    @endif
                                   </td> -->

                                <!-- <td>

                                    <a href="javascript:void(0)"
                                         class="editPlayer"
                                         data-id="{{ $player->id }}">
                                          <img src="{{ asset('assets/images/edit.png') }}" alt="">
                                      </a>
                                    <a type="button" class="show-modal" onclick="conform_delete('{{ $player->id }}')">
                                        <img src="{{ asset('assets/images/remove.png') }}" alt="">
                                    </a>

                                    
                                   </td> -->
                                <td>
                                    {{-- <a href="javascript:void(0)"
                                         class="editPlayer"
                                          data-id="{{ $player->id }}">
                                           <img src="{{ asset('assets/images/edit.png') }}" alt="">
                                      </a> --}}

                                    <a type="button" class="show-modal"
                                        onclick="conform_delete('{{ $player->id }}')">
                                        <img src="{{ asset('assets/images/remove.png') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14">Data not found</td>
                            </tr>
                        @endforelse


                    </table>




                    <!-- <p>Showing 50 of 170 results</p> -->
                    <!-- {!! $players->onEachSide(1)->links('layouts.paginate') !!} -->
                    {{-- <div class="pagination">
            <div class="influ-pagi">
                <ul>
                    <li><a href="#"><i>Prev</i></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#"><i>Next</i></a></li>
                </ul>
            </div>
            <form action="">
                <div class="showing-1">
                    <p>Showing 50 of 170 results</p>
                </div>
            </form>
        </div> --}}

                </div>
                {!! $players->onEachSide(1)->links('layouts.paginate') !!}
        </main>


        <!-- delete popup -- -->

        <div class="modal fade" id="delete-query" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit" role="document">
                <div class="modal-content clearfix">
                    <div class="modall-head">
                        <button type="button" class="close close-btn-front" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body-2">
                        <div class="small-box">
                            <form id="player_delete_form" method="post">
                                @csrf
                                <input type="hidden" name="player_id" id="player_id" value="">
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


        <div class="modal fade exportclass" id="myModal-2" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit" role="document">
                <div class="modal-content clearfix">
                    <div class="modal-heading ">
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h1>Player CSV</h1>
                        <div class="edit-in">
                            <form id="csv_upload_form" enctype="multipart/form-data">
                                <input class="import-field" id="csv_upload" name="csv_upload" type="file"
                                    placeholder="User Name">
                                {{-- <input class="edit-in_txt-2" type="text" placeholder="Profile picture">
								<input class="edit-in_txt-2" type="text" placeholder="Email ">
								<input class="edit-in_txt-2" type="text" placeholder="Country">
								<input class="edit-in_txt-2" type="text" placeholder="Skill level">
								<input class="edit-in_txt-2" type="text" placeholder="Position ">
								<input class="edit-in_txt-2" type="text" placeholder="Style of play">
								<input class="edit-in_txt-2" type="text" placeholder="Profile picture"> --}}
                                {{-- <input class="login-btn edit-in_btn" type="button" value="Edit"> --}}
                                {{-- <input class="login-btn edit-in_btn" type="button" value="Upload"> --}}
                                <button class="login-btn edit-in_btn csv_save_button" type="submit">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- delete popup -- -->


        <div class="modal fade" id="myModal-33" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-edit" role="document">
                <div class="modal-content clearfix">
                    {{-- <div class="modal-heading">
						<button type="button"class="close close-btn"data-dismiss="modal"aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>

					</div> --}}
                    <div class="modal-body">
                        <div class="edit-in">
                            {{-- progress bar start --}}
                            {{-- <div class="progress" style="display: block;">
                                <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                    10%
                                </div>
                            </div> --}}
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100">25%</div>
                            </div>

                            {{-- progress bar end --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- edit modal  -->
        <div class="modal fade" id="editPlayerModal" tabindex="-1">
            <!-- <div class="modal-dialog modal-dialog-edit"> -->
            <div class="modal-dialog modal-dialog-edit modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content clearfix">

                    <div class="modal-heading">
                        <button type="button" class="close close-btn" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body mb-3">

                        <div class="edit-heading-popup">
                            <h4>Edit Player</h4>
                        </div>

                        <div class="edit-in">

                            <form id="editPlayerForm" enctype="multipart/form-data">

                                @csrf

                                <input type="hidden" id="player_id">

                                <input class="edit-in_txt-2" type="text" id="Competition" name="Competition"
                                    placeholder="Competition">

                                <input class="edit-in_txt-2" type="text" id="Year" name="Year"
                                    placeholder="Year">

                                <input class="edit-in_txt-2" type="text" id="team" name="team"
                                    placeholder="Team">

                                <input class="edit-in_txt-2" type="text" id="number" name="number"
                                    placeholder="Number">

                                <input class="edit-in_txt-2" type="text" id="position" name="position"
                                    placeholder="Position">

                                <input class="edit-in_txt-2" type="text" id="FullName" name="FullName"
                                    placeholder="Full Name">

                                <input class="edit-in_txt-2" type="text" id="Country" name="Country"
                                    placeholder="Country">

                                <input class="edit-in_txt-2" type="text" id="country_id" name="country_id"
                                    placeholder="Country ID">

                                <select class="edit-in_txt-2" id="IsCaptain" name="IsCaptain">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>

                                <input class="edit-in_txt-2" type="text" id="Name" name="Name"
                                    placeholder="Name">

                                <input class="edit-in_txt-2" type="text" id="Position_id" name="Position_id"
                                    placeholder="Position ID">

                                <select class="edit-in_txt-2" id="Special" name="Special">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>

                                <div class="mt-2">
                                    <!-- <label>Sticker</label> -->

                                    <input type="file" class="edit-in_txt-2" id="sticker" name="sticker"
                                        accept="image/*">
                                </div>

                                <input class="login-btn edit-in_btn" type="submit" value="Update">

                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- edit modal  -->





        <script>
            // function export_csv() {
            //     // console.log('gdgafsgfdag')
            //     let params = new URLSearchParams(document.location.search);
            //     let newParams = params.toString()
            //     console.log('ghghghghghg', newParams);
            //     if (newParams != '') {
            //         window.location.href = '/admin/player_export_csv?' + newParams;
            //     } else {
            //         window.location.href = '/admin/player_export_csv';
            //     }

            // }

            function export_csv() {

                let data = $('#player_manage_fillter').serialize();
                data += '&search=' + $('#search').val();

                window.location.href = '/admin/player_export_csv?' + data;
            }



            function modal_open_csv() {
                $('#myModal-2').modal('show');
                // $('#myModal-33').modal('show');
            }

            function conform_delete(payer_id) {
                $('#delete-query').modal('show');
                $('#player_id').val(payer_id);

            }


            $("#player_delete_form").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                // for (const pair of formData.entries()) {
                // console.log(`${pair[0]}, ${pair[1]}`);
                // }
                // return false;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    url: '{{ route('admin.delete_player') }}',
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






            $("#csv_upload_form").submit(function(e) {
                e.preventDefault();


                // let fileInput = $('#csv_upload');
                let progressBar = $('.progress');
                let progressBarText = $('.progress-bar');

                // let formData = new FormData(this);
                // // formData.append('csv_file', fileInput.files[0]);

                // axios.post('admin/import_player_csv', formData, {
                //     headers: { 'Content-Type': 'multipart/form-data' },
                //     onUploadProgress: function (progressEvent) {
                //         let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                //         progressBar.style.display = 'block';
                //         progressBarText.style.width = percentCompleted + '%';
                //         progressBarText.innerHTML = percentCompleted + '%';
                //     },
                // }).then(function (response) {
                //     progressBar.style.display = 'none';
                //     fileInput.value = ''; // Clear the file input
                //     alert(response.data.message);
                // }).catch(function (error) {
                //     progressBar.style.display = 'none';
                //     alert('File upload failed.');
                // });

                // $('#myModal-2').modal('hide');
                // $('#myModal-33').modal('show');


                // ====================================================================
                let formData = new FormData(this);

                // for (const pair of formData.entries()) {
                // console.log(`${pair[0]}, ${pair[1]}`);
                // }
                // return false;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    url: '{{ route('admin.import_player_csv') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: formData, //form.serialize()
                    processData: false,
                    contentType: false,

                    // uploadProgress: function(event, positio, total, percentComplete){
                    // $('#myModal-2').modal('hide');
                    // $('#myModal-33').modal('show');

                    //         // let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    //         progressBar.style.display = 'block';
                    //         progressBarText.style.width = percentCompleted + '%';
                    //         progressBarText.innerHTML = percentCompleted + '%';
                    //     },
                    success: function(res) {
                        if (res.status == 200) {
                            toastr.success('success..!');
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);;
                        } else {
                            $.each(res.errors, function(field_name, error) {
                                toastr.error(error);
                            });

                        }
                    },
                    error: function(result) {
                        // alert(result);
                        toastr.error('please upload correct csv format');

                    }
                });

            });



            // filter and search =================================
            // $('#player_manage_fillter').change(function() {
            //     // filterSearchReportManage();
            //     this.submit()
            // });

            function delete_all() {
                // console.log('gfhdsgghgdhs')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // cache: false,
                    url: '{{ route('admin.delete_all_player') }}',
                    type: 'get',
                    dataType: 'json',
                    // data: formData,//form.serialize()
                    // processData: false,
                    // contentType: false,
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


            }

            function sample_csv() {
                window.location.href = '/admin/sample_player_csv';
            }
        </script>

        <script>
            // function player_manage_search() {
            //     let search = $('#search').val();
            //     if (window.location.href.indexOf("?") > -1) {
            //         if (window.location.href.indexOf("search") > -1) {
            //             let params = new URLSearchParams(document.location.search);
            //             params.set("search", search);
            //             let newparam = params.toString();
            //             // console.log('newparam',newparam);
            //             window.location.href = '/admin/players?' + newparam;
            //         } else {
            //             window.location.href = `?search=${search}`;
            //         }
            //     } else {
            //         window.location.href = `?search=${search}`;
            //     }
            // };
            //changes
            $(document).ready(function() {

                $('#search').on('keyup', function() {
                    player_manage_search();
                });

            });

            function loadPlayers() {

                let data = $('#player_manage_fillter').serialize();
                data += '&search=' + $('#search').val();

                $.ajax({

                    url: "{{ url('/admin/players') }}",
                    type: "GET",
                    data: data,

                    success: function(response) {

                        $('#playerTable').html(
                            $(response).find('#playerTable').html()
                        );

                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }

                });

            }

            function player_manage_search() {
                loadPlayers();
            }

            $('#player_manage_fillter input[type="checkbox"]').on('change', function() {

                loadPlayers();

            });
            //changes
        </script>


        <script>
            // $(document).mouseup(function(e) 
            // {
            //     var container = $(".show-elipse-card");
            //     // if the target of the click isn't the container nor a descendant of the container
            //     if (!container.is(e.target) && container.has(e.target).length === 0) 
            //     {
            //         container.hide();
            //     }
            // });


            $(document).mouseup(function(e) {
                var container = $(".hile-card");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });

            //edit button js
            $(document).on('click', '.editPlayer', function() {

                var id = $(this).data('id');

                $.get("{{ url('admin/player') }}/" + id, function(res) {

                    $('#player_id').val(res.id);

                    $('#Competition').val(res.Competition);
                    $('#Year').val(res.Year);
                    $('#team').val(res.team);
                    $('#number').val(res.number);
                    $('#position').val(res.position);
                    $('#FullName').val(res.FullName);
                    $('#Country').val(res.Country);
                    $('#country_id').val(res.country_id);
                    $('#IsCaptain').val(res.IsCaptain);
                    $('#Name').val(res.Name);
                    $('#Position_id').val(res.Position_id);
                    $('#Special').val(res.Special);

                    $('#editPlayerModal').modal('show');
                });

            });

            //update js
            // $('#editPlayerForm').submit(function (e) {

            //     e.preventDefault();

            //     var id = $('#player_id').val();

            //     $.ajax({

            //         url: "{{ url('admin/player/update') }}/" + id,

            //         type: "POST",

            //         data: {
            //             _token: "{{ csrf_token() }}",

            //             Competition: $('#Competition').val(),
            //             Year: $('#Year').val(),
            //             team: $('#team').val(),
            //             number: $('#number').val(),
            //             position: $('#position').val(),
            //             FullName: $('#FullName').val(),
            //             Country: $('#Country').val(),
            //             country_id: $('#country_id').val(),
            //             IsCaptain: $('#IsCaptain').val(),
            //             Name: $('#Name').val(),
            //             Position_id: $('#Position_id').val(),
            //             Special: $('#Special').val()
            //         },

            //         // success: function (res) {

            //         //     if (res.success) {

            //         //         toastr.success(res.message);

            //         //         $('#editPlayerModal').modal('hide');

            //         //         players(); // ya location.reload();
            //         //     }

            //         // }

            //         success: function (res) {

            //     if (res.success) {

            //         toastr.success(res.message);

            //         $('#editPlayerModal').modal('hide');

            //         loadPlayers();   // table ajax se reload ho jayegi
            //     }

            // }

            //     });

            // });
            //update js
            //update new js
            $('#editPlayerForm').submit(function(e) {

                e.preventDefault();

                var id = $('#player_id').val();

                var formData = new FormData(this);

                $.ajax({

                    url: "{{ url('admin/player/update') }}/" + id,

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(res) {

                        if (res.success) {

                            toastr.success(res.message);

                            $('#editPlayerModal').modal('hide');

                            loadPlayers();

                        }

                    },

                    error: function(xhr) {

                        console.log(xhr.responseText);

                    }

                });

            });
            //update new js
            //edit button js
            // downloadtemplate 
            function sample_csv() {
                window.location.href = "{{ route('admin.sample_player_csv') }}";
            }
            // downloadtemplate 
        </script>

        <style>
            .close-btn {
                position: absolute;
                top: -10px;
                right: 18px;
                bottom: 0;
                border: 1px solid #fff !important;
                border-radius: 102%;
                width: 40px;
                height: 40px;
                box-shadow: none;
                color: #000;
                margin-left: auto;
                text-shadow: none;
                font-weight: 200;
                opacity: 1;
                line-height: 30px;
                z-index: 9;
                margin-bottom: 21rem;
            }
        </style>
    @endsection
