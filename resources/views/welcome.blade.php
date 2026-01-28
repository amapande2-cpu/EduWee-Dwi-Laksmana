<!-- resources/views/welcome-new.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduWee E-Learning</title>
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/logo_eduwee.png') }}" alt="its should be logo" class="logo-img">
                <span>EduWee</span>
            </a>
            
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>

            <div class="nav-actions">
                <button class="btn-login" onclick="showLoginModal()">Log In</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Interactive E-Learning Platform</h1>
                <p class="hero-subtitle">Dive into hands-on learning! Master coding, explore AI, and build robotics projects through interactive challenges and real-world applications</p>
                <button class="btn-cta" onclick="showLoginModal()">Start Learning Now</button>
            </div>

            <div class="hero-illustration">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="skill-icons" aria-hidden="true">
                            <div class="skill-icon skill-code" title="Coding">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 9L5 12L8 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 9L19 12L16 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 7L10 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="skill-icon skill-ai" title="AI">
                                <!-- AI chip icon -->
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="7" y="7" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2" />
                                    <path d="M9 1v3M15 1v3M9 20v3M15 20v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M1 9h3M1 15h3M20 9h3M20 15h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M10 10h4v4h-4z" stroke="currentColor" stroke-width="2" />
                                    <path d="M12 10v4M10 12h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="skill-icon skill-robot" title="Robotics">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 3h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M12 3v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <rect x="5" y="7" width="14" height="12" rx="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="M9 12h.01M15 12h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    <path d="M9 15c1 1 2 1.5 3 1.5s2-.5 3-1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="books-stack">
                    <div class="book book-1"></div>
                    <div class="book book-2"></div>
                    <div class="book book-3"></div>
                </div>
                <div class="floating-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <rect x="10" y="12" width="8" height="24" rx="2" fill="#4A90E2"/>
                        <rect x="20" y="8" width="8" height="28" rx="2" fill="#64B5F6"/>
                        <rect x="30" y="16" width="8" height="20" rx="2" fill="#81C4F9"/>
                    </svg>
                </div>
                <h3 class="feature-title">💻 Coding Projects</h3>
                <p class="feature-description">Build real applications and solve programming challenges interactively</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="16" r="6" fill="#4A90E2"/>
                        <path d="M24 26c-8 0-12 4-12 8h24c0-4-4-8-12-8z" fill="#64B5F6"/>
                    </svg>
                </div>
                <h3 class="feature-title">🤖 AI Exploration</h3>
                <p class="feature-description">Learn artificial intelligence concepts through hands-on experiments</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="12" stroke="#4A90E2" stroke-width="3"/>
                        <path d="M24 16v8l6 4" stroke="#64B5F6" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3 class="feature-title">🦾 Robotics Building</h3>
                <p class="feature-description">Design and program robots with step-by-step guidance</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-container">
            <div class="about-content">
                <h2 class="section-title">About EduWee</h2>
                <p class="about-description">
                    EduWee is an innovative e-learning platform designed to make education engaging, interactive, and accessible to everyone.
                    Our mission is to revolutionize the way students learn by combining cutting-edge technology with proven educational methodologies.
                </p>
                <div class="about-stats">
                    <div class="stat">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Teachers</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">Materials</div>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <div class="about-illustration">
                    <div class="learning-icons">
                        <div class="icon-item">📚</div>
                        <div class="icon-item">💻</div>
                        <div class="icon-item">🤖</div>
                        <div class="icon-item">🦾</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="contact-container">
            <h2 class="section-title">Get In Touch</h2>
            <p class="contact-description">Have questions? We'd love to hear from you!</p>

            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <h3>Phone</h3>
                    <p>
                        <a class="contact-link" href="tel:+6282146678853">+62 821-4667-8853</a>
                        <span class="contact-sep" aria-hidden="true"></span>
                        <a class="contact-link" href="https://api.whatsapp.com/send?phone=+6282146678853" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                    </p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <h3>Email</h3>
                    <p><a class="contact-link" href="mailto:madedwilaksmana@gmail.com">madedwilaksmana@gmail.com</a></p>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">📱</div>
                    <h3>Instagram</h3>
                    <p><a class="contact-link" href="https://instagram.com/dwilaksmana__" target="_blank" rel="noopener noreferrer">@dwilaksmana__</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-overlay" onclick="closeLoginModal()"></div>
        <div class="modal-content">
            <button class="modal-close" onclick="closeLoginModal()">×</button>
            
            <div class="modal-header">
                <h2>Choose Login Type</h2>
                <p>Select your role to continue</p>
            </div>

            <div class="login-options">
                <a href="{{ url('/student/login') }}" class="login-option">
                    <div class="option-icon student-icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <circle cx="24" cy="16" r="8" fill="#4A90E2"/>
                            <path d="M24 28c-10 0-14 5-14 10h28c0-5-4-10-14-10z" fill="#64B5F6"/>
                        </svg>
                    </div>
                    <h3>I'm a Student</h3>
                    <p>Join classes and learn</p>
                    <span class="option-arrow">→</span>
                </a>

                <a href="{{ url('/teacher/login') }}" class="login-option">
                    <div class="option-icon teacher-icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <circle cx="24" cy="16" r="8" fill="#64B5F6"/>
                            <path d="M24 28c-10 0-14 5-14 10h28c0-5-4-10-14-10z" fill="#81C4F9"/>
                            <rect x="20" y="10" width="8" height="4" fill="#4A90E2"/>
                        </svg>
                    </div>
                    <h3>I'm a Teacher</h3>
                    <p>Create and manage classes</p>
                    <span class="option-arrow">→</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function showLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
            }
        });
    </script>
</body>
</html>
