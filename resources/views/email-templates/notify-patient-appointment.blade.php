<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Order Placed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            background-color: #e9ecef;
        }

        .card {
            background-color: #ffffff;
            border-radius: 0.25rem;
            margin: 1rem 0;
            padding: 1.25rem;
        }

        .card-header {
            background-color: rgb(0,168,232);
            color: white;
            padding: 1rem;
            border-radius: 0.25rem 0.25rem 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border: 1px solid #ddd; /* Border around the table */
        }

        th,
        td {
            border: 1px solid #ddd; /* Border around cells */
            padding: 12px; /* Padding between cells vertically */
            text-align: left;
        }

        th {
            background-color: rgb(0,168,232);
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ddd;
        }

        tfoot {
            background-color: rgb(0,168,232);
            color: white;
        }

        .headerColor{
            color: #1a82e2;
        }
    

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>
</head>

<body style="background-color: #e9ecef;">
    <!-- end preheader -->

    <div class="card" style="padding: 10px 20px;">
        {{-- <div class="card-header">
            <h2>Appointment Schedule Notification</h2>
        </div> --}}
        <div class="card-body">
            <p>Dear {{ $patient->full_name }},</p>
            <p>We are excited to inform you about your upcoming appointment scheduled with Dr. {{ $doctor->admin->f_name }}.</p>
            
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</p>
            <p><strong>Time Slot:</strong> 
                {{ \Carbon\Carbon::parse($appointment->appointmentSlot->start_time)->format('g:i a') }}
                -
                {{ \Carbon\Carbon::parse($appointment->appointmentSlot->end_time)->format('g:i a') }}    
            </p>
            
            <p>Please be prepared for your appointment on the specified date and time. If you have any questions or need to reschedule, feel free to contact us.</p>
            
            <p>Thank you for choosing our service. We look forward to seeing you soon!</p>
            
            <p>Best regards,<br>{{ $doctor->admin->f_name }}</p>
        </div>
    </div>
    
</body>

</html>