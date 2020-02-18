@extends('layouts.user')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" />
@stop

@section('title', 'Sendmunk | Responses')

@section('content')
    <div class="ui vertical segment">
        <h2>Responses</h2>
    </div>
    <br/>
    @if(!$questions->isEmpty())
    <table id="responses_table" class="ui table display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Question</th>
                <th>Option</th>
                <th style="text-align: center">Times Selected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
                @if(!$question->options->isEmpty())
                        @foreach($question->options as $key => $option)
                        <tr>
                            <td>{{ $question->title }}</td>
                            <td>{{ $option->title }}</td>
                            <td style="text-align: center">{{ $option->count }}</td>
                        </tr>                   
                        @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    @else
    <div class="ui message"><p>You don't have any Response(s) yet</p></div>
    @endif
@endsection

@section('footerscripts')
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
    <script>
        $(document).ready(function() {
            $('#responses_table').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                rowsGroup: [0],
            } );
        } );
    </script>
@endsection