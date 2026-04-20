@extends('layouts.home_page.master')

@section('content')
    <!-- Hero section -->
    <section class="hero">
        <h1 class="hero__title">
            Smarter school management that helps you spend less time <span class="text-purple">managing</span> and more
            time <span class="text-purple">educating</span>.
        </h1>
        <div class="hero__badge">
            <span class="hero__badge-icon">
                <img src="{{ asset('assets/icons/home-icons/Isolation_Mode.svg') }}" alt="Badge Icon"
                    class="hero__badge-icon-image" />
            </span>
            <span class="hero__badge-text">Built by Educators, for Educators</span>
            <span class="hero__badge-icon">
                <img src="{{ asset('assets/icons/home-icons/Isolation_Mode-2.svg') }}" alt="Badge Icon"
                    class="hero__badge-icon-image" />
            </span>
        </div>
        <p class="hero__description">
            A comprehensive, cloud-based platform designed to streamline school operations, enhance communication, and
            deliver a seamless experience for administrators, teachers, students, and parents.
        </p>
        <div class="hero__ctas">
            <button class="btn btn--primary">
                <a href="{{route('schedule.demo')}}">
                    Schedule a Demo
                </a>
            </button>
            <button class="btn btn--secondary">Watch a Demo</button>
        </div>
    </section>

    <!-- Features section -->
    <section class="features">
        <div class="trust-bar">
            <div class="trust-bar__content">
                <p class="trust-bar__text">Chosen by educational institutions globally</p>
                <div class="trust-bar__logos">
                    <div class="trust-bar__logos-track">
                        <span class="trust-bar__logo-item">
                            <img src="{{ asset('assets/icons/home-icons/voiceflow.svg') }}" alt="Voiceflow"
                                class="trust-bar__logo-image" />
                        </span>
                        <span class="trust-bar__logo-item">
                            <img src="{{ asset('assets/icons/home-icons/dropbox.svg') }}" alt="Dropbox"
                                class="trust-bar__logo-image" />
                        </span>
                        <span class="trust-bar__logo-item">
                            <img src="{{ asset('assets/icons/home-icons/discord.svg') }}" alt="Discord"
                                class="trust-bar__logo-image" />
                        </span>
                        <span class="trust-bar__logo-item">
                            <img src="{{ asset('assets/icons/home-icons/twilio.svg') }}" alt="Twilio"
                                class="trust-bar__logo-image" />
                        </span>
                        <span class="trust-bar__logo-item">
                            <img src="{{ asset('assets/icons/home-icons/asana.svg') }}" alt="Asana"
                                class="trust-bar__logo-image" />
                        </span>
                        <span class="duplicate">
                            <!-- Duplicate set for infinite scroll loop -->
                            <span class="trust-bar__logo-item">
                                <img src="{{ asset('assets/icons/home-icons/voiceflow.svg') }}" alt="Voiceflow"
                                    class="trust-bar__logo-image" />
                            </span>
                            <span class="trust-bar__logo-item">
                                <img src="{{ asset('assets/icons/home-icons/dropbox.svg') }}" alt="Dropbox"
                                    class="trust-bar__logo-image" />
                            </span>
                            <span class="trust-bar__logo-item">
                                <img src="{{ asset('assets/icons/home-icons/discord.svg') }}" alt="Discord"
                                    class="trust-bar__logo-image" />
                            </span>
                            <span class="trust-bar__logo-item">
                                <img src="{{ asset('assets/icons/home-icons/twilio.svg') }}" alt="Twilio"
                                    class="trust-bar__logo-image" />
                            </span>
                            <span class="trust-bar__logo-item">
                                <img src="{{ asset('assets/icons/home-icons/asana.svg') }}" alt="Asana"
                                    class="trust-bar__logo-image" />
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="features__container">
            <div class="features__content-left">
                <div class="badge-how">How We Helps You</div>
                <h2 class="features__title">Why <span class="text-purple">ShikshaEMS</span>?</h2>
                <p class="features__description">
                    ShikshaEMS helps schools run smarter by automating daily tasks, improving communication, and
                    providing real-time insights. It reduces manual work, keeps everyone connected, and ensures secure,
                    error-free operations across the entire school ecosystem.
                </p>

                <div class="features__image-placeholder">
                    <div class="features__purple-box"></div>
                    <div class="features__white-box"></div>
                </div>
            </div>

            <div class="features__content-right">
                <div class="feature-card">
                    <div class="feature-item">
                        <div class="feature-item__icon icon--purple">
                            <img src="{{ asset('assets/icons/home-icons/solar.svg') }}" alt="Solar"
                                class="feature-item__icon-image" />
                        </div>
                        <div class="feature-item__text">
                            <h3 class="feature-item__title">Complete Automation</h3>
                            <p class="feature-item__description">Reduce paperwork and manual errors with smart digital
                                workflows.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-item__icon icon--purple">
                            <img src="{{ asset('assets/icons/home-icons/speech.svg') }}" alt="Speech"
                                class="feature-item__icon-image" />
                        </div>
                        <div class="feature-item__text">
                            <h3 class="feature-item__title">Real-Time Communication</h3>
                            <p class="feature-item__description">Instant updates for parents, teachers, and students
                                through app notifications and messages.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-item__icon icon--purple">
                            <img src="{{ asset('assets/icons/home-icons/data-analytics.svg') }}" alt="Data Analytics"
                                class="feature-item__icon-image" />
                        </div>
                        <div class="feature-item__text">
                            <h3 class="feature-item__title">Powerful Analytics</h3>
                            <p class="feature-item__description">Track performance, attendance, fees, and overall
                                operations with easy-to-understand dashboards.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-item__icon icon--purple">
                            <img src="{{ asset('assets/icons/home-icons/server.svg') }}" alt="Server"
                                class="feature-item__icon-image" />
                        </div>
                        <div class="feature-item__text">
                            <h3 class="feature-item__title">Safe & Secure</h3>
                            <p class="feature-item__description">Cloud-based system with secure login and role-based
                                access for all users.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Offer Section -->
    <section class="offer">
        <div class="offer__header">
            <div class="badge-what">What We Offer</div>
            <h2 class="offer__title">Everything your institution needs—simple, smart, and built for modern education.
            </h2>
        </div>

        <div class="offer__grid">
            <!-- Column 1 -->
            <div class="offer__column">
                <div class="offer-card offer-card--light">
                    <div class="offer-card__badge offer-card__badge--light">One platform for everyone</div>
                    <h3 class="offer-card__title">Multi-user access, dedicated portal and app</h3>
                    <p class="offer-card__description">Dedicated dashboards for admin, teachers, students, and parents -
                        ensuring seamless access and role-based control.</p>
                </div>
                <div class="offer-card offer-card--light">
                    <div class="offer-card__badge offer-card__badge--light">Stay connected, always</div>
                    <h3 class="offer-card__title">Seamless communication and notifications</h3>
                    <p class="offer-card__description">Send instant updates, announcements, and alerts to parents,
                        students, and staff through integrated communication tools.</p>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="offer__column">
                <div class="offer-card offer-card--purple">
                    <div class="offer-card__badge offer-card__badge--purple">All academics. One system</div>
                    <h3 class="offer-card__title">Complete student and academic management</h3>
                    <p class="offer-card__description">Manage admissions, student records, attendance, timetables,
                        exams, and results, all from a single, organised platform.</p>
                    <div class="offer-card__image-placeholder">

                    </div>
                </div>
            </div>

            <!-- Column 3 -->
            <div class="offer__column">
                <div class="offer-card offer-card--light">
                    <div class="offer-card__badge offer-card__badge--light">Flexible payments, zero confusion</div>
                    <h3 class="offer-card__title">Smart fee and payment system</h3>
                    <p class="offer-card__description">Handle fee collection with ease, support partial payments,
                        advance payments, dues tracking, and automatic balance adjustments.</p>
                </div>
                <div class="offer-card offer-card--light">
                    <div class="offer-card__badge offer-card__badge--light">Run smarter with data</div>
                    <h3 class="offer-card__title">Insights, reports and administration control</h3>
                    <p class="offer-card__description">Manage staff and operations while accessing powerful reports and
                        analytics to make informed decisions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comprehensive Features Section -->
    <section class="comp-features">
        <div class="comp-features__header">
            <div class="badge-comp">One Platform. Every Need.</div>
            <h2 class="comp-features__title">Comprehensive features for every educational institution management need.
            </h2>
        </div>
        <div class="comp-features__grid">
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Admissions management</h3>
                <p class="comp-feature-card__description">Simplify student enrollment with automated processes and
                    credential generation.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Classes & subjects management</h3>
                <p class="comp-feature-card__description">Easily create and assign classes, subjects, and academic
                    structure.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>
            <div class="comp-feature-card">
                <div class="comp-feature-card__image"></div>
                <h3 class="comp-feature-card__title">Exams and Tests result</h3>
                <p class="comp-feature-card__description">Create exams, manage marks, and generate automated performance
                    reports.</p>
            </div>

        </div>
    </section>

    <hr />

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="testimonials__header">
            <div class="badge-testimonials">Testimonials</div>
            <h2 class="testimonials__title">What People <span class="text-purple">Say About Us</span></h2>
        </div>

        <div class="testimonials__container">
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <p class="testimonial-card__text">
                                ShikshaEMS has completely transformed how we manage our institution. From attendance to
                                fee collection, everything is now streamlined and efficient. It has significantly
                                reduced our manual workload.
                            </p>
                            <div class="testimonial-card__user">
                                <div class="testimonial-card__avatar"></div>
                                <div class="testimonial-card__info">
                                    <h4 class="testimonial-card__name">James Slocum</h4>
                                    <p class="testimonial-card__position">Chairman, DS Gurukul International School</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <p class="testimonial-card__text">
                                Since implementing ShikshaEMS, our daily operations have become more organized and
                                hassle-free. Communication with parents and staff is now seamless and effective.
                            </p>
                            <div class="testimonial-card__user">
                                <div class="testimonial-card__avatar"></div>
                                <div class="testimonial-card__info">
                                    <h4 class="testimonial-card__name">CPS Chaudhary</h4>
                                    <p class="testimonial-card__position">Principle, Bright Futures International</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <p class="testimonial-card__text">
                                Since implementing ShikshaEMS, our daily operations have become more organized and
                                hassle-free. Communication with parents and staff is now seamless and effective.
                            </p>
                            <div class="testimonial-card__user">
                                <div class="testimonial-card__avatar"></div>
                                <div class="testimonial-card__info">
                                    <h4 class="testimonial-card__name">CPS Chaudhary</h4>
                                    <p class="testimonial-card__position">Principle, Bright Futures International</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-button-prev testimonial-prev">
                <img src="{{ asset('assets/icons/home-icons/arrow-right.svg') }}" alt="Next" />
            </div>
            <div class="swiper-button-next testimonial-next">
                <img src="{{ asset('assets/icons/home-icons/arrow-left.svg') }}" alt="Prev" />
            </div>
        </div>
    </section>
    <!-- contact section -->
    <section class="contact">
        <div class="contact__container">
            <div class="contact__content-left">
                <div class="badge-contact">Schedule a Walkthrough</div>
                <h2 class="contact__title">
                    Discover how <span class="text-purple">ShikshaEMS streamlines</span> your entire
                    <span class="text-purple">
                        school management.
                    </span>
                </h2>
            </div>

            <div class="contact__content-right">
                <p class="contact__note">No spam. No obligations. Just a quick walkthrough to help you get started.</p>
                <form class="contact__form">
                    <div class="form-group">
                        <label for="name">Full Name<span class="required">*</span></label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Your Role<span class="required">*</span></label>
                        <input type="text" id="role" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone<span class="required">*</span></label>
                        <input type="tel" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="required">*</span></label>
                        <input type="email" id="email" required>
                    </div>
                    <button type="submit" class="btn-request">
                        Request a Demo
                        <img src="{{ asset('assets/icons/home-icons/Frame 1.svg') }}" alt="Request a Demo" />
                    </button>
                </form>
            </div>
        </div>
    </section>
    <!-- Demo section -->
    <section class="demo-cta">
        <div class="demo-cta__container">
            <h2 class="demo-cta__title">
                Get a guided walkthrough and discover how it transforms your school management.
            </h2>
            <div class="demo-cta__actions">
                <a href="{{route('schedule.demo')}}" class="btn btn-demo-yellow">Schedule Demo</a>
                <p class="demo-cta__subtitle">See ShikshaEMS in action</p>
            </div>
        </div>
    </section>
@endsection
