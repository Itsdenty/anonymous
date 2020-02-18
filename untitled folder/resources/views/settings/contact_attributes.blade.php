@extends('layouts.user')
@section('title', 'Sendmunk | Contact Attributes')

@section('content')
    @if(Session::has('error'))
    <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('error') }}
        </div>
    </div>
    @endif
    @if(Session::has('status'))
    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('status') }}
        </div>
    </div>
    @endif

    <div class="ui vertical segment">
        <h2>Contact Attributes</h2>
    </div>
    <br/>
    <div class="ui segment">
        <a id="add_attribute_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Add Attribute</a>
        <h3>Attributes</h3>
        <hr/>
        <table class="ui celled stackable table" style="background:white;">
            <thead>
                <tr>
                    <th width="35%">Attribute Name</th>
                    <th width="15%">Atribute Type</th>
                    <th style="text-align: center" width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>firstname</td>
                    <td>text</td>
                    <td style="text-align: center">Not Editable</td>
                </tr>
                <tr>
                    <td>lastname</td>
                    <td>text</td>
                    <td style="text-align: center">Not Editable</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>text</td>
                    <td style="text-align: center">Not Editable</td>
                </tr>
    
                @foreach($contact_attributes as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->type }}</td>
                    <td style="text-align: center">
                        <a class="edit_attribute" data-id="{{ $attribute->id }}" data-name="{{ $attribute->name }}" data-type="{{ $attribute->type }}" href="#"><i data-content="Edit Attribute" class="edit icon"></i></a>
                        <a href="{{ url('attribute/delete').'/'.$attribute->id }}"><i data-content="Delete Attribute" class="delete icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Create Attribute Modal --}}
    <div id="create_attribute_modal" class="ui tiny modal">
        <div class="header">Add Attribute</div>
        <div class="content">
            <form action="{{ url('createattribute') }}" id="create_new_attribute" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Attribute Name</label>
                    <input type="text" name="name"  placeholder="Attribute Name" required />
                </div>
                <div class="field">
                    <label>Attribute Type</label>
                    <select id="attribute_type" class="ui dropdown" name="type" required>
                        <option value="">Attribute Type</option>
                        <option value="text">Text</option>
                        <option value="date">Date</option>
                        <option value="number">Number</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_attribute_btn" type="submit" value="Create" form="create_new_attribute" class="ui button" />
        </div>
    </div>

    {{-- Edit Attribute Modal --}}
    <div id="edit_attribute_modal" class="ui tiny modal">
        <div class="header">Edit Attribute</div>
        <div class="content">
            <form action="{{ url('updateattribute') }}" id="edit_attribute_form" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <input type="hidden" name="attribute_id" id="edit_attribute_id" />
                <div class="field">
                    <label>Attribute Name</label>
                    <input id="edit_attribute_name" type="text" name="name"  placeholder="Attribute Name" required />
                </div>
                <div class="field">
                    <label>Attribute Type</label>
                    <select id="edit_attribute_type" class="ui dropdown" name="type" required>
                        <option value="">Attribute Type</option>
                        <option value="text">Text</option>
                        <option value="date">Date</option>
                        <option value="number">Number</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="edit_attribute_btn" type="submit" value="Update" form="edit_attribute_form" class="ui button" />
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('#add_attribute_icon').on('click', function(e){
            e.preventDefault();
            $('#create_new_attribute')[0].reset();
            $('#attribute_type').dropdown('clear');
            $('#create_attribute_modal').modal('show');
        });

        $('.edit_attribute').on('click', function(e){
            e.preventDefault();
            let attribute_id = $(this).data('id');
            let attribute_name = $(this).data('name');
            let attribute_type = $(this).data('type');

            $('#edit_attribute_id').val(attribute_id);
            $('#edit_attribute_name').val(attribute_name);
            $('#edit_attribute_type').val(attribute_type).trigger('change');

            $('#edit_attribute_modal').modal('show');
        });
    </script>
@endsection