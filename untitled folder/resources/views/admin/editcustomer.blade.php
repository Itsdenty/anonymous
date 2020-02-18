@extends('layouts.admin')
@section('title', 'PixlyPRO | Admin Edit User')

@section('content')
    <h1>Edit User</h1>
    <hr/>
    <div class="ui stackable grid">
        <div class="four wide column"></div>
        <div class="eight wide column">
            @if ($errors->any())
                <div class="ui warning message">
                    <i class="close icon"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
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
    
            <form class="ui form" method="post" action="{{ url('admin/updateuser') }}" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ $user->id }}"/>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" name="name" placeholder="Name" value="{{ $user->name }}" required/>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" placeholder="Email Address" disabled/>
                </div>
                {{-- <div class="field">
                    <label>FE</label>
                    <select name="fe" class="ui dropdown">
                        <option value="0" {{ $subplan->fe == 0 ? "selected" : "" }}>0</option>
                        <option value="1" {{ $subplan->fe == 1 ? "selected" : "" }}>1</option>
                        <option value="2" {{ $subplan->fe == 2 ? "selected" : "" }}>2</option>
                    </select>
                </div>
                <div class="field">
                    <label>OTO1</label>
                    <select name="oto1" class="ui dropdown">
                        <option value="0" {{ $subplan->oto1 == 0 ? "selected" : "" }}>0</option>
                        <option value="1" {{ $subplan->oto1 == 1 ? "selected" : "" }}>1</option>                 
                    </select>
                </div>
                <div class="field">
                    <label>OTO2</label>
                    <select name="oto2" class="ui dropdown">
                        <option value="0" {{ $subplan->oto2 == 0 ? "selected" : "" }}>0</option>
                        <option value="1" {{ $subplan->oto2 == 1 ? "selected" : "" }}>1</option>                
                    </select>
                </div>
                <div class="field">
                    <label>OTO3</label>
                    <select name="oto3" class="ui dropdown">
                        <option value="0" {{ $subplan->oto3 == 0 ? "selected" : "" }}>0</option>
                        <option value="1" {{ $subplan->oto3 == 1 ? "selected" : "" }}>1</option>
                        <option value="2" {{ $subplan->oto3 == 2 ? "selected" : "" }}>2</option>               
                    </select>
                </div>
                <div class="field">
                    <label>OTO4</label>
                    <select name="oto4" class="ui dropdown">
                        <option value="0" {{ $subplan->oto4 == 0 ? "selected" : "" }}>0</option>
                        <option value="1" {{ $subplan->oto4 == 1 ? "selected" : "" }}>1</option>                  
                    </select>
                </div> --}}
                <button id="submit-btn" type="submit" class="fluid ui primary publish large button">Update</button>
            </form>
        </div>
        <div class="four wide column"></div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
        
        $('.ui .dropdown').dropdown();
    </script>
@endsection