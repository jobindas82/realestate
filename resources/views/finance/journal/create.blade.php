@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li>
                    <a href="/">
                        <i class="material-icons">home</i> Home
                    </a>
                </li>
                <li>
                    <a href="/finance/journal">
                        <i class="material-icons">euro_symbol</i> Journals
                    </a>
                </li>
                <li class="active">
                    <i class="material-icons">add_circle</i> Create
                </li>
            </ol>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Journal @if( $model->exists ) {{ '# '.$model->number  }} @endif
                    </h2>
                </div>
                <div class="body have-mask">
                    {{ Form::open(['method' => 'post', 'id' => 'journal-form']) }}
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::hidden('id', $model->id, [ 'id' => 'journal_id' ]) }}
                                    {{ Form::text('date', $model->exists ? $model->formated_date() : date('d/m/Y'), [ 'class' => 'form-control datepicker', 'required' => true ]) }}
                                    <label class="form-label">Entry Date</label>
                                </div>
                                <label id="date-error" class="error" for="date"></label>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    {{ Form::textarea('narration', $model->narration, [ 'class' => 'form-control no-resize auto-growth', 'rows' => 1]) }}
                                    <label class="form-label">Narration</label>
                                </div>
                                <label id="narration-error" class="error" for="narration"></label>
                            </div>
                        </div>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover" id="journal-items-table">
                            <thead>
                                <tr>
                                    <th style="width:2%">#</th>
                                    <th style="width:40%">Ledger</th>
                                    <th style="width:20%">Debit</th>
                                    <th style="width:20%">Credit</th>
                                    <th style="width:2%"><a href="#" title="Add Row" onclick="addRow();"><i class="material-icons">add_circle</i></a></th>
                                </tr>
                            </thead>
                            <tbody id="receipt-tbody">
                                @php
                                $totalDebit = 0;
                                $totalCredit = 0;
                                @endphp

                                @foreach( $model->exists ? $model->entries : [new \App\models\Entries()] as $i => $each )
                                @php
                                $debit = $each->amount > 0 ? number_format((float) $each->amount, 6, '.', '') : '';
                                $credit = $each->amount < 0 ? number_format((float) abs($each->amount), 6, '.', '') : '';
                                    $totalDebit += (float) $debit;
                                    $totalCredit += (float) $credit;
                                    @endphp
                                    <tr>
                                        <th><label>{{ $i+1 }}</label></th>
                                        <td>{{ Form::select('Entries['.$i.'][ledger_id]', \App\models\Ledgers::children($each->ledger_id), $each->ledger_id, [ 'class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_'.$i.'_ledger_id' ]) }}</td>
                                        <td>{{ Form::number('Entries['.$i.'][debit]', $debit, [ 'class' => 'form-control align-right', 'id' => 'Entries_'.$i.'_debit', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate(); setReadonly(this);' , 'onBlur' => 'round_field(this.id)', 'readonly' => $credit > 0 ? true : false ]) }}</td>
                                        <td>{{ Form::number('Entries['.$i.'][credit]', $credit, [ 'class' => 'form-control align-right', 'id' => 'Entries_'.$i.'_credit', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate(); setReadonly(this);' , 'onBlur' => 'round_field(this.id)', 'readonly' => $debit > 0 ? true : false]) }}</td>
                                        <td><a href="#" title="Remove" id="Entries_{{ $i }}_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                                    </tr>
                                    @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="warning">
                                    <th colspan="2" style="text-align:right">Total</th>
                                    <th><input type="text" name="total_debit" id="total_debit" class="form-control align-right" readonly="true" value="{{ number_format(round($totalDebit,  2), 2, '.', '') }}"></th>
                                    <th><input type="text" name="total_credit" id="total_credit" class="form-control align-right" readonly="true" value="{{ number_format(round($totalCredit,  2), 2, '.', '') }}"></th>
                                    <th></th>
                                </tr>
                                <tr class="warning">
                                    <th colspan="3" style="text-align:right">Difference</th>
                                    <th><input type="text" name="difference" id="difference" class="form-control align-right" readonly="true" value="{{ number_format(round(( $totalDebit - $totalCredit ),  6), 6, '.', '') }}"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="form-group align-right">
                        {{ Form::submit('Save', [ 'id' => 'basic_submit', 'class' => 'btn btn-danger'] )  }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#journal-form').on('submit', function(e) {

            var totalDebit = 0;
            var totalCredit = 0;

            $("#journal-items-table").find('tbody').find("tr").each(function() {
                totalDebit += +Number($(this).find("[id $=_debit]").val());
                totalCredit += +Number($(this).find("[id $=_credit]").val());
            });

            if (totalDebit > 0 && totalDebit === totalCredit) {
                //Hide Error Fields
                $('.error').hide();
                e.preventDefault();
                $('.page-loader-wrapper').fadeIn();

                $.ajax({
                    type: "POST",
                    url: '/finance/journal/save',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.message == 'success') {

                            $('#journal_id').val(response.journal_id);
                            $('.page-loader-wrapper').fadeOut();
                            Swal.fire(
                                'SUCCESS',
                                'Journal Saved!',
                                'success'
                            ).then((response) => {
                                location.href = "/finance/journal";
                            });

                        } else {
                            $('.page-loader-wrapper').fadeOut();
                            $.each(response, function(fieldName, fieldErrors) {
                                $('#' + fieldName + '-error').text(fieldErrors.toString());
                                $('#' + fieldName + '-error').show();
                            });
                        }
                    }
                });
            } else {
                alert('Totals Are not Matching!');
                return false;
            }
        });
    });

    function addRow() {
        last_field = $('#journal-items-table').find('tbody').find('tr:last select').attr('id');
        last_id = last_field.match(/\d+/g);
        new_id = Number(last_id) + 1;
        newRow = $('#journal-items-table').find('tbody').find('tr:last').clone();
        newRow.find('label:first').html(new_id + 1);
        newRow.attr('class', new_id);
        newRow.find('.bootstrap-select').replaceWith(function() {
            return $('select', this);
        });
        newRow.find('div,input,textarea,checkbox,td,select,a,button').each(function() {
            this.id = this.id.replace(/\d+/, new_id);
            if (!$(this).is(':checkbox'))
                this.value = '';
            else
                $(this).prop('checked', false);
            (this.name !== undefined) ? this.name = this.name.replace(/\d+/, new_id): this.style = '';
        });
        newRow.find('select').selectpicker({
            liveSearch: true,
            dropupAuto: false,
            size: 5
        });
        $('#journal-items-table').find('tbody').append(newRow);
        $('#Entries_' + new_id + '_debit').attr('readonly', false);
        $('#Entries_' + new_id + '_credit').attr('readonly', false);
    }

    function deleteRow(event) {
        var rowCount = $('#journal-items-table').find('tr:gt(0)').length;
        if (rowCount > 2) {
            $(event).parents('tr').remove();
            var i = 0;
            $('#journal-items-table').find('tr:gt(0)').each(function() {
                $(this).find('div,input,textarea,checkbox,td,select,a,button').each(function() {
                    old_id = $(this).attr('id');
                    if (old_id) {
                        new_id = old_id.replace(/\d+/, i);
                        $(this).attr('id', new_id);
                        old_name = $(this).attr('name');
                        if (old_name !== undefined) {
                            new_name = old_name.replace(/\d+/, i);
                            $(this).attr('name', new_name);
                        }
                    }
                    old_data_id = $(this).attr('data-id');
                    if (old_data_id) {
                        new_data_id = old_data_id.replace(/\d+/, i);
                        $(this).attr('data-id', new_data_id);
                    }
                });
                $(this).find('label:first').html(++i);
                calculate();
            });


        } else {
            Swal.fire('At least one item needed here!!');
        }
    }

    function calculate() {

        var totalDebit = 0;
        var totalCredit = 0;

        $("#journal-items-table").find('tbody').find("tr").each(function() {
            totalDebit += +Number($(this).find("[id $=_debit]").val());
            totalCredit += +Number($(this).find("[id $=_credit]").val());
        });

        var difference = totalDebit - totalCredit;

        $("#total_debit").val(roundNumber(totalDebit, 2).toFixed(2));
        $("#total_credit").val(roundNumber(totalCredit, 2).toFixed(2));
        $("#difference").val(roundNumber(difference, 6).toFixed(6));

        return false;
    }

    function setReadonly(event) {
        var fieldId = event.id;
        var arr_field_id = fieldId.split('_');
        var i = arr_field_id[1];
        var clicked = arr_field_id[2];
        var fieldValue = event.value;

        $('#Entries_' + i + '_debit').attr('readonly', false).attr('tabindex', 0);
        $('#Entries_' + i + '_credit').attr('readonly', false).attr('tabindex', 0);

        if (+fieldValue > 0) {
            if (clicked == 'debit') {
                $('#Entries_' + i + '_credit').attr('readonly', true).attr('tabindex', -1);
            } else {
                $('#Entries_' + i + '_debit').attr('readonly', true).attr('tabindex', -1);
            }
        }
    }
</script>

@endsection