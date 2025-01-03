<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $('.country_select2').select2({
                dropdownParent: $('#addModal')
            });
            $('.country_select22').select2({
                dropdownParent: $('#editStateModal')
            });
            $('.timezone_select2_add').select2({
                dropdownParent: $('#addModal')
            });
            $('.timezone_select2_edit').select2({
                dropdownParent: $('#editStateModal')
            });

            // add country
            $(document).on('click','.add_country',function(e){
                let state = $('#state').val();
                let country = $('#country').val();
                let timezone = $('#timezone').val();
                if(state == '' || country == '' || timezone==''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            // show state in edit modal
            $(document).on('click','.edit_state_modal',function(){
                let state_id = $(this).data('state_id');
                let state = $(this).data('state');
                let country = $(this).data('country');
                let timezone = $(this).data('timezone');

                $('#state_id').val(state_id).trigger("change");
                $('#edit_state').val(state).trigger("change");
                $('#edit_country').val(country).trigger("change");
                $('#edit_timezone').val(timezone).trigger("change");
            });

            // update state
            $(document).on('click','.edit_state',function(e){
                let state = $('#edit_state').val();
                let country = $('#edit_country').val();
                let timezone = $('#edit_timezone').val();
                if(state == '' || country == '' || timezone==''){
                    toastr_warning_js("{{ __('Please fill both field !') }}");
                    return false;
                }
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('admin.state.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.state.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            });

            // change status
            $(document).on('click','.swal_status_change',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to change status complete? Once you done you can not revert this !!")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, change it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

        });
    }(jQuery));
</script>
