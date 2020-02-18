@extends('layouts.user')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" />
@stop

@section('title', 'Sendmunk | Subscribers')

@section('content')
    <div class="ui vertical segment">
        <h2>Subscribers</h2>
    </div>
    <br/>
    @if(!$subscribers->isEmpty())
    <table id="subscribers_table" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Email</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $subscriber)
            <tr>
                <td>{{ $subscriber->email }}</td>
                <td>{{ $subscriber->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any subscriber(s) yet</p></div>
    @endif
@endsection

@section('footerscripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#subscribers_table').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>
@endsection