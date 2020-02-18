@extends('layouts.admin')
@section('title', 'Sendmunk | Admin User Register')

@section('content')
    <h1>Register New User</h1>
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
    
            <form class="ui form" method="post" action="{{ url('admin/registeruser') }}" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Name" required/>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email Address" required/>
                </div>
                {{-- <div class="field">
                    <label>FE</label>
                    <select name="fe" class="ui dropdown">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="field">
                    <label>OTO1</label>
                    <select name="oto1" class="ui dropdown">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>                 
                    </select>
                </div>
                <div class="field">
                    <label>OTO2</label>
                    <select name="oto2" class="ui dropdown">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>                
                    </select>
                </div>
                <div class="field">
                    <label>OTO3</label>
                    <select name="oto3" class="ui dropdown">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>               
                    </select>
                </div>
                <div class="field">
                    <label>OTO4</label>
                    <select name="oto4" class="ui dropdown">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>                  
                    </select>
                </div> --}}
                <button id="submit-btn" type="submit" class="fluid ui primary publish large button">Register</button>
            </form>
        </div>
        <div class="four wide column"></div>
    </div>
@endsection

@section('footerscripts')
    <script>
        
        $('.ui .dropdown').dropdown();
    </script>
@endsection