@extends('adminlte::page')

@section('title', 'Reportes')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Generar reporte</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>PENSIONISTA</th>
                        <th>SALDO ACTUAL S/.</th>
                        <th>ESTADO</th>
                        <th>GENERAR REPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pensioners as $pensioner)
                        <tr>
                            <td>{{ $pensioner->id }}</td>
                            <td>{{ $pensioner->name }} {{ $pensioner->lastname }}</td>
                            <td>
                                @if ($pensioner->accountStatus)
                                    {{ number_format($pensioner->accountStatus->current_balance, 2) }}
                                @else
                                    No consume/pago
                                @endif
                            </td>
                            <td>
                                @if ($pensioner->accountStatus)
                                    {{ ucfirst($pensioner->accountStatus->status) }}
                                @else
                                    Sin consumo
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm generate-report" data-id="{{ $pensioner->id }}"
                                    data-name="{{ $pensioner->name }} {{ $pensioner->lastname }}"
                                    data-balance="{{ $pensioner->accountStatus->current_balance ?? 0 }}"
                                    data-status="{{ $pensioner->accountStatus->status ?? 'Sin consumo' }}">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>

    <div class="modal fade" id="generateReportModal" tabindex="-1" role="dialog"
        aria-labelledby="generateReportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="generateReportForm" method="GET" action="{{ route('admin.reports.generate') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateReportModalLabel">Formulario de reporte</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pensioner_id" id="modalPensionerId">

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="pensioner_name">Pensionista</label>
                                <input type="text" class="form-control" id="modalPensionerName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="balance">Saldo actual S/.</label>
                                <input type="text" class="form-control" id="modalBalance" readonly>
                            </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="status">Estado</label>
                                <input type="text" class="form-control" id="modalStatus" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="month">Seleccione el mes</label>
                                <select name="month" id="month" class="form-control">
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ now()->year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                            {{ \Carbon\Carbon::createFromFormat('m', $month)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Descargar PDF</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section('js')
    <script>
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Al presionar "Generar PDF", abrir modal y cargar datos
            document.querySelectorAll('.generate-report').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('generateReportModal');

                    // Cargar datos al modal
                    document.getElementById('modalPensionerId').value = this.dataset.id;
                    document.getElementById('modalPensionerName').value = this.dataset.name;
                    document.getElementById('modalBalance').value = this.dataset.balance;
                    document.getElementById('modalStatus').value = this.dataset.status;

                    // Mostrar el modal
                    $(modal).modal('show');
                });
            });
        });

    </script>

@stop
