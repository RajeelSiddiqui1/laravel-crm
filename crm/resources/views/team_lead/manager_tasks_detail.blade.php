@extends('layout.app')

@section('content')
<style>
    /* Advanced UI/UX Styles */
    :root {
        --primary-color: #3b82f6;
        --secondary-color: #6b7280;
        --gradient: linear-gradient(135deg, #3b82f6, #a855f7, #ec4899);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --text-color: #1f2937;
    }

    body {
        background: url('https://source.unsplash.com/random/1920x1080/?abstract') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Inter', sans-serif;
        color: var(--text-color);
        overflow-x: hidden;
    }

    .task-container {
        perspective: 1000px;
        padding: 2rem;
    }

    .task-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transform-style: preserve-3d;
        transition: transform 0.5s ease, box-shadow 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    .task-card:hover {
        transform: rotateY(5deg) rotateX(5deg) translateY(-10px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .task-header {
        background: var(--gradient);
        color: #ffffff;
        padding: 2rem;
        border-radius: 16px 16px 0 0;
        position: relative;
        overflow: hidden;
    }

    .task-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0.5;
    }

    .task-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .task-body {
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.95);
    }

    .task-detail {
        display: flex;
        align-items: center;
        margin-bottom: 1.2rem;
        font-size: 1rem;
        transition: transform 0.3s ease;
    }

    .task-detail:hover {
        transform: translateX(5px);
    }

    .task-detail i {
        margin-right: 1rem;
        font-size: 1.2rem;
        color: var(--primary-color);
        transition: color 0.3s ease;
    }

    .task-detail:hover i {
        color: #a855f7;
    }

    .badge-priority, .badge-status {
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .badge-priority:hover, .badge-status:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .badge-priority.high { background: #ff6b6b; color: #ffffff; }
    .badge-priority.medium { background: #facc15; color: #1f2937; }
    .badge-priority.low { background: #22c55e; color: #ffffff; }

    .badge-status.completed { background: #22c55e; color: #ffffff; }
    .badge-status.in_progress { background: #3b82f6; color: #ffffff; }
    .badge-status.pending { background: #facc15; color: #1f2937; }

    .alert-success, .alert-danger {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: #ffffff;
        font-weight: 500;
        padding: 1.2rem;
        margin-bottom: 2rem;
        animation: slideIn 0.5s ease;
    }

    .alert-danger {
        border-color: #ff6b6b;
    }

    .btn-close {
        filter: brightness(1.5);
    }

    .task-actions {
        margin-top: 2.5rem;
        display: flex;
        gap: 1.2rem;
    }

    .btn {
        border-radius: 50px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary {
        background: var(--gradient);
        border: none;
        color: #ffffff;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: left 0.3s ease;
    }

    .btn-primary:hover::before {
        left: 0;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-secondary {
        background: var(--glass-bg);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .progress-timeline {
        margin-top: 2rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        position: relative;
    }

    .timeline-step {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .timeline-step::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        z-index: 1;
    }

    .timeline-step::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: {{ $account->status == 'completed' ? '100%' : ($account->status == 'in_progress' ? '50%' : '10%') }};
        height: 4px;
        background: var(--gradient);
        z-index: 2;
        transition: width 1s ease;
    }

    .timeline-node {
        width: 20px;
        height: 20px;
        background: #e5e7eb;
        border-radius: 50%;
        z-index: 3;
        position: relative;
        transition: background 0.3s ease;
    }

    .timeline-node.active {
        background: var(--primary-color);
        box-shadow: 0 0 10px var(--primary-color);
    }

    .timeline-label {
        position: absolute;
        top: 30px;
        font-size: 0.85rem;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .task-card {
            margin: 1rem;
            transform: none !important;
        }

        .task-title {
            font-size: 1.6rem;
        }

        .task-body {
            padding: 1.5rem;
        }

        .task-actions {
            flex-direction: column;
        }
    }

    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    .slide-in {
        animation: slideIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    #particles-js {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    audio {
        width: 100%;
        margin-top: 0.5rem;
    }
</style>

<div id="particles-js"></div>
<div class="container my-5 task-container">
    <!-- Success Alert -->
    @if (session('success_swal'))
        <div class="alert alert-success alert-dismissible fade show mb-4 slide-in" role="alert">
            {{ session('success_swal') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if (session('error_swal'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 slide-in" role="alert">
            {{ session('error_swal') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            @if ($account)
                <div class="task-card fade-in" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-perspective="1000">
                    <!-- Task Header -->
                    <div class="task-header">
                        <h5 class="task-title">Task: {{ $account->clientname ?? 'Task #' . $account->id }}</h5>
                        <p class="task-description">Details for {{ $accountType }} Account</p>
                    </div>
                    <!-- Task Body -->
                    <div class="task-body">
                        <!-- Common Task Details -->
                        <div class="task-detail" data-tooltip="Client's name">
                            <i class="bi bi-person-circle"></i>
                            <div>
                                <strong>Client Name:</strong> {{ $account->clientname ?? 'N/A' }}
                            </div>
                        </div>
                        @if ($accountType === 'T2' || $accountType === 'HST')
                            <div class="task-detail" data-tooltip="Client's email address">
                                <i class="bi bi-envelope-fill"></i>
                                <div>
                                    <strong>Email:</strong> {{ $account->email ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Client's contact number">
                                <i class="bi bi-telephone-fill"></i>
                                <div>
                                    <strong>Phone:</strong> {{ $account->phone ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Due date">
                                <i class="bi bi-calendar-x-fill"></i>
                                <div>
                                    <strong>Due Date:</strong> {{ $account->due_date ? \Carbon\Carbon::parse($account->due_date)->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Corporation name">
                                <i class="bi bi-building"></i>
                                <div>
                                    <strong>Corporation Name:</strong> {{ $account->corporation_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Corporation number">
                                <i class="bi bi-hash"></i>
                                <div>
                                    <strong>Corporation Number:</strong> {{ $account->corporation_number ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Nature of business">
                                <i class="bi bi-briefcase-fill"></i>
                                <div>
                                    <strong>Nature of Business:</strong> {{ $account->nature_of_business ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Priority level">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <div>
                                    <strong>Priority:</strong>
                                    <span class="badge badge-priority {{ strtolower($account->priority) }}">
                                        {{ ucfirst($account->priority) ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if ($accountType === 'T1')
                            <div class="task-detail" data-tooltip="Period">
                                <i class="bi bi-calendar-month"></i>
                                <div>
                                    <strong>Period:</strong> {{ $account->period ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Driving license">
                                <i class="bi bi-card-text"></i>
                                <div>
                                    <strong>Driving License:</strong> {{ $account->driving_license ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="SIM number">
                                <i class="bi bi-sim"></i>
                                <div>
                                    <strong>SIM Number:</strong> {{ $account->sim_number ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Business name">
                                <i class="bi bi-briefcase"></i>
                                <div>
                                    <strong>Business Name:</strong> {{ $account->business_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Family name">
                                <i class="bi bi-people"></i>
                                <div>
                                    <strong>Family Name:</strong> {{ $account->family_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="task-detail" data-tooltip="Year">
                                <i class="bi bi-calendar"></i>
                                <div>
                                    <strong>Year:</strong> {{ $account->year ?? 'N/A' }}
                                </div>
                            </div>
                        @endif
                        <div class="task-detail" data-tooltip="Assigned department">
                            <i class="bi bi-building"></i>
                            <div>
                                <strong>Department:</strong> {{ $account->department->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="task-detail" data-tooltip="Team lead assigned">
                            <i class="bi bi-person-fill"></i>
                            <div>
                                <strong>Team Lead:</strong> {{ $account->teamLead->name ?? 'N/A' }}
                            </div>
                        </div>
                        @if ($account->attachments)
                            <div class="task-detail" data-tooltip="View attached file">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                                <div>
                                    <strong>Attachment:</strong>
                                    <a href="{{ $account->attachments }}" target="_blank">{{ basename($account->attachments) }}</a>
                                </div>
                            </div>
                        @endif
                        <!-- Progress Timeline -->
                        <div class="progress-timeline">
                            <div class="timeline-step">
                                <div class="timeline-node {{ $account->status == 'pending' ? 'active' : '' }}"></div>
                                <div class="timeline-node {{ $account->status == 'in_progress' ? 'active' : '' }}"></div>
                                <div class="timeline-node {{ $account->status == 'completed' ? 'active' : '' }}"></div>
                            </div>
                            <div class="timeline-label" style="left: 0;">Pending</div>
                            <div class="timeline-label" style="left: 50%; transform: translateX(-50%);">In Progress</div>
                            <div class="timeline-label" style="right: 0;">Completed</div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="task-actions">
                            <button class="btn btn-primary" id="export-pdf">Export as PDF</button>
                           
                            <a href="{{ route('team_lead.manager_tasks') }}" class="btn btn-secondary">Back to Tasks</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger text-center slide-in">
                    Task not found.
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Advanced Interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Initialize Particles.js for background effect
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#ffffff' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: '#ffffff', opacity: 0.4, width: 1 },
                move: { enable: true, speed: 6, direction: 'none', random: false, straight: false }
            },
            interactivity: {
                detect_on: 'canvas',
                events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: true, mode: 'push' } },
                modes: { repulse: { distance: 100 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });

        // Custom Tooltip Implementation
        document.querySelectorAll('[data-tooltip]').forEach(elem => {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.innerText = elem.dataset.tooltip;
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0, 0, 0, 0.8);
                color: #fff;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-size: 0.85rem;
                opacity: 0;
                transition: opacity 0.3s ease, transform 0.3s ease;
                transform: translateY(10px);
                pointer-events: none;
                z-index: 1000;
            `;
            document.body.appendChild(tooltip);

            elem.addEventListener('mouseenter', (e) => {
                tooltip.style.opacity = '1';
                tooltip.style.transform = 'translateY(0)';
                const rect = elem.getBoundingClientRect();
                tooltip.style.top = `${rect.top - 40}px`;
                tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
            });

            elem.addEventListener('mouseleave', () => {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'translateY(10px)';
            });
        });

        // PDF Export Functionality
        document.getElementById('export-pdf')?.addEventListener('click', () => {
            const element = document.querySelector('.task-card');
            const opt = {
                margin: 0.5,
                filename: `Task_${{ $account->clientname ?? $account->id }}_${{ $accountType }}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(element).set(opt).save();
        });

        // Smooth Scroll for Accessibility
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelector(anchor.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Keyboard Navigation Support
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    btn.click();
                }
            });
        });

        // Dynamic Timeline Animation on Load
        window.addEventListener('load', () => {
            const timeline = document.querySelector('.timeline-step::after');
            timeline.style.transition = 'width 1.5s ease-in-out';
        });
    </script>
@endsection