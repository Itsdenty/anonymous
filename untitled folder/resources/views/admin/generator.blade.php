@extends('layouts.admin')
@section('title', 'Sendmunk | Admin Dashboard')

@section('content')
    <h1>Generate License(s)</h1>
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
    
            <form class="ui form" method="post" action="{{ url('admin/generateLicense') }}" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="field">
                    <label>User ID: </label>
                    <input type="text" name="user_id"/>
                </div>
                <div class="field">
                    <label>Number of Licence(s)</label>
                    <input type="number" name="number" min="1" max="100" />
                </div>

                <button id="submit-btn" type="submit" class="fluid ui publish primary large button">Generate</button>
            </form>
        </div>
        <div class="four wide column"></div>
    </div>
@endsection
