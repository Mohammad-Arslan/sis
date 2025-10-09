<table id="fee-packages-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0"
    style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Frequency</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fee_charges as $key => $fee_charge)
            <tr>
                <td>
                    <input class="form-check-input" data-fee_package_id="{{ $fee_package[0]->id }}"
                        value="{{ $fee_charge->id }}" type="checkbox" id="feeCharges{{ $key }}"
                        {{ in_array($fee_charge->id, $fee_packages_fee_charges) ? 'checked' : '' }}>
                    <label class="form-check-label" for="feeCharges{{ $key }}">
                        {{ $fee_charge->fee_charges_type->name }}
                    </label>
                </td>
                <td>
                    {{ $fee_charge->fee_charges_type->frequency }}
                </td>
                <td>
                    {{ $fee_charge->amount }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
