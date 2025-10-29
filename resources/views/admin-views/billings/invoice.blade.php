<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-10 bg-gray-100">
    <div class="max-w-2xl mx-auto bg-white p-8 shadow-md rounded-lg">
        <!-- Logo -->
        <div class="text-center mb-4">
            <img src="logo.png" alt="Lab Logo" class="h-16 mx-auto">
        </div>

        <!-- Invoice Header -->
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Clinical Invoice</h2>
                <p class="text-sm text-gray-500">Invoice #: INV-{{ invoice.id }}</p>
                <p class="text-sm text-gray-500">FS No.: {{ invoice.fs_no }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date & Time: {{ invoice.date_time }}</p>
            </div>
        </div>

        <!-- Patient & Billing Info -->
        <div class="grid grid-cols-2 gap-6 mt-4">
            <div>
                <h3 class="font-semibold text-gray-700">Patient Details</h3>
                <p><strong>Name:</strong> {{ patient.full_name }}</p>
                <p><strong>Age:</strong> {{ patient.age }}</p>
                <p><strong>Contact:</strong> {{ patient.contact }}</p>
                <p><strong>Address:</strong> {{ patient.address }}</p>
                <p><strong>VAT Reg. No.:</strong> {{ patient.vat_reg_no }}</p>
                <p><strong>TIN No.:</strong> {{ patient.tin_no }}</p>
                <p><strong>Registration Date:</strong> {{ patient.registration_date }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700">Billing Details</h3>
                <p><strong>Date:</strong> {{ invoice.date }}</p>
                <p><strong>Total Amount:</strong> <span id="totalAmount">{{ invoice.total }}</span></p>
                <p><strong>Status:</strong> <span class="px-2 py-1 rounded bg-green-500 text-white">{{ invoice.status }}</span></p>
                <p><strong>Biller TIN No.:</strong> {{ invoice.biller_tin_no }}</p>
                <p><strong>Address:</strong> {{ invoice.biller_address }}</p>
            </div>
        </div>

        <!-- Test Details -->
        <h3 class="mt-6 font-semibold text-gray-700">Test Details</h3>
        <table class="w-full border mt-2">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Test</th>
                    <th class="p-2 text-right">Cost</th>
                </tr>
            </thead>
            <tbody>
                {% for test in invoice.tests %}
                <tr>
                    <td class="p-2">{{ test.name }}</td>
                    <td class="p-2 text-right"><span class="currency">{{ test.cost }}</span></td>
                </tr>
                {% endfor %}
            </tbody>
        </table>

        <!-- Payment History -->
        <h3 class="mt-6 font-semibold text-gray-700">Payment History</h3>
        <table class="w-full border mt-2">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Method</th>
                    <th class="p-2 text-right">Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                {% for payment in invoice.payments %}
                <tr>
                    <td class="p-2">{{ payment.method }}</td>
                    <td class="p-2 text-right"><span class="currency">{{ payment.amount }}</span></td>
                </tr>
                {% endfor %}
            </tbody>
        </table>

        <!-- Footer -->
        {{-- <p class="text-center text-gray-500 text-sm mt-6">Thank you for choosing our laboratory services.</p> --}}
    </div>

    <script>
        var currencySymbol = "{{ currency }}";
        var currencyPosition = "{{ currency_position }}" || "right";

        document.querySelectorAll('.currency').forEach(el => {
            let amount = el.innerText.trim();
            el.innerText = currencyPosition === 'left' ? currencySymbol + amount : amount + currencySymbol;
        });
    </script>
</body>
</html>
