@php
use App\Models\State;
use App\Models\District;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Contract</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
            color: #1a1a1a;
            background-color: #ffffff;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #3b82f6, #059669);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #059669);
        }

        .logo {
            max-width: 180px;
            margin-bottom: 20px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .title {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section {
            margin-bottom: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #059669);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 20px;
        }

        .info-item {
            margin-bottom: 16px;
        }

        .info-label {
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 6px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #111827;
            font-weight: 400;
            font-size: 16px;
            line-height: 1.5;
        }

        .terms-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .terms-list li {
            margin-bottom: 16px;
            padding-left: 28px;
            position: relative;
            line-height: 1.6;
        }

        .terms-list li:before {
            content: "•";
            color: #3b82f6;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 20px;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #059669);
        }

        .contract-id {
            font-weight: 500;
            color: #4b5563;
            margin-top: 8px;
        }

        .price {
            font-size: 24px;
            font-weight: 600;
            color: #059669;
            background: linear-gradient(90deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 4px 0;
        }

        .location {
            color: #4b5563;
            font-weight: 500;
        }

        .party-section {
            background-color: #f9fafb;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
        }

        .party-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .project-description {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
        }

        .project-description .info-label {
            margin-bottom: 12px;
        }

        .project-description .info-value {
            white-space: pre-line;
        }

        .date-time {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('storage/logoV/logo.svg') }}" alt="Logo" class="logo">
        <div class="title">JOB CONTRACT AGREEMENT</div>
        <div class="subtitle">Professional Service Agreement</div>
    </div>

    <div class="section">
        <div class="section-title">1. Parties</div>
        <div class="info-grid">
            <div class="party-section">
                <div class="party-title">Client Information</div>
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $client->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $client->email }}</div>
                </div>
            </div>

            <div class="party-section">
                <div class="party-title">Freelancer Information</div>
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $freelancer->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $freelancer->email }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">2. Project Details</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Project Title</div>
                <div class="info-value">{{ $job->title }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Location</div>
                <div class="info-value location">
                    @if($job->is_online)
                        Online Job
                    @else
                        {{ optional(State::find($job->state_id))->name }}, {{ optional(District::find($job->district_id))->name }}
                    @endif
                </div>
            </div>

        </div>
        <div class="project-description">
            <div class="info-label">Project Description</div>
            <div class="info-value">{{ $job->description }}</div>
        </div>
    </div>


    <div class="section">
        <div class="section-title">3. Freelancer Cover Letter</div>
        <div class="info-label">Agreed Price</div>
        <div class="info-value price">MYR {{ number_format($application->proposed_price, 2) }}</div>
        <div class="project-description">

                <div class="info-label">Cover Letter</div>
            <div class="info-value">{!! $application->cover_letter !!}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">4. Terms and Conditions</div>
        <ul class="terms-list">
            <li>The Freelancer agrees to complete the work as specified in the project description.</li>
            <li>The Client agrees to pay the agreed amount upon satisfactory completion of the work.</li>
            <li>Both parties agree to maintain confidentiality regarding project details.</li>
            <li>Any disputes will be resolved through mutual discussion and agreement.</li>
            <li>The project timeline will be determined by mutual agreement between both parties.</li>
            <li>Any modifications to the project scope must be agreed upon in writing by both parties.</li>
            <li>Both parties agree to maintain professional communication throughout the project duration.</li>
            <li>The Freelancer will provide regular updates on the project progress.</li>
        </ul>
    </div>

    <div class="footer">
        <p class="date-time">This contract was generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p class="contract-id">Contract ID: {{ $job->id }}</p>
    </div>
</body>
</html>
