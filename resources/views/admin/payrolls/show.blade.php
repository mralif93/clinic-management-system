@extends('layouts.admin')

@section('title', 'Payslip Details')
@section('page-title', 'Payslip Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-money text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Payslip #{{ $payroll->id }}</h1>
                        <p class="text-emerald-100 flex items-center gap-2 mt-1">
                            <i class='bx bx-user'></i>
                            {{ $payroll->user->name ?? 'Employee' }} • {{ $payroll->pay_period ?? 'N/A' }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-400/30',
                                    'approved' => 'bg-blue-400/30',
                                    'paid' => 'bg-green-400/30',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusColors[$payroll->status] ?? 'bg-gray-400/30' }}">
                                @if($payroll->status === 'paid')
                                    <i class='bx bx-check-circle mr-1'></i>
                                @elseif($payroll->status === 'approved')
                                    <i class='bx bx-badge-check mr-1'></i>
                                @else
                                    <i class='bx bx-file mr-1'></i>
                                @endif
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Icon-only action buttons -->
                    <button onclick="printPayslip()" title="Print Payslip"
                        class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-blue-600 hover:bg-blue-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-printer text-xl'></i>
                    </button>
                    <button onclick="downloadPayslip()" title="Download PDF"
                        class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-emerald-600 hover:bg-emerald-50 hover:scale-105 transition-all shadow-lg">
                        <i class='bx bx-download text-xl'></i>
                    </button>
                    
                    <div class="w-px h-8 bg-white/30 mx-1"></div>
                    
                    @if($payroll->status === 'draft')
                        <button onclick="approvePayroll({{ $payroll->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-600 rounded-full font-semibold hover:bg-emerald-50 hover:scale-105 transition-all shadow-lg">
                            <i class='bx bx-check-circle text-lg'></i>
                            Approve
                        </button>
                        <a href="{{ route('admin.payrolls.edit', $payroll->id) }}" title="Edit Payslip"
                            class="w-11 h-11 flex items-center justify-center bg-white rounded-full text-amber-600 hover:bg-amber-50 hover:scale-105 transition-all shadow-lg">
                            <i class='bx bx-edit text-xl'></i>
                        </a>
                    @elseif($payroll->status === 'approved')
                        <button onclick="markAsPaid({{ $payroll->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-600 rounded-full font-semibold hover:bg-purple-50 hover:scale-105 transition-all shadow-lg">
                            <i class='bx bx-dollar-circle text-lg'></i>
                            Mark as Paid
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.payrolls.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur text-white rounded-full font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Payslip Card -->
        <div class="bg-gray-50 rounded-2xl p-4 md:p-6">
            <div class="max-w-[210mm] mx-auto">
                @include('admin.payrolls.payslip_template', ['payroll' => $payroll])
            </div>
        </div>

        <!-- Notes -->
        @if($payroll->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Notes</h3>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <p class="text-gray-700">{{ $payroll->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Audit Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Generated By</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $payroll->generatedBy->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $payroll->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($payroll->approved_by)
                        <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <p class="text-xs text-blue-600 mb-1">Approved By</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $payroll->approvedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $payroll->approved_at->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                    @if($payroll->status === 'paid')
                        <div class="text-center p-4 bg-green-50 rounded-xl border border-green-100">
                            <p class="text-xs text-green-600 mb-1">Payment Date</p>
                            <p class="text-sm font-semibold text-green-900">
                                {{ $payroll->payment_date ? $payroll->payment_date->format('M d, Y') : 'N/A' }}</p>
                            <p class="text-xs text-green-600">Ref: {{ $payroll->payment_reference ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone (only for draft) -->
        @if($payroll->status === 'draft')
            <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
                <div class="p-6 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                        <i class='bx bx-error-circle text-red-600'></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete this payslip</p>
                            <p class="text-sm text-gray-500">This action cannot be undone.</p>
                        </div>
                        <form action="{{ route('admin.payrolls.destroy', $payroll->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                                <i class='bx bx-trash'></i>
                                Delete Payslip
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @push('scripts')
        <script>
            function getPdfOptions(filename) {
                return {
                    margin: [5, 5, 5, 5],
                    filename: filename,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        letterRendering: true,
                        logging: false
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    },
                    pagebreak: { mode: 'avoid-all' }
                };
            }

            function printPayslip() {
                const element = document.getElementById('payslip-content');
                const opt = getPdfOptions('Payslip-{{ str_replace(' ', '-', $payroll->pay_period ?? 'N-A') }}-{{ Str::slug($payroll->user->name ?? 'Employee') }}.pdf');

                html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdf) {
                    // Open PDF in new window for printing
                    const blob = pdf.output('blob');
                    const url = URL.createObjectURL(blob);
                    const printWindow = window.open(url, '_blank');
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                });
            }

            function downloadPayslip() {
                const element = document.getElementById('payslip-content');
                const opt = getPdfOptions('Payslip-{{ str_replace(' ', '-', $payroll->pay_period ?? 'N-A') }}-{{ Str::slug($payroll->user->name ?? 'Employee') }}.pdf');
                html2pdf().set(opt).from(element).save();
            }

            function approvePayroll(payrollId) {
                Swal.fire({
                    title: 'Approve Payroll?',
                    text: "This will approve the payslip for payment.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/payrolls/${payrollId}/approve`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function markAsPaid(payrollId) {
                Swal.fire({
                    title: 'Mark as Paid',
                    text: "Enter the payment reference number (optional):",
                    input: 'text',
                    inputPlaceholder: 'Payment Reference (e.g., TRX12345)',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8b5cf6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, mark as paid!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/payrolls/${payrollId}/mark-as-paid`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const referenceInput = document.createElement('input');
                        referenceInput.type = 'hidden';
                        referenceInput.name = 'payment_reference';
                        referenceInput.value = result.value;
                        form.appendChild(referenceInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection