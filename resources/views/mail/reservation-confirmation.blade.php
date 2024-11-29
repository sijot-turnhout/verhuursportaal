<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .details {
            margin: 20px 0;
            border-collapse: collapse;
            width: 100%;
        }
        .details th, .details td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .details th {
            background-color: #f4f4f4;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #888;
            padding: 10px;
            background: #f4f4f4;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        @media (max-width: 600px) {
            .content {
                padding: 15px;
            }
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="header">
            <h1>Bevestiging van de aanvraag</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <p>Geachte {{ $tenantInformation->firstName }} {{ $tenantInformation->lastName }},</p>
            <p>Bedankt voor uw aanvraag. We gaan er zo snel mogelijk mee aan de slag. Hieronder vind u nog eens alle gegevens:</p>

            <!-- Details Table -->
            <table class="details">
                <tr>
                    <th>Referentie nummer:</th>
                    <td>{{ $leaseInformation->reference_number }}</td>
                </tr>
                </tr>
                    <th>Periode:</th>
                    <td>{{ $leaseInformation->arrival_date->format('d/m/Y') }} - {{ $leaseInformation->departure_date->format('d/m/Y') }}</td>
                </tr>
                </tr>
                    <th>Aantal personen:</th>
                    <td>{{ $leaseInformation->persons }}</td>
                </tr>
                </tr>
                    <th>Organisatie:</th>
                    <td>{{ $leaseInformation->group ?? '-' }}</td>
                </tr>
            </table>

            <!-- Call to Action -->
            {{-- <p>If you need to review your reservation or make changes, please click the button below:</p> --}}
            {{-- <a href="" class="btn">View Reservation</a> --}}

            <p>Indien u nog bijkomende vragen hebt, of info wenst te wijzigen. Aarzel dan niet om ons te contacteren.</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Alle rechten voorbehouden.</p>
            <p><a href="{{ route('welcome') }}" style="color: #4CAF50;">Bezoek onze website</a></p>
        </div>
    </div>
</body>
</html>
