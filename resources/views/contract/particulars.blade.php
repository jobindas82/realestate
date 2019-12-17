<fieldset>
    <div class="row clearfix">
        <div class="col-sm-12">
            <div class="body table-responsive">
                <table class="table table-hover" id="items-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Particular</th>
                            <th>Amount</th>
                            <th>Tax(%)</th>
                            <th>Gross Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( [new \App\models\Tenants()] as $i => $each )
                        <tr>
                            <th>{{ $i+1 }}</th>
                            <td>{{ Form::select('ContractItems['.$i.'][ledger_id]', \App\models\Ledgers::children($each->ledger_id), $each->ledger_id, [ 'class' => 'contract-item form-control show-tick ajax-drop', 'required', 'id' => 'ContractItems_'.$i.'_ledger_id' ]) }}</td>
                            <td>{{ Form::text('ContractItems['.$i.'][amount]', $each->amount, [ 'class' => 'form-control', 'required']) }}</td>
                            <td>{{ Form::select('ContractItems['.$i.'][tax_id]', \App\models\TaxCode::activeCodes((int) $model->tax_id), $each->tax_id, [ 'class' => 'contract-item form-control show-tick ajax-drop', 'required', 'id' => 'ContractItems_'.$i.'_tax_id', 'min' => '1' ]) }}</td>
                            <td>{{ Form::text('ContractItems['.$i.'][gross_amount]', $each->gross_amount, [ 'class' => 'form-control', 'readonly' => true]) }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</fieldset>

<script>
    setTimeout(function() {
        $('.contract-item').selectpicker({
            liveSearch: true
        });
    }, 600);
</script>